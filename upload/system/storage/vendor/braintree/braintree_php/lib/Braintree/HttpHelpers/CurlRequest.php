<?php

namespace Braintree\HttpHelpers;

//phpcs:ignore
class CurlRequest implements HttpRequest
{
    private $_handle = null;

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($url)
    {
        $this->_handle = curl_init($url);
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function setOption($name, $value)
    {
        curl_setopt($this->_handle, $name, $value);
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function execute()
    {
        return curl_exec($this->_handle);
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function getInfo($name)
    {
        return curl_getinfo($this->_handle, $name);
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function getErrorCode()
    {
        return curl_errno($this->_handle);
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function getError()
    {
        return curl_error($this->_handle);
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function close()
    {
        curl_close($this->_handle);
    }
}
