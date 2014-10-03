<?php
namespace Tao\Templating;

use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap3View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Templating\Asset\PathPackage;
use Symfony\Component\Templating\Helper\AssetsHelper;
use Symfony\Component\Templating\Helper\SlotsHelper;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Tao\Application;
use Tao\Html\Escaper;
use Tao\Html\Modifiers;
use Tao\Templating\Helpers\Breadcrumb;
use Tao\Templating\Helpers\FormElements;
use Tao\Templating\Helpers\TitleTag;
use Zend\Escaper\Escaper;

class Templating extends PhpEngine
{
	protected $app;

	protected $escaper;

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

		$this->set(new TitleTag());
		$this->set(new Breadcrumb());
		$this->set(new FormElements());

		$this->escaper = new Escaper('utf-8');

		$this->addBuiltInEscapers();

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

	public function addBuiltInEscapers()
	{
		$this->setEscaper('html', [
			$this->escaper,
			'escapeHtml'
		]);

		$this->setEscaper('html_attr', [
			$this->escaper,
			'escapeHtmlAttr'
		]);

		$this->setEscaper('js', [
			$this->escaper,
			'escapeJs'
		]);

		$this->setEscaper('url', [
			$this->escaper,
			'escapeUrl'
		]);

		$this->setEscaper('css', [
			$this->escaper,
			'escapeCss'
		]);
	}

	public function escapeHtmlAttr($string)
	{
		return $this->escape($string, 'html_attr');
	}

	public function escapeJs($string)
	{
		return $this->escape($string, 'js');
	}

	public function escapeUrl($string)
	{
		return $this->escape($string, 'url');
	}

	public function escapeCss($string)
	{
		return $this->escape($string, 'css');
	}
}
