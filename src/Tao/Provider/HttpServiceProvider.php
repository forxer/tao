<?php
namespace Tao\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class HttpServiceProvider implements ServiceProviderInterface
{
	public function register(Container $app)
	{
		$app['request'] = function() use ($app) {
			return $app['class']['http.request']::createFromGlobals();
		};

		$app['session.handler'] = function() use ($app) {
			return new $app['class']['session.handler']();
		};

		$app['session.storage'] = function() use ($app) {
			return new $app['class']['session.storage'](
					[
						'cookie_lifetime' 	=> 0,
						'cookie_path' 		=> $app['app_url'],
						'cookie_secure' 	=> $app['request']->isSecure(),
						'cookie_httponly' 	=> true,
						'use_trans_sid' 	=> false,
						'use_only_cookies' 	=> true
					],
					$app['session.handler']
				);
		};

		$app['session'] = function() use ($app) {
			return new $app['class']['session'](
				$app,
				$app['session.storage'],
				null,
				$app['persistentMessages'],
				$app['flashMessages'],
				$app['sec.csrf_token_name']
			);
		};
	}
}
