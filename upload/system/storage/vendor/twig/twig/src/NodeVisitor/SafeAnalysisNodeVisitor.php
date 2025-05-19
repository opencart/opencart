<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Twig\NodeVisitor;

use Twig\Environment;
use Twig\Node\Expression\BlockReferenceExpression;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Expression\FilterExpression;
use Twig\Node\Expression\FunctionExpression;
use Twig\Node\Expression\GetAttrExpression;
use Twig\Node\Expression\MacroReferenceExpression;
use Twig\Node\Expression\MethodCallExpression;
use Twig\Node\Expression\NameExpression;
use Twig\Node\Expression\OperatorEscapeInterface;
use Twig\Node\Expression\ParentExpression;
use Twig\Node\Node;

/**
 * @internal
 */
final class SafeAnalysisNodeVisitor implements NodeVisitorInterface
{
    private $data = [];
    private $safeVars = [];

    public function setSafeVars(array $safeVars): void
    {
        $this->safeVars = $safeVars;
    }

    /**
     * @return array
     */
    public function getSafe(Node $node)
    {
        $hash = spl_object_hash($node);
        if (!isset($this->data[$hash])) {
            return [];
        }

        foreach ($this->data[$hash] as $bucket) {
            if ($bucket['key'] !== $node) {
                continue;
            }

            if (\in_array('html_attr', $bucket['value'])) {
                $bucket['value'][] = 'html';
            }

            return $bucket['value'];
        }

        return [];
    }

    private function setSafe(Node $node, array $safe): void
    {
        $hash = spl_object_hash($node);
        if (isset($this->data[$hash])) {
            foreach ($this->data[$hash] as &$bucket) {
                if ($bucket['key'] === $node) {
                    $bucket['value'] = $safe;

                    return;
                }
            }
        }
        $this->data[$hash][] = [
            'key' => $node,
            'value' => $safe,
        ];
    }

    public function enterNode(Node $node, Environment $env): Node
    {
        return $node;
    }

    public function leaveNode(Node $node, Environment $env): ?Node
    {
        if ($node instanceof ConstantExpression) {
            // constants are marked safe for all
            $this->setSafe($node, ['all']);
        } elseif ($node instanceof BlockReferenceExpression) {
            // blocks are safe by definition
            $this->setSafe($node, ['all']);
        } elseif ($node instanceof ParentExpression) {
            // parent block is safe by definition
            $this->setSafe($node, ['all']);
        } elseif ($node instanceof OperatorEscapeInterface) {
            // intersect safeness of operands
            $operands = $node->getOperandNamesToEscape();
            if (2 < \count($operands)) {
                throw new \LogicException(\sprintf('Operators with more than 2 operands are not supported yet, got %d.', \count($operands)));
            } elseif (2 === \count($operands)) {
                $safe = $this->intersectSafe($this->getSafe($node->getNode($operands[0])), $this->getSafe($node->getNode($operands[1])));
                $this->setSafe($node, $safe);
            }
        } elseif ($node instanceof FilterExpression) {
            // filter expression is safe when the filter is safe
            if ($node->hasAttribute('twig_callable')) {
                $filter = $node->getAttribute('twig_callable');
            } else {
                // legacy
                $filter = $env->getFilter($node->getAttribute('name'));
            }

            if ($filter) {
                $safe = $filter->getSafe($node->getNode('arguments'));
                if (null === $safe) {
                    trigger_deprecation('twig/twig', '3.16', 'The "%s::getSafe()" method should not return "null" anymore, return "[]" instead.', $filter::class);
                    $safe = [];
                }

                if (!$safe) {
                    $safe = $this->intersectSafe($this->getSafe($node->getNode('node')), $filter->getPreservesSafety());
                }
                $this->setSafe($node, $safe);
            }
        } elseif ($node instanceof FunctionExpression) {
            // function expression is safe when the function is safe
            if ($node->hasAttribute('twig_callable')) {
                $function = $node->getAttribute('twig_callable');
            } else {
                // legacy
                $function = $env->getFunction($node->getAttribute('name'));
            }

            if ($function) {
                $safe = $function->getSafe($node->getNode('arguments'));
                if (null === $safe) {
                    trigger_deprecation('twig/twig', '3.16', 'The "%s::getSafe()" method should not return "null" anymore, return "[]" instead.', $function::class);
                    $safe = [];
                }
                $this->setSafe($node, $safe);
            }
        } elseif ($node instanceof MethodCallExpression || $node instanceof MacroReferenceExpression) {
            // all macro calls are safe
            $this->setSafe($node, ['all']);
        } elseif ($node instanceof GetAttrExpression && $node->getNode('node') instanceof NameExpression) {
            $name = $node->getNode('node')->getAttribute('name');
            if (\in_array($name, $this->safeVars)) {
                $this->setSafe($node, ['all']);
            }
        }

        return $node;
    }

    private function intersectSafe(array $a, array $b): array
    {
        if (!$a || !$b) {
            return [];
        }

        if (\in_array('all', $a)) {
            return $b;
        }

        if (\in_array('all', $b)) {
            return $a;
        }

        return array_intersect($a, $b);
    }

    public function getPriority(): int
    {
        return 0;
    }
}
