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
 * Handle GetFolders command
 *
 * @package CKFinder
 * @subpackage CommandHandlers
 * @copyright CKSource - Frederico Knabben
 */
class CKFinder_Connector_CommandHandler_GetFolders extends CKFinder_Connector_CommandHandler_XmlCommandHandlerBase
{
    /**
     * Command name
     *
     * @access private
     * @var string
     */
    var $command = "GetFolders";

    /**
     * handle request and build XML
     * @access protected
     *
     */
    function buildXml()
    {
        $_config =& CKFinder_Connector_Core_Factory::getInstance("Core_Config");
        if (!$this->_currentFolder->checkAcl(CKFINDER_CONNECTOR_ACL_FOLDER_VIEW)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
        }

        // Map the virtual path to the local server path.
        $_sServerDir = $this->_currentFolder->getServerPath();

        if (!is_dir($_sServerDir)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_FOLDER_NOT_FOUND);
        }

        // Create the "Folders" node.
        $oFoldersNode = new Ckfinder_Connector_Utils_XmlNode("Folders");
        $this->_connectorNode->addChild($oFoldersNode);

        $files = array();
        if ($dh = @opendir($_sServerDir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != "." && $file != ".." && is_dir($_sServerDir . $file)) {
                    $files[] = $file;
                }
            }
            closedir($dh);
        } else {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
        }

        $resourceTypeInfo = $this->_currentFolder->getResourceTypeConfig();

        if (sizeof($files)>0) {
            natcasesort($files);
            $i=0;
            foreach ($files as $file) {
                $oAcl = $_config->getAccessControlConfig();
                $aclMask = $oAcl->getComputedMask($this->_currentFolder->getResourceTypeName(), $this->_currentFolder->getClientPath() . $file . "/");

                if (($aclMask & CKFINDER_CONNECTOR_ACL_FOLDER_VIEW) != CKFINDER_CONNECTOR_ACL_FOLDER_VIEW) {
                    continue;
                }
                if ($resourceTypeInfo->checkIsHiddenFolder($file)) {
                    continue;
                }

                // Create the "Folder" node.
                $oFolderNode[$i] = new Ckfinder_Connector_Utils_XmlNode("Folder");
                $oFoldersNode->addChild($oFolderNode[$i]);
                $oFolderNode[$i]->addAttribute("name", CKFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($file));
                $oFolderNode[$i]->addAttribute("hasChildren", CKFinder_Connector_Utils_FileSystem::hasChildren($_sServerDir . $file) ? "true" : "false");
                $oFolderNode[$i]->addAttribute("acl", $aclMask);

                $i++;
            }
        }
    }
}
