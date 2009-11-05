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
 * @subpackage Config
 * @copyright CKSource - Frederico Knabben
 */

/**
 * This class keeps resource types configuration
 *
 * @package CKFinder
 * @subpackage Config
 * @copyright CKSource - Frederico Knabben
 */
class CKFinder_Connector_Core_ResourceTypeConfig
{
    /**
     * Resource name
     *
     * @var string
     * @access private
     */
    var $_name = "";
    /**
     * Resource url
     *
     * @var string
     * @access private
     */
    var $_url = "";
    /**
     * Directory path on a server
     *
     * @var string
     * @access private
     */
    var $_directory = "";
    /**
     * Max size
     *
     * @var unknown_type
     * @access private
     */
    var $_maxSize = 0;
    /**
     * Array with allowed extensions
     *
     * @var array[]string
     * @access private
     */
    var $_allowedExtensions = array();
    /**
     * Array with denied extensions
     *
     * @var array[]string
     * @access private
     */
    var $_deniedExtensions = array();
    /**
     * Default view
     *
     * @var string
     * @access private
     */
    var $_defaultView = "Thumbnails";
    /**
     * used for CKFinder_Connector_Core_Config object caching
     *
     * @var CKFinder_Connector_Core_Config
     * @access private
     */
    var $_config;

    /**
     * Get ResourceType configuration
     *
     * @param string $resourceTypeNode
     * @return array
     *
     */
    function CKFinder_Connector_Core_ResourceTypeConfig($resourceTypeNode)
    {
        if (isset($resourceTypeNode["name"])) {
            $this->_name = $resourceTypeNode["name"];
        }

        if (isset($resourceTypeNode["url"])) {
            $this->_url = $resourceTypeNode["url"];
        }

        if (!strlen($this->_url)) {
            $this->_url = "/";
        }
        else if(substr($this->_url,-1,1) != "/") {
            $this->_url .= "/";
        }

        if (isset($resourceTypeNode["maxSize"])) {
            $this->_maxSize = CKFinder_Connector_Utils_Misc::returnBytes((string)$resourceTypeNode["maxSize"]);
        }

        if (isset($resourceTypeNode["directory"])) {
            $this->_directory = $resourceTypeNode["directory"];
        }

        if (!strlen($this->_directory)) {
            $this->_directory = resolveUrl($this->_url);
        }

        if (isset($resourceTypeNode["allowedExtensions"])) {
            if (is_array($resourceTypeNode["allowedExtensions"])) {
                foreach ($resourceTypeNode["allowedExtensions"] as $extension) {
                    $this->_allowedExtensions[] = strtolower(trim((string)$e));
                }
            }
            else {
                $resourceTypeNode["allowedExtensions"] = trim((string)$resourceTypeNode["allowedExtensions"]);
                if (strlen($resourceTypeNode["allowedExtensions"])) {
                    $extensions = explode(",", $resourceTypeNode["allowedExtensions"]);
                    foreach ($extensions as $e) {
                        $this->_allowedExtensions[] = strtolower(trim($e));
                    }
                }
            }
        }

        if (isset($resourceTypeNode["deniedExtensions"])) {
            if (is_array($resourceTypeNode["deniedExtensions"])) {

                foreach ($resourceTypeNode["deniedExtensions"] as $extension) {
                    $this->_deniedExtensions[] = strtolower(trim((string)$e));
                }
            }
            else {
                $resourceTypeNode["deniedExtensions"] = trim((string)$resourceTypeNode["deniedExtensions"]);
                if (strlen($resourceTypeNode["deniedExtensions"])) {
                    $extensions = explode(",", $resourceTypeNode["deniedExtensions"]);
                    foreach ($extensions as $e) {
                        $this->_deniedExtensions[] = strtolower(trim($e));
                    }
                }
            }
        }

        $_view = "";
        if (isset($resourceTypeNode["defaultView"])) {
            $_view = $resourceTypeNode["defaultView"];
        }
        if (!strlen($_view) && isset($GLOBALS['config']['DefaultDisplaySettings']['view'])) {
            $_view = $GLOBALS['config']['DefaultDisplaySettings']['view'];
        }
        if ($_view == "List") {
            $this->_defaultView = "List";
        }
    }

