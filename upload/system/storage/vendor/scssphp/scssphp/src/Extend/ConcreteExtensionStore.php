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

use ScssPhp\ScssPhp\Ast\Css\CssMediaQuery;
use ScssPhp\ScssPhp\Ast\Sass\Statement\ExtendRule;
use ScssPhp\ScssPhp\Ast\Selector\ComplexSelector;
use ScssPhp\ScssPhp\Ast\Selector\ComplexSelectorComponent;
use ScssPhp\ScssPhp\Ast\Selector\CompoundSelector;
use ScssPhp\ScssPhp\Ast\Selector\PlaceholderSelector;
use ScssPhp\ScssPhp\Ast\Selector\PseudoSelector;
use ScssPhp\ScssPhp\Ast\Selector\SelectorList;
use ScssPhp\ScssPhp\Ast\Selector\SimpleSelector;
use ScssPhp\ScssPhp\Exception\SassException;
use ScssPhp\ScssPhp\Exception\SassScriptException;
use ScssPhp\ScssPhp\Exception\SimpleSassException;
use ScssPhp\ScssPhp\Util;
use ScssPhp\ScssPhp\Util\Box;
use ScssPhp\ScssPhp\Util\EquatableUtil;
use ScssPhp\ScssPhp\Util\IterableUtil;
use ScssPhp\ScssPhp\Util\ListUtil;
use ScssPhp\ScssPhp\Util\ModifiableBox;
use SourceSpan\FileSpan;

class ConcreteExtensionStore implements ExtensionStore
{
    /**
     * A map from all simple selectors in the stylesheet to the selector lists
     * that contain them.
     *
     * This is used to find which selectors an `@extend` applies to and adjust
     * them.
     *
     * @var SimpleSelectorMap<ObjectSet<ModifiableBox<SelectorList>>>
     */
    private readonly SimpleSelectorMap $selectors;
    /**
     * A map from all extended simple selectors to the sources of those
     * extensions.
     *
     * @var SimpleSelectorMap<ComplexSelectorMap<Extension>>
     */
    private SimpleSelectorMap $extensions;
    /**
     * A map from all simple selectors in extenders to the extensions that those
     * extenders define.
     *
     * @var SimpleSelectorMap<list<Extension>>
     */
    private SimpleSelectorMap $extensionsByExtender;
    /**
     * A map from CSS selectors to the media query contexts they're defined in.
     *
     * This tracks the contexts in which each selector's style rule is defined.
     * If a rule is defined at the top level, it doesn't have an entry.
     *
     * @var \SplObjectStorage<ModifiableBox<SelectorList>, list<CssMediaQuery>>
     */
    private readonly \SplObjectStorage $mediaContexts;
    /**
     * @var \SplObjectStorage<SimpleSelector, int>
     */
    private \SplObjectStorage $sourceSpecificity;
    /**
     * @var \SplObjectStorage<ComplexSelector, mixed>
     */
    private readonly \SplObjectStorage $originals;
    private readonly ExtendMode $mode;

    /**
     * Extends $selector with $source extender and $targets extendees.
     *
     * This works as though `source {@extend target}` were written in the
     * stylesheet, with the exception that $target can contain compound
     * selectors which must be extended as a unit.
     */
    public static function extend(SelectorList $selector, SelectorList $source, SelectorList $targets, FileSpan $span): SelectorList
    {
        return self::extendOrReplace($selector, $source, $targets, ExtendMode::allTargets, $span);
    }

    /**
     * Returns a copy of $selector with $targets replaced by $source.
     */
    public static function replace(SelectorList $selector, SelectorList $source, SelectorList $targets, FileSpan $span): SelectorList
    {
        return self::extendOrReplace($selector, $source, $targets, ExtendMode::replace, $span);
    }

    /**
     * A helper function for {@see extend} and {@see replace}.
     */
    private static function extendOrReplace(SelectorList $selector, SelectorList $source, SelectorList $targets, ExtendMode $mode, FileSpan $span): SelectorList
    {
        $extender = ConcreteExtensionStore::createForMode($mode);

        if (!$selector->isInvisible()) {
            foreach ($selector->getComponents() as $component) {
                $extender->originals->attach($component);
            }
        }

        foreach ($targets->getComponents() as $complex) {
            $compound = $complex->getSingleCompound();

            if ($compound === null) {
                throw new SassScriptException("Can't extend complex selector $complex.");
            }

            $extensions = new SimpleSelectorMap();
            foreach ($compound->getComponents() as $simple) {
                $extensionMap = new ComplexSelectorMap();

                foreach ($source->getComponents() as $sourceComplex) {
                    $extensionMap[$sourceComplex] = new Extension($sourceComplex, $simple, $span, optional: true);
                }

                $extensions[$simple] = $extensionMap;
            }

            $selector = $extender->extendList($selector, $extensions);
        }

        return $selector;
    }

