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

namespace ScssPhp\ScssPhp\Extend;

use ScssPhp\ScssPhp\Ast\Css\CssValue;
use ScssPhp\ScssPhp\Ast\Selector\Combinator;
use ScssPhp\ScssPhp\Ast\Selector\ComplexSelector;
use ScssPhp\ScssPhp\Ast\Selector\ComplexSelectorComponent;
use ScssPhp\ScssPhp\Ast\Selector\CompoundSelector;
use ScssPhp\ScssPhp\Ast\Selector\IDSelector;
use ScssPhp\ScssPhp\Ast\Selector\PlaceholderSelector;
use ScssPhp\ScssPhp\Ast\Selector\PseudoSelector;
use ScssPhp\ScssPhp\Ast\Selector\QualifiedName;
use ScssPhp\ScssPhp\Ast\Selector\SelectorList;
use ScssPhp\ScssPhp\Ast\Selector\SimpleSelector;
use ScssPhp\ScssPhp\Ast\Selector\TypeSelector;
use ScssPhp\ScssPhp\Ast\Selector\UniversalSelector;
use ScssPhp\ScssPhp\Util\EquatableUtil;
use ScssPhp\ScssPhp\Util\IterableUtil;
use ScssPhp\ScssPhp\Util\ListUtil;
use ScssPhp\ScssPhp\Util\SpanUtil;
use SourceSpan\FileSpan;

/**
 * @internal
 */
final class ExtendUtil
{
    /**
     * Pseudo-selectors that can only meaningfully appear in the first component of
     * a complex selector.
     */
    private const ROOTISH_PSEUDO_CLASSES = ['root', 'scope', 'host', 'host-context'];

    /**
     * Returns the contents of a {@see SelectorList} that matches only elements that are
     * matched by every complex selector in $complexes.
     *
     * If no such list can be produced, returns `null`.
     *
     * @param list<ComplexSelector> $complexes
     *
     * @return list<ComplexSelector>|null
     */
    public static function unifyComplex(array $complexes, FileSpan $span): ?array
    {
        if (\count($complexes) === 1) {
            return $complexes;
        }

        $unifiedBase = null;
        $leadingCombinator = null;
        $trailingCombinator = null;

        foreach ($complexes as $complex) {
            if ($complex->isUseless()) {
                return null;
            }

            if (\count($complex->getComponents()) === 1 && \count($complex->getLeadingCombinators()) !== 0) {
                $newLeadingCombinator = \count($complex->getLeadingCombinators()) === 1 ? $complex->getLeadingCombinators()[0] : null;
                if ($leadingCombinator !== null && !EquatableUtil::equals($newLeadingCombinator, $leadingCombinator)) {
                    return null;
                }

                $leadingCombinator = $newLeadingCombinator;
            }

            $base = $complex->getLastComponent();

            if (\count($base->getCombinators()) !== 0) {
                $newTrailingCombinator = \count($base->getCombinators()) === 1 ? $base->getCombinators()[0] : null;

                if ($trailingCombinator !== null && $newTrailingCombinator !== $trailingCombinator) {
                    return null;
                }

                $trailingCombinator = $newTrailingCombinator;
            }

            if ($unifiedBase === null) {
                $unifiedBase = $base->getSelector()->getComponents();
            } else {
                foreach ($base->getSelector()->getComponents() as $simple) {
                    $unifiedBase = $simple->unify($unifiedBase);

                    if ($unifiedBase === null) {
                        return null;
                    }
                }
            }
        }

        $withoutBases = [];
        $hasLineBreak = false;
        foreach ($complexes as $complex) {
            if (\count($complex->getComponents()) > 1) {
                $withoutBases[] = new ComplexSelector($complex->getLeadingCombinators(), array_slice($complex->getComponents(), 0, \count($complex->getComponents()) - 1), $complex->getSpan(), $complex->getLineBreak());
            }

            if ($complex->getLineBreak()) {
                $hasLineBreak = true;
            }
        }

        \assert($unifiedBase !== null);

        $base = new ComplexSelector(
            $leadingCombinator === null ? [] : [$leadingCombinator],
            [new ComplexSelectorComponent(new CompoundSelector($unifiedBase, $span), $trailingCombinator === null ? [] : [$trailingCombinator], $span)],
            $span,
            $hasLineBreak
        );

        return self::weave($withoutBases === [] ? [$base] : array_merge(ListUtil::exceptLast($withoutBases), [ListUtil::last($withoutBases)->concatenate($base, $span)]), $span);
    }

    /**
     * Returns a {@see CompoundSelector} that matches only elements that are matched by
     * both $compound1 and $compound2.
     *
     * If no such selector can be produced, returns `null`.
     */
    public static function unifyCompound(CompoundSelector $compound1, CompoundSelector $compound2): ?CompoundSelector
    {
        $result = $compound2->getComponents();

        foreach ($compound1->getComponents() as $simple) {
            $unified = $simple->unify($result);

            if ($unified === null) {
                return null;
            }

            $result = $unified;
        }

        return new CompoundSelector($result, $compound1->getSpan());
    }

