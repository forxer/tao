<?php
namespace Tao\Controller;

use Tao\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Controller de base.
 */
class Controller
{
	protected $app;

	/**
	 * Constructor.
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
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
	public function generateUrl($route, $parameters = [], $language = null, $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
	{
		return $this->app['router']->generate($route, $parameters, $language, $referenceType);
	}

	/**
	 * Returns a RedirectResponse to the given URL.
	 *
	 * @param string $url The URL to redirect to
	 * @param integer $status The status code to use for the Response
	 *
	 * @return RedirectResponse
	 */
	public function redirect($url, $status = 302)
	{
		return new RedirectResponse($url, $status);
	}

	/**
	 * Returns a RedirectResponse to the given route with the given parameters.
	 *
	 * @param string $route The name of the route
	 * @param array $parameters An array of parameters
	 * @param int $status The status code to use for the Response
	 *
	 * @return RedirectResponse
	 */
	protected function redirectToRoute($route, array $parameters = array(), $status = 302)
	{
		return $this->redirect($this->generateUrl($route, $parameters), $status);
	}

	public function jsonResponse($data = null, $status = 200, array $headers = [])
	{
		return new JsonResponse($data, $status, $headers);
	}

	/**
	 * Returns true if the template view exists.
	 *
	 * @param string|Symfony\Component\Templating\TemplateReferenceInterface $view
	 *        	A template name or a TemplateReferenceInterface instance
	 *
	 * @return Boolean true if the template view exists, false otherwise
	 */
	public function viewExists($view)
	{
		return $this->app['tpl']->exists($view);
	}

	/**
	 * Returns a rendered view.
	 *
	 * @param string $view The view name
	 * @param array $parameters An array of parameters to pass to the view
	 *
	 * @return string The rendered view
	 */
	public function renderView($view, array $parameters = [])
	{
		return $this->app['tpl']->render($view, $parameters);
	}

	/**
	 * Renders a view.
	 *
	 * @param string $view The view name
	 * @param array $parameters An array of parameters to pass to the view
	 * @param Response $response A response instance
	 *
	 * @return Response A Response instance
	 */
	public function render($view, array $parameters = [], Response $response = null)
	{
		if (null === $response) {
			$response = new Response();
		}

		return $this->app['tpl']->renderResponse($view, $parameters, $response);
	}

	/**
	 * Streams a view.
	 *
	 * @param string $view The view name
	 * @param array $parameters An array of parameters to pass to the view
	 * @param StreamedResponse $response A response instance
	 *
	 * @return StreamedResponse A StreamedResponse instance
	 */
	public function stream($view, array $parameters = [], StreamedResponse $response = null)
	{
		$templating = $this->app['tpl'];

		$callback = function () use($templating, $view, $parameters) {
			$templating->stream($view, $parameters);
		};

		if (null === $response) {
			return new StreamedResponse($callback);
		}

		$response->setCallback($callback);

		return $response;
	}

	/**
	 * Affichage page 401
	 */
	public function serve401()
	{
		$response = new Response();
		$response->setStatusCode(Response::HTTP_UNAUTHORIZED);

		return $this->render('Errors/401', [], $response);
	}

	/**
	 * Affichage page 404
	 */
	public function serve404()
	{
		$response = new Response();
		$response->setStatusCode(Response::HTTP_NOT_FOUND);

		return $this->render('Errors/404', [], $response);
	}

	/**
	 * Affichage page 503
	 */
	public function serve503()
	{
		$response = new Response();
		$response->setStatusCode(Response::HTTP_SERVICE_UNAVAILABLE);
		$response->headers->set('Retry-After', 3600);

		return $this->render('Errors/503', [], $response);
	}
}
