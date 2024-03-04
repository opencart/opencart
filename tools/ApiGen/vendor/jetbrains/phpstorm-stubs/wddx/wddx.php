<?php

// Start of wddx v.

/**
 * Serialize a single value into a WDDX packet
 * @link https://php.net/manual/en/function.wddx-serialize-value.php
 * @param mixed $var <p>
 * The value to be serialized
 * </p>
 * @param string $comment [optional] <p>
 * An optional comment string that appears in the packet header.
 * </p>
 * @return string|false the WDDX packet, or <b>FALSE</b> on error.
 * @removed 7.4
 */
function wddx_serialize_value($var, $comment = null) {}

/**
 * Serialize variables into a WDDX packet
 * @link https://php.net/manual/en/function.wddx-serialize-vars.php
 * @param mixed $var_name <p>
 * Can be either a string naming a variable or an array containing
 * strings naming the variables or another array, etc.
 * </p>
 * @param mixed ...$_ [optional]
 * @return string|false the WDDX packet, or <b>FALSE</b> on error.
 * @removed 7.4
 */
function wddx_serialize_vars($var_name, ...$_) {}

/**
 * Starts a new WDDX packet with structure inside it
 * @link https://php.net/manual/en/function.wddx-packet-start.php
 * @param string $comment [optional] <p>
 * An optional comment string.
 * </p>
 * @return resource|false a packet ID for use in later functions, or <b>FALSE</b> on error.
 * @removed 7.4
 */
function wddx_packet_start($comment = null) {}

/**
 * Ends a WDDX packet with the specified ID
 * @link https://php.net/manual/en/function.wddx-packet-end.php
 * @param resource $packet_id <p>
 * A WDDX packet, returned by <b>wddx_packet_start</b>.
 * </p>
 * @return string the string containing the WDDX packet.
 * @removed 7.4
 */
function wddx_packet_end($packet_id) {}

/**
 * Add variables to a WDDX packet with the specified ID
 * @link https://php.net/manual/en/function.wddx-add-vars.php
 * @param resource $packet_id <p>
 * A WDDX packet, returned by <b>wddx_packet_start</b>.
 * </p>
 * @param mixed $var_name <p>
 * Can be either a string naming a variable or an array containing
 * strings naming the variables or another array, etc.
 * </p>
 * @param mixed ...$_ [optional]
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 * @removed 7.4
 */
function wddx_add_vars($packet_id, $var_name, ...$_) {}

/**
 * Unserializes a WDDX packet
 * @link https://php.net/manual/en/function.wddx-deserialize.php
 * @param string $packet <p>
 * A WDDX packet, as a string or stream.
 * </p>
 * @return mixed the deserialized value which can be a string, a number or an
 * array. Note that structures are deserialized into associative arrays.
 * @removed 7.4
 */
function wddx_deserialize($packet) {}

// End of wddx v.
