<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\SqlQuery\Common;

/**
 *
 * Common DELETE builder.
 *
 * @package Aura.SqlQuery
 *
 */
class DeleteBuilder extends AbstractBuilder
{
    /**
     *
     * Builds the FROM clause.
     *
     * @param string $from The FROM element.
     *
     * @return string
     *
     */
    public function buildFrom($from)
    {
        return " FROM {$from}";
    }
}
