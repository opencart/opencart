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
 * Marketplace Web Service Orders Exception provides details of errors
 * returned by Marketplace Web Service Orders service
 *
 */
class MarketplaceWebServiceOrders_Exception extends Exception

{
    /** @var string */
    private $_message = null;
    /** @var int */
    private $_statusCode = -1;
    /** @var string */
    private $_errorCode = null;
    /** @var string */
    private $_errorType = null;
    /** @var string */
    private $_requestId = null;
    /** @var string */
    private $_xml = null;

    private $_responseHeaderMetadata = null;

    /**
     * Constructs MarketplaceWebServiceOrders_Exception
     * @param array $errorInfo details of exception.
     * Keys are:
     * <ul>
     * <li>Message - (string) text message for an exception</li>
     * <li>StatusCode - (int) HTTP status code at the time of exception</li>
     * <li>ErrorCode - (string) specific error code returned by the service</li>
     * <li>ErrorType - (string) Possible types:  Sender, Receiver or Unknown</li>
     * <li>RequestId - (string) request id returned by the service</li>
     * <li>XML - (string) compete xml response at the time of exception</li>
     * <li>Exception - (Exception) inner exception if any</li>
     * </ul>
     *
     */
    public function __construct(array $errorInfo = array())
    {
        $this->_message = $errorInfo["Message"];
        parent::__construct($this->_message);
        if (array_key_exists("Exception", $errorInfo)) {
            $exception = $errorInfo["Exception"];
            if ($exception instanceof MarketplaceWebServiceOrders_Exception) {
                $this->_statusCode = $exception->getStatusCode();
                $this->_errorCode = $exception->getErrorCode();
                $this->_errorType = $exception->getErrorType();
                $this->_requestId = $exception->getRequestId();
                $this->_xml= $exception->getXML();
                $this->_responseHeaderMetadata = $exception->getResponseHeaderMetadata();
            }
        } else {
            $this->_statusCode = $this->arr_val($errorInfo, "StatusCode");
            $this->_errorCode = $this->arr_val($errorInfo, "ErrorCode");
            $this->_errorType = $this->arr_val($errorInfo, "ErrorType");
            $this->_requestId = $this->arr_val($errorInfo, "RequestId");
            $this->_xml = $this->arr_val($errorInfo, "XML");
            $this->_responseHeaderMetadata = $this->arr_val($errorInfo, "ResponseHeaderMetadata");
        }
    }

    private function arr_val($arr, $key) {
        if(array_key_exists($key, $arr)) {
            return $arr[$key];
        } else {
            return null;
        }
    }

    /**
     * Gets error type returned by the service if available.
     *
     * @return string Error Code returned by the service
     */
    public function getErrorCode(){
        return $this->_errorCode;
    }

    /**
     * Gets error type returned by the service.
     *
     * @return string Error Type returned by the service.
     * Possible types:  Sender, Receiver or Unknown
     */
    public function getErrorType(){
        return $this->_errorType;
    }

    /**
     * Gets error message
     *
     * @return string Error message
     */
    public function getErrorMessage() {
        return $this->_message;
    }

    /**
     * Gets status code returned by the service if available. If status
     * code is set to -1, it means that status code was unavailable at the
     * time exception was thrown
     *
     * @return int status code returned by the service
     */
    public function getStatusCode() {
        return $this->_statusCode;
    }

    /**
     * Gets XML returned by the service if available.
     *
     * @return string XML returned by the service
     */
    public function getXML() {
        return $this->_xml;
    }

    /**
     * Gets Request ID returned by the service if available.
     *
     * @return string Request ID returned by the service
     */
    public function getRequestId() {
        return $this->_requestId;
    }

    public function getResponseHeaderMetadata() {
      return $this->_responseHeaderMetadata;
    }
}
