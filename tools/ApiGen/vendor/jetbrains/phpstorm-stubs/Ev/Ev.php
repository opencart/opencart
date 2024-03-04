<?php

use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

/**
 * Ev is a singleton providing access to the default loop and to some common operations.
 */
final class Ev
{
    /**
     * Flag passed to create a loop: The default flags value
     */
    public const FLAG_AUTO = 0;

    /**
     * Flag passed to create a loop: If this flag used(or the program runs setuid or setgid), libev won't look at the
     * environment variable LIBEV_FLAGS. Otherwise(by default), LIBEV_FLAGS will override the flags completely if it is
     * found. Useful for performance tests and searching for bugs.
     */
    public const FLAG_NOENV = 16777216;

    /**
     * Flag passed to create a loop: Makes libev check for a fork in each iteration, instead of calling EvLoop::fork()
     * manually. This works by calling getpid() on every iteration of the loop, and thus this might slow down the event
     * loop with lots of loop iterations, but usually is not noticeable. This flag setting cannot be overridden or
     * specified in the LIBEV_FLAGS environment variable.
     */
    public const FLAG_FORKCHECK = 33554432;

    /**
     * Flag passed to create a loop: When this flag is specified, libev won't attempt to use the inotify API for its
     * ev_stat watchers. The flag can be useful to conserve inotify file descriptors, as otherwise each loop using
     * ev_stat watchers consumes one inotify handle.
     */
    public const FLAG_NOINOTIFY = 1048576;

    /**
     * Flag passed to create a loop: When this flag is specified, libev will attempt to use the signalfd API for its
     * ev_signal (and ev_child ) watchers. This API delivers signals synchronously, which makes it both faster and might
     * make it possible to get the queued signal data. It can also simplify signal handling with threads, as long as
     * signals are properly blocked in threads. Signalfd will not be used by default.
     */
    public const FLAG_SIGNALFD = 2097152;

    /**
     * Flag passed to create a loop: When this flag is specified, libev will avoid to modify the signal mask.
     * Specifically, this means having to make sure signals are unblocked before receiving them.
     *
     * This behaviour is useful for custom signal handling, or handling signals only in specific threads.
     */
    public const FLAG_NOSIGMASK = 4194304;

    /**
     * Flag passed to Ev::run() or EvLoop::run(): Means that event loop will look for new events, will handle those
     * events and any already outstanding ones, but will not wait and block the process in case there are no events and
     * will return after one iteration of the loop. This is sometimes useful to poll and handle new events while doing
     * lengthy calculations, to keep the program responsive.
     */
    public const RUN_NOWAIT = 1;

    /**
     * Flag passed to Ev::run() or EvLoop::run(): Means that event loop will look for new events (waiting if necessary)
     * and will handle those and any already outstanding ones. It will block the process until at least one new event
     * arrives (which could be an event internal to libev itself, so there is no guarantee that a user-registered
     * callback will be called), and will return after one iteration of the loop.
     */
    public const RUN_ONCE = 2;

    /**
     * Flag passed to Ev::stop() or EvLoop::stop(): Cancel the break operation.
     */
    public const BREAK_CANCEL = 0;

    /**
     * Flag passed to Ev::stop() or EvLoop::stop(): Makes the innermost Ev::run() or EvLoop::run() call return.
     */
    public const BREAK_ONE = 1;

    /**
     * Flag passed to Ev::stop() or EvLoop::stop(): Makes all nested Ev::run() or EvLoop::run() calls return.
     */
    public const BREAK_ALL = 2;

    /**
     * Lowest allowed watcher priority.
     */
    public const MINPRI = -2;

    /**
     * Highest allowed watcher priority.
     */
    public const MAXPRI = 2;

    /**
     * Event bitmask: The file descriptor in the EvIo watcher has become readable.
     */
    public const READ = 1;

    /**
     * Event bitmask: The file descriptor in the EvIo watcher has become writable.
     */
    public const WRITE = 2;

    /**
     * Event bitmask: EvTimer watcher has been timed out.
     */
    public const TIMER = 256;

    /**
     * Event bitmask: EvPeriodic watcher has been timed out.
     */
    public const PERIODIC = 512;

    /**
     * Event bitmask: A signal specified in EvSignal::__construct() has been received.
     */
    public const SIGNAL = 1024;

    /**
     * Event bitmask: The pid specified in EvChild::__construct() has received a status change.
     */
    public const CHILD = 2048;

    /**
     * Event bitmask: The path specified in EvStat watcher changed its attributes.
     */
    public const STAT = 4096;

    /**
     * Event bitmask: EvIdle watcher works when there is nothing to do with other watchers.
     */
    public const IDLE = 8192;

    /**
     * Event bitmask: All EvPrepare watchers are invoked just before Ev::run() starts. Thus, EvPrepare watchers are the
     * last watchers invoked before the event loop sleeps or polls for new events.
     */
    public const PREPARE = 16384;

    /**
     * Event bitmask: All EvCheck watchers are queued just after Ev::run() has gathered the new events, but before it
     * queues any callbacks for any received events. Thus, EvCheck watchers will be invoked before any other watchers
     * of the same or lower priority within an event loop iteration.
     */
    public const CHECK = 32768;

    /**
     * Event bitmask: The embedded event loop specified in the EvEmbed watcher needs attention.
     */
    public const EMBED = 65536;

    /**
     * Event bitmask: Not ever sent(or otherwise used) by libev itself, but can be freely used by libev users to signal
     * watchers (e.g. via EvWatcher::feed() ).
     */
    public const CUSTOM = 16777216;

    /**
     * Event bitmask: An unspecified error has occurred, the watcher has been stopped. This might happen because the
     * watcher could not be properly started because libev ran out of memory, a file descriptor was found to be closed
     * or any other problem. Libev considers these application bugs.
     */
    public const ERROR = -2147483648;

    /**
     * select(2) backend
     */
    public const BACKEND_SELECT = 1;

    /**
     * poll(2) backend
     */
    public const BACKEND_POLL = 2;

    /**
     * Linux-specific epoll(7) backend for both pre- and post-2.6.9 kernels
     */
    public const BACKEND_EPOLL = 4;

    /**
     * kqueue backend used on most BSD systems. EvEmbed watcher could be used to embed one loop(with kqueue backend)
     * into another. For instance, one can try to create an event loop with kqueue backend and use it for sockets only.
     */
    public const BACKEND_KQUEUE = 8;

    /**
     * Solaris 8 backend. This is not implemented yet.
     */
    public const BACKEND_DEVPOLL = 16;

