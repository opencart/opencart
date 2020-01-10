<?php
/**
 * SCSSPHP
 *
 * @copyright 2012-2018 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://leafo.github.io/scssphp
 */

namespace Leafo\ScssPhp\Formatter;

use Leafo\ScssPhp\Formatter;
use Leafo\ScssPhp\Formatter\OutputBlock;

/**
 * Crunched formatter
 *
 * @author Anthon Pang <anthon.pang@gmail.com>
 */
class Crunched extends Formatter
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->indentLevel = 0;
        $this->indentChar = '  ';
        $this->break = '';
        $this->open = '{';
        $this->close = '}';
        $this->tagSeparator = ',';
        $this->assignSeparator = ':';
        $this->keepSemicolons = false;
    }

    /**
     * {@inheritdoc}
     */
    public function blockLines(OutputBlock $block)
    {
        $inner = $this->indentStr();

        $glue = $this->break . $inner;

        foreach ($block->lines as $index => $line) {
            if (substr($line, 0, 2) === '/*') {
                unset($block->lines[$index]);
            }
        }

        $this->write($inner . implode($glue, $block->lines));

        if (! empty($block->children)) {
            $this->write($this->break);
        }
    }

    /**
     * Output block selectors
     *
     * @param \Leafo\ScssPhp\Formatter\OutputBlock $block
     */
    protected function blockSelectors(OutputBlock $block)
    {
        $inner = $this->indentStr();

        $this->write(
            $inner
            . implode(
                $this->tagSeparator,
                str_replace([' > ', ' + ', ' ~ '], ['>', '+', '~'], $block->selectors)
            )
            . $this->open . $this->break
        );
    }
}
