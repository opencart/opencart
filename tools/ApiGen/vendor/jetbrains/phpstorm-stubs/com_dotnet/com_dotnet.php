<?php

// Start of com_dotnet v.

/**
 * The COM class allows you to instantiate an OLE compatible COM object and call its methods and access its properties.
 * @link https://php.net/manual/en/class.com.php
 */
class COM
{
    /**
     * (PHP 4 &gt;= 4.1.0, PHP 5, PHP 7)<br/>
     * COM class constructor.
     * @param string $module_name
     * @param string $server_name [optional]
     * @param int $codepage [optional]
     * @param string $typelib [optional]
     */
    public function __construct($module_name, $server_name = null, $codepage = CP_ACP, $typelib = null) {}

    public function __get($name) {}

    public function __set($name, $value) {}

    public function __call($name, $args) {}
}

/**
 * The DOTNET class allows you to instantiate a class from a .Net assembly and call its methods and access its properties.
 * @link https://php.net/manual/en/class.dotnet.php
 */
class DOTNET
{
    /**
     * (PHP 4 &gt;= 4.1.0, PHP 5, PHP 7)<br/>
     * COM class constructor.
     * @param string $assembly_name
     * @param string $class_name
     * @param int $codepage [optional]
     */
    public function __construct($assembly_name, string $class_name, $codepage = CP_ACP) {}

    public function __get($name) {}

    public function __set($name, $value) {}

    public function __call($name, $args) {}
}

/**
 * The VARIANT is COM's equivalent of the PHP zval; it is a structure that can contain a value with a range of different possible types. The VARIANT class provided by the COM extension allows you to have more control over the way that PHP passes values to and from COM.
 * @link https://php.net/manual/en/class.variant.php
 */
class VARIANT
{
    /**
     * (PHP 4 &gt;= 4.1.0, PHP 5, PHP 7)<br/>
     * COM class constructor.
     * @param mixed $value [optional]
     * @param int $type [optional]
     * @param int $codepage [optional]
     */
    public function __construct($value = null, int $type = VT_EMPTY, $codepage = CP_ACP) {}

    public function __get($name) {}

    public function __set($name, $value) {}

    public function __call($name, $args) {}
}

/**
 * This extension will throw instances of the class com_exception whenever there is a potentially fatal error reported by COM. All COM exceptions have a well-defined code property that corresponds to the HRESULT return value from the various COM operations. You may use this code to make programmatic decisions on how to handle the exception.
 * @link https://php.net/manual/en/com.error-handling.php
 */
class com_exception extends \Exception {}

/**
 * (PHP 5, PHP 7)<br/>
 * Generate a globally unique identifier (GUID)
 * @link https://php.net/manual/en/function.com-create-guid.php
 * @return string
 */
function com_create_guid() {}

/**
 * (PHP 4 &gt;= 4.2.0, PHP 5, PHP 7)<br/>
 * Connect events from a COM object to a PHP object
 * @link https://php.net/manual/en/function.com-event-sink.php
 * @param \VARIANT $comobject
 * @param object $sinkobject
 * @param string $sinkinterface [optional]
 * @return bool
 */
function com_event_sink($comobject, $sinkobject, $sinkinterface = null) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Returns a handle to an already running instance of a COM object
 * @link https://php.net/manual/en/function.com-get-active-object.php
 * @param string $progid
 * @param int $code_page [optional]
 * @return \VARIANT
 */
function com_get_active_object($progid, $code_page = CP_ACP) {}

/**
 * (PHP 4 &gt;= 4.1.0, PHP 5, PHP 7)<br/>
 * Loads a Typelib
 * @link https://php.net/manual/en/function.com-get-active-object.php
 * @param string $typelib_name
 * @param bool $case_insensitive [optional]
 * @return bool
 */
function com_load_typelib($typelib_name, $case_insensitive = true) {}

/**
 * (PHP 4 &gt;= 4.2.0, PHP 5, PHP 7)<br/>
 * Process COM messages, sleeping for up to timeoutms milliseconds
 * @link https://php.net/manual/en/function.com-message-pump.php
 * @param int $timeoutms [optional]
 * @return bool
 */
