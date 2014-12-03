<?php
namespace Tao\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class RouterServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['requestContext'] = function() use ($app)  {

            $requestContext = new $app['class']['routing.request_context'];
            $requestContext->fromRequest($app['request']);

            return $requestContext;
        };

        $app['routing.locator'] = function() use ($app)  {
            return new $app['class']['routing.locator']($app['routing.resources_dirs']);
        };

        $app['route.loader'] = function() use ($app)  {
            return new $app['class']['routing.loader'](
                $app['routing.locator']
            );
        };

        $app['router'] = function() use ($app)  {

            $router =  new $app['class']['routing.router'](
                $app['route.loader'],
                $app['routing.resource_name'],
                [
                    'debug' 	=> $app['debug'],
                    'cache_dir' => $app['routing.cache_dir'],

                    'generator_class' => $app['class']['routing.generator'],
                    'generator_base_class' => $app['class']['routing.generator_base'],
                    'generator_dumper_class' => $app['class']['routing.generator_dumper'],
                    'generator_cache_class' => $app['class']['routing.generator_cache'] ?: $app['app_id'] . 'UrlGenerator',

                    'matcher_class' => $app['class']['routing.matcher'],
                    'matcher_base_class' => $app['class']['routing.matcher_base'],
                    'matcher_dumper_class' => $app['class']['routing.matcher_dumper'],
                    'matcher_cache_class' => $app['class']['routing.matcher_cache'] ?: $app['app_id'] . 'UrlMatcher',

                    'resource_type' => $app['routing.resource_type'],
                    'strict_requirements' => $app['routing.strict_requirements']
                ],
                $app['requestContext']
            );

            return $router;
        };

        /*
        $app['controllerResolver'] = function() use ($app) {

            if (!$controller = $app['request']->attributes->get('_controller')) {
                throw new \RuntimeException('Unable to look for the controller as the "controller" parameter is missing');
            }

            if (false === strpos($controller, '::')) {
                throw new \RuntimeException(sprintf('Unable to find controller "%s".', $controller));
            }

            list($class, $method) = explode('::', $controller, 2);

            if ($app['routing.controllers_namespace']) {
                $class = $app['routing.controllers_namespace'] . '\\' . $class;
            }

            if (!class_exists($class)) {
                throw new \RuntimeException(sprintf('Class "%s" does not exist.', $class));
            }

            $callable = [
                new $class($app),
                $method
            ];

            if (!is_callable($callable)) {
                throw new \RuntimeException(sprintf('The controller for URI "%s" is not callable.', $app['request']->getPathInfo()));
            }

            $app['request']->attributes->set('controller_class', $class);
            $app['request']->attributes->set('controller_method', $method);

            return call_user_func($callable);
        };
        */
    }
}
