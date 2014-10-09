<?php
namespace Tao\Templating\Helpers;

use Symfony\Component\Templating\Helper\Helper;
use Symfony\Component\Translation\TranslatorInterface;

class Translator extends Helper
{
	protected $translator;

	/**
	 * Constructor.
	 *
	 * @param TranslatorInterface $translator A TranslatorInterface instance
	 */
	public function __construct(TranslatorInterface $translator)
	{
		$this->translator = $translator;
	}

	/**
	 * @see TranslatorInterface::trans()
	 */
	public function trans($id, array $parameters = [], $domain = 'messages', $locale = null)
	{
		return $this->translator->trans($id, $parameters, $domain, $locale);
	}

	/**
	 * @see TranslatorInterface::transChoice()
	 */
	public function transChoice($id, $number, array $parameters = [], $domain = 'messages', $locale = null)
	{
		return $this->translator->transChoice($id, $number, $parameters, $domain, $locale);
	}

	/**
	 * Returns the canonical name of this helper.
	 *
	 * @return string The canonical name
	 */
	public function getName()
	{
		return 'translator';
	}
}
