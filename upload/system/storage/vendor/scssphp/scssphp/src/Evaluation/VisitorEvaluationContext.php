<?php

/**
 * SCSSPHP
 *
 * @copyright 2012-2020 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://scssphp.github.io/scssphp
 */

namespace ScssPhp\ScssPhp\Evaluation;

use ScssPhp\ScssPhp\Ast\AstNode;
use ScssPhp\ScssPhp\Deprecation;
use SourceSpan\FileSpan;

/**
 * @internal
 */
final class VisitorEvaluationContext extends EvaluationContext
{
    private readonly EvaluateVisitor $visitor;

    private readonly AstNode $defaultWarnNodeWithSpan;

    public function __construct(EvaluateVisitor $visitor, AstNode $defaultWarnNodeWithSpan)
    {
        $this->visitor = $visitor;
        $this->defaultWarnNodeWithSpan = $defaultWarnNodeWithSpan;
    }

    public function getCurrentCallableSpan(): FileSpan
    {
        $callableNode = $this->visitor->getCallableNode();

        if ($callableNode !== null) {
            return $callableNode->getSpan();
        }

        throw new \LogicException('No Sass callable is currently being evaluated.');
    }

    public function warn(string $message, ?Deprecation $deprecation = null): void
    {
        $span = $this->visitor->getImportSpan() ?? $this->maybeCurrentCallableSpan() ?? $this->defaultWarnNodeWithSpan->getSpan();

        $this->visitor->warn($message, $span, $deprecation);
    }

    private function maybeCurrentCallableSpan(): ?FileSpan
    {
        $callableNode = $this->visitor->getCallableNode();

        if ($callableNode !== null) {
            return $callableNode->getSpan();
        }

        return null;
    }
}
