<?php

declare(strict_types = 1);

namespace Doctum\Parser;

use Doctum\Reflection\FunctionReflection;

/**
 * @author William Desportes <williamdes@wdes.fr>
 */
interface FunctionVisitorInterface
{

    public function visit(FunctionReflection $class): bool;

}
