<?php

namespace Braintree\HttpHelpers;

/**
 * Braintree HttpRequest module
 *
 * Facilitates web requests made by the SDK
 */
interface HttpRequest
{
    // phpcs:disable PEAR.Commenting.FunctionComment.Missing
    public function setOption($name, $value);
    public function execute();
    public function getInfo($name);
    public function getErrorCode();
    public function getError();
    public function close();
    // phpcs:enable PEAR.Commenting.FunctionComment.Missing
}
