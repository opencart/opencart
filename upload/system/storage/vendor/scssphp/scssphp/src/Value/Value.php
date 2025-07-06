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

namespace ScssPhp\ScssPhp\Value;

use JiriPudil\SealedClasses\Sealed;
use ScssPhp\ScssPhp\Ast\Selector\ComplexSelector;
use ScssPhp\ScssPhp\Ast\Selector\CompoundSelector;
use ScssPhp\ScssPhp\Ast\Selector\SelectorList;
use ScssPhp\ScssPhp\Ast\Selector\SimpleSelector;
use ScssPhp\ScssPhp\Deprecation;
use ScssPhp\ScssPhp\Exception\SassFormatException;
use ScssPhp\ScssPhp\Exception\SassScriptException;
use ScssPhp\ScssPhp\Serializer\Serializer;
use ScssPhp\ScssPhp\Util\Equatable;
use ScssPhp\ScssPhp\Visitor\ValueVisitor;
use ScssPhp\ScssPhp\Warn;

/**
 * A SassScript value.
 *
 * All SassScript values are unmodifiable. New values can be constructed using
 * subclass constructors like `new SassString`. Untyped values can be cast to
 * particular types using `assert*()` functions like {@see assertString}, which
 * throw user-friendly error messages if they fail.
 */
#[Sealed(permits: [SassBoolean::class, SassCalculation::class, SassColor::class, SassFunction::class, SassList::class, SassMap::class, SassMixin::class, SassNull::class, SassNumber::class, SassString::class])]
abstract class Value implements Equatable, \Stringable
{
    /**
     * Whether the value counts as `true` in an `@if` statement and other contexts
     */
    public function isTruthy(): bool
    {
        return true;
    }

    /**
     * The separator for this value as a list.
     *
     * All SassScript values can be used as lists. Maps count as lists of pairs,
     * and all other values count as single-value lists.
     */
    public function getSeparator(): ListSeparator
    {
        return ListSeparator::UNDECIDED;
    }

    /**
     * Whether this value as a list has brackets.
     *
     * All SassScript values can be used as lists. Maps count as lists of pairs,
     * and all other values count as single-value lists.
     */
    public function hasBrackets(): bool
    {
        return false;
    }

    /**
     * This value as a list.
     *
     * All SassScript values can be used as lists. Maps count as lists of pairs,
     * and all other values count as single-value lists.
     *
     * @return list<Value>
     */
    public function asList(): array
    {
        return [$this];
    }

    /**
     * The length of {@see asList}.
     *
     * This is used to compute {@see sassIndexToListIndex} without allocating a new
     * list.
     */
    protected function getLengthAsList(): int
    {
        return 1;
    }

    /**
     * Calls the appropriate visit method on $visitor.
     *
     * @template T
     *
     * @param ValueVisitor<T> $visitor
     *
     * @return T
     *
     * @internal
     */
    abstract public function accept(ValueVisitor $visitor);

    /**
     * Converts $sassIndex into a PHP-style index into the list returned by
     * {@see asList}.
     *
     * Sass indexes are one-based, while PHP indexes are zero-based. Sass
     * indexes may also be negative in order to index from the end of the list.
     *
     * @throws SassScriptException if $sassIndex isn't a number, if that
     * number isn't an integer, or if that integer isn't a valid index for
     * {@see asList}. If $sassIndex came from a function argument, $name is the
     * argument name (without the `$`). It's used for error reporting.
     */
    public function sassIndexToListIndex(Value $sassIndex, ?string $name = null): int
    {
        $indexValue = $sassIndex->assertNumber($name);

        if ($indexValue->hasUnits()) {
            $message = <<<WARNING
\$$name: Passing a number with unit {$indexValue->getUnitString()} is deprecated.

To preserve current behavior: {$indexValue->unitSuggestion($name ?? 'index')}

More info: https://sass-lang.com/d/function-units
WARNING;

            Warn::forDeprecation($message, Deprecation::functionUnits);
        }

        $index = $indexValue->assertInt($name);

        if ($index === 0) {
            throw SassScriptException::forArgument('List index may not be 0.', $name);
        }

        $lengthAsList = $this->getLengthAsList();

        if (abs($index) > $lengthAsList) {
            throw SassScriptException::forArgument("Invalid index $sassIndex for a list with $lengthAsList elements.", $name);
        }

        return $index < 0 ? $lengthAsList + $index : $index - 1;
    }