    /**
     * Solaris 10 event port mechanism with a good scaling.
     */
    public const BACKEND_PORT = 32;

    /**
     * Try all backends(even currupted ones). It's not recommended to use it explicitly. Bitwise operators should be
     * applied here(e.g. Ev::BACKEND_ALL & ~ Ev::BACKEND_KQUEUE ) Use Ev::recommendedBackends() , or don't specify any
     * backends at all.
     */
    public const BACKEND_ALL = 255;

    /**
     * Not a backend, but a mask to select all backend bits from flags value to mask out any backends(e.g. when
     * modifying the LIBEV_FLAGS environment variable).
     */
    public const BACKEND_MASK = 65535;

    /* Methods */

    /**
     * Returns an integer describing the backend used by libev.
     *
     * Returns an integer describing the backend used by libev. See Ev::BACKEND_* flags
     *
     * @return int Bit mask describing the backend used by libev, see Ev::BACKEND_* flags.
     */
    final public static function backend() {}

    /**
     * Returns recursion depth
     *
     * The number of times Ev::run() was entered minus the number of times Ev::run() was exited normally, in other
     * words, the recursion depth. Outside Ev::run() , this number is 0 . In a callback, this number is 1 , unless
     * Ev::run() was invoked recursively (or from another thread), in which case it is higher.
     *
     * @return int Recursion depth of the default loop.
     */
    final public static function depth() {}

    /**
     * Returns the set of backends that are embeddable in other event loops.
     *
     * @return int Bit mask which can contain Ev::BACKEND_* flags combined using bitwise OR operator.
     */
    final public static function embeddableBackends() {}

    /**
     * Feed signal event into Ev
     *
     * Simulates a signal receive. It is safe to call this function at any time, from any context, including signal
     * handlers or random threads. Its main use is to customise signal handling in the process.
     *
     * Unlike Ev::feedSignalEvent() , this works regardless of which loop has registered the signal.
     *
     * @param int $signum Signal number. See signal(7) man page for details. You can use constants exported by pcntl
     *      extension.
     */
    final public static function feedSignal(int $signum) {}

    /**
     * Feed signal event into the default loop
     *
     * Feed signal event into the default loop. Ev will react to this call as if the signal specified by signal had
     * occurred.
     *
     * @param int $signum Signal number. See signal(7) man page for details. See also constants exported by pcntl
     *      extension.
     */
    final public static function feedSignalEvent(int $signum) {}

    /**
     * Return the number of times the default event loop has polled for new events.
     *
     * Return the number of times the event loop has polled for new events. Sometimes useful as a generation counter.
     *
     * @return int Number of polls of the default event loop.
     */
    final public static function iteration() {}

    /**
     * Returns the time when the last iteration of the default event loop has started.
     *
     * Returns the time when the last iteration of the default event loop has started. This is the time that timers
     * (EvTimer and EvPeriodic) are based on, and referring to it is usually faster then calling Ev::time().
     *
     * @return float Number of seconds(fractional) representing the time when the last iteration of the default event
     *      loop has started.
     */
    final public static function now() {}

    /**
     * Establishes the current time by querying the kernel, updating the time returned by Ev::now in the progress.
     *
     * Establishes the current time by querying the kernel, updating the time returned by Ev::now() in the progress.
     * This is a costly operation and is usually done automatically within Ev::run().
     *
     * This method is rarely useful, but when some event callback runs for a very long time without entering the event
     * loop, updating libev's consideration of the current time is a good idea.
     */
    final public static function nowUpdate() {}

    /**
     * Returns a bit mask of recommended backends for current platform.
     *
     * Returns the set of all backends compiled into this binary of libev and also recommended for this platform,
     * meaning it will work for most file descriptor types. This set is often smaller than the one returned by
     * Ev::supportedBackends(), as for example kqueue is broken on most BSD systems and will not be auto-detected
     * unless it is requested explicitly. This is the set of backends that libev will probe with no backends specified
     * explicitly.
     *
     * @return int Bit mask which can contain Ev::BACKEND_* flags combined using bitwise OR operator.
     */
    final public static function recommendedBackends() {}

    /**
     * Resume previously suspended default event loop.
     *
     * Ev::suspend() and Ev::resume() methods suspend and resume a loop correspondingly.
     *
     * All timer watchers will be delayed by the time spend between suspend and resume , and all periodic watchers will
     * be rescheduled(that is, they will lose any events that would have occurred while suspended).
     *
     * After calling Ev::suspend() it is not allowed to call any function on the given loop other than Ev::resume().
     * Also it is not allowed to call Ev::resume() without a previous call to Ev::suspend().
     *
     * Calling suspend / resume has the side effect of updating the event loop time (see Ev::nowUpdate()).
     */
    final public static function resume() {}

    /**
     * Begin checking for events and calling callbacks for the default loop.
     *
     * Begin checking for events and calling callbacks for the default loop . Returns when a callback calls Ev::stop()
     * method, or the flags are nonzero(in which case the return value is true) or when there are no active watchers
     * which reference the loop( EvWatcher::keepalive() is TRUE), in which case the return value will be FALSE. The
     * return value can generally be interpreted as if TRUE, there is more work left to do.
     *
     * @param int $flags One of the Ev::FLAG_* flags
     */
    final public static function run(int $flags = self::FLAG_AUTO) {}

    /**
     * Block the process for the given number of seconds.
     *
     * @param float $seconds Fractional number of seconds
     */
    final public static function sleep(float $seconds) {}

    /**
     * Stops the default event loop
     *
     * @param int $how One of the Ev::BREAK_* constants
     */
    final public static function stop(int $how = self::BREAK_ONE) {}

    /**
     * Returns the set of backends supported by current libev configuration.
     *
     * @return int Bit mask which can contain Ev::BACKEND_* flags combined using bitwise OR operator.
     */
    final public static function supportedBackends() {}

    /**
     * Suspend the default event loop.
     *
     * Ev::suspend() and Ev::resume() methods suspend and resume the default loop correspondingly.
     *
     * All timer watchers will be delayed by the time spend between suspend and resume , and all periodic watchers will
     * be rescheduled(that is, they will lose any events that would have occurred while suspended).
     *
     * After calling Ev::suspend() it is not allowed to call any function on the given loop other than Ev::resume().
     * Also it is not allowed to call Ev::resume() without a previous call to Ev::suspend().
     */
    final public static function suspend() {}