    /**
     * @param SimpleSelectorMap<ObjectSet<ModifiableBox<SelectorList>>> $selectors
     * @param SimpleSelectorMap<ComplexSelectorMap<Extension>> $extensions
     * @param SimpleSelectorMap<list<Extension>> $extensionsByExtender
     * @param \SplObjectStorage<ModifiableBox<SelectorList>, list<CssMediaQuery>> $mediaContexts
     * @param \SplObjectStorage<SimpleSelector, int> $sourceSpecificity
     * @param \SplObjectStorage<ComplexSelector, mixed> $originals
     */
    private function __construct(
        SimpleSelectorMap $selectors,
        SimpleSelectorMap $extensions,
        SimpleSelectorMap $extensionsByExtender,
        \SplObjectStorage $mediaContexts,
        \SplObjectStorage $sourceSpecificity,
        \SplObjectStorage $originals,
        ExtendMode $mode,
    ) {
        $this->selectors = $selectors;
        $this->extensions = $extensions;
        $this->extensionsByExtender = $extensionsByExtender;
        $this->mediaContexts = $mediaContexts;
        $this->sourceSpecificity = $sourceSpecificity;
        $this->originals = $originals;
        $this->mode = $mode;
    }

    public static function create(): self
    {
        return self::createForMode(ExtendMode::normal);
    }

    private static function createForMode(ExtendMode $mode): self
    {
        /** @var \SplObjectStorage<ModifiableBox<SelectorList>, list<CssMediaQuery>> $mediaContexts */
        $mediaContexts = new \SplObjectStorage();
        /** @var \SplObjectStorage<SimpleSelector, int> $sourceSpecificity */
        $sourceSpecificity = new \SplObjectStorage();
        /** @var \SplObjectStorage<ComplexSelector, mixed> $originals */
        $originals = new \SplObjectStorage();

        return new self(
            new SimpleSelectorMap(),
            new SimpleSelectorMap(),
            new SimpleSelectorMap(),
            $mediaContexts,
            $sourceSpecificity,
            $originals,
            $mode,
        );
    }

    public function isEmpty(): bool
    {
        return \count($this->extensions) === 0;
    }

    public function getSimpleSelectors(): array
    {
        return iterator_to_array($this->selectors);
    }

    public function extensionsWhereTarget(callable $callback): iterable
    {
        foreach ($this->extensions as $simple) {
            if (!$callback($simple)) {
                continue;
            }

            $sources = $this->extensions[$simple];

            foreach ($sources->getValues() as $extension) {
                if ($extension instanceof MergedExtension) {
                    foreach ($extension->unmerge() as $leafExtension) {
                        if (!$leafExtension->isOptional) {
                            yield $leafExtension;
                        }
                    }
                } elseif (!$extension->isOptional) {
                    yield $extension;
                }
            }
        }
    }

    public function addSelector(SelectorList $selector, ?array $mediaContext): Box
    {
        $originalSelector = $selector;

        if (!$originalSelector->isInvisible()) {
            foreach ($originalSelector->getComponents() as $component) {
                $this->originals->attach($component);
            }
        }

        if (\count($this->extensions) !== 0) {
            try {
                $selector = $this->extendList($originalSelector, $this->extensions, $mediaContext);
            } catch (SassException $e) {
                throw new SimpleSassException("From {$e->getSpan()->message('')}\n" . $e->getOriginalMessage(), $e->getSpan(), $e);
            }
        }

        $modifiableSelector = new ModifiableBox($selector);

        if ($mediaContext !== null) {
            $this->mediaContexts->attach($modifiableSelector, $mediaContext);
        }

        $this->registerSelector($selector, $modifiableSelector);

        return $modifiableSelector->seal();
    }

    /**
     * Registers the {@see SimpleSelector}s in $list to point to $selector in
     * {@see selectors}.
     *
     * @param ModifiableBox<SelectorList> $selector
     */
    private function registerSelector(SelectorList $list, ModifiableBox $selector): void
    {
        foreach ($list->getComponents() as $complex) {
            foreach ($complex->getComponents() as $component) {
                foreach ($component->getSelector()->getComponents() as $simple) {
                    if (!isset($this->selectors[$simple])) {
                        /** @var ObjectSet<ModifiableBox<SelectorList>> $set */
                        $set = new ObjectSet();
                        $this->selectors->attach($simple, $set);
                    }
                    $this->selectors[$simple]->add($selector);

                    if ($simple instanceof PseudoSelector && $simple->getSelector() !== null) {
                        $this->registerSelector($simple->getSelector(), $selector);
                    }
                }
            }
        }
    }

