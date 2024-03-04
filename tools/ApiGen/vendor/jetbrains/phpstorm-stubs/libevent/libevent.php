<?php

// Start of PECL libevent v.0.0.4

// Libevent documentation:
// http://www.wangafu.net/~nickm/libevent-book/

// PHP Libevent extension documentation:
// https://php.net/libevent

// Event flags

/**
 * This flag indicates an event that becomes active after a timeout elapses.
 *
 * The EV_TIMEOUT flag is ignored when constructing an event: you
 * can either set a timeout when you add the event, or not.  It is
 * set in the 'what' argument to the callback function when a timeout
 * has occurred.
 */
define('EV_TIMEOUT', 1);

/**
 * This flag indicates an event that becomes active when the provided
 * file descriptor is ready for reading.
 */
define('EV_READ', 2);

/**
 * This flag indicates an event that becomes active when the provided
 * file descriptor is ready for writing.
 */
define('EV_WRITE', 4);

/**
 * Used to implement signal detection.
 */
define('EV_SIGNAL', 8);

/**
 * Indicates that the event is persistent.
 *
 * By default, whenever a pending event becomes active
 * (because its fd is ready to read or write, or because its timeout expires),
 * it becomes non-pending right before its callback is executed.
 * Thus, if you want to make the event pending again, you can call event_add()
 * on it again from inside the callback function.
 *
 * If the EV_PERSIST flag is set on an event, however, the event is persistent.
 * This means that event remains pending even when its callback is activated.
 * If you want to make it non-pending from within its callback, you can call
 * event_del() on it.
 *
 * The timeout on a persistent event resets whenever the event's callback runs.
 * Thus, if you have an event with flags EV_READ|EV_PERSIST and a timeout of five
 * seconds, the event will become active:
 *
 * Whenever the socket is ready for reading.
 *
 * Whenever five seconds have passed since the event last became active.
 */
define('EV_PERSIST', 16);

// Event loop modes

/**
 * Event base loop mode.
 * Starts only one iteration of loop.
 *
 * @see event_base_loop
 */
define('EVLOOP_ONCE', 1);

/**
 * Event base loop mode.
 * Not wait for events to trigger, only check whether
 * any events are ready to trigger immediately.
 *
 * @see event_base_loop
 */
define('EVLOOP_NONBLOCK', 2);

// Buffered event error codes (second argument in buffer's error-callback)

/**
 * An event occurred during a read operation on the
 * bufferevent. See the other flags for which event it was.
 */
define('EVBUFFER_READ', 1);

/**
 * An event occurred during a write operation on the bufferevent.
 * See the other flags for which event it was.
 */
define('EVBUFFER_WRITE', 2);

/**
 * We finished a requested connection on the bufferevent.
 */
define('EVBUFFER_EOF', 16);

/**
 * An error occurred during a bufferevent operation. For more information
 * on what the error was, call {@link socket_strerror}().
 */
define('EVBUFFER_ERROR', 32);

/**
 * A timeout expired on the bufferevent.
 */
define('EVBUFFER_TIMEOUT', 64);

/**
 * <p>Create and initialize new event base</p>
 *
 * <p>Returns new event base, which can be used later in {@link event_base_set}(), {@link event_base_loop}() and other functions.</p>
 *
 * @link https://php.net/event_base_new
 *
 * @return resource|false returns valid event base resource on success or FALSE on error.
 */
function event_base_new() {}

/**
 * <p>Destroy event base</p>
 * <p>(PECL libevent >= 0.0.1)</p>
 *
 * <p>Destroys the specified event_base and frees all the resources associated.
 * Note that it's not possible to destroy an event base with events attached to it.</p>
 *
 * @link https://php.net/event_base_free
 *
 * @param resource $event_base Valid event base resource.
 *
 * @return void
 */
function event_base_free($event_base) {}

