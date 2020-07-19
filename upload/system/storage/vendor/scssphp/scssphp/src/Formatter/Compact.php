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

namespace ScssPhp\ScssPhp\Formatter;

use ScssPhp\ScssPhp\Formatter;

/**
 * Compact formatter
 *
 * @author Leaf Corcoran <leafot@gmail.com>
 */
class Compact extends Formatter
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->indentLevel = 0;
        $this->indentChar = '';
        $this->break = '';
        $this->open = ' {';
        $this->close = "}\n\n";
        $this->tagSeparator = ',';
        $this->assignSeparator = ':';
        $this->keepSemicolons = true;
    }

    /**
     * {@inheritdoc}
     */
    public function indentStr()
    {
        return ' ';
    }
}