    /**
     * Returns a {@see SimpleSelector} that matches only elements that are matched by
     * both $selector1 and $selector2, which must both be either
     * {@see UniversalSelector}s or {@see TypeSelector}s.
     *
     * If no such selector can be produced, returns `null`.
     */
    public static function unifyUniversalAndElement(SimpleSelector $selector1, SimpleSelector $selector2): ?SimpleSelector
    {
        [$namespace1, $name1] = self::namespaceAndName($selector1, 'selector1');
        [$namespace2, $name2] = self::namespaceAndName($selector2, 'selector2');

        if ($namespace1 === $namespace2 || $namespace2 === '*') {
            $namespace = $namespace1;
        } elseif ($namespace1 === '*') {
            $namespace = $namespace2;
        } else {
            return null;
        }

        if ($name1 === $name2 || $name2 === null) {
            $name = $name1;
        } elseif ($name1 === null) {
            $name = $name2;
        } else {
            return null;
        }

        if ($name === null) {
            return new UniversalSelector($selector1->getSpan(), $namespace);
        }

        return new TypeSelector(new QualifiedName($name, $namespace), $selector1->getSpan());
    }

    /**
     * Returns the namespace and name for $selector, which must be a
     * {@see UniversalSelector} or a {@see TypeSelector}.
     *
     * The $name parameter is used for error reporting.
     *
     * @return array{string|null, string|null} The namespace and the name
     */
    private static function namespaceAndName(SimpleSelector $selector, string $name): array
    {
        if ($selector instanceof UniversalSelector) {
            return [$selector->getNamespace(), null];
        }

        if ($selector instanceof TypeSelector) {
            return [$selector->getName()->getNamespace(), $selector->getName()->getName()];
        }

        throw new \InvalidArgumentException("Argument $name must be a UniversalSelector or a TypeSelector.");
    }

    /**
     * Expands "parenthesized selectors" in $complexes.
     *
     * That is, if we have `.A .B {@extend .C}` and `.D .C {...}`, this
     * conceptually expands into `.D .C, .D (.A .B)`, and this function translates
     * `.D (.A .B)` into `.D .A .B, .A .D .B`. For thoroughness, `.A.D .B` would
     * also be required, but including merged selectors results in exponential
     * output for very little gain.
     *
     * The selector `.D (.A .B)` is represented as the list `[.D, .A .B]`.
     *
     * The $span will be used for any new combined selectors.
     *
     * If $forceLineBreak is `true`, this will mark all returned complex selectors
     * as having line breaks.
     *
     * @param list<ComplexSelector> $complexes
     *
     * @return list<ComplexSelector>
     */
    public static function weave(array $complexes, FileSpan $span, bool $forceLineBreak = false): array
    {
        if (\count($complexes) === 1) {
            $complex = $complexes[0];

            if (!$forceLineBreak || $complex->getLineBreak()) {
                return $complexes;
            }

            return [
                new ComplexSelector($complex->getLeadingCombinators(), $complex->getComponents(), $complex->getSpan(), true),
            ];
        }

        $prefixes = [$complexes[0]];

        foreach (array_slice($complexes, 1) as $complex) {
            if (\count($complex->getComponents()) === 1) {
                foreach ($prefixes as $i => $prefix) {
                    $prefixes[$i] = $prefix->concatenate($complex, $span, $forceLineBreak);
                }

                continue;
            }

            $newPrefixes = [];

            foreach ($prefixes as $prefix) {
                foreach (self::weaveParents($prefix, $complex, $span) ?? [] as $parentPrefix) {
                    $newPrefixes[] = $parentPrefix->withAdditionalComponent(ListUtil::last($complex->getComponents()), $span, $forceLineBreak);
                }
            }

            $prefixes = $newPrefixes;
        }

        return $prefixes;
    }

