<?php
namespace Tao\Templating;

use Pagerfanta\View\TwitterBootstrap3View;
use Tao\Html\Escaper;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Templating\Asset\PathPackage;
use Symfony\Component\Templating\Helper\AssetsHelper;
use Symfony\Component\Templating\Helper\SlotsHelper;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Tao\Application;
use Tao\Html\Modifiers;
use Pagerfanta\Pagerfanta;

class Templating extends PhpEngine
{
	protected $app;

	public function __construct(Application $app)
	{
		$this->app = $app;

		$loader = new FilesystemLoader([ $app['dir.views'] . '/%name%.php' ]);

		if ($this->app['debug']) {
			$loader->setLogger($app['logger']);
		}

		parent::__construct(new TemplateNameParser(), $loader);

		$this->set(new SlotsHelper());

		$this->set(new AssetsHelper());
		$this->get('assets')->addPackage('assets', new PathPackage($app['assets_url']));
		$this->get('assets')->addPackage('components', new PathPackage($app['components_url']));

		$this->set(new FormElementsHelper());

		$this->addEscapers();

		$this->addGlobal('app', $app);
	}

	/**
	 * Generates a URL from the given parameters.
	 *
	 * @param string $route The name of the route
	 * @param mixed $parameters An array of parameters
	 * @param Boolean|string $referenceType sThe type of reference (one of the constants in UrlGeneratorInterface)
	 *
	 * @return string The generated URL
	 *
	 * @see UrlGeneratorInterface
	 */
	public function generateUrl($route, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
	{
		return $this->app['router']->generate($route, $parameters, $referenceType);
	}

	/**
	 * Renders a view and returns a Response.
	 *
	 * @param string $view The view name
	 * @param array $parameters An array of parameters to pass to the view
	 * @param Response $response A Response instance
	 *
	 * @return Response A Response instance
	 */
	public function renderResponse($view, array $parameters = [], Response $response = null)
	{
		if (null === $response) {
			$response = new Response();
		}

		$response->setContent($this->render($view, $parameters));

		return $response;
	}

	public function pluralize($iNumber, $zero, $one, $more)
	{
		$iNumber = (integer)$iNumber;

		if ($iNumber === 0) {
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
	 * Truncate a string to a certain length if necessary,
	 * optionally splitting in the middle of a word, and
	 * appending the $etc string or inserting $etc into the middle.
	 *
	 * @param unknown $string
	 * @param number $length
	 * @param string $etc
	 * @param string $bBreakWords
	 * @param string $bMiddle
	 */
	public function truncate($string, $length = 70, $etc = '...', $bBreakWords = false, $bMiddle = false)
	{
		return Modifiers::truncate($string, $length, $etc, $bBreakWords, $bMiddle);
	}

	public function nlToP($string)
	{
		return Modifiers::nlToP($string);
	}

	/**
	 * Retourne le HTML de la pagination.
	 *
	 * @todo Ne devrait pas se trouver ici, ou au moins devrait être découplé de TwitterBootstrap3View,
	 * mais en attendant ça fait le job...
	 *
	 * @param Pagerfanta\Pagerfanta $pager
	 * @param string $routeName
	 * @return string
	 */
	public function getPaginationHtml($pager, $routeName, array $routeAttributes = [])
	{
		return (new TwitterBootstrap3View())->render($pager,
			function($page) use ($routeName, $routeAttributes) {
				return $this->app['router']->generate($routeName, array_merge(['page' => $page], $routeAttributes));
			},
			[
				'proximity' => 3,
				'prev_message' => 'Précédente',
				'next_message' => 'Suivante'
			]
		);
	}

	public function escapeJs($string)
	{
		return $this->escape($string, 'js');
	}

	public function escapeHtmlAttr($string)
	{
		return $this->escape($string, 'html_attr');
	}

	public function addEscapers()
	{
		$this->setEscaper('html', [
			'Tao\Html\Escaper',
			'html'
		]);

		$this->setEscaper('html_attr', [
			'Tao\Html\Escaper',
			'attribute'
		]);

		$this->setEscaper('js', [
			'Tao\Html\Escaper',
			'js'
		]);
	}
}
