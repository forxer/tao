<?php
namespace Tao;

use Monolog\ErrorHandler;
use Patchwork\Utf8\Bootup as Utf8Bootup;
use Pimple\Container;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Tao\Database\DatabaseServiceProvider;
use Tao\Http\HttpServiceProvider;
use Tao\Logger\LoggerServiceProvider;
use Tao\Messages\MessagesServiceProvider;
use Tao\Routing\RouterServiceProvider;
use Tao\Templating\TemplatingServiceProvider;
use Tao\Triggers\TriggersServiceProvider;
use Whoops\Run as WhoopsRun;
use Whoops\Handler\PrettyPageHandler as WhoopsHandler;

class Application extends Container
{
	protected $startTime;

	protected static $models;

	/**
	 * Application constructor.
	 *
	 * @param object $loader The autoloader instance.
	 * @param array $config The configuration of the application.
	 */
	public function __construct($loader, array $config = [])
	{
		# Register start time
		$this->startTime = microtime(true);

		# Call container constructor
		parent::__construct();

		# Register core services providers
		$this->registerCoreServiceProvider();

		# Enables the portablity layer and configures PHP for UTF-8
		Utf8Bootup::initAll();

		# Redirects to an UTF-8 encoded URL if it's not already the case
		Utf8Bootup::filterRequestUri();

		# Normalizes HTTP inputs to UTF-8 NFC
		Utf8Bootup::filterRequestInputs();

		# Register configuration data
		$this->registerConfiguration($config);

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
	 * @todo need to review and rewrite all of this method
	 */
	public function run()
	{
	//	try
	//	{
			$this['request']->attributes->add(
				$this['router']->matchRequest($this['request'])
			);

			$response = $this['router']->callController();

	//		if (null === $response || false === $response)
	//		{
	//			$response = new Response();
	//			$response->headers->set('Content-Type', 'text/plain');
	//			$response->setStatusCode(Response::HTTP_NOT_IMPLEMENTED);
	//			$response->setContent('Unable to load controller ' . $this['request']->attributes->get('controller'));
	//		}
	//	}
	//	catch (ResourceNotFoundException $e)
	//	{
	//		$response = (new Controller($this))->serve404();
	//	}
	//	catch (\Exception $e)
	//	{
	//		$response = new Response();
	//		$response->headers->set('Content-Type', 'text/plain');
	//		$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
	//		$response->setContent($e->getMessage());
	//	}

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
		$namespacedClass = '\\Application\\Models\\' . $sModel;

		if (!isset(static::$models[$sModel])) {
			static::$models[$sModel] = new $namespacedClass($this);
		}

		return static::$models[$sModel];
	}

	/**
	 * Return Tao version.
	 *
	 * @return string
	 */
	public function getVersion()
	{
		return static::VERSION;
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

	/**
	 * Register core service provider.
	 *
	 * @return void
	 */
	protected function registerCoreServiceProvider()
	{
		$this->register(new DatabaseServiceProvider());
		$this->register(new HttpServiceProvider());
		$this->register(new LoggerServiceProvider());
		$this->register(new MessagesServiceProvider());
		$this->register(new RouterServiceProvider());
		$this->register(new TemplatingServiceProvider());
		$this->register(new TriggersServiceProvider());
	}

	/**
	 * Format and register configuration data into the container.
	 *
	 * @param array $values
	 */
	protected function registerConfiguration(array $values = [])
	{
		# First pass : format special configuration values
		foreach ($values as $key => $value)
		{
			# Special case for absolute directory paths
			if (strpos($key, 'dir.') === 0)
			{
				$values[$key] = realpath($value);

				continue;
			}

			# Special case for database connexion configuration
			if (strpos($key, 'db.') === 0)
			{
				$values['db_params'][substr($key, 3)] = $value;

				unset($values[$key]);

				continue;
			}
		}

		# Second pass : add config values to the container
		foreach ($values as $key => $value) {
			$this[$key] = $value;
		}

		# Set the path to the Tao directory
		$this['dir.tao'] = __DIR__;
	}
}
