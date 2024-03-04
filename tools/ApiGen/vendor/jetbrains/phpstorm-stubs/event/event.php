<?php

// The Event class
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Pure;

/**
 * Event.
 * Event class represents and event firing on a file descriptor being ready to read from or write to; a file descriptor becoming ready to read from or write to(edge-triggered I/O only); a timeout expiring; a signal occurring; a user-triggered event.
 * Every event is associated with EventBase . However, event will never fire until it is added (via Event::add() ). An added event remains in pending state until the registered event occurs, thus turning it to active state. To handle events user may register a callback which is called when event becomes active. If event is configured persistent , it remains pending. If it is not persistent, it stops being pending when it's callback runs. Event::del() method deletes event, thus making it non-pending. By means of Event::add() method it could be added again.
 *
 * @author Kazuaki MABUCHI
 * @copyright Сopyright (https://php.net/manual/cc.license.php) by the PHP Documentation Group is licensed under [CC by 3.0 or later](https://creativecommons.org/licenses/by/3.0/).
 *
 * @see https://php.net/manual/en/class.event.php
 */
final class Event
{
    /**
     * @var bool
     */
    #[Immutable]
    public $pending;
    public const ET = 32;
    public const PERSIST = 16;
    public const READ = 2;
    public const WRITE = 4;
    public const SIGNAL = 8;
    public const TIMEOUT = 1;

    /**
     * __construct.
     * Constructs Event object.
     *
     * @param EventBase $base
     * @param mixed     $fd
     * @param int       $what
     * @param callable  $cb
     * @param mixed     $arg  = null
     *
     * @see https://php.net/manual/en/event.construct.php
     */
    #[Pure]
    public function __construct(EventBase $base, $fd, int $what, callable $cb, $arg = null) {}

    /**
     * add.
     * Makes event pending.
     *
     * @param float $timeout (optional)
     *
     * @return bool
     *
     * @see https://php.net/manual/en/event.add.php
     */
    public function add(float $timeout = -1): bool {}

    /**
     * addSignal.
     * Makes signal event pending.
     *
     * @param float $timeout (optional)
     *
     * @return bool
     *
     * @see https://php.net/manual/en/event.addsignal.php
     */
    public function addSignal(float $timeout = -1): bool {}

    /**
     * addTimer.
     * Makes timer event pending.
     *
     * @param float $timeout (optional)
     *
     * @return bool
     *
     * @see https://php.net/manual/en/event.addtimer.php
     */
    public function addTimer(float $timeout = -1): bool {}

    /**
     * del.
     * Makes event non-pending.
     *
     * @return bool
     *
     * @see https://php.net/manual/en/event.del.php
     */
    public function del(): bool {}

    /**
     * delSignal.
     * Makes signal event non-pending.
     *
     * @return bool
     *
     * @see https://php.net/manual/en/event.delsignal.php
     */
    public function delSignal(): bool {}

    /**
     * delTimer.
     * Makes timer event non-pending.
     *
     * @return bool
     *
     * @see https://php.net/manual/en/event.deltimer.php
     */
    public function delTimer(): bool {}

    /**
     * free.
     * Make event non-pending and free resources allocated for this event.
     *
     * @see https://php.net/manual/en/event.free.php
     */
    public function free(): void {}

    /**
     * getSupportedMethods.
     * Returns array with of the names of the methods supported in this version of Libevent.
     *
     * @return array
     *
     * @see https://php.net/manual/en/event.getsupportedmethods.php
     */
    public static function getSupportedMethods(): array {}

    /**
     * pending.
     * Detects whether event is pending or scheduled.
     *
     * @param int $flags
     *
     * @return bool
     *
     * @see https://php.net/manual/en/event.pending.php
     */
    public function pending(int $flags): bool {}

    /**
     * set.
     * Re-configures event.
     *
     * @param EventBase $base
     * @param mixed     $fd
     * @param int       $what (optional)
     * @param callable  $cb   (optional)
     * @param mixed     $arg  (optional)
     *
     * @return bool
     *
     * @see https://php.net/manual/en/event.set.php
     */
    public function set(EventBase $base, $fd, int $what, callable $cb, $arg): bool {}

    /**
     * setPriority.
     * Set event priority.
     *
     * @param int $priority
     * @return bool
     *
     * @see https://php.net/manual/en/event.setpriority.php
     */
    public function setPriority(int $priority): bool {}

    /**
     * setTimer.
     * Re-configures timer event.
     *
     * @param EventBase $base
     * @param callable  $cb
     * @param mixed     $arg  (optional)
     *
     * @return bool
     *
     * @see https://php.net/manual/en/event.settimer.php
     */
    public function setTimer(EventBase $base, callable $cb, $arg): bool {}

    /**
     * signal.
     * Constructs signal event object.
     *
     * @param EventBase $base
     * @param int       $signum
     * @param callable  $cb
     * @param mixed     $arg    (optional)
     *
     * @return Event
     *
     * @see https://php.net/manual/en/event.signal.php
     */
    public static function signal(EventBase $base, int $signum, callable $cb, $arg): Event {}

    /**
     * timer.
     * Constructs timer event object.
     *
     * @param EventBase $base
     * @param callable  $cb
     * @param mixed     $arg  (optional)
     *
     * @return Event
     *
     * @see https://php.net/manual/en/event.timer.php
     */
    public static function timer(EventBase $base, callable $cb, $arg): Event {}
}

//  The EventBase class
/**
 * EventBase.
 * EventBase class represents libevent's event base structure. It holds a set of events and can poll to determine which events are active.
 * Each event base has a method, or a backend that it uses to determine which events are ready. The recognized methods are: select, poll, epoll, kqueue, devpoll, evport and win32 .
 * To configure event base to use, or avoid specific backend EventConfig class can be used.
 *
 * @author Kazuaki MABUCHI
 * @copyright Copyright (https://php.net/manual/cc.license.php) by the PHP Documentation Group is licensed under [CC by 3.0 or later](https://creativecommons.org/licenses/by/3.0/).
 *
 * @see https://php.net/manual/en/class.eventbase.php
 */
final class EventBase
{
    public const LOOP_ONCE = 1;
    public const LOOP_NONBLOCK = 2;
    public const NOLOCK = 1;
    public const STARTUP_IOCP = 4;
    public const NO_CACHE_TIME = 8;
    public const EPOLL_USE_CHANGELIST = 16;
    public const IGNORE_ENV = 2;
    public const PRECISE_TIMER = 32;

