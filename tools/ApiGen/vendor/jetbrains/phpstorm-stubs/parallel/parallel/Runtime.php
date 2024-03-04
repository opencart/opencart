<?php

namespace parallel;

use Closure;

/**
 * Each runtime represents a single PHP thread, the thread is created (and bootstrapped) upon construction. The thread
 * then waits for tasks to be scheduled: Scheduled tasks will be executed FIFO and then the thread will resume waiting
 * until more tasks are scheduled, or it's closed, killed, or destroyed by the normal scoping rules of PHP objects.
 *
 * Warning: When a runtime is destroyed by the normal scoping rules of PHP objects, it will first execute all of the
 * tasks that were scheduled, and block while doing so.
 *
 * When a new runtime is created, it does not share code with the thread (or process) that created it. This means it
 * doesn't have the same classes and functions loaded, nor the same autoloader set. In some cases, a very lightweight
 * runtime is desirable because the tasks that will be scheduled do not need access to the code in the parent thread.
 * In those cases where the tasks do need to access the same code, it is enough to set an autoloader as the bootstrap.
 *
 * Note: preloading may be used in conjunction with parallel, in this case preloaded code is available without
 * bootstrapping
 */
final class Runtime
{
    /* Create */

    /**
     * Shall construct a new runtime without bootstrapping.
     * Shall construct a bootstrapped runtime.
     *
     * @param null|string $bootstrap The location of a bootstrap file, generally an autoloader.
     *
     * @throws Runtime\Error if thread could not be created
     * @throws Runtime\Error\Bootstrap if bootstrapping failed
     */
    public function __construct(?string $bootstrap = null) {}

    /* Execute */

    /**
     * Shall schedule task for execution in parallel, passing argv at execution time.
     *
     * @param Closure $task    A Closure with specific characteristics.
     * @param null|array $argv An array of arguments with specific characteristics to be passed to task at execution
     *                         time.
     *
     * ### Task Characteristics
     * -----------------------------------------------------------------------------------------------------------------
     * Closures scheduled for parallel execution must not:
     *  - accept or return by reference
     *  - accept or return internal objects (see notes)
     *  - execute a limited set of instructions
     *
     * Instructions prohibited in Closures intended for parallel execution are:
     *  - yield
     *  - use by-reference
     *  - declare class
     *  - declare named function
     *
     * Note: Nested closures may yield or use by-reference, but must not contain class or named function declarations.
     * Note: No instructions are prohibited in the files which the task may include.
     *
     * ### Arguments Characteristics
     * -----------------------------------------------------------------------------------------------------------------
     * Arguments must not:
     *  - contain references
     *  - contain resources
     *  - contain internal objects (see notes)
     *
     * Note: In the case of file stream resources, the resource will be cast to the file descriptor and passed as int
     *     where possible, this is unsupported on Windows
     *
     * ### Internal Objects Notes
     * -----------------------------------------------------------------------------------------------------------------
     * Internal objects generally use a custom structure which cannot be copied by value safely, PHP currently lacks
     *     the mechanics to do this (without serialization) and so only objects that do not use a custom structure may
     *     be shared.
     *
     * Some internal objects do not use a custom structure, for example @see \parallel\Events\Event and so may be
     *     shared. Closures are a special kind of internal object and support being copied by value, and so may be
     *     shared. Channels are central to writing parallel code and support concurrent access and execution by
     *     necessity, and so may be shared.
     *
     * Warning: A user class that extends an internal class may use a custom structure as defined by the internal
     *     class, in which case they cannot be copied by value safely, and so may not be shared.
     *
     * @return Future|null The return Future must not be ignored when the task contains a return or throw
     *     statement.
     *
     * @throws Runtime\Error\Closed if \parallel\Runtime was closed.
     * @throws Runtime\Error\IllegalFunction if task is a closure created from an internal function.
     * @throws Runtime\Error\IllegalInstruction if task contains illegal instructions.
     * @throws Runtime\Error\IllegalParameter if task accepts or argv contains illegal variables.
     * @throws Runtime\Error\IllegalReturn if task returns illegally.
     */
    public function run(Closure $task, ?array $argv = null): ?Future {}

    /* Join */

    /**
     * Shall request that the runtime shutsdown.
     * Note: Tasks scheduled for execution will be executed before the shutdown occurs.
     *
     * @throws Runtime\Error\Closed if Runtime was already closed.
     */
    public function close(): void {}

    /**
     * Shall attempt to force the runtime to shutdown.
     *
     * Note: Tasks scheduled for execution will not be executed, the currently running task shall be interrupted.
     * Warning: Internal function calls in progress cannot be interrupted.
     *
     * @throws Runtime\Error\Closed if Runtime was closed.
     */
    public function kill(): void {}
}
