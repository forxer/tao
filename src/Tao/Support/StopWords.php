<?php
namespace Tao\Support;

class StopWords
{
	private $stopwords = [];

	private $path;

	public function __construct($path = null)
	{
		if (null === $path) {
			$path = __DIR__ . '/StopWords';
		}

		$this->path = $path;
	}

	/**
	 * Return stop word list for given locale.
	 *
	 * @param string $locale
	 * @return array
	 */
	public function get($locale = 'en')
	{
		if (isset($this->stopwords[$locale])) {
			return $this->stopwords[$locale];
		}

		$this->stopwords[$locale] = [];

		$filename = $this->path . '/' . $locale . '.php';

		if (file_exists($filename)) {
			$this->stopwords[$locale] = require $filename;
		}

		return $this->stopwords[$locale];
	}

	/**
	 * Return all existing stopwords in an associative array.
	 *
	 * @return array
	 */
	public function getAll()
	{
		//...
	}
}