    public function addExtension(SelectorList $extender, SimpleSelector $target, ExtendRule $extend, ?array $mediaContext): void
    {
        $selectors = $this->selectors[$target] ?? null;
        $existingExtensions = $this->extensionsByExtender[$target] ?? null;

        $newExtensions = null;
        $sources = $this->extensions[$target] ??= new ComplexSelectorMap();

        foreach ($extender->getComponents() as $complex) {
            if ($complex->isUseless()) {
                continue;
            }

            $extension = new Extension($complex, $target, $extend->getSpan(), $mediaContext, $extend->isOptional());

            $existingExtension = $sources[$complex] ?? null;

            if ($existingExtension !== null) {
                // If there's already an extend from $extender to $target, we don't need
                // to re-run the extension. We may need to mark the extension as
                // mandatory, though.
                $sources[$complex] = MergedExtension::merge($existingExtension, $extension);
                continue;
            }

            $sources[$complex] = $extension;

            foreach ($this->simpleSelectors($complex) as $simple) {
                $extensionsByExtender = $this->extensionsByExtender[$simple] ?? [];
                $extensionsByExtender[] = $extension;
                $this->extensionsByExtender[$simple] = $extensionsByExtender;

                // Only source specificity for the original selector is relevant.
                // Selectors generated by `@extend` don't get new specificity.
                $this->sourceSpecificity[$simple] ??= $complex->getSpecificity();
            }

            if ($selectors !== null || $existingExtensions !== null) {
                /** @var ComplexSelectorMap<Extension> $newExtensions */
                $newExtensions ??= new ComplexSelectorMap();
                $newExtensions[$complex] = $extension;
            }
        }

        if ($newExtensions === null) {
            return;
        }

        /** @var SimpleSelectorMap<ComplexSelectorMap<Extension>> $newExtensionsByTarget */
        $newExtensionsByTarget = new SimpleSelectorMap();
        $newExtensionsByTarget[$target] = $newExtensions;

        if ($existingExtensions !== null) {
            // Reload the list of existing extensions as it is an array, not an object.
            $existingExtensions = $this->extensionsByExtender[$target];
            $additionalExtensions = $this->extendExistingExtensions($existingExtensions, $newExtensionsByTarget);

            if ($additionalExtensions !== null) {
                Util::mapAddAll2($newExtensionsByTarget, $additionalExtensions);
            }
        }

        if ($selectors !== null) {
            $this->extendExistingSelectors($selectors, $newExtensionsByTarget);
        }
    }

    /**
     * Returns an iterable of all simple selectors in $complex.
     *
     * @return iterable<SimpleSelector>
     */
    private function simpleSelectors(ComplexSelector $complex): iterable
    {
        foreach ($complex->getComponents() as $component) {
            foreach ($component->getSelector()->getComponents() as $simple) {
                yield $simple;

                if ($simple instanceof PseudoSelector && $simple->getSelector() !== null) {
                    foreach ($simple->getSelector()->getComponents() as $pseudoComplex) {
                        yield from $this->simpleSelectors($pseudoComplex);
                    }
                }
            }
        }
    }

    /**
     * Extend $extensions using $newExtensions.
     *
     * Note that this does duplicate some work done by
     * {@see extendExistingSelectors}, but it's necessary to expand each extension's
     * extender separately without reference to the full selector list, so that
     * relevant results don't get trimmed too early.
     *
     * Returns extensions that should be added to $newExtensions before
     * extending selectors in order to properly handle extension loops such as:
     *
     *     .c {x: y; @extend .a}
     *     .x.y.a {@extend .b}
     *     .z.b {@extend .c}
     *
     * Returns `null` if there are no extensions to add.
     *
     * @param list<Extension> $extensions
     * @param SimpleSelectorMap<ComplexSelectorMap<Extension>> $newExtensions
     * @return SimpleSelectorMap<ComplexSelectorMap<Extension>>|null
     */
    private function extendExistingExtensions(array $extensions, SimpleSelectorMap $newExtensions): ?SimpleSelectorMap
    {
        $additionalExtensions = null;

        foreach ($extensions as $extension) {
            $sources = $this->extensions[$extension->target];

            try {
                $selectors = $this->extendComplex($extension->extender->selector, $newExtensions, $extension->mediaContext);

                if ($selectors === null) {
                    continue;
                }
            } catch (SassException $e) {
                throw $e->withAdditionalSpan($extension->extender->selector->getSpan(), 'target selector', $e);
            }

            // If the output contains the original complex selector, there's no need
            // to recreate it.
            $containsExtension = EquatableUtil::equals($selectors[0], $extension->extender->selector);
            if ($containsExtension) {
                $selectors = array_slice($selectors, 1);
            }

            foreach ($selectors as $complex) {
                $withExtender = $extension->withExtender($complex);

                $existingExtension = $sources[$complex] ?? null;
                if ($existingExtension !== null) {
                    $sources[$complex] = MergedExtension::merge($existingExtension, $withExtender);
                } else {
                    $sources[$complex] = $withExtender;

                    foreach ($complex->getComponents() as $component) {
                        foreach ($component->getSelector()->getComponents() as $simple) {
                            $extensionsByExtender = $this->extensionsByExtender[$simple] ?? [];
                            $extensionsByExtender[] = $withExtender;
                            $this->extensionsByExtender[$simple] = $extensionsByExtender;
                        }
                    }

                    if ($newExtensions->contains($extension->target)) {
                        /** @var SimpleSelectorMap<ComplexSelectorMap<Extension>> $additionalExtensions */
                        $additionalExtensions ??= new SimpleSelectorMap();

                        if (!isset($additionalExtensions[$extension->target])) {
                            /** @var ComplexSelectorMap<Extension> $additionalSources */
                            $additionalSources = new ComplexSelectorMap();
                            $additionalExtensions[$extension->target] = $additionalSources;
                        } else {
                            $additionalSources = $additionalExtensions[$extension->target];
                        }

                        $additionalSources[$complex] = $withExtender;
                    }
                }
            }
        }

        return $additionalExtensions;
    }

