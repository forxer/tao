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

			$translator = $app['class.translator'](
				$app['session']->getLanguage(),
				$app['class.translator.messages.selector']
			);

			$app['templating']->set(new TemplatingHelper($translator));

			return $translator;
		};
	}
}
