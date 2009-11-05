<?php
/*
 * CKFinder
 * ========
 * http://ckfinder.com
 * Copyright (C) 2007-2009, CKSource - Frederico Knabben. All rights reserved.
 *
 * The software, this file and its contents are subject to the CKFinder
 * License. Please read the license.txt file before using, installing, copying,
 * modifying or distribute this file or part of its contents. The contents of
 * this file is part of the Source Code of CKFinder.
 */

/**
 * @package CKFinder
 * @subpackage Utils
 * @copyright CKSource - Frederico Knabben
 */

/**
 * @package CKFinder
 * @subpackage Utils
 * @copyright CKSource - Frederico Knabben
 */
class CKFinder_Connector_Utils_Security
{

    /**
     * Strip quotes from global arrays
     * @access public
     */
    function getRidOfMagicQuotes()
    {
        if (get_magic_quotes_gpc()) {
            if (!empty($_GET)) {
                $this->stripQuotes($_GET);
            }
            if (!empty($_POST)) {
                $this->stripQuotes($_POST);
            }
            if (!empty($_COOKIE)) {
                $this->stripQuotes($_COOKIE);
            }
            if (!empty($_FILES)) {
                while (list($k,$v) = each($_FILES)) {
                    if (isset($_FILES[$k]['name'])) {
                        $this->stripQuotes($_FILES[$k]['name']);
                    }
                }
            }
        }
    }

    /**
     * Strip quotes from variable
     *
     * @access public
     * @param mixed $var
     * @param int $depth current depth
     * @param int $howDeep maximum depth
     */
    function stripQuotes(&$var, $depth=0, $howDeep=5)
    {
        if (is_array($var)) {
            if ($depth++<$howDeep) {
                while (list($k,$v) = each($var)) {
                    $this->stripQuotes($var[$k], $depth, $howDeep);
                }
            }
        } else {
            $var = stripslashes($var);
        }
    }
}
