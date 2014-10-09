<?php
namespace Tao\Templating;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Templating\Asset\PathPackage;
use Symfony\Component\Templating\Helper\AssetsHelper;
use Symfony\Component\Templating\Helper\SlotsHelper;
use Tao\Templating\Helpers\Breadcrumb;
use Tao\Templating\Helpers\FormElements;
use Tao\Templating\Helpers\Modifier;
use Tao\Templating\Helpers\TitleTag;

class TemplatingServiceProvider implements ServiceProviderInterface
{
	public function register(Container $app)
	{
		$app['templating'] = function() use ($app)  {

			$loader = new $app['class.templating.loader']([ $app['dir.views'] . $app['template.path.patterns'] ]);

			if ($app['debug']) {
				$loader->setLogger($app['logger']);
			}

			$templating = new $app['class.templating'](
				$app,
				new $app['class.templating.template_name_parser'],
				$loader,
				new $app['class.templating.escaper']('utf-8')
			);

			$templating->set(new SlotsHelper());

			$templating->set(new AssetsHelper());
				$templating->get('assets')->addPackage('assets', new PathPackage($app['assets_url']));
				$templating->get('assets')->addPackage('components', new PathPackage($app['components_url']));

			$templating->set(new Breadcrumb());
			$templating->set(new FormElements());
			$templating->set(new Modifier());
			$templating->set(new TitleTag());

			/*
			 * @TODO this is realy a bad practise,
			 * but this is realy usefull for the moment...
			 *
			 * __should be removed in futur__
			 */
			$templating->addGlobal('app', $app);

			return $templating;
		};
	}
}
