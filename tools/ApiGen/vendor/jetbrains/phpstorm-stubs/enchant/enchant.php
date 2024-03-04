<?php

// Start of enchant v.1.1.0
use JetBrains\PhpStorm\Deprecated;

/**
 * (PHP 5 &gt;= 5.3.0, PECL enchant &gt;= 0.1.0 )<br/>
 * create a new broker object capable of requesting
 * @link https://php.net/manual/en/function.enchant-broker-init.php
 * @return resource|false|EnchantBroker a broker resource on success or <b>FALSE</b>.
 */
function enchant_broker_init() {}

/**
 * Free the broker resource and its dictionaries
 * @link https://php.net/manual/en/function.enchant-broker-free.php
 * @param resource|EnchantBroker $broker <p>
 * Broker resource
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 * @since 5.3
 */
#[Deprecated(reason: "Unset the object instead", since: '8.0')]
function enchant_broker_free($broker) {}

/**
 * (PHP 5 &gt;= 5.3.0, PECL enchant &gt;= 0.1.0 )<br/>
 * Returns the last error of the broker
 * @link https://php.net/manual/en/function.enchant-broker-get-error.php
 * @param resource|EnchantBroker $broker <p>
 * Broker resource.
 * </p>
 * @return string|false Return the msg string if an error was found or <b>FALSE</b>
 */
function enchant_broker_get_error($broker) {}

/**
 * Set the directory path for a given backend
 * @link https://www.php.net/manual/en/function.enchant-broker-set-dict-path.php
 * @param resource|EnchantBroker $broker
 * @param int $dict_type
 * @param string $value
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
#[Deprecated(since: '8.0', reason: 'Relying on this function is highly discouraged.')]
function enchant_broker_set_dict_path($broker, int $dict_type, string $value) {}

/**
 * Get the directory path for a given backend
 * @link https://www.php.net/manual/en/function.enchant-broker-get-dict-path.php
 * @param resource|EnchantBroker $broker
 * @param int $dict_type
 * @return string|false
 */
#[Deprecated(since: '8.0', reason: 'Relying on this function is highly discouraged.')]
function enchant_broker_get_dict_path($broker, $dict_type) {}

/**
 * (PHP 5 &gt;= 5.3.0, PECL enchant &gt;= 1.0.1)<br/>
 * Returns a list of available dictionaries
 * @link https://php.net/manual/en/function.enchant-broker-list-dicts.php
 * @param resource|EnchantBroker $broker <p>
 * Broker resource
 * </p>
 * @return array Returns an array of available dictionaries with their details.
 */
function enchant_broker_list_dicts($broker) {}

/**
 * (PHP 5 &gt;= 5.3.0, PECL enchant &gt;= 0.1.0 )<br/>
 * create a new dictionary using a tag
 * @link https://php.net/manual/en/function.enchant-broker-request-dict.php
 * @param resource|EnchantBroker $broker <p>
 * Broker resource
 * </p>
 * @param string $tag <p>
 * A tag describing the locale, for example en_US, de_DE
 * </p>
 * @return resource|false|EnchantDictionary a dictionary resource on success or <b>FALSE</b> on failure.
 */
function enchant_broker_request_dict($broker, $tag) {}

/**
 * (PHP 5 &gt;= 5.3.0, PECL enchant &gt;= 0.1.0 )<br/>
 * creates a dictionary using a PWL file
 * @link https://php.net/manual/en/function.enchant-broker-request-pwl-dict.php
 * @param resource|EnchantBroker $broker <p>
 * Broker resource
 * </p>
 * @param string $filename <p>
 * Path to the PWL file.
 * </p>
 * @return resource|false|EnchantDictionary a dictionary resource on success or <b>FALSE</b> on failure.
 */
function enchant_broker_request_pwl_dict($broker, $filename) {}

