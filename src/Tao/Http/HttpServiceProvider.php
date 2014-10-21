<?php
namespace Tao\Http;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class HttpServiceProvider implements ServiceProviderInterface
{
	public function register(Container $app)
	{
		$app['request'] = function() use ($app) {
			return $app['http.request_class']::createFromGlobals();
		};

		$app['session'] = function() use ($app) {
			return new $app['session.class'](
				$app,
				new $app['session.storage_class'](
					[
						'cookie_lifetime' 	=> 0,
						'cookie_path' 		=> $app['app_url'],
						'cookie_secure' 	=> $app['request']->isSecure(),
						'cookie_httponly' 	=> true,
						'use_trans_sid' 	=> false,
						'use_only_cookies' 	=> true
					],
					new $app['session.handler_class']()
				),
				null,
				$app['persistentMessages'],
				$app['flashMessages'],
				$app['sec.csrf_token_name']
			);
		};
	}
}
