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
 * MarketplaceWebServiceOrders_Model_ListOrderItemsByNextTokenResult
 * 
 * Properties:
 * <ul>
 * 
 * <li>NextToken: string</li>
 * <li>AmazonOrderId: string</li>
 * <li>OrderItems: array</li>
 *
 * </ul>
 */

 class MarketplaceWebServiceOrders_Model_ListOrderItemsByNextTokenResult extends MarketplaceWebServiceOrders_Model {

    public function __construct($data = null)
    {
    $this->_fields = array (
    'NextToken' => array('FieldValue' => null, 'FieldType' => 'string'),
    'AmazonOrderId' => array('FieldValue' => null, 'FieldType' => 'string'),
    'OrderItems' => array('FieldValue' => array(), 'FieldType' => array('MarketplaceWebServiceOrders_Model_OrderItem'), 'ListMemberName' => 'OrderItem'),
    );
    parent::__construct($data);
    }

    /**
     * Get the value of the NextToken property.
     *
     * @return String NextToken.
     */
    public function getNextToken()
    {
        return $this->_fields['NextToken']['FieldValue'];
    }

    /**
     * Set the value of the NextToken property.
     *
     * @param string nextToken
     * @return this instance
     */
    public function setNextToken($value)
    {
        $this->_fields['NextToken']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Check to see if NextToken is set.
     *
     * @return true if NextToken is set.
     */
    public function isSetNextToken()
    {
                return !is_null($this->_fields['NextToken']['FieldValue']);
            }

    /**
     * Set the value of NextToken, return this.
     *
     * @param nextToken
     *             The new value to set.
     *
     * @return This instance.
     */
    public function withNextToken($value)
    {
        $this->setNextToken($value);
        return $this;
    }

    /**
     * Get the value of the AmazonOrderId property.
     *
     * @return String AmazonOrderId.
     */
    public function getAmazonOrderId()
    {
        return $this->_fields['AmazonOrderId']['FieldValue'];
    }

    /**
     * Set the value of the AmazonOrderId property.
     *
     * @param string amazonOrderId
     * @return this instance
     */
    public function setAmazonOrderId($value)
    {
        $this->_fields['AmazonOrderId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Check to see if AmazonOrderId is set.
     *
     * @return true if AmazonOrderId is set.
     */
    public function isSetAmazonOrderId()
    {
                return !is_null($this->_fields['AmazonOrderId']['FieldValue']);
            }

    /**
     * Set the value of AmazonOrderId, return this.
     *
     * @param amazonOrderId
     *             The new value to set.
     *
     * @return This instance.
     */
    public function withAmazonOrderId($value)
    {
        $this->setAmazonOrderId($value);
        return $this;
    }

    /**
     * Get the value of the OrderItems property.
     *
     * @return List<OrderItem> OrderItems.
     */
    public function getOrderItems()
    {
        if ($this->_fields['OrderItems']['FieldValue'] == null)
        {
            $this->_fields['OrderItems']['FieldValue'] = array();
        }
        return $this->_fields['OrderItems']['FieldValue'];
    }

    /**
     * Set the value of the OrderItems property.
     *
     * @param array orderItems
     * @return this instance
     */
    public function setOrderItems($value)
    {
        if (!$this->_isNumericArray($value)) {
            $value = array ($value);
        }
        $this->_fields['OrderItems']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Clear OrderItems.
     */
    public function unsetOrderItems()
    {
        $this->_fields['OrderItems']['FieldValue'] = array();
    }

    /**
     * Check to see if OrderItems is set.
     *
     * @return true if OrderItems is set.
     */
    public function isSetOrderItems()
    {
                return !empty($this->_fields['OrderItems']['FieldValue']);
            }

    /**
     * Add values for OrderItems, return this.
     *
     * @param orderItems
     *             New values to add.
     *
     * @return This instance.
     */
    public function withOrderItems()
    {
        foreach (func_get_args() as $OrderItems)
        {
            $this->_fields['OrderItems']['FieldValue'][] = $OrderItems;
        }
        return $this;
    }

}