    /**
     * Extend $selectors using $newExtensions.
     *
     * @param ObjectSet<ModifiableBox<SelectorList>> $selectors
     * @param SimpleSelectorMap<ComplexSelectorMap<Extension>> $newExtensions
     */
    private function extendExistingSelectors(ObjectSet $selectors, SimpleSelectorMap $newExtensions): void
    {
        foreach ($selectors as $selector) {
            $oldValue = $selector->getValue();

            try {
                $selector->setValue($this->extendList($selector->getValue(), $newExtensions, $this->mediaContexts[$selector] ?? null));
            } catch (SassException $e) {
                throw new SimpleSassException("From {$e->getSpan()->message('')}\n" . $e->getOriginalMessage(), $e->getSpan(), $e);
            }

            // If no extends actually happened (for example because unification
            // failed), we don't need to re-register the selector.
            if ($oldValue === $selector->getValue()) {
                continue;
            }
            $this->registerSelector($selector->getValue(), $selector);
        }
    }

    /**
     * @param iterable<ExtensionStore> $extensionStores
     */
    public function addExtensions(iterable $extensionStores): void
    {
        /** @var list<Extension>|null $extensionsToExtend */
        $extensionsToExtend = null;
        $selectorsToExtend = null;
        $newExtensions = null;

        foreach ($extensionStores as $extensionStore) {
            if ($extensionStore->isEmpty()) {
                continue;
            }
            \assert($extensionStore instanceof ConcreteExtensionStore);
            $this->sourceSpecificity->addAll($extensionStore->sourceSpecificity);

            foreach ($extensionStore->extensions as $target) {
                $newSources = $extensionStore->extensions->getInfo();

                // Private selectors can't be extended across module boundaries.
                if ($target instanceof PlaceholderSelector && $target->isPrivate()) {
                    continue;
                }

                $extensionsForTarget = $this->extensionsByExtender[$target] ?? null;
                if ($extensionsForTarget !== null) {
                    $extensionsToExtend ??= [];
                    array_push($extensionsToExtend, ...$extensionsForTarget);
                }

                // Find existing selectors to extend.
                $selectorsForTarget = $this->selectors[$target] ?? null;
                if ($selectorsForTarget !== null) {
                    if ($selectorsToExtend === null) {
                        /** @var ObjectSet<ModifiableBox<SelectorList>> $selectorsToExtend */
                        $selectorsToExtend = new ObjectSet();
                    }
                    $selectorsToExtend->addAll($selectorsForTarget);
                }

                $existingSources = $this->extensions[$target] ?? null;
                if ($existingSources !== null) {
                    foreach ($newSources as $extender) {
                        $extension = $newSources->getInfo();

                        if (isset($existingSources[$extender])) {
                            $extension = MergedExtension::merge($existingSources[$extender], $extension);
                        }
                        $existingSources[$extender] = $extension;

                        if ($extensionsForTarget !== null || $selectorsForTarget !== null) {
                            /** @var SimpleSelectorMap<ComplexSelectorMap<Extension>> $newExtensions */
                            $newExtensions ??= new SimpleSelectorMap();

                            if (!isset($newExtensions[$target])) {
                                /** @var ComplexSelectorMap<Extension> $newMap */
                                $newMap = new ComplexSelectorMap();
                                $newExtensions[$target] = $newMap;
                            }
                            $newExtensions[$target][$extender] = $extension;
                        }
                    }
                } else {
                    $this->extensions[$target] = clone $newSources;
                    if ($extensionsForTarget !== null || $selectorsForTarget !== null) {
                        /** @var SimpleSelectorMap<ComplexSelectorMap<Extension>> $newExtensions */
                        $newExtensions ??= new SimpleSelectorMap();
                        $newExtensions[$target] = clone $newSources;
                    }
                }
            }
        }

        if ($newExtensions !== null) {
            // We can ignore the return value here because it's only useful for extend
            // loops, which can't exist across module boundaries.
            if ($extensionsToExtend !== null) {
                $this->extendExistingExtensions($extensionsToExtend, $newExtensions);
            }

            if ($selectorsToExtend !== null) {
                $this->extendExistingSelectors($selectorsToExtend, $newExtensions);
            }
        }
    }

    /**
     * Extends $list using $extensions.
     *
     * @param SimpleSelectorMap<ComplexSelectorMap<Extension>> $extensions
     * @param list<CssMediaQuery>|null $mediaQueryContext
     */
    private function extendList(SelectorList $list, SimpleSelectorMap $extensions, ?array $mediaQueryContext = null): SelectorList
    {
        $extended = null;

        foreach ($list->getComponents() as $i => $complex) {
            $result = $this->extendComplex($complex, $extensions, $mediaQueryContext);

            \assert($result === null || \count($result) > 0, "extendComplex($complex) should return null rather than [] if extension fails.");

            if ($result === null) {
                if ($extended !== null) {
                    $extended[] = $complex;
                }
            } else {
                $extended ??= $i === 0 ? [] : array_slice($list->getComponents(), 0, $i);
                array_push($extended, ...$result);
            }
        }

        if ($extended === null) {
            return $list;
        }

        return new SelectorList($this->trim($extended, $this->originals->contains(...)), $list->getSpan());
    }

