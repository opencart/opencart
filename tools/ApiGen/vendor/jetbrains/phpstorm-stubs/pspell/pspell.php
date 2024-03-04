<?php

// Start of pspell v.
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

/**
 * Load a new dictionary
 * @link https://php.net/manual/en/function.pspell-new.php
 * @param string $language <p>
 * The language parameter is the language code which consists of the
 * two letter ISO 639 language code and an optional two letter ISO
 * 3166 country code after a dash or underscore.
 * </p>
 * @param string $spelling <p>
 * The spelling parameter is the requested spelling for languages
 * with more than one spelling such as English. Known values are
 * 'american', 'british', and 'canadian'.
 * </p>
 * @param string $jargon <p>
 * The jargon parameter contains extra information to distinguish
 * two different words lists that have the same language and
 * spelling parameters.
 * </p>
 * @param string $encoding <p>
 * The encoding parameter is the encoding that words are expected to
 * be in. Valid values are 'utf-8', 'iso8859-*', 'koi8-r',
 * 'viscii', 'cp1252', 'machine unsigned 16', 'machine unsigned
 * 32'. This parameter is largely untested, so be careful when
 * using.
 * </p>
 * @param int $mode <p>
 * The mode parameter is the mode in which spellchecker will work.
 * There are several modes available:
 * <b>PSPELL_FAST</b> - Fast mode (least number of
 * suggestions)</p>
 * @return int|false the dictionary link identifier on success or <b>FALSE</b> on failure.
 */
#[LanguageLevelTypeAware(['8.1' => 'PSpell\Dictionary|false'], default: 'int|false')]
function pspell_new(string $language, string $spelling = "", string $jargon = "", string $encoding = "", int $mode = 0) {}

/**
 * Load a new dictionary with personal wordlist
 * @link https://php.net/manual/en/function.pspell-new-personal.php
 * @param string $filename <p>
 * The file where words added to the personal list will be stored.
 * It should be an absolute filename beginning with '/' because otherwise
 * it will be relative to $HOME, which is "/root" for most systems, and
 * is probably not what you want.
 * </p>
 * @param string $language <p>
 * The language code which consists of the two letter ISO 639 language
 * code and an optional two letter ISO 3166 country code after a dash
 * or underscore.
 * </p>
 * @param string $spelling <p>
 * The requested spelling for languages with more than one spelling such
 * as English. Known values are 'american', 'british', and 'canadian'.
 * </p>
 * @param string $jargon <p>
 * Extra information to distinguish two different words lists that have
 * the same language and spelling parameters.
 * </p>
 * @param string $encoding <p>
 * The encoding that words are expected to be in. Valid values are
 * utf-8, iso8859-*,
 * koi8-r, viscii,
 * cp1252, machine unsigned 16,
 * machine unsigned 32.
 * </p>
 * @param int $mode <p>
 * The mode in which spellchecker will work. There are several modes available:
 * <b>PSPELL_FAST</b> - Fast mode (least number of
 * suggestions)</p>
 * @return int|false the dictionary link identifier for use in other pspell functions.
 */
#[LanguageLevelTypeAware(['8.1' => 'PSpell\Dictionary|false'], default: 'int|false')]
function pspell_new_personal(string $filename, string $language, string $spelling = "", string $jargon = "", string $encoding = "", int $mode = 0) {}

/**
 * Load a new dictionary with settings based on a given config
 * @link https://php.net/manual/en/function.pspell-new-config.php
 * @param int $config <p>
 * The <i>config</i> parameter is the one returned by
 * <b>pspell_config_create</b> when the config was created.
 * </p>
 * @return int|false a dictionary link identifier on success.
 */
