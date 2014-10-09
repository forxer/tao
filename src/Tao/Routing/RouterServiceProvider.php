<?php
namespace Tao\Routing;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class RouterServiceProvider implements ServiceProviderInterface
{
	public function register(Container $app)
	{
		$app['router'] = function() use ($app)  {

			$router =  new $app['class.router']($app);

			$app['tpl']->set(new TemplatingHelper($router->getGenerator()));

			return $router;
		};
	}
}
