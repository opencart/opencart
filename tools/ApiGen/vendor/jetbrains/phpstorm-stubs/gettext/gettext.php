<?php

// Start of gettext v.
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Pure;

/**
 * Sets the default domain
 * @link https://php.net/manual/en/function.textdomain.php
 * @param string|null $domain <p>
 * The new message domain, or <b>NULL</b> to get the current setting without
 * changing it
 * </p>
 * @return string If successful, this function returns the current message
 * domain, after possibly changing it.
 */
function textdomain(?string $domain): string {}

/**
 * Lookup a message in the current domain
 * @link https://php.net/manual/en/function.gettext.php
 * @param string $message <p>
 * The message being translated.
 * </p>
 * @return string a translated string if one is found in the
 * translation table, or the submitted message if not found.
 */
#[Pure]
function _(string $message): string {}

/**
 * Lookup a message in the current domain
 * @link https://php.net/manual/en/function.gettext.php
 * @param string $message <p>
 * The message being translated.
 * </p>
 * @return string a translated string if one is found in the
 * translation table, or the submitted message if not found.
 */
#[Pure]
function gettext(string $message): string {}

/**
 * Override the current domain
 * @link https://php.net/manual/en/function.dgettext.php
 * @param string $domain <p>
 * The domain
 * </p>
 * @param string $message <p>
 * The message
 * </p>
 * @return string A string on success.
 */
function dgettext(string $domain, string $message): string {}

/**
 * Overrides the domain for a single lookup
 * @link https://php.net/manual/en/function.dcgettext.php
 * @param string $domain <p>
 * The domain
 * </p>
 * @param string $message <p>
 * The message
 * </p>
 * @param int $category <p>
 * The category
 * </p>
 * @return string A string on success.
 */
function dcgettext(string $domain, string $message, int $category): string {}

/**
 * Sets the path for a domain
 * @link https://php.net/manual/en/function.bindtextdomain.php
 * @param string $domain <p>
 * The domain
 * </p>
 * @param string|null $directory <p>
 * The directory path. Since PHP 8.0.3 directory is nullable. If null is passed, the currently set directory is returned.
 * </p>
 * @return string|false The full pathname for the <i>domain</i> currently being set.
 */
function bindtextdomain(string $domain, #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: 'string')] $directory): string|false {}

/**
 * Plural version of gettext
 * @link https://php.net/manual/en/function.ngettext.php
 * @param string $singular
 * @param string $plural
 * @param int $count
 * @return string correct plural form of message identified by
 * <i>msgid1</i> and <i>msgid2</i>
 * for count <i>n</i>.
 */
#[Pure]
function ngettext(string $singular, string $plural, int $count): string {}

/**
 * Plural version of dgettext
 * @link https://php.net/manual/en/function.dngettext.php
 * @param string $domain <p>
 * The domain
 * </p>
 * @param string $singular
 * @param string $plural
 * @param int $count
 * @return string A string on success.
 */
#[Pure]
function dngettext(string $domain, string $singular, string $plural, int $count): string {}

/**
 * Plural version of dcgettext
 * @link https://php.net/manual/en/function.dcngettext.php
 * @param string $domain <p>
 * The domain
 * </p>
 * @param string $singular
 * @param string $plural
 * @param int $count
 * @param int $category
 * @return string A string on success.
 */
#[Pure]
function dcngettext(string $domain, string $singular, string $plural, int $count, int $category): string {}

/**
 * Specify the character encoding in which the messages from the DOMAIN message catalog will be returned
 * @link https://php.net/manual/en/function.bind-textdomain-codeset.php
 * @param string $domain <p>
 * The domain
 * </p>
 * @param string|null $codeset <p>
 * The code set. Since 8.0.3 is nullable.  If null is passed, the currently set encoding is returned.
 * </p>
 * @return string|false A string on success.
 */
function bind_textdomain_codeset(string $domain, #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: 'string')] $codeset): string|false {}

// End of gettext v.
