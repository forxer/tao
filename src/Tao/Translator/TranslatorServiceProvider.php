<?php
namespace Tao\Translator;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Translation\Loader\PhpFileLoader;

class TranslatorServiceProvider implements ServiceProviderInterface
{
	public function register(Container $app)
	{
		$app['translator.messages.selector'] = function() use ($app)  {
			return new $app['translator.messages_selector_class'];
		};

		$app['translator'] = function() use ($app)  {

			$translator = new $app['translator.class'](
				$app['session']->getLanguage(),
				$app['translator.messages.selector'],
				$app['translator.cache_dir'],
				$app['debug']
			);

			if ($app['translator.use_default_php_loader'])
			{
				$translator->addLoader('php', new PhpFileLoader());

				$finder = (new Finder())
					->files()
					->in($app['translator.dir'])
					->name('*.php');

				foreach ($finder as $file)
				{
					$translator->addResource(
						'php',
						$app['translator.dir'] . '/' . $file->getRelativePathname(),
						$file->getRelativePath()
					);
				}
			}

			return $translator;
		};
	}
}
