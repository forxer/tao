<?php
namespace Tao\Routing;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class RouterServiceProvider implements ServiceProviderInterface
{
	public function register(Container $app)
	{
		$app['router'] = function() use ($app)  {
			return new $app['class.router']($app);
		};
	}
}