    /**
     * __construct.
     * Constructs EventBase object.
     *
     * @param null|EventConfig $cfg
     *
     * @see https://php.net/manual/en/eventbase.construct.php
     */
    public function __construct(?EventConfig $cfg = null) {}

    /**
     * dispatch.
     * Dispatch pending events.
     *
     * @see https://php.net/manual/en/eventbase.dispatch.php
     */
    public function dispatch(): void {}

    /**
     * exit.
     * Stop dispatching events.
     *
     * @param float $timeout
     *
     * @return bool
     *
     * @see https://php.net/manual/en/eventbase.exit.php
     */
    public function exit(float $timeout = 0.0): bool {}

    /**
     * free.
     * Free resources allocated for this event base.
     *
     * @see https://php.net/manual/en/eventbase.free.php
     */
    public function free(): void {}

    /**
     * getFeatures.
     * Returns bitmask of features supported.
     *
     * @return int
     *
     * @see https://php.net/manual/en/eventbase.getfeatures.php
     */
    #[Pure]
    public function getFeatures(): int {}

    /**
     * getMethod.
     * Returns event method in use.
     *
     * @return string
     *
     * @see https://php.net/manual/en/eventbase.getmethod.php
     */
    #[Pure]
    public function getMethod(): string {}

    /**
     * getTimeOfDayCached.
     * Returns the current event base time.
     *
     * @return float
     *
     * @see https://php.net/manual/en/eventbase.gettimeofdaycached.php
     */
    #[Pure]
    public function getTimeOfDayCached(): float {}

    /**
     * gotExit.
     * Checks if the event loop was told to exit.
     *
     * @return bool
     *
     * @see https://php.net/manual/en/eventbase.gotexit.php
     */
    #[Pure]
    public function gotExit(): bool {}

    /**
     * gotStop.
     * Checks if the event loop was told to exit.
     *
     * @return bool
     *
     * @see https://php.net/manual/en/eventbase.gotstop.php
     */
    #[Pure]
    public function gotStop(): bool {}

    /**
     * loop.
     * Dispatch pending events.
     *
     * @param int $flags
     *
     * @return bool
     *
     * @see https://php.net/manual/en/eventbase.loop.php
     */
    public function loop(int $flags = -1): bool {}

    /**
     * priorityInit.
     * Sets number of priorities per event base.
     *
     * @param int $n_priorities
     *
     * @return bool
     *
     * @see https://php.net/manual/en/eventbase.priorityinit.php
     */
    public function priorityInit(int $n_priorities): bool {}

    /**
     * reInit.
     * Re]initialize event base(after a fork).
     *
     * @return bool
     *
     * @see https://php.net/manual/en/eventbase.reinit.php
     */
    public function reInit(): bool {}

    /**
     * Tells event_base to resume previously stopped event
     * @return bool
     * @since libevent version 2.1.2-alpha
     * @see https://bitbucket.org/osmanov/pecl-event/src/8e5ab7303f3ef7827b71f31904a51b3f26dd1ac2/php8/classes/base.c#lines-387
     */
    public function resume(): bool {}

    /**
     * stop.
     * Tells event_base to stop dispatching events.
     *
     * @return bool
     *
     * @see https://php.net/manual/en/eventbase.stop.php
     */
    public function stop(): bool {}

    /**
     * Updates cache time
     * @return bool
     * @since libevent 2.1.1-alpha
     * @see https://bitbucket.org/osmanov/pecl-event/src/8e5ab7303f3ef7827b71f31904a51b3f26dd1ac2/php8/classes/base.c#lines-343
     */
    public function updateCacheTime(): bool {}
}

// The EventBuffer class
/**
 * EventBuffer.
 * EventBuffer represents Libevent's "evbuffer", an utility functionality for buffered I/O.
 * Event buffers are meant to be generally useful for doing the "buffer" part of buffered network I/O.
 *
 * @author Kazuaki MABUCHI
 * @copyright Copyright (https://php.net/manual/cc.license.php) by the PHP Documentation Group is licensed under [CC by 3.0 or later](https://creativecommons.org/licenses/by/3.0/).
 *
 * @see https://php.net/manual/en/class.eventbuffer.php
 */
class EventBuffer
{
    /**
     * @var int
     */
    #[Immutable]
    public $length;

    /**
     * @var int
     */
    #[Immutable]
    public $contiguous_space;
    public const EOL_ANY = 0;
    public const EOL_CRLF = 1;
    public const EOL_CRLF_STRICT = 2;
    public const EOL_LF = 3;
    public const EOL_NUL = 4;
    public const PTR_SET = 0;
    public const PTR_ADD = 1;

    /**
     * __construct.
     * Constructs EventBuffer object.
     *
     * @see https://php.net/manual/en/eventbuffer.construct.php
     */
    #[Pure]
    public function __construct() {}

    /**
     * add.
     * Append data to the end of an event buffer.
     *
     * @param string $data
     *
     * @return bool
     *
     * @see https://php.net/manual/en/eventbuffer.add.php
     */
    public function add(string $data): bool {}

    /**
     * addBuffer.
     * Move all data from a buffer provided to the current instance of EventBuffer.
     *
     * @param EventBuffer $buf
     *
     * @return bool
     *
     * @see https://php.net/manual/en/eventbuffer.addbuffer.php
     */
    public function addBuffer(EventBuffer $buf): bool {}

    /**
     * appendFrom.
     * Moves the specified number of bytes from a source buffer to the end of the current buffer.
     *
     * @param EventBuffer $buf
     * @param int         $len
     *
     * @return int
     *
     * @see https://php.net/manual/en/eventbuffer.appendfrom.php
     */
    public function appendFrom(EventBuffer $buf, int $len): int {}

    /**
     * copyout.
     * Copies out specified number of bytes from the front of the buffer.
     *
     * @param string &$data
     * @param int    $max_bytes
     *
     * @return int
     *
     * @see https://php.net/manual/en/eventbuffer.copyout.php
     */
    public function copyout(string &$data, int $max_bytes): int {}

    /**
     * drain.
     * Removes specified number of bytes from the front of the buffer without copying it anywhere.
     *
     * @param int $len
     *
     * @return bool
     *
     * @see https://php.net/manual/en/eventbuffer.drain.php
     */
    public function drain(int $len): bool {}

    /**
     * enableLocking.
     *
     * @see https://php.net/manual/en/eventbuffer.enablelocking.php
     */
    public function enableLocking(): void {}

    /**
     * expand.
     * Reserves space in buffer.
     *
     * @param int $len
     *
     * @return bool
     *
     * @see https://php.net/manual/en/eventbuffer.expand.php
     */
    public function expand(int $len): bool {}

