<?php
namespace Tao\Templating\Helpers;

use Symfony\Component\Templating\Helper\Helper;
use Tao\Html\Modifiers;

class Modifier extends Helper
{
	/**
	 * Number format shortcut.
	 *
	 * @param float $number
	 * @param integer $decimals
	 * @param string $dec_point
	 * @param string $thousands_sep
	 * @return string
	 */
	public function number($number, $decimals = 0, $dec_point = ',', $thousands_sep = '&nbsp;' )
	{
		return number_format((float)$number, $decimals, $dec_point, $thousands_sep);
	}

	/**
	 * Converts text line breaks into HTML paragraphs.
	 *
	 * @param string $string String to transform
	 * @return string
	 */
	public function nlToP($string)
	{
		$string = trim($string);
		$string = Modifiers::linebreaks($string);
		$string = str_replace("\n", "</p>\n<p>", $string);
		$string = str_replace('<p></p>', '', $string);
		return '<p>' . $string . '</p>' . PHP_EOL;
	}

	/**
	 * Converts text line breaks into HTML paragraphs and HTML line breaks.
	 *
	 * @param string $string String to transform
	 * @return string
	 */
	public function nlToPbr($string)
	{
		$string = trim($string);
		$string = Modifiers::linebreaks($string);
		$string = str_replace("\n", '<br />', $string);
		$string = str_replace('<br /><br />', "</p>\n<p>", $string);
		$string = str_replace('<p></p>', '', $string);
		return '<p>' . $string . '</p>' . PHP_EOL;
	}

	/**
	 * Encode an email address for HTML.
	 *
	 * @param string $sEmail
	 * @return string encoded email
	 */
	public static function emailEncode($sEmail)
	{
		$sEmail = bin2hex($sEmail);
		$sEmail = chunk_split($sEmail, 2, '%');
		$sEmail = '%' . substr($sEmail, 0, strlen($sEmail) - 1);
		return $sEmail;
	}

	/**
	 * Truncate a string to a certain length if necessary,
	 * optionally splitting in the middle of a word, and
	 * appending the $etc string or inserting $etc into the middle.
	 *
	 * @param string $string
	 * @param integer $length
	 * @param string $etc
	 * @param boolean $bBreakWords
	 * @param boolean $bMiddle
	 * @return string truncated string
	 */
	public function truncate($string, $length = 80, $etc = '...', $bBreakWords = false, $bMiddle = false)
	{
		if (mb_strlen($string) > $length)
		{
			$length -= min($length, mb_strlen($etc));

			if (!$bBreakWords && !$bMiddle) {
				$string = preg_replace('/\s+?(\S+)?$/u', '', mb_substr($string, 0, $length + 1));
			}

			if (!$bMiddle) {
				return mb_substr($string, 0, $length) . $etc;
			}

			return mb_substr($string, 0, $length / 2) . $etc . mb_substr($string, - $length / 2, $length);
		}

		return $string;
	}

	public function pluralize($iNumber, $zero, $one, $more)
	{
		$iNumber = (integer)$iNumber;

		if ($iNumber < 1) {
			return $zero;
		}
		elseif ($iNumber === 1) {
			return $one;
		}
		else {
			return sprintf($more, $this->number($iNumber));
		}
	}

	/**
	 * Returns the canonical name of this helper.
	 *
	 * @return string The canonical name
	 */
	public function getName()
	{
		return 'modifier';
	}
}
