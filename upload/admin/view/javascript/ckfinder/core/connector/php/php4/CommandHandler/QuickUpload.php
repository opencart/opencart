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
 * @subpackage CommandHandlers
 * @copyright CKSource - Frederico Knabben
 */

/**
 * Include file upload command handler
 */
require_once CKFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/FileUpload.php";

/**
 * Handle QuickUpload command
 *
 * @package CKFinder
 * @subpackage CommandHandlers
 * @copyright CKSource - Frederico Knabben
 */
class CKFinder_Connector_CommandHandler_QuickUpload extends CKFinder_Connector_CommandHandler_FileUpload
{
    /**
     * Command name
     *
     * @access protected
     * @var string
     */
    var $command = "QuickUpload";

    function sendResponse()
    {
        $oRegistry =& CKFinder_Connector_Core_Factory::getInstance("Core_Registry");
        $oRegistry->set("FileUpload_url", $this->_currentFolder->getUrl());

        return parent::sendResponse();
    }
}
