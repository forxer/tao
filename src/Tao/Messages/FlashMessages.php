<?php
namespace Tao\Messages;

use Symfony\Component\HttpFoundation\Session\Flash\AutoExpireFlashBag;

/**
 * In this implementation, messages set in one page-load will be available for display only on the next page load.
 * These messages will auto expire regardless of if they are retrieved or not.
 */
class FlashMessages extends AutoExpireFlashBag implements MessagesInterface
{
	use MessagesTrait;
}
