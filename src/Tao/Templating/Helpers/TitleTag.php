<?php
namespace Tao\Templating\Helpers;

use Symfony\Component\Templating\Helper\Helper;

class TitleTag extends Helper
{
	protected $aTitles = [];

	/**
	 * Add a title tag to the titles tag stack.
	 *
	 * @param string $sTitle
	 * @param boolean $bPrepend
	 */
	public function add($sTitle, $bPrepend = false)
	{
		if ($bPrepend)
		{
			$this->aTitles = array_reverse($this->aTitles, true);
			$this->aTitles[$sTitle] = $sTitle;
			$this->aTitles = array_reverse($this->aTitles, true);
		}
		else {
			$this->aTitles[$sTitle] = $sTitle;
		}
	}

	/**
	 * Remove a title tag from the titles stack.
	 *
	 * @param string $sTitle
	 */
	public function remove($sTitle)
	{
		if ($this->hasTitle($sTitle)) {
			unset($this->aTitles[$sTitle]);
		}
	}

	/**
	 * Indicate if a given title tag exists or if there are items in the titles tag stack.
	 *
	 * @param string $sTitle
	 * @return boolean
	 */
	public function has($sTitle = null)
	{
		if (null === $sTitle) {
			return !empty($this->aTitles);
		}

		return isset($this->aTitles[$sTitle]);
	}

	/**
	 * Return titles tag stack.
	 *
	 * @return array
	 */
	public function getAll()
	{
		return array_reverse($this->aTitles, true);
	}

	/**
	 * Return title tag string.
	 *
	 * @param string $glue
	 * @return string
	 */
	public function get($glue = '-')
	{
		return implode($glue, $this->getAll());
	}

	public function __toString()
	{
		echo $this->get();
	}

	/**
	 * Returns the canonical name of this helper.
	 *
	 * @return string The canonical name
	 */
	public function getName()
	{
		return 'titleTag';
	}
}