    /**
     * Interweaves $prefix's components with $base's components _other than
     * the last_.
     *
     * Returns all possible orderings of the selectors in the inputs (including
     * using unification) that maintain the relative ordering of the input. For
     * example, given `.foo .bar` and `.baz .bang div`, this would return `.foo
     * .bar .baz .bang div`, `.foo .bar.baz .bang div`, `.foo .baz .bar .bang div`,
     * `.foo .baz .bar.bang div`, `.foo .baz .bang .bar div`, and so on until `.baz
     * .bang .foo .bar div`.
     *
     * Semantically, for selectors `P` and `C`, this returns all selectors `PC_i`
     * such that the union over all `i` of elements matched by `PC_i` is identical
     * to the intersection of all elements matched by `C` and all descendants of
     * elements matched by `P`. Some `PC_i` are elided to reduce the size of the
     * output.
     *
     * The $span will be used for any new combined selectors.
     *
     * Returns `null` if this intersection is empty.
     *
     * @return list<ComplexSelector>|null
     */
    private static function weaveParents(ComplexSelector $prefix, ComplexSelector $base, FileSpan $span): ?array
    {
        $leadingCombinators = self::mergeLeadingCombinators($prefix->getLeadingCombinators(), $base->getLeadingCombinators());
        if ($leadingCombinators === null) {
            return null;
        }

        // Make queues of _only_ the parent selectors. The prefix only contains
        // parents, but the complex selector has a target that we don't want to weave
        // in.
        $queue1 = $prefix->getComponents();
        $queue2 = ListUtil::exceptLast($base->getComponents());

        $finalCombinators = self::mergeTrailingCombinators($queue1, $queue2, $span);
        if ($finalCombinators === null) {
            return null;
        }

        // Make sure all selectors that are required to be at the root are unified
        // with one another.
        $rootish1 = self::firstIfRootish($queue1);
        $rootish2 = self::firstIfRootish($queue2);

        if ($rootish1 !== null && $rootish2 !== null) {
            $rootish = self::unifyCompound($rootish1->getSelector(), $rootish2->getSelector());

            if ($rootish === null) {
                return null;
            }

            array_unshift($queue1, new ComplexSelectorComponent($rootish, $rootish1->getCombinators(), $rootish1->getSpan()));
            array_unshift($queue2, new ComplexSelectorComponent($rootish, $rootish2->getCombinators(), $rootish2->getSpan()));
        } elseif ($rootish1 !== null || $rootish2 !== null) {
            // If there's only one rootish selector, it should only appear in the first
            // position of the resulting selector. We can ensure that happens by adding
            // it to the beginning of _both_ queues.
            $rootish = $rootish1 ?? $rootish2;
            \assert($rootish !== null);
            array_unshift($queue1, $rootish);
            array_unshift($queue2, $rootish);
        }

        $groups1 = self::groupSelectors($queue1);
        $groups2 = self::groupSelectors($queue2);

        /** @var list<list<ComplexSelectorComponent>> $lcs */
        $lcs = ListUtil::longestCommonSubsequence($groups2, $groups1, function ($group1, $group2) use ($span) {
            if (EquatableUtil::listEquals($group1, $group2)) {
                return $group1;
            }

            if (self::complexIsParentSuperselector($group1, $group2)) {
                return $group2;
            }

            if (self::complexIsParentSuperselector($group2, $group1)) {
                return $group1;
            }

            if (!self::mustUnify($group1, $group2)) {
                return null;
            }

            $unified = self::unifyComplex([new ComplexSelector([], $group1, $span), new ComplexSelector([], $group2, $span)], $span);

            if ($unified === null) {
                return null;
            }
            if (\count($unified) > 1) {
                return null;
            }

            return $unified[0]->getComponents();
        });

        $choices = [];

        foreach ($lcs as $group) {
            $newChoice = [];
            /** @var list<list<list<ComplexSelectorComponent>>> $chunks */
            $chunks = self::chunks($groups1, $groups2, fn($sequence) => self::complexIsParentSuperselector($sequence[0], $group));
            foreach ($chunks as $chunk) {
                $flattened = [];
                foreach ($chunk as $chunkGroup) {
                    $flattened = array_merge($flattened, $chunkGroup);
                }
                $newChoice[] = $flattened;
            }

            /** @var list<list<ComplexSelectorComponent>> $groups1 */
            /** @var list<list<ComplexSelectorComponent>> $groups2 */
            $choices[] = $newChoice;
            $choices[] = [$group];
            array_shift($groups1);
            array_shift($groups2);
        }

        $newChoice = [];
        /** @var list<list<list<ComplexSelectorComponent>>> $chunks */
        $chunks = self::chunks($groups1, $groups2, fn($sequence) => count($sequence) === 0);
        foreach ($chunks as $chunk) {
            $flattened = [];
            foreach ($chunk as $chunkGroup) {
                $flattened = array_merge($flattened, $chunkGroup);
            }
            $newChoice[] = $flattened;
        }

        $choices[] = $newChoice;

        foreach ($finalCombinators as $finalCombinator) {
            $choices[] = $finalCombinator;
        }

        $choices = array_filter($choices, fn($choice) => $choice !== []);

        $paths = self::paths($choices);

        return array_map(function (array $path) use ($leadingCombinators, $prefix, $base, $span) {
            $result = [];

            foreach ($path as $group) {
                $result = array_merge($result, $group);
            }

            return new ComplexSelector($leadingCombinators, $result, $span, $prefix->getLineBreak() || $base->getLineBreak());
        }, $paths);
    }

    /**
     * If the first element of $queue has a `:root` selector, removes and returns
     * that element.
     *
     * @param list<ComplexSelectorComponent> $queue
     *
     * @return ComplexSelectorComponent|null
     */
    private static function firstIfRootish(array &$queue): ?ComplexSelectorComponent
    {
        if (empty($queue)) {
            return null;
        }

        $first = $queue[0];

        foreach ($first->getSelector()->getComponents() as $simple) {
            if ($simple instanceof PseudoSelector && $simple->isClass() && \in_array($simple->getNormalizedName(), self::ROOTISH_PSEUDO_CLASSES, true)) {
                array_shift($queue);

                return $first;
            }
        }

        return null;
    }

    /**
     * Returns a leading combinator list that's compatible with both $combinators1
     * and $combinators2.
     *
     * Returns `null` if the combinator lists can't be unified.
     *
     * @param list<CssValue<Combinator>>|null $combinators1
     * @param list<CssValue<Combinator>>|null $combinators2
     *
     * @return list<CssValue<Combinator>>|null
     */
    private static function mergeLeadingCombinators(?array $combinators1, ?array $combinators2): ?array
    {
        if ($combinators1 === null) {
            return null;
        }

        if ($combinators2 === null) {
            return null;
        }

        if (\count($combinators1) > 1) {
            return null;
        }

        if (\count($combinators2) > 1) {
            return null;
        }

        if (\count($combinators1) === 0) {
            return $combinators2;
        }

        if (\count($combinators2) === 0) {
            return $combinators1;
        }

        return $combinators1 === $combinators2 ? $combinators1 : null;
    }