    /**
     * Throws a {@see SassScriptException} if $this isn't a boolean.
     *
     * Note that generally, functions should use {@see isTruthy} rather than requiring
     * a literal boolean.
     *
     * If this came from a function argument, $name is the argument name
     * (without the `$`). It's used for error reporting.
     *
     * @throws SassScriptException
     */
    public function assertBoolean(?string $name = null): SassBoolean
    {
        throw SassScriptException::forArgument("$this is not a boolean.", $name);
    }

    /**
     * Throws a {@see SassScriptException} if $this isn't a calculation.
     *
     * If this came from a function argument, $name is the argument name
     * (without the `$`). It's used for error reporting.
     *
     * @throws SassScriptException
     */
    public function assertCalculation(?string $name = null): SassCalculation
    {
        throw SassScriptException::forArgument("$this is not a calculation.", $name);
    }

    /**
     * Throws a {@see SassScriptException} if $this isn't a color.
     *
     * If this came from a function argument, $name is the argument name
     * (without the `$`). It's used for error reporting.
     *
     * @throws SassScriptException
     */
    public function assertColor(?string $name = null): SassColor
    {
        throw SassScriptException::forArgument("$this is not a color.", $name);
    }

    /**
     * Throws a {@see SassScriptException} if $this isn't a function reference.
     *
     * If this came from a function argument, $name is the argument name
     * (without the `$`). It's used for error reporting.
     *
     * @throws SassScriptException
     */
    public function assertFunction(?string $name = null): SassFunction
    {
        throw SassScriptException::forArgument("$this is not a function reference.", $name);
    }

    /**
     * Throws a {@see SassScriptException} if $this isn't a mixin reference.
     *
     * If this came from a function argument, $name is the argument name
     * (without the `$`). It's used for error reporting.
     *
     * @throws SassScriptException
     */
    public function assertMixin(?string $name = null): SassMixin
    {
        throw SassScriptException::forArgument("$this is not a mixin reference.", $name);
    }

    /**
     * Throws a {@see SassScriptException} if $this isn't a map.
     *
     * If this came from a function argument, $name is the argument name
     * (without the `$`). It's used for error reporting.
     *
     * @throws SassScriptException
     */
    public function assertMap(?string $name = null): SassMap
    {
        throw SassScriptException::forArgument("$this is not a map.", $name);
    }

    /**
     * Return $this as a SassMap if it is one (including empty lists) or null otherwise.
     */
    public function tryMap(): ?SassMap
    {
        return null;
    }

    /**
     * Throws a {@see SassScriptException} if $this isn't a number.
     *
     * If this came from a function argument, $name is the argument name
     * (without the `$`). It's used for error reporting.
     *
     * @throws SassScriptException
     */
    public function assertNumber(?string $name = null): SassNumber
    {
        throw SassScriptException::forArgument("$this is not a number.", $name);
    }

    /**
     * Throws a {@see SassScriptException} if $this isn't a string.
     *
     * If this came from a function argument, $name is the argument name
     * (without the `$`). It's used for error reporting.
     *
     * @throws SassScriptException
     */
    public function assertString(?string $name = null): SassString
    {
        throw SassScriptException::forArgument("$this is not a string.", $name);
    }

    /**
     * Parses $this as a selector list, in the same manner as the
     * `selector-parse()` function.
     *
     * @throws SassScriptException if this isn't a type that can be parsed as a
     * selector, or if parsing fails. If $allowParent is `true`, this allows
     * {@see ParentSelector}s. Otherwise, they're considered parse errors.
     *
     * If this came from a function argument, $name is the argument name
     * (without the `$`). It's used for error reporting.
     *
     * @internal
     */
    public function assertSelector(?string $name = null, bool $allowParent = false): SelectorList
    {
        $string = $this->selectorString($name);

        try {
            return SelectorList::parse($string, null, null, null, $allowParent);
        } catch (SassFormatException $e) {
            throw SassScriptException::forArgument($e->getMessage(), $name, $e);
        }
    }

    /**
     * Parses $this as a simple selector, in the same manner as the
     * `selector-parse()` function.
     *
     * @throws SassScriptException if this isn't a type that can be parsed as a
     * selector, or if parsing fails. If $allowParent is `true`, this allows
     * {@see ParentSelector}s. Otherwise, they're considered parse errors.
     *
     * If this came from a function argument, $name is the argument name
     * (without the `$`). It's used for error reporting.
     *
     * @internal
     */
    public function assertSimpleSelector(?string $name = null, bool $allowParent = false): SimpleSelector
    {
        $string = $this->selectorString($name);

        try {
            return SimpleSelector::parse($string, null, null, $allowParent);
        } catch (SassFormatException $e) {
            throw SassScriptException::forArgument($e->getMessage(), $name, $e);
        }
    }

