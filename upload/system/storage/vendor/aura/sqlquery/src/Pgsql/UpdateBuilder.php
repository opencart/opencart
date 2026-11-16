<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\SqlQuery\Pgsql;

use Aura\SqlQuery\Common;

/**
 *
 * UPDATE builder for Postgres.
 *
 * @package Aura.SqlQuery
 *
 */
class UpdateBuilder extends Common\UpdateBuilder
{
    use BuildReturningTrait;
}
