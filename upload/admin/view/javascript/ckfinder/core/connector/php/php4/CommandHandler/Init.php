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
 * Handle Init command
 *
 * @package CKFinder
 * @subpackage CommandHandlers
 * @copyright CKSource - Frederico Knabben
 */
class CKFinder_Connector_CommandHandler_Init extends CKFinder_Connector_CommandHandler_XmlCommandHandlerBase
{
    /**
     * Command name
     *
     * @access private
     * @var string
     */
    var $command = "Init";

    function mustCheckRequest()
    {
        return false;
    }

    /**
     * Must add CurrentFolder node?
     *
     * @return boolean
     * @access protected
     */
    function mustAddCurrentFolderNode()
    {
        return false;
    }

    /**
     * handle request and build XML
     * @access protected
     *
     */
    function buildXml()
    {
        $_config =& CKFinder_Connector_Core_Factory::getInstance("Core_Config");

        // Create the "ConnectorInfo" node.
        $_oConnInfo = new Ckfinder_Connector_Utils_XmlNode("ConnectorInfo");
        $this->_connectorNode->addChild($_oConnInfo);
        $_oConnInfo->addAttribute("enabled", $_config->getIsEnabled() ? "true" : "false");

        if (!$_config->getIsEnabled()) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_CONNECTOR_DISABLED);
        }

        $_ln = '' ;
        $_lc = $_config->getLicenseKey() . '                                  ' ;
        if ( 1 == ( strpos( CKFINDER_CHARS, $_lc[0] ) % 5 ) )
        $_ln = $_config->getLicenseName() ;

        $_oConnInfo->addAttribute("s", $_ln);
        $_oConnInfo->addAttribute("c", trim( $_lc[11] . $_lc[0] . $_lc [8] . $_lc[12] ));
        $_thumbnailsConfig = $_config->getThumbnailsConfig();
        $_thumbnailsEnabled = $_thumbnailsConfig->getIsEnabled() ;
        $_oConnInfo->addAttribute("thumbsEnabled", $_thumbnailsEnabled ? "true" : "false");
        if ($_thumbnailsEnabled) {
            $_oConnInfo->addAttribute("thumbsUrl", $_thumbnailsConfig->getUrl());
            $_oConnInfo->addAttribute("thumbsDirectAccess", $_thumbnailsConfig->getDirectAccess() ? "true" : "false" );
        }

        // Create the "ResourceTypes" node.
        $_oResourceTypes = new Ckfinder_Connector_Utils_XmlNode("ResourceTypes");
        $this->_connectorNode->addChild($_oResourceTypes);

        // Load the resource types in an array.
        $_aTypes = $_config->getDefaultResourceTypes();

        if (!sizeof($_aTypes)) {
            $_aTypes = $_config->getResourceTypeNames();
        }

        $_aTypesSize = sizeof($_aTypes);
        if ($_aTypesSize) {
            for ($i = 0; $i < $_aTypesSize; $i++)
            {
                $_resourceTypeName = $_aTypes[$i];

                $_acl = $_config->getAccessControlConfig();
                $_aclMask = $_acl->getComputedMask($_resourceTypeName, "/");

                if ( ($_aclMask & CKFINDER_CONNECTOR_ACL_FOLDER_VIEW) != CKFINDER_CONNECTOR_ACL_FOLDER_VIEW ) {
                    continue;
                }

                if (!isset($_GET['type']) || $_GET['type'] === $_resourceTypeName) {
                    //print $_resourceTypeName;
                    $_oTypeInfo = $_config->getResourceTypeConfig($_resourceTypeName);
                    //print_r($_oTypeInfo);
                    $_oResourceType[$i] = new Ckfinder_Connector_Utils_XmlNode("ResourceType");
                    $_oResourceTypes->addChild($_oResourceType[$i]);

                    $_oResourceType[$i]->addAttribute("name", $_resourceTypeName);
                    $_oResourceType[$i]->addAttribute("url", $_oTypeInfo->getUrl());
                    $_oResourceType[$i]->addAttribute("allowedExtensions", implode(",", $_oTypeInfo->getAllowedExtensions()));
                    $_oResourceType[$i]->addAttribute("deniedExtensions", implode(",", $_oTypeInfo->getDeniedExtensions()));
                    $_oResourceType[$i]->addAttribute("defaultView", $_oTypeInfo->getDefaultView());
                    $_oResourceType[$i]->addAttribute("hash", substr(md5($_oTypeInfo->getDirectory()), 0, 16));
                    $_oResourceType[$i]->addAttribute("hasChildren", CKFinder_Connector_Utils_FileSystem::hasChildren($_oTypeInfo->getDirectory()) ? "true" : "false");
                    $_oResourceType[$i]->addAttribute("acl", $_aclMask);
                }
            }
        }
    }
}
