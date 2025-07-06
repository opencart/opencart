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

namespace ScssPhp\ScssPhp\Ast\Css;

use League\Uri\Contracts\UriInterface;
use ScssPhp\ScssPhp\Exception\SassFormatException;
use ScssPhp\ScssPhp\Logger\LoggerInterface;
use ScssPhp\ScssPhp\Parser\InterpolationMap;
use ScssPhp\ScssPhp\Parser\MediaQueryParser;
use ScssPhp\ScssPhp\Util\Equatable;

/**
 * A plain CSS media query, as used in `@media` and `@import`.
 *
 * @internal
 */
final class CssMediaQuery implements MediaQueryMergeResult, Equatable
{
    /**
     * The modifier, probably either "not" or "only".
     *
     * This may be `null` if no modifier is in use.
     */
    private readonly ?string $modifier;

    /**
     * The media type, for example "screen" or "print".
     *
     * This may be `null`. If so, {@see $conditions} will not be empty.
     */
    private readonly ?string $type;

    /**
     * Whether {@see $conditions} is a conjunction or a disjunction.
     *
     * In other words, if this is `true` this query matches when _all_
     * {@see $conditions} are met, and if it's `false` this query matches when _any_
     * condition in {@see $conditions} is met.
     *
     * If this is `false`, {@see $modifier} and {@see $type} will both be `null`.
     */
    private readonly bool $conjunction;

    /**
     * Media conditions, including parentheses.
     *
     * This is anything that can appear in the [`<media-in-parens>`] production.
     *
     * [`<media-in-parens>`]: https://drafts.csswg.org/mediaqueries-4/#typedef-media-in-parens
     *
     * @var list<string>
     */
    private readonly array $conditions;

    /**
     * Parses a media query from $contents.
     *
     * If passed, $url is the name of the file from which $contents comes.
     *
     * @return list<CssMediaQuery>
     *
     * @throws SassFormatException if parsing fails
     */
    public static function parseList(string $contents, ?LoggerInterface $logger = null, ?UriInterface $url = null, ?InterpolationMap $interpolationMap = null): array
    {
        return (new MediaQueryParser($contents, $logger, $url, $interpolationMap))->parse();
    }

    /**
     * @param list<string> $conditions
     */
    private function __construct(array $conditions = [], bool $conjunction = true, ?string $type = null, ?string $modifier = null)
    {
        $this->modifier = $modifier;
        $this->type = $type;
        $this->conditions = $conditions;
        $this->conjunction = $conjunction;
    }

    /**
     * Creates a media query specifies a type and, optionally, conditions.
     *
     * This always sets {@see $conjunction} to `true`.
     *
     * @param list<string> $conditions
     */
    public static function type(?string $type, ?string $modifier = null, array $conditions = []): CssMediaQuery
    {
        return new CssMediaQuery($conditions, true, $type, $modifier);
    }

    /**
     * Creates a media query that matches $conditions according to
     * $conjunction.
     *
     * The $conjunction argument may not be null if $conditions is longer than
     * a single element.
     *
     * @param list<string> $conditions
     */
    public static function condition(array $conditions, ?bool $conjunction = null): CssMediaQuery
    {
        if (\count($conditions) > 1 && $conjunction === null) {
            throw new \InvalidArgumentException('If conditions is longer than one element, conjunction may not be null.');
        }

        return new CssMediaQuery($conditions, $conjunction ?? true);
    }

    public function getModifier(): ?string
    {
        return $this->modifier;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function isConjunction(): bool
    {
        return $this->conjunction;
    }

    /**
     * @return list<string>
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * Whether this media query matches all media types.
     */
    public function matchesAllTypes(): bool
    {
        return $this->type === null || strtolower($this->type) === 'all';
    }

    /**
     * Merges this with $other to return a query that matches the intersection
     * of both inputs.
     */
    public function merge(CssMediaQuery $other): MediaQueryMergeResult
    {
        if (!$this->conjunction || !$other->conjunction) {
            return MediaQuerySingletonMergeResult::unrepresentable;
        }

        $ourModifier = $this->modifier !== null ? strtolower($this->modifier) : null;
        $ourType = $this->type !== null ? strtolower($this->type) : null;
        $theirModifier = $other->modifier !== null ? strtolower($other->modifier) : null;
        $theirType = $other->type !== null ? strtolower($other->type) : null;

        if ($ourType === null && $theirType === null) {
            return self::condition(array_merge($this->conditions, $other->conditions), true);
        }

        if (($ourModifier === 'not') !== ($theirModifier === 'not')) {
            if ($ourType === $theirType) {
                $negativeConditions = $ourModifier === 'not' ? $this->conditions : $other->conditions;
                $positiveConditions = $ourModifier === 'not' ? $other->conditions : $this->conditions;

                // If the negative conditions are a subset of the positive conditions, the
                // query is empty. For example, `not screen and (color)` has no
                // intersection with `screen and (color) and (grid)`.
                //
                // However, `not screen and (color)` *does* intersect with `screen and
                // (grid)`, because it means `not (screen and (color))` and so it allows
                // a screen with no color but with a grid.
                if (empty(array_diff($negativeConditions, $positiveConditions))) {
                    return MediaQuerySingletonMergeResult::empty;
                }

                return MediaQuerySingletonMergeResult::unrepresentable;
            }

            if ($this->matchesAllTypes() || $other->matchesAllTypes()) {
                return MediaQuerySingletonMergeResult::unrepresentable;
            }

            if ($ourModifier === 'not') {
                $modifier = $theirModifier;
                $type = $theirType;
                $conditions = $other->conditions;
            } else {
                $modifier = $ourModifier;
                $type = $ourType;
                $conditions = $this->conditions;
            }
        } elseif ($ourModifier === 'not') {
            // CSS has no way of representing "neither screen nor print".
            if ($ourType !== $theirType) {
                return MediaQuerySingletonMergeResult::unrepresentable;
            }

            $moreConditions = \count($this->conditions) > \count($other->conditions) ? $this->conditions : $other->conditions;
            $fewerConditions = \count($this->conditions) > \count($other->conditions) ? $other->conditions : $this->conditions;

            // If one set of features is a superset of the other, use those features
            // because they're strictly narrower.
            if (empty(array_diff($fewerConditions, $moreConditions))) {
                $modifier = $ourModifier; // "not"
                $type = $ourType;
                $conditions = $moreConditions;
            } else {
                // Otherwise, there's no way to represent the intersection.
                return MediaQuerySingletonMergeResult::unrepresentable;
            }
        } elseif ($this->matchesAllTypes()) {
            $modifier = $theirModifier;
            // Omit the type if either input query did, since that indicates that they
            // aren't targeting a browser that requires "all and".
            $type = $other->matchesAllTypes() && $ourType === null ? null : $theirType;
            $conditions = array_merge($this->conditions, $other->conditions);
        } elseif ($other->matchesAllTypes()) {
            $modifier = $ourModifier;
            $type = $ourType;
            $conditions = array_merge($this->conditions, $other->conditions);
        } elseif ($ourType !== $theirType) {
            return MediaQuerySingletonMergeResult::empty;
        } else {
            $modifier = $ourModifier ?? $theirModifier;
            $type = $ourType;
            $conditions = array_merge($this->conditions, $other->conditions);
        }

        return CssMediaQuery::type(
            $type === $ourType ? $this->type : $other->type,
            $modifier === $ourModifier ? $this->modifier : $other->modifier,
            $conditions
        );
    }

    public function equals(object $other): bool
    {
        return $other instanceof CssMediaQuery && $other->modifier === $this->modifier && $other->type === $this->type && $other->conditions === $this->conditions;
    }
}
