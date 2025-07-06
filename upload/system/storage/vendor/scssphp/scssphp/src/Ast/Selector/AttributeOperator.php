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

namespace ScssPhp\ScssPhp\Ast\Selector;

/**
 * An operator that defines the semantics of an {@see AttributeSelector}.
 *
 * @internal
 */
enum AttributeOperator
{
    /**
     * The attribute value exactly equals the given value.
     */
    case EQUAL;

    /**
     * The attribute value is a whitespace-separated list of words, one of which
     * is the given value.
     */
    case INCLUDE;

    /**
     * The attribute value is either exactly the given value, or starts with the
     * given value followed by a dash.
     */
    case DASH;

    /**
     * The attribute value begins with the given value.
     */
    case PREFIX;

    /**
     * The attribute value ends with the given value.
     */
    case SUFFIX;

    /**
     * The attribute value contains the given value.
     */
    case SUBSTRING;

    public function getText(): string
    {
        return match ($this) {
            self::EQUAL => '=',
            self::INCLUDE => '~=',
            self::DASH => '|=',
            self::PREFIX => '^=',
            self::SUFFIX => '$=',
            self::SUBSTRING => '*=',
        };
    }
}