    /**
     * Parses $this as a compound selector, in the same manner as the
     * `selector-parse()` function.
     *
     * @throws SassScriptException if this isn't a type that can be parsed as a
     * selector, or if parsing fails. If $allowParent is `true`, this allows
     * {@see ParentSelector}s. Otherwise, they're considered parse errors.
     *
     * If this came from a function argument, $name is the argument name
     * (without the `$`). It's used for error reporting.
     *
     * @internal
     */
    public function assertCompoundSelector(?string $name = null, bool $allowParent = false): CompoundSelector
    {
        $string = $this->selectorString($name);

        try {
            return CompoundSelector::parse($string, null, null, $allowParent);
        } catch (SassFormatException $e) {
            throw SassScriptException::forArgument($e->getMessage(), $name, $e);
        }
    }

    /**
     * Parses $this as a complex selector, in the same manner as the
     * `selector-parse()` function.
     *
     * @throws SassScriptException if this isn't a type that can be parsed as a
     * selector, or if parsing fails. If $allowParent is `true`, this allows
     * {@see ParentSelector}s. Otherwise, they're considered parse errors.
     *
     * If this came from a function argument, $name is the argument name
     * (without the `$`). It's used for error reporting.
     *
     * @internal
     */
    public function assertComplexSelector(?string $name = null, bool $allowParent = false): ComplexSelector
    {
        $string = $this->selectorString($name);

        try {
            return ComplexSelector::parse($string, null, null, $allowParent);
        } catch (SassFormatException $e) {
            throw SassScriptException::forArgument($e->getMessage(), $name, $e);
        }
    }

    /**
     * Converts a `selector-parse()`-style input into a string that can be
     * parsed.
     *
     * @throws SassScriptException if $this isn't a type or a structure that
     * can be parsed as a selector.
     */
    private function selectorString(?string $name): string
    {
        $string = $this->selectorStringOrNull();

        if ($string !== null) {
            return $string;
        }

        throw SassScriptException::forArgument("$this is not a valid selector: it must be a string,\na list of strings, or a list of lists of strings.", $name);
    }

    /**
     * Converts a `selector-parse()`-style input into a string that can be
     * parsed.
     *
     * Returns `null` if $this isn't a type or a structure that can be parsed as
     * a selector.
     */
    private function selectorStringOrNull(): ?string
    {
        if ($this instanceof SassString) {
            return $this->getText();
        }

        if (!$this instanceof SassList) {
            return null;
        }

        $list = $this;
        if (\count($list->asList()) === 0) {
            return null;
        }

        $result = [];
        switch ($list->getSeparator()) {
            case ListSeparator::COMMA:
                foreach ($list->asList() as $complex) {
                    if ($complex instanceof SassString) {
                        $result[] = $complex->getText();
                    } elseif ($complex instanceof SassList && $complex->getSeparator() === ListSeparator::SPACE) {
                        $string = $complex->selectorStringOrNull();

                        if ($string === null) {
                            return null;
                        }

                        $result[] = $string;
                    } else {
                        return null;
                    }
                }
                break;

            case ListSeparator::SLASH:
                return null;

            default:
                foreach ($list->asList() as $compound) {
                    if ($compound instanceof SassString) {
                        $result[] = $compound->getText();
                    } else {
                        return null;
                    }
                }
                break;
        }

        return implode($list->getSeparator() === ListSeparator::COMMA ? ', ' : ' ', $result);
    }

    /**
     * Whether the value will be represented in CSS as the empty string.
     *
     * @internal
     */
    public function isBlank(): bool
    {
        return false;
    }

    /**
     * Whether this is a value that CSS may treat as a number, such as `calc()` or `var()`.
     *
     * Functions that shadow plain CSS functions need to gracefully handle when
     * these arguments are passed in.
     *
     * @internal
     */
    public function isSpecialNumber(): bool
    {
        return false;
    }

    /**
     * Whether this is a call to `var()`, which may be substituted in CSS for a custom property value.
     *
     * Functions that shadow plain CSS functions need to gracefully handle when
     * these arguments are passed in.
     *
     * @internal
     */
    public function isVar(): bool
    {
        return false;
    }

    /**
     * Returns PHP's `null` value if this is Sass null, and returns `$this` otherwise
     */
    public function realNull(): ?Value
    {
        return $this;
    }

