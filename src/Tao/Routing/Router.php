<?php
namespace Tao\Routing;

use InvalidArgumentException;
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
				'cache_dir' => $app['dir.cache'].'/router',
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
		if (false !== ($callable = $this->getController($this->app['request']))) {
			return call_user_func($callable);
		}
	}

	/**
	 * Returns the Controller instance associated with a Request.
	 *
	 * This method looks for a '_controller' request attribute that represents
	 * the controller name (a string like ClassName::MethodName).
	 *
	 * @param Request $request A Request instance
	 *
	 * @return mixed|Boolean A PHP callable representing the Controller,
	 *         or false if this resolver is not able to determine the controller
	 *
	 * @throws \InvalidArgumentException|\LogicException If the controller can't be found @api
	 */
	public function getController(Request $request)
	{
		if (!$controller = $request->attributes->get('_controller'))
		{
			if (null !== $this->logger) {
				$this->logger->warning('Unable to look for the controller as the "controller" parameter is missing');
			}

			return false;
		}

		if (is_array($controller) || (is_object($controller) && method_exists($controller, '__invoke'))) {
			return $controller;
		}

		if (false === strpos($controller, ':'))
		{
			if (method_exists($controller, '__invoke')) {
				return new $controller();
			}
			elseif (function_exists($controller)) {
				return $controller;
			}
		}

		$callable = $this->createController($controller);

		if (!is_callable($callable)) {
			throw new InvalidArgumentException(sprintf('The controller for URI "%s" is not callable.', $request->getPathInfo()));
		}

		return $callable;
	}

	/**
	 * Returns a callable for the given controller.
	 *
	 * @param string $controller A Controller string
	 *
	 * @return mixed A PHP callable
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function createController($controller)
	{
		if (false === strpos($controller, '::')) {
			throw new InvalidArgumentException(sprintf('Unable to find controller "%s".', $controller));
		}

		list($class, $method) = explode('::', $controller, 2);

		$namespacedClass = 'Application\\Controllers\\' . $class;

		if (!class_exists($namespacedClass)) {
			throw new InvalidArgumentException(sprintf('Class "%s" does not exist.', $namespacedClass));
		}

		$this->app['controller'] = $class;
		$this->app['action'] = $method;

		return [
			new $namespacedClass($this->app),
			$method
		];
	}
}