/**
 * <p>Handle events</p>
 * <p>(PECL libevent >= 0.0.1)</p>
 *
 * <p>Starts event loop for the specified event base.</p>
 *
 * <p>By default, the {@link event_base_loop}() function runs an event_base until
 * there are no more events registered in it. To run the loop, it repeatedly
 * checks whether any of the registered events has triggered (for example,
 * if a read event's file descriptor is ready to read, or if a timeout event's
 * timeout is ready to expire). Once this happens, it marks all triggered events
 * as "active", and starts to run them.
 * </p>
 *
 * <p>You can change the behavior of event_base_loop() by setting one or more flags
 * in its flags argument. If EVLOOP_ONCE is set, then the loop will wait until some
 * events become active, then run active events until there are no more to run, then
 * return. If EVLOOP_NONBLOCK is set, then the loop will not wait for events to trigger:
 * it will only check whether any events are ready to trigger immediately,
 * and run their callbacks if so.
 * </p>
 *
 * @link https://php.net/event_base_loop
 *
 * @param resource $event_base Valid event base resource.
 * @param int $flags [optional] Optional parameter, which can take any combination of EVLOOP_ONCE and EVLOOP_NONBLOCK.
 *
 * @return int <p>
 * Returns 0 if it exited normally,
 * -1 if it exited because of some unhandled error in the backend
 * and 1 if no events were registered.
 * </p>
 */
function event_base_loop($event_base, $flags = null) {}

/**
 * <p>Tells the event_base to exit its loop immediately.</p>
 * <p>(PECL libevent >= 0.0.1)</p>
 *
 * <p>It differs from {@link event_base_loopexit}() in that if the event_base is currently
 * running callbacks for any active events, it will exit immediately after finishing the
 * one it's currently processing. The behaviour is similar to break statement.</p>
 *
 * @link https://php.net/event_base_loopbreak
 *
 * @param resource $event_base Valid event base resource.
 *
 * @return bool returns TRUE on success or FALSE on error.
 */
function event_base_loopbreak($event_base) {}

/**
 * <p>Tells an event_base to stop looping after a given time has elapsed</p>
 * <p>(PECL libevent >= 0.0.1)</p>
 *
 * <p>If the event_base is currently running callbacks for any active events,
 * it will continue running them, and not exit until they have all been run.</p>
 *
 * <p>If event loop isn't running {@link event_base_loopexit}() schedules the next instance
 * of the event loop to stop right after the next round of callbacks are run (as if it had
 * been invoked with EVLOOP_ONCE).</p>
 *
 * @link https://php.net/event_base_loopexit
 *
 * @param resource $event_base <p>
 * Valid event base resource.
 * </p>
 * @param int $timeout [optional] <p>
 * Optional timeout parameter (in microseconds). If lower than 1,
 * the event_base stops looping without a delay.
 * </p>
 *
 * @return bool returns TRUE on success or FALSE on error.
 */
function event_base_loopexit($event_base, $timeout = -1) {}

/**
 * <p>Associate event base with an event</p>
 * <p>(PECL libevent >= 0.0.1)</p>
 *
 * <p>Associates the event_base with the event.</p>
 *
 * @link https://php.net/event_base_set
 *
 * @param resource $event Valid event resource.
 * @param resource $base Valid event base resource.
 *
 * @return bool returns TRUE on success or FALSE on error.
 */
function event_base_set($event, $base) {}

/**
 * <p>Set the number of different event priority levels</p>
 * <p>(PECL libevent >= 0.0.2)</p>
 *
 * <p>By default all events are scheduled with the same priority (npriorities/2).
 * Using {@link event_base_priority_init}() you can change the number of event priority
 * levels and then set a desired priority for each event.</p>
 *
 * @link https://php.net/event_base_priority_init
 *
 * @param resource $event_base Valid event base resource.
 * @param int $npriorities The number of event priority levels.
 *
 * @return bool returns TRUE on success or FALSE on error.
 */
function event_base_priority_init($event_base, $npriorities) {}

