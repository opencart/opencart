<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Twig\Node\Expression\Variable;

use Twig\Compiler;
use Twig\Node\Expression\AbstractExpression;

final class AssignTemplateVariable extends AbstractExpression
{
    public function __construct(TemplateVariable $var, bool $global = true)
    {
        parent::__construct(['var' => $var], ['global' => $global], $var->getTemplateLine());
    }

    public function compile(Compiler $compiler): void
    {
        /** @var TemplateVariable $var */
        $var = $this->nodes['var'];

        $compiler
            ->addDebugInfo($this)
            ->write('$macros[')
            ->string($var->getName($compiler))
            ->raw('] = ')
        ;

        if ($this->getAttribute('global')) {
            $compiler
                ->raw('$this->macros[')
                ->string($var->getName($compiler))
                ->raw('] = ')
            ;
        }
    }
}