/**
 * (PHP 5 &gt;= 5.3.0, PECL enchant &gt;= 0.1.0 )<br/>
 * Free a dictionary resource
 * @link https://php.net/manual/en/function.enchant-broker-free-dict.php
 * @param resource|EnchantDictionary $dict <p>
 * Dictionary resource.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
#[Deprecated("Unset the object instead", since: '8.0')]
function enchant_broker_free_dict($dict) {}

/**
 * (PHP 5 &gt;= 5.3.0, PECL enchant &gt;= 0.1.0 )<br/>
 * Whether a dictionary exists or not. Using non-empty tag
 * @link https://php.net/manual/en/function.enchant-broker-dict-exists.php
 * @param resource|EnchantBroker $broker <p>
 * Broker resource
 * </p>
 * @param string $tag <p>
 * non-empty tag in the LOCALE format, ex: us_US, ch_DE, etc.
 * </p>
 * @return bool <b>TRUE</b> when the tag exist or <b>FALSE</b> when not.
 */
function enchant_broker_dict_exists($broker, $tag) {}

/**
 * (PHP 5 &gt;= 5.3.0, PECL enchant &gt;= 0.1.0 )<br/>
 * Declares a preference of dictionaries to use for the language
 * @link https://php.net/manual/en/function.enchant-broker-set-ordering.php
 * @param resource|EnchantBroker $broker <p>
 * Broker resource
 * </p>
 * @param string $tag <p>
 * Language tag. The special "*" tag can be used as a language tag
 * to declare a default ordering for any language that does not
 * explicitly declare an ordering.
 * </p>
 * @param string $ordering <p>
 * Comma delimited list of provider names
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function enchant_broker_set_ordering($broker, $tag, $ordering) {}

/**
 * (PHP 5 &gt;= 5.3.0, PECL enchant &gt;= 0.1.0)<br/>
 * Enumerates the Enchant providers
 * @link https://php.net/manual/en/function.enchant-broker-describe.php
 * @param resource|EnchantBroker $broker <p>
 * Broker resource
 * </p>
 * @return array|false
 */
function enchant_broker_describe($broker) {}

/**
 * (PHP 5 &gt;= 5.3.0, PECL enchant &gt;= 0.1.0 )<br/>
 * Check whether a word is correctly spelled or not
 * @link https://php.net/manual/en/function.enchant-dict-check.php
 * @param resource|EnchantDictionary $dict <p>
 * Dictionary resource
 * </p>
 * @param string $word <p>
 * The word to check
 * </p>
 * @return bool <b>TRUE</b> if the word is spelled correctly, <b>FALSE</b> if not.
 */
function enchant_dict_check($dict, $word) {}

/**
 * (PHP 5 &gt;= 5.3.0, PECL enchant &gt;= 0.1.0 )<br/>
 * Will return a list of values if any of those pre-conditions are not met
 * @link https://php.net/manual/en/function.enchant-dict-suggest.php
 * @param resource|EnchantDictionary $dict <p>
 * Dictionary resource
 * </p>
 * @param string $word <p>
 * Word to use for the suggestions.
 * </p>
 * @return array|false Will returns an array of suggestions if the word is bad spelled.
 */
function enchant_dict_suggest($dict, $word) {}

/**
 * (PHP 5 &gt;= 5.3.0, PECL enchant &gt;= 0.1.0 )<br/>
 * add a word to personal word list
 * @link https://php.net/manual/en/function.enchant-dict-add-to-personal.php
 * @param resource $dict <p>
 * Dictionary resource
 * </p>
 * @param string $word <p>
 * The word to add
 * </p>
 * @return void
 * @see enchant_dict_add()
 */
#[Deprecated(
    reason: 'Use enchant_dict_add instead',
    replacement: 'enchant_dict_add(%parameter0%, %parameter1%)',
    since: '8.0'
)]
function enchant_dict_add_to_personal($dict, $word) {}

/**
 * (PHP 5 &gt;= 5.3.0, PECL enchant &gt;= 0.1.0 )<br/>
 * add 'word' to this spell-checking session
 * @link https://php.net/manual/en/function.enchant-dict-add-to-session.php
 * @param resource|EnchantDictionary $dict <p>
 * Dictionary resource
 * </p>
 * @param string $word <p>
 * The word to add
 * </p>
 * @return void
 */
function enchant_dict_add_to_session($dict, $word) {}

