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

namespace ScssPhp\ScssPhp\Block;

use ScssPhp\ScssPhp\Block;
use ScssPhp\ScssPhp\Node\Number;
use ScssPhp\ScssPhp\Type;

/**
 * @internal
 */
class ForBlock extends Block
{
    /**
     * @var string
     */
    public $var;

    /**
     * @var array|Number
     */
    public $start;

    /**
     * @var array|Number
     */
    public $end;

    /**
     * @var bool
     */
    public $until;

    public function __construct()
    {
        $this->type = Type::T_FOR;
    }
}