#[LanguageLevelTypeAware(['8.1' => 'PSpell\Dictionary|false'], default: 'int|false')]
function pspell_new_config(#[LanguageLevelTypeAware(['8.1' => 'PSpell\Config'], default: 'int')] $config) {}

/**
 * Check a word
 * @link https://php.net/manual/en/function.pspell-check.php
 * @param int $dictionary
 * @param string $word <p>
 * The tested word.
 * </p>
 * @return bool <b>TRUE</b> if the spelling is correct, <b>FALSE</b> if not.
 */
function pspell_check(#[LanguageLevelTypeAware(['8.1' => 'PSpell\Dictionary'], default: 'int')] $dictionary, string $word): bool {}

/**
 * Suggest spellings of a word
 * @link https://php.net/manual/en/function.pspell-suggest.php
 * @param int $dictionary
 * @param string $word <p>
 * The tested word.
 * </p>
 * @return array|false an array of possible spellings.
 */
function pspell_suggest(#[LanguageLevelTypeAware(['8.1' => 'PSpell\Dictionary'], default: 'int')] $dictionary, string $word): array|false {}

/**
 * Store a replacement pair for a word
 * @link https://php.net/manual/en/function.pspell-store-replacement.php
 * @param int $dictionary <p>
 * A dictionary link identifier, opened with
 * <b>pspell_new_personal</b>
 * </p>
 * @param string $misspelled <p>
 * The misspelled word.
 * </p>
 * @param string $correct <p>
 * The fixed spelling for the <i>misspelled</i> word.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pspell_store_replacement(#[LanguageLevelTypeAware(['8.1' => 'PSpell\Dictionary'], default: 'int')] $dictionary, string $misspelled, string $correct): bool {}

/**
 * Add the word to a personal wordlist
 * @link https://php.net/manual/en/function.pspell-add-to-personal.php
 * @param int $dictionary
 * @param string $word <p>
 * The added word.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pspell_add_to_personal(#[LanguageLevelTypeAware(['8.1' => 'PSpell\Dictionary'], default: 'int')] $dictionary, string $word): bool {}

/**
 * Add the word to the wordlist in the current session
 * @link https://php.net/manual/en/function.pspell-add-to-session.php
 * @param int $dictionary
 * @param string $word <p>
 * The added word.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pspell_add_to_session(#[LanguageLevelTypeAware(['8.1' => 'PSpell\Dictionary'], default: 'int')] $dictionary, string $word): bool {}

/**
 * Clear the current session
 * @link https://php.net/manual/en/function.pspell-clear-session.php
 * @param int $dictionary
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pspell_clear_session(#[LanguageLevelTypeAware(['8.1' => 'PSpell\Dictionary'], default: 'int')] $dictionary): bool {}

/**
 * Save the personal wordlist to a file
 * @link https://php.net/manual/en/function.pspell-save-wordlist.php
 * @param int $dictionary <p>
 * A dictionary link identifier opened with
 * <b>pspell_new_personal</b>.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pspell_save_wordlist(#[LanguageLevelTypeAware(['8.1' => 'PSpell\Dictionary'], default: 'int')] $dictionary): bool {}

/**
 * Create a config used to open a dictionary
 * @link https://php.net/manual/en/function.pspell-config-create.php
 * @param string $language <p>
 * The language parameter is the language code which consists of the
 * two letter ISO 639 language code and an optional two letter ISO
 * 3166 country code after a dash or underscore.
 * </p>
 * @param string $spelling <p>
 * The spelling parameter is the requested spelling for languages
 * with more than one spelling such as English. Known values are
 * 'american', 'british', and 'canadian'.
 * </p>
 * @param string $jargon <p>
 * The jargon parameter contains extra information to distinguish
 * two different words lists that have the same language and
 * spelling parameters.
 * </p>
 * @param string $encoding <p>
 * The encoding parameter is the encoding that words are expected to
 * be in. Valid values are 'utf-8', 'iso8859-*', 'koi8-r',
 * 'viscii', 'cp1252', 'machine unsigned 16', 'machine unsigned
 * 32'. This parameter is largely untested, so be careful when
 * using.
 * </p>
 * @return int Retuns a pspell config identifier.
 */
#[LanguageLevelTypeAware(['8.1' => 'PSpell\Config'], default: 'int')]
function pspell_config_create(string $language, string $spelling = "", string $jargon = "", string $encoding = "") {}

/**
 * Consider run-together words as valid compounds
 * @link https://php.net/manual/en/function.pspell-config-runtogether.php
 * @param int $config
 * @param bool $allow <p>
 * <b>TRUE</b> if run-together words should be treated as legal compounds,
 * <b>FALSE</b> otherwise.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pspell_config_runtogether(#[LanguageLevelTypeAware(['8.1' => 'PSpell\Config'], default: 'int')] $config, bool $allow): bool {}

/**
 * Change the mode number of suggestions returned
 * @link https://php.net/manual/en/function.pspell-config-mode.php
 * @param int $config
 * @param int $mode <p>
 * The mode parameter is the mode in which spellchecker will work.
 * There are several modes available:
 * <b>PSPELL_FAST</b> - Fast mode (least number of
 * suggestions)</p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pspell_config_mode(#[LanguageLevelTypeAware(['8.1' => 'PSpell\Config'], default: 'int')] $config, int $mode): bool {}

/**
 * Ignore words less than N characters long
 * @link https://php.net/manual/en/function.pspell-config-ignore.php
 * @param int $config
 * @param int $min_length <p>
 * Words less than <i>n</i> characters will be skipped.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pspell_config_ignore(#[LanguageLevelTypeAware(['8.1' => 'PSpell\Config'], default: 'int')] $config, int $min_length): bool {}

/**
 * Set a file that contains personal wordlist
 * @link https://php.net/manual/en/function.pspell-config-personal.php
 * @param int $config
 * @param string $filename <p>
 * The personal wordlist. If the file does not exist, it will be created.
 * The file should be writable by whoever PHP runs as (e.g. nobody).
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pspell_config_personal(#[LanguageLevelTypeAware(['8.1' => 'PSpell\Config'], default: 'int')] $config, string $filename): bool {}

/**
 * Location of the main word list
 * @link https://php.net/manual/en/function.pspell-config-dict-dir.php
 * @param int $config
 * @param string $directory
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pspell_config_dict_dir(#[LanguageLevelTypeAware(['8.1' => 'PSpell\Config'], default: 'int')] $config, string $directory): bool {}

/**
 * location of language data files
 * @link https://php.net/manual/en/function.pspell-config-data-dir.php
 * @param int $config
 * @param string $directory
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pspell_config_data_dir(#[LanguageLevelTypeAware(['8.1' => 'PSpell\Config'], default: 'int')] $config, string $directory): bool {}

/**
 * Set a file that contains replacement pairs
 * @link https://php.net/manual/en/function.pspell-config-repl.php
 * @param int $config
 * @param string $filename <p>
 * The file should be writable by whoever PHP runs as (e.g. nobody).
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pspell_config_repl(#[LanguageLevelTypeAware(['8.1' => 'PSpell\Config'], default: 'int')] $config, string $filename): bool {}

/**
 * Determine whether to save a replacement pairs list
 * along with the wordlist
 * @link https://php.net/manual/en/function.pspell-config-save-repl.php
 * @param int $config
 * @param bool $save <p>
 * <b>TRUE</b> if replacement pairs should be saved, <b>FALSE</b> otherwise.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pspell_config_save_repl(#[LanguageLevelTypeAware(['8.1' => 'PSpell\Config'], default: 'int')] $config, bool $save): bool {}

define('PSPELL_FAST', 1);
define('PSPELL_NORMAL', 2);
define('PSPELL_BAD_SPELLERS', 3);
define('PSPELL_RUN_TOGETHER', 8);

// End of pspell v.
