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
 * Include base XML command handler
 */
require_once CKFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/XmlCommandHandlerBase.php";

/**
 * Handle RenameFile command
 *
 * @package CKFinder
 * @subpackage CommandHandlers
 * @copyright CKSource - Frederico Knabben
 */
class CKFinder_Connector_CommandHandler_RenameFile extends CKFinder_Connector_CommandHandler_XmlCommandHandlerBase
{
    /**
     * Command name
     *
     * @access private
     * @var string
     */
    private $command = "RenameFile";


    /**
     * handle request and build XML
     * @access protected
     *
     */
    protected function buildXml()
    {
        if (!$this->_currentFolder->checkAcl(CKFINDER_CONNECTOR_ACL_FILE_RENAME)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
        }

        if (!isset($_GET["fileName"])) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_NAME);
        }
        if (!isset($_GET["newFileName"])) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_NAME);
        }

        $fileName = CKFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding($_GET["fileName"]);
        $newFileName = CKFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding($_GET["newFileName"]);

        $oRenamedFileNode = new Ckfinder_Connector_Utils_XmlNode("RenamedFile");
        $this->_connectorNode->addChild($oRenamedFileNode);
        $oRenamedFileNode->addAttribute("name", CKFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($fileName));

        $resourceTypeInfo = $this->_currentFolder->getResourceTypeConfig();
        if (!$resourceTypeInfo->checkExtension($newFileName)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_EXTENSION);
        }

        if (!CKFinder_Connector_Utils_FileSystem::checkFileName($fileName) || $resourceTypeInfo->checkIsHiddenFile($fileName)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }

        if (!CKFinder_Connector_Utils_FileSystem::checkFileName($newFileName) || $resourceTypeInfo->checkIsHiddenFile($newFileName)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_NAME);
        }

        if (!$resourceTypeInfo->checkExtension($fileName, false)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }

        $filePath = CKFinder_Connector_Utils_FileSystem::combinePaths($this->_currentFolder->getServerPath(), $fileName);
        $newFilePath = CKFinder_Connector_Utils_FileSystem::combinePaths($this->_currentFolder->getServerPath(), $newFileName);

        $bMoved = false;

        if (!file_exists($filePath)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_FILE_NOT_FOUND);
        }

        if (!is_writable(dirname($newFilePath))) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
        }

        if (!is_writable($filePath)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
        }

        $bMoved = @rename($filePath, $newFilePath);

        if (!$bMoved) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_UNKNOWN, "File " . CKFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($fileName) . "has not been renamed");
        } else {
            $oRenamedFileNode->addAttribute("newName", CKFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($newFileName));

            $thumbPath = CKFinder_Connector_Utils_FileSystem::combinePaths($this->_currentFolder->getThumbsServerPath(), $fileName);
            CKFinder_Connector_Utils_FileSystem::unlink($thumbPath);
        }
    }
}
