<?php
namespace Tao\Database;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class DatabaseServiceProvider implements ServiceProviderInterface
{
	public function register(Container $app)
	{
		$app['db'] = function() use ($app)
		{
			$config = new $app['database.config_class']();

			if ($app['debug']) {
				$config->setSQLLogger(new $app['database.logger_class']());
			}

			return $app['database.driver_manager_class']::getConnection($app['database.connection'], $config);
		};

		$app['qb'] = $app->factory(function ($app) {
			return new $app['database.query_builder_class']($app['db']);
		});
	}
}
