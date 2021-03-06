<?php
namespace Tao\Templating\Helpers;

use Symfony\Component\Templating\Helper\Helper;

class FormElements extends Helper
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
    public function select($mNameId, $aData, $mDefault = null, $sClass = null, $iTabindex = null, $bDisabled = false, $bMultiple = false, $sExtraHtml = null)
    {
        self::getNameAndId($mNameId, $sName, $sId);

        $res = '<select name="' . $sName . '"';
        $res .= null === $sId ? '' : ' id="' . $sId . '"';
        $res .= null === $sClass ? '' : ' class="' . $sClass . '"';
        $res .= null === $iTabindex ? '' : ' tabindex="' . $iTabindex . '"';
        $res .= $bDisabled ? ' disabled="disabled"' : '';
        $res .= $bMultiple ? ' multiple="multiple"' : '';
        $res .= $sExtraHtml;
        $res .= '>' . PHP_EOL;

        $res .= self::selectOptions($aData, $mDefault);

        $res .= '</select>' . PHP_EOL;

        return $res;
    }

    /**
     * Retourne les options d'un élément select.
     *
     * @param array $aData Le tableau contenant les lignes d'option du select
     * @param mixed $mDefault La valeur sélectionnée par défaut
     * @return string
     */
    public function selectOptions($aData, $mDefault)
    {
        $res = '';
        $option = '<option value="%1$s"%3$s>%2$s</option>' . PHP_EOL;
        $optgroup = '<optgroup label="%1$s">' . PHP_EOL . '%2$s' . "</optgroup>\n";

        foreach ($aData as $k => $v)
        {
            if (is_array($v)) {
                $res .= sprintf($optgroup, $k, self::selectOptions($v, $mDefault));
            }
            elseif ($v instanceof SelectOption) {
                $res .= $v->render($mDefault);
            }
            else
            {
                if (is_array($mDefault)) {
                    $s = in_array($v, $mDefault) ? ' selected="selected"' : '';
                }
                else {
                    $s = $v == $mDefault ? ' selected="selected"' : '';
                }

                $res .= sprintf($option, $v, $k, $s);
            }
        }

        return $res;
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
    public function radio($mNameId, $value, $checked = '', $sClass = null, $iTabindex = null, $bDisabled = false, $sExtraHtml = null)
    {
        self::getNameAndId($mNameId, $sName, $sId);

        $res = '<input type="radio" name="' . $sName . '" value="' . $value . '"';
        $res .= null === $sId ? '' : ' id="' . $sId . '"';
        $res .= $checked ? ' checked="checked"' : '';
        $res .= null === $sClass ? '' : ' class="' . $sClass . '"';
        $res .= null === $iTabindex ? '' : ' tabindex="' . $iTabindex . '"';
        $res .= $bDisabled ? ' disabled="disabled"' : '';
        $res .= $sExtraHtml;
        $res .= '>' . PHP_EOL;

        return $res;
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
    public function checkbox($mNameId, $value, $checked = '', $sClass = null, $iTabindex = null, $bDisabled = false, $sExtraHtml = null)
    {
        self::getNameAndId($mNameId, $sName, $sId);

        $res = '<input type="checkbox" name="' . $sName . '" value="' . $value . '"';
        $res .= null === $sId ? '' : ' id="' . $sId . '"';
        $res .= $checked ? ' checked="checked"' : '';
        $res .= null === $sClass ? '' : ' class="' . $sClass . '"';
        $res .= null === $iTabindex ? '' : ' tabindex="' . $iTabindex . '"';
        $res .= $bDisabled ? ' disabled="disabled"' : '';
        $res .= $sExtraHtml;
        $res .= '>' . PHP_EOL;

        return $res;
    }

    /**
     * Retourne un champ de formulaire de type text.
     *
     * @param mixed $mNameId Le nom et l'identifiant du champ
     * @param integer $size La taille de l'élément en nombre de caractères
     * @param integer $max Le nombre maximum de caractères
     * @param string $sDefault La valeur par défaut de lélément
     * @param string $sClass La classe CSS de l'élément
     * @param string $sPlaceholder
     * @param integer $iTabindex Le tabindex de l'élément
     * @param boolean $bDisabled Désactiver ou non le champ
     * @param string $sExtraHtml Du HTML en plus à mettre dans l'élément
     * @return string
     */
    public function text($mNameId, $size, $max = null, $sDefault = null, $sClass = null, $sPlaceholder = null, $iTabindex = null, $bDisabled = false, $sExtraHtml = null)
    {
        self::getNameAndId($mNameId, $sName, $sId);

        $res = '<input type="text" size="' . $size . '" name="' . $sName . '"';
        $res .= null === $sId ? '' : ' id="' . $sId . '"';
        $res .= null === $max ? '' : ' maxlength="' . $max . '"';
        $res .= null === $sDefault ? '' : ' value="' . $sDefault . '"';
        $res .= null === $sClass ? '' : ' class="' . $sClass . '"';
        $res .= null === $sPlaceholder ? '' : ' placeholder="' . $sPlaceholder . '"';
        $res .= null === $iTabindex ? '' : ' tabindex="' . $iTabindex . '"';
        $res .= $bDisabled ? ' disabled="disabled"' : '';
        $res .= $sExtraHtml;
        $res .= '>';

        return $res;
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
    public function file($mNameId, $sDefault = null, $sClass = null, $iTabindex = null, $bDisabled = false, $sExtraHtml = null)
    {
        self::getNameAndId($mNameId, $sName, $sId);

        $res = '<input type="file" name="' . $sName . '"';
        $res .= null === $sId ? '' : ' id="' . $sId . '"';
        $res .= null === $sDefault ? '' : ' value="' . $sDefault . '"';
        $res .= null === $sClass ? '' : ' class="' . $sClass . '"';
        $res .= null === $iTabindex ? '' : ' tabindex="' . $iTabindex . '"';
        $res .= $bDisabled ? ' disabled="disabled"' : '';
        $res .= $sExtraHtml;
        $res .= ' />';

        return $res;
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
    public function password($mNameId, $size, $max = null, $sDefault = null, $sClass = null, $iTabindex = null, $bDisabled = false, $sExtraHtml = null)
    {
        self::getNameAndId($mNameId, $sName, $sId);

        $res = '<input type="password" size="' . $size . '" name="' . $sName . '"';
        $res .= null === $sId ? '' : ' id="' . $sId . '"';
        $res .= null === $max ? '' : ' maxlength="' . $max . '"';
        $res .= null === $sDefault ? '' : ' value="' . $sDefault . '"';
        $res .= null === $sClass ? '' : ' class="' . $sClass . '"';
        $res .= null === $iTabindex ? '' : ' tabindex="' . $iTabindex . '"';
        $res .= $bDisabled ? ' disabled="disabled"' : '';
        $res .= $sExtraHtml;
        $res .= '>';

        return $res;
    }

    /**
     * Retourne un champ de formulaire de type textarea.
     *
     * @param mixed $mNameId Le nom et l'identifiant du champ
     * @param integer $iCols Le nombre de colonnes
     * @param integer $iRows Le nombre de lignes
     * @param string $sDefault La valeur par défaut de lélément
     * @param string $sClass La classe CSS de l'élément
     * @param string $sPlaceholder
     * @param integer $iTabindex Le tabindex de l'élément
     * @param boolean $bDisabled Désactiver ou non le champ
     * @param string $sExtraHtml Du HTML en plus à mettre dans l'élément
     * @return string
     */
    public function textarea($mNameId, $iCols = null, $iRows = null, $sDefault = null, $sClass = null, $sPlaceholder = null, $iTabindex = null, $bDisabled = false, $sExtraHtml = null)
    {
        self::getNameAndId($mNameId, $sName, $sId);

        $res = '<textarea';
        $res .= null === $iCols ? '' : ' cols="' . $iCols . '"';
        $res .= null === $iRows ? '' : ' rows="' . $iRows . '"';
        $res .= ' name="' . $sName . '"';
        $res .= null === $sId ? '' : ' id="' . $sId . '"';
        $res .= null === $sPlaceholder ? '' : ' placeholder="' . $sPlaceholder . '"';
        $res .= null === $iTabindex ? '' : ' tabindex="' . $iTabindex . '"';
        $res .= null === $sClass ? '' : ' class="' . $sClass . '"';
        $res .= $bDisabled ? ' disabled="disabled"' : '';
        $res .= $sExtraHtml . '>';
        $res .= $sDefault;
        $res .= '</textarea>';

        return $res;
    }

    /**
     * Retourne un champ de formulaire de type hidden.
     *
     * @param mixed $mNameId Le nom et l'identifiant du champ
     * @param string $value La valeur par de lélément
     * @return string
     */
    public function hidden($mNameId, $value)
    {
        self::getNameAndId($mNameId, $sName, $sId);

        $res = '<input type="hidden" name="' . $sName . '" value="' . $value . '" ';
        $res .= null === $sId ? '' : ' id="' . $sId . '"';
        $res .= '>';

        return $res;
    }

    /**
     * Retourne l'identifiant et le nom du champ en
     * fonction des paramètres passés en argument.
     *
     * @param mixed $mNameId
     * @param string $sName
     * @param string $sId
     */
    protected static function getNameAndId($mNameId, &$sName, &$sId)
    {
        if (is_array($mNameId))
        {
            $sName = $mNameId[0];
            $sId = !empty($mNameId[1]) ? $mNameId[1] : null;
        }
        else {
            $sName = $sId = $mNameId;
        }
    }

    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     */
    public function getName()
    {
        return 'form';
    }
}
