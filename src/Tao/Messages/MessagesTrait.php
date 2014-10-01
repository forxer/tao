<?php
namespace Tao\Messages;

/**
 * Shared methods between the flash messages and persistent messages.
 *
 */
trait MessagesTrait
{
	/**
	 * Add a message type "info" to the stack.
	 *
	 * @param string $sMessage The message
	 * @return void
	 */
	public function info($sMessage)
	{
		$this->add(self::TYPE_INFO, $sMessage);
	}

	/**
	 * Gets and clears message type "info" from the stack.
	 *
	 * @param array  $aDefault Default value if "info" does not exist.
	 * @return array
	 */
	public function getInfo(array $aDefault = [])
	{
		return $this->get(self::TYPE_INFO, $aDefault);
	}

	/**
	 * Gets messages of type "info" (read only).
	 *
	 * @param array  $aDefault Default value if "info" does not exist.
	 * @return array
	 */
	public function peekInfo(array $aDefault = [])
	{
		return $this->peek(self::TYPE_INFO, $aDefault);
	}

	/**
	 * Returns true if message type "info" exists, false if not.
	 *
	 * @return boolean
	 */
	public function hasInfo()
	{
		return $this->has(self::TYPE_INFO);
	}

	/**
	 * Add a message type "success" to the stack.
	 *
	 * @param string $sMessage The message
	 * @return void
	 */
	public function success($sMessage)
	{
		$this->add(self::TYPE_SUCCESS, $sMessage);
	}

	/**
	 * Gets and clears message type "success" from the stack.
	 *
	 * @param array  $aDefault Default value if "success" does not exist.
	 * @return array
	 */
	public function getSuccess(array $aDefault = [])
	{
		return $this->get(self::TYPE_SUCCESS, $aDefault);
	}

	/**
	 * Gets messages of type "success" (read only).
	 *
	 * @param array  $aDefault Default value if "success" does not exist.
	 * @return array
	 */
	public function peekSuccess(array $aDefault = [])
	{
		return $this->peek(self::TYPE_SUCCESS, $aDefault);
	}

	/**
	 * Returns true if message type "success" exists, false if not.
	 *
	 * @return boolean
	 */
	public function hasSuccess()
	{
		return $this->has(self::TYPE_SUCCESS);
	}

	/**
	 * Add a message type "warning" to the stack.
	 *
	 * @param string $sMessage The message
	 * @return void
	 */
	public function warning($sMessage)
	{
		$this->add(self::TYPE_WARNING, $sMessage);
	}

	/**
	 * Gets and clears message type "warning" from the stack.
	 *
	 * @param array  $aDefault Default value if "warning" does not exist.
	 * @return array
	 */
	public function getWarning(array $aDefault = [])
	{
		return $this->get(self::TYPE_WARNING, $aDefault);
	}

	/**
	 * Gets messages of type "warning" (read only).
	 *
	 * @param array  $aDefault Default value if "warning" does not exist.
	 * @return array
	 */
	public function peekWarning(array $aDefault = [])
	{
		return $this->peek(self::TYPE_WARNING, $aDefault);
	}

	/**
	 * Returns true if message type "warning" exists, false if not.
	 *
	 * @return boolean
	 */
	public function hasWarning()
	{
		return $this->has(self::TYPE_WARNING);
	}

	/**
	 * Add a message type "error" to the stack.
	 *
	 * @param string $sMessage The message
	 * @return void
	 */
	public function error($sMessage)
	{
		$this->add(self::TYPE_ERROR, $sMessage);
	}

	/**
	 * Gets and clears message type "error" from the stack.
	 *
	 * @param array  $aDefault Default value if "error" does not exist.
	 * @return array
	 */
	public function getError(array $aDefault = [])
	{
		return $this->get(self::TYPE_ERROR, $aDefault);
	}

	/**
	 * Gets messages of type "error" (read only).
	 *
	 * @param array  $aDefault Default value if "error" does not exist.
	 * @return array
	 */
	public function peekError(array $aDefault = [])
	{
		return $this->peek(self::TYPE_ERROR, $aDefault);
	}

	/**
	 * Returns true if message type "error" exists, false if not.
	 *
	 * @return boolean
	 */
	public function hasError()
	{
		return $this->has(self::TYPE_ERROR);
	}
}