    /**
     * freeze.
     * Prevent calls that modify an event buffer from succeeding.
     *
     * @param bool $at_front
     *
     * @return bool
     *
     * @see https://php.net/manual/en/eventbuffer.freeze.php
     */
    public function freeze(bool $at_front): bool {}

    /**
     * lock.
     * Acquires a lock on buffer.
     *
     * @see https://php.net/manual/en/eventbuffer.lock.php
     */
    public function lock(): void {}

    /**
     * prepend.
     * Prepend data to the front of the buffer.
     *
     * @param string $data
     *
     * @return bool
     *
     * @see https://php.net/manual/en/eventbuffer.prepend.php
     */
    public function prepend(string $data): bool {}

    /**
     * prependBuffer.
     * Moves all data from source buffer to the front of current buffer.
     *
     * @param EventBuffer $buf
     *
     * @return bool
     *
     * @see https://php.net/manual/en/eventbuffer.prependbuffer.php
     */
    public function prependBuffer(EventBuffer $buf): bool {}

    /**
     * pullup.
     * Linearizes data within buffer and returns it's contents as a string.
     *
     * @param int $size
     *
     * @return null|string
     *
     * @see https://php.net/manual/en/eventbuffer.pullup.php
     */
    public function pullup(int $size): ?string {}

    /**
     * read.
     * Read data from an evbuffer and drain the bytes read.
     *
     * @param int $max_bytes
     *
     * @return null | string
     */
    public function read(int $max_bytes): ?string {}

    /**
     * readFrom.
     * Read data from a file onto the end of the buffer.
     *
     * @param mixed $fd
     * @param int   $howmuch
     *
     * @return int
     *
     * @see https://php.net/manual/en/eventbuffer.readfrom.php
     */
    public function readFrom($fd, int $howmuch): int {}

    /**
     * readLine.
     * Extracts a line from the front of the buffer.
     *
     * @param int $eol_style
     *
     * @return null | string
     *
     * @see https://php.net/manual/en/eventbuffer.readline.php
     */
    public function readLine(int $eol_style): ?string {}

    /**
     * search.
     * Scans the buffer for an occurrence of a string.
     *
     * @param string $what
     * @param int    $start = 1
     * @param int    $end   = 1
     *
     * @return int|false
     *
     * @see https://php.net/manual/en/eventbuffer.search.php
     */
    public function search(string $what, int $start = 1, int $end = 1): int|false {}

    /**
     * searchEol.
     * Scans the buffer for an occurrence of an end of line.
     *
     * @param int $start     = 1
     * @param int $eol_style = EOL_ANY
     *
     * @return int|false
     *
     * @see https://php.net/manual/en/eventbuffer.searcheol.php
     */
    public function searchEol(int $start = 1, int $eol_style = EventBuffer::EOL_ANY): int|false {}

    /**
     * substr.
     * Substracts a portion of the buffer data.
     *
     * @param int $start
     * @param int $length (optional)
     *
     * @return string
     *
     * @see https://php.net/manual/en/eventbuffer.substr.php
     */
    public function substr(int $start, int $length): string {}

    /**
     * unfreeze.
     * Re-enable calls that modify an event buffer.
     *
     * @param bool $at_front
     *
     * @return bool
     *
     * @see https://php.net/manual/en/eventbuffer.unfreeze.php
     */
    public function unfreeze(bool $at_front): bool {}

    /**
     * unlock.
     * Releases lock acquired by EventBuffer::lock.
     *
     * @return void
     *
     * @see https://php.net/manual/en/eventbuffer.unlock.php
     */
    public function unlock(): void {}

    /**
     * write.
     * Write contents of the buffer to a file or socket.
     *
     * @param mixed $fd
     * @param int $howmuch (optional)
     *
     * @return int|false
     *
     * @see https://php.net/manual/en/eventbuffer.write.php
     */
    public function write(mixed $fd, int $howmuch): int|false {}
}

// The EventBufferEvent class
/**
 * EventBufferEvent.
 * Represents Libevent's buffer event.
 * Usually an application wants to perform some amount of data buffering in addition to just responding to events. When we want to write data, for example, the usual pattern looks like:
 * Decide that we want to write some data to a connection; put that data in a buffer.
 * Wait for the connection to become writable
 * Write as much of the data as we can
 * Remember how much we wrote, and if we still have more data to write, wait for the connection to become writable again.
 * This buffered I/O pattern is common enough that Libevent provides a generic mechanism for it. A "buffer event" consists of an underlying transport (like a socket), a read buffer, and a write buffer. Instead of regular events, which give callbacks when the underlying transport is ready to be read or written, a buffer event invokes its user-supplied callbacks when it has read or written enough data.
 *
 * @author Kazuaki MABUCHI
 * @copyright Copyright (https://php.net/manual/cc.license.php) by the PHP Documentation Group is licensed under [CC by 3.0 or later](https://creativecommons.org/licenses/by/3.0/).
 *
 * @see https://php.net/manual/en/class.eventbufferevent.php
 */
final class EventBufferEvent
{
    /** @var int */
    public $fd;

    /** @var int */
    public $priority;

    /**
     * @var EventBuffer
     */
    #[Immutable]
    public $input;

    /**
     * @var EventBuffer
     */
    #[Immutable]
    public $output;
    public const READING = 1;
    public const WRITING = 2;
    public const EOF = 16;
    public const ERROR = 32;
    public const TIMEOUT = 64;
    public const CONNECTED = 128;
    public const OPT_CLOSE_ON_FREE = 1;
    public const OPT_THREADSAFE = 2;
    public const OPT_DEFER_CALLBACKS = 4;
    public const OPT_UNLOCK_CALLBACKS = 8;
    public const SSL_OPEN = 0;
    public const SSL_CONNECTING = 1;
    public const SSL_ACCEPTING = 2;

    /**
     * __construct.
     * Constructs EventBufferEvent object.
     *
     * @param EventBase $base
     * @param mixed     $socket  = null
     * @param int       $options = 0
     * @param null|callable $readcb  = null
     * @param null|callable $writecb = null
     * @param null|callable $eventcb = null
     *
     * @see https://php.net/manual/en/eventbufferevent.construct.php
     */
    #[Pure]
    public function __construct(EventBase $base, $socket = null, int $options = 0, ?callable $readcb = null, ?callable $writecb = null, ?callable $eventcb = null) {}

    /**
     * close.
     * Closes file descriptor associated with the current buffer event.
     * @return bool
     * @see https://php.net/manual/en/eventbufferevent.close.php
     */
    public function close(): bool {}