    /**
     * Returns a new list containing $contents that defaults to this value's
     * separator and brackets.
     *
     * @param list<Value> $contents
     */
    public function withListContents(array $contents, ?ListSeparator $separator = null, ?bool $brackets = null): SassList
    {
        return new SassList($contents, $separator ?? $this->getSeparator(), $brackets ?? $this->hasBrackets());
    }

    /**
     * The SassScript = operation
     *
     * @internal
     */
    public function singleEquals(Value $other): Value
    {
        return new SassString(sprintf('%s=%s', $this->toCssString(), $other->toCssString()), false);
    }

    /**
     * The SassScript `>` operation.
     *
     * @internal
     */
    public function greaterThan(Value $other): SassBoolean
    {
        throw new SassScriptException("Undefined operation \"$this > $other\".");
    }

    /**
     * The SassScript `>=` operation.
     *
     * @internal
     */
    public function greaterThanOrEquals(Value $other): SassBoolean
    {
        throw new SassScriptException("Undefined operation \"$this >= $other\".");
    }

    /**
     * The SassScript `<` operation.
     *
     * @internal
     */
    public function lessThan(Value $other): SassBoolean
    {
        throw new SassScriptException("Undefined operation \"$this < $other\".");
    }

    /**
     * The SassScript `<=` operation.
     *
     * @internal
     */
    public function lessThanOrEquals(Value $other): SassBoolean
    {
        throw new SassScriptException("Undefined operation \"$this <= $other\".");
    }

    /**
     * The SassScript `*` operation.
     *
     * @internal
     */
    public function times(Value $other): Value
    {
        throw new SassScriptException("Undefined operation \"$this * $other\".");
    }

    /**
     * The SassScript `%` operation.
     *
     * @internal
     */
    public function modulo(Value $other): Value
    {
        throw new SassScriptException("Undefined operation \"$this % $other\".");
    }

    /**
     * The SassScript `+` operation.
     *
     * @internal
     */
    public function plus(Value $other): Value
    {
        if ($other instanceof SassString) {
            return new SassString($this->toCssString() . $other->getText(), $other->hasQuotes());
        }

        if ($other instanceof SassCalculation) {
            throw new SassScriptException("Undefined operation \"$this + $other\".");
        }

        return new SassString($this->toCssString() . $other->toCssString(), false);
    }

    /**
     * The SassScript `-` operation.
     *
     * @internal
     */
    public function minus(Value $other): Value
    {
        if ($other instanceof SassCalculation) {
            throw new SassScriptException("Undefined operation \"$this - $other\".");
        }

        return new SassString(sprintf('%s-%s', $this->toCssString(), $other->toCssString()), false);
    }

    /**
     * The SassScript `/` operation.
     *
     * @internal
     */
    public function dividedBy(Value $other): Value
    {
        return new SassString(sprintf('%s/%s', $this->toCssString(), $other->toCssString()), false);
    }

    /**
     * The SassScript unary `+` operation.
     *
     * @internal
     */
    public function unaryPlus(): Value
    {
        return new SassString(sprintf('+%s', $this->toCssString()), false);
    }

    /**
     * The SassScript unary `-` operation.
     *
     * @internal
     */
    public function unaryMinus(): Value
    {
        return new SassString(sprintf('-%s', $this->toCssString()), false);
    }

    /**
     * The SassScript unary `/` operation.
     *
     * @internal
     */
    public function unaryDivide(): Value
    {
        return new SassString(sprintf('/%s', $this->toCssString()), false);
    }

    /**
     * The SassScript unary `not` operation.
     *
     * @internal
     */
    public function unaryNot(): Value
    {
        return SassBoolean::create(false);
    }

    /**
     * Returns a copy of $this without {@see SassNumber::$asSlash} set.
     *
     * If this isn't a SassNumber, return it as-is.
     *
     * @internal
     */
    public function withoutSlash(): Value
    {
        return $this;
    }

    /**
     * Returns a valid CSS representation of $this.
     *
     * Use {@see toString} instead to get a string representation even if this
     * isn't valid CSS.
     *
     * Internal-only: If $quote is `false`, quoted strings are emitted without
     * quotes.
     *
     * @throws SassScriptException if $this cannot be represented in plain CSS.
     */
    final public function toCssString(bool $quote = true): string
    {
        return Serializer::serializeValue($this, false, $quote);
    }

    /**
     * Returns a Sass representation of $this.
     *
     * Note that this is equivalent to calling `inspect()` on the value, and thus
     * won't reflect the user's output settings. {@see toCssString} should be used
     * instead to convert $this to CSS.
     */
    final public function __toString(): string
    {
        return Serializer::serializeValue($this, true);
    }
}
