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
 * Include base command handler
 */
require_once CKFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/CommandHandlerBase.php";
/**
 * Include xml utils
 */
require_once CKFINDER_CONNECTOR_LIB_DIR . "/Core/Xml.php";

/**
 * Base XML commands handler
 *
 * @package CKFinder
 * @subpackage CommandHandlers
 * @copyright CKSource - Frederico Knabben
 * @abstract
 *
 */
abstract class CKFinder_Connector_CommandHandler_XmlCommandHandlerBase extends CKFinder_Connector_CommandHandler_CommandHandlerBase
{
    /**
     * Connector node - Ckfinder_Connector_Utils_XmlNode object
     *
     * @var Ckfinder_Connector_Utils_XmlNode
     * @access protected
     */
    protected $_connectorNode;

    /**
     * send response
     * @access public
     *
     */
    public function sendResponse()
    {
        $xml =& CKFinder_Connector_Core_Factory::getInstance("Core_Xml");
        $this->_connectorNode =& $xml->getConnectorNode();

        $this->checkConnector();
        if ($this->mustCheckRequest()) {
            $this->checkRequest();
        }

        $resourceTypeName = $this->_currentFolder->getResourceTypeName();
        if (!empty($resourceTypeName)) {
            $this->_connectorNode->addAttribute("resourceType", $this->_currentFolder->getResourceTypeName());
        }

        if ($this->mustAddCurrentFolderNode()) {
            $_currentFolder = new Ckfinder_Connector_Utils_XmlNode("CurrentFolder");
            $this->_connectorNode->addChild($_currentFolder);
            $_currentFolder->addAttribute("path", CKFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($this->_currentFolder->getClientPath()));

            $this->_errorHandler->setCatchAllErros(true);
            $_url = $this->_currentFolder->getUrl();
            $_currentFolder->addAttribute("url", is_null($_url) ? "" : CKFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($_url));
            $this->_errorHandler->setCatchAllErros(false);

            $_currentFolder->addAttribute("acl", $this->_currentFolder->getAclMask());
        }

        $this->buildXml();

        $_oErrorNode =& $xml->getErrorNode();
        $_oErrorNode->addAttribute("number", "0");

        echo $this->_connectorNode->asXML();
        exit;
    }

    /**
     * Must check request?
     *
     * @return boolean
     * @access protected
     */
    protected function mustCheckRequest()
    {
        return true;
    }

    /**
     * Must add CurrentFolder node?
     *
     * @return boolean
     * @access protected
     */
    protected function mustAddCurrentFolderNode()
    {
        return true;
    }

    /**
     * @access protected
     * @abstract
     * @return void
     */
    abstract protected function buildXml();
}