    /**
     * connect.
     * Connect buffer event's file descriptor to given address or UNIX socket.
     *
     * @param string $addr
     *
     * @return bool
     *
     * @see https://php.net/manual/en/eventbufferevent.connect.php
     */
    public function connect(string $addr): bool {}

    /**
     * connectHost.
     * Connects to a hostname with optionally asyncronous DNS.
     *
     * @param null|EventDnsBase $dns_base
     * @param string       $hostname
     * @param int          $port
     * @param int          $family   = EventUtil::AF_UNSPEC
     *
     * @return bool
     *
     * @see https://php.net/manual/en/eventbufferevent.connecthost.php
     */
    public function connectHost(?EventDnsBase $dns_base, string $hostname, int $port, int $family = EventUtil::AF_UNSPEC): bool {}

    /**
     * createSslFilter
     *
     * @param EventBufferEvent $underlying
     * @param EventSslContext $ctx
     * @param int $state
     * @param int $options
     * @return EventBufferEvent
     * @see https://bitbucket.org/osmanov/pecl-event/src/8e5ab7303f3ef7827b71f31904a51b3f26dd1ac2/php8/classes/buffer_event.c#lines-1025
     */
    public function createSslFilter(EventBufferEvent $underlying, EventSslContext $ctx, int $state, int $options = 0): EventBufferEvent {}

    /**
     * createPair.
     * Creates two buffer events connected to each other.
     *
     * @param EventBase $base
     * @param int       $options = 0
     *
     * @return array
     *
     * @see https://php.net/manual/en/eventbufferevent.createpair.php
     */
    public static function createPair(EventBase $base, int $options = 0): array {}

    /**
     * disable.
     * Disable events read, write, or both on a buffer event.
     *
     * @param int $events
     *
     * @return bool
     *
     * @see https://php.net/manual/en/eventbufferevent.disable.php
     */
    public function disable(int $events): bool {}

    /**
     * enable.
     * Enable events read, write, or both on a buffer event.
     *
     * @param int $events
     *
     * @return bool
     *
     * @see https://php.net/manual/en/eventbufferevent.enable.php
     */
    public function enable(int $events): bool {}

    /**
     * free.
     * Free a buffer event.
     *
     * @see https://php.net/manual/en/eventbufferevent.free.php
     */
    public function free(): void {}

    /**
     * getDnsErrorString.
     * Returns string describing the last failed DNS lookup attempt.
     *
     * @return string
     *
     * @see https://php.net/manual/en/eventbufferevent.getdnserrorstring.php
     */
    #[Pure]
    public function getDnsErrorString(): string {}

    /**
     * getEnabled.
     * Returns bitmask of events currently enabled on the buffer event.
     *
     * @return int
     *
     * @see https://php.net/manual/en/eventbufferevent.getenabled.php
     */
    #[Pure]
    public function getEnabled(): int {}

    /**
     * getInput.
     * Returns underlying input buffer associated with current buffer event.
     *
     * @return EventBuffer
     *
     * @see https://php.net/manual/en/eventbufferevent.getinput.php
     */
    #[Pure]
    public function getInput(): EventBuffer {}

    /**
     * getOutput.
     * Returns underlying output buffer associated with current buffer event.
     *
     * @return EventBuffer
     *
     * @see https://php.net/manual/en/eventbufferevent.getoutput.php
     */
    #[Pure]
    public function getOutput(): EventBuffer {}

    /**
     * read.
     * Read buffer's data.
     *
     * @param int $size
     *
     * @return null|string
     *
     * @see https://php.net/manual/en/eventbufferevent.read.php
     */
    public function read(int $size): ?string {}

    /**
     * readBuffer.
     * Drains the entire contents of the input buffer and places them into buf.
     *
     * @param EventBuffer $buf
     *
     * @return bool
     *
     * @see https://php.net/manual/en/eventbufferevent.readbuffer.php
     */
    public function readBuffer(EventBuffer $buf): bool {}

    /**
     * setCallbacks.
     * Assigns read, write and event(status) callbacks.
     *
     * @param callable $readcb
     * @param callable $writecb
     * @param callable $eventcb
     * @param string   $arg     (optional)
     *
     * @see https://php.net/manual/en/eventbufferevent.setcallbacks.php
     */
    public function setCallbacks(callable $readcb, callable $writecb, callable $eventcb, mixed $arg = null): void {}

    /**
     * setPriority.
     * Assign a priority to a bufferevent.
     *
     * @param int $priority
     *
     * @return bool
     *
     * @see https://php.net/manual/en/eventbufferevent.setpriority.php
     */
    public function setPriority(int $priority): bool {}

    /**
     * setTimeouts.
     * Set the read and write timeout for a buffer event.
     *
     * @param float $timeout_read
     * @param float $timeout_write
     *
     * @return bool
     *
     * @see https://php.net/manual/en/eventbufferevent.settimeouts.php
     */
    public function setTimeouts(float $timeout_read, float $timeout_write): bool {}

    /**
     * setWatermark.
     * Adjusts read and/or write watermarks.
     *
     * @param int $events
     * @param int $lowmark
     * @param int $highmark
     *
     * @see https://php.net/manual/en/eventbufferevent.setwatermark.php
     */
    public function setWatermark(int $events, int $lowmark, int $highmark): void {}

    /**
     * sslError.
     * Returns most recent OpenSSL error reported on the buffer event.
     *
     * @return false|string
     *
     * @see https://secure.php.net/manual/en/eventbufferevent.sslerror.php
     */
    public function sslError(): false|string {}

    /**
     * sslFilter.
     * Create a new SSL buffer event to send its data over another buffer event.
     *
     * @param EventBase        $base
     * @param EventBufferEvent $underlying
     * @param EventSslContext  $ctx
     * @param int              $state
     * @param int              $options    = 0
     *
     * @return EventBufferEvent
     *
     * @see https://secure.php.net/manual/en/eventbufferevent.sslfilter.php
     */
    public static function sslFilter(EventBase $base, EventBufferEvent $underlying, EventSslContext $ctx, int $state, int $options = 0): EventBufferEvent {}

    /**
     * sslGetCipherInfo.
     * Returns a textual description of the cipher.
     *
     * @return string|false
     *
     * @see https://secure.php.net/manual/en/eventbufferevent.sslgetcipherinfo.php
     */
    public function sslGetCipherInfo(): string|false {}

    /**
     * sslGetCipherName.
     * Returns the current cipher name of the SSL connection.
     *
     * @return string|false
     *
     * @see https://secure.php.net/manual/en/eventbufferevent.sslgetciphername.php
     */
    public function sslGetCipherName(): string|false {}

