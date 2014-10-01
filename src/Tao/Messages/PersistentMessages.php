<?php
namespace Tao\Messages;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

/**
 * In this implementation, messages will remain in the session
 * until they are explicitly retrieved or cleared.
 */
class PersistentMessages extends FlashBag implements MessagesInterface
{
	use MessagesTrait;
}
