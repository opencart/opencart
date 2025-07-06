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

namespace ScssPhp\ScssPhp\Function;

use ScssPhp\ScssPhp\Ast\Selector\ComplexSelector;
use ScssPhp\ScssPhp\Ast\Selector\ComplexSelectorComponent;
use ScssPhp\ScssPhp\Ast\Selector\CompoundSelector;
use ScssPhp\ScssPhp\Ast\Selector\ParentSelector;
use ScssPhp\ScssPhp\Ast\Selector\SelectorList;
use ScssPhp\ScssPhp\Ast\Selector\SimpleSelector;
use ScssPhp\ScssPhp\Ast\Selector\TypeSelector;
use ScssPhp\ScssPhp\Ast\Selector\UniversalSelector;
use ScssPhp\ScssPhp\Evaluation\EvaluationContext;
use ScssPhp\ScssPhp\Exception\SassScriptException;
use ScssPhp\ScssPhp\Extend\ConcreteExtensionStore;
use ScssPhp\ScssPhp\Util\ArrayUtil;
use ScssPhp\ScssPhp\Value\ListSeparator;
use ScssPhp\ScssPhp\Value\SassBoolean;
use ScssPhp\ScssPhp\Value\SassList;
use ScssPhp\ScssPhp\Value\SassNull;
use ScssPhp\ScssPhp\Value\SassString;
use ScssPhp\ScssPhp\Value\Value;

/**
 * @internal
 */
final class SelectorFunctions
{
    /**
     * @param list<Value> $arguments
     */
    public static function nest(array $arguments): Value
    {
        $selectors = $arguments[0]->asList();

        if (\count($selectors) === 0) {
            throw new SassScriptException('$selectors: At least one selector must be passed.');
        }

        $first = true;

        return ArrayUtil::reduce(array_map(function (Value $selector) use (&$first) {
            $result = $selector->assertSelector(allowParent: !$first);
            $first = false;

            return $result;
        }, $selectors), fn (SelectorList $parent, SelectorList $child) => $child->nestWithin($parent))->asSassList();
    }

    /**
     * @param list<Value> $arguments
     */
    public static function append(array $arguments): Value
    {
        $selectors = $arguments[0]->asList();

        if (\count($selectors) === 0) {
            throw new SassScriptException('$selectors: At least one selector must be passed.');
        }

        $span = EvaluationContext::getCurrent()->getCurrentCallableSpan();

        return ArrayUtil::reduce(array_map(fn(Value $selector) => $selector->assertSelector(), $selectors), function (SelectorList $parent, SelectorList $child) use ($span) {
            return (new SelectorList(array_map(function (ComplexSelector $complex) use ($span, $parent) {
                if (\count($complex->getLeadingCombinators()) > 0) {
                    throw new SassScriptException("Can't append $complex to $parent.");
                }

                $component = $complex->getComponents()[0];
                $rest = array_slice($complex->getComponents(), 1);
                $newCompound = self::prependParent($component->getSelector());

                if ($newCompound === null) {
                    throw new SassScriptException("Can't append $complex to $parent.");
                }

                return new ComplexSelector([], [
                    new ComplexSelectorComponent($newCompound, $component->getCombinators(), $span),
                    ...$rest,
                ], $span);
            }, $child->getComponents()), $span))->nestWithin($parent);
        })->asSassList();
    }

    /**
     * @param list<Value> $arguments
     */
    public static function extend(array $arguments): Value
    {
        $selector = $arguments[0]->assertSelector('selector');
        $selector->assertNotBogus('selector');
        $target = $arguments[1]->assertSelector('extendee');
        $target->assertNotBogus('extendee');
        $source = $arguments[2]->assertSelector('extender');
        $source->assertNotBogus('extender');

        return ConcreteExtensionStore::extend($selector, $source, $target, EvaluationContext::getCurrent()->getCurrentCallableSpan())->asSassList();
    }

    /**
     * @param list<Value> $arguments
     */
    public static function replace(array $arguments): Value
    {
        $selector = $arguments[0]->assertSelector('selector');
        $selector->assertNotBogus('selector');
        $target = $arguments[1]->assertSelector('original');
        $target->assertNotBogus('original');
        $source = $arguments[2]->assertSelector('replacement');
        $source->assertNotBogus('replacement');

        return ConcreteExtensionStore::replace($selector, $source, $target, EvaluationContext::getCurrent()->getCurrentCallableSpan())->asSassList();
    }

    /**
     * @param list<Value> $arguments
     */
    public static function unify(array $arguments): Value
    {
        $selector1 = $arguments[0]->assertSelector('selector1');
        $selector1->assertNotBogus('selector1');

        $selector2 = $arguments[1]->assertSelector('selector2');
        $selector2->assertNotBogus('selector2');

        return $selector1->unify($selector2)?->asSassList() ?? SassNull::create();
    }

    /**
     * @param list<Value> $arguments
     */
    public static function isSuperselector(array $arguments): Value
    {
        $selector1 = $arguments[0]->assertSelector('super');
        $selector1->assertNotBogus('super');

        $selector2 = $arguments[1]->assertSelector('sub');
        $selector2->assertNotBogus('sub');

        return SassBoolean::create($selector1->isSuperselector($selector2));
    }

    /**
     * @param list<Value> $arguments
     */
    public static function simpleSelectors(array $arguments): Value
    {
        $selector = $arguments[0]->assertCompoundSelector('selector');

        return new SassList(
            array_map(fn (SimpleSelector $simple) => new SassString((string) $simple, false), $selector->getComponents()),
            ListSeparator::COMMA
        );
    }

    /**
     * @param list<Value> $arguments
     */
    public static function parse(array $arguments): Value
    {
        return $arguments[0]->assertSelector('selector')->asSassList();
    }

    /**
     * Adds a {@see ParentSelector} to the beginning of $compound, or returns `null` if
     * that wouldn't produce a valid selector.
     */
    private static function prependParent(CompoundSelector $compound): ?CompoundSelector
    {
        $span = EvaluationContext::getCurrent()->getCurrentCallableSpan();

        $firstComponent = $compound->getComponents()[0];

        if ($firstComponent instanceof UniversalSelector) {
            return null;
        }

        if ($firstComponent instanceof TypeSelector && $firstComponent->getName()->getNamespace() !== null) {
            return null;
        }

        if ($firstComponent instanceof TypeSelector) {
            return new CompoundSelector([
                new ParentSelector($span, $firstComponent->getName()->getName()),
                ...array_slice($compound->getComponents(), 1),
            ], $span);
        }

        return new CompoundSelector([
            new ParentSelector($span),
            ...$compound->getComponents(),
        ], $span);
    }
}
