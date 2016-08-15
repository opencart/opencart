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
 * MarketplaceWebServiceOrders_Model_InvoiceData
 * 
 * Properties:
 * <ul>
 * 
 * <li>InvoiceRequirement: string</li>
 * <li>BuyerSelectedInvoiceCategory: string</li>
 * <li>InvoiceTitle: string</li>
 * <li>InvoiceInformation: string</li>
 *
 * </ul>
 */

 class MarketplaceWebServiceOrders_Model_InvoiceData extends MarketplaceWebServiceOrders_Model {

    public function __construct($data = null)
    {
    $this->_fields = array (
    'InvoiceRequirement' => array('FieldValue' => null, 'FieldType' => 'string'),
    'BuyerSelectedInvoiceCategory' => array('FieldValue' => null, 'FieldType' => 'string'),
    'InvoiceTitle' => array('FieldValue' => null, 'FieldType' => 'string'),
    'InvoiceInformation' => array('FieldValue' => null, 'FieldType' => 'string'),
    );
    parent::__construct($data);
    }

    /**
     * Get the value of the InvoiceRequirement property.
     *
     * @return String InvoiceRequirement.
     */
    public function getInvoiceRequirement()
    {
        return $this->_fields['InvoiceRequirement']['FieldValue'];
    }

    /**
     * Set the value of the InvoiceRequirement property.
     *
     * @param string invoiceRequirement
     * @return this instance
     */
    public function setInvoiceRequirement($value)
    {
        $this->_fields['InvoiceRequirement']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Check to see if InvoiceRequirement is set.
     *
     * @return true if InvoiceRequirement is set.
     */
    public function isSetInvoiceRequirement()
    {
                return !is_null($this->_fields['InvoiceRequirement']['FieldValue']);
            }

    /**
     * Set the value of InvoiceRequirement, return this.
     *
     * @param invoiceRequirement
     *             The new value to set.
     *
     * @return This instance.
     */
    public function withInvoiceRequirement($value)
    {
        $this->setInvoiceRequirement($value);
        return $this;
    }

    /**
     * Get the value of the BuyerSelectedInvoiceCategory property.
     *
     * @return String BuyerSelectedInvoiceCategory.
     */
    public function getBuyerSelectedInvoiceCategory()
    {
        return $this->_fields['BuyerSelectedInvoiceCategory']['FieldValue'];
    }

    /**
     * Set the value of the BuyerSelectedInvoiceCategory property.
     *
     * @param string buyerSelectedInvoiceCategory
     * @return this instance
     */
    public function setBuyerSelectedInvoiceCategory($value)
    {
        $this->_fields['BuyerSelectedInvoiceCategory']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Check to see if BuyerSelectedInvoiceCategory is set.
     *
     * @return true if BuyerSelectedInvoiceCategory is set.
     */
    public function isSetBuyerSelectedInvoiceCategory()
    {
                return !is_null($this->_fields['BuyerSelectedInvoiceCategory']['FieldValue']);
            }

    /**
     * Set the value of BuyerSelectedInvoiceCategory, return this.
     *
     * @param buyerSelectedInvoiceCategory
     *             The new value to set.
     *
     * @return This instance.
     */
    public function withBuyerSelectedInvoiceCategory($value)
    {
        $this->setBuyerSelectedInvoiceCategory($value);
        return $this;
    }

    /**
     * Get the value of the InvoiceTitle property.
     *
     * @return String InvoiceTitle.
     */
    public function getInvoiceTitle()
    {
        return $this->_fields['InvoiceTitle']['FieldValue'];
    }

    /**
     * Set the value of the InvoiceTitle property.
     *
     * @param string invoiceTitle
     * @return this instance
     */
    public function setInvoiceTitle($value)
    {
        $this->_fields['InvoiceTitle']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Check to see if InvoiceTitle is set.
     *
     * @return true if InvoiceTitle is set.
     */
    public function isSetInvoiceTitle()
    {
                return !is_null($this->_fields['InvoiceTitle']['FieldValue']);
            }

    /**
     * Set the value of InvoiceTitle, return this.
     *
     * @param invoiceTitle
     *             The new value to set.
     *
     * @return This instance.
     */
    public function withInvoiceTitle($value)
    {
        $this->setInvoiceTitle($value);
        return $this;
    }

    /**
     * Get the value of the InvoiceInformation property.
     *
     * @return String InvoiceInformation.
     */
    public function getInvoiceInformation()
    {
        return $this->_fields['InvoiceInformation']['FieldValue'];
    }

    /**
     * Set the value of the InvoiceInformation property.
     *
     * @param string invoiceInformation
     * @return this instance
     */
    public function setInvoiceInformation($value)
    {
        $this->_fields['InvoiceInformation']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Check to see if InvoiceInformation is set.
     *
     * @return true if InvoiceInformation is set.
     */
    public function isSetInvoiceInformation()
    {
                return !is_null($this->_fields['InvoiceInformation']['FieldValue']);
            }

    /**
     * Set the value of InvoiceInformation, return this.
     *
     * @param invoiceInformation
     *             The new value to set.
     *
     * @return This instance.
     */
    public function withInvoiceInformation($value)
    {
        $this->setInvoiceInformation($value);
        return $this;
    }

}