    /**
     * Extends $complex using $extensions, and returns the contents of a
     * {@see SelectorList}.
     *
     * @param SimpleSelectorMap<ComplexSelectorMap<Extension>> $extensions
     * @param list<CssMediaQuery>|null $mediaQueryContext
     * @return list<ComplexSelector>|null
     */
    private function extendComplex(ComplexSelector $complex, SimpleSelectorMap $extensions, ?array $mediaQueryContext): ?array
    {
        if (\count($complex->getLeadingCombinators()) > 1) {
            return null;
        }

        // The complex selectors that each compound selector in $complex->getComponents()
        // can expand to.
        //
        // For example, given
        //
        //     .a .b {...}
        //     .x .y {@extend .b}
        //
        // this will contain
        //
        //     [
        //       [.a],
        //       [.b, .x .y]
        //     ]
        //
        $extendedNotExpanded = null;
        $isOriginal = $this->originals->contains($complex);

        foreach ($complex->getComponents() as $i => $component) {
            $extended = $this->extendCompound($component, $extensions, $mediaQueryContext, $isOriginal);

            \assert($extended === null || \count($extended) > 0, "extendCompound($component) should return null rather than [] if extension fails.");

            if ($extended === null) {
                if ($extendedNotExpanded !== null) {
                    $extendedNotExpanded[] = [
                        new ComplexSelector(
                            [],
                            [$component],
                            $complex->getSpan(),
                            $complex->getLineBreak()
                        ),
                    ];
                }
            } elseif ($extendedNotExpanded !== null) {
                $extendedNotExpanded[] = $extended;
            } elseif ($i !== 0) {
                $extendedNotExpanded = [
                    [
                        new ComplexSelector(
                            $complex->getLeadingCombinators(),
                            array_slice($complex->getComponents(), 0, $i),
                            $complex->getSpan(),
                            $complex->getLineBreak(),
                        ),
                    ],
                    $extended,
                ];
            } elseif (\count($complex->getLeadingCombinators()) === 0) {
                $extendedNotExpanded = [$extended];
            } else {
                $newExtended = [];

                foreach ($extended as $newComplex) {
                    if (
                        \count($newComplex->getLeadingCombinators()) === 0
                        || EquatableUtil::listEquals($complex->getLeadingCombinators(), $newComplex->getLeadingCombinators())
                    ) {
                        $newExtended[] = new ComplexSelector(
                            $complex->getLeadingCombinators(),
                            $newComplex->getComponents(),
                            $complex->getSpan(),
                            $complex->getLineBreak() || $newComplex->getLineBreak(),
                        );
                    }
                }

                $extendedNotExpanded = [$newExtended];
            }
        }

        if ($extendedNotExpanded === null) {
            return null;
        }

        $first = true;

        return iterator_to_array(self::expandIterable(ExtendUtil::paths($extendedNotExpanded), function ($path) use (&$first, $complex) {
            return array_map(function (ComplexSelector $outputComplex) use (&$first, $complex) {
                // Make sure that copies of $complex retain their status as "original"
                // selectors. This includes selectors that are modified because a :not()
                // was extended into.
                if ($first && $this->originals->contains($complex)) {
                    $this->originals->attach($outputComplex);
                }

                $first = false;

                return $outputComplex;
            }, ExtendUtil::weave($path, $complex->getSpan(), $complex->getLineBreak()));
        }), false);
    }

    /**
     * Extends $component using $extensions, and returns the contents of a
     * {@see SelectorList}.
     *
     * The $inOriginal parameter indicates whether this is in an original
     * complex selector, meaning that the compound should not be trimmed out.
     *
     * @param SimpleSelectorMap<ComplexSelectorMap<Extension>> $extensions
     * @param list<CssMediaQuery>|null $mediaQueryContext
     * @return list<ComplexSelector>|null
     */
    private function extendCompound(ComplexSelectorComponent $component, SimpleSelectorMap $extensions, ?array $mediaQueryContext, bool $inOriginal): ?array
    {
        // If there's more than one target and they all need to match, we track
        // which targets are actually extended.
        $targetsUsed = $this->mode === ExtendMode::normal || \count($extensions) < 2 ? null : new SimpleSelectorMap();

        $simples = $component->getSelector()->getComponents();

        // The complex selectors produced from each simple selector in the compound selector.
        $options = null;

        foreach ($simples as $i => $simple) {
            $extended = $this->extendSimple($simple, $extensions, $mediaQueryContext, $targetsUsed);

            \assert($extended === null || \count($extended) > 0, "extendSimple($simple) should return null rather than [] if extension fails.");

            if ($extended === null) {
                if ($options !== null) {
                    $options[] = [$this->extenderForSimple($simple)];
                }
            } else {
                if ($options === null) {
                    $options = [];

                    if ($i !== 0) {
                        $options[] = [$this->extenderForCompound(array_slice($simples, 0, $i), $component->getSpan())];
                    }
                }

                array_push($options, ...$extended);
            }
        }

        if ($options === null) {
            return null;
        }

        /**
         * If {@see mode} isn't {@see ExtendMode::normal} and we didn't use all the targets in
         * $extensions, extension fails for $component.
         */
        if ($targetsUsed !== null && \count($targetsUsed) !== \count($extensions)) {
            return null;
        }

        // Optimize for the simple case of a single simple selector that doesn't
        // need any unification.
        if (\count($options) === 1) {
            $extenders = $options[0];
            $result = null;
            foreach ($extenders as $extender) {
                $extender->assertCompatibleMediaContext($mediaQueryContext);

                $complex = $extender->selector->withAdditionalCombinators($component->getCombinators());
                if ($complex->isUseless()) {
                    continue;
                }

                $result ??= [];
                $result[] = $complex;
            }

            return $result;
        }

        // Find all paths through $options. In this case, each path represents a
        // different unification of the base selector. For example, if we have:
        //
        //     .a.b {...}
        //     .w .x {@extend .a}
        //     .y .z {@extend .b}
        //
        // then $options is `[[.a, .w .x], [.b, .y .z]]` and `paths($options)` is
        //
        //     [
        //       [.a, .b],
        //       [.a, .y .z],
        //       [.w .x, .b],
        //       [.w .x, .y .z]
        //     ]
        //
        // We then unify each path to get a list of complex selectors:
        //
        //     [
        //       [.a.b],
        //       [.y .a.z],
        //       [.w .x.b],
        //       [.w .y .x.z, .y .w .x.z]
        //     ]
        //
        // And finally flatten them to get:
        //
        //     [
        //       .a.b,
        //       .y .a.z,
        //       .w .x.b,
        //       .w .y .x.z,
        //       .y .w .x.z
        //     ]
        $extenderPaths = ExtendUtil::paths($options);

        $result = [];

        if ($this->mode !== ExtendMode::replace) {
            // The first path is always the original selector. We can't just return
            // $component directly because selector pseudos may be modified, but we
            // don't have to do any unification.
            $result[] = new ComplexSelector([], [new ComplexSelectorComponent(
                new CompoundSelector(iterator_to_array(self::expandIterable($extenderPaths[0], function (Extender $extender) {
                    \assert(\count($extender->selector->getComponents()) === 1);
                    return ListUtil::last($extender->selector->getComponents())->getSelector()->getComponents();
                }), false), $component->getSelector()->getSpan()),
                $component->getCombinators(),
                $component->getSpan(),
            )], $component->getSpan());
        }

        foreach (array_slice($extenderPaths, $this->mode === ExtendMode::replace ? 0 : 1) as $path) {
            $extended = $this->unifyExtenders($path, $mediaQueryContext, $component->getSpan());

            if ($extended === null) {
                continue;
            }

            foreach ($extended as $complex) {
                $withCombinators = $complex->withAdditionalCombinators($component->getCombinators());

                if (!$withCombinators->isUseless()) {
                    $result[] = $withCombinators;
                }
            }
        }

        // If we're preserving the original selector, mark the first unification as
        // such so {@see trim} doesn't get rid of it.
        $isOriginal = fn (ComplexSelector $complex) => false;
        if ($inOriginal && $this->mode !== ExtendMode::replace) {
            $original = $result[0];
            $isOriginal = fn (ComplexSelector $complex) => EquatableUtil::equals($complex, $original);
        }

        return $this->trim($result, $isOriginal);
    }

