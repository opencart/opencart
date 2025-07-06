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

namespace ScssPhp\ScssPhp\Logger;

use ScssPhp\ScssPhp\Deprecation;
use ScssPhp\ScssPhp\Exception\SassScriptException;
use ScssPhp\ScssPhp\Exception\SimpleSassException;
use ScssPhp\ScssPhp\Exception\SimpleSassRuntimeException;
use ScssPhp\ScssPhp\StackTrace\Trace;
use SourceSpan\FileSpan;
use SourceSpan\SourceSpan;

/**
 * A logger that wraps an inner logger to have special handling for
 * deprecation warnings, silencing, making fatal, enabling future, and/or
 * limiting repetition based on its inputs.
 *
 * @internal
 */
final class DeprecationProcessingLogger implements LoggerInterface
{
    private const MAX_REPETITIONS = 5;

    /**
     * A map of how many times each deprecation has been emitted by this logger.
     *
     * @var array<value-of<Deprecation>, int>
     */
    private array $warningCounts = [];

    /**
     * Deprecation warnings of these types will be ignored.
     *
     * @var Deprecation[]
     */
    private readonly array $silenceDeprecations;

    /**
     * Deprecation warnings of one of these types will cause an error to be
     * thrown.
     *
     * Future deprecations in this list will still cause an error even if they
     * are not also in {@see $futureDeprecations}.
     *
     * @var Deprecation[]
     */
    private readonly array $fatalDeprecations;

    /**
     * Future deprecations that the user has explicitly opted into.
     *
     * @var Deprecation[]
     */
    private readonly array $futureDeprecations;

    /**
     * @param Deprecation[] $silenceDeprecations
     * @param Deprecation[] $fatalDeprecations
     * @param Deprecation[] $futureDeprecations
     */
    public function __construct(
        private readonly LoggerInterface $inner,
        array $silenceDeprecations,
        array $fatalDeprecations,
        array $futureDeprecations,
        private readonly bool $limitRepetition = true,
    ) {
        $this->silenceDeprecations = $silenceDeprecations;
        $this->futureDeprecations = $futureDeprecations;
        $this->fatalDeprecations = $fatalDeprecations;
    }

    /**
     * Warns if any of the deprecations options are incompatible or unnecessary.
     */
    public function validate(): void
    {
        foreach ($this->fatalDeprecations as $deprecation) {
            if ($deprecation->isFuture() && !\in_array($deprecation, $this->futureDeprecations, true)) {
                $this->warn("Future $deprecation->value deprecation must be enabled before it can be made fatal.");
            } elseif ($deprecation->getObsoleteIn() !== null) {
                $this->warn("$deprecation->value deprecation is obsolete, so does not need to be made fatal.");
            } elseif (\in_array($deprecation, $this->silenceDeprecations, true)) {
                $this->warn("Ignoring setting to silence $deprecation->value deprecation, since it has also been made fatal.");
            }
        }

        foreach ($this->silenceDeprecations as $deprecation) {
            if ($deprecation === Deprecation::userAuthored) {
                $this->warn('User-authored deprecations should not be silenced.');
            } elseif ($deprecation->getObsoleteIn() !== null) {
                $this->warn("$deprecation->value deprecation is obsolete. If you were previously silencing it, your code may now behave in unexpected ways.");
            } elseif ($deprecation->isFuture() && \in_array($deprecation, $this->futureDeprecations, true)) {
                $this->warn("Conflicting options for future $deprecation->value deprecation cancel each other out.");
            } elseif ($deprecation->isFuture()) {
                $this->warn("Future $deprecation->value deprecation is not yet active, so silencing it is unnecessary.");
            }
        }

        foreach ($this->futureDeprecations as $deprecation) {
            if (!$deprecation->isFuture()) {
                $this->warn("$deprecation->value is not a future deprecation, so it does not need to be explicitly enabled.");
            }
        }
    }

    public function warn(string $message, ?Deprecation $deprecation = null, ?FileSpan $span = null, ?Trace $trace = null): void
    {
        if ($deprecation !== null) {
            $this->handleDeprecation($deprecation, $message, $span, $trace);
        } else {
            $this->inner->warn($message, $deprecation, $span, $trace);
        }
    }

    /**
     * Processes a deprecation warning.
     *
     * If $deprecation is in {@see $fatalDeprecations}, this shows an error.
     *
     * If it's a future deprecation that hasn't been opted into or it's a
     * deprecation that's already been warned for {@see self::MAX_REPETITIONS} times and
     * {@see limitRepetitions} is true, the warning is dropped.
     *
     * Otherwise, this is passed on to {@see warn}.
     */
    private function handleDeprecation(Deprecation $deprecation, string $message, ?FileSpan $span = null, ?Trace $trace = null): void
    {
        if ($deprecation->isFuture() && !\in_array($deprecation, $this->futureDeprecations, true)) {
            return;
        }

        if (\in_array($deprecation, $this->fatalDeprecations, true)) {
            $message .= "\n\nThis is only an error because you've set the {$deprecation->value} deprecation to be fatal.\nRemove this setting if you need to keep using this feature.";

            if ($span !== null && $trace !== null) {
                throw new SimpleSassRuntimeException($message, $span, $trace);
            }

            if ($span !== null) {
                throw new SimpleSassException($message, $span);
            }

            throw new SassScriptException($message);
        }

        if (\in_array($deprecation, $this->silenceDeprecations, true)) {
            return;
        }

        if ($this->limitRepetition) {
            $count = $this->warningCounts[$deprecation->value] = ($this->warningCounts[$deprecation->value] ?? 0) + 1;

            if ($count > self::MAX_REPETITIONS) {
                return;
            }
        }

        $this->inner->warn($message, $deprecation, $span, $trace);
    }

    public function debug(string $message, SourceSpan $span): void
    {
        $this->inner->debug($message, $span);
    }

    /**
     * Prints a warning indicating the number of deprecation warnings that were
     * omitted due to repetition.
     */
    public function summarize(): void
    {
        $total = 0;

        foreach ($this->warningCounts as $count) {
            if ($count > self::MAX_REPETITIONS) {
                $total += $count - self::MAX_REPETITIONS;
            }
        }

        if ($total > 0) {
            $this->inner->warn("$total repetitive deprecation warnings omitted.\nRun in verbose mode to see all warnings.");
        }
    }
}
