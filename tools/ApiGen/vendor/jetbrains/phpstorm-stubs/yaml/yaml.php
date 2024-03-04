<?php

// Start of yaml v.1.2.0

/**
 * Scalar entity styles usable by yaml_parse() callback methods.
 * @link https://php.net/manual/en/yaml.constants.php
 */
define('YAML_ANY_SCALAR_STYLE', 0);
define('YAML_PLAIN_SCALAR_STYLE', 1);
define('YAML_SINGLE_QUOTED_SCALAR_STYLE', 2);
define('YAML_DOUBLE_QUOTED_SCALAR_STYLE', 3);
define('YAML_LITERAL_SCALAR_STYLE', 4);
define('YAML_FOLDED_SCALAR_STYLE', 5);

/**
 * Common tags usable by yaml_parse() callback methods.
 * @link https://php.net/manual/en/yaml.constants.php
 */
define('YAML_NULL_TAG', 'tag:yaml.org,2002:null');
define('YAML_BOOL_TAG', 'tag:yaml.org,2002:bool');
define('YAML_STR_TAG', 'tag:yaml.org,2002:str');
define('YAML_INT_TAG', 'tag:yaml.org,2002:int');
define('YAML_FLOAT_TAG', 'tag:yaml.org,2002:float');
define('YAML_TIMESTAMP_TAG', 'tag:yaml.org,2002:timestamp');
define('YAML_SEQ_TAG', 'tag:yaml.org,2002:seq');
define('YAML_MAP_TAG', 'tag:yaml.org,2002:map');
define('YAML_PHP_TAG', '!php/object');

/**
 * Encoding types for yaml_emit()
 * @link https://php.net/manual/en/yaml.constants.php
 */
define('YAML_ANY_ENCODING', 0);
define('YAML_UTF8_ENCODING', 1);
define('YAML_UTF16LE_ENCODING', 2);
define('YAML_UTF16BE_ENCODING', 3);

/**
 * Let emitter choose linebreak character.
 * @link https://php.net/manual/en/yaml.constants.php
 */
define('YAML_ANY_BREAK', 0);
/**
 * Use \r as break character (Mac style).
 * @link https://php.net/manual/en/yaml.constants.php
 */
define('YAML_CR_BREAK', 1);
/**
 * Use \n as break character (Unix style).
 * @link https://php.net/manual/en/yaml.constants.php
 */
define('YAML_LN_BREAK', 2);
/**
 * Use \r\n as break character (DOS style).
 * @link https://php.net/manual/en/yaml.constants.php
 */
define('YAML_CRLN_BREAK', 3);

define('YAML_MERGE_TAG', 'tag:yaml.org,2002:merge');
define('YAML_BINARY_TAG', 'tag:yaml.org,2002:binary');

/**
 * (PHP 5 &gt;= 5.2.0, PECL yaml &gt;= 0.5.0)<br/>
 * Send the YAML representation of a value to a file
 * @link https://php.net/manual/en/function.yaml-emit-file.php
 * @param string $filename Path to the file.
 * @param mixed $data The data being encoded. Can be any type except a resource.
 * @param int $encoding Output character encoding chosen from YAML_ANY_ENCODING, YAML_UTF8_ENCODING, YAML_UTF16LE_ENCODING, YAML_UTF16BE_ENCODING.
 * @param int $linebreak Output linebreak style chosen from YAML_ANY_BREAK, YAML_CR_BREAK, YAML_LN_BREAK, YAML_CRLN_BREAK.
 * @param array $callbacks [optional] Content handlers for YAML nodes. Associative array of YAML tag => callable mappings. See parse callbacks for more details.
 * @return bool Returns TRUE on success.
 */
function yaml_emit_file($filename, $data, $encoding = YAML_ANY_ENCODING, $linebreak = YAML_ANY_BREAK, array $callbacks = []) {}

/**
 * (PHP 5 &gt;= 5.2.0, PECL yaml &gt;= 0.5.0)<br/>
 * @link https://php.net/manual/en/function.yaml-emit.php
 * @param mixed $data The data being encoded. Can be any type except a resource.
 * @param int $encoding [optional] Output character encoding chosen from YAML_ANY_ENCODING, YAML_UTF8_ENCODING, YAML_UTF16LE_ENCODING, YAML_UTF16BE_ENCODING.
 * @param int $linebreak [optional] Output linebreak style chosen from YAML_ANY_BREAK, YAML_CR_BREAK, YAML_LN_BREAK, YAML_CRLN_BREAK.
 * @param array $callbacks [optional] Content handlers for YAML nodes. Associative array of YAML tag => callable mappings. See parse callbacks for more details.
 * @return string Returns a YAML encoded string on success.
 */
function yaml_emit($data, $encoding = YAML_ANY_ENCODING, $linebreak = YAML_ANY_BREAK, array $callbacks = []) {}

/**
 * (PHP 5 &gt;= 5.2.0, PECL yaml &gt;= 0.4.0)<br/>
 * Parse a YAML stream from a file
 * @link https://php.net/manual/en/function.yaml-parse-file.php
 * @param string $filename Path to the file.
 * @param int $pos [optional] Document to extract from stream (-1 for all documents, 0 for first document, ...).
 * @param int &$ndocs [optional] If ndocs is provided, then it is filled with the number of documents found in stream.
 * @param array $callbacks [optional] Content handlers for YAML nodes. Associative array of YAML tag => callable mappings. See parse callbacks for more details.
 * @return mixed|false Returns the value encoded in input in appropriate PHP type or FALSE on failure. If pos is -1 an array will be returned with one entry for each document found in the stream.
 */
function yaml_parse_file($filename, $pos = 0, &$ndocs = null, array $callbacks = []) {}

/**
 * (PHP 5 &gt;= 5.2.0, PECL yaml &gt;= 0.4.0)<br/>
 * Parse a Yaml stream from a URL
 * @link https://php.net/manual/en/function.yaml-parse-url.php
 * @param string $url url should be of the form "scheme://...". PHP will search for a protocol handler (also known as a wrapper) for that scheme. If no wrappers for that protocol are registered, PHP will emit a notice to help you track potential problems in your script and then continue as though filename specifies a regular file.
 * @param int $pos [optional] Document to extract from stream (-1 for all documents, 0 for first document, ...).
 * @param int &$ndocs [optional] If ndocs is provided, then it is filled with the number of documents found in stream.
 * @param array $callbacks [optional] Content handlers for YAML nodes. Associative array of YAML tag => callable mappings. See parse callbacks for more details.
 * @return mixed|false Returns the value encoded in input in appropriate PHP type or FALSE on failure. If pos is -1 an array will be returned with one entry for each document found in the stream.
 */
function yaml_parse_url($url, $pos = 0, &$ndocs = null, array $callbacks = []) {}

/**
 * (PHP 5 &gt;= 5.2.0, PECL yaml &gt;= 0.4.0)<br/>
 * Parse a YAML stream
 * @link https://php.net/manual/en/function.yaml-parse.php
 * @param string $input The string to parse as a YAML document stream.
 * @param int $pos [optional] Document to extract from stream (-1 for all documents, 0 for first document, ...).
 * @param int &$ndocs [optional] If ndocs is provided, then it is filled with the number of documents found in stream.
 * @param array $callbacks [optional] Content handlers for YAML nodes. Associative array of YAML tag => callable mappings. See parse callbacks for more details.
 * @return mixed|false Returns the value encoded in input in appropriate PHP type or FALSE on failure. If pos is -1 an array will be returned with one entry for each document found in the stream.
 */
function yaml_parse($input, $pos = 0, &$ndocs = null, array $callbacks = []) {}
