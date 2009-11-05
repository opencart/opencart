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
 * Sigleton factory creating objects
 *
 * @package CKFinder
 * @subpackage Core
 * @copyright CKSource - Frederico Knabben
 */
class CKFinder_Connector_Core_Factory
{
    static $instances = array();

    /**
     * Initiate factory
     * @static
     */
    static function initFactory()
    {
    }

    /**
     * Get instance of specified class
     * Short and Long class names are possible
     * <code>
     * $obj1 =& CKFinder_Connector_Core_Factory::getInstance("Ckfinder_Connector_Core_Xml");
     * $obj2 =& CKFinder_Connector_Core_Factory::getInstance("Core_Xml");
     * </code>
     *
     * @param string $className class name
     * @static
     * @access public
     * @return object
     */
    public static function &getInstance($className)
    {
        $namespace = "CKFinder_Connector_";

        $baseName = str_replace($namespace,"",$className);

        $className = $namespace.$baseName;

        if (!isset(CKFinder_Connector_Core_Factory::$instances[$className])) {
            require_once CKFINDER_CONNECTOR_LIB_DIR . "/" . str_replace("_","/",$baseName).".php";
            CKFinder_Connector_Core_Factory::$instances[$className] = new $className;
        }

        return CKFinder_Connector_Core_Factory::$instances[$className];
    }
}
