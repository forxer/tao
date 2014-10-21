<?php
namespace Tao\Templating;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Templating\Helper\AssetsHelper;
use Symfony\Component\Templating\Helper\SlotsHelper;
use Tao\Templating\Helpers\Breadcrumb;
use Tao\Templating\Helpers\FormElements;
use Tao\Templating\Helpers\Modifier;
use Tao\Templating\Helpers\Router;
use Tao\Templating\Helpers\TitleTag;

class TemplatingServiceProvider implements ServiceProviderInterface
{
	public function register(Container $app)
	{
		$app['templating'] = function() use ($app)  {

			$loader = new $app['templating.loader_class']([ $app['templating.path.patterns'] ]);

			if ($app['debug']) {
				$loader->setLogger($app['logger']);
			}

			$templating = new $app['templating.class'](
				$app,
				new $app['templating.name_parser_class'],
				$loader,
				new $app['templating.escaper_class']('utf-8')
			);

			if ($app['templating.load_default_helpers'])
			{
				$templating->set(new AssetsHelper());
				$templating->set(new Breadcrumb());
				$templating->set(new FormElements());
				$templating->set(new Modifier());
				$templating->set(new Router($app['router']));
				$templating->set(new SlotsHelper());
				$templating->set(new TitleTag());
			}

			$templating->addGlobal('app', $app);

			return $templating;
		};
	}
}
