<?php
namespace Tao\Templating\Escaper;

interface EscaperInterface
{
	public function __construct($encoding = null);

	/**
	 * Escape a $string for the HTML Body context.
	 *
	 * @param $string $string
	 * @return $string
	 */
	public function html($string);

	/**
	 * Escape a $string for the HTML Attribute context.
	 *
	 * @param $string $string
	 * @return $string
	 */
	public function htmlAttr($string);

	/**
	 * Escape a $string for the Javascript context.
	 *
	 * @param $string $string
	 * @return $string
	 */
	public function js($string);

	/**
	 * Escape a $string for the URI or Parameter contexts.
	 *
	 * @param $string $string
	 * @return $string
	 */
	public function url($string);

	/**
	 * Escape a $string for the CSS context.
	 *
	 * @param $string $$string
	 * @return $string
	 */
	public function css($string);
}
