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
 * MarketplaceWebServiceOrders_Model_GetServiceStatusResult
 * 
 * Properties:
 * <ul>
 * 
 * <li>Status: string</li>
 * <li>Timestamp: string</li>
 * <li>MessageId: string</li>
 * <li>Messages: array</li>
 *
 * </ul>
 */

 class MarketplaceWebServiceOrders_Model_GetServiceStatusResult extends MarketplaceWebServiceOrders_Model {

    public function __construct($data = null)
    {
    $this->_fields = array (
    'Status' => array('FieldValue' => null, 'FieldType' => 'string'),
    'Timestamp' => array('FieldValue' => null, 'FieldType' => 'string'),
    'MessageId' => array('FieldValue' => null, 'FieldType' => 'string'),
    'Messages' => array('FieldValue' => array(), 'FieldType' => array('MarketplaceWebServiceOrders_Model_Message'), 'ListMemberName' => 'Message'),
    );
    parent::__construct($data);
    }

    /**
     * Get the value of the Status property.
     *
     * @return String Status.
     */
    public function getStatus()
    {
        return $this->_fields['Status']['FieldValue'];
    }

    /**
     * Set the value of the Status property.
     *
     * @param string status
     * @return this instance
     */
    public function setStatus($value)
    {
        $this->_fields['Status']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Check to see if Status is set.
     *
     * @return true if Status is set.
     */
    public function isSetStatus()
    {
                return !is_null($this->_fields['Status']['FieldValue']);
            }

    /**
     * Set the value of Status, return this.
     *
     * @param status
     *             The new value to set.
     *
     * @return This instance.
     */
    public function withStatus($value)
    {
        $this->setStatus($value);
        return $this;
    }

    /**
     * Get the value of the Timestamp property.
     *
     * @return XMLGregorianCalendar Timestamp.
     */
    public function getTimestamp()
    {
        return $this->_fields['Timestamp']['FieldValue'];
    }

    /**
     * Set the value of the Timestamp property.
     *
     * @param string timestamp
     * @return this instance
     */
    public function setTimestamp($value)
    {
        $this->_fields['Timestamp']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Check to see if Timestamp is set.
     *
     * @return true if Timestamp is set.
     */
    public function isSetTimestamp()
    {
                return !is_null($this->_fields['Timestamp']['FieldValue']);
            }

    /**
     * Set the value of Timestamp, return this.
     *
     * @param timestamp
     *             The new value to set.
     *
     * @return This instance.
     */
    public function withTimestamp($value)
    {
        $this->setTimestamp($value);
        return $this;
    }

    /**
     * Get the value of the MessageId property.
     *
     * @return String MessageId.
     */
    public function getMessageId()
    {
        return $this->_fields['MessageId']['FieldValue'];
    }

    /**
     * Set the value of the MessageId property.
     *
     * @param string messageId
     * @return this instance
     */
    public function setMessageId($value)
    {
        $this->_fields['MessageId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Check to see if MessageId is set.
     *
     * @return true if MessageId is set.
     */
    public function isSetMessageId()
    {
                return !is_null($this->_fields['MessageId']['FieldValue']);
            }

    /**
     * Set the value of MessageId, return this.
     *
     * @param messageId
     *             The new value to set.
     *
     * @return This instance.
     */
    public function withMessageId($value)
    {
        $this->setMessageId($value);
        return $this;
    }

    /**
     * Get the value of the Messages property.
     *
     * @return List<Message> Messages.
     */
    public function getMessages()
    {
        if ($this->_fields['Messages']['FieldValue'] == null)
        {
            $this->_fields['Messages']['FieldValue'] = array();
        }
        return $this->_fields['Messages']['FieldValue'];
    }

    /**
     * Set the value of the Messages property.
     *
     * @param array messages
     * @return this instance
     */
    public function setMessages($value)
    {
        if (!$this->_isNumericArray($value)) {
            $value = array ($value);
        }
        $this->_fields['Messages']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Clear Messages.
     */
    public function unsetMessages()
    {
        $this->_fields['Messages']['FieldValue'] = array();
    }

    /**
     * Check to see if Messages is set.
     *
     * @return true if Messages is set.
     */
    public function isSetMessages()
    {
                return !empty($this->_fields['Messages']['FieldValue']);
            }

    /**
     * Add values for Messages, return this.
     *
     * @param messages
     *             New values to add.
     *
     * @return This instance.
     */
    public function withMessages()
    {
        foreach (func_get_args() as $Messages)
        {
            $this->_fields['Messages']['FieldValue'][] = $Messages;
        }
        return $this;
    }

}
