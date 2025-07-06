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

namespace ScssPhp\ScssPhp\SassCallable;

use ScssPhp\ScssPhp\Value\SassNumber;
use ScssPhp\ScssPhp\Value\Value;

/**
 * An interface for functions and mixins that can be invoked from Sass by
 * passing in arguments.
 *
 * When writing custom functions, it's important to make them as user-friendly
 * and as close to the standards set by Sass's core functions as possible. Some
 * good guidelines to follow include:
 *
 * * Use `Value.assert*` methods, like {@see Value::assertString}, to cast untyped
 *   {@see Value} objects to more specific types. For values from the argument list,
 *   pass in the argument name as well. This ensures that the user gets good
 *   error messages when they pass in the wrong type to your function.
 *
 * * Individual classes may have more specific `assert*` methods, like
 *   {@see SassNumber::assertInt}, which should be used when possible.
 *
 * * In Sass, every value counts as a list. Functions should avoid casting
 *   values to the `SassList` type, and should use the {@see Value::asList} method
 *   instead.
 *
 * * When manipulating values like lists, strings, and numbers that have
 *   metadata (comma versus space separated, bracketed versus unbracketed,
 *   quoted versus unquoted, units), the output metadata should match the input
 *   metadata. For lists, the {@see Value::withListContents} method can be used to do
 *   this automatically.
 *
 * * When in doubt, lists should default to comma-separated, strings should
 *   default to quoted, and number should default to unitless.
 *
 * * In Sass, lists and strings use one-based indexing and use negative indices
 *   to index from the end of value. Functions should follow these conventions.
 *   The {@see Value::sassIndexToListIndex} and {@see SassString::sassIndexToStringIndex}
 *   methods can be used to do this automatically.
 *
 * * String indexes in Sass refer to Unicode code points while PHP string
 *   indices refer to bytes. For example, the character U+1F60A,
 *   Smiling Face With Smiling Eyes, is a single Unicode code point but is
 *   represented in UTF-8 as several bytes (`0xF0`, `0x9F`, `0x98` and `0x8A`). So in
 *   PHP, `substr("a😊b", 1, 1)` returns `"\xF0"`, whereas in Sass
 *   `str-slice("a😊b", 1, 1)` returns `"😊"`. Functions should follow this
 *   convention. The {@see SassString::sassIndexToStringIndex} and
 *   {@see SassString::sassIndexToCodePointIndex} methods can be used to do this
 *   automatically, and the {@see SassString::getSassLength} getter can be used to
 *   access a string's length in code points.
 *
 * @internal
 */
interface SassCallable
{
    /**
     * The callable's name
     */
    public function getName(): string;
}