    /**
     * Extracts trailing {@see ComplexSelectorComponent}s with trailing combinators from
     * $components1 and $components2 and merges them together into a single list.
     *
     *  Each element in the returned list is a set of choices for a particular
     * position in a complex selector. Each choice is the contents of a complex
     * selector, which is to say a list of complex selector components. The union
     * of each path through these choices will match the full set of necessary
     * elements.
     *
     * If there are no combinators to be merged, returns an empty list. If the
     * sequences can't be merged, returns `null`.
     *
     * The $span will be used for any new combined selectors.
     *
     * @param list<ComplexSelectorComponent>             $components1
     * @param list<ComplexSelectorComponent>             $components2
     * @param list<list<list<ComplexSelectorComponent>>> $result
     *
     * @return list<list<list<ComplexSelectorComponent>>>|null
     */
    private static function mergeTrailingCombinators(array &$components1, array &$components2, FileSpan $span, array $result = []): ?array
    {
        $combinators1 = \count($components1) === 0 ? [] : ListUtil::last($components1)->getCombinators();
        $combinators2 = \count($components2) === 0 ? [] : ListUtil::last($components2)->getCombinators();

        if (\count($combinators1) === 0 && \count($combinators2) === 0) {
            return $result;
        }

        if (count($combinators1) > 1 || count($combinators2) > 1) {
            return null;
        }

        // This code looks complicated, but it's actually just a bunch of special
        // cases for interactions between different combinators.
        $combinator1 = $combinators1[0] ?? null;
        $combinator2 = $combinators2[0] ?? null;

        if ($combinator1 !== null && $combinator2 !== null) {
            $component1 = array_pop($components1);
            assert($component1 instanceof ComplexSelectorComponent);
            $component2 = array_pop($components2);
            assert($component2 instanceof ComplexSelectorComponent);

            if ($combinator1->getValue() === Combinator::FOLLOWING_SIBLING && $combinator2->getValue() === Combinator::FOLLOWING_SIBLING) {
                if ($component1->getSelector()->isSuperselector($component2->getSelector())) {
                    array_unshift($result, [[$component2]]);
                } elseif ($component2->getSelector()->isSuperselector($component1->getSelector())) {
                    array_unshift($result, [[$component1]]);
                } else {
                    $choices = [
                        [$component1, $component2],
                        [$component2, $component1],
                    ];

                    $unified = self::unifyCompound($component1->getSelector(), $component2->getSelector());

                    if ($unified !== null) {
                        $choices[] = [new ComplexSelectorComponent($unified, [$combinator1], $span)];
                    }

                    array_unshift($result, $choices);
                }
            } elseif (($combinator1->getValue() === Combinator::FOLLOWING_SIBLING && $combinator2->getValue() === Combinator::NEXT_SIBLING) || ($combinator1->getValue() === Combinator::NEXT_SIBLING && $combinator2->getValue() === Combinator::FOLLOWING_SIBLING)) {
                $followingSiblingComponent = $combinator1->getValue() === Combinator::FOLLOWING_SIBLING ? $component1 : $component2;
                $nextSiblingComponent = $combinator1->getValue() === Combinator::FOLLOWING_SIBLING ? $component2 : $component1;

                if ($followingSiblingComponent->getSelector()->isSuperselector($nextSiblingComponent->getSelector())) {
                    array_unshift($result, [[$nextSiblingComponent]]);
                } else {
                    $unified = self::unifyCompound($followingSiblingComponent->getSelector(), $nextSiblingComponent->getSelector());

                    $choices = [
                        [$followingSiblingComponent, $nextSiblingComponent],
                    ];

                    if ($unified !== null) {
                        $choices[] = [new ComplexSelectorComponent($unified, $nextSiblingComponent->getCombinators(), $span)];
                    }

                    array_unshift($result, $choices);
                }
            } elseif ($combinator1->getValue() === Combinator::CHILD && ($combinator2->getValue() === Combinator::NEXT_SIBLING || $combinator2->getValue() === Combinator::FOLLOWING_SIBLING)) {
                array_unshift($result, [[$component2]]);
                $components1[] = $component1;
            } elseif ($combinator2->getValue() === Combinator::CHILD && ($combinator1->getValue() === Combinator::NEXT_SIBLING || $combinator1->getValue() === Combinator::FOLLOWING_SIBLING)) {
                array_unshift($result, [[$component1]]);
                $components2[] = $component2;
            } elseif (EquatableUtil::equals($combinator1, $combinator2)) {
                $unified = self::unifyCompound($component1->getSelector(), $component2->getSelector());

                if ($unified === null) {
                    return null;
                }

                array_unshift($result, [[new ComplexSelectorComponent($unified, [$combinator1], $span)]]);
            } else {
                return null;
            }

            return self::mergeTrailingCombinators($components1, $components2, $span, $result);
        }

        if ($combinator1 !== null) {
            $component1 = array_pop($components1);
            \assert($component1 instanceof ComplexSelectorComponent);

            if ($combinator1->getValue() === Combinator::CHILD && \count($components2) > 0 && ListUtil::last($components2)->getSelector()->isSuperselector($component1->getSelector())) {
                array_pop($components2);
            }

            array_unshift($result, [[$component1]]);

            return self::mergeTrailingCombinators($components1, $components2, $span, $result);
        }

        $component2 = array_pop($components2);
        \assert($component2 instanceof ComplexSelectorComponent);
        assert($combinator2 !== null);

        if ($combinator2->getValue() === Combinator::CHILD && \count($components1) > 0 && ListUtil::last($components1)->getSelector()->isSuperselector($component2->getSelector())) {
            array_pop($components1);
        }

        array_unshift($result, [[$component2]]);

        return self::mergeTrailingCombinators($components1, $components2, $span, $result);
    }

