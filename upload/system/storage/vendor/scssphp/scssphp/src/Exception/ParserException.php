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

namespace ScssPhp\ScssPhp\Exception;

/**
 * Parser Exception
 *
 * @author Oleksandr Savchenko <traveltino@gmail.com>
 *
 * @internal
 */
class ParserException extends \Exception implements SassException
{
    /**
     * @var array|null
     * @phpstan-var array{string, int, int}|null
     */
    private $sourcePosition;

    /**
     * Get source position
     *
     * @api
     *
     * @return array|null
     * @phpstan-return array{string, int, int}|null
     */
    public function getSourcePosition()
    {
        return $this->sourcePosition;
    }

    /**
     * Set source position
     *
     * @api
     *
     * @param array $sourcePosition
     *
     * @return void
     *
     * @phpstan-param array{string, int, int} $sourcePosition
     */
    public function setSourcePosition($sourcePosition)
    {
        $this->sourcePosition = $sourcePosition;
    }
}