    /**
     * Returns the current time in fractional seconds since the epoch.
     *
     * Returns the current time in fractional seconds since the epoch. Consider using Ev::now()
     *
     * @return float The current time in fractional seconds since the epoch.
     */
    final public static function time() {}

    /**
     * Performs internal consistency checks (for debugging).
     *
     * Performs internal consistency checks (for debugging libev) and abort the program if any data structures were
     * found to be corrupted.
     */
    final public static function verify() {}
}

/**
 * Class EvWatcher
 */
abstract class EvWatcher
{
    /**
     * @var bool TRUE if the watcher is active. FALSE otherwise.
     */
    #[Immutable]
    public $is_active;

    /**
     * @var bool TRUE if the watcher is pending, i.e. it has outstanding events, but its callback
     *      has not yet been invoked. FALSE otherwise. As long, as a watcher is pending (but not active), one must not
     *      change its priority.
     */
    #[Immutable]
    public $is_pending;

    /**
     * Abstract constructor of a watcher object
     */
    abstract public function __construct();

    /**
     * @var mixed Custom user data associated with the watcher
     */
    public $data;

    /**
     * @var int Number between Ev::MINPRi and Ev::MAXPRI. Pending watchers with higher priority will be invoked before
     *      watchers with lower priority, but priority will not keep watchers from being executed (except for EvIdle
     *      watchers). EvIdle watchers provide functionality to suppress invocation when higher priority events are
     *      pending.
     */
    public $priority;

    /**
     * Clear watcher pending status.
     *
     * If the watcher is pending, this method clears its pending status and returns its revents bitset (as if its
     * callback was invoked). If the watcher isn't pending it does nothing and returns 0.
     *
     * Sometimes it can be useful to "poll" a watcher instead of waiting for its callback to be invoked, which can be
     * accomplished with this function.
     *
     * @return int In case if the watcher is pending, returns revents bitset as if the watcher callback had been
     *      invoked. Otherwise returns 0 .
     */
    public function clear() {}

    /**
     * Feeds the given revents set into the event loop.
     *
     * Feeds the given revents set into the event loop, as if the specified event had happened for the watcher.
     *
     * @param int $revents Bit mask of watcher received events.
     */
    public function feed(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $revents) {}

    /**
     * Returns the loop responsible for the watcher.
     *
     * @return EvLoop Event loop object responsible for the watcher.
     */
    public function getLoop() {}

    /**
     * Invokes the watcher callback with the given received events bit mask.
     *
     * @param int $revents Bit mask of watcher received events.
     */
    public function invoke(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $revents) {}

