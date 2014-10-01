<?php
namespace Tao\Templating;

use Symfony\Component\Templating\Helper\Helper;
use Tao\Html\FormElements;

class FormElementsHelper extends Helper
{
	/**
	 * Retourne un champ de formulaire de type select.
	 *
	 * @param mixed $mNameId Le nom et l'identifiant du champ
	 * @param array $aData Le tableau contenant les lignes d'option du select
	 * @param mixed $mDefault La valeur sélectionnée par défaut
	 * @param string $sClass La classe CSS de l'élément
	 * @param integer $iTabindex Le tabindex de l'élément
	 * @param boolean $bDisabled Désactiver ou non le champ
	 * @param boolean $bMultiple Choix multiple
	 * @param string $sExtraHtml Du HTML en plus à mettre dans l'élément
	 * @return string
	 */
	public function select()
	{
		return call_user_func_array('Tao\Html\FormElements::select', func_get_args());
	}

	/**
	 * Retourne les options d'un élément select.
	 *
	 * @param array $aData Le tableau contenant les lignes d'option du select
	 * @param mixed $mDefault La valeur sélectionnée par défaut
	 * @return string
	 */
	public function selectOptions()
	{
		return call_user_func_array('Tao\Html\FormElements::selectOptions', func_get_args());
	}

	/**
	 * Retourne un champ de formulaire de type radio.
	 *
	 * @param mixed $mNameId Le nom et l'identifiant du champ
	 * @param mixed $value La valeur de l'élément
	 * @param boolean $checked L'état par défaut de l'élément
	 * @param string $sClass La classe CSS de l'élément
	 * @param integer $iTabindex Le tabindex de l'élément
	 * @param boolean $bDisabled Désactiver ou non le champ
	 * @param string $sExtraHtml Du HTML en plus à mettre dans l'élément
	 * @return string
	 */
	public function radio()
	{
		return call_user_func_array('Tao\Html\FormElements::radio', func_get_args());
	}

	/**
	 * Retourne un champ de formulaire de type checkbox.
	 *
	 * @param mixed $mNameId Le nom et l'identifiant du champ
	 * @param mixed $value La valeur de l'élément
	 * @param boolean $checked L'état par défaut de l'élément
	 * @param string $sClass La classe CSS de l'élément
	 * @param integer $iTabindex Le tabindex de l'élément
	 * @param boolean $bDisabled Désactiver ou non le champ
	 * @param string $sExtraHtml Du HTML en plus à mettre dans l'élément
	 * @return string
	 */
	public function checkbox()
	{
		return call_user_func_array('Tao\Html\FormElements::checkbox', func_get_args());
	}

	/**
	 * Retourne un champ de formulaire de type text.
	 *
	 * @param mixed $mNameId Le nom et l'identifiant du champ
	 * @param integer $size La taille de l'élément en nombre de caractères
	 * @param integer $max Le nombre maximum de caractères
	 * @param string $sDefault La valeur par défaut de lélément
	 * @param string $sClass La classe CSS de l'élément
	 * @param integer $iTabindex Le tabindex de l'élément
	 * @param boolean $bDisabled Désactiver ou non le champ
	 * @param string $sExtraHtml Du HTML en plus à mettre dans l'élément
	 * @return string
	 */
	public function text()
	{
		return call_user_func_array('Tao\Html\FormElements::text', func_get_args());
	}

	/**
	 * Retourne un champ de formulaire de type file.
	 *
	 * @param mixed $mNameId Le nom et l'identifiant du champ
	 * @param string $sDefault La valeur par défaut de lélément
	 * @param string $sClass La classe CSS de l'élément
	 * @param integer $iTabindex Le tabindex de l'élément
	 * @param boolean $bDisabled Désactiver ou non le champ
	 * @param string $sExtraHtml Du HTML en plus à mettre dans l'élément
	 * @return string
	 */
	public function file()
	{
		return call_user_func_array('Tao\Html\FormElements::file', func_get_args());
	}

	/**
	 * Retourne un champ de formulaire de type password.
	 *
	 * @param mixed $mNameId Le nom et l'identifiant du champ
	 * @param integer $size La taille de l'élément en nombre de caractères
	 * @param integer $max Le nombre maximum de caractères
	 * @param string $sDefault La valeur par défaut de lélément
	 * @param string $sClass La classe CSS de l'élément
	 * @param integer $iTabindex Le tabindex de l'élément
	 * @param boolean $bDisabled Désactiver ou non le champ
	 * @param string $sExtraHtml Du HTML en plus à mettre dans l'élément
	 * @return string
	 */
	public function password()
	{
		return call_user_func_array('Tao\Html\FormElements::password', func_get_args());
	}

	/**
	 * Retourne un champ de formulaire de type textarea.
	 *
	 * @param mixed $mNameId Le nom et l'identifiant du champ
	 * @param integer $iCols Le nombre de colonnes
	 * @param integer $iRows Le nombre de lignes
	 * @param string $sDefault La valeur par défaut de lélément
	 * @param string $sClass La classe CSS de l'élément
	 * @param integer $iTabindex Le tabindex de l'élément
	 * @param boolean $bDisabled Désactiver ou non le champ
	 * @param string $sExtraHtml Du HTML en plus à mettre dans l'élément
	 * @return string
	 */
	public function textarea()
	{
		return call_user_func_array('Tao\Html\FormElements::textarea', func_get_args());
	}

	/**
	 * Retourne un champ de formulaire de type hidden.
	 *
	 * @param mixed $mNameId Le nom et l'identifiant du champ
	 * @param string $value La valeur par de lélément
	 * @return string
	 */
	public function hidden()
	{
		return call_user_func_array('Tao\Html\FormElements::hidden', func_get_args());
	}


	public function getName()
	{
		return 'form';
	}
}
