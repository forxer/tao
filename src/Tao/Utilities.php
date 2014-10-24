<?php
namespace Tao;

class Utilities
{
	protected $startTime;

	protected $appPath;

	protected $config;

	private $memoryUsageData;

	private $dbUsageData;

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
		list($memoryUsageValue, $memoryUsageUnit) = $this->getMemoryUsageData();

		return $memoryUsageValue.' '.$memoryUsageUnit;
	}

	/**
	 * Return the application memory usage data.
	 *
	 * @return array
	 */
	public function getMemoryUsageData()
	{
		if (null === $this->memoryUsageData)
		{
			$memoryUsage = memory_get_usage();

			$unit = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

			$this->memoryUsageData = [
				round($memoryUsage/pow(1024, ($i=floor(log($memoryUsage, 1024))) ), 2),
				$unit[$i]
			];
		}

		return $this->memoryUsageData;
	}




	public function getDbNumQueries()
	{
		$aDbUsageData = $this->getDbUsageData();

		return $aDbUsageData[0];
	}

	public function getDbExecutionTime()
	{
		$aDbUsageData = $this->getDbUsageData();

		return $aDbUsageData[1];
	}

	public function getDbUsageData()
	{
		if (null === $this->dbUsageData && isset($this->app['db']))
		{
			$iNumQueries = 0;

			$dbExecTime = 0;

			$queries = $this->app['db']->getConfiguration()->getSQLLogger()->queries;

			foreach ($queries as $dbLog)
			{
				$iNumQueries++;
				$dbExecTime += $dbLog['executionMS'];
			}

			$this->dbUsageData= [
				$iNumQueries,
				$dbExecTime
			];
		}

		return $this->dbUsageData;
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
		$config = $config + $this->getDefaultConfiguration();

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
	 * Return the default configuration from Configuration.php file.
	 *
	 * @return array
	 */
	protected function getDefaultConfiguration()
	{
		return require __DIR__ . '/Configuration.php';
	}

	/**
	 * Return application class map.
	 *
	 * @param array $config
	 * @return array
	 */
	public function setClassMap(array $classMap = [])
	{
		# Merge given class map with default values
		$classMap = $classMap + $this->getDefaultClassMap();

		return $classMap;
	}

	/**
	 * Return the default class map from ClassMap.php file.
	 *
	 * @return array
	 */
	protected function getDefaultClassMap()
	{
		return require __DIR__ . '/ClassMap.php';
	}
}
