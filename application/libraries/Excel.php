<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Thin wrapper around PhpSpreadsheet (loaded via Composer + PHPExcel_compat).
 */
class Excel extends PHPExcel
{
    public function __construct()
    {
        parent::__construct();
    }
}