function com_message_pump($timeoutms = 0) {}

/**
 * (PHP 4 &gt;= 4.2.0, PHP 5, PHP 7)<br/>
 * Print out a PHP class definition for a dispatchable interface
 * @link https://php.net/manual/en/function.com-print-typeinfo.php
 * @param object $comobject
 * @param string $dispinterface [optional]
 * @param bool $wantsink [optional]
 * @return bool
 */
function com_print_typeinfo($comobject, $dispinterface = null, $wantsink = false) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Returns the absolute value of a variant
 * @link https://php.net/manual/en/function.variant-abs.php
 * @param mixed $val
 * @return mixed
 */
function variant_abs($val) {}

/**
 * (PHP 5, PHP 7)<br/>
 * "Adds" two variant values together and returns the result
 * @link https://php.net/manual/en/function.variant-abs.php
 * @param mixed $left
 * @param mixed $right
 * @return mixed
 */
function variant_add($left, $right) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Performs a bitwise AND operation between two variants
 * @link https://php.net/manual/en/function.variant-and.php
 * @param mixed $left
 * @param mixed $right
 * @return mixed
 */
function variant_and($left, $right) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Convert a variant into a new variant object of another type
 * @link https://php.net/manual/en/function.variant-cast.php
 * @param \VARIANT $variant
 * @param int $type
 * @return \VARIANT
 */
function variant_cast($variant, $type) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Concatenates two variant values together and returns the result
 * @link https://php.net/manual/en/function.variant-cat.php
 * @param mixed $left
 * @param mixed $right
 * @return mixed
 */
function variant_cat($left, $right) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Compares two variants
 * @link https://php.net/manual/en/function.variant-cmp.php
 * @param mixed $left
 * @param mixed $right
 * @param int $lcid [optional]
 * @param int $flags [optional]
 * @return int
 */
function variant_cmp($left, $right, $lcid = null, $flags = null) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Returns a variant date representation of a Unix timestamp
 * @link https://php.net/manual/en/function.variant-date-from-timestamp.php
 * @param int $timestamp
 * @return \VARIANT
 */
function variant_date_from_timestamp($timestamp) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Converts a variant date/time value to Unix timestamp
 * @link https://php.net/manual/en/function.variant-date-to-timestamp.php
 * @param \VARIANT $variant
 * @return int
 */
function variant_date_to_timestamp($variant) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Returns the result from dividing two variants
 * @link https://php.net/manual/en/function.variant-div.php
 * @param mixed $left
 * @param mixed $right
 * @return mixed
 */
function variant_div($left, $right) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Performs a bitwise equivalence on two variants
 * @link https://php.net/manual/en/function.variant-eqv.php
 * @param mixed $left
 * @param mixed $right
 * @return mixed
 */
function variant_eqv($left, $right) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Returns the integer portion of a variant
 * @link https://php.net/manual/en/function.variant-fix.php
 * @param mixed $variant
 * @return mixed
 */
function variant_fix($variant) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Returns the type of a variant object
 * @link https://php.net/manual/en/function.variant-get-type.php
 * @param VARIANT $variant
 * @return int
 */
function variant_get_type($variant) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Converts variants to integers and then returns the result from dividing them
 * @link https://php.net/manual/en/function.variant-idiv.php
 * @param mixed $left
 * @param mixed $right
 * @return mixed
 */
function variant_idiv($left, $right) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Performs a bitwise implication on two variants
 * @link https://php.net/manual/en/function.variant-imp.php
 * @param mixed $left
 * @param mixed $right
 * @return mixed
 */
function variant_imp($left, $right) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Returns the integer portion of a variant
 * @link https://php.net/manual/en/function.variant-int.php
 * @param mixed $variant
 * @return mixed
 */
function variant_int($variant) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Divides two variants and returns only the remainder
 * @link https://php.net/manual/en/function.variant-mod.php
 * @param mixed $left
 * @param mixed $right
 * @return mixed
 */
function variant_mod($left, $right) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Multiplies the values of the two variants
 * @link https://php.net/manual/en/function.variant-mul.php
 * @param mixed $left
 * @param mixed $right
 * @return mixed
 */
