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
			$config = new $app['class.dbal.config']();

			if ($app['debug']) {
				$config->setSQLLogger(new $app['class.dbal.logging']());
			}

			return $app['class.dbal.driver.manager']::getConnection($app['db_params'], $config);
		};

		$app['qb'] = $app->factory(function ($app) {
			return new $app['class.dbal.query.builder']($app['db']);
		});
	}
}
