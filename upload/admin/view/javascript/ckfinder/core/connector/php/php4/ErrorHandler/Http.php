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
 * @subpackage ErrorHandler
 * @copyright CKSource - Frederico Knabben
 */

/**
 * Include base error handling class
 */
require_once CKFINDER_CONNECTOR_LIB_DIR . "/ErrorHandler/Base.php";

/**
 * HTTP error handler
 *
 * @package CKFinder
 * @subpackage ErrorHandler
 * @copyright CKSource - Frederico Knabben
 */
class CKFinder_Connector_ErrorHandler_Http extends CKFinder_Connector_ErrorHandler_Base
{
    /**
     * Throw file upload error, return true if error has been thrown, false if error has been catched
     *
     * @param int $number
     * @param string $text
     * @access public
     */
    function throwError($number, $text = false, $exit = true)
    {
        if ($this->_catchAllErrors || in_array($number, $this->_skipErrorsArray)) {
            return false;
        }

        switch ($number)
        {
            case CKFINDER_CONNECTOR_ERROR_INVALID_REQUEST:
            case CKFINDER_CONNECTOR_ERROR_INVALID_NAME:
            case CKFINDER_CONNECTOR_ERROR_THUMBNAILS_DISABLED:
            case CKFINDER_CONNECTOR_ERROR_UNAUTHORIZED:
                header("HTTP/1.0 403 Forbidden");
                header("X-CKFinder-Error: ". $number);
                break;

            case CKFINDER_CONNECTOR_ERROR_ACCESS_DENIED:
                header("HTTP/1.0 500 Internal Server Error");
                header("X-CKFinder-Error: ".$number);
                break;

            default:
                header("HTTP/1.0 404 Not Found");
                header("X-CKFinder-Error: ". $number);
                break;
        }

        if ($exit) {
            exit;
        }
    }
}
