<?php

$config = [

	#
	# Common settings
	#-------------------------------------------------

	# Enable/disable debug mode
	'debug' 					=> false,

	# Database connexion configuration.
	# Should be doctrine DBAL configuration params prefixed by "db."
	# see http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html
	'db.driver'					=> '',
	'db.host'   				=> '',
	'db.dbname'   				=> '',
	'db.user'   				=> '',
	'db.password'   			=> '',
	'db.charset' 				=> '',

	# Relative path to the application URL from the hostname.
	# The value should always begin and end with a slash.
	#
	# ex.:
	# 	http://localhost 		: '/'
	# 	http://localhost/app 	: '/app/'
	#   http://vhost 			: '/'
	# 	http://domain.tld 		: '/'
	# 	http://domain.tld/test  : '/test/'
	# 	etc.
	'app_url' 					=> '/',

	# Relative path to the assets URL
	# from the app_url configuration (see above).
	'assets_url' 				=> 'Assets',

	# Relative path to the components URL
	# from the app_url configuration (see above).
	'components_url' 			=> 'Components',

	#
	# Advanced settings
	#-------------------------------------------------

	# Absolute directory paths
	'dir.tao' 					=> __DIR__ . '/..',
	'dir.application' 			=> __DIR__ . '/../../../app',
	'dir.cache' 				=> __DIR__ . '/../../../app/Storage/Cache',
	'dir.config' 				=> __DIR__ . '/../../../app/Config',
	'dir.controllers' 			=> __DIR__ . '/../../../app/Controllers',
	'dir.logs' 					=> __DIR__ . '/../../../app/Storage/Logs',
	'dir.models' 				=> __DIR__ . '/../../../app/Models',
	'dir.views' 				=> __DIR__ . '/../../../app/Views',
	'dir.web' 					=> __DIR__ . '/../../../www',

	# Dependencies; where the Pimple DIC shows all its power
	# ('cause you can use other core class by simply configuration setting)
	'class.dbal.config'			=> 'Doctrine\DBAL\Configuration',
	'class.dbal.logging'		=> 'Doctrine\DBAL\Logging\DebugStack',
	'class.dbal.driver.manager'	=> 'Doctrine\DBAL\DriverManager',
	'class.dbal.query.builder'	=> 'Tao\Database\QueryBuilder',
	'class.http.request'		=> 'Symfony\Component\HttpFoundation\Request',
	'class.session'				=> 'Tao\Http\Session',
	'class.session.storage' 	=> 'Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage',
	'class.session.handler' 	=> '\SessionHandler',
	'class.logger' 				=> 'Monolog\Logger',
	'class.router' 				=> 'Tao\Routing\Router',
	'class.templating' 			=> 'Tao\Templating\Templating',

	# Cross-Site Request Forgery token name in session
	'sec.csrf_token_name' 		=> 'csrf_token',

];

# Merge with environment dedicated files
if (defined('APP_ENV'))
{
	$envConfigFile = $config['dir.config'] . '/' . APP_ENV . '.php';

	if (file_exists($envConfigFile))
	{
		$envConfig = require $envConfigFile;

		$envConfig['env'] = APP_ENV;

		return $envConfig + $config;
	}
}

# If no environment is specified with the APP_ENV constant.
$config['env'] = null;

return $config;
