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
		$app['templating.templates.loader'] = function() use ($app)  {

			$loader = new $app['class']['templating.loader']([ $app['templating.path.patterns'] ]);

			if ($app['debug']) {
				$loader->setLogger($app['logger']);
			}

			return $loader;
		};

		$app['templating.templates.name.parser'] = function() use ($app)  {
			return new $app['class']['templating.name_parser'];
		};

		$app['templating.escaper'] = function() use ($app)  {
			return new $app['class']['templating.escaper']('utf-8');
		};

		$app['templating'] = function() use ($app)  {

			$templating = new $app['class']['templating'](
				$app,
				$app['templating.templates.name.parser'],
				$app['templating.templates.loader'],
				$app['templating.escaper']
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
