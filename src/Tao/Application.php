<?php
namespace Tao;

use Monolog\ErrorHandler;
use Patchwork\Utf8\Bootup as Utf8Bootup;
use Pimple\Container;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Tao\Controller\Controller;
use Tao\Http\HttpServiceProvider;
use Tao\Logger\LoggerServiceProvider;
use Tao\Messages\MessagesServiceProvider;
use Tao\Routing\RouterServiceProvider;
use Tao\Templating\TemplatingServiceProvider;
use Whoops\Run as WhoopsRun;
use Whoops\Handler\PrettyPageHandler as WhoopsHandler;

abstract class Application extends Container
{
	protected $appDir;

	protected $startTime;

	protected static $models;

	/**
	 * Application constructor.
	 *
	 * @param object $loader The autoloader instance.
	 * @param array $config The configuration of the application.
	 */
	public function __construct($loader, array $config = [], $appDir = null)
	{
		# Register start time
		$this->startTime = microtime(true);

		$this->appDir = $appDir;

		# Call container constructor
		parent::__construct($config + $this->getBaseConfiguration());

		# Register core services providers
		$this->register(new HttpServiceProvider());
		$this->register(new LoggerServiceProvider());
		$this->register(new MessagesServiceProvider());
		$this->register(new RouterServiceProvider());
		$this->register(new TemplatingServiceProvider());

		# Enables the portablity layer and configures PHP for UTF-8
		Utf8Bootup::initAll();

		# Redirects to an UTF-8 encoded URL if it's not already the case
		Utf8Bootup::filterRequestUri();

		# Normalizes HTTP inputs to UTF-8 NFC
		Utf8Bootup::filterRequestInputs();

		# Print errors in debug mode
		if ($this['debug'])
		{
			$whoops = new WhoopsRun;
			$whoops->pushHandler(new WhoopsHandler);
			$whoops->register();
		}
		# otherwise log them
		else {
			ErrorHandler::register($this['phpLogger']);
		}
	}

	/**
	 * Run the application.
	 *
	 */
	public function run()
	{
		try
		{
			$this['request']->attributes->add(
				$this['router']->matchRequest($this['request'])
			);

			$response = $this['controllerResolver'];
		}
		catch (ResourceNotFoundException $e)
		{
			$response = (new Controller($this))->serve404();
		}
		catch (\Exception $e)
		{
			$response = new Response();
			$response->headers->set('Content-Type', 'text/plain');
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			$response->setContent($e->getMessage());
		}

		$response->prepare($this['request']);

		$response->send();
	}

	/**
	 * Return the instance of specified model.
	 *
	 * @param string $sModel
	 * @return \Tao\Database\Model
	 */
	public function getModel($sModel)
	{
		$namespacedClass = $this['database.models_namespace'] . '\\' . $sModel;

		if (!isset(static::$models[$sModel])) {
			static::$models[$sModel] = new $namespacedClass($this);
		}

		return static::$models[$sModel];
	}

	/**
	 * Return the application execution time.
	 *
	 * @return string
	 */
	public function getExecutionTime()
	{
		return microtime(true) - $this->startTime;
	}

	/**
	 * Return the application memory usage.
	 *
	 * @return string
	 */
	public function getMemoryUsage()
	{
		$memoryUsage = memory_get_usage();

		$unit = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

		return @round($memoryUsage/pow(1024, ($i=floor(log($memoryUsage, 1024))) ), 2).' '.$unit[$i];
	}

	private function getBaseConfiguration()
	{
		return require __DIR__ . '/BaseConfiguration.php';
	}
}