    /**
     * sslGetCipherVersion.
     * Returns version of cipher used by current SSL connection.
     *
     * @return string|false
     *
     * @see https://secure.php.net/manual/en/eventbufferevent.sslgetcipherversion.php
     */
    public function sslGetCipherVersion(): string|false {}

    /**
     * sslGetProtocol.
     * Returns the name of the protocol used for current SSL.
     *
     * @return string
     *
     * @see https://secure.php.net/manual/en/eventbufferevent.sslgetprotocol.php
     */
    public function sslGetProtocol(): string {}

    /**
     * sslRenegotiate.
     * Tells a bufferevent to begin SSL renegotiation.
     *
     * @see https://secure.php.net/manual/en/eventbufferevent.sslrenegotiate.php
     */
    public function sslRenegotiate(): void {}

    /**
     * sslSocket.
     * Creates a new SSL buffer event to send its data over an SSL on a socket.
     *
     * @param EventBase $base
     * @param mixed $socket
     * @param EventSslContext $ctx
     * @param int $state
     * @param int $options (optional)
     *
     * @return EventBufferEvent
     *
     * @see https://secure.php.net/manual/en/eventbufferevent.sslsocket.php
     */
    public static function sslSocket(EventBase $base, mixed $socket, EventSslContext $ctx, int $state, int $options = 0): EventBufferEvent {}

    /**
     * write.
     * Adds data to a buffer event's output buffer.
     *
     * @param string $data
     *
     * @return bool
     *
     * @see https://secure.php.net/manual/en/eventbufferevent.write.php
     */
    public function write(string $data): bool {}

    /**
     * writeBuffer.
     * Adds contents of the entire buffer to a buffer event's output buffer.
     *
     * @param EventBuffer $buf
     *
     * @return bool
     *
     * @see https://secure.php.net/manual/en/eventbufferevent.writebuffer.php
     */
    public function writeBuffer(EventBuffer $buf): bool {}
}

// The EventConfig class
/**
 * EventConfig.
 * Represents configuration structure which could be used in construction of the EventBase .
 *
 * @author Kazuaki MABUCHI
 * @copyright Copyright (https://secure.php.net/manual/cc.license.php) by the PHP Documentation Group is licensed under [CC by 3.0 or later](https://creativecommons.org/licenses/by/3.0/).
 *
 * @see https://secure.php.net/manual/en/class.eventconfig.php
 */
final class EventConfig
{
    public const FEATURE_ET = 1;
    public const FEATURE_O1 = 2;
    public const FEATURE_FDS = 4;

    /**
     * __construct.
     * Constructs EventConfig object.
     *
     * @see https://secure.php.net/manual/en/eventconfig.construct.php
     */
    #[Pure]
    public function __construct() {}

    /**
     * avoidMethod.
     * Tells libevent to avoid specific event method.
     *
     * @param string $method
     *
     * @return bool
     *
     * @see https://secure.php.net/manual/en/eventconfig.avoidmethod.php
     */
    public function avoidMethod(string $method): bool {}

    /**
     * requireFeatures.
     * Enters a required event method feature that the application demands.
     *
     * @param int $feature
     *
     * @return bool
     *
     * @see https://secure.php.net/manual/en/eventconfig.requirefeatures.php
     */
    public function requireFeatures(int $feature): bool {}

    /**
     * Sets one or more flags to configure what parts of the eventual EventBase
     * will be initialized, and how they'll work
     * @param int $flags
     * @return bool
     * @since libevent version 2.0.2-alpha
     */
    public function setFlags(int $flags): bool {}

    /**
     * setMaxDispatchInterval.
     * Prevents priority inversion.
     *
     * @param int $max_interval
     * @param int $max_callbacks
     * @param int $min_priority
     *
     * @see https://secure.php.net/manual/en/eventconfig.setmaxdispatchinterval.php
     */
    public function setMaxDispatchInterval(int $max_interval, int $max_callbacks, int $min_priority): void {}
}

// The EventDnsBase class
/**
 * EventDnsBase.
 * Represents Libevent's DNS base structure. Used to resolve DNS asyncronously, parse configuration files like resolv.conf etc.
 *
 * @author Kazuaki MABUCHI
 * @copyright Copyright (https://secure.php.net/manual/cc.license.php) by the PHP Documentation Group is licensed under [CC by 3.0 or later](https://creativecommons.org/licenses/by/3.0/).
 *
 * @see https://secure.php.net/manual/en/class.eventdnsbase.php
 */
final class EventDnsBase
{
    public const OPTION_SEARCH = 1;
    public const OPTION_NAMESERVERS = 2;
    public const OPTION_MISC = 4;
    public const OPTION_HOSTSFILE = 8;
    public const OPTIONS_ALL = 15;

    /**
     * __construct.
     * Constructs EventDnsBase object.
     *
     * @param EventBase $base
     * @param bool      $initialize
     *
     * @see https://secure.php.net/manual/en/eventdnsbase.construct.php
     */
    #[Pure]
    public function __construct(EventBase $base, bool $initialize) {}

    /**
     * addNameserverIp.
     * Adds a nameserver to the DNS base.
     *
     * @param string $ip
     *
     * @return bool
     *
     * @see https://secure.php.net/manual/en/eventdnsbase.addnameserverip.php
     */
    public function addNameserverIp(string $ip): bool {}

    /**
     * addSearch.
     * Adds a domain to the list of search domains.
     *
     * @param string $domain
     *
     * @see https://secure.php.net/manual/en/eventdnsbase.addsearch.php
     */
    public function addSearch(string $domain): void {}

    /**
     * clearSearch.
     * Removes all current search suffixes.
     *
     * @see https://secure.php.net/manual/en/eventdnsbase.clearsearch.php
     */
    public function clearSearch(): void {}

    /**
     * countNameservers.
     * Gets the number of configured nameservers.
     *
     * @return int
     *
     * @see https://secure.php.net/manual/en/eventdnsbase.countnameservers.php
     */
    public function countNameservers(): int {}

    /**
     * loadHosts.
     * Loads a hosts file (in the same format as /etc/hosts) from hosts file.
     *
     * @param string $hosts
     *
     * @return bool
     *
     * @see https://secure.php.net/manual/en/eventdnsbase.loadhosts.php
     */
    public function loadHosts(string $hosts): bool {}

