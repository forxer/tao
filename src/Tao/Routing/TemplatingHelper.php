<?php
namespace Tao\Routing;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Templating\Helper\Helper;

class TemplatingHelper extends Helper
{
	protected $generator;

	/**
	 * Constructor.
	 *
	 * @param UrlGeneratorInterface $router A Router instance
	 */
	public function __construct(UrlGeneratorInterface $router)
	{
		$this->generator = $router;
	}

	/**
	 * Generates a URL from the given parameters.
	 *
	 * @param string $name The name of the route
	 * @param mixed $parameters An array of parameters
	 * @param bool|string $referenceType The type of reference (one of the constants in UrlGeneratorInterface)
	 *
	 * @return string The generated URL
	 *
	 * @see UrlGeneratorInterface
	 */
	public function generate($name, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
	{
		return $this->generator->generate($name, $parameters, $referenceType);
	}

	/**
	 * Returns the canonical name of this helper.
	 *
	 * @return string The canonical name
	 */
	public function getName()
	{
		return 'router';
	}
}