/**
 * (PHP 8)<br/>
 * Add a word to personal word list
 * @link https://php.net/manual/en/function.enchant-dict-add.php
 * @param EnchantDictionary $dictionary <p>
 * An Enchant dictionary returned by enchant_broker_request_dict() or enchant_broker_request_pwl_dict().
 * </p>
 * @param string $word <p>
 * The word to add
 * </p>
 * @return void
 * @since 8.0
 */
function enchant_dict_add($dictionary, $word) {}

/**
 * (PHP 5 &gt;= 5.3.0, PECL enchant &gt;= 0.1.0 )<br/>
 * whether or not 'word' exists in this spelling-session
 * @link https://php.net/manual/en/function.enchant-dict-is-in-session.php
 * @param resource $dict <p>
 * Dictionary resource
 * </p>
 * @param string $word <p>
 * The word to lookup
 * </p>
 * @return bool <b>TRUE</b> if the word exists or <b>FALSE</b>
 * @see enchant_dict_is_added
 */
#[Deprecated(
    reason: 'Use enchant_dict_is_added instead',
    replacement: 'enchant_dict_is_added(%parameter0%, %parameter1%)',
    since: '8.0'
)]
function enchant_dict_is_in_session($dict, $word) {}

/**
 * (PHP 5 &gt;= 5.3.0, PECL enchant &gt;= 0.1.0 )<br/>
 * Add a correction for a word
 * @link https://php.net/manual/en/function.enchant-dict-store-replacement.php
 * @param resource|EnchantDictionary $dict <p>
 * Dictionary resource
 * </p>
 * @param string $mis <p>
 * The work to fix
 * </p>
 * @param string $cor <p>
 * The correct word
 * </p>
 * @return void
 */
function enchant_dict_store_replacement($dict, $mis, $cor) {}

/**
 * (PHP 5 &gt;= 5.3.0, PECL enchant &gt;= 0.1.0 )<br/>
 * Returns the last error of the current spelling-session
 * @link https://php.net/manual/en/function.enchant-dict-get-error.php
 * @param resource|EnchantDictionary $dict <p>
 * Dictinaray resource
 * </p>
 * @return string|false the error message as string or <b>FALSE</b> if no error occurred.
 */
function enchant_dict_get_error($dict) {}

/**
 * (PHP 8)<br/>
 * Whether or not 'word' exists in this spelling-session
 * @link https://php.net/manual/en/function.enchant-dict-is-added.php
 * @param EnchantDictionary $dictionary <p>
 * An Enchant dictionary returned by enchant_broker_request_dict() or enchant_broker_request_pwl_dict().
 * </p>
 * @param string $word <p>
 * The word to lookup
 * </p>
 * @return bool <b>TRUE</b> if the word exists or <b>FALSE</b>
 * @since 8.0
 */
function enchant_dict_is_added($dictionary, $word) {}

/**
 * (PHP 5 &gt;= 5.3.0, PECL enchant &gt;= 0.1.0 )<br/>
 * Describes an individual dictionary
 * @link https://php.net/manual/en/function.enchant-dict-describe.php
 * @param resource|EnchantDictionary $dict <p>
 * Dictionary resource
 * </p>
 * @return array Returns the details of the dictionary.
 */
function enchant_dict_describe($dict) {}

/**
 * (PHP 5 &gt;= 5.3.0, PECL enchant:0.2.0-1.0.1)<br/>
 * Check the word is correctly spelled and provide suggestions
 * @link https://php.net/manual/en/function.enchant-dict-quick-check.php
 * @param resource|EnchantDictionary $dict <p>
 * Dictionary resource
 * </p>
 * @param string $word <p>
 * The word to check
 * </p>
 * @param null|array &$suggestions [optional] <p>
 * If the word is not correctly spelled, this variable will
 * contain an array of suggestions.
 * </p>
 * @return bool <b>TRUE</b> if the word is correctly spelled or <b>FALSE</b>
 */
function enchant_dict_quick_check($dict, $word, ?array &$suggestions = null) {}

/**
 * @deprecated 8.0
 */
define('ENCHANT_MYSPELL', 1);
/**
 * @deprecated 8.0
 */
define('ENCHANT_ISPELL', 2);

final class EnchantBroker {}

final class EnchantDictionary {}
// End of enchant v.1.1.0
