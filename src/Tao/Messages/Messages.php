<?php
namespace Tao\Messages;

/**
 * Convenient merged messages bags.
 * Merge Instant messages, Flash messages and Persistent messages.
 *
 */
class Messages
{
	protected $instant;
	protected $flash;
	protected $persistent;

	public function __construct(MessagesInterface $instant, MessagesInterface $flash, MessagesInterface $persistent)
	{
		$this->instant = $instant;
		$this->flash = $flash;
		$this->persistent = $persistent;
	}

	public function getInfo()
	{
		return array_merge(
			$this->instant->getInfo(),
			$this->flash->getInfo(),
			$this->persistent->getInfo()
		);
	}

	public function hasInfo()
	{
		return $this->instant->hasInfo()
			|| $this->flash->hasInfo()
			|| $this->persistent->hasInfo();
	}

	public function getSuccess()
	{
		return array_merge(
			$this->instant->getSuccess(),
			$this->flash->getSuccess(),
			$this->persistent->getSuccess()
		);
	}

	public function hasSuccess()
	{
		return $this->instant->hasSuccess()
			|| $this->flash->hasSuccess()
			|| $this->persistent->hasSuccess();
	}

	public function getWarning()
	{
		return array_merge(
			$this->instant->getWarning(),
			$this->flash->getWarning(),
			$this->persistent->getWarning()
		);
	}

	public function hasWarning()
	{
		return $this->instant->hasWarning()
			|| $this->flash->hasWarning()
			|| $this->persistent->hasWarning();
	}

	public function getError()
	{
		return array_merge(
			$this->instant->getError(),
			$this->flash->getError(),
			$this->persistent->getError()
		);
	}

	public function hasError()
	{
		return $this->instant->hasError()
			|| $this->flash->hasError()
			|| $this->persistent->hasError();
	}
}
