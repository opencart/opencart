<?php

declare(strict_types = 1);

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
namespace Wdes\phpI18nL10n\Twig;

use PhpMyAdmin\Twig\Extensions\Node\TransNode;

/**
 * Translation node for Twig
 * @license MPL-2.0
 */
class TranslationNode extends TransNode
{
    /**
     * The label for gettext notes to be exported
     *
     * @var string
     */
    public static $notesLabel = '// l10n: ';

    /**
     * Enables context functions usage
     *
     * @var bool
     */
    public static $hasContextFunctions = true;

    /**
     * @param bool $hasPlural  use plural
     * @param bool $hasContext use context
     * @param bool $hasDomain  use domain
     *
     * @return string The function
     */
    protected function getTransFunction(bool $hasPlural, bool $hasContext, bool $hasDomain): string
    {
        $functionPrefix = '\Wdes\phpI18nL10n\Launcher::getPlugin()->';

        if ($hasDomain) {
            if ($hasPlural) {
                // dnpgettext($domain, $msgctxt, $msgid, $msgidPlural, $number);
                // dngettext($domain, $msgid, $msgidPlural, $number);
                return $functionPrefix . ($hasContext ? 'dnpgettext' : 'dngettext');
            }

            // dpgettext($domain, $msgctxt, $msgid);
            // dgettext($domain, $msgid);
            return $functionPrefix . ($hasContext ? 'dpgettext' : 'dgettext');
        }

        if ($hasPlural) {
            // npgettext($msgctxt, $msgid, $msgidPlural, $number);
            // ngettext($msgid, $msgidPlural, $number);
            return $functionPrefix . ($hasContext ? 'npgettext' : 'ngettext');
        }

        // pgettext($msgctxt, $msgid);
        // gettext($msgid);
        return $functionPrefix . ($hasContext ? 'pgettext' : 'gettext');
    }

}
