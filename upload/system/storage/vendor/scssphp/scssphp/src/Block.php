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

namespace ScssPhp\ScssPhp;

/**
 * Block
 *
 * @author Anthon Pang <anthon.pang@gmail.com>
 *
 * @internal
 */
class Block
{
    /**
     * @var string|null
     */
    public $type;

    /**
     * @var Block|null
     */
    public $parent;

    /**
     * @var string
     */
    public $sourceName;

    /**
     * @var int
     */
    public $sourceIndex;

    /**
     * @var int
     */
    public $sourceLine;

    /**
     * @var int
     */
    public $sourceColumn;

    /**
     * @var array|null
     */
    public $selectors;

    /**
     * @var array
     */
    public $comments;

    /**
     * @var array
     */
    public $children;

    /**
     * @var Block|null
     */
    public $selfParent;
}
