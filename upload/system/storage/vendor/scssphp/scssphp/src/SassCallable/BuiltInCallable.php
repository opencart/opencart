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

use League\Uri\Contracts\UriInterface;
use ScssPhp\ScssPhp\Ast\Sass\ArgumentDeclaration;
use ScssPhp\ScssPhp\Exception\SassFormatException;
use ScssPhp\ScssPhp\Value\SassNull;
use ScssPhp\ScssPhp\Value\Value;

/**
 * A callable defined in PHP code.
 *
 * Unlike user-defined callables, built-in callables support overloads. They
 * may declare multiple different callbacks with multiple different sets of
 * arguments. When the callable is invoked, the first callback with matching
 * arguments is invoked.
 *
 * @internal
 */
class BuiltInCallable implements SassCallable
{
    private readonly string $name;

    /**
     * @var list<array{ArgumentDeclaration, callable(list<Value>): Value}>
     */
    private readonly array $overloads;

    private readonly bool $acceptsContent;

    /**
     * Creates a function with a single $arguments declaration and a single
     * $callback.
     *
     * The argument declaration is parsed from $arguments, which should not
     * include parentheses. Throws a {@see SassFormatException} if parsing fails.
     *
     * If passed, $url is the URL of the module in which the function is
     * defined.
     *
     * @param callable(list<Value>): Value $callback
     *
     * @throws SassFormatException
     */
    public static function function(string $name, string $arguments, callable $callback, ?UriInterface $url = null): BuiltInCallable
    {
        return self::parsed(
            $name,
            ArgumentDeclaration::parse("@function $name($arguments) {", url: $url),
            $callback
        );
    }

    /**
     * Creates a mixin with a single $arguments declaration and a single
     * $callback.
     *
     * The argument declaration is parsed from $arguments, which should not
     * include parentheses. Throws a {@see SassFormatException} if parsing fails.
     *
     * If passed, $url is the URL of the module in which the mixin is
     * defined.
     *
     * @param callable(list<Value>): void $callback
     *
     * @throws SassFormatException
     */
    public static function mixin(string $name, string $arguments, callable $callback, ?UriInterface $url = null, bool $acceptsContent = false): BuiltInCallable
    {
        return self::parsed(
            $name,
            ArgumentDeclaration::parse("@mixin $name($arguments) {", url: $url),
            function ($arguments) use ($callback) {
                $callback($arguments);

                return SassNull::create();
            },
            $acceptsContent
        );
    }

    /**
     * Creates a function with multiple implementations.
     *
     * Each key/value pair in $overloads defines the argument declaration for
     * the overload (which should not include parentheses), and the callback to
     * execute if that argument declaration matches. Throws a
     * {@see SassFormatException} if parsing fails.
     *
     * If passed, $url is the URL of the module in which the function is
     * defined.
     *
     * @param array<string, callable(list<Value>): Value> $overloads
     *
     * @throws SassFormatException
     */
    public static function overloadedFunction(string $name, array $overloads, ?UriInterface $url = null): BuiltInCallable
    {
        $processedOverloads = [];

        foreach ($overloads as $args => $callback) {
            $processedOverloads[] = [
                ArgumentDeclaration::parse("@function $name($args) {", url: $url),
                $callback
            ];
        }

        return new BuiltInCallable($name, $processedOverloads, false);
    }

    /**
     * Creates a callable with a single $arguments declaration and a single $callback.
     *
     * @param callable(list<Value>): Value $callback
     */
    private static function parsed(string $name, ArgumentDeclaration $arguments, callable $callback, bool $acceptsContent = false): BuiltInCallable
    {
        return new BuiltInCallable($name, [[$arguments, $callback]], $acceptsContent);
    }

    /**
     * @param list<array{ArgumentDeclaration, callable(list<Value>): Value}> $overloads
     */
    private function __construct(string $name, array $overloads, bool $acceptsContent)
    {
        $this->name = $name;
        $this->overloads = $overloads;
        $this->acceptsContent = $acceptsContent;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function acceptsContent(): bool
    {
        return $this->acceptsContent;
    }

    /**
     * Returns the argument declaration and PHP callback for the given
     * positional and named arguments.
     *
     * If no exact match is found, finds the closest approximation. Note that this
     * doesn't guarantee that $positional and $names are valid for the returned
     * {@see ArgumentDeclaration}.
     *
     * @param array<string, mixed> $names Only the keys are relevant
     *
     * @return array{ArgumentDeclaration, callable(list<Value>): Value}
     */
    public function callbackFor(int $positional, array $names): array
    {
        $fuzzyMatch = null;
        $minMismatchDistance = null;

        foreach ($this->overloads as $overload) {
            // Ideally, find an exact match.
            if ($overload[0]->matches($positional, $names)) {
                return $overload;
            }

            $mismatchDistance = \count($overload[0]->getArguments()) - $positional;

            if ($minMismatchDistance !== null) {
                if (abs($mismatchDistance) > $minMismatchDistance) {
                    continue;
                }

                // If two overloads have the same mismatch distance, favor the overload
                // that has more arguments.
                if (abs($mismatchDistance) === abs($minMismatchDistance) && $mismatchDistance < 0) {
                    continue;
                }
            }

            $minMismatchDistance = $mismatchDistance;
            $fuzzyMatch = $overload;
        }

        if ($fuzzyMatch !== null) {
            return $fuzzyMatch;
        }

        throw new \LogicException("BuiltInCallable {$this->name} may not have empty overloads.");
    }
}