    /**
     * Returns a list of {@see ComplexSelector}s that match the intersection of
     * elements matched by all of $extenders' selectors.
     *
     * The $span will be used for the new selectors.
     *
     * @param list<Extender> $extenders
     * @param list<CssMediaQuery>|null $mediaQueryContext
     * @return list<ComplexSelector>|null
     */
    private function unifyExtenders(array $extenders, ?array $mediaQueryContext, FileSpan $span): ?array
    {
        $toUnify = [];
        $originals = null;
        $originalsLineBreak = false;

        foreach ($extenders as $extender) {
            if ($extender->isOriginal) {
                $originals ??= [];
                $finalExtenderComponent = ListUtil::last($extender->selector->getComponents());
                \assert(\count($finalExtenderComponent->getCombinators()) === 0);

                foreach ($finalExtenderComponent->getSelector()->getComponents() as $component) {
                    $originals[] = $component;
                }
                $originalsLineBreak = $originalsLineBreak || $extender->selector->getLineBreak();
            } elseif ($extender->selector->isUseless()) {
                return null;
            } else {
                $toUnify[] = $extender->selector;
            }
        }

        if ($originals !== null) {
            array_unshift($toUnify, new ComplexSelector([], [
                new ComplexSelectorComponent(new CompoundSelector($originals, $span), [], $span),
            ], $span, $originalsLineBreak));
        }

        $complexes = ExtendUtil::unifyComplex($toUnify, $span);
        if ($complexes === null) {
            return null;
        }

        foreach ($extenders as $extender) {
            $extender->assertCompatibleMediaContext($mediaQueryContext);
        }

        return $complexes;
    }

    /**
     * @param SimpleSelectorMap<ComplexSelectorMap<Extension>> $extensions
     * @param list<CssMediaQuery>|null $mediaQueryContext
     * @param SimpleSelectorMap<mixed>|null $targetsUsed
     * @return list<list<Extender>>|null
     */
    private function extendSimple(SimpleSelector $simple, SimpleSelectorMap $extensions, ?array $mediaQueryContext, ?SimpleSelectorMap $targetsUsed): ?array
    {
        // Extends $simple without extending the contents of any selector pseudos
        // it contains.
        $withoutPseudo = function (SimpleSelector $simple) use ($extensions, $targetsUsed) {
            $extensionsForSimple = $extensions[$simple] ?? null;

            if ($extensionsForSimple === null) {
                return null;
            }
            $targetsUsed?->attach($simple);

            $result = [];

            if ($this->mode !== ExtendMode::replace) {
                $result[] = $this->extenderForSimple($simple);
            }

            /** @var Extension $extension */
            foreach ($extensionsForSimple->getValues() as $extension) {
                $result[] = $extension->extender;
            }

            return $result;
        };

        if ($simple instanceof PseudoSelector && $simple->getSelector() !== null) {
            $extended = $this->extendPseudo($simple, $extensions, $mediaQueryContext);

            if ($extended !== null) {
                return array_map(fn ($pseudo) => $withoutPseudo($pseudo) ?? [$this->extenderForSimple($pseudo)], $extended);
            }
        }

        $result = $withoutPseudo($simple);

        if ($result === null) {
            return null;
        }

        return [$result];
    }

