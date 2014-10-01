<?php
namespace Tao\Html;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class HtmlServiceProvider implements ServiceProviderInterface
{
	public function register(Container $app)
	{
		$app['page'] = function() {
			return new Page();
		};
	}
}
