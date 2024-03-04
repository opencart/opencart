<?php

// Start of xmlrpc v.0.51

/**
 * Generates XML for a PHP value
 * @link https://php.net/manual/en/function.xmlrpc-encode.php
 * @param mixed $value
 * @return string
 */
function xmlrpc_encode($value) {}

/**
 * Decodes XML into native PHP types
 * @link https://php.net/manual/en/function.xmlrpc-decode.php
 * @param string $xml <p>
 * XML response returned by XMLRPC method.
 * </p>
 * @param string $encoding [optional] <p>
 * Input encoding supported by iconv.
 * </p>
 * @return mixed either an array, or an integer, or a string, or a boolean according
 * to the response returned by the XMLRPC method.
 */
function xmlrpc_decode($xml, $encoding = "iso-8859-1") {}

/**
 * Decodes XML into native PHP types
 * @link https://php.net/manual/en/function.xmlrpc-decode-request.php
 * @param string $xml
 * @param string &$method
 * @param string $encoding [optional]
 * @return mixed
 */
function xmlrpc_decode_request($xml, &$method, $encoding = null) {}

/**
 * Generates XML for a method request
 * @link https://php.net/manual/en/function.xmlrpc-encode-request.php
 * @param string $method <p>
 * Name of the method to call.
 * </p>
 * @param mixed $params <p>
 * Method parameters compatible with method signature.
 * </p>
 * @param null|array $output_options [optional] <p>
 * Array specifying output options may contain (default values are
 * emphasised):
 * output_type: php, xml</p>
 * @return string a string containing the XML representation of the request.
 */
function xmlrpc_encode_request($method, $params, ?array $output_options = null) {}

/**
 * Gets xmlrpc type for a PHP value
 * @link https://php.net/manual/en/function.xmlrpc-get-type.php
 * @param mixed $value <p>
 * PHP value
 * </p>
 * @return string the XML-RPC type.
 */
function xmlrpc_get_type($value) {}

/**
 * Sets xmlrpc type, base64 or datetime, for a PHP string value
 * @link https://php.net/manual/en/function.xmlrpc-set-type.php
 * @param string &$value <p>
 * Value to set the type
 * </p>
 * @param string $type <p>
 * 'base64' or 'datetime'
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 * If successful, <i>value</i> is converted to an object.
 */
function xmlrpc_set_type(&$value, $type) {}

/**
 * Determines if an array value represents an XMLRPC fault
 * @link https://php.net/manual/en/function.xmlrpc-is-fault.php
 * @param array $arg <p>
 * Array returned by <b>xmlrpc_decode</b>.
 * </p>
 * @return bool <b>TRUE</b> if the argument means fault, <b>FALSE</b> otherwise. Fault
 * description is available in $arg["faultString"], fault
 * code is in $arg["faultCode"].
 */
function xmlrpc_is_fault(array $arg) {}

/**
 * Creates an xmlrpc server
 * @link https://php.net/manual/en/function.xmlrpc-server-create.php
 * @return resource
 */
function xmlrpc_server_create() {}

/**
 * Destroys server resources
 * @link https://php.net/manual/en/function.xmlrpc-server-destroy.php
 * @param resource $server
 * @return int
 */
function xmlrpc_server_destroy($server) {}

/**
 * Register a PHP function to resource method matching method_name
 * @link https://php.net/manual/en/function.xmlrpc-server-register-method.php
 * @param resource $server
 * @param string $method_name
 * @param callable $function
 * @return bool
 */
function xmlrpc_server_register_method($server, $method_name, $function) {}

/**
 * Parses XML requests and call methods
 * @link https://php.net/manual/en/function.xmlrpc-server-call-method.php
 * @param resource $server
 * @param string $xml
 * @param mixed $user_data
 * @param null|array $output_options [optional]
 * @return string
 */
function xmlrpc_server_call_method($server, $xml, $user_data, ?array $output_options = null) {}

/**
 * Decodes XML into a list of method descriptions
 * @link https://php.net/manual/en/function.xmlrpc-parse-method-descriptions.php
 * @param string $xml
 * @return array
 */
function xmlrpc_parse_method_descriptions($xml) {}

/**
 * Adds introspection documentation
 * @link https://php.net/manual/en/function.xmlrpc-server-add-introspection-data.php
 * @param resource $server
 * @param array $desc
 * @return int
 */
function xmlrpc_server_add_introspection_data($server, array $desc) {}

/**
 * Register a PHP function to generate documentation
 * @link https://php.net/manual/en/function.xmlrpc-server-register-introspection-callback.php
 * @param resource $server
 * @param string $function
 * @return bool
 */
function xmlrpc_server_register_introspection_callback($server, $function) {}

// End of xmlrpc v.0.51