    /**
     * Returns an {@see Extender} composed solely of a compound selector containing
     * $simples.
     *
     * @param list<SimpleSelector> $simples
     */
    private function extenderForCompound(array $simples, FileSpan $span): Extender
    {
        $compound = new CompoundSelector($simples, $span);

        return Extender::create(
            new ComplexSelector([], [new ComplexSelectorComponent($compound, [], $span)], $span),
            $this->sourceSpecificityFor($compound),
            true,
        );
    }

    /**
     * Returns an {@see Extender} composed solely of $simple.
     */
    private function extenderForSimple(SimpleSelector $simple): Extender
    {
        return Extender::create(
            new ComplexSelector([], [new ComplexSelectorComponent(
                new CompoundSelector([$simple], $simple->getSpan()),
                [],
                $simple->getSpan(),
            )], $simple->getSpan()),
            $this->sourceSpecificity[$simple] ?? 0,
            true,
        );
    }

    /**
     * Extends $pseudo using $extensions, and returns a list of resulting
     * pseudo selectors.
     *
     * This requires that $pseudo have a selector argument.
     *
     * @param SimpleSelectorMap<ComplexSelectorMap<Extension>> $extensions
     * @param list<CssMediaQuery>|null                         $mediaQueryContext
     * @return list<PseudoSelector>|null
     */
    private function extendPseudo(PseudoSelector $pseudo, SimpleSelectorMap $extensions, ?array $mediaQueryContext): ?array
    {
        $selector = $pseudo->getSelector();

        if ($selector === null) {
            throw new \InvalidArgumentException("Selector $pseudo must have a selector argument.");
        }

        $extended = $this->extendList($selector, $extensions, $mediaQueryContext);

        if ($extended === $selector) {
            return null;
        }

        // For `:not()`, we usually want to get rid of any complex selectors because
        // that will cause the selector to fail to parse on all browsers at time of
        // writing. We can keep them if either the original selector had a complex
        // selector, or the result of extending has only complex selectors, because
        // either way we aren't breaking anything that isn't already broken.
        $complexes = $extended->getComponents();
        if (
            $pseudo->getNormalizedName() === 'not'
            && !IterableUtil::any($selector->getComponents(), fn ($complex) => \count($complex->getComponents()) > 1)
            && IterableUtil::any($extended->getComponents(), fn ($complex) => \count($complex->getComponents()) === 1)
        ) {
            $complexes = array_filter($extended->getComponents(), fn ($complex) => \count($complex->getComponents()) <= 1);
        }

        $complexes = iterator_to_array(self::expandIterable($complexes, function (ComplexSelector $complex) use ($pseudo) {
            $innerPseudo = $complex->getSingleCompound()?->getSingleSimple();
            if (!$innerPseudo instanceof PseudoSelector) {
                return [$complex];
            }

            $innerSelector = $innerPseudo->getSelector();
            if ($innerSelector === null) {
                return [$complex];
            }

            switch ($pseudo->getNormalizedName()) {
                case 'not':
                    // In theory, if there's a `:not` nested within another `:not`, the
                    // inner `:not`'s contents should be unified with the return value.
                    // For example, if `:not(.foo)` extends `.bar`, `:not(.bar)` should
                    // become `.foo:not(.bar)`. However, this is a narrow edge case and
                    // supporting it properly would make this code and the code calling it
                    // a lot more complicated, so it's not supported for now.
                    if (!\in_array($innerPseudo->getNormalizedName(), ['is', 'matches', 'where'], true)) {
                        return [];
                    }

                    return $innerSelector->getComponents();

                case 'is':
                case 'matches':
                case 'where':
                case 'any':
                case 'current':
                case 'nth-child':
                case 'nth-last-child':
                    // As above, we could theoretically support :not within :matches, but
                    // doing so would require this method and its callers to handle much
                    // more complex cases that likely aren't worth the pain.
                    if ($innerPseudo->getName() !== $pseudo->getName()) {
                        return [];
                    }
                    if ($innerPseudo->getArgument() !== $pseudo->getArgument()) {
                        return [];
                    }

                    return $innerSelector->getComponents();

                case 'has':
                case 'host':
                case 'host-context':
                case 'slotted':
                    // We can't expand nested selectors here, because each layer adds an
                    // additional layer of semantics. For example, `:has(:has(img))`
                    // doesn't match `<div><img></div>` but `:has(img)` does.
                    return [$complex];

                default:
                    return [];
            }
        }), false);

        // Older browsers support `:not`, but only with a single complex selector.
        // In order to support those browsers, we break up the contents of a `:not`
        // unless it originally contained a selector list.
        if ($pseudo->getNormalizedName() === 'not' && \count($selector->getComponents()) === 1) {
            $result = array_map(fn (ComplexSelector $complex) => $pseudo->withSelector(new SelectorList([$complex], $selector->getSpan())), $complexes);

            return \count($result) === 0 ? null : $result;
        }

        return [$pseudo->withSelector(new SelectorList($complexes, $selector->getSpan()))];
    }