/**
 * <p>Creates and returns a new event resource.</p>
 * <p>(PECL libevent >= 0.0.1)</p>
 *
 * @link https://php.net/event_new
 *
 * @return resource|false returns a new event resource on success or FALSE on error.
 */
function event_new() {}

/**
 * <p>Free event resource.</p>
 * <p>(PECL libevent >= 0.0.1)</p>
 *
 * @link https://php.net/event_free
 *
 * @param resource $event Valid event resource.
 *
 * @return void
 */
function event_free($event) {}

/**
 * <p>Add an event to the set of monitored events</p>
 * <p>(PECL libevent >= 0.0.1)</p>
 *
 * <p>Schedules the execution of the non-pending event (makes it pending in it's
 * configured base) when the event specified in {@link event_set}() occurs or in
 * at least the time specified by the timeout argument. If timeout was not specified,
 * not timeout is set. The event must be already initialized by
 * {@link event_set}() and {@link event_base_set}() functions.
 * If the event already has a timeout set,
 * it is replaced by the new one.</p>
 *
 * <p>If you call {@link event_add}() on an event that is already pending,
 * it will leave it pending, and reschedule it with the provided timeout.</p>
 *
 * @link https://php.net/event_add
 *
 * @param resource $event <p>
 * Valid event resource.
 * </p>
 * @param int $timeout [optional] <p>
 * Optional timeout (in microseconds).
 * </p>
 *
 * @return bool returns TRUE on success or FALSE on error.
 */
function event_add($event, $timeout = -1) {}

/**
 * <p>Prepares the event to be used in {@link event_add}().</p>
 * <p>(PECL libevent >= 0.0.1)</p>
 *
 * <p>The event is prepared to call the function specified by the callback
 * on the events specified in parameter events, which is a set of the following
 * flags: EV_TIMEOUT, EV_SIGNAL, EV_READ, EV_WRITE and EV_PERSIST.</p>
 *
 * <p>EV_SIGNAL support was added in version 0.0.4</p>
 *
 * <p>After initializing the event, use {@link event_base_set}() to associate the event with its event base.</p>
 *
 * <p>In case of matching event, these three arguments are passed to the callback function:
 * <table>
 * 	<tr>
 * 		<td><b>$fd</b></td>
 * 		<td>Signal number or resource indicating the stream.</td>
 * 	</tr>
 * 	<tr>
 * 		<td><b>$events</b></td>
 * 		<td>A flag indicating the event. Consists of the following flags: EV_TIMEOUT, EV_SIGNAL, EV_READ, EV_WRITE and EV_PERSIST.</td>
 * 	</tr>
 * 	<tr>
 * 		<td><b>$arg</b></td>
 * 		<td>Optional parameter, previously passed to {@link event_set}() as arg.</td>
 * 	</tr>
 * </table>
 * </p>
 *
 * @link https://php.net/event_set
 *
 * @param resource $event <p>
 * Valid event resource.
 * </p>
 * @param resource|int $fd <p>
 * Valid PHP stream resource. The stream must be castable to file descriptor,
 * so you most likely won't be able to use any of filtered streams.
 * </p>
 * @param int $events <p>
 * A set of flags indicating the desired event, can be EV_READ and/or EV_WRITE.
 * The additional flag EV_PERSIST makes the event to persist until {@link event_del}() is
 * called, otherwise the callback is invoked only once.
 * </p>
 * @param callable $callback <p>
 * Callback function to be called when the matching event occurs.
 * </p>
 * @param mixed $arg [optional] <p>
 * Optional callback parameter.
 * </p>
 *
 * @return bool returns TRUE on success or FALSE on error.
 */
function event_set($event, $fd, $events, $callback, $arg = null) {}

/**
 * <p>Remove an event from the set of monitored events.</p>
 * <p>(PECL libevent >= 0.0.1)</p>
 *
 * <p>Calling {@link event_del}() on an initialized event makes it non-pending
 * and non-active. If the event was not pending or active, there is no effect.</p>
 *
 * @link https://php.net/event_del
 *
 * @param resource $event Valid event resource.
 *
 * @return bool returns TRUE on success or FALSE on error.
 */
