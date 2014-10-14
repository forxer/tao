<?php
namespace Tao\Routing;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class RouterServiceProvider implements ServiceProviderInterface
{
	public function register(Container $app)
	{
		$app['router'] = function() use ($app)  {

			$loader = new $app['routing.loader_class'](
				new $app['routing.locator_class']($app['routing.resources_dirs'])
			);

			$requestContext = (new $app['routing.request_context_class'])->fromRequest($app['request']);

			$router =  new $app['routing.router_class'](
				$loader,
				$app['routing.resource_name'],
				[
					'debug' 	=> $app['debug'],
					'cache_dir' => $app['routing.cache_dir'].'/Router',

					'generator_class' => $app['routing.generator_class'],
					'generator_base_class' => $app['routing.generator_base_class'],
					'generator_dumper_class' => $app['routing.generator_dumper_class'],
					'generator_cache_class' => $app['routing.generator_cache_class'] ?: $app['app_id'] . 'UrlGenerator',

					'matcher_class' => $app['routing.matcher_class'],
					'matcher_base_class' => $app['routing.matcher_base_class'],
					'matcher_dumper_class' => $app['routing.matcher_dumper_class'],
					'matcher_cache_class' => $app['routing.matcher_cache_class'] ?: $app['app_id'] . 'UrlMatcher',

					'resource_type' => $app['routing.resource_type'],
					'strict_requirements' => $app['routing.strict_requirements']
				],
				$requestContext
			);

			return $router;
		};

		if ($app['templating.load_default_helpers']) {
			$app['templating']->set(new TemplatingHelper($app['router']->getGenerator()));
		}

		$app['controllerResolver'] = function() use ($app) {

			if (!$controller = $app['request']->attributes->get('_controller')) {
				throw new \RuntimeException('Unable to look for the controller as the "controller" parameter is missing');
			}

			if (false === strpos($controller, '::')) {
				throw new \RuntimeException(sprintf('Unable to find controller "%s".', $controller));
			}

			list($class, $method) = explode('::', $controller, 2);

			$namespacedClass = $class;
			if ($app['routing.controllers_namespace']) {
				$namespacedClass = $app['routing.controllers_namespace'] . '\\' . $class;
			}

			if (!class_exists($namespacedClass)) {
				throw new \RuntimeException(sprintf('Class "%s" does not exist.', $namespacedClass));
			}

			$callable = [
				new $namespacedClass($app),
				$method
			];

			if (!is_callable($callable)) {
				throw new \RuntimeException(sprintf('The controller for URI "%s" is not callable.', $app['request']->getPathInfo()));
			}

			$app['request']->attributes->set('controller_class', $class);
			$app['request']->attributes->set('controller_method', $method);

			return call_user_func($callable);
		};
	}
}
