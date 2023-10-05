<?php

declare(strict_types = 1);

/*
 * This file is part of the Doctum utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Doctum\Parser;

use PhpParser\Error;
use PhpParser\NodeTraverser;
use PhpParser\Parser as PhpParser;

class CodeParser
{
    /** @var PhpParser */
    protected $parser;
    /** @var NodeTraverser */
    protected $traverser;
    /** @var ParserContext */
    protected $context;

    public function __construct(ParserContext $context, PhpParser $parser, NodeTraverser $traverser)
    {
        $this->context   = $context;
        $this->parser    = $parser;
        $this->traverser = $traverser;

        // with big fluent interfaces it can happen that PHP-Parser's Traverser
        // exceeds the 100 recursions limit; we set it to 10000 to be sure.
        ini_set('xdebug.max_nesting_level', '10000');
    }

    public function getContext(): ParserContext
    {
        return $this->context;
    }

    public function parse(string $code): void
    {
        try {
            $this->traverser->traverse($this->parser->parse($code) ?? []);
        } catch (Error $e) {
            $this->context->addError($this->context->getFile(), 0, $e->getMessage());
        }
    }

}
