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

namespace ScssPhp\ScssPhp\Extend;

use ScssPhp\ScssPhp\Exception\SimpleSassException;
use ScssPhp\ScssPhp\Util\EquatableUtil;

/**
 * An {@see Extension} created by merging two {@see Extension}s with the same extender
 * and target.
 *
 * This is used when multiple mandatory extensions exist to ensure that both of
 * them are marked as resolved.
 *
 * @internal
 */
final class MergedExtension extends Extension
{
    public readonly Extension $left;
    public readonly Extension $right;

    private function __construct(Extension $left, Extension $right)
    {
        $this->left = $left;
        $this->right = $right;

        parent::__construct($left->extender->selector, $left->target, $left->span, $left->mediaContext ?? $right->mediaContext, true);
    }

    public static function merge(Extension $left, Extension $right): Extension
    {
        if (!EquatableUtil::equals($left->extender->selector, $right->extender->selector) || !EquatableUtil::equals($left->target, $right->target)) {
            throw new \InvalidArgumentException('$left and $right aren\'t the same extension.');
        }

        if ($left->mediaContext !== null && $right->mediaContext !== null && !EquatableUtil::listEquals($left->mediaContext, $right->mediaContext)) {
            $location = $left->span->message('');

            throw new SimpleSassException("From $location\nYou may not @extend the same selector from within different media queries.", $right->span);
        }

        // If one extension is optional and doesn't add a special media context, it
        // doesn't need to be merged.
        if ($right->isOptional && $right->mediaContext === null) {
            return $left;
        }
        if ($left->isOptional && $left->mediaContext === null) {
            return $right;
        }

        return new MergedExtension($left, $right);
    }

    /**
     * Returns all leaf-node [Extension]s in the tree of [MergedExtension]s.
     *
     * @return \Traversable<Extension>
     */
    public function unmerge(): \Traversable
    {
        if ($this->left instanceof MergedExtension) {
            yield from $this->left->unmerge();
        } else {
            yield $this->left;
        }

        if ($this->right instanceof MergedExtension) {
            yield from $this->right->unmerge();
        } else {
            yield $this->right;
        }
    }
}
