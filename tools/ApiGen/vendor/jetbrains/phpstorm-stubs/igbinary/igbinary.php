<?php

// Start of igbinary v.1.0.0

/** Generates a storable representation of a value.
 * This is useful for storing or passing PHP values around without losing their type and structure.
 * To make the serialized string into a PHP value again, use {@link igbinary_unserialize}.
 *
 * igbinary_serialize() handles all types, except the resource-type.
 * You can even serialize() arrays that contain references to itself.
 * Circular references inside the array/object you are serialize()ing will also be stored.
 *
 * If object implements {@link https://secure.php.net/~helly/php/ext/spl/interfaceSerializable.html Serializable} -interface,
 * PHP will call the member function serialize to get serialized representation of object.
 *
 * When serializing objects, PHP will attempt to call the member function __sleep prior to serialization.
 * This is to allow the object to do any last minute clean-up, etc. prior to being serialized.
 * Likewise, when the object is restored using unserialize() the __wakeup member function is called.
 *
 * @param mixed $value The value to be serialized.
 * @return string|null Returns a string containing a byte-stream representation of value that can be stored anywhere or <b>NULL</b> on error.
 * @link https://secure.php.net/serialize PHP default serialize
 */
function igbinary_serialize($value) {}

/** Creates a PHP value from a stored representation.
 * igbinary_unserialize() takes a single serialized variable and converts it back into a PHP value.
 *
 * If the variable being unserialized is an object, after successfully reconstructing the object
 * PHP will automatically attempt to call the __wakeup() member function (if it exists).
 * In case the passed string is not unserializeable, NULL is returned and E_WARNING is issued.
 *
 * @param string $str The serialized string.
 * @return mixed|false The converted value is returned, and can be a boolean, integer, float, string, array, object or <b>false</b> by empty string input.
 * @link https://secure.php.net/manual/en/function.unserialize.php PHP default unserialize
 * @link https://secure.php.net/~helly/php/ext/spl/interfaceSerializable.html Serializable
 */
function igbinary_unserialize($str) {}

// End of igbinary v.1.0.0
