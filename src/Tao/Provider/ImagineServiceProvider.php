<?php
namespace Tao\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Filesystem\Filesystem;

class ImagineServiceProvider implements ServiceProviderInterface
{
	public function register(Container $app)
	{
		if (class_exists('\Gmagick')) {
			$app['imagine.driver'] = 'Gmagick';
		}
		elseif (class_exists('\Imagick')) {
			$app['imagine.driver'] = 'Imagick';
		}
		else {
			$app['imagine.driver'] = 'Gd';
		}

		$app['imagine'] = function() use ($app) {
			$classname = sprintf('Imagine\%s\Imagine', $app['imagine.driver']);
			return new $classname;
		};
	}
}
