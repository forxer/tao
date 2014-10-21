<?php
namespace Tao;

class ApplicationUtilities
{
	protected $startTime;

	protected $appPath;

	protected $config;

	public function __construct($app)
	{
		# Application reference
		$this->app = $app;
	}

	/**
	 * Register the application start time.
	 *
	 */
	public function registerStartTime()
	{
		$this->startTime = microtime(true);
	}

	/**
	 * Return the application start time.
	 *
	 * @return float
	 */
	public function getStartTime()
	{
		return $this->startTime;
	}

	/**
	 * Return the application execution time.
	 *
	 * @return string
	 */
	public function getExecutionTime()
	{
		return microtime(true) - $this->startTime;
	}

	/**
	 * Return the application memory usage.
	 *
	 * @return string
	 */
	public function getMemoryUsage()
	{
		$memoryUsage = memory_get_usage();

		$unit = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

		return round($memoryUsage/pow(1024, ($i=floor(log($memoryUsage, 1024))) ), 2).' '.$unit[$i];
	}

	/**
	 * Set the application path.
	 *
	 * @param string $appPath
	 */
	public function setApplicationPath($appPath)
	{
		$this->appPath = realpath($appPath);
	}

	/**
	 * Return the application path.
	 *
	 * @return string
	 */
	public function getApplicationPath()
	{
		return $this->appPath;
	}

	/**
	 * Return application configuration values.
	 *
	 * @param array $config
	 * @return array
	 */
	public function setConfiguration(array $config = [])
	{
		# Merge config with default values
		$config = $config + $this->getBaseConfiguration();

		# If debug mode, store config data for debug purpose
		if (!empty($config['debug'])) {
			$this->config = $config;
		}

		return $config;
	}

	/**
	 * Return configuration data in debug mode.
	 *
	 * @return array
	 */
	public function getConfiguration()
	{
		return $this->config;
	}

	/**
	 * Return the basic configuration from BaseConfiguration.php.
	 *
	 */
	protected function getBaseConfiguration()
	{
		return require __DIR__ . '/BaseConfiguration.php';
	}
}
