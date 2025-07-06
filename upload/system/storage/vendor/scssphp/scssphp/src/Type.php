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
 * Block/node types
 *
 * @author Anthon Pang <anthon.pang@gmail.com>
 */
final class Type
{
    const T_COLOR = 'color';
    /**
     * @internal
     */
    const T_KEYWORD = 'keyword';
    const T_LIST = 'list';
    const T_MAP = 'map';
    const T_NULL = 'null';
    const T_NUMBER = 'number';
    const T_STRING = 'string';
}
