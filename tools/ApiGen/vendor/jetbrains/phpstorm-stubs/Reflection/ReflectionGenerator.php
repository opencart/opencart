<?php

use JetBrains\PhpStorm\Internal\TentativeType;
use JetBrains\PhpStorm\Pure;

/**
 * The ReflectionGenerator class reports information about a generator.
 *
 * @since 7.0
 */
class ReflectionGenerator
{
    /**
     * Constructs a ReflectionGenerator object
     *
     * @link https://php.net/manual/en/reflectiongenerator.construct.php
     * @param Generator $generator A generator object.
     * @since 7.0
     */
    public function __construct(Generator $generator) {}

    /**
     * Gets the currently executing line of the generator
     *
     * @link https://php.net/manual/en/reflectiongenerator.getexecutingline.php
     * @return int Returns the line number of the currently executing statement
     * in the generator.
     * @since 7.0
     */
    #[Pure]
    #[TentativeType]
    public function getExecutingLine(): int {}

    /**
     * Gets the file name of the currently executing generator
     *
     * @link https://php.net/manual/en/reflectiongenerator.getexecutingfile.php
     * @return string Returns the full path and file name of the currently
     * executing generator.
     * @since 7.0
     */
    #[Pure]
    #[TentativeType]
    public function getExecutingFile(): string {}

    /**
     * Gets the trace of the executing generator
     *
     * @link https://php.net/manual/en/reflectiongenerator.gettrace.php
     * @param int $options The value of <em>options</em> can be any of the following the following flags.
     *
     * Available options:
     *
     * {@see DEBUG_BACKTRACE_PROVIDE_OBJECT} - Default
     *
     * {@see DEBUG_BACKTRACE_IGNORE_ARGS} - Don't include the argument
     * information for functions in the stack trace.
     *
     * @return array Returns the trace of the currently executing generator.
     * @since 7.0
     */
    #[Pure]
    #[TentativeType]
    public function getTrace(int $options = DEBUG_BACKTRACE_PROVIDE_OBJECT): array {}

    /**
     * Gets the function name of the generator
     *
     * @link https://php.net/manual/en/reflectiongenerator.getfunction.php
     * @return ReflectionFunctionAbstract Returns a {@see ReflectionFunctionAbstract}
     * class. This will be {@see ReflectionFunction} for functions,
     * or {@see ReflectionMethod} for methods.
     * @since 7.0
     */
    #[Pure]
    #[TentativeType]
    public function getFunction(): ReflectionFunctionAbstract {}

    /**
     * Gets the function name of the generator
     *
     * @link https://php.net/manual/en/reflectiongenerator.getthis.php
     * @return object|null Returns the $this value, or {@see null} if the
     * generator was not created in a class context.
     * @since 7.0
     */
    #[Pure]
    #[TentativeType]
    public function getThis(): ?object {}

    /**
     * Gets the executing Generator object
     *
     * @link https://php.net/manual/en/reflectiongenerator.construct.php
     * @return Generator Returns the currently executing Generator object.
     * @since 7.0
     */
    #[Pure]
    #[TentativeType]
    public function getExecutingGenerator(): Generator {}
}
