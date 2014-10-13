<?php
namespace Tao\Translator;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Translator;

class TranslatorServiceProvider implements ServiceProviderInterface
{
	public function register(Container $app)
	{
		$app['translator'] = function() use ($app)  {

			$translator = new $app['translator.engine'](
				$app['session']->getLanguage(),
				new $app['translator.messages_selector']
			);

			$app['templating']->set(new TemplatingHelper($translator));

			return $translator;
		};
	}
}
