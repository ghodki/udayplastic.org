<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Inject safe defaults for inventory footer alert vars on every view load.
 */
class MY_Loader extends CI_Loader
{
    public function view($view, $vars = array(), $return = FALSE)
    {
        if (!is_array($vars)) {
            $vars = $this->_ci_object_to_array($vars);
        }

        if (!array_key_exists('NewFinancialYear', $vars)) {
            $vars['NewFinancialYear'] = null;
        }
        if (!array_key_exists('NewFinancialYearFlag', $vars)) {
            $vars['NewFinancialYearFlag'] = null;
        }
        if (!array_key_exists('ChequeData', $vars)) {
            $vars['ChequeData'] = null;
        }

        return parent::view($view, $vars, $return);
    }
}
