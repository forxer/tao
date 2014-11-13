<?php
namespace Tao\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tao\Support\Bourinator;
use Tao\Support\StopWords;

class SupportServiceProvider implements ServiceProviderInterface
{
	public function register(Container $app)
	{
		$app['bourinator'] = $app->factory(function() use ($app) {
			return new Bourinator();
		});

		$app['stopwords'] = function() use ($app) {
			return new StopWords();
		};
	}
}
