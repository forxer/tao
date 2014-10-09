<?php
namespace Tao\Templating;

use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap3View;
use Symfony\Component\Templating\Loader\LoaderInterface;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Tao\Application;
use Tao\Templating\Escaper\EscaperInterface;

class Templating extends PhpEngine
{
	protected $app;

	public function __construct(Application $app, TemplateNameParserInterface $templateNameParser, LoaderInterface $loader, EscaperInterface $escaper)
	{
		$this->app = $app;

		parent::__construct(new $templateNameParser(), $loader);

		$this->setEscaper('html', [$escaper, 'html']);
		$this->setEscaper('html_attr', [$escaper, 'htmlAttr']);
		$this->setEscaper('js', [$escaper, 'js']);
		$this->setEscaper('url', [$escaper, 'url']);
		$this->setEscaper('css', [$escaper, 'css']);
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

	/**
	 * $view->escape($value) alias
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function e($value)
	{
		return $this->escape($value);
	}

	/**
	 * $view->escape($value, 'html_attr') alias
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function escapeHtmlAttr($value)
	{
		return $this->escape($value, 'html_attr');
	}

	/**
	 * $view->escape($value, 'html_attr') alias
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function eAttr($value)
	{
		return $this->escape($value, 'html_attr');
	}

	/**
	 * $view->escape($value, 'js') alias
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function escapeJs($value)
	{
		return $this->escape($value, 'js');
	}

	/**
	 * $view->escape($value, 'js') alias
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function eJs($value)
	{
		return $this->escape($value, 'js');
	}

	/**
	 * $view->escape($value, 'url') alias
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function escapeUrl($value)
	{
		return $this->escape($value, 'url');
	}

	/**
	 * $view->escape($value, 'url') alias
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function eUrl($value)
	{
		return $this->escape($value, 'url');
	}

	/**
	 * $view->escape($value, 'css') alias
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function escapeCss($value)
	{
		return $this->escape($value, 'css');
	}

	/**
	 * $view->escape($value, 'css') alias
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function eCss($value)
	{
		return $this->escape($value, 'css');
	}
}
