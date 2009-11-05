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
 * Folder view mask
 */
define('CKFINDER_CONNECTOR_ACL_FOLDER_VIEW',1);
define('CKFINDER_CONNECTOR_ACL_FOLDER_CREATE',2);
define('CKFINDER_CONNECTOR_ACL_FOLDER_RENAME',4);
define('CKFINDER_CONNECTOR_ACL_FOLDER_DELETE',8);
define('CKFINDER_CONNECTOR_ACL_FILE_VIEW',16);
define('CKFINDER_CONNECTOR_ACL_FILE_UPLOAD',32);
define('CKFINDER_CONNECTOR_ACL_FILE_RENAME',64);
define('CKFINDER_CONNECTOR_ACL_FILE_DELETE',128);

/**
 * This class keeps ACL configuration
 *
 * @package CKFinder
 * @subpackage Config
 * @copyright CKSource - Frederico Knabben
 */
class CKFinder_Connector_Core_AccessControlConfig
{

    /**
     * array with ACL entries
     *
     * @var array[string]string
     * @access private
     */
    var $_aclEntries = array();

    function CKFinder_Connector_Core_AccessControlConfig($accessControlNodes)
    {
        foreach ($accessControlNodes as $node) {
            $_folderView = isset($node['folderView']) ? CKFinder_Connector_Utils_Misc::booleanValue($node['folderView']) : false;
            $_folderCreate = isset($node['folderCreate']) ? CKFinder_Connector_Utils_Misc::booleanValue($node['folderCreate']) : false;
            $_folderRename = isset($node['folderRename']) ? CKFinder_Connector_Utils_Misc::booleanValue($node['folderRename']) : false;
            $_folderDelete = isset($node['folderDelete']) ? CKFinder_Connector_Utils_Misc::booleanValue($node['folderDelete']) : false;
            $_fileView = isset($node['fileView']) ? CKFinder_Connector_Utils_Misc::booleanValue($node['fileView']) : false;
            $_fileUpload = isset($node['fileUpload']) ? CKFinder_Connector_Utils_Misc::booleanValue($node['fileUpload']) : false;
            $_fileRename = isset($node['fileRename']) ? CKFinder_Connector_Utils_Misc::booleanValue($node['fileRename']) : false;
            $_fileDelete = isset($node['fileDelete']) ? CKFinder_Connector_Utils_Misc::booleanValue($node['fileDelete']) : false;

            $_role = isset($node['role']) ? $node['role'] : "*";
            $_resourceType = isset($node['resourceType']) ? $node['resourceType'] : "*";
            $_folder = isset($node['folder']) ? $node['folder'] : "/";

            $this->addACLEntry($_role, $_resourceType, $_folder,
            array(
            $_folderView ? CKFINDER_CONNECTOR_ACL_FOLDER_VIEW : 0,
            $_folderCreate ? CKFINDER_CONNECTOR_ACL_FOLDER_CREATE : 0,
            $_folderRename ? CKFINDER_CONNECTOR_ACL_FOLDER_RENAME : 0,
            $_folderDelete ? CKFINDER_CONNECTOR_ACL_FOLDER_DELETE : 0,
            $_fileView ? CKFINDER_CONNECTOR_ACL_FILE_VIEW : 0,
            $_fileUpload ? CKFINDER_CONNECTOR_ACL_FILE_UPLOAD : 0,
            $_fileRename ? CKFINDER_CONNECTOR_ACL_FILE_RENAME : 0,
            $_fileDelete ? CKFINDER_CONNECTOR_ACL_FILE_DELETE : 0,
            ),
            array(
            $_folderView ? 0 : CKFINDER_CONNECTOR_ACL_FOLDER_VIEW,
            $_folderCreate ? 0 : CKFINDER_CONNECTOR_ACL_FOLDER_CREATE,
            $_folderRename ? 0 : CKFINDER_CONNECTOR_ACL_FOLDER_RENAME,
            $_folderDelete ? 0 : CKFINDER_CONNECTOR_ACL_FOLDER_DELETE,
            $_fileView ? 0 : CKFINDER_CONNECTOR_ACL_FILE_VIEW,
            $_fileUpload ? 0 : CKFINDER_CONNECTOR_ACL_FILE_UPLOAD,
            $_fileRename ? 0 : CKFINDER_CONNECTOR_ACL_FILE_RENAME,
            $_fileDelete ? 0 : CKFINDER_CONNECTOR_ACL_FILE_DELETE,
            )
            );
        }
    }

