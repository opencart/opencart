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
 * Include file system utils class
 */
require_once CKFINDER_CONNECTOR_LIB_DIR . "/Utils/FileSystem.php";

/**
 * @package CKFinder
 * @subpackage Core
 * @copyright CKSource - Frederico Knabben
 */
class CKFinder_Connector_Core_FolderHandler
{
    /**
     * CKFinder_Connector_Core_ResourceTypeConfig object
     *
     * @var CKFinder_Connector_Core_ResourceTypeConfig
     * @access private
     */
    private $_resourceTypeConfig;
    /**
     * ResourceType name
     *
     * @var string
     * @access private
     */
    private $_resourceTypeName = "";
    /**
     * Client path
     *
     * @var string
     * @access private
     */
    private $_clientPath = "/";
    /**
     * Url
     *
     * @var string
     * @access private
     */
    private $_url;
    /**
     * Server path
     *
     * @var string
     * @access private
     */
    private $_serverPath;
    /**
     * Thumbnails server path
     *
     * @var string
     * @access private
     */
    private $_thumbsServerPath;
    /**
     * ACL mask
     *
     * @var int
     * @access private
     */
    private $_aclMask;
    /**
     * Folder info
     *
     * @var mixed
     * @access private
     */
    private $_folderInfo;
    /**
     * Thumbnails folder info
     *
     * @var mized
     * @access private
     */
    private $_thumbsFolderInfo;

    function __construct()
    {
        if (isset($_GET["type"])) {
            $this->_resourceTypeName = (string)$_GET["type"];
        }

        if (isset($_GET["currentFolder"])) {
            $this->_clientPath = CKFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding((string)$_GET["currentFolder"]);
        }

        if (!strlen($this->_clientPath)) {
            $this->_clientPath = "/";
        }
        else {
            if (substr($this->_clientPath, -1, 1) != "/") {
                $this->_clientPath .= "/";
            }
            if (substr($this->_clientPath, 0, 1) != "/") {
                $this->_clientPath = "/" . $this->_clientPath;
            }
        }

        $this->_aclMask = -1;
    }

    /**
     * Get resource type config
     *
     * @return CKFinder_Connector_Core_ResourceTypeConfig
     * @access public
     */
    public function &getResourceTypeConfig()
    {
        if (!isset($this->_resourceTypeConfig)) {
            $_config =& CKFinder_Connector_Core_Factory::getInstance("Core_Config");
            $this->_resourceTypeConfig = $_config->getResourceTypeConfig($this->_resourceTypeName);
        }

        if (is_null($this->_resourceTypeConfig)) {
            $connector =& CKFinder_Connector_Core_Factory::getInstance("Core_Connector");
            $oErrorHandler =& $connector->getErrorHandler();
            $oErrorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_TYPE);
        }

        return $this->_resourceTypeConfig;
    }

    /**
     * Get resource type name
     *
     * @return string
     * @access public
     */
    public function getResourceTypeName()
    {
        return $this->_resourceTypeName;
    }

    /**
     * Get Client path
     *
     * @return string
     * @access public
     */
    public function getClientPath()
    {
        return $this->_clientPath;
    }

    /**
     * Get Url
     *
     * @return string
     * @access public
     */
    public function getUrl()
    {
        if (is_null($this->_url)) {
            $this->_resourceTypeConfig = $this->getResourceTypeConfig();
            if (is_null($this->_resourceTypeConfig)) {
                $connector =& CKFinder_Connector_Core_Factory::getInstance("Core_Connector");
                $oErrorHandler =& $connector->getErrorHandler();
                $oErrorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_TYPE);
                $this->_url = "";
            }
            else {
                $this->_url = $this->_resourceTypeConfig->getUrl() . ltrim($this->getClientPath(), "/");
            }
        }

        return $this->_url;
    }

    /**
     * Get server path
     *
     * @return string
     * @access public
     */
    public function getServerPath()
    {
        if (is_null($this->_serverPath)) {
            $this->_resourceTypeConfig = $this->getResourceTypeConfig();
            $this->_serverPath = CKFinder_Connector_Utils_FileSystem::combinePaths($this->_resourceTypeConfig->getDirectory(), ltrim($this->_clientPath, "/"));
        }

        return $this->_serverPath;
    }

    /**
     * Get server path to thumbnails directory
     *
     * @access public
     * @return string
     */
    public function getThumbsServerPath()
    {
        if (is_null($this->_thumbsServerPath)) {
            $this->_resourceTypeConfig = $this->getResourceTypeConfig();

            $_config =& CKFinder_Connector_Core_Factory::getInstance("Core_Config");
            $_thumbnailsConfig = $_config->getThumbnailsConfig();

            // Get the resource type directory.
            $this->_thumbsServerPath = CKFinder_Connector_Utils_FileSystem::combinePaths($_thumbnailsConfig->getDirectory(), $this->_resourceTypeConfig->getName());

            // Return the resource type directory combined with the required path.
            $this->_thumbsServerPath = CKFinder_Connector_Utils_FileSystem::combinePaths($this->_thumbsServerPath, ltrim($this->_clientPath, '/'));

            if (!is_dir($this->_thumbsServerPath)) {
                if(!CKFinder_Connector_Utils_FileSystem::createDirectoryRecursively($this->_thumbsServerPath)) {
                    /**
                     * @todo  Ckfinder_Connector_Utils_Xml::raiseError(); perhaps we should return error
                     *
                     */
                }
            }
        }

        return $this->_thumbsServerPath;
    }

    /**
     * Get ACL Mask
     *
     * @return int
     * @access public
     */
    public function getAclMask()
    {
        $_config =& CKFinder_Connector_Core_Factory::getInstance("Core_Config");
        $_aclConfig = $_config->getAccessControlConfig();

        if ($this->_aclMask == -1) {
            $this->_aclMask = $_aclConfig->getComputedMask($this->_resourceTypeName, $this->_clientPath);
        }

        return $this->_aclMask;
    }

    /**
     * Check ACL
     *
     * @access public
     * @param int $aclToCkeck
     * @return boolean
     */
    public function checkAcl($aclToCkeck)
    {
        $aclToCkeck = intval($aclToCkeck);

        $maska = $this->getAclMask();
        return (($maska & $aclToCkeck) == $aclToCkeck);
    }
}
