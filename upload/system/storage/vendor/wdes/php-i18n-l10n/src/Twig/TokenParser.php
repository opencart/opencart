<?php

declare(strict_types = 1);

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */
namespace Wdes\phpI18nL10n\Twig;

use PhpMyAdmin\Twig\Extensions\TokenParser\TransTokenParser;
use Twig\Token;

/**
 * Token parser for Twig
 * @license MPL-2.0
 */
class TokenParser extends TransTokenParser
{

    /**
     * Parses a token and returns a node.
     *
     * @param Token $token The token
     *
     * @return TranslationNode
     */
    public function parse(Token $token)
    {
        [
            $body,
            $plural,
            $count,
            $context,
            $notes,
            $domain,
            $lineno,
            $tag,
        ] = $this->preParse($token);

        return new TranslationNode($body, $plural, $count, $context, $notes, $domain, $lineno, $tag);
    }

}