function variant_mul($left, $right) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Performs logical negation on a variant
 * @link https://php.net/manual/en/function.variant-neg.php
 * @param mixed $variant
 * @return mixed
 */
function variant_neg($variant) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Performs bitwise not negation on a variant
 * @link https://php.net/manual/en/function.variant-not.php
 * @param mixed $variant
 * @return mixed
 */
function variant_not($variant) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Performs a logical disjunction on two variants
 * @link https://php.net/manual/en/function.variant-or.php
 * @param mixed $left
 * @param mixed $right
 * @return mixed
 */
function variant_or($left, $right) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Returns the result of performing the power function with two variants
 * @link https://php.net/manual/en/function.variant-pow.php
 * @param mixed $left
 * @param mixed $right
 * @return mixed
 */
function variant_pow($left, $right) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Rounds a variant to the specified number of decimal places
 * @link https://php.net/manual/en/function.variant-round.php
 * @param mixed $variant
 * @param int $decimals
 * @return mixed
 */
function variant_round($variant, $decimals) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Convert a variant into another type "in-place"
 * @link https://php.net/manual/en/function.variant-set-type.php
 * @param VARIANT $variant
 * @param int $type
 * @return void
 */
function variant_set_type($variant, $type) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Assigns a new value for a variant object
 * @link https://php.net/manual/en/function.variant-set.php
 * @param VARIANT $variant
 * @param mixed $value
 * @return void
 */
function variant_set($variant, $value) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Subtracts the value of the right variant from the left variant value
 * @link https://php.net/manual/en/function.variant-sub.php
 * @param mixed $left
 * @param mixed $right
 * @return mixed
 */
function variant_sub($left, $right) {}

/**
 * (PHP 5, PHP 7)<br/>
 * Performs a logical exclusion on two variants
 * @link https://php.net/manual/en/function.variant-xor.php
 * @param mixed $left
 * @param mixed $right
 * @return mixed
 */
function variant_xor($left, $right) {}

define('CLSCTX_INPROC_SERVER', 1);
define('CLSCTX_INPROC_HANDLER', 2);
define('CLSCTX_LOCAL_SERVER', 4);
define('CLSCTX_REMOTE_SERVER', 16);
define('CLSCTX_SERVER', 21);
define('CLSCTX_ALL', 23);

define('VT_NULL', 1);
define('VT_EMPTY', 0);
define('VT_UI1', 17);
define('VT_I2', 2);
define('VT_I4', 3);
define('VT_R4', 4);
define('VT_R8', 5);
define('VT_BOOL', 11);
define('VT_ERROR', 10);
define('VT_CY', 6);
define('VT_DATE', 7);
define('VT_BSTR', 8);
define('VT_DECIMAL', 14);
define('VT_UNKNOWN', 13);
define('VT_DISPATCH', 9);
define('VT_VARIANT', 12);
define('VT_I1', 16);
define('VT_UI2', 18);
define('VT_UI4', 19);
define('VT_INT', 22);
define('VT_UINT', 23);
define('VT_ARRAY', 8192);
define('VT_BYREF', 16384);

define('CP_ACP', 0);
define('CP_MACCP', 2);
define('CP_OEMCP', 1);
define('CP_UTF7', 65000);
define('CP_UTF8', 65001);
define('CP_SYMBOL', 42);
define('CP_THREAD_ACP', 3);

define('VARCMP_LT', 0);
define('VARCMP_EQ', 1);
define('VARCMP_GT', 2);
define('VARCMP_NULL', 3);

define('NORM_IGNORECASE', 1);
define('NORM_IGNORENONSPACE', 2);
define('NORM_IGNORESYMBOLS', 4);
define('NORM_IGNOREWIDTH', 131072);
define('NORM_IGNOREKANATYPE', 65536);
define('NORM_IGNOREKASHIDA', 262144);

define('DISP_E_DIVBYZERO', -2147352558);
define('DISP_E_OVERFLOW', -2147352566);
define('MK_E_UNAVAILABLE', -2147221021);

// End of com v.
