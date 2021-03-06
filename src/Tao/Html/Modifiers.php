<?php
namespace Tao\Html;

class Modifiers
{
	/**
	 * Convert \r\n an \r in \n
	 *
	 * @param string $string String to transform
	 * @return string
	 */
	public static function linebreaks($string)
	{
		return str_replace(
		[ "\r\n", "\r" ],
		[ "\n", "\n" ],
		$string);
	}

	/**
	 * Transform a string in slug regarding to Okatea configuration.
	 *
	 * @param string $string String to transform
	 * @param boolean $bWithSlashes
	 *        	in URL
	 * @return string
	 */
	static public function strToSlug($string, $bWithSlashes = true)
	{
		switch ($GLOBALS['okt']->config->slug_type)
		{
			case 'utf8':
				return self::tidyURL($string, $bWithSlashes);

			case 'ascii':
			default:
				return self::strToLowerURL($string, $bWithSlashes);
		}
	}

	/**
	 * String to URL
	 *
	 * Transforms a string to a proper URL.
	 *
	 * @copyright Copyright (c) 2003-2013 Olivier Meunier & Association Dotclear
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
	 *
	 * @param string $string String to transform
	 * @param boolean $bWithSlashes in URL
	 * @return string
	 */
	public static function strToUrl($string, $bWithSlashes = true)
	{
		$string = self::deaccent($string);
		$string = preg_replace('/[^A-Za-z0-9_\s\'\:\/[\]-]/', '', $string);

		return self::tidyUrl($string, $bWithSlashes);
	}

	/**
	 * String to lower URL.
	 *
	 * Transforms a string to a lowercase proper URL.
	 *
	 * @param string $string String to transform
	 * @param boolean $bWithSlashes in URL
	 * @return string
	 */
	public static function strToLowerUrl($string, $bWithSlashes = true)
	{
		return strtolower(self::strToUrl($string, $bWithSlashes));
	}

	/**
	 * Transform a string in a camelCase style.
	 *
	 * @param string $string
	 * @return string
	 */
	static public function strToCamelCase($string)
	{
		$string = self::strToLowerUrl($string, false);

		$string = implode('', array_map('ucfirst', explode('_', $string)));
		$string = implode('', array_map('ucfirst', explode('-', $string)));

		return strtolower(substr($string, 0, 1)) . substr($string, 1);
	}

	/**
	 * Transform a string in underscored style.
	 *
	 * @param string $string
	 * @return string
	 */
	static public function strToUnderscored($string)
	{
		$string = self::strToLowerUrl($string, false);

		return str_replace('-', '_', $string);
	}

	/**
	 * Accents replacement.
	 *
	 * Replaces some occidental accentuated characters by their ASCII
	 * representation.
	 *
	 * @copyright Copyright (c) 2003-2013 Olivier Meunier & Association Dotclear
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
	 *
	 * @param string $string
	 *        	deaccent
	 * @return string
	 */
	public static function deaccent($string)
	{
		$pattern = [];

		$pattern['A'] = '\x{00C0}-\x{00C5}';
		$pattern['AE'] = '\x{00C6}';
		$pattern['C'] = '\x{00C7}';
		$pattern['D'] = '\x{00D0}';
		$pattern['E'] = '\x{00C8}-\x{00CB}';
		$pattern['I'] = '\x{00CC}-\x{00CF}';
		$pattern['N'] = '\x{00D1}';
		$pattern['O'] = '\x{00D2}-\x{00D6}\x{00D8}';
		$pattern['OE'] = '\x{0152}';
		$pattern['S'] = '\x{0160}';
		$pattern['U'] = '\x{00D9}-\x{00DC}';
		$pattern['Y'] = '\x{00DD}';
		$pattern['Z'] = '\x{017D}';

		$pattern['a'] = '\x{00E0}-\x{00E5}';
		$pattern['ae'] = '\x{00E6}';
		$pattern['c'] = '\x{00E7}';
		$pattern['d'] = '\x{00F0}';
		$pattern['e'] = '\x{00E8}-\x{00EB}';
		$pattern['i'] = '\x{00EC}-\x{00EF}';
		$pattern['n'] = '\x{00F1}';
		$pattern['o'] = '\x{00F2}-\x{00F6}\x{00F8}';
		$pattern['oe'] = '\x{0153}';
		$pattern['s'] = '\x{0161}';
		$pattern['u'] = '\x{00F9}-\x{00FC}';
		$pattern['y'] = '\x{00FD}\x{00FF}';
		$pattern['z'] = '\x{017E}';

		$pattern['ss'] = '\x{00DF}';

		foreach ($pattern as $r => $p) {
			$string = preg_replace('/[' . $p . ']/u', $r, $string);
		}

		return $string;
	}

	/**
	 * URL cleanup.
	 *
	 * @copyright Copyright (c) 2003-2013 Olivier Meunier & Association Dotclear
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
	 *
	 * @param string $string
	 *        	tidy
	 * @param boolean $bKeepSlashes
	 *        	in URL
	 * @param boolean $bKeepSpaces
	 *        	in URL
	 * @return string
	 */
	public static function tidyUrl($string, $bKeepSlashes = true, $bKeepSpaces = false)
	{
		$string = strip_tags($string);
		$string = str_replace([
			'?',
			'&',
			'#',
			'=',
			'+',
			'<',
			'>',
			'"',
			'%'
		], '', $string);
		$string = str_replace("'", ' ', $string);
		$string = preg_replace('/[\s]+/u', ' ', trim($string));

		if (!$bKeepSlashes) {
			$string = str_replace('/', '-', $string);
		}

		if (!$bKeepSpaces) {
			$string = str_replace(' ', '-', $string);
		}

		$string = preg_replace('/[-]+/', '-', $string);

		# Remove path changes in URL
		$string = preg_replace('%^/%', '', $string);
		$string = preg_replace('%\.+/%', '', $string);

		return $string;
	}

	/**
	 * Split words
	 *
	 * Returns an array of words from a given string.
	 *
	 * @copyright Copyright (c) 2003-2013 Olivier Meunier & Association Dotclear
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
	 *
	 * @param string $string
	 *        	split
	 * @return array
	 */
	public static function splitWords($string)
	{
		$non_word = '\x{0000}-\x{002F}\x{003A}-\x{0040}\x{005b}-\x{0060}\x{007B}-\x{007E}\x{00A0}-\x{00BF}\s';
		if (preg_match_all('/([^' . $non_word . ']{2,})/msu', strip_tags($string), $match))
		{
			foreach ($match[1] as $i => $v) {
				$match[1][$i] = mb_strtolower($v);
			}

			return $match[1];
		}
		return [];
	}

	/**
	 * Transform a given string into an array of "indexes string"
	 *
	 * @param string $words
	 * @return array
	 */
	public static function toIndexes($words)
	{
		return array_unique(self::splitWords($words));
	}

	/**
	 * Transform a given string into a string of "indexes string"
	 *
	 * @param string $words
	 * @return string
	 */
	public static function toIndexesString($words)
	{
		return implode(' ', self::toIndexes($words));
	}
}
