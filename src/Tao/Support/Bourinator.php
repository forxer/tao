<?php
namespace Tao\Support;

/**
 * Un simple outil pour bourriner sévère.
 *
 * Fourni 2 méthodes : start et stop.
 *
 * Bourinator::start() essai de donner le maximum de ressources.
 * Bourinator::stop() essai de rétablir la configuration.
 */
class Bourinator
{
	protected $iStartTime;

	const MAX_EXECUTION_TIME = 'max_execution_time';
	const MEMORY_LIMIT = 'memory_limit';

	public function start()
	{
		ini_set(self::MAX_EXECUTION_TIME, 0);
		ini_set(self::MEMORY_LIMIT, -1);

		$this->iStartTime = microtime(true);
	}

	public function stop()
	{
		$exectime = microtime(true) - $this->iStartTime;

		ini_restore(self::MAX_EXECUTION_TIME);
		ini_restore(self::MEMORY_LIMIT);

		return $exectime;
	}
}