    /**
     * parseResolvConf.
     * Scans the resolv.conf-formatted file.
     *
     * @param int    $flags
     * @param string $filename
     *
     * @return bool
     *
     * @see https://secure.php.net/manual/en/eventdnsbase.parseresolvconf.php
     */
    public function parseResolvConf(int $flags, string $filename): bool {}

    /**
     * setOption.
     * Set the value of a configuration option.
     *
     * @param string $option
     * @param string $value
     *
     * @return bool
     *
     * @see https://secure.php.net/manual/en/eventdnsbase.setoption.php
     */
    public function setOption(string $option, string $value): bool {}

    /**
     * setSearchNdots.
     * Set the 'ndots' parameter for searches.
     *
     * @param int $ndots
     *
     * @return void
     *
     * @see https://secure.php.net/manual/en/eventdnsbase.setsearchndots.php
     */
    public function setSearchNdots(int $ndots): void {}
}

// The EventHttp class
/**
 * EventHttp.
 * Represents HTTP server.
 *
 * @author Kazuaki MABUCHI
 * @copyright Copyright (https://secure.php.net/manual/cc.license.php) by the PHP Documentation Group is licensed under [CC by 3.0 or later](https://creativecommons.org/licenses/by/3.0/).
 *
 * @see https://secure.php.net/manual/en/class.eventhttp.php
 */
final class EventHttp
{
    /**
     * __construct.
     * Constructs EventHttp object(the HTTP server).
     *
     * @param EventBase       $base
     * @param null|EventSslContext $ctx
     *
     * @see https://secure.php.net/manual/en/eventhttp.construct.php
     */
    public function __construct(EventBase $base, ?EventSslContext $ctx = null) {}

    /**
     * accept.
     * Makes an HTTP server accept connections on the specified socket stream or resource.
     *
     * @param mixed $socket
     *
     * @return bool
     *
     * @see https://secure.php.net/manual/en/eventhttp.accept.php
     */
    public function accept(mixed $socket): bool {}

    /**
     * addServerAlias.
     * Adds a server alias to the HTTP server object.
     *
     * @param string $alias
     *
     * @return bool
     *
     * @see https://secure.php.net/manual/en/eventhttp.addserveralias.php
     */
    public function addServerAlias(string $alias): bool {}

    /**
     * bind.
     * Binds an HTTP server on the specified address and port.
     *
     * @param string $address
     * @param int    $port
     * @return bool
     *
     * @see https://secure.php.net/manual/en/eventhttp.bind.php
     */
    public function bind(string $address, int $port): bool {}

    /**
     * removeServerAlias.
     * Removes server alias.
     *
     * @param string $alias
     *
     * @return bool
     *
     * @see https://secure.php.net/manual/en/eventhttp.removeserveralias.php
     */
    public function removeServerAlias(string $alias): bool {}

    /**
     * setAllowedMethods.
     * Sets the what HTTP methods are supported in requests accepted by this server, and passed to user callbacks.
     *
     * @param int $methods
     *
     * @see https://secure.php.net/manual/en/eventhttp.setallowedmethods.php
     */
    public function setAllowedMethods(int $methods): void {}

    /**
     * setCallback.
     * Sets a callback for specified URI.
     *
     * @param string $path
     * @param string $cb
     * @param null|string $arg (optional)
     * @return bool
     * @see https://secure.php.net/manual/en/eventhttp.setcallback.php
     */
    public function setCallback(string $path, string $cb, ?string $arg = null): bool {}

    /**
     * setDefaultCallback.
     * Sets default callback to handle requests that are not caught by specific callbacks.
     *
     * @param string $cb
     * @param null|string $arg (optional)
     *
     * @see https://secure.php.net/manual/en/eventhttp.setdefaultcallback.php
     */
    public function setDefaultCallback(string $cb, ?string $arg = null): void {}

    /**
     * setMaxBodySize.
     * Sets maximum request body size.
     *
     * @param int $value
     *
     * @see https://secure.php.net/manual/en/eventhttp.setmaxbodysize.php
     */
    public function setMaxBodySize(int $value): void {}

    /**
     * setMaxHeadersSize.
     * Sets maximum HTTP header size.
     *
     * @param int $value
     *
     * @see https://secure.php.net/manual/en/eventhttp.setmaxheaderssize.php
     */
    public function setMaxHeadersSize(int $value): void {}

    /**
     * setTimeout.
     * Sets the timeout for an HTTP request.
     *
     * @param int $value
     *
     * @see https://secure.php.net/manual/en/eventhttp.settimeout.php
     */
    public function setTimeout(int $value): void {}
}

// The EventHttpConnection class
/**
 * EventHttpConnection.
 * Represents an HTTP connection.
 *
 * @author Kazuaki MABUCHI
 * @copyright Copyright (https://secure.php.net/manual/cc.license.php) by the PHP Documentation Group is licensed under [CC by 3.0 or later](https://creativecommons.org/licenses/by/3.0/).
 *
 * @see https://secure.php.net/manual/en/class.eventhttpconnection.php
 */
class EventHttpConnection
{
    /**
     * __construct.
     * Constructs EventHttpConnection object.
     *
     * @param EventBase       $base
     * @param EventDnsBase    $dns_base
     * @param string          $address
     * @param int             $port
     * @param null|EventSslContext $ctx
     *
     * @see https://secure.php.net/manual/en/eventhttpconnection.construct.php
     */
    #[Pure]
    public function __construct(EventBase $base, EventDnsBase $dns_base, string $address, int $port, ?EventSslContext $ctx = null) {}

    /**
     * getBase.
     * Returns event base associated with the connection.
     *
     * @return false|EventBase
     *
     * @see https://secure.php.net/manual/en/eventhttpconnection.getbase.php
     */
    public function getBase(): false|EventBase {}

    /**
     * getPeer.
     * Gets the remote address and port associated with the connection.
     *
     * @param string &$address
     * @param int    &$port
     *
     * @see https://secure.php.net/manual/en/eventhttpconnection.getpeer.php
     */
    public function getPeer(string &$address, int &$port): void {}

    /**
     * makeRequest.
     * Makes an HTTP request over the specified connection.
     *
     * @param EventHttpRequest $req
     * @param int              $type
     * @param string           $uri
     *
     * @return bool
     *
     * @see https://secure.php.net/manual/en/eventhttpconnection.makerequest.php
     */
    public function makeRequest(EventHttpRequest $req, int $type, string $uri): bool {}

    /**
     * setCloseCallback.
     * Set callback for connection close.
     *
     * @param callable $callback
     * @param mixed    $data     (optional)
     *
     * @see https://secure.php.net/manual/en/eventhttpconnection.setclosecallback.php
     */
    public function setCloseCallback(callable $callback, mixed $data = null): void {}