function event_del($event) {}

/**
 * <p>Create new buffered event</p>
 * <p>(PECL libevent >= 0.0.1)</p>
 *
 * <p>Libevent provides an abstraction layer on top of the regular event API.
 * Using buffered event you don't need to deal with the I/O manually, instead
 * it provides input and output buffers that get filled and drained automatically.</p>
 *
 * <p>Every bufferevent has two data-related callbacks: a read callback and a write
 * callback. By default, the read callback is called whenever any data is read from
 * the underlying transport, and the write callback is called whenever enough data
 * from the output buffer is emptied to the underlying transport. You can override
 * the behavior of these functions by adjusting the read and write "watermarks"
 * of the bufferevent (see {@link event_buffer_watermark_set}()).</p>
 *
 * <p>A bufferevent also has an "error" or "event" callback that gets invoked to tell
 * the application about non-data-oriented events, like when a connection is closed or
 * an error occurs.</p>
 *
 * @link https://php.net/event_buffer_new
 *
 * @param resource      $stream  Valid PHP stream resource. Must be castable to file descriptor.
 * @param callable|null $readcb  Callback to invoke where there is data to read, or NULL if no callback is desired.
 * @param callable|null $writecb Callback to invoke where the descriptor is ready for writing, or NULL if no callback is desired.
 * @param callable      $errorcb Callback to invoke where there is an error on the descriptor, cannot be NULL.
 * @param mixed         $arg     An argument that will be passed to each of the callbacks (optional).
 *
 * @return resource|false returns new buffered event resource on success or FALSE on error.
 */
function event_buffer_new($stream, $readcb, $writecb, $errorcb, $arg = null) {}

/**
 * <p>Destroys the specified buffered event and frees all the resources associated.</p>
 * <p>(PECL libevent >= 0.0.1)</p>
 *
 * @link https://php.net/event_buffer_free
 *
 * @param resource $bevent Valid buffered event resource.
 *
 * @return void
 */
function event_buffer_free($bevent) {}

/**
 * <p>Associate buffered event with an event base</p>
 * <p>(PECL libevent >= 0.0.1)</p>
 *
 * <p>Assign the specified bevent to the event_base.</p>
 *
 * @link https://php.net/event_buffer_base_set
 *
 * @param resource $bevent Valid buffered event resource.
 * @param resource $event_base Valid event base resource.
 *
 * @return bool returns TRUE on success or FALSE on error.
 */
function event_buffer_base_set($bevent, $event_base) {}

/**
 * <p>Assign a priority to a buffered event. Use it after
 * initializing event, but before adding an event to the event_base.</p>
 * <p>(PECL libevent >= 0.0.1)</p>
 *
 * <p>When multiple events trigger at the same time, Libevent
 * does not define any order with respect to when their callbacks
 * will be executed. You can define some events as more important
 * than others by using priorities.</p>
 *
 * <p>When multiple events of multiple priorities become active,
 * the low-priority events are not run. Instead, Libevent runs
 * the high priority events, then checks for events again. Only
 * when no high-priority events are active are the low-priority
 * events run.</p>
 *
 * <p>When you do not set the priority for an event, the default
 * is the number of queues in the event base, divided by 2.</p>
 *
 * @link https://php.net/event_buffer_priority_set
 *
 * @see event_base_priority_init
 *
 * @param resource $bevent <p>
 * Valid buffered event resource.
 * </p>
 * @param int $priority <p>
 * Priority level. Cannot be less than 0 and cannot exceed
 * maximum priority level of the event base (see {@link event_base_priority_init}()).
 * </p>
 *
 * @return bool returns TRUE on success or FALSE on error.
 */
function event_buffer_priority_set($bevent, $priority) {}

