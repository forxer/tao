<?php
namespace Tao\Messages;

use Tao\Application;

/**
 * Convenient merged messages bags.
 * Merge Instant messages, Flash messages and Persistent messages.
 *
 */
class Messages
{
	protected $app;

	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	public function getInfo()
	{
		return array_merge(
			$this->app['instantMessages']->getInfo(),
			$this->app['flashMessages']->getInfo(),
			$this->app['persistentMessages']->getInfo()
		);
	}

	public function hasInfo()
	{
		return $this->app['instantMessages']->hasInfo()
			|| $this->app['flashMessages']->hasInfo()
			|| $this->app['persistentMessages']->hasInfo();
	}

	public function getSuccess()
	{
		return array_merge(
			$this->app['instantMessages']->getSuccess(),
			$this->app['flashMessages']->getSuccess(),
			$this->app['persistentMessages']->getSuccess()
		);
	}

	public function hasSuccess()
	{
		return $this->app['instantMessages']->hasSuccess()
			|| $this->app['flashMessages']->hasSuccess()
			|| $this->app['persistentMessages']->hasSuccess();
	}

	public function getWarning()
	{
		return array_merge(
			$this->app['instantMessages']->getWarning(),
			$this->app['flashMessages']->getWarning(),
			$this->app['persistentMessages']->getWarning()
		);
	}

	public function hasWarning()
	{
		return $this->app['instantMessages']->hasWarning()
			|| $this->app['flashMessages']->hasWarning()
			|| $this->app['persistentMessages']->hasWarning();
	}

	public function getError()
	{
		return array_merge(
			$this->app['instantMessages']->getError(),
			$this->app['flashMessages']->getError(),
			$this->app['persistentMessages']->getError()
		);
	}

	public function hasError()
	{
		return $this->app['instantMessages']->hasError()
			|| $this->app['flashMessages']->hasError()
			|| $this->app['persistentMessages']->hasError();
	}
}
