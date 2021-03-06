<?php
namespace Tao\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Translation\Loader\PhpFileLoader;

class TranslatorServiceProvider implements ServiceProviderInterface
{
	public function register(Container $app)
	{
		$app['translator.messages.selector'] = function() use ($app)  {
			return new $app['class']['translator.messages_selector'];
		};

		$app['translator'] = function() use ($app)  {

			$translator = new $app['class']['translator'](
				$app['session']->getLanguage(),
				$app['translator.messages.selector'],
				$app['translator.cache_dir'],
				$app['debug']
			);

			if ($app['translator.use_default_php_loader'])
			{
				$translator->addLoader('php', new PhpFileLoader());

				$finder = $app['finder']
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
