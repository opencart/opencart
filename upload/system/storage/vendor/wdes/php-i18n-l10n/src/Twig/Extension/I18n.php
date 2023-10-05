<?php

declare(strict_types = 1);

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */
namespace Wdes\phpI18nL10n\Twig\Extension;

use PhpMyAdmin\Twig\Extensions\I18nExtension;
use Twig\TwigFilter;
use Wdes\phpI18nL10n\Twig\TokenParser;

/**
 * I18n extension for Twig
 * @license MPL-2.0
 */
class I18n extends I18nExtension
{

    /**
     * @return array
     */
    public function getTokenParsers()
    {
        return [new TokenParser()];
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new TwigFilter('trans', '\Wdes\phpI18nL10n\Launcher::gettext'), /* Note, the filter does not handle plurals */
        ];
    }

}
