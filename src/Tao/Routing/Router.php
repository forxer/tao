<?php
namespace Tao\Routing;

use RuntimeException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router as BaseRouter;
use Tao\Application;

class Router extends BaseRouter
{
	protected $app;

	/**
	 * Router constructor.
	 *
	 * @param Application $app
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;

		parent::__construct(
			new YamlFileLoader(
				new FileLocator($app['dir.config'])
			),
			'routes.yml',
			array(
				'cache_dir' => $app['dir.cache'].'/Router',
				'debug' 	=> $app['debug']
			),
			(new RequestContext())->fromRequest($app['request'])
		);
	}

	/**
	 * Invoque le controller de la route trouvée.
	 *
	 * @return void
	 */
	public function callController()
	{
		if (!$controller = $this->app['request']->attributes->get('_controller')) {
			throw new RuntimeException('Unable to look for the controller as the "controller" parameter is missing');
		}

		if (false === strpos($controller, '::')) {
			throw new RuntimeException(sprintf('Unable to find controller "%s".', $controller));
		}

		list($class, $method) = explode('::', $controller, 2);

		$namespacedClass = $this->app['namespace.controllers'] . '\\' . $class;

		if (!class_exists($namespacedClass)) {
			throw new RuntimeException(sprintf('Class "%s" does not exist.', $namespacedClass));
		}

		$this->app['request']->attributes->set('controller_class', $class);
		$this->app['request']->attributes->set('controller_method', $method);

		$callable = [
			new $namespacedClass($this->app),
			$method
		];

		if (!is_callable($callable)) {
			throw new RuntimeException(sprintf('The controller for URI "%s" is not callable.', $this->app['request']->getPathInfo()));
		}

		return call_user_func($callable);
	}
}
