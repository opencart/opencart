<?php

declare(strict_types = 1);

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */
namespace Wdes\phpI18nL10n;

use Wdes\phpI18nL10n\plugins\BasePlugin;

/**
 * Plugin for reading .mo files
 * @see https://www.gnu.org/software/gettext/manual/html_node/MO-Files.html
 * @author William Desportes <williamdes@wdes.fr>
 * @license MPL-2.0
 */
class Launcher
{
    /**
     * Plugin to use for translation
     *
     * @var BasePlugin
     */
    private static $plugin;

    /**
     * Return registered plugin
     *
     * @return BasePlugin
     */
    public static function getPlugin(): BasePlugin
    {
        return Launcher::$plugin;
    }

    /**
     * Set the translation plugin
     * @param BasePlugin $plugin The translation plugin to provide
     * @return void
     */
    public static function setPlugin(BasePlugin $plugin): void
    {
        Launcher::$plugin = $plugin;
    }

    /**
     * Access gettext directly, not recommended
     * Reserved to twig filter
     *
     * @param string $msgId Message id
     * @return string
     */
    public static function gettext(string $msgId): string
    {
        return Launcher::$plugin->gettext($msgId);
    }

    /**
     * Access dgettext directly, not recommended
     * Reserved to twig filter
     *
     * @param string $domain The domain
     * @param string $msgId  Message id
     * @return string
     */
    public static function dgettext(string $domain, string $msgId): string
    {
        return Launcher::$plugin->dgettext($domain, $msgId);
    }

}
