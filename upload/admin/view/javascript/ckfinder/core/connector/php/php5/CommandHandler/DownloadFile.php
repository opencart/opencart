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
 * Handle DownloadFile command
 *
 * @package CKFinder
 * @subpackage CommandHandlers
 * @copyright CKSource - Frederico Knabben
 */
class CKFinder_Connector_CommandHandler_DownloadFile extends CKFinder_Connector_CommandHandler_CommandHandlerBase
{
    /**
     * Command name
     *
     * @access private
     * @var string
     */
    private $command = "DownloadFile";

    /**
     * send response (file)
     * @access public
     *
     */
    public function sendResponse()
    {
        if (!function_exists('ob_list_handlers') || !ob_list_handlers()) {
            @ob_end_clean();
        }
        header("Content-Encoding: none");

        $this->checkConnector();
        $this->checkRequest();

        if (!$this->_currentFolder->checkAcl(CKFINDER_CONNECTOR_ACL_FILE_VIEW)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
        }

        $fileName = CKFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding($_GET["FileName"]);
        $_resourceTypeInfo = $this->_currentFolder->getResourceTypeConfig();

        if (!CKFinder_Connector_Utils_FileSystem::checkFileName($fileName)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }

        if (!$_resourceTypeInfo->checkExtension($fileName, false)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }

        $filePath = CKFinder_Connector_Utils_FileSystem::combinePaths($this->_currentFolder->getServerPath(), $fileName);
        if ($_resourceTypeInfo->checkIsHiddenFile($fileName) || !file_exists($filePath) || !is_file($filePath)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_FILE_NOT_FOUND);
        }

        $fileName = CKFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($fileName);

        header("Cache-Control: cache, must-revalidate");
        header("Pragma: public");
        header("Expires: 0");
        header("Content-type: application/octet-stream; name=\"" . $fileName . "\"");
        header("Content-Disposition: attachment; filename=\"" . str_replace("\"", "\\\"", $fileName). "\"");
        header("Content-Length: " . filesize($filePath));
        CKFinder_Connector_Utils_FileSystem::readfileChunked($filePath);
        exit;
    }
}
