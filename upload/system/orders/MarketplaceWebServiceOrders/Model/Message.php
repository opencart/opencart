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
 * MarketplaceWebServiceOrders_Model_Message
 * 
 * Properties:
 * <ul>
 * 
 * <li>Locale: string</li>
 * <li>Text: string</li>
 *
 * </ul>
 */

 class MarketplaceWebServiceOrders_Model_Message extends MarketplaceWebServiceOrders_Model {

    public function __construct($data = null)
    {
    $this->_fields = array (
    'Locale' => array('FieldValue' => null, 'FieldType' => 'string'),
    'Text' => array('FieldValue' => null, 'FieldType' => 'string'),
    );
    parent::__construct($data);
    }

    /**
     * Get the value of the Locale property.
     *
     * @return String Locale.
     */
    public function getLocale()
    {
        return $this->_fields['Locale']['FieldValue'];
    }

    /**
     * Set the value of the Locale property.
     *
     * @param string locale
     * @return this instance
     */
    public function setLocale($value)
    {
        $this->_fields['Locale']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Check to see if Locale is set.
     *
     * @return true if Locale is set.
     */
    public function isSetLocale()
    {
                return !is_null($this->_fields['Locale']['FieldValue']);
            }

    /**
     * Set the value of Locale, return this.
     *
     * @param locale
     *             The new value to set.
     *
     * @return This instance.
     */
    public function withLocale($value)
    {
        $this->setLocale($value);
        return $this;
    }

    /**
     * Get the value of the Text property.
     *
     * @return String Text.
     */
    public function getText()
    {
        return $this->_fields['Text']['FieldValue'];
    }

    /**
     * Set the value of the Text property.
     *
     * @param string text
     * @return this instance
     */
    public function setText($value)
    {
        $this->_fields['Text']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Check to see if Text is set.
     *
     * @return true if Text is set.
     */
    public function isSetText()
    {
                return !is_null($this->_fields['Text']['FieldValue']);
            }

    /**
     * Set the value of Text, return this.
     *
     * @param text
     *             The new value to set.
     *
     * @return This instance.
     */
    public function withText($value)
    {
        $this->setText($value);
        return $this;
    }

}
