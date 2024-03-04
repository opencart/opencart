<?php

namespace parallel;

use Closure;

/**
 * Shall use the provided file to bootstrap all runtimes created for automatic scheduling via @see run().
 *
 * @param string $file
 *
 * @throws Runtime\Error\Bootstrap if previously called for this process.
 * @throws Runtime\Error\Bootstrap if called after @see run().
 */
function bootstrap(string $file): void {}

/**
 * @see Runtime::run() for more details
 * @link https://www.php.net/manual/en/parallel.run
 * @param Closure $task
 * @param array   $argv
 *
 * ### Automatic Scheduling
 * ---------------------------------------------------------------------------------------------------------------------
 * If a \parallel\Runtime internally created and cached by a previous call to parallel\run() is idle, it will be used
 *     to execute the task. If no \parallel\Runtime is idle parallel will create and cache a \parallel\Runtime.
 *
 * Note: \parallel\Runtime objects created by the programmer are not used for automatic scheduling.
 *
 * @return Future|null
 *
 * @throws Runtime\Error\Closed if \parallel\Runtime was closed.
 * @throws Runtime\Error\IllegalFunction if task is a closure created from an internal function.
 * @throws Runtime\Error\IllegalInstruction if task contains illegal instructions.
 * @throws Runtime\Error\IllegalParameter if task accepts or argv contains illegal variables.
 * @throws Runtime\Error\IllegalReturn if task returns illegally.
 */
function run(Closure $task, array $argv = null): ?Future {}

#ifdef ZEND_DEBUG
/**
 * @return int
 */
function count(): int {}
#endif
