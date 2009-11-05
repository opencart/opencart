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
 * Handle RenameFolder command
 *
 * @package CKFinder
 * @subpackage CommandHandlers
 * @copyright CKSource - Frederico Knabben
 */
class CKFinder_Connector_CommandHandler_RenameFolder extends CKFinder_Connector_CommandHandler_XmlCommandHandlerBase
{
    /**
     * Command name
     *
     * @access private
     * @var string
     */
    private $command = "RenameFolder";


    /**
     * handle request and build XML
     * @access protected
     *
     */
    protected function buildXml()
    {
        if (!$this->_currentFolder->checkAcl(CKFINDER_CONNECTOR_ACL_FOLDER_RENAME)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
        }

        if (!isset($_GET["NewFolderName"])) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_NAME);
        }

        $newFolderName = CKFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding($_GET["NewFolderName"]);
        $resourceTypeInfo = $this->_currentFolder->getResourceTypeConfig();

        if (!CKFinder_Connector_Utils_FileSystem::checkFileName($newFolderName) || $resourceTypeInfo->checkIsHiddenFolder($newFolderName)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_NAME);
        }

        // The root folder cannot be deleted.
        if ($this->_currentFolder->getClientPath() == "/") {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }

        $oldFolderPath = $this->_currentFolder->getServerPath();
        $bMoved = false;

        if (!is_dir($oldFolderPath)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }

        //let's calculate new folder name
        $newFolderPath = dirname($oldFolderPath).DIRECTORY_SEPARATOR.$newFolderName.DIRECTORY_SEPARATOR;

        if (file_exists($newFolderPath)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_ALREADY_EXIST);
        }

        $bMoved = @rename($oldFolderPath, $newFolderPath);

        if (!$bMoved) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
        } else {
            $newThumbsServerPath = dirname($this->_currentFolder->getThumbsServerPath()) . '/' . $newFolderName . '/';
            if (!@rename($this->_currentFolder->getThumbsServerPath(), $newThumbsServerPath)) {
                CKFinder_Connector_Utils_FileSystem::unlink($this->_currentFolder->getThumbsServerPath());
            }
        }

        $newFolderPath = preg_replace(",[^/]+/?$,", $newFolderName, $this->_currentFolder->getClientPath()) . '/';
        $newFolderUrl = $resourceTypeInfo->getUrl() . ltrim($newFolderPath, '/');

        $oRenameNode = new Ckfinder_Connector_Utils_XmlNode("RenamedFolder");
        $this->_connectorNode->addChild($oRenameNode);

        $oRenameNode->addAttribute("newName", CKFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($newFolderName));
        $oRenameNode->addAttribute("newPath", CKFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($newFolderPath));
        $oRenameNode->addAttribute("newUrl", CKFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($newFolderUrl));
    }
}