    /**
     * setLocalAddress.
     * Sets the IP address from which HTTP connections are made.
     *
     * @param string $address
     *
     * @see https://secure.php.net/manual/en/eventhttpconnection.setlocaladdress.php
     */
    public function setLocalAddress(string $address): void {}

    /**
     * setLocalPort.
     * Sets the local port from which connections are made.
     *
     * @param int $port
     *
     * @see https://secure.php.net/manual/en/eventhttpconnection.setlocalport.php
     */
    public function setLocalPort(int $port): void {}

    /**
     * setMaxBodySize.
     * Sets maximum body size for the connection.
     *
     * @param string $max_size
     *
     * @see https://secure.php.net/manual/en/eventhttpconnection.setmaxbodysize.php
     */
    public function setMaxBodySize(string $max_size): void {}

    /**
     * setMaxHeadersSize.
     * Sets maximum header size.
     *
     * @param string $max_size
     *
     * @see https://secure.php.net/manual/en/eventhttpconnection.setmaxheaderssize.php
     */
    public function setMaxHeadersSize(string $max_size): void {}

    /**
     * setRetries.
     * Sets the retry limit for the connection.
     *
     * @param int $retries
     *
     * @see https://secure.php.net/manual/en/eventhttpconnection.setretries.php
     */
    public function setRetries(int $retries): void {}

    /**
     * setTimeout.
     * Sets the timeout for the connection.
     *
     * @param int $timeout
     *
     * @see https://secure.php.net/manual/en/eventhttpconnection.settimeout.php
     */
    public function setTimeout(int $timeout): void {}
}

// The EventHttpRequest class
class EventHttpRequest
{
    public const CMD_GET = 1;
    public const CMD_POST = 2;
    public const CMD_HEAD = 4;
    public const CMD_PUT = 8;
    public const CMD_DELETE = 16;
    public const CMD_OPTIONS = 32;
    public const CMD_TRACE = 64;
    public const CMD_CONNECT = 128;
    public const CMD_PATCH = 256;
    public const INPUT_HEADER = 1;
    public const OUTPUT_HEADER = 2;

    /**
     * EventHttpRequest constructor.
     * @param callable $callback
     * @param mixed $data
     */
    #[Pure]
    public function __construct(
        callable $callback,
        $data = null
    ) {}

    public function addHeader(string $key, string $value, int $type): bool {}

    public function cancel(): void {}

    public function clearHeaders(): void {}

    public function closeConnection(): void {}

    public function findHeader(string $key, string $type): ?string {}

    public function free() {}

    #[Pure]
    public function getCommand(): int {}

    #[Pure]
    public function getConnection(): ?EventHttpConnection {}

    #[Pure]
    public function getHost(): string {}

    #[Pure]
    public function getInputBuffer(): EventBuffer {}

    #[Pure]
    public function getInputHeaders(): array {}

    #[Pure]
    public function getOutputBuffer(): EventBuffer {}

    #[Pure]
    public function getOutputHeaders(): array {}

    #[Pure]
    public function getResponseCode(): int {}

    #[Pure]
    public function getUri(): string {}

    public function removeHeader(string $key, int $type): bool {}

    public function sendError(int $error, ?string $reason = null) {}

    public function sendReply(int $code, string $reason, ?EventBuffer $buf = null) {}

    public function sendReplyChunk(EventBuffer $buf) {}

    public function sendReplyEnd(): void {}

    public function sendReplyStart(int $code, string $reason): void {}
}

//  The EventListener class
/**
 * EventListener.
 * Represents a connection listener.
 *
 * @author Kazuaki MABUCHI
 * @copyright Copyright (https://secure.php.net/manual/cc.license.php) by the PHP Documentation Group is licensed under [CC by 3.0 or later](https://creativecommons.org/licenses/by/3.0/).
 *
 * @see https://secure.php.net/manual/en/class.eventlistener.php
 */
final class EventListener
{
    /**
     * @var int
     */
    #[Immutable]
    public $fd;
    public const OPT_LEAVE_SOCKETS_BLOCKING = 1;
    public const OPT_CLOSE_ON_FREE = 2;
    public const OPT_CLOSE_ON_EXEC = 4;
    public const OPT_REUSEABLE = 8;
    public const OPT_THREADSAFE = 16;
    public const OPT_DISABLED = 32;
    public const OPT_DEFERRED_ACCEPT = 64;

    /**
     * __construct.
     * Creates new connection listener associated with an event base.
     *
     * @param EventBase $base
     * @param callable  $cb
     * @param mixed     $data
     * @param int       $flags
     * @param int       $backlog
     * @param mixed     $target
     *
     * @see https://secure.php.net/manual/en/eventlistener.construct.php
     */
    public function __construct(EventBase $base, callable $cb, mixed $data, int $flags, int $backlog, mixed $target) {}

    /**
     * disable.
     * Disables an event connect listener object.
     *
     * @return bool
     *
     * @see https://secure.php.net/manual/en/eventlistener.disable.php
     */
    public function disable(): bool {}

    /**
     * enable.
     * Enables an event connect listener object.
     *
     * @return bool
     *
     * @see https://secure.php.net/manual/en/eventlistener.enable.php
     */
    public function enable(): bool {}

    public function free(): void {}

    /**
     * getBase.
     * Returns event base associated with the event listener.
     *
     * @see https://secure.php.net/manual/en/eventlistener.getbase.php
     */
    public function getBase(): void {}

    /**
     * getSocketName.
     * Retreives the current address to which the listener's socket is bound.
     *
     * @param string &$address
     * @param mixed  &$port
     *
     * @return bool
     *
     * @see https://secure.php.net/manual/en/eventlistener.getsocketname.php
     */
    public static function getSocketName(string &$address, int &$port): bool {}

    /**
     * setCallback.
     * The setCallback purpose.
     *
     * @param callable $cb
     * @param mixed    $arg = null
     *
     * @see https://secure.php.net/manual/en/eventlistener.setcallback.php
     */
    public function setCallback(callable $cb, mixed $arg = null): void {}

    /**
     * setErrorCallback.
     * Set event listener's error callback.
     *
     * @param string $cb
     *
     * @see https://secure.php.net/manual/en/eventlistener.seterrorcallback.php
     */
    public function setErrorCallback(string $cb): void {}
}

