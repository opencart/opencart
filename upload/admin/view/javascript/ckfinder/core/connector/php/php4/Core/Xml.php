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
 * @subpackage Core
 * @copyright CKSource - Frederico Knabben
 */

/**
 * Include basic Xml library
 */
require_once CKFINDER_CONNECTOR_LIB_DIR . "/Utils/XmlNode.php";

/**
 * XML document
 *
 * @package CKFinder
 * @subpackage Core
 * @copyright CKSource - Frederico Knabben
 */
class CKFinder_Connector_Core_Xml
{
    /**
     * Connector node (root)
     *
     * @var Ckfinder_Connector_Utils_XmlNode
     * @access private
     */
    var $_connectorNode;
    /**
     * Error node
     *
     * @var Ckfinder_Connector_Utils_XmlNode
     * @access private
     */
    var $_errorNode;

    function CKFinder_Connector_Core_Xml()
    {
        $this->sendXmlHeaders();
        echo $this->getXMLDeclaration();
        $this->_connectorNode = new Ckfinder_Connector_Utils_XmlNode("Connector");
        $this->_errorNode = new Ckfinder_Connector_Utils_XmlNode("Error");
        $this->_connectorNode->addChild($this->_errorNode);
    }

    /**
     * Return connector node
     *
     * @access public
     * @return Ckfinder_Connector_Utils_XmlNode
     */
    function &getConnectorNode()
    {
    	return $this->_connectorNode;
    }

    /**
     * Return error node
     *
     * @access public
     * @return Ckfinder_Connector_Utils_XmlNode
     */
    function &getErrorNode()
    {
    	return $this->_errorNode;
    }

    /**
     * Send XML headers to the browser (and force browser not to use cache)
     * @access private
     */
    function sendXmlHeaders()
    {
        // Prevent the browser from caching the result.
        // Date in the past
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT') ;
        // always modified
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT') ;
        // HTTP/1.1
        header('Cache-Control: no-store, no-cache, must-revalidate') ;
        header('Cache-Control: post-check=0, pre-check=0', false) ;
        // HTTP/1.0
        header('Pragma: no-cache') ;

        // Set the response format.
        header( 'Content-Type:text/xml; charset=utf-8' ) ;
    }

    /**
     * Return XML declaration
     *
     * @access private
     * @return string
     */
    function getXMLDeclaration()
    {
    	return '<?xml version="1.0" encoding="utf-8"?>';
    }

    /**
     * Send error message to the browser. If error number is set to 1, $text (custom error message) will be displayed
     * Don't call this function directly
     *
     * @access public
     * @param int $number error number
     * @param string $text Custom error message (optional)
     */
    function raiseError( $number, $text = false)
    {
        $this->_errorNode->addAttribute("number", intval($number));
        if (false!=$text) {
            $this->_errorNode->addAttribute("text", $text);
        }

        echo $this->_connectorNode->asXML();
    }
}
