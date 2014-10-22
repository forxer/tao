<?php
namespace Tao\Messages;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class MessagesServiceProvider implements ServiceProviderInterface
{
	public function register(Container $app)
	{
		$app['instantMessages'] = function() {
			return new InstantMessages();
		};

		$app['flashMessages'] = function() {
			return new FlashMessages('flashMessages');
		};

		$app['persistentMessages'] = function() {
			return new PersistentMessages('persistentMessages');
		};

		$app['messages'] = function($app) {
			return new Messages($app['instantMessages'], $app['flashMessages'], $app['persistentMessages']);
		};
	}
}
