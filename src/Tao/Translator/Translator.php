<?php
namespace Tao\Routing;

use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Translator as BaseTranslator;
use Tao\Application;

class Translator extends BaseTranslator
{
	protected $app;

	/**
	 * Router constructor.
	 *
	 * @param Application $app
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;

		return parent::__construct(
			$app['visitor']->getLanguage(),
			new MessageSelector()
		);
	}
}