    /**
     * @template E
     * @template T
     * @param iterable<E> $elements
     * @param callable(E): iterable<T> $callback
     * @return \Traversable<T>
     *
     * @param-immediately-invoked-callable $callback
     */
    private static function expandIterable(iterable $elements, callable $callback): \Traversable
    {
        foreach ($elements as $element) {
            yield from $callback($element);
        }
    }

    /**
     * Removes elements from $selectors if they're subselectors of other
     * elements.
     *
     * The $isOriginal callback indicates which selectors are original to the
     * document, and thus should never be trimmed.
     *
     * @param list<ComplexSelector> $selectors
     * @param callable(ComplexSelector): bool $isOriginal
     * @return list<ComplexSelector>
     *
     * @param-immediately-invoked-callable $isOriginal
     */
    private function trim(array $selectors, callable $isOriginal): array
    {
        // This is n² on the sequences, but only comparing between separate
        // sequences should limit the quadratic behavior. We iterate from last to
        // first and reverse the result so that, if two selectors are identical, we
        // keep the first one.
        /** @var list<ComplexSelector> $result */
        $result = [];
        $numOriginals = 0;

        for ($i = \count($selectors) - 1; $i >= 0; $i--) {
            $complex1 = $selectors[$i];

            if ($isOriginal($complex1)) {
                // Make sure we don't include duplicate originals, which could happen if
                // a style rule extends a component of its own selector.
                for ($j = 0; $j < $numOriginals; $j++) {
                    if (EquatableUtil::equals($result[$j], $complex1)) {
                        // Rotates the slice one index higher
                        $element = $result[$j];
                        for ($k = 0; $k <= $j; $k++) {
                            $next = $result[$k];
                            $result[$k] = $element;
                            $element = $next;
                        }
                        // Rotating the slice preserves the list status of the array, but phpstan does not recognize it.
                        \assert(array_is_list($result));

                        continue 2;
                    }
                }

                $numOriginals++;
                array_unshift($result, $complex1);
                continue;
            }

            // The maximum specificity of the sources that caused $complex1 to be
            // generated. In order for $complex1 to be removed, there must be another
            // selector that's a superselector of it *and* that has specificity
            // greater or equal to this.
            $maxSpecificity = 0;
            foreach ($complex1->getComponents() as $component) {
                $maxSpecificity = max($maxSpecificity, $this->sourceSpecificityFor($component->getSelector()));
            }

            // Look in $result rather than $selectors for selectors after $i. This
            // ensures that we aren't comparing against a selector that's already been
            // trimmed, and thus that if there are two identical selectors only one is
            // trimmed.
            if (IterableUtil::any($result, fn (ComplexSelector $complex2) => $complex2->getSpecificity() >= $maxSpecificity && $complex2->isSuperselector($complex1))) {
                continue;
            }

            if (IterableUtil::any(array_slice($selectors, 0, $i), fn (ComplexSelector $complex2) => $complex2->getSpecificity() >= $maxSpecificity && $complex2->isSuperselector($complex1))) {
                continue;
            }

            array_unshift($result, $complex1);
        }

        return $result;
    }

    /**
     * Returns the maximum specificity for sources that went into producing
     * $compound.
     */
    private function sourceSpecificityFor(CompoundSelector $compound): int
    {
        $specificity = 0;

        foreach ($compound->getComponents() as $simple) {
            $specificity = max($specificity, $this->sourceSpecificity[$simple] ?? 0);
        }

        return $specificity;
    }

    public function clone(): array
    {
        /** @var SimpleSelectorMap<ObjectSet<ModifiableBox<SelectorList>>> $newSelectors */
        $newSelectors = new SimpleSelectorMap();
        /** @var \SplObjectStorage<ModifiableBox<SelectorList>, list<CssMediaQuery>> $newMediaContexts */
        $newMediaContexts = new \SplObjectStorage();
        /** @var \SplObjectStorage<SelectorList, Box<SelectorList>> $oldToNewSelectors */
        $oldToNewSelectors = new \SplObjectStorage();

        foreach ($this->selectors as $simple) {
            $selectors = $this->selectors->getInfo();

            /** @var ObjectSet<ModifiableBox<SelectorList>> $newSelectorSet */
            $newSelectorSet = new ObjectSet();
            $newSelectors[$simple] = $newSelectorSet;

            foreach ($selectors as $selector) {
                $newSelector = new ModifiableBox($selector->getValue());
                $newSelectorSet->add($newSelector);
                $oldToNewSelectors[$selector->getValue()] = $newSelector->seal();

                if (isset($this->mediaContexts[$selector])) {
                    $newMediaContexts[$newSelector] = $this->mediaContexts[$selector];
                }
            }
        }

        /** @var SimpleSelectorMap<ComplexSelectorMap<Extension>> $newExtensions */
        $newExtensions = new SimpleSelectorMap();
        foreach ($this->extensions as $simple) {
            $newExtensions[$simple] = clone $this->extensions->getInfo();
        }

        return [new ConcreteExtensionStore(
            $newSelectors,
            $newExtensions,
            clone $this->extensionsByExtender,
            $newMediaContexts,
            clone $this->sourceSpecificity,
            clone $this->originals,
            ExtendMode::normal,
        ), $oldToNewSelectors];
    }
}
