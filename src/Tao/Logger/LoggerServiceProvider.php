<?php
namespace Tao\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\MemoryPeakUsageProcessor;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class LoggerServiceProvider implements ServiceProviderInterface
{
	public function register(Container $app)
	{
		$app['logger'] = function() use ($app) {
			return new $app['class']['logger'](
				'app_logger',
				[
					new FirePHPHandler()
				],
				[
					new IntrospectionProcessor(),
					new WebProcessor(),
					new MemoryUsageProcessor(),
					new MemoryPeakUsageProcessor()
				]
			);
		};

		$app['phpLogger'] = function() use ($app) {
			return new $app['class']['logger'](
				'php_error',
				[
					new FingersCrossedHandler(
						new StreamHandler(
							$app['logger.dir'] . '/php_errors.log',
							$app['logger']::INFO
						),
						$app['logger']::WARNING
					)
				],
				[
					new IntrospectionProcessor(),
					new WebProcessor(),
					new MemoryUsageProcessor(),
					new MemoryPeakUsageProcessor()
				]
			);
		};
	}
}
