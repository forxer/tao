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

			$translator = new $app['translator.class'](
				$app['session']->getLanguage(),
				new $app['translator.messages_selector_class']
			);

			$app['templating']->set(new TemplatingHelper($translator));

			return $translator;
		};
	}
}
