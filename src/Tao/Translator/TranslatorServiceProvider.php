<?php
namespace Tao\Translator;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class TranslatorServiceProvider implements ServiceProviderInterface
{
	public function register(Container $app)
	{
		$app['translator'] = function() use ($app)  {
			return new $app['class.translator']($app);
		};
	}
}
