<?php
namespace Tao\Templating;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class TemplatingServiceProvider implements ServiceProviderInterface
{
	public function register(Container $app)
	{
		$app['templating'] = function() use ($app)  {
			return new $app['class.templating']($app);
		};
	}
}
