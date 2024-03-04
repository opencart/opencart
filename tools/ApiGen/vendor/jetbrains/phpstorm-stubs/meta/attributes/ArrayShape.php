<?php

namespace JetBrains\PhpStorm;

use Attribute;

/**
 * The attribute specifies possible array keys and their types.
 *
 * If applied, an IDE will suggest the specified array keys, infer the specified types, and highlight non-specified keys in array access expressions.
 *
 * Array shapes should be specified with the required $shape parameter whose values should be array literals.<br />
 *
 * Example: <br />
 * <b>#[ArrayShape(["f" => "int", "string", "x" => "float"])]</b>
 * This usage applied on an element effectively means that the array has 3 elements, the keys are "f", 1, and "x", and the corresponding types are "int", "string", and "float".
 */
#[Attribute(Attribute::TARGET_FUNCTION|Attribute::TARGET_METHOD|Attribute::TARGET_PARAMETER|Attribute::TARGET_PROPERTY)]
class ArrayShape
{
    public function __construct(array $shape) {}
}
