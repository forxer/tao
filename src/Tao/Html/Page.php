<?php
namespace Tao\Html;

class Page
{
	public $titleTag;

	public $breadcrumb;

	public function __construct()
	{
		$this->titleTag = new TitleTag();

		$this->breadcrumb = new Breadcrumb();
	}

	/**
	 * Convenient shorcut for both :
	 * 	- $this->titleTag->add()
	 *  - $this->breadcrumb->add()
	 *
	 * @param string $title
	 * @param string $url
	 * @param string $bPrepend
	 */
	public function addTitle($title, $url = null, $bPrepend = false)
	{
		$this->titleTag->add($title, $bPrepend);

		$this->breadcrumb->add($title, $url, $bPrepend);
	}
}
