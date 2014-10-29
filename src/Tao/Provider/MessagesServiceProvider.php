<?php
namespace Tao\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class MessagesServiceProvider implements ServiceProviderInterface
{
	public function register(Container $app)
	{
		$app['instantMessages'] = function() use ($app) {
			return new $app['class']['messages.instant']();
		};

		$app['flashMessages'] = function() use ($app) {
			return new $app['class']['messages.flash']('flashMessages');
		};

		$app['persistentMessages'] = function() use ($app)  {
			return new $app['class']['messages.persistent']('persistentMessages');
		};

		$app['messages'] = function() use ($app) {
			return new $app['class']['messages']($app['instantMessages'], $app['flashMessages'], $app['persistentMessages']);
		};
	}
}
