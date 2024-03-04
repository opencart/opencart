<?php

namespace JetBrains\PhpStorm;

use Attribute;

/**
 * The attribute specifies the expected values of an entity: return values for functions and arguments' values for methods.
 *
 * If the attribute is applied, PhpStorm assumes that only the arguments specified in the attribute constructor can
 * be passed/returned. This will affect the following:
 * <ul>
 * <li><i>Code completion</i> - expected arguments are displayed on the top of the suggestions list when used in comparison expressions</li>
 * <li><i>Inspections [when used in a comparison with a value/assignment to/return from method]</i> - the element absent from the expected values list produces the inspection warning</li>
 * <li><i>Code generation</i> - for example, when generating the 'switch' statement, all possible expected values are inserted automatically</li>
 * </ul>
 *
 * Expected values can be any of the following:
 * <ul>
 * <li>numbers</li>
 * <li>string literals</li>
 * <li>constant references</li>
 * <li>class constant references</li>
 * </ul>
 *
 * Expected arguments can be specified in any of the following ways:
 * <ul>
 * <li><b>#[ExpectedValues(values: [1,2,3])]</b> means that one of the following is expected: `1`, `2`, or `3`</li>
 * <li><b>#[ExpectedValues(values: MY_CONST]</b> - default value of MY_CONST is expected to be array creation expression, in this case value of MY_CONST will be inlined</li>
 * <li><b>#[ExpectedValues(flags: [1,2,3])]</b> means that a bitmask of the following is expected: `1`, `2`, or `3`</li>
 * <li><b>#[ExpectedValues(valuesFromClass: MyClass::class)]</b> means that one of the constants from the class `MyClass` is expected</li>
 * <li><b>#[ExpectedValues(flagsFromClass: ExpectedValues::class)]</b> means that a bitmask of the constants from the class `MyClass` is expected</li>
 * </ul>
 *
 * The attribute with the number of provided constructor arguments different from 1 will result in undefined behavior.
 * @since 8.0
 */
#[Attribute(Attribute::TARGET_FUNCTION|Attribute::TARGET_METHOD|Attribute::TARGET_PARAMETER|Attribute::TARGET_PROPERTY)]
class ExpectedValues
{
    public function __construct(array $values = [], array $flags = [], string $valuesFromClass = null, string $flagsFromClass = null) {}
}
