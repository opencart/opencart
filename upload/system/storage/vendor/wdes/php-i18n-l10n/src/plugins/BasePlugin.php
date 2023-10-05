<?php

declare(strict_types = 1);

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */
namespace Wdes\phpI18nL10n\plugins;

/**
 * Plugin interface
 * @author William Desportes <williamdes@wdes.fr>
 * @license MPL-2.0
 */
abstract class BasePlugin
{

    /**
     * Build the plugin instance
     *
     * @param array $options The options for the plugin
     */
    abstract public function __construct(array $options = []);

    /**
     * Get translation for a message id
     *
     * @param string $msgId Message id
     * @return string
     */
    abstract public function gettext(string $msgId): string;

    /**
     * Get translation for a message id
     *
     * @param string $msgId Message id
     * @return string
     */
    abstract public function __(string $msgId): string;

    /**
     * Get translation
     *
     * @param string $domain      Domain
     * @param string $msgctxt     msgctxt
     * @param string $msgId       Message id
     * @param string $msgIdPlural Plural message id
     * @param int    $number      Number
     * @return string
     */
    abstract public function dnpgettext(string $domain, string $msgctxt, string $msgId, string $msgIdPlural, int $number): string;

    /**
     * Get translation
     *
     * @param string $domain      Domain
     * @param string $msgId       Message id
     * @param string $msgIdPlural Plural message id
     * @param int    $number      Number
     * @return string
     */
    abstract public function dngettext(string $domain, string $msgId, string $msgIdPlural, int $number): string;

    /**
     * Get translation
     *
     * @param string $msgctxt     msgctxt
     * @param string $msgId       Message id
     * @param string $msgIdPlural Plural message id
     * @param int    $number      Number
     * @return string
     */
    abstract public function npgettext(string $msgctxt, string $msgId, string $msgIdPlural, int $number): string;

    /**
     * Plural version of gettext
     *
     * @param string $msgId       Message id
     * @param string $msgIdPlural Plural message id
     * @param int    $number      Number
     * @return string
     */
    abstract public function ngettext(string $msgId, string $msgIdPlural, int $number);

    /**
     * Get translation
     *
     * @param string $domain  Domain
     * @param string $msgctxt msgctxt
     * @param string $msgId   Message id
     * @return string
     */
    abstract public function dpgettext(string $domain, string $msgctxt, string $msgId): string;

    /**
     * Get translation
     *
     * @param string $domain Domain
     * @param string $msgId  Message id
     * @return string
     */
    abstract public function dgettext(string $domain, string $msgId): string;

    /**
     * Get translation
     *
     * @param string $msgctxt msgctxt
     * @param string $msgId   Message id
     * @return string
     */
    abstract public function pgettext(string $msgctxt, string $msgId): string;

}
