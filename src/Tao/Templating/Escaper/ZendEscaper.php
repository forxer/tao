<?php
namespace Tao\Templating\Escaper;

use Zend\Escaper\Escaper;

class ZendEscaper  implements EscaperInterface
{
	protected $escaper = null;

	public function __construct()
	{
		$this->escaper = new Escaper('utf-8');
	}

	public function html($string)
	{
		return $this->escaper->escapeHtml($string);
	}

	public function htmlAttr($string)
	{
		return $this->escaper->escapeHtmlAttr($string);
	}

	public function js($string)
	{
		return $this->escaper->escapeJs($string);
	}

	public function url($string)
	{
		return $this->escaper->escapeUrl($string);
	}

	public function css($string)
	{
		return $this->escaper->escapeCss($string);
	}
}
