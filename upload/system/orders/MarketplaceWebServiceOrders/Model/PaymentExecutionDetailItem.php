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
 * MarketplaceWebServiceOrders_Model_PaymentExecutionDetailItem
 * 
 * Properties:
 * <ul>
 * 
 * <li>Payment: MarketplaceWebServiceOrders_Model_Money</li>
 * <li>PaymentMethod: string</li>
 *
 * </ul>
 */

 class MarketplaceWebServiceOrders_Model_PaymentExecutionDetailItem extends MarketplaceWebServiceOrders_Model {

    public function __construct($data = null)
    {
    $this->_fields = array (
    'Payment' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_Money'),
    'PaymentMethod' => array('FieldValue' => null, 'FieldType' => 'string'),
    );
    parent::__construct($data);
    }

    /**
     * Get the value of the Payment property.
     *
     * @return Money Payment.
     */
    public function getPayment()
    {
        return $this->_fields['Payment']['FieldValue'];
    }

    /**
     * Set the value of the Payment property.
     *
     * @param MarketplaceWebServiceOrders_Model_Money payment
     * @return this instance
     */
    public function setPayment($value)
    {
        $this->_fields['Payment']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Check to see if Payment is set.
     *
     * @return true if Payment is set.
     */
    public function isSetPayment()
    {
                return !is_null($this->_fields['Payment']['FieldValue']);
            }

    /**
     * Set the value of Payment, return this.
     *
     * @param payment
     *             The new value to set.
     *
     * @return This instance.
     */
    public function withPayment($value)
    {
        $this->setPayment($value);
        return $this;
    }

    /**
     * Get the value of the PaymentMethod property.
     *
     * @return String PaymentMethod.
     */
    public function getPaymentMethod()
    {
        return $this->_fields['PaymentMethod']['FieldValue'];
    }

    /**
     * Set the value of the PaymentMethod property.
     *
     * @param string paymentMethod
     * @return this instance
     */
    public function setPaymentMethod($value)
    {
        $this->_fields['PaymentMethod']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Check to see if PaymentMethod is set.
     *
     * @return true if PaymentMethod is set.
     */
    public function isSetPaymentMethod()
    {
                return !is_null($this->_fields['PaymentMethod']['FieldValue']);
            }

    /**
     * Set the value of PaymentMethod, return this.
     *
     * @param paymentMethod
     *             The new value to set.
     *
     * @return This instance.
     */
    public function withPaymentMethod($value)
    {
        $this->setPaymentMethod($value);
        return $this;
    }

}
