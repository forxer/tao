<?php

return [

	# Database
	# ----------------------------------------------------------

	'database.config' 				=> 'Doctrine\DBAL\Configuration',
	'database.logger' 				=> 'Doctrine\DBAL\Logging\DebugStack',
	'database.driver_manager' 		=> 'Doctrine\DBAL\DriverManager',
	'database.query_builder' 		=> 'Tao\Database\QueryBuilder',


	# Http
	# ----------------------------------------------------------

	'http.request' 					=> 'Symfony\Component\HttpFoundation\Request',


	# Logger
	# ----------------------------------------------------------

	'logger' 						=> 'Monolog\Logger',


	# Messages
	# ----------------------------------------------------------

	'messages' 						=> 'Tao\Messages\Messages',
	'messages.instant' 				=> 'Tao\Messages\InstantMessages',
	'messages.flash' 				=> 'Tao\Messages\FlashMessages',
	'messages.persistent' 			=> 'Tao\Messages\PersistentMessages',


	# Routing
	# ----------------------------------------------------------

	'routing.router' 				=> 'Symfony\Component\Routing\Router',
	'routing.loader' 				=> 'Symfony\Component\Routing\Loader\YamlFileLoader',
	'routing.locator' 				=> 'Symfony\Component\Config\FileLocator',
	'routing.request_context' 		=> 'Symfony\Component\Routing\RequestContext',

	'routing.generator' 			=> 'Symfony\Component\Routing\Generator\UrlGenerator',
	'routing.generator_base'		=> 'Symfony\Component\Routing\Generator\UrlGenerator',
	'routing.generator_dumper' 		=> 'Symfony\Component\Routing\Generator\Dumper\PhpGeneratorDumper',
	'routing.generator_cache' 		=> null,

	'routing.matcher' 				=> 'Symfony\Component\Routing\Matcher\UrlMatcher',
	'routing.matcher_base' 			=> 'Symfony\Component\Routing\Matcher\UrlMatcher',
	'routing.matcher_dumper' 		=> 'Symfony\Component\Routing\Matcher\Dumper\PhpMatcherDumper',
	'routing.matcher_cache' 		=> null,


	# Session
	# ----------------------------------------------------------

	'session' 						=> 'Tao\Http\Session',
	'session.storage' 				=> 'Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage',
	'session.handler' 				=> '\SessionHandler',


	# Templating
	# ----------------------------------------------------------

	'templating' 					=> 'Tao\Templating\Templating',
	'templating.escaper' 			=> 'Tao\Templating\Escaper\ZendEscaper',
	'templating.loader' 			=> 'Symfony\Component\Templating\Loader\FilesystemLoader',
	'templating.name_parser' 		=> 'Symfony\Component\Templating\TemplateNameParser',


	# Translator
	# ----------------------------------------------------------

	'translator' 					=> 'Symfony\Component\Translation\Translator',
	'translator.messages_selector' 	=> 'Symfony\Component\Translation\MessageSelector',

];

