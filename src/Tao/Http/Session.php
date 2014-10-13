<?php
namespace Tao\Http;

use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface;
use Symfony\Component\HttpFoundation\Session\Flash\AutoExpireFlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session as BaseSession;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;
use Tao\Application;

class Session extends BaseSession
{
	protected $app;

	protected $language;

	/**
	 * The namespace for the session variable and form inputs.
	 *
	 * @var string
	 */
	protected $sTokenNamespace;

	/**
	 * @var string
	 */
	private $autoExpireFlashName;

	/**
	 * Constructor.
	 *
	 * @param SessionStorageInterface $storage    			A SessionStorageInterface instance.
	 * @param AttributeBagInterface   $attributes 			An AttributeBagInterface instance, (defaults null for default AttributeBag)
	 * @param FlashBagInterface       $flashes    			A FlashBagInterface instance (defaults null for default FlashBag)
	 * @param FlashBagInterface       $autoExpireFlashBag   A FlashBagInterface instance (defaults null for default FlashBag)
	 */
	public function __construct(Application $app, SessionStorageInterface $storage = null, AttributeBagInterface $attributes = null, FlashBagInterface $flashes = null, FlashBagInterface $autoExpireFlashBag = null, $sTokenNamespace = 'csrf')
	{
		$this->app = $app;

		parent::__construct($storage, $attributes, $flashes);

		$autoExpireFlashBag = $autoExpireFlashBag ?: new AutoExpireFlashBag();
		$this->autoExpireFlashName = $autoExpireFlashBag->getName();
		$this->registerBag($autoExpireFlashBag);

		$this->start();

		$this->sTokenNamespace = $sTokenNamespace;

		$this->setToken();
	}

	/**
	 * Gets the autoExpireFlashbag.
	 *
	 * @return FlashBagInterface
	 */
	public function getAutoExpireFlashBag()
	{
		return $this->getBag($this->autoExpireFlashName);
	}

	/**
	 * Return the token from session.
	 *
	 * @return string
	 */
	public function getToken()
	{
		return $this->get($this->sTokenNamespace, '');
	}

	/**
	 * Verify if supplied token matches the stored token.
	 *
	 * @param string $userToken
	 * @return boolean
	 */
	public function isValidToken($userToken)
	{
		$bIsValid = ($userToken === $this->getToken());

		$this->generateToken();

		return $bIsValid;
	}

	/**
	 * Return the HTML input field with the token, and namespace
	 * as the name of the field
	 *
	 * @return string
	 */
	public function getTokenInputField()
	{
		return '<input type="hidden" name="' . $this->sTokenNamespace . '" value="' . htmlspecialchars($this->getToken()) . '" />';
	}

	public function getLanguage()
	{
		if (null === $this->language) {
			$this->setLanguage();
		}

		return $this->language;
	}

	public function setLanguage($sLanguage = null)
	{
		if (null === $sLanguage) {
			$sLanguage = $this->getPreferedLanguage();
		}

		if (!array_key_exists($sLanguage, $this->app['translator.locales'])) {
			$sLanguage = $this->application->config['translator.fallback'];
		}

		$this->set('language', $sLanguage);

		$this->language = $sLanguage;
	}

	protected function getPreferedLanguage()
	{
		if ($this->has('language')) {
			return $this->get('language');
		}

		return $this->app['request']->getPreferredLanguage();
	}

	/**
	 * Generates a new token value and stores it in session, or else
	 * does nothing if one already exists in session.
	 *
	 * @return void
	 */
	protected function setToken()
	{
		$storedToken = $this->getToken();

		if ($storedToken === '') {
			$this->generateToken();
		}
	}

	/**
	 * Remove token.
	 *
	 * @return void
	 */
	protected function removeToken()
	{
		$this->remove($this->sTokenNamespace);
	}

	protected function generateToken()
	{
		$sToken = sha1(uniqid(mt_rand(), true));

		$this->set($this->sTokenNamespace, $sToken);
	}
}
