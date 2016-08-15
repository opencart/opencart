<?php
/*******************************************************************************
 * Copyright 2009-2015 Amazon Services. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License"); 
 *
 * You may not use this file except in compliance with the License. 
 * You may obtain a copy of the License at: http://aws.amazon.com/apache2.0
 * This file is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR 
 * CONDITIONS OF ANY KIND, either express or implied. See the License for the 
 * specific language governing permissions and limitations under the License.
 *******************************************************************************
 * PHP Version 5
 * @category Amazon
 * @package  Marketplace Web Service Orders
 * @version  2013-09-01
 * Library Version: 2015-09-24
 * Generated: Fri Sep 25 20:06:28 GMT 2015
 */

/**
 *  @see MarketplaceWebServiceOrders_Model
 */

require_once (dirname(__FILE__) . '/../Model.php');


/**
 * MarketplaceWebServiceOrders_Model_GetOrderResult
 * 
 * Properties:
 * <ul>
 * 
 * <li>Orders: array</li>
 *
 * </ul>
 */

 class MarketplaceWebServiceOrders_Model_GetOrderResult extends MarketplaceWebServiceOrders_Model {

    public function __construct($data = null)
    {
    $this->_fields = array (
    'Orders' => array('FieldValue' => array(), 'FieldType' => array('MarketplaceWebServiceOrders_Model_Order'), 'ListMemberName' => 'Order'),
    );
    parent::__construct($data);
    }

    /**
     * Get the value of the Orders property.
     *
     * @return List<Order> Orders.
     */
    public function getOrders()
    {
        if ($this->_fields['Orders']['FieldValue'] == null)
        {
            $this->_fields['Orders']['FieldValue'] = array();
        }
        return $this->_fields['Orders']['FieldValue'];
    }

    /**
     * Set the value of the Orders property.
     *
     * @param array orders
     * @return this instance
     */
    public function setOrders($value)
    {
        if (!$this->_isNumericArray($value)) {
            $value = array ($value);
        }
        $this->_fields['Orders']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Clear Orders.
     */
    public function unsetOrders()
    {
        $this->_fields['Orders']['FieldValue'] = array();
    }

    /**
     * Check to see if Orders is set.
     *
     * @return true if Orders is set.
     */
    public function isSetOrders()
    {
                return !empty($this->_fields['Orders']['FieldValue']);
            }

    /**
     * Add values for Orders, return this.
     *
     * @param orders
     *             New values to add.
     *
     * @return This instance.
     */
    public function withOrders()
    {
        foreach (func_get_args() as $Orders)
        {
            $this->_fields['Orders']['FieldValue'][] = $Orders;
        }
        return $this;
    }

}