/**
 * <p>Writes data to the specified buffered event.</p>
 * <p>(PECL libevent >= 0.0.1)</p>
 *
 * <p>The data is appended to the output buffer and written
 * to the descriptor when it becomes available for writing.</p>
 *
 * @link https://php.net/event_buffer_write
 *
 * @param resource $bevent Valid buffered event resource.
 * @param string $data The data to be written.
 * @param int $data_size Optional size parameter. {@link event_buffer_write}() writes all the data by default
 *
 * @return bool returns TRUE on success or FALSE on error.
 */
function event_buffer_write($bevent, $data, $data_size = -1) {}

/**
 * <p>Reads data from the input buffer of the buffered event.</p>
 * <p>(PECL libevent >= 0.0.1)</p>
 *
 * @link https://php.net/event_buffer_read
 *
 * @param resource $bevent Valid buffered event resource.
 * @param int $data_size Data size in bytes.
 *
 * @return string
 */
function event_buffer_read($bevent, $data_size) {}

/**
 * <p>Enables the specified buffered event.</p>
 * <p>(PECL libevent >= 0.0.1)</p>
 *
 * @link https://php.net/event_buffer_enable
 *
 * @param resource $bevent Valid buffered event resource.
 * @param int $events Any combination of EV_READ and EV_WRITE.
 *
 * @return bool returns TRUE on success or FALSE on error.
 */
function event_buffer_enable($bevent, $events) {}

/**
 * <p>Disable a buffered event</p>
 * <p>(PECL libevent >= 0.0.1)</p>
 *
 * <p>Disables the specified buffered event.</p>
 *
 * @link https://php.net/event_buffer_disable
 *
 * @param resource $bevent Valid buffered event resource.
 * @param int $events Any combination of EV_READ and EV_WRITE.
 *
 * @return bool returns TRUE on success or FALSE on error.
 */
function event_buffer_disable($bevent, $events) {}

/**
 * <p>Sets the read and write timeouts for the specified buffered event.</p>
 * <p>(PECL libevent >= 0.0.1)</p>
 *
 * @link https://php.net/event_buffer_timeout_set
 *
 * @param resource $bevent Valid buffered event resource.
 * @param int $read_timeout Read timeout (in seconds).
 * @param int $write_timeout Write timeout (in seconds).
 *
 * @return void
 */
function event_buffer_timeout_set($bevent, $read_timeout, $write_timeout) {}

/**
 * <p>Set the watermarks for read and write events.</p>
 * <p>(PECL libevent >= 0.0.1)</p>
 *
 * <p>Every bufferevent has four watermarks:</p>
 *
 * <p><b>Read low-water mark</b><br/>
 * Whenever a read occurs that leaves the bufferevent's input buffer at this
 * level or higher, the bufferevent's read callback is invoked. Defaults to 0,
 * so that every read results in the read callback being invoked.</p>
 *
 * <p><b>Read high-water mark</b><br/>
 * If the bufferevent's input buffer ever gets to this level, the bufferevent
 * stops reading until enough data is drained from the input buffer to take us
 * below it again. Defaults to unlimited, so that we never stop reading because
 * of the size of the input buffer.</p>
 *
 * <p><b>Write low-water mark</b><br/>
 * Whenever a write occurs that takes us to this level or below, we invoke the write
 * callback. Defaults to 0, so that a write callback is not invoked unless the output
 * buffer is emptied.</p>
 *
 * <p><b>Write high-water mark</b><br/>
 * Not used by a bufferevent directly, this watermark can have special meaning when
 * a bufferevent is used as the underlying transport of another bufferevent.</p>
 *
 * <p>Libevent does not invoke read callback unless there is at least lowmark
 * bytes in the input buffer; if the read buffer is beyond the highmark,
 * reading is stopped. On output, the write callback is invoked whenever
 * the buffered data falls below the lowmark.</p>
 *
 * @link https://php.net/event_buffer_watermark_set
 *
 * @param resource $bevent   Valid buffered event resource.
 * @param int      $events   Any combination of EV_READ and EV_WRITE.
 * @param int      $lowmark  Low watermark.
 * @param int      $highmark High watermark.
 *
 * @return void
 */
function event_buffer_watermark_set($bevent, $events, $lowmark, $highmark) {}

/**
 * <p>Changes the file descriptor on which the buffered event operates.</p>
 * <p>(PECL libevent >= 0.0.1)</p>
 *
 * @link https://php.net/event_buffer_fd_set
 *
 * @param resource $bevent Valid buffered event resource.
 * @param resource $fd Valid PHP stream, must be castable to file descriptor.
 *
 * @return void
 */
function event_buffer_fd_set($bevent, $fd) {}

/**
 * <p>Set or reset callbacks for a buffered event</p>
 * <p>(PECL libevent >= 0.0.4)</p>
 *
 * <p>Sets or changes existing callbacks for the buffered event.</p>
 *
 * @link https://php.net/event_buffer_set_callback
 *
 * @param resource $bevent Valid buffered event resource.
 * @param callable|null $readcb Callback to invoke where there is data to read, or NULL if no callback is desired.
 * @param callable|null $writecb Callback to invoke where the descriptor is ready for writing, or NULL if no callback is desired.
 * @param callable $errorcb Callback to invoke where there is an error on the descriptor, cannot be NULL.
 * @param mixed $arg An argument that will be passed to each of the callbacks (optional).
 *
 * @return bool returns TRUE on success or FALSE on error.
 */
function event_buffer_set_callback($bevent, $readcb, $writecb, $errorcb, $arg = null) {}

/**
 * <p>Alias of {@link event_new}().</p>
 *
 * @return resource|false returns valid event base resource on success or FALSE on error.
 */
function event_timer_new() {}

/**
 * <p>Prepares the timer event to be used in {@link event_add}().</p>
 *
 * <p>The event is prepared to call the function specified by the callback
 * on the timeout event (EV_TIMEOUT).</p>
 *
 * <p>After initializing the event, use {@link event_base_set}() to associate the event with its event base.</p>
 *
 * <p>In case of matching event, these three arguments are passed to the callback function:
 * <table>
 * 	<tr>
 * 		<td><b>$fd</b></td>
 * 		<td>null</td>
 * 	</tr>
 * 	<tr>
 * 		<td><b>$events</b></td>
 * 		<td>A flag indicating the event. EV_TIMEOUT.</td>
 * 	</tr>
 * 	<tr>
 * 		<td><b>$arg</b></td>
 * 		<td>Optional parameter, previously passed to {@link event_timer_set}() as arg.</td>
 * 	</tr>
 * </table>
 * </p>
 *
 * @param resource $event <p>
 * Valid event resource.
 * </p>
 * @param callable $callback <p>
 * Callback function to be called when the matching event occurs.
 * </p>
 * @param mixed $arg [optional] <p>
 * Optional callback parameter.
 * </p>
 *
 * @return void
 */
function event_timer_set($event, $callback, $arg = null) {}

/**
 * <p>Checks if a specific event is pending or scheduled.</p>
 *
 * @param resource $event <p>
 * Valid event resource.
 * </p>
 * @param int $timeout [optional] <p>
 * Optional timeout (in microseconds).
 * </p>
 *
 * @return bool TRUE if event is not scheduled (added) FALSE otherwise
 */
function event_timer_pending($event, $timeout = -1) {}

/**
 * <p>Alias of {@link event_add}().</p>
 *
 * @param resource $event <p>
 * Valid event resource.
 * </p>
 * @param int $timeout [optional] <p>
 * Optional timeout (in microseconds).
 * </p>
 *
 * @return bool returns TRUE on success or FALSE on error.
 */
function event_timer_add($event, $timeout = -1) {}

/**
 * <p>Alias of {@link event_del}().</p>
 *
 * @param resource $event Valid event resource.
 *
 * @return bool returns TRUE on success or FALSE on error.
 */
function event_timer_del($event) {}

// End of PECL libevent v.0.0.4
