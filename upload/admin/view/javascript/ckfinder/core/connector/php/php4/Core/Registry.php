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
 * Registry for storing global variables values (not references)
 *
 * @package CKFinder
 * @subpackage Core
 * @copyright CKSource - Frederico Knabben
 */
class CKFinder_Connector_Core_Registry
{
    /**
     * Arrat that stores all values
     *
     * @var array
     * @access private
     */
    var $_store = array();

    /**
     * Chacke if value has been set
     *
     * @param string $key
     * @return boolean
     * @access private
     */
    function isValid($key)
    {
        return array_key_exists($key, $this->_store);
    }

    /**
     * Set value
     *
     * @param string $key
     * @param mixed $obj
     * @access public
     */
    function set($key, $obj)
    {
        $this->_store[$key] = $obj;
    }

    /**
     * Get value
     *
     * @param string $key
     * @return mixed
     * @access public
     */
    function get($key)
    {
    	if ($this->isValid($key)) {
    	    return $this->_store[$key];
    	}
    }
}
