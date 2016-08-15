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
 * MarketplaceWebServiceOrders_Model_GetOrderRequest
 * 
 * Properties:
 * <ul>
 * 
 * <li>SellerId: string</li>
 * <li>MWSAuthToken: string</li>
 * <li>AmazonOrderId: array</li>
 *
 * </ul>
 */

 class MarketplaceWebServiceOrders_Model_GetOrderRequest extends MarketplaceWebServiceOrders_Model {

    public function __construct($data = null)
    {
    $this->_fields = array (
    'SellerId' => array('FieldValue' => null, 'FieldType' => 'string'),
    'MWSAuthToken' => array('FieldValue' => null, 'FieldType' => 'string'),
    'AmazonOrderId' => array('FieldValue' => array(), 'FieldType' => array('string'), 'ListMemberName' => 'Id'),
    );
    parent::__construct($data);
    }

    /**
     * Get the value of the SellerId property.
     *
     * @return String SellerId.
     */
    public function getSellerId()
    {
        return $this->_fields['SellerId']['FieldValue'];
    }

    /**
     * Set the value of the SellerId property.
     *
     * @param string sellerId
     * @return this instance
     */
    public function setSellerId($value)
    {
        $this->_fields['SellerId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Check to see if SellerId is set.
     *
     * @return true if SellerId is set.
     */
    public function isSetSellerId()
    {
                return !is_null($this->_fields['SellerId']['FieldValue']);
            }

    /**
     * Set the value of SellerId, return this.
     *
     * @param sellerId
     *             The new value to set.
     *
     * @return This instance.
     */
    public function withSellerId($value)
    {
        $this->setSellerId($value);
        return $this;
    }

    /**
     * Get the value of the MWSAuthToken property.
     *
     * @return String MWSAuthToken.
     */
    public function getMWSAuthToken()
    {
        return $this->_fields['MWSAuthToken']['FieldValue'];
    }

    /**
     * Set the value of the MWSAuthToken property.
     *
     * @param string mwsAuthToken
     * @return this instance
     */
    public function setMWSAuthToken($value)
    {
        $this->_fields['MWSAuthToken']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Check to see if MWSAuthToken is set.
     *
     * @return true if MWSAuthToken is set.
     */
    public function isSetMWSAuthToken()
    {
                return !is_null($this->_fields['MWSAuthToken']['FieldValue']);
            }

    /**
     * Set the value of MWSAuthToken, return this.
     *
     * @param mwsAuthToken
     *             The new value to set.
     *
     * @return This instance.
     */
    public function withMWSAuthToken($value)
    {
        $this->setMWSAuthToken($value);
        return $this;
    }

    /**
     * Get the value of the AmazonOrderId property.
     *
     * @return List<String> AmazonOrderId.
     */
    public function getAmazonOrderId()
    {
        if ($this->_fields['AmazonOrderId']['FieldValue'] == null)
        {
            $this->_fields['AmazonOrderId']['FieldValue'] = array();
        }
        return $this->_fields['AmazonOrderId']['FieldValue'];
    }

    /**
     * Set the value of the AmazonOrderId property.
     *
     * @param array amazonOrderId
     * @return this instance
     */
    public function setAmazonOrderId($value)
    {
        if (!$this->_isNumericArray($value)) {
            $value = array ($value);
        }
        $this->_fields['AmazonOrderId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Clear AmazonOrderId.
     */
    public function unsetAmazonOrderId()
    {
        $this->_fields['AmazonOrderId']['FieldValue'] = array();
    }

    /**
     * Check to see if AmazonOrderId is set.
     *
     * @return true if AmazonOrderId is set.
     */
    public function isSetAmazonOrderId()
    {
                return !empty($this->_fields['AmazonOrderId']['FieldValue']);
            }

    /**
     * Add values for AmazonOrderId, return this.
     *
     * @param amazonOrderId
     *             New values to add.
     *
     * @return This instance.
     */
    public function withAmazonOrderId()
    {
        foreach (func_get_args() as $AmazonOrderId)
        {
            $this->_fields['AmazonOrderId']['FieldValue'][] = $AmazonOrderId;
        }
        return $this;
    }

}