    /**
     * Add ACL entry
     *
     * @param string $role role
     * @param string $resourceType resource type
     * @param string $folderPath folder path
     * @param int $allowRulesMask allow rules mask
     * @param int $denyRulesMask deny rules mask
     * @access private
     */
    function addACLEntry($role, $resourceType, $folderPath, $allowRulesMask, $denyRulesMask)
    {

        if (!strlen($folderPath)) {
            $folderPath = '/';
        }
        else {
            if (substr($folderPath,0,1) != '/') {
                $folderPath = '/' . $folderPath;
            }

            if (substr($folderPath,-1,1) != '/') {
                $folderPath .= '/';
            }
        }

        $_entryKey = $role . "#@#" . $resourceType;

        if (array_key_exists($folderPath,$this->_aclEntries)) {
            if (array_key_exists($_entryKey, $this->_aclEntries[$folderPath])) {
                $_rulesMasks = $this->_aclEntries[$folderPath][$_entryKey];
                foreach ($_rulesMasks[0] as $key => $value) {
                    $allowRulesMask[$key] |= $value;
                }
                foreach ($_rulesMasks[1] as $key => $value) {
                    $denyRulesMask[$key] |= $value;
                }
            }
        }
        else {
            $this->_aclEntries[$folderPath] = array();
        }

        $this->_aclEntries[$folderPath][$_entryKey] = array($allowRulesMask, $denyRulesMask);
    }


    /**
     * Get computed mask
     *
     * @param string $resourceType
     * @param string $folderPath
     * @return int
     */
    function getComputedMask($resourceType, $folderPath)
    {
        $_computedMask = 0;

        $_config =& CKFinder_Connector_Core_Factory::getInstance("Core_Config");
        $_roleSessionVar = $_config->getRoleSessionVar();

        $_userRole = null;
        if (strlen($_roleSessionVar) && isset($_SESSION[$_roleSessionVar])) {
            $_userRole = (string)$_SESSION[$_roleSessionVar];
        }
        if (!is_null($_userRole) && !strlen($_userRole)) {
            $_userRole = null;
        }

        $folderPath = trim($folderPath, "/");
        $_pathParts = explode("/", $folderPath);

        $_currentPath = "/";

        for($i = -1; $i < sizeof($_pathParts); $i++) {
            if ($i >= 0) {
                if (!strlen($_pathParts[$i])) {
                    continue;
                }

                if (array_key_exists($_currentPath . '*/', $this->_aclEntries))
                $_computedMask = $this->mergePathComputedMask( $_computedMask, $resourceType, $_userRole, $_currentPath . '*/' );

                $_currentPath .= $_pathParts[$i] . '/';
            }

            if (array_key_exists($_currentPath, $this->_aclEntries)) {
                $_computedMask = $this->mergePathComputedMask( $_computedMask, $resourceType, $_userRole, $_currentPath );
            }
        }

        return $_computedMask;
    }

    /**
     * merge current mask with folder entries
     *
     * @access private
     * @param int $currentMask
     * @param string $resourceType
     * @param string $userRole
     * @param string $path
     * @return int
     */
    function mergePathComputedMask( $currentMask, $resourceType, $userRole, $path )
    {
        $_folderEntries = $this->_aclEntries[$path];

        $_possibleEntries = array();

        $_possibleEntries[0] = "*#@#*";
        $_possibleEntries[1] = "*#@#" . $resourceType;

        if (!is_null($userRole))
        {
            $_possibleEntries[2] = $userRole . "#@#*";
            $_possibleEntries[3] = $userRole . "#@#" . $resourceType;
        }

        for ($r = 0; $r < sizeof($_possibleEntries); $r++)
        {
            $_possibleKey = $_possibleEntries[$r];
            if (array_key_exists($_possibleKey, $_folderEntries))
            {
                $_rulesMasks = $_folderEntries[$_possibleKey];

                $currentMask |= array_sum($_rulesMasks[0]);
                $currentMask ^= ($currentMask & array_sum($_rulesMasks[1]));
            }
        }

        return $currentMask;
    }
}
