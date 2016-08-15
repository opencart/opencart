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
 * MarketplaceWebServiceOrders_Model_PointsGrantedDetail
 * 
 * Properties:
 * <ul>
 * 
 * <li>PointsNumber: int</li>
 * <li>PointsMonetaryValue: MarketplaceWebServiceOrders_Model_Money</li>
 *
 * </ul>
 */

 class MarketplaceWebServiceOrders_Model_PointsGrantedDetail extends MarketplaceWebServiceOrders_Model {

    public function __construct($data = null)
    {
    $this->_fields = array (
    'PointsNumber' => array('FieldValue' => null, 'FieldType' => 'int'),
    'PointsMonetaryValue' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_Money'),
    );
    parent::__construct($data);
    }

    /**
     * Get the value of the PointsNumber property.
     *
     * @return Integer PointsNumber.
     */
    public function getPointsNumber()
    {
        return $this->_fields['PointsNumber']['FieldValue'];
    }

    /**
     * Set the value of the PointsNumber property.
     *
     * @param int pointsNumber
     * @return this instance
     */
    public function setPointsNumber($value)
    {
        $this->_fields['PointsNumber']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Check to see if PointsNumber is set.
     *
     * @return true if PointsNumber is set.
     */
    public function isSetPointsNumber()
    {
                return !is_null($this->_fields['PointsNumber']['FieldValue']);
            }

    /**
     * Set the value of PointsNumber, return this.
     *
     * @param pointsNumber
     *             The new value to set.
     *
     * @return This instance.
     */
    public function withPointsNumber($value)
    {
        $this->setPointsNumber($value);
        return $this;
    }

    /**
     * Get the value of the PointsMonetaryValue property.
     *
     * @return Money PointsMonetaryValue.
     */
    public function getPointsMonetaryValue()
    {
        return $this->_fields['PointsMonetaryValue']['FieldValue'];
    }

    /**
     * Set the value of the PointsMonetaryValue property.
     *
     * @param MarketplaceWebServiceOrders_Model_Money pointsMonetaryValue
     * @return this instance
     */
    public function setPointsMonetaryValue($value)
    {
        $this->_fields['PointsMonetaryValue']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Check to see if PointsMonetaryValue is set.
     *
     * @return true if PointsMonetaryValue is set.
     */
    public function isSetPointsMonetaryValue()
    {
                return !is_null($this->_fields['PointsMonetaryValue']['FieldValue']);
            }

    /**
     * Set the value of PointsMonetaryValue, return this.
     *
     * @param pointsMonetaryValue
     *             The new value to set.
     *
     * @return This instance.
     */
    public function withPointsMonetaryValue($value)
    {
        $this->setPointsMonetaryValue($value);
        return $this;
    }

}
