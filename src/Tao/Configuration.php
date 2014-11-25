<?php

return [

	# The application identifier (used internally)
	'app_id' => 'tao',

	# Enable/disable debug mode
	'debug' => false,

	# Value of X-Frame-Options Response Headers
	# to prevent Clickjacking ; https://www.owasp.org/index.php/Clickjacking
	#
	# Should be :
	# - DENY, which prevents any domain from framing the content.
	# - SAMEORIGIN, which only allows the current site to frame the content.
	# - or false to not use this header
	'x-frame-options' => false,

	# Relative path to the application URL from the hostname.
	# The value should always begin and end with a slash.
	#
	# ex.:
	# 	http://domain.tld 			: '/'
	# 	http://domain.tld/app 		: '/app/'
	# 	http://sub.domain.tld 		: '/'
	# 	http://sub.domain.tld/test 	: '/test/'
	# 	etc.
	'app_url' 					=> '/',

	# Relative path to the assets URL
	# from the app_url configuration (see above).
	'assets_url' 				=> 'Assets',

	# Relative path to the components URL
	# from the app_url configuration (see above).
	'components_url' 			=> 'Components',


	# Database
	# ----------------------------------------------------------

	# Database connexion parameters
	'database.connection' => [
		'driver' => '',
		'host' => '',
		'dbname' => '',
		'user' => '',
		'password' => '',
		'charset' => ''
	],

	'database.models_namespace' => 'Application\Models',


	# Logger
	# ----------------------------------------------------------

	'logger.dir' => $this->getApplicationPath() . '/Storage/Logs',


	# Minify
	# ----------------------------------------------------------

	// not implemented
	//'minify.cache_dir' => $this->getApplicationPath() . '/Storage/Cache/Minify',


	# Routing
	# ----------------------------------------------------------

	# List of directories to search resources
	'routing.resources_dirs' => [
		$this->getApplicationPath() . '/Config'
	],

	# Name of resource files
	'routing.resource_name' => 'routes.yml',

	# Controllers namespace (to avoid having to type it every route definition)
	'routing.controllers_namespace' => null,

	# Path to the router cache directory
	'routing.cache_dir' => $this->getApplicationPath() . '/Storage/Cache/Router',

	'routing.resource_type' => null,
	'routing.strict_requirements' => true,


	# Translator
	# ----------------------------------------------------------

	'translator.locales' => [
		'fr' => 'Français',
		'en' => 'English'
	],

	'translator.fallback' => 'fr',

	'translator.dir' => $this->getApplicationPath() . '/Translations',

	'translator.cache_dir' => $this->getApplicationPath() . '/Storage/Cache/Translations',

	'translator.use_default_php_loader' => true,


	# Templating
	# ----------------------------------------------------------

	'templating.path.patterns' => $this->getApplicationPath() . '/Views/%name%.php',

	'templating.load_default_helpers' => true,



	# Cross-Site Request Forgery token name in session
	'sec.csrf_token_name' 		=> 'csrf_token',
];