    /**
     * Get name
     *
     * @access public
     * @return string
     */
    function getName()
    {
        return $this->_name;
    }

    /**
     * Get url
     *
     * @access public
     * @return string
     */
    function getUrl()
    {
        return $this->_url;
    }

    /**
     * Get directory
     *
     * @access public
     * @return string
     */
    function getDirectory()
    {
        return $this->_directory;
    }

    /**
     * Get max size
     *
     * @access public
     * @return int
     */
    function getMaxSize()
    {
        return $this->_maxSize;
    }

    /**
     * Get allowed extensions
     *
     * @access public
     * @return array[]string
     */
    function getAllowedExtensions()
    {
        return $this->_allowedExtensions;
    }

    /**
     * Get denied extensions
     *
     * @access public
     * @return array[]string
     */
    function getDeniedExtensions()
    {
        return $this->_deniedExtensions;
    }

    /**
     * Get default view
     *
     * @access public
     * @return string
     */
    function getDefaultView()
    {
        return $this->_defaultView;
    }

    /**
     * Check extension, return true if file name is valid.
     * Return false if extension is on denied list.
     * If allowed extensions are defined, return false if extension isn't on allowed list.
     *
     * @access public
     * @param string $extension extension
     * @param boolean $renameIfRequired whether try to rename file or not
     * @return boolean
     */
    function checkExtension(&$fileName, $renameIfRequired = true)
    {
        if (strpos($fileName, '.') === false) {
            return true;
        }

        if (is_null($this->_config)) {
            $this->_config =& CKFinder_Connector_Core_Factory::getInstance("Core_Config");
        }

        $toCheck = array();

        if ($this->_config->getCheckDoubleExtension()) {
            $pieces = explode('.', $fileName);

            // First, check the last extension (ex. in file.php.jpg, the "jpg").
            if ( !$this->checkSingleExtension( $pieces[sizeof($pieces)-1] ) ) {
                return false;
            }

            if ($renameIfRequired) {
                // Check the other extensions, rebuilding the file name. If an extension is
                // not allowed, replace the dot with an underscore.
                $fileName = $pieces[0] ;
                for ($i=1; $i<sizeof($pieces)-1; $i++) {
                    $fileName .= $this->checkSingleExtension( $pieces[$i] ) ? '.' : '_' ;
                    $fileName .= $pieces[$i];
                }

                // Add the last extension to the final name.
                $fileName .= '.' . $pieces[sizeof($pieces)-1] ;
            }
        }
        else {
            // Check only the last extension (ex. in file.php.jpg, only "jpg").
            return $this->checkSingleExtension( substr($fileName, strrpos($fileName,'.')+1) );
        }

        return true;
    }

    /**
     * Check given folder name
     * Return true if folder name matches hidden folder names list
     *
     * @param string $folderName
     * @access public
     * @return boolean
     */
    function checkIsHiddenFolder($folderName)
    {
        if (is_null($this->_config)) {
            $this->_config =& CKFinder_Connector_Core_Factory::getInstance("Core_Config");
        }

        $regex = $this->_config->getHideFoldersRegex();
        if ($regex) {
            return preg_match($regex, $folderName);
        }

        return false;
    }

    /**
     * Check given file name
     * Return true if file name matches hidden file names list
     *
     * @param string $fileName
     * @access public
     * @return boolean
     */
    function checkIsHiddenFile($fileName)
    {
        if (is_null($this->_config)) {
            $this->_config =& CKFinder_Connector_Core_Factory::getInstance("Core_Config");
        }

        $regex = $this->_config->getHideFilesRegex();
        if ($regex) {
            return preg_match($regex, $fileName);
        }

        return false;
    }

    function checkSingleExtension($extension)
    {
        $extension = strtolower(ltrim($extension,'.'));

        if (sizeof($this->_deniedExtensions)) {
            if (in_array($extension, $this->_deniedExtensions)) {
                return false;
            }
        }

        if (sizeof($this->_allowedExtensions)) {
            return in_array($extension, $this->_allowedExtensions);
        }

        return true;
    }
}