    /**
     * Returns whether $complex1 and $complex2 need to be unified to produce a
     * valid combined selector.
     *
     * This is necessary when both selectors contain the same unique simple
     * selector, such as an ID.
     *
     * @param list<ComplexSelectorComponent> $complex1
     * @param list<ComplexSelectorComponent> $complex2
     */
    private static function mustUnify(array $complex1, array $complex2): bool
    {
        $uniqueSelectors = [];
        foreach ($complex1 as $component) {
            foreach ($component->getSelector()->getComponents() as $simple) {
                if (self::isUnique($simple)) {
                    $uniqueSelectors[] = $simple;
                }
            }
        }

        if (\count($uniqueSelectors) === 0) {
            return false;
        }

        foreach ($complex2 as $component) {
            foreach ($component->getSelector()->getComponents() as $simple) {
                if (self::isUnique($simple) && EquatableUtil::iterableContains($uniqueSelectors, $simple)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Returns whether a {@see CompoundSelector} may contain only one simple selector of
     * the same type as $simple.
     */
    private static function isUnique(SimpleSelector $simple): bool
    {
        return $simple instanceof IDSelector || ($simple instanceof PseudoSelector && $simple->isElement());
    }

    /**
     * Returns all orderings of initial subsequences of $queue1 and $queue2.
     *
     * The $done callback is used to determine the extent of the initial
     * subsequences. It's called with each queue until it returns `true`.
     *
     * This destructively removes the initial subsequences of $queue1 and
     * $queue2.
     *
     * For example, given `(A B C | D E)` and `(1 2 | 3 4 5)` (with `|` denoting
     * the boundary of the initial subsequence), this would return `[(A B C 1 2),
     * (1 2 A B C)]`. The queues would then contain `(D E)` and `(3 4 5)`.
     *
     * @template T
     *
     * @param list<T>                 $queue1
     * @param list<T>                 $queue2
     * @param callable(list<T>): bool $done
     *
     * @return list<list<T>>
     *
     * @param-immediately-invoked-callable $done
     */
    private static function chunks(array &$queue1, array &$queue2, callable $done): array
    {
        $chunk1 = [];
        while (!$done($queue1)) {
            $element = array_shift($queue1);
            if ($element === null) {
                throw new \LogicException('Cannot remove an element from an empty queue');
            }

            $chunk1[] = $element;
        }

        $chunk2 = [];
        while (!$done($queue2)) {
            $element = array_shift($queue2);
            if ($element === null) {
                throw new \LogicException('Cannot remove an element from an empty queue');
            }

            $chunk2[] = $element;
        }

        if (empty($chunk1) && empty($chunk2)) {
            return [];
        }

        if (empty($chunk1)) {
            return [$chunk2];
        }

        if (empty($chunk2)) {
            return [$chunk1];
        }

        return [
            array_merge($chunk1, $chunk2),
            array_merge($chunk2, $chunk1),
        ];
    }

    /**
     * Returns a list of all possible paths through the given lists.
     *
     * For example, given `[[1, 2], [3, 4], [5]]`, this returns:
     *
     * ```
     * [[1, 3, 5],
     *  [2, 3, 5],
     *  [1, 4, 5],
     *  [2, 4, 5]]
     * ```
     *
     * @template T
     *
     * @param array<list<T>> $choices
     *
     * @return list<list<T>>
     */
    public static function paths(array $choices): array
    {
        return array_reduce($choices, function (array $paths, array $choice) {
            $newPaths = [];

            foreach ($choice as $option) {
                foreach ($paths as $path) {
                    $path[] = $option;
                    $newPaths[] = $path;
                }
            }

            return $newPaths;
        }, [[]]);
    }

    /**
     * Returns $complex, grouped into the longest possible sub-lists such that
     * {@see ComplexSelectorComponent}s without combinators only appear at the end of
     * sub-lists.
     *
     * For example, `(A B > C D + E ~ G)` is grouped into
     * `[(A) (B > C) (D + E ~ G)]`.
     *
     * @param iterable<ComplexSelectorComponent> $complex
     *
     * @return list<list<ComplexSelectorComponent>>
     */
    private static function groupSelectors(iterable $complex): array
    {
        $groups = [];
        $group = [];

        foreach ($complex as $component) {
            $group[] = $component;

            if (\count($component->getCombinators()) === 0) {
                $groups[] = $group;
                $group = [];
            }
        }

        if ($group !== []) {
            $groups[] = $group;
        }

        return $groups;
    }

    /**
     * Returns whether $list1 is a superselector of $list2.
     *
     * That is, whether $list1 matches every element that $list2 matches, as well
     * as possibly additional elements.
     *
     * @param list<ComplexSelector> $list1
     * @param list<ComplexSelector> $list2
     */
    public static function listIsSuperselector(array $list1, array $list2): bool
    {
        foreach ($list2 as $complex1) {
            foreach ($list1 as $complex2) {
                if ($complex2->isSuperselector($complex1)) {
                    continue 2;
                }
            }

            return false;
        }

        return true;
    }

    /**
     * Like {@see complexIsSuperselector}, but compares $complex1 and $complex2 as
     * though they shared an implicit base {@see SimpleSelector}.
     *
     * For example, `B` is not normally a superselector of `B A`, since it doesn't
     * match elements that match `A`. However, it *is* a parent superselector,
     * since `B X` is a superselector of `B A X`.
     *
     * @param list<ComplexSelectorComponent> $complex1
     * @param list<ComplexSelectorComponent> $complex2
     */
    private static function complexIsParentSuperselector(array $complex1, array $complex2): bool
    {
        if (\count($complex1) > \count($complex2)) {
            return false;
        }

        $bogusSpan = SpanUtil::bogusSpan();

        $base = new ComplexSelectorComponent(new CompoundSelector([new PlaceholderSelector('<temp>', $bogusSpan)], $bogusSpan), [], $bogusSpan);
        $complex1[] = $base;
        $complex2[] = $base;

        return self::complexIsSuperselector($complex1, $complex2);
    }

    /**
     * Returns whether $complex1 is a superselector of $complex2.
     *
     * That is, whether $complex1 matches every element that $complex2 matches, as well
     * as possibly additional elements.
     *
     * @param list<ComplexSelectorComponent> $complex1
     * @param list<ComplexSelectorComponent> $complex2
     */
    public static function complexIsSuperselector(array $complex1, array $complex2): bool
    {
        // Selectors with trailing operators are neither superselectors nor
        // subselectors.
        if (\count(ListUtil::last($complex1)->getCombinators()) !== 0) {
            return false;
        }
        if (\count(ListUtil::last($complex2)->getCombinators()) !== 0) {
            return false;
        }

        $i1 = 0;
        $i2 = 0;
        $previousCombinator = null;

        while (true) {
            $remaining1 = \count($complex1) - $i1;
            $remaining2 = \count($complex2) - $i2;

            if ($remaining1 === 0 || $remaining2 === 0) {
                return false;
            }

            // More complex selectors are never superselectors of less complex ones.
            if ($remaining1 > $remaining2) {
                return false;
            }

            $component1 = $complex1[$i1];
            if (\count($component1->getCombinators()) > 1) {
                return false;
            }
            if ($remaining1 === 1) {
                if (IterableUtil::any($complex2, fn (ComplexSelectorComponent $parent) => \count($parent->getCombinators()) > 1)) {
                    return false;
                }

                return self::compoundIsSuperselector(
                    $component1->getSelector(),
                    ListUtil::last($complex2)->getSelector(),
                    $component1->getSelector()->hasComplicatedSuperselectorSemantics() ? array_slice($complex2, $i2, -1) : null
                );
            }

            // Find the first index $endOfSubselector in $complex2 such that
            // `complex2.sublist(i2, endOfSubselector + 1)` is a subselector of
            // `$component1->getSelector()`.
            $endOfSubselector = $i2;
            while (true) {
                $component2 = $complex2[$endOfSubselector];
                if (\count($component2->getCombinators()) > 1) {
                    return false;
                }
                if (self::compoundIsSuperselector($component1->getSelector(), $component2->getSelector(), $component1->getSelector()->hasComplicatedSuperselectorSemantics() ? array_slice($complex2, $i2, $endOfSubselector - $i2) : null)) {
                    break;
                }

                $endOfSubselector++;

                if ($endOfSubselector === \count($complex2) - 1) {
                    // Stop before the superselector would encompass all of $complex2
                    // because we know $complex1 has more than one element, and consuming
                    // all of $complex2 wouldn't leave anything for the rest of $complex1
                    // to match.
                    return false;
                }
            }

            if (!self::compatibleWithPreviousCombinator($previousCombinator, array_slice($complex2, $i2, $endOfSubselector - $i2))) {
                return false;
            }

            $component2 = $complex2[$endOfSubselector];
            $combinator1 = $component1->getCombinators()[0] ?? null;
            $combinator2 = $component2->getCombinators()[0] ?? null;

            if (!self::isSupercombinator($combinator1, $combinator2)) {
                return false;
            }

            $i1++;
            $i2 = $endOfSubselector + 1;
            $previousCombinator = $combinator1;

            if (\count($complex1) - $i1 === 1) {
                if ($combinator1 !== null && $combinator1->getValue() === Combinator::FOLLOWING_SIBLING) {
                    // The selector `.foo ~ .bar` is only a superselector of selectors that
                    // *exclusively* contain subcombinators of `~`.
                    for ($index = $i2; $index < \count($complex2) - 1; $index++) {
                        $component = $complex2[$index];

                        if (!self::isSupercombinator($combinator1, $component->getCombinators()[0] ?? null)) {
                            return false;
                        }
                    }
                } elseif ($combinator1 !== null) {
                    // `.foo > .bar` and `.foo + bar` aren't superselectors of any selectors
                    // with more than one combinator.
                    if (\count($complex2) - $i2 > 1) {
                        return false;
                    }
                }
            }
        }
    }

    /**
     * @param CssValue<Combinator>|null $previous
     * @param list<ComplexSelectorComponent> $parents
     */
    private static function compatibleWithPreviousCombinator(?CssValue $previous, array $parents): bool
    {
        if ($parents === []) {
            return true;
        }

        if ($previous === null) {
            return true;
        }

        // The child and next sibling combinators require that the *immediate*
        // following component be a superselector.
        if ($previous->getValue() !== Combinator::FOLLOWING_SIBLING) {
            return false;
        }

        // The following sibling combinator does allow intermediate components, but
        // only if they're all siblings.
        foreach ($parents as $component) {
            $firstCombinator = $component->getCombinators()[0] ?? null;
            $firstCombinatorValue = $firstCombinator?->getValue();

            if ($firstCombinatorValue !== Combinator::FOLLOWING_SIBLING && $firstCombinatorValue !== Combinator::NEXT_SIBLING) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns whether $combinator1 is a supercombinator of $combinator2.
     *
     * That is, whether `X $combinator1 Y` is a superselector of `X $combinator2 Y`.
     *
     * @param CssValue<Combinator>|null $combinator1
     * @param CssValue<Combinator>|null $combinator2
     */
    private static function isSupercombinator(?CssValue $combinator1, ?CssValue $combinator2): bool
    {
        return EquatableUtil::equals($combinator1, $combinator2) || ($combinator1 === null && $combinator2 !== null && $combinator2->getValue() === Combinator::CHILD) || ($combinator1 !== null && $combinator1->getValue() === Combinator::FOLLOWING_SIBLING && $combinator2 !== null && $combinator2->getValue() === Combinator::NEXT_SIBLING);
    }

    /**
     * Returns whether $compound1 is a superselector of $compound2.
     *
     * That is, whether $compound1 matches every element that $compound2 matches, as well
     * as possibly additional elements.
     *
     * If $parents is passed, it represents the parents of $compound2. This is
     * relevant for pseudo selectors with selector arguments, where we may need to
     * know if the parent selectors in the selector argument match $parents.
     *
     * @param list<ComplexSelectorComponent>|null $parents
     */
    public static function compoundIsSuperselector(CompoundSelector $compound1, CompoundSelector $compound2, ?array $parents = null): bool
    {
        if (!$compound1->hasComplicatedSuperselectorSemantics() && !$compound2->hasComplicatedSuperselectorSemantics()) {
            if (\count($compound1->getComponents()) > \count($compound2->getComponents())) {
                return false;
            }

            return IterableUtil::every(
                $compound1->getComponents(),
                fn (SimpleSelector $simple1) => IterableUtil::any($compound2->getComponents(), $simple1->isSuperselector(...))
            );
        }

        // Pseudo elements effectively change the target of a compound selector rather
        // than narrowing the set of elements to which it applies like other
        // selectors. As such, if either selector has a pseudo element, they both must
        // have the _same_ pseudo element.
        //
        // In addition, order matters when pseudo-elements are involved. The selectors
        // before them must
        $tuple1 = self::findPseudoElementIndexed($compound1);
        $tuple2 = self::findPseudoElementIndexed($compound2);

        if ($tuple1 !== null && $tuple2 !== null) {
            return $tuple1[0]->isSuperselector($tuple2[0]) &&
                self::compoundComponentsIsSuperselector(
                    array_slice($compound1->getComponents(), 0, $tuple1[1]),
                    array_slice($compound2->getComponents(), 0, $tuple2[1]),
                    $parents
                ) &&
                self::compoundComponentsIsSuperselector(
                    array_slice($compound1->getComponents(), $tuple1[1] + 1),
                    array_slice($compound2->getComponents(), $tuple2[1] + 1),
                    $parents
                );
        } elseif ($tuple1 !== null || $tuple2 !== null) {
            return false;
        }

        // Every selector in `$compound1->getComponents()` must have a matching selector in
        // `$compound2->getComponents()`.
        foreach ($compound1->getComponents() as $simple1) {
            if ($simple1 instanceof PseudoSelector && $simple1->getSelector() !== null) {
                if (!self::selectorPseudoIsSuperselector($simple1, $compound2, $parents)) {
                    return false;
                }
            } else {
                foreach ($compound2->getComponents() as $simple2) {
                    if ($simple1->isSuperselector($simple2)) {
                        continue 2;
                    }
                }

                return false;
            }
        }

        return true;
    }

    /**
     * If $compound contains a pseudo-element, returns it and its index in
     * `$compound->getComponents()`.
     *
     * @return array{PseudoSelector, int}|null
     */
    private static function findPseudoElementIndexed(CompoundSelector $compound): ?array
    {
        foreach ($compound->getComponents() as $i => $simple) {
            if ($simple instanceof PseudoSelector && $simple->isElement()) {
                return [$simple, $i];
            }
        }

        return null;
    }

    /**
     * Like {@see compoundIsSuperselector} but operates on the underlying lists of
     * simple selectors.
     *
     * @param list<SimpleSelector>                $compound1
     * @param list<SimpleSelector>                $compound2
     * @param list<ComplexSelectorComponent>|null $parents
     */
    private static function compoundComponentsIsSuperselector(array $compound1, array $compound2, ?array $parents = null): bool
    {
        if (\count($compound1) === 0) {
            return true;
        }

        $bogusSpan = SpanUtil::bogusSpan();

        if (\count($compound2) === 0) {
            $compound2 = [new UniversalSelector($bogusSpan, '*')];
        }

        return self::compoundIsSuperselector(new CompoundSelector($compound1, $bogusSpan), new CompoundSelector($compound2, $bogusSpan), $parents);
    }

    /**
     * Returns whether $pseudo1 is a superselector of $compound2.
     *
     * That is, whether $pseudo1 matches every element that $compound2 matches, as well
     * as possibly additional elements.
     *
     * This assumes that $pseudo1's `selector` argument is not `null`.
     *
     * If $parents is passed, it represents the parents of $compound2. This is
     * relevant for pseudo selectors with selector arguments, where we may need to
     * know if the parent selectors in the selector argument match $parents.
     *
     * @param list<ComplexSelectorComponent>|null $parents
     */
    private static function selectorPseudoIsSuperselector(PseudoSelector $pseudo1, CompoundSelector $compound2, ?array $parents): bool
    {
        $selector1 = $pseudo1->getSelector();

        if ($selector1 === null) {
            throw new \InvalidArgumentException("Selector $pseudo1 must have a selector argument.");
        }

        switch ($pseudo1->getNormalizedName()) {
            case 'is':
            case 'matches':
            case 'any':
            case 'where':
                $selectors = self::selectorPseudoArgs($compound2, $pseudo1->getName());

                foreach ($selectors as $selector2) {
                    if ($selector1->isSuperselector($selector2)) {
                        return true;
                    }
                }

                $componentWithParents = $parents;
                $componentWithParents[] = new ComplexSelectorComponent($compound2, [], $compound2->getSpan());

                foreach ($selector1->getComponents() as $complex1) {
                    if (\count($complex1->getLeadingCombinators()) === 0 && self::complexIsSuperselector($complex1->getComponents(), $componentWithParents)) {
                        return true;
                    }
                }

                return false;

            case 'has':
            case 'host':
            case 'host-context':
                $selectors = self::selectorPseudoArgs($compound2, $pseudo1->getName());

                foreach ($selectors as $selector2) {
                    if ($selector1->isSuperselector($selector2)) {
                        return true;
                    }
                }

                return false;

            case 'slotted':
                $selectors = self::selectorPseudoArgs($compound2, $pseudo1->getName(), false);

                foreach ($selectors as $selector2) {
                    if ($selector1->isSuperselector($selector2)) {
                        return true;
                    }
                }

                return false;

            case 'not':
                foreach ($selector1->getComponents() as $complex) {
                    if ($complex->isBogus()) {
                        return false;
                    }

                    foreach ($compound2->getComponents() as $simple2) {
                        if ($simple2 instanceof TypeSelector) {
                            foreach ($complex->getLastComponent()->getSelector()->getComponents() as $simple1) {
                                if ($simple1 instanceof TypeSelector && !$simple1->equals($simple2)) {
                                    continue 3;
                                }
                            }
                        } elseif ($simple2 instanceof IDSelector) {
                            foreach ($complex->getLastComponent()->getSelector()->getComponents() as $simple1) {
                                if ($simple1 instanceof IDSelector && !$simple1->equals($simple2)) {
                                    continue 3;
                                }
                            }
                        } elseif ($simple2 instanceof PseudoSelector && $simple2->getName() === $pseudo1->getName()) {
                            $selector2 = $simple2->getSelector();
                            if ($selector2 === null) {
                                continue;
                            }

                            if (self::listIsSuperselector($selector2->getComponents(), [$complex])) {
                                continue 2;
                            }
                        }
                    }

                    return false;
                }

                return true;

            case 'current':
                $selectors = self::selectorPseudoArgs($compound2, $pseudo1->getName());

                foreach ($selectors as $selector2) {
                    if ($selector1->equals($selector2)) {
                        return true;
                    }
                }

                return false;

            case 'nth-child':
            case 'nth-last-child':
                foreach ($compound2->getComponents() as $pseudo2) {
                    if (!$pseudo2 instanceof PseudoSelector) {
                        continue;
                    }

                    if ($pseudo2->getName() !== $pseudo1->getName()) {
                        continue;
                    }

                    if ($pseudo2->getArgument() !== $pseudo1->getArgument()) {
                        continue;
                    }

                    $selector2 = $pseudo2->getSelector();

                    if ($selector2 === null) {
                        continue;
                    }

                    if ($selector1->isSuperselector($selector2)) {
                        return true;
                    }
                }

                return false;

            default:
                throw new \LogicException('unreachache');
        }
    }

    /**
     * Returns all the selector arguments of pseudo selectors in $compound with
     * the given $name.
     *
     * @return SelectorList[]
     */
    private static function selectorPseudoArgs(CompoundSelector $compound, string $name, bool $isClass = true): array
    {
        $selectors = [];

        foreach ($compound->getComponents() as $simple) {
            if (!$simple instanceof PseudoSelector) {
                continue;
            }

            if ($simple->isClass() !== $isClass || $simple->getName() !== $name) {
                continue;
            }

            if ($simple->getSelector() === null) {
                continue;
            }

            $selectors[] = $simple->getSelector();
        }

        return $selectors;
    }
}
