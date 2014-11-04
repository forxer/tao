<?php
namespace Tao\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Filesystem\Filesystem;

class FilesystemServiceProvider implements ServiceProviderInterface
{
	public function register(Container $app)
	{
		$app['filesystem'] = function() {
			return new Filesystem();
		};
	}
}
