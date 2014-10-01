<?php
namespace Tao\Config\Loader;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

class YamlAppLoader extends FileLoader
{
	public function load($resource, $type = null)
	{
		$configValues = Yaml::parse($resource);

		// ... handle the config values

		// maybe import some other resource:

		// $this->import('extra_app.yml');
	}

	public function supports($resource, $type = null)
	{
		return is_string($resource) && 'yml' === pathinfo(
			$resource,
			PATHINFO_EXTENSION
		);
	}
}
