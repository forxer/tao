<?php
namespace Tao\Controller;

trait SearchableControllerTrait
{
	protected $aCriteria;

	/**
	 * Méthode statique permettant de récupérer la liste des critères
	 * sans pour autant instancier le controller.
	 *
	 * Cette méthode doit être implémentée dans les classes afin de définir
	 * la liste des critères.
	 *
	 * @return array
	 */
	static public function getCriteriaList()
	{
		return [];
	}

	/**
	 * Retourne les clés des critères de recherche.
	 *
	 * @return array
	 */
	static public function getCriteriaKeys()
	{
		return array_keys(static::getCriteriaList());
	}

	/**
	 * Retrouve les éventuels critères de recherche en _SESSION
	 *
	 * @return void
	 */
	protected function setSearchCriteriaFromSession()
	{
		$this->initSearchCriteria();

		foreach ($this->aCriteria as $sIndex => $mValue) {
			$this->aCriteria[$sIndex] = $this->app['session']->get($sIndex, $mValue);
		}
	}

	/**
	 * Supprime les critères de recherche de la _SESSION
	 *
	 * @return void
	 */
	protected function removeSearchCriteriaFromSession()
	{
		$this->initSearchCriteria();

		foreach (array_keys($this->aCriteria) as $sIndex) {
			$this->app['session']->remove($sIndex);
		}
	}

	/**
	 * Retrouve les éventuels critères de recherche en _POST
	 *
	 * @return void
	 */
	protected function setSearchCriteriaFromPost()
	{
		$this->initSearchCriteria();

		foreach ($this->aCriteria as $sIndex => $mValue) {
			$this->aCriteria[$sIndex] = $this->app['request']->request->get($sIndex, $mValue);
		}
	}

	/**
	 * Retrouve les éventuels critères de recherche en _GET
	 *
	 * @return void
	 */
	protected function setSearchCriteriaFromQuery()
	{
		$this->initSearchCriteria();

		foreach ($this->aCriteria as $sIndex => $mValue) {
			$this->aCriteria[$sIndex] = $this->app['request']->query->get($sIndex, $mValue);
		}
	}

	/**
	 * Initialise les critères de recherche en interne.
	 *
	 * @return void
	 */
	protected function initSearchCriteria()
	{
		if (null === $this->aCriteria) {
			$this->aCriteria = self::getCriteriaList();
		}
	}
}
