<?php
namespace Tao\Templating\Escaper;

use \InvalidArgumentException;

class SimpleEscaper implements EscaperInterface
{
	/**
	 * Current encoding for escaping. If not UTF-8, we convert strings from this encoding
	 * pre-escaping and back to this encoding post-escaping.
	 *
	 * @var string
	 */
	protected $encoding = 'utf-8';

	/**
	 * List of all encoding supported by this class
	 *
	 * @var array
	 */
	protected $supportedEncodings = array(
		'iso-8859-1',   'iso8859-1',    'iso-8859-5',   'iso8859-5',
		'iso-8859-15',  'iso8859-15',   'utf-8',        'cp866',
		'ibm866',       '866',          'cp1251',       'windows-1251',
		'win-1251',     '1251',         'cp1252',       'windows-1252',
		'1252',         'koi8-r',       'koi8-ru',      'koi8r',
		'big5',         '950',          'gb2312',       '936',
		'big5-hkscs',   'shift_jis',    'sjis',         'sjis-win',
		'cp932',        '932',          'euc-jp',       'eucjp',
		'eucjp-win',    'macroman'
	);

	/**
	 * Constructor: Single parameter allows setting of global encoding for use by
	 * the current object.
	 *
	 * @param string $encoding
	 * @throws InvalidArgumentException
	 */
	public function __construct($encoding = null)
	{
		if ($encoding !== null)
		{
			$encoding = (string) $encoding;

			if ($encoding === '')
			{
				throw new InvalidArgumentException(
					get_class($this) . ' constructor parameter does not allow a blank value'
				);
			}

			$encoding = strtolower($encoding);
			if (!in_array($encoding, $this->supportedEncodings))
			{
				throw new InvalidArgumentException(
					'Value of \'' . $encoding . '\' passed to ' . get_class($this)
					. ' constructor parameter is invalid. Provide an encoding supported by htmlspecialchars()'
				);
			}

			$this->encoding = $encoding;
		}
	}

	/**
	 * Return the encoding that all output/input is expected to be encoded in.
	 *
	 * @return string
	 */
	public function getEncoding()
	{
		return $this->encoding;
	}

	public function html($string)
	{
		return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, $this->encoding, false);
	}

	public function htmlAttr($string)
	{
		return htmlspecialchars($string, ENT_COMPAT | ENT_SUBSTITUTE, $this->encoding, false);
	}

	public function js($string)
	{
		$string = htmlspecialchars($string, ENT_NOQUOTES | ENT_SUBSTITUTE, $this->encoding, false);

		$string = str_replace(["'", '"' ], ["\'", '\"'], $string);

		return $string;
	}

	public function url($string)
	{
		return rawurlencode($string);
	}

	public function css($string)
	{
		return $string;
	}
}
