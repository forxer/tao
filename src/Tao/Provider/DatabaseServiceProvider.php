<?php
namespace Tao\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class DatabaseServiceProvider implements ServiceProviderInterface
{
	public function register(Container $app)
	{
		$app['db.config'] = function() use ($app) {
			$config = new $app['class']['database.config']();

			if ($app['debug']) {
				$config->setSQLLogger($app['db.logger']);
			}

			return $config;
		};

		$app['db.logger'] = function() use ($app) {
			return new $app['class']['database.logger']();
		};

		$app['db'] = function() use ($app) {
			return $app['class']['database.driver_manager']::getConnection($app['database.connection'], $app['db.config']);
		};

		$app['qb'] = $app->factory(function ($app) {
			return new $app['class']['database.query_builder']($app['db']);
		});
	}
}