//  The EventSslContext class
/**
 * EventSslContext.
 * Represents SSL_CTX structure. Provides methods and properties to configure the SSL context.
 *
 *
 * @author Kazuaki MABUCHI
 * @copyright Copyright (https://secure.php.net/manual/cc.license.php) by the PHP Documentation Group is licensed under [CC by 3.0 or later](https://creativecommons.org/licenses/by/3.0/).
 *
 * @see https://secure.php.net/manual/en/class.eventsslcontext.php
 */
final class EventSslContext
{
    public const SSLv2_CLIENT_METHOD = 1;
    public const SSLv3_CLIENT_METHOD = 2;
    public const SSLv23_CLIENT_METHOD = 3;
    public const TLS_CLIENT_METHOD = 4;
    public const SSLv2_SERVER_METHOD = 5;
    public const SSLv3_SERVER_METHOD = 6;
    public const SSLv23_SERVER_METHOD = 7;
    public const TLS_SERVER_METHOD = 8;
    public const TLSv11_CLIENT_METHOD = 9;
    public const TLSv11_SERVER_METHOD = 10;
    public const TLSv12_CLIENT_METHOD = 11;
    public const TLSv12_SERVER_METHOD = 12;
    public const OPT_LOCAL_CERT = 1;
    public const OPT_LOCAL_PK = 2;
    public const OPT_PASSPHRASE = 3;
    public const OPT_CA_FILE = 4;
    public const OPT_CA_PATH = 5;
    public const OPT_ALLOW_SELF_SIGNED = 6;
    public const OPT_VERIFY_PEER = 7;
    public const OPT_VERIFY_DEPTH = 8;
    public const OPT_CIPHERS = 9;
    public const OPT_NO_SSLv2 = 10;
    public const OPT_NO_SSLv3 = 11;
    public const OPT_NO_TLSv1 = 12;
    public const OPT_NO_TLSv1_1 = 13;
    public const OPT_NO_TLSv1_2 = 14;
    public const OPT_CIPHER_SERVER_PREFERENCE = 15;
    public const OPT_REQUIRE_CLIENT_CERT = 16;
    public const OPT_VERIFY_CLIENT_ONCE = 17;

    /**
     * @var string
     */
    public $local_cert;

    /**
     * @var string
     */
    public $local_pk;

    /**
     * __construct.
     * Constructs an OpenSSL context for use with Event classes.
     *
     * @param int $method
     * @param array $options
     *
     * @see https://secure.php.net/manual/en/eventsslcontext.construct.php
     */
    #[Pure]
    public function __construct(int $method, array $options) {}

    /**
     * Sets minimum supported protocol version for the SSL context
     * @param int $proto
     * @return bool
     */
    public function setMinProtoVersion(int $proto): bool {}

    /**
     * Sets max supported protocol version for the SSL context.
     * @param int $proto
     * @return bool
     */
    public function setMaxProtoVersion(int $proto): bool {}
}

// The EventUtil class
/**
 * EventUtil.
 * EventUtil is a singleton with supplimentary methods and constants.
 *
 * @author Kazuaki MABUCHI
 * @copyright Copyright (https://secure.php.net/manual/cc.license.php) by the PHP Documentation Group is licensed under [CC by 3.0 or later](https://creativecommons.org/licenses/by/3.0/).
 *
 * @see https://secure.php.net/manual/en/class.eventutil.php
 */
final class EventUtil
{
    public const AF_INET = 2;
    public const AF_INET6 = 10;
    public const AF_UNIX = 1;
    public const AF_UNSPEC = 0;
    public const LIBEVENT_VERSION_NUMBER = 33559808;
    public const SO_DEBUG = 1;
    public const SO_REUSEADDR = 2;
    public const SO_KEEPALIVE = 9;
    public const SO_DONTROUTE = 5;
    public const SO_LINGER = 13;
    public const SO_BROADCAST = 6;
    public const SO_OOBINLINE = 10;
    public const SO_SNDBUF = 7;
    public const SO_RCVBUF = 8;
    public const SO_SNDLOWAT = 19;
    public const SO_RCVLOWAT = 18;
    public const SO_SNDTIMEO = 21;
    public const SO_RCVTIMEO = 20;
    public const SO_TYPE = 3;
    public const SO_ERROR = 4;
    public const SOL_SOCKET = 1;
    public const SOL_TCP = 6;
    public const SOL_UDP = 17;
    public const SOCK_RAW = 3;
    public const TCP_NODELAY = 1;
    public const IPPROTO_IP = 0;
    public const IPPROTO_IPV6 = 41;

    /**
     * __construct.
     * The abstract constructor.
     *
     * @see https://secure.php.net/manual/en/eventutil.construct.php
     */
    abstract public function __construct();

    /**
     * @param mixed $socket
     * @return resource
     */
    public function createSocket(mixed $socket) {}

    /**
     * getLastSocketErrno.
     * Returns the most recent socket error number.
     *
     * @param mixed $socket = null
     *
     * @return int|false
     *
     * @see https://secure.php.net/manual/en/eventutil.getlastsocketerrno.php
     */
    public static function getLastSocketErrno($socket = null): int|false {}

    /**
     * getLastSocketError.
     * Returns the most recent socket error.
     *
     * @param mixed $socket
     *
     * @return string|false
     *
     * @see https://secure.php.net/manual/en/eventutil.getlastsocketerror.php
     */
    public static function getLastSocketError(mixed $socket): string|false {}

    /**
     * getSocketFd.
     * Returns numeric file descriptor of a socket, or stream.
     *
     * @param mixed $socket
     *
     * @return int
     *
     * @see https://secure.php.net/manual/en/eventutil.getsocketfd.php
     */
    public static function getSocketFd(mixed $socket): int {}

    /**
     * getSocketName.
     * Retreives the current address to which the socket is bound.
     *
     * @param mixed $socket
     * @param string &$address
     * @param mixed  &$port
     *
     * @return bool
     *
     * @see https://secure.php.net/manual/en/eventutil.getsocketname.php
     */
    public static function getSocketName(mixed $socket, string &$address, int &$port): bool {}

    /**
     * setSocketOption.
     * Sets socket options.
     *
     * @param mixed $socket
     * @param int   $level
     * @param int   $optname
     * @param int|array $optval
     *
     * @return bool
     *
     * @see https://secure.php.net/manual/en/eventutil.setsocketoption.php
     */
    public static function setSocketOption(mixed $socket, int $level, int $optname, int|array $optval): bool {}

    /**
     * sslRandPoll.
     * Generates entropy by means of OpenSSL's RAND_poll().
     *
     * @see https://secure.php.net/manual/en/eventutil.sslrandpoll.php
     */
    public static function sslRandPoll(): bool {}
}
