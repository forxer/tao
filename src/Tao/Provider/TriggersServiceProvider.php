<?php
namespace Tao\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tao\Triggers\Triggers;

class TriggersServiceProvider implements ServiceProviderInterface
{
	public function register(Container $app)
	{
		$app['triggers'] = function() {
			return new Triggers();
		};
	}
}