    /**
     * Configures whether to keep the loop from returning.
     *
     * Configures whether to keep the loop from returning. With keepalive value set to FALSE the watcher won't keep
     * Ev::run() / EvLoop::run() from returning even though the watcher is active.
     *
     * Watchers have keepalive value TRUE by default.
     *
     * Clearing keepalive status is useful when returning from Ev::run() / EvLoop::run() just because of the watcher
     * is undesirable. It could be a long running UDP socket watcher or so.
     *
     * @param bool $value With keepalive value set to FALSE the watcher won't keep Ev::run() / EvLoop::run() from
     *      returning even though the watcher is active.
     */
    public function keepalive(#[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $value = true) {}

    /**
     * Sets new callback for the watcher.
     *
     * @param callable $callback void callback ([ object $watcher = NULL [, int $revents = NULL ]] )
     */
    public function setCallback(#[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $callback) {}

    /**
     * Starts the watcher.
     *
     * Marks the watcher as active. Note that only active watchers will receive events.
     */
    public function start() {}

    /**
     * Stops the watcher.
     *
     * Marks the watcher as inactive. Note that only active watchers will receive events.
     */
    public function stop() {}
}

/**
 * Class EvCheck
 *
 * EvPrepare and EvCheck watchers are usually used in pairs. EvPrepare watchers get invoked before the process blocks,
 * EvCheck afterwards.
 *
 * It is not allowed to call EvLoop::run() or similar methods or functions that enter the current event loop from either
 * EvPrepare or EvCheck watchers. Other loops than the current one are fine, however. The rationale behind this is that
 * one don't need to check for recursion in those watchers, i.e. the sequence will always be: EvPrepare -> blocking ->
 * EvCheck , so having a watcher of each kind they will always be called in pairs bracketing the blocking call.
 *
 * The main purpose is to integrate other event mechanisms into libev and their use is somewhat advanced. They could be
 * used, for example, to track variable changes, implement custom watchers, integrate net-snmp or a coroutine library
 * and lots more. They are also occasionally useful to cache some data and want to flush it before blocking.
 *
 * It is recommended to give EvCheck watchers highest( Ev::MAXPRI ) priority, to ensure that they are being run before
 * any other watchers after the poll (this doesn’t matter for EvPrepare watchers).
 *
 * Also, EvCheck watchers should not activate/feed events. While libev fully supports this, they might get executed
 * before other EvCheck watchers did their job.
 */
final class EvCheck extends EvWatcher
{
    /**
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $callback,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $data = null,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $priority = 0
    ) {}

    /**
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     * @return EvCheck
     */
    final public static function createStopped(mixed $callback, mixed $data = null, int $priority = 0) {}
}

/**
 * Class EvChild
 *
 * EvChild watchers trigger when the process receives a SIGCHLD in response to some child status changes (most typically
 * when a child dies or exits). It is permissible to install an EvChild watcher after the child has been forked (which
 * implies it might have already exited), as long as the event loop isn't entered(or is continued from a watcher), i.e.
 * forking and then immediately registering a watcher for the child is fine, but forking and registering a watcher a few
 * event loop iterations later or in the next callback invocation is not.
 *
 * It is allowed to register EvChild watchers in the default loop only.
 */
final class EvChild extends EvWatcher
{
    /**
     * @var int The process ID this watcher watches out for, or 0, meaning any process ID.
     */
    #[Immutable]
    public $pid;

    /**
     * @var int The process ID that detected a status change.
     */
    #[Immutable]
    public $rpid;

    /**
     * @var int The process exit status caused by rpid.
     */
    #[Immutable]
    public $rstatus;

    /**
     * Constructs the EvChild watcher object.
     *
     * Call the callback when a status change for process ID pid (or any PID if pid is 0) has been received (a status
     * change happens when the process terminates or is killed, or, when trace is TRUE, additionally when it is stopped
     * or continued). In other words, when the process receives a SIGCHLD, Ev will fetch the outstanding exit/wait
     * status for all changed/zombie children and call the callback.
     *
     * It is valid to install a child watcher after an EvChild has exited but before the event loop has started its next
     * iteration. For example, first one calls fork , then the new child process might exit, and only then an EvChild
     * watcher is installed in the parent for the new PID .
     *
     * You can access both exit/tracing status and pid by using the rstatus and rpid properties of the watcher object.
     *
     * The number of PID watchers per PID is unlimited. All of them will be called.
     *
     * The EvChild::createStopped() method doesn't start(activate) the newly created watcher.
     *
     * @param int $pid  Wait for status changes of process PID(or any process if PID is specified as 0 ).
     * @param bool $trace If FALSE, only activate the watcher when the process terminates. Otherwise(TRUE) additionally
     *      activate the watcher when the process is stopped or continued.
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $pid,
        #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $trace,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $callback,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $data = null,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $priority = 0
    ) {}

    /**
     * Create instance of a stopped EvCheck watcher.
     *
     * The same as EvChild::__construct() , but doesn't start the watcher automatically.
     *
     * @param int $pid  Wait for status changes of process PID(or any process if PID is specified as 0 ).
     * @param bool $trace If FALSE, only activate the watcher when the process terminates. Otherwise(TRUE) additionally
     *      activate the watcher when the process is stopped or continued.
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     *
     * @return EvChild
     */
    final public static function createStopped(int $pid, bool $trace, mixed $callback, mixed $data = null, int $priority = 0) {}

    /**
     * Configures the watcher
     *
     * @param int $pid  Wait for status changes of process PID(or any process if PID is specified as 0 ).
     * @param bool $trace If FALSE, only activate the watcher when the process terminates. Otherwise(TRUE) additionally
     *      activate the watcher when the process is stopped or continued.
     */
    public function set(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $pid,
        #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $trace
    ) {}
}

/**
 * Class EvEmbed
 *
 * Used to embed one event loop into another.
 */
final class EvEmbed extends EvWatcher
{
    /**
     * @var EvLoop The embedded loop
     */
    #[Immutable]
    public $embed;

    /**
     * Constructs the EvEmbed object.
     *
     * This is a rather advanced watcher type that lets to embed one event loop into another(currently only IO events
     * are supported in the embedded loop, other types of watchers might be handled in a delayed or incorrect fashion
     * and must not be used).
     *
     * See the libev documentation for details.
     *
     * This watcher is most useful on BSD systems without working kqueue to still be able to handle a large number of
     * sockets.
     *
     * @param EvLoop $other The loop to embed, this loop must be embeddable(see Ev::embeddableBackends()).
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     */
    public function __construct(
        EvLoop $other,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $callback,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $data = null,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $priority = 0
    ) {}

    /**
     * Configures the watcher.
     *
     * @param EvLoop $other The loop to embed, this loop must be embeddable(see Ev::embeddableBackends()).
     */
    public function set(EvLoop $other) {}

    /**
     * Make a single, non-blocking sweep over the embedded loop.
     */
    public function sweep() {}

    /**
     * Create stopped EvEmbed watcher object
     *
     * The same as EvEmbed::__construct() , but doesn't start the watcher automatically.
     *
     * @param EvLoop $other The loop to embed, this loop must be embeddable(see Ev::embeddableBackends()).
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     *
     * @return EvEmbed
     */
    final public static function createStopped(EvLoop $other, mixed $callback, mixed $data = null, int $priority = 0) {}
}

/**
 * Class EvIo
 *
 * EvIo watchers check whether a file descriptor(or socket, or a stream castable to numeric file descriptor) is readable
 * or writable in each iteration of the event loop, or, more precisely, when reading would not block the process and
 * writing would at least be able to write some data. This behaviour is called level-triggering because events are kept
 * receiving as long as the condition persists. To stop receiving events just stop the watcher.
 *
 * The number of read and/or write event watchers per fd is unlimited. Setting all file descriptors to non-blocking mode
 * is also usually a good idea (but not required).
 *
 * Another thing to watch out for is that it is quite easy to receive false readiness notifications, i.e. the callback
 * might be called with Ev::READ but a subsequent read() will actually block because there is no data. It is very easy
 * to get into this situation. Thus it is best to always use non-blocking I/O: An extra read() returning EAGAIN (or
 * similar) is far preferable to a program hanging until some data arrives.
 *
 * If for some reason it is impossible to run the fd in non-blocking mode, then separately re-test whether a file
 * descriptor is really ready. Some people additionally use SIGALRM and an interval timer, just to be sure they won't
 * block infinitely.
 *
 * Always consider using non-blocking mode.
 */
final class EvIo extends EvWatcher
{
    /**
     * @var resource A stream opened with fopen() or similar functions, numeric file descriptor, or socket.
     */
    #[Immutable]
    public $fd;

    /**
     * @var int Ev::READ and/or Ev::WRITE. See the bit masks.
     */
    #[Immutable]
    #[ExpectedValues(flags: [Ev::READ, Ev::WRITE])]
    public $events;

    /**
     * Constructs EvIo watcher object.
     *
     * Constructs EvIo watcher object and starts the watcher automatically.
     *
     * @param resource $fd  A stream opened with fopen() or similar functions, numeric file descriptor, or socket.
     * @param int $events Ev::READ and/or Ev::WRITE. See the bit masks.
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $fd,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $events,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $callback,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $data = null,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $priority = 0
    ) {}

    /**
     * Configures the watcher.
     *
     * @param resource $fd  A stream opened with fopen() or similar functions, numeric file descriptor, or socket.
     * @param int $events Ev::READ and/or Ev::WRITE. See the bit masks.
     */
    public function set(
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $fd,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $events
    ) {}

    /**
     * Create stopped EvIo watcher object.
     *
     * The same as EvIo::__construct() , but doesn't start the watcher automatically.
     *
     * @param resource $fd  A stream opened with fopen() or similar functions, numeric file descriptor, or socket.
     * @param int $events Ev::READ and/or Ev::WRITE. See the bit masks.
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     *
     * @return EvIo
     */
    final public static function createStopped(mixed $fd, int $events, mixed $callback, mixed $data = null, int $priority = 0) {}
}

/**
 * Class EvPeriodic
 *
 * Periodic watchers are also timers of a kind, but they are very versatile.
 *
 * Unlike EvTimer, EvPeriodic watchers are not based on real time (or relative time, the physical time that passes) but
 * on wall clock time (absolute time, calendar or clock). The difference is that wall clock time can run faster or
 * slower than real time, and time jumps are not uncommon (e.g. when adjusting it).
 *
 * EvPeriodic watcher can be configured to trigger after some specific point in time. For example, if an EvPeriodic
 * watcher is configured to trigger "in 10 seconds" (e.g. EvLoop::now() + 10.0 , i.e. an absolute time, not a delay),
 * and the system clock is reset to January of the previous year , then it will take a year or more to trigger the event
 * (unlike an EvTimer , which would still trigger roughly 10 seconds after starting it as it uses a relative timeout).
 *
 * As with timers, the callback is guaranteed to be invoked only when the point in time where it is supposed to trigger
 * has passed. If multiple timers become ready during the same loop iteration then the ones with earlier time-out values
 * are invoked before ones with later time-out values (but this is no longer true when a callback calls EvLoop::run()
 * recursively).
 */
final class EvPeriodic extends EvWatcher
{
    /**
     * @var float When repeating, this contains the offset value, otherwise this is the absolute point in time (the
     *      offset value passed to EvPeriodic::set(), although libev might modify this value for better numerical
     *      stability).
     */
    public $offset;

    /**
     * @var float The current interval value. Can be modified any time, but changes only take effect when the periodic
     *      timer fires or EvPeriodic::again() is being called.
     */
    public $interval;

    /**
     * Constructs EvPeriodic watcher object.
     *
     * Constructs EvPeriodic watcher object and starts it automatically. EvPeriodic::createStopped() method creates
     * stopped periodic watcher.
     *
     * @param float $offset When repeating, this contains the offset value, otherwise this is the absolute point in
     *      time (the offset value passed to EvPeriodic::set(), although libev might modify this value for better
     *      numerical stability).
     * @param float $interval The current interval value. Can be modified any time, but changes only take effect when
     *      the periodic timer fires or EvPeriodic::again() is being called.
     * @param null|callable $reschedule_cb If set, tt must return the next time to trigger, based on the passed time value
     *      (that is, the lowest time value larger than or equal to the second argument). It will usually be called just
     *      before the callback will be triggered, but might be called at other times, too.
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'float'], default: '')] $offset,
        #[LanguageLevelTypeAware(['8.0' => 'float'], default: '')] $interval,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $reschedule_cb,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $callback,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $data = null,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $priority = 0
    ) {}

    /**
     * Simply stops and restarts the periodic watcher again.
     *
     * Simply stops and restarts the periodic watcher again. This is only useful when attributes are changed.
     *
     * @return void
     */
    public function again() {}

    /**
     * Returns the absolute time that this watcher is supposed to trigger next.
     *
     * When the watcher is active, returns the absolute time that this watcher is supposed to trigger next. This is not
     * the same as the offset argument to EvPeriodic::set() or EvPeriodic::__construct(), but indeed works even in
     * interval mode.
     *
     * @return float Rhe absolute time this watcher is supposed to trigger next in seconds.
     */
    public function at() {}

    /**
     * Create a stopped EvPeriodic watcher
     *
     * Create EvPeriodic object. Unlike EvPeriodic::__construct() this method doesn't start the watcher automatically.
     *
     * @param float $offset When repeating, this contains the offset value, otherwise this is the absolute point in
     *      time (the offset value passed to EvPeriodic::set(), although libev might modify this value for better
     *      numerical stability).
     * @param float $interval The current interval value. Can be modified any time, but changes only take effect when
     *      the periodic timer fires or EvPeriodic::again() is being called.
     * @param null|callable $reschedule_cb If set, tt must return the next time to trigger, based on the passed time value
     *      (that is, the lowest time value larger than or equal to the second argument). It will usually be called just
     *      before the callback will be triggered, but might be called at other times, too.
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     *
     * @return EvPeriodic
     */
    final public static function createStopped(float $offset, float $interval, mixed $reschedule_cb, mixed $callback, mixed $data = null, int $priority = 0) {}

    /**
     * Configures the watcher
     * @param float $offset The same meaning as for {@see EvPeriodic::__construct}
     * @param float $interval The same meaning as for {@see EvPeriodic::__construct}
     * @param null|callable $reschedule_cb The same meaning as for {@see EvPeriodic::__construct}
     * @return void
     */
    public function set(
        #[LanguageLevelTypeAware(['8.0' => 'float'], default: '')] $offset,
        #[LanguageLevelTypeAware(['8.0' => 'float'], default: '')] $interval,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $reschedule_cb = null
    ) {}
}

/**
 * Class EvPrepare
 *
 * EvPrepare and EvCheck watchers are usually used in pairs. EvPrepare watchers get invoked before the process blocks,
 * EvCheck afterwards.
 *
 * It is not allowed to call EvLoop::run() or similar methods or functions that enter the current event loop from either
 * EvPrepare or EvCheck watchers. Other loops than the current one are fine, however. The rationale behind this is that
 * one don't need to check for recursion in those watchers, i.e. the sequence will always be: EvPrepare -> blocking ->
 * EvCheck, so having a watcher of each kind they will always be called in pairs bracketing the blocking call.
 *
 * The main purpose is to integrate other event mechanisms into libev and their use is somewhat advanced. They could be
 * used, for example, to track variable changes, implement custom watchers, integrate net-snmp or a coroutine library
 * and lots more. They are also occasionally useful to cache some data and want to flush it before blocking.
 *
 * It is recommended to give EvCheck watchers highest (Ev::MAXPRI) priority, to ensure that they are being run before
 * any other watchers after the poll (this doesn’t matter for EvPrepare watchers).
 *
 * Also, EvCheck watchers should not activate/feed events. While libev fully supports this, they might get executed
 * before other EvCheck watchers did their job.
 */
final class EvPrepare extends EvWatcher
{
    /**
     * Constructs EvPrepare watcher object.
     *
     * Constructs EvPrepare watcher object and starts the watcher automatically. If you need a stopped watcher, consider
     * using EvPrepare::createStopped().
     *
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $callback,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $data = null,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $priority = 0
    ) {}

    /**
     * Creates a stopped instance of EvPrepare watcher.
     *
     * Creates a stopped instance of EvPrepare watcher. Unlike EvPrepare::__construct(), this method doesn't start the
     * watcher automatically.
     *
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     *
     * @return EvPrepare
     */
    final public static function createStopped(mixed $callback, mixed $data = null, int $priority = 0) {}
}

/**
 * Class EvSignal
 *
 * EvSignal watchers will trigger an event when the process receives a specific signal one or more times. Even though
 * signals are very asynchronous, libev will try its best to deliver signals synchronously, i.e. as part of the normal
 * event processing, like any other event.
 *
 * There is no limit for the number of watchers for the same signal, but only within the same loop, i.e. one can watch
 * for SIGINT in the default loop and for SIGIO in another loop, but it is not allowed to watch for SIGINT in both the
 * default loop and another loop at the same time. At the moment, SIGCHLD is permanently tied to the default loop.
 *
 * If possible and supported, libev will install its handlers with SA_RESTART (or equivalent) behaviour enabled, so
 * system calls should not be unduly interrupted. In case of a problem with system calls getting interrupted by signals,
 * all the signals can be blocked in an EvCheck watcher and unblocked in a EvPrepare watcher.
 */
final class EvSignal extends EvWatcher
{
    /**
     * @var int Signal number. See the constants exported by pcntl extension. See also signal(7) man page.
     */
    #[Immutable]
    public $signum;

    /**
     * Constructs EvSignal watcher object
     *
     * @param int $signum Signal number. See the constants exported by pcntl extension. See also signal(7) man page.
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $signum,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $callback,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $data = null,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $priority = 0
    ) {}

    /**
     * Configures the watcher.
     *
     * @param int $signum Signal number. See the constants exported by pcntl extension. See also signal(7) man page.
     */
    public function set(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $signum) {}

    /**
     * Creates a stopped instance of EvSignal watcher.
     *
     * Creates a stopped instance of EvSignal watcher. Unlike EvPrepare::__construct(), this method doesn't start the
     * watcher automatically.
     *
     * @param int $signum Signal number. See the constants exported by pcntl extension. See also signal(7) man page.
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     *
     * @return EvSignal
     */
    final public static function createStopped(int $signum, mixed $callback, mixed $data = null, int $priority = 0) {}
}

/**
 * Class EvStat
 *
 * EvStat monitors a file system path for attribute changes. It calls stat() on that path in regular intervals (or when
 * the OS signals it changed) and sees if it changed compared to the last time, invoking the callback if it did.
 *
 * The path does not need to exist: changing from "path exists" to "path does not exist" is a status change like any
 * other. The condition "path does not exist" is signified by the 'nlink' item being 0 (returned by EvStat::attr()
 * method).
 *
 * The path must not end in a slash or contain special components such as '.' or '..'. The path should be absolute: if
 * it is relative and the working directory changes, then the behaviour is undefined.
 *
 * Since there is no portable change notification interface available, the portable implementation simply calls stat()
 * regularly on the path to see if it changed somehow. For this case a recommended polling interval can be specified. If
 * one specifies a polling interval of 0.0 (highly recommended) then a suitable, unspecified default value will be used
 * (which could be expected to be around 5 seconds, although this might change dynamically). libev will also impose a
 * minimum interval which is currently around 0.1 , but that’s usually overkill.
 *
 * This watcher type is not meant for massive numbers of EvStat watchers, as even with OS-supported change
 * notifications, this can be resource-intensive.
 */
final class EvStat extends EvWatcher
{
    /**
     * @var float  Hint on how quickly a change is expected to be detected and should normally be
     *      specified as 0.0 to let libev choose a suitable value.
     */
    #[Immutable]
    public $interval;

    /**
     * @var string The path to wait for status changes on.
     */
    #[Immutable]
    public $path;

    /**
     * Constructs EvStat watcher object.
     *
     * Constructs EvStat watcher object and starts the watcher automatically.
     *
     * @param string $path The path to wait for status changes on.
     * @param float $interval Hint on how quickly a change is expected to be detected and should normally be specified
     *      as 0.0 to let libev choose a suitable value.
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $path,
        #[LanguageLevelTypeAware(['8.0' => 'float'], default: '')] $interval,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $callback,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $data = null,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $priority = 0
    ) {}

    /**
     * @return array The values most recently detect by Ev (without actual stat'ing). See stat(2) man page for details.
     */
    public function attr() {}

    /**
     * @return array Just like EvStat::attr() , but returns the previous set of values.
     */
    public function prev() {}

    /**
     * Configures the watcher.
     *
     * @param string $path The path to wait for status changes on.
     * @param float $interval Hint on how quickly a change is expected to be detected and should normally be specified
     *      as 0.0 to let libev choose a suitable value.
     */
    public function set(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $path,
        #[LanguageLevelTypeAware(['8.0' => 'float'], default: '')] $interval
    ) {}

    /**
     * Initiates the stat call.
     *
     * Initiates the stat call(updates internal cache). It stats (using lstat) the path specified in the watcher and
     * sets the internal cache to the values found.
     *
     * @return bool TRUE if path exists. Otherwise FALSE.
     */
    public function stat() {}

    /**
     * Create a stopped EvStat watcher object.
     *
     * Creates EvStat watcher object, but doesn't start it automatically (unlike EvStat::__construct()).
     *
     * @param string $path The path to wait for status changes on.
     * @param float $interval Hint on how quickly a change is expected to be detected and should normally be specified
     *      as 0.0 to let libev choose a suitable value.
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     *
     * @return EvStat
     */
    final public static function createStopped(string $path, float $interval, mixed $callback, mixed $data = null, int $priority = 0) {}
}

/**
 * Class EvTimer
 *
 * EvTimer watchers are simple relative timers that generate an event after a given time, and optionally repeating in
 * regular intervals after that.
 *
 * The timers are based on real time, that is, if one registers an event that times out after an hour and resets the
 * system clock to January last year, it will still time out after( roughly) one hour. "Roughly" because detecting time
 * jumps is hard, and some inaccuracies are unavoidable.
 *
 * The callback is guaranteed to be invoked only after its timeout has passed (not at, so on systems with very
 * low-resolution clocks this might introduce a small delay). If multiple timers become ready during the same loop
 * iteration then the ones with earlier time-out values are invoked before ones of the same priority with later time-out
 * values (but this is no longer true when a callback calls EvLoop::run() recursively).
 *
 * The timer itself will do a best-effort at avoiding drift, that is, if a timer is configured to trigger every 10
 * seconds, then it will normally trigger at exactly 10 second intervals. If, however, the script cannot keep up with
 * the timer (because it takes longer than those 10 seconds to do) the timer will not fire more than once per event loop
 * iteration.
 */
final class EvTimer extends EvWatcher
{
    /**
     * @var float If repeat is 0.0, then it will automatically be stopped once the timeout is reached. If it is
     *      positive, then the timer will automatically be configured to trigger again every repeat seconds later, until
     *      stopped manually.
     */
    public $repeat;

    /**
     * @var float The remaining time until a timer fires. If the timer is active, then this time is relative to the
     *      current event loop time, otherwise it's the timeout value currently configured.
     *
     *      That is, after instantiating an EvTimer with an after value of 5.0 and repeat value of 7.0, remaining
     *      returns 5.0. When the timer is started and one second passes, remaining will return 4.0 . When the timer
     *      expires and is restarted, it will return roughly 7.0 (likely slightly less as callback invocation takes some
     *      time too), and so on.
     */
    public $remaining;

    /**
     * Constructs an EvTimer watcher object.
     *
     * @param float $after Configures the timer to trigger after $after seconds.
     * @param float $repeat If repeat is 0.0, then it will automatically be stopped once the timeout is reached. If it
     *      is positive, then the timer will automatically be configured to trigger again every repeat seconds later,
     *      until stopped manually.
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'float'], default: '')] $after,
        #[LanguageLevelTypeAware(['8.0' => 'float'], default: '')] $repeat,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $callback,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $data = null,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $priority = 0
    ) {}

    /**
     * Restarts the timer watcher.
     *
     * This will act as if the timer timed out and restart it again if it is repeating. The exact semantics are:
     *
     * - if the timer is pending, its pending status is cleared.
     * - if the timer is started but non-repeating, stop it (as if it timed out).
     * - if the timer is repeating, either start it if necessary (with the repeat value), or reset the running timer to
     *   the repeat value.
     */
    public function again() {}

    /**
     * Configures the watcher.
     *
     * @param float $after Configures the timer to trigger after $after seconds.
     * @param float $repeat If repeat is 0.0, then it will automatically be stopped once the timeout is reached. If it
     *      is positive, then the timer will automatically be configured to trigger again every repeat seconds later,
     *      until stopped manually.
     */
    public function set(
        #[LanguageLevelTypeAware(['8.0' => 'float'], default: '')] $after,
        #[LanguageLevelTypeAware(['8.0' => 'float'], default: '')] $repeat
    ) {}

    /**
     * Creates a stopped EvTimer watcher object.
     *
     * @param float $after Configures the timer to trigger after $after seconds.
     * @param float $repeat If repeat is 0.0, then it will automatically be stopped once the timeout is reached. If it
     *      is positive, then the timer will automatically be configured to trigger again every repeat seconds later,
     *      until stopped manually.
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     *
     * @return EvTimer
     */
    final public static function createStopped(float $after, float $repeat, mixed $callback, mixed $data = null, int $priority = 0) {}
}

/**
 * Class EvIdle
 *
 * EvIdle watchers trigger events when no other events of the same or higher priority are pending (EvPrepare, EvCheck
 * and other EvIdle watchers do not count as receiving events).
 *
 * Thus, as long as the process is busy handling sockets or timeouts (or even signals) of the same or higher priority it
 * will not be triggered. But when the process is idle (or only lower-priority watchers are pending), the EvIdle
 * watchers are being called once per event loop iteration - until stopped, that is, or the process receives more events
 * and becomes busy again with higher priority stuff.
 *
 * Apart from keeping the process non-blocking (which is a useful on its own sometimes), EvIdle watchers are a good
 * place to do "pseudo-background processing", or delay processing stuff to after the event loop has handled all
 * outstanding events.
 *
 * The most noticeable effect is that as long as any idle watchers are active, the process will not block when waiting
 * for new events.
 */
final class EvIdle extends EvWatcher
{
    /**
     * Constructs an EvIdle instance.
     *
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     */
    public function __construct(mixed $callback, mixed $data = null, int $priority = 0) {}

    /**
     * Creates a stopped EvIdle instance.
     *
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     *
     * @return EvIdle
     */
    final public static function createStopped(mixed $callback, mixed $data = null, int $priority = 0) {}
}

/**
 * Class EvFork
 *
 * Fork watchers are called when a fork() was detected (usually because whoever signalled libev about it by calling
 * EvLoop::fork()). The invocation is done before the event loop blocks next and before EvCheck watchers are being
 * called, and only in the child after the fork. Note that if someone calls EvLoop::fork() in the wrong process, the
 * fork handlers will be invoked, too.
 */
final class EvFork extends EvWatcher
{
    /**
     * Constructs an EvFork instance.
     *
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     */
    public function __construct(EvLoop $loop, mixed $callback, mixed $data = null, int $priority = 0) {}

    /**
     * Creates a stopped EvFork instance.
     *
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     *
     * @return EvFork
     */
    final public static function createStopped(EvLoop $loop, mixed $callback, mixed $data = null, int $priority = 0) {}
}

/**
 * Class EvLoop
 *
 * Represents an event loop that is always distinct from the default loop. Unlike the default loop, it cannot handle
 * EvChild watchers.
 *
 * Having threads we have to create a loop per thread, and use the the default loop in the parent thread.
 *
 * The default event loop is initialized automatically by Ev. It is accessible via methods of the Ev class, or via
 * EvLoop::defaultLoop() method.
 */
final class EvLoop
{
    /**
     * @var int The Ev::BACKEND_* flag indicating the event backend in use.
     */
    #[Immutable]
    #[ExpectedValues(flags: [Ev::BACKEND_ALL, Ev::BACKEND_DEVPOLL, Ev::BACKEND_EPOLL, Ev::BACKEND_KQUEUE, Ev::BACKEND_MASK, Ev::BACKEND_POLL, Ev::BACKEND_PORT, Ev::BACKEND_SELECT])]
    public $backend;

    /**
     * @var bool TRUE if it is the default event loop.
     */
    #[Immutable]
    public $is_default_loop;

    /**
     * @var mixed Custom data attached to the loop.
     */
    public $data;

    /**
     * @var int The current iteration count of the loop. See Ev::iteration().
     */
    public $iteration;

    /**
     * @var int The number of pending watchers. 0 indicates that there are no watchers pending.
     */
    public $pending;

    /**
     * @var float Higher io_interval allows libev to spend more time collecting EvIo events, so more events can be
     *      handled per iteration, at the cost of increasing latency. Timeouts (both EvPeriodic and EvTimer) will not be
     *      affected. Setting this to a non-zero value will introduce an additional sleep() call into most loop
     *      iterations. The sleep time ensures that libev will not poll for EvIo events more often than once per this
     *      interval, on average. Many programs can usually benefit by setting the io_interval to a value near 0.1,
     *      which is often enough for interactive servers (not for games). It usually doesn't make much sense to set it
     *      to a lower value than 0.01, as this approaches the timing granularity of most systems.
     */
    public $io_interval;

    /**
     * @var float Higher timeout_interval allows libev to spend more time collecting timeouts, at the expense of
     *      increased latency/jitter/inexactness (the watcher callback will be called later). EvIo watchers will not be
     *      affected. Setting this to a non-null value will not introduce any overhead in libev.
     */
    public $timeout_interval;

    /**
     * @var int The recursion depth.
     */
    public $depth;

    /**
     * @param int $flags
     * @param mixed $data
     * @param float $io_interval
     * @param float $timeout_interval
     */
    public function __construct(int $flags = Ev::FLAG_AUTO, mixed $data = null, float $io_interval = 0.0, float $timeout_interval = 0.0) {}

    /**
     * Returns an integer describing the backend used by libev.
     *
     * @return int An integer describing the backend used by libev. See Ev::backend().
     */
    public function backend() {}

    /**
     * Creates EvCheck object associated with the current event loop instance.
     *
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     * @return EvCheck
     */
    final public function check(callable $callback, $data = null, $priority = 0) {}

    /**
     * Creates EvChild object associated with the current event loop instance;
     * @link https://www.php.net/manual/en/evloop.child.php
     * @param int $pid
     * @param bool $trace
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     * @return EvChild
     */
    final public function child(int $pid, bool $trace, mixed $callback, mixed $data = null, int $priority = 0) {}

    /**
     * Creates EvEmbed object associated with the current event loop instance.
     *
     * @param EvLoop $other
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     * @return EvEmbed
     */
    final public function embed(EvLoop $other, callable $callback, $data = null, $priority = 0) {}

    /**
     * Creates EvFork object associated with the current event loop instance.
     *
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     * @return EvFork
     */
    final public function fork(callable $callback, $data = null, $priority = 0) {}

    /**
     * Creates EvIdle object associated with the current event loop instance.
     *
     * @param callable $callback
     * @param null $data
     * @param int $priority
     * @return EvIdle
     */
    final public function idle(mixed $callback, mixed $data = null, int $priority = 0) {}

    /**
     * Invoke all pending watchers while resetting their pending state.
     */
    public function invokePending() {}

    /**
     * Creates EvIo object associated with the current event loop instance.
     *
     * @param resource $fd
     * @param int $events
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     * @return EvIo
     */
    final public function io(mixed $fd, int $events, mixed $callback, mixed $data = null, int $priority = 0) {}

    /**
     * Must be called after a fork.
     *
     * Must be called after a fork in the child, before entering or continuing the event loop. An alternative is to use
     * Ev::FLAG_FORKCHECK which calls this function automatically, at some performance loss (refer to the libev
     * documentation).
     */
    public function loopFork() {}

    /**
     * Returns the current "event loop time".
     *
     * Returns the current "event loop time", which is the time the event loop received events and started processing
     * them. This timestamp does not change as long as callbacks are being processed, and this is also the base time
     * used for relative timers. You can treat it as the timestamp of the event occurring (or more correctly, libev
     * finding out about it).
     *
     * @return float Time of the event loop in (fractional) seconds.
     */
    public function now() {}

    /**
     * Establishes the current time by querying the kernel, updating the time returned by Ev::now in the progress.
     *
     * Establishes the current time by querying the kernel, updating the time returned by Ev::now() in the progress.
     * This is a costly operation and is usually done automatically within Ev::run().
     *
     * This method is rarely useful, but when some event callback runs for a very long time without entering the event
     * loop, updating libev's consideration of the current time is a good idea.
     */
    public function nowUpdate() {}

    /**
     * Creates EvPeriodic object associated with the current event loop instance.
     *
     * @param float $offset
     * @param float $interval
     * @param callable $reschedule_cb
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     */
    final public function periodic(float $offset, float $interval, mixed $reschedule_cb, mixed $callback, mixed $data = null, int $priority = 0) {}

    /**
     * Creates EvPrepare object associated with the current event loop instance.
     *
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     */
    final public function prepare(callable $callback, $data = null, $priority = 0) {}

    /**
     * Resume previously suspended default event loop.
     *
     * EvLoop::suspend() and EvLoop::resume() methods suspend and resume a loop correspondingly.
     */
    public function resume() {}

    /**
     * Begin checking for events and calling callbacks for the loop.
     *
     * Begin checking for events and calling callbacks for the current event loop. Returns when a callback calls
     * Ev::stop() method, or the flags are nonzero (in which case the return value is true) or when there are no active
     * watchers which reference the loop (EvWatcher::keepalive() is TRUE), in which case the return value will be FALSE.
     * The return value can generally be interpreted as if TRUE, there is more work left to do.
     *
     * @param int $flags One of the Ev::RUN_* flags.
     */
    public function run(int $flags = Ev::FLAG_AUTO) {}

    /**
     * Creates EvSignal object associated with the current event loop instance.
     *
     * @param int $signum
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     * @return EvSignal
     */
    final public function signal(int $signum, mixed $callback, mixed $data = null, int $priority = 0) {}

    /**
     * Creates EvStats object associated with the current event loop instance.
     *
     * @param string $path
     * @param float $interval
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     * @return EvStat
     */
    final public function stat(string $path, float $interval, mixed $callback, mixed $data = null, int $priority = 0) {}

    /**
     * Stops the event loop.
     *
     * @param int $how One of the Ev::BREAK_* flags.
     */
    public function stop(int $how = Ev::BREAK_ALL) {}

    /**
     * Suspend the loop.
     *
     * EvLoop::suspend() and EvLoop::resume() methods suspend and resume a loop correspondingly.
     */
    public function suspend() {}

    /**
     * Creates EvTimer object associated with the current event loop instance.
     *
     * @param float $after
     * @param float $repeat
     * @param callable $callback
     * @param mixed $data
     * @param int $priority
     * @return EvTimer
     */
    final public function timer(float $after, float $repeat, mixed $callback, mixed $data = null, int $priority = 0) {}

    /**
     * Performs internal consistency checks (for debugging).
     *
     * Performs internal consistency checks (for debugging libev) and abort the program if any data structures were
     * found to be corrupted.
     */
    public function verify() {}

    /**
     * Returns or creates the default event loop.
     *
     * If the default event loop is not created, EvLoop::defaultLoop() creates it with the specified parameters.
     * Otherwise, it just returns the object representing previously created instance ignoring all the parameters.
     *
     * @param int $flags
     * @param mixed $data
     * @param float $io_interval
     * @param float $timeout_interval
     */
    public static function defaultLoop(
        int $flags = Ev::FLAG_AUTO,
        mixed $data = null,
        float $io_interval = 0.0,
        float $timeout_interval = 0.0
    ) {}
}
