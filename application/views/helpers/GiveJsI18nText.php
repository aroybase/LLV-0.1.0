<?php
/**
 * PHP Class GiveJsI18nText.php
 * PHP Version 5
 *
 * @category    : default
 * @package     : default
 * @license     : Not for free use
 * @date        : 29/07/12
 * @author      : aroy <contact@aroy.fr>
 */

class App_View_Helper_GiveJsI18nText
{
    /**
     * Retourne les informations textuelles à donner au Javascript
     * @return string
     */
    public function giveJsI18nText()
    {
        $i18n = array();
        $i18n['datepicker']['closeText'] = _('GLOBAL_DATEPICKER_CLOSETEXT');
        $i18n['datepicker']['prevText'] = _('GLOBAL_DATEPICKER_PREVIOUS');
        $i18n['datepicker']['nextText'] = _('GLOBAL_DATEPICKER_NEXT');
        $i18n['datepicker']['currentText'] = _('GLOBAL_DATEPICKER_CURRENT');
        for ($i = 1; $i <= 12; $i++) {
            $i = $i < 10 ? '0' . $i : $i;
            $i18n['datepicker']['monthNames'][] = _('GLOBAL_MONTH_LABEL' . $i);
        }
        for ($i = 0; $i <= 7; $i++) {
            $i18n['datepicker']['dayNamesMin'][] = _('GLOBAL_DAY_LABEL_SHORT' . $i);
        }
        $i18n['datepicker']['weekHeader'] = _('GLOBAL_DATEPICKER_WEEK');
        $i18n['datepicker']['dateFormat'] = _('GLOBAL_DATEPICKER_FORMAT');
        $i18n['datepicker']['firstDay'] = _('GLOBAL_DATEPICKER_FIRSTDAY');
        return Zend_Json::encode($i18n);
    }
}