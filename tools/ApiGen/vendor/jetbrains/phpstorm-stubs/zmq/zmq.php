<?php
/**
 * Helper autocomplete for php zmq extension
 * @author Boris Gorbylev <ekho@ekho.name>
 * @link https://github.com/i-ekho/zmq-phpdoc
 */
/**
 * Class ZMQ
 * @link https://secure.php.net/manual/en/class.zmq.php
 */
class ZMQ
{
    /**
     * Exclusive pair pattern
     */
    public const SOCKET_PAIR = 0;

    /**
     * Publisher socket
     */
    public const SOCKET_PUB = 1;

    /**
     * Subscriber socket
     */
    public const SOCKET_SUB = 2;

    /**
     * Request socket
     */
    public const SOCKET_REQ = 3;

    /**
     * Reply socket
     */
    public const SOCKET_REP = 4;

    /**
     * Alias for SOCKET_DEALER
     */
    public const SOCKET_XREQ = 5;

    /**
     * Alias for SOCKET_ROUTER
     */
    public const SOCKET_XREP = 6;

    /**
     * Pipeline upstream push socket
     */
    public const SOCKET_PUSH = 8;

    /**
     * Pipeline downstream pull socket
     */
    public const SOCKET_PULL = 7;

    /**
     * Extended REP socket that can route replies to requesters
     */
    public const SOCKET_ROUTER = 6;

    /**
     * Extended REQ socket that load balances to all connected peers
     */
    public const SOCKET_DEALER = 5;

    /**
     * Similar to SOCKET_PUB, except you can receive subscriptions as messages.
     * The subscription message is 0 (unsubscribe) or 1 (subscribe) followed by the topic.
     */
    public const SOCKET_XPUB = 9;

    /**
     * Similar to SOCKET_SUB, except you can send subscriptions as messages. See SOCKET_XPUB for format.
     */
    public const SOCKET_XSUB = 10;

    /**
     * Used to send and receive TCP data from a non-ØMQ peer.
     * Available if compiled against ZeroMQ 4.x or higher.
     */
    public const SOCKET_STREAM = 11;

    /**
     * The high water mark for inbound and outbound messages is a hard
     * limit on the maximum number of outstanding messages ØMQ shall queue in memory
     * for any single peer that the specified socket is communicating with.
     * Setting this option on a socket will only affect connections made after the option has been set.
     * On ZeroMQ 3.x this is a wrapper for setting both SNDHWM and RCVHWM.
     */
    public const SOCKOPT_HWM = 1;

    /**
     * The ZMQ_SNDHWM option shall set the high water mark for outbound messages on the specified socket.
     * Available if compiled against ZeroMQ 3.x or higher.
     */
    public const SOCKOPT_SNDHWM = 23;

    /**
     * The ZMQ_SNDHWM option shall set the high water mark for inbound messages on the specified socket.
     * Available if compiled against ZeroMQ 3.x or higher.
     */
    public const SOCKOPT_RCVHWM = 24;

    /**
     * Set I/O thread affinity
     */
    public const SOCKOPT_AFFINITY = 4;

    /**
     * Set socket identity
     */
    public const SOCKOPT_IDENTITY = 5;

    /**
     * Establish message filter. Valid for subscriber socket
     */
    public const SOCKOPT_SUBSCRIBE = 6;

    /**
     * Remove message filter. Valid for subscriber socket
     */
    public const SOCKOPT_UNSUBSCRIBE = 7;

    /**
     * Set rate for multicast sockets (pgm) (Value: int >= 0)
     */
    public const SOCKOPT_RATE = 8;

    /**
     * Set multicast recovery interval (Value: int >= 0)
     */
    public const SOCKOPT_RECOVERY_IVL = 9;

    /**
     * Set the initial reconnection interval (Value: int >= 0)
     */
    public const SOCKOPT_RECONNECT_IVL = 18;

    /**
     * Set the max reconnection interval (Value: int >= 0)
     */
    public const SOCKOPT_RECONNECT_IVL_MAX = 21;

    /**
     * Control multicast loopback (Value: int >= 0)
     */
    public const SOCKOPT_MCAST_LOOP = 10;

    /**
     * Set kernel transmit buffer size (Value: int >= 0)
     */
    public const SOCKOPT_SNDBUF = 11;

    /**
     * Set kernel receive buffer size (Value: int >= 0)
     */
    public const SOCKOPT_RCVBUF = 12;

    /**
     * Receive multi-part messages
     */
    public const SOCKOPT_RCVMORE = 13;

    /**
     * Get the socket type. Valid for getSockOpt
     */
    public const SOCKOPT_TYPE = 16;

    /**
     * The linger value of the socket.
     * Specifies how long the socket blocks trying flush messages after it has been closed
     */
    public const SOCKOPT_LINGER = 17;

    /**
     * The SOCKOPT_BACKLOG option shall set the maximum length of the queue of outstanding peer connections
     * for the specified socket; this only applies to connection-oriented transports.
     */
    public const SOCKOPT_BACKLOG = 19;

    /**
     * Limits the maximum size of the inbound message. Value -1 means no limit.
     * Available if compiled against ZeroMQ 3.x or higher
     */
    public const SOCKOPT_MAXMSGSIZE = 22;

    /**
     * Sets the timeout for send operation on the socket. Value -1 means no limit.
     * Available if compiled against ZeroMQ 3.x or higher
     */
    public const SOCKOPT_SNDTIMEO = 28;

    /**
     * Sets the timeout for receive operation on the socket. Value -1 means no limit.
     * Available if compiled against ZeroMQ 3.x or higher
     */
    public const SOCKOPT_RCVTIMEO = 27;

    /**
     * Disable IPV6 support if 1.
     * Available if compiled against ZeroMQ 3.x
     */
    public const SOCKOPT_IPV4ONLY = 31;

    /**
     * Retrieve the last connected endpoint - for use with * wildcard ports.
     * Available if compiled against ZeroMQ 3.x or higher
     */
    public const SOCKOPT_LAST_ENDPOINT = 32;

    /**
     * Idle time for TCP keepalive.
     * Available if compiled against ZeroMQ 3.x or higher
     */
    public const SOCKOPT_TCP_KEEPALIVE_IDLE = 36;

    /**
     * Count time for TCP keepalive.
     * Available if compiled against ZeroMQ 3.x or higher
     */
    public const SOCKOPT_TCP_KEEPALIVE_CNT = 35;

    /**
     * Interval for TCP keepalive.
     * Available if compiled against ZeroMQ 3.x or higher
     */
    public const SOCKOPT_TCP_KEEPALIVE_INTVL = 37;

    /**
     * Set a CIDR string to match against incoming TCP connections.
     * Available if compiled against ZeroMQ 3.x or higher
     */
    public const SOCKOPT_DELAY_ATTACH_ON_CONNECT = 39;

    /**
     * Set a CIDR string to match against incoming TCP connections.
     * Available if compiled against ZeroMQ 3.x or higher
     */
    public const SOCKOPT_TCP_ACCEPT_FILTER = 38;

    /**
     * Set the XPUB to receive an application message on each instance of a subscription.
     * Available if compiled against ZeroMQ 3.x or higher
     */
    public const SOCKOPT_XPUB_VERBOSE = 40;

    /**
     * Sets the raw mode on the ROUTER, when set to 1.
     * In raw mode when using tcp:// transport the socket will read and write without ZeroMQ framing.
     * Available if compiled against ZeroMQ 4.0 or higher
     */
    public const SOCKOPT_ROUTER_RAW = 41;

    /**
     * Enable IPV6.
     * Available if compiled against ZeroMQ 4.0 or higher
     */
    public const SOCKOPT_IPV6 = 42;

    /**
     * The socket limit for this context.
     * Available if compiled against ZeroMQ 3.x or higher
     */
    public const CTXOPT_MAX_SOCKETS = 2;

    /**
     * Poll for incoming data
     */
    public const POLL_IN = 1;

    /**
     * Poll for outgoing data
     */
    public const POLL_OUT = 2;

    /**
     * Non-blocking operation.
     * @deprecated use ZMQ::MODE_DONTWAIT instead
     */
    public const MODE_NOBLOCK = 1;

    /**
     * Non-blocking operation
     */
    public const MODE_DONTWAIT = 1;

    /**
     * Send multi-part message
     */
    public const MODE_SNDMORE = 2;

    /**
     * Forwarder device
     */
    public const DEVICE_FORWARDER = 2;

    /**
     * Queue device
     */
    public const DEVICE_QUEUE = 3;

    /**
     * Streamer device
     */
    public const DEVICE_STREAMER = 1;

    /**
     * ZMQ extension internal error
     */
    public const ERR_INTERNAL = -99;

    /**
     * Implies that the operation would block when ZMQ::MODE_DONTWAIT is used
     */
    public const ERR_EAGAIN = 11;

    /**
     * The operation is not supported by the socket type
     */
    public const ERR_ENOTSUP = 156384713;

    /**
     * The operation can not be executed because the socket is not in correct state
     */
    public const ERR_EFSM = 156384763;

    /**
     * The context has been terminated
     */
    public const ERR_ETERM = 156384765;

    /**
     * Private constructor to prevent direct initialization. This class holds the constants for ZMQ extension.
     * @link https://secure.php.net/manual/en/zmq.construct.php
     */
    private function __construct() {}
}
/**
 * Class ZMQContext
 * @link https://secure.php.net/manual/en/class.zmqcontext.php
 */
class ZMQContext
{
    /**
     * Constructs a new ZMQ context. The context is used to initialize sockets.
     * A persistent context is required to initialize persistent sockets.
     *
     * @link https://secure.php.net/manual/en/zmqcontext.construct.php
     *
     * @param int $io_threads Number of io-threads in the context
     * @param bool $is_persistent Whether the context is persistent. Persistent context is stored over multiple requests and is a requirement for persistent sockets.
     */
    public function __construct($io_threads = 1, $is_persistent = true) {}

    /**
     * (PECL zmq &gt;= 1.0.4)
     * Returns the value of a context option.
     *
     * @link https://secure.php.net/manual/en/zmqcontext.getopt.php
     *
     * @param string $key An int representing the option. See the <b>ZMQ::CTXOPT_*</b> constants.
     * @return string|int Returns either a string or an integer depending on key. Throws ZMQContextException on error.
     * @throws ZMQContextException
     */
    public function getOpt($key) {}

    /**
     * (PECL zmq &gt;= 0.5.0)
     * Shortcut for creating new sockets from the context.
     * If the context is not persistent the persistent_id parameter is ignored
     * and the socket falls back to being non-persistent.
     * The on_new_socket is called only when a new underlying socket structure is created.
     *
     * @link https://secure.php.net/manual/en/zmqcontext.getsocket.php
     *
     * @param int $type <b>ZMQ::SOCKET_*</b> constant to specify socket type.
     * @param string $persistent_id If persistent_id is specified the socket will be persisted over multiple requests.
     * @param callable $on_new_socket Callback function, which is executed when a new socket structure is created. This function does not get invoked if the underlying persistent connection is re-used. The callback takes ZMQSocket and persistent_id as two arguments.
     * @return ZMQSocket
     * @throws ZMQSocketException
     */
    public function getSocket($type, $persistent_id = null, $on_new_socket = null) {}

    /**
     * (PECL zmq &gt;= 0.5.0)
     * Whether the context is persistent.
     * Persistent context is needed for persistent connections as each socket is allocated from a context.
     *
     * @link https://secure.php.net/manual/en/zmqcontext.ispersistent.php
     *
     * @return bool Returns <b>TRUE</b> if the context is persistent and <b>FALSE</b> if the context is non-persistent.
     */
    public function isPersistent() {}

    /**
     * (PECL zmq &gt;= 1.0.4)
     * Sets a ZMQ context option. The type of the value depends on the key.
     * See ZMQ Constant Types for more information.
     *
     * @link https://secure.php.net/manual/en/zmqcontext.setopt.php
     *
     * @param int $key One of the <b>ZMQ::CTXOPT_*<b> constants.
     * @param mixed $value The value of the parameter.
     * @return ZMQContext
     * @throws ZMQContextException
     */
    public function setOpt($key, $value) {}
}
/**
 * Class ZMQSocket
 * @link https://secure.php.net/manual/en/class.zmqsocket.php
 */
class ZMQSocket
{
    /**
     * (PECL zmq &gt;= 0.5.0)
     * Constructs a ZMQSocket object.
     * The persistent_id parameter can be used to allocated a persistent socket.
     * A persistent socket has to be allocated from a persistent context and it stays connected over multiple requests.
     * The persistent_id parameter can be used to recall the same socket over multiple requests.
     * The on_new_socket is called only when a new underlying socket structure is created.
     *
     * @link https://secure.php.net/manual/en/zmqsocket.construct.php
     *
     * @param ZMQContext $context <p>ZMQContext to build this object</p>
     * @param int $type <p>The type of the socket. See ZMQ::SOCKET_* constants.</p>
     * @param string $persistent_id [optional] <p>If persistent_id is specified the socket will be persisted over multiple requests. If context is not persistent the socket falls back to non-persistent mode.</p>
     * @param callable $on_new_socket [optional] <p>Callback function, which is executed when a new socket structure is created. This function does not get invoked if the underlying persistent connection is re-used.</p>
     *
     * @throws ZMQSocketException
     */
    public function __construct(ZMQContext $context, $type, $persistent_id = null, $on_new_socket = null) {}

    /**
     * (PECL zmq &gt;= 0.5.0)
     * Bind the socket to an endpoint.
     * The endpoint is defined in format transport://address
     * where transport is one of the following: inproc, ipc, tcp, pgm or epgm.
     *
     * @link https://secure.php.net/manual/en/zmqsocket.bind.php
     *
     * @param string $dsn The bind dsn, for example transport://address.
     * @param bool $force Tries to bind even if the socket has already been bound to the given endpoint.
     *
     * @return ZMQSocket
     * @throws ZMQSocketException if binding fails
     */
    public function bind($dsn, $force = false) {}

    /**
     * (PECL zmq &gt;= 0.5.0)
     * Connect the socket to a remote endpoint.
     * The endpoint is defined in format transport://address
     * where transport is one of the following: inproc, ipc, tcp, pgm or epgm.
     *
     * @link https://secure.php.net/manual/en/zmqsocket.connect.php
     *
     * @param string $dsn The bind dsn, for example transport://address.
     * @param bool $force Tries to bind even if the socket has already been bound to the given endpoint.
     *
     * @return ZMQSocket
     * @throws ZMQSocketException If connection fails
     */
    public function connect($dsn, $force = false) {}

    /**
     * (PECL zmq &gt;= 1.0.4)
     * Disconnect the socket from a previously connected remote endpoint.
     * The endpoint is defined in format transport://address
     * where transport is one of the following: inproc, ipc, tcp, pgm or epgm.
     *
     * @link https://secure.php.net/manual/en/zmqsocket.disconnect.php
     *
     * @param string $dsn The bind dsn, for example transport://address.
     *
     * @return ZMQSocket
     * @throws ZMQSocketException If connection fails
     */
    public function disconnect($dsn) {}

    /**
     * Returns a list of endpoints where the socket is connected or bound to.
     *
     * @link https://secure.php.net/manual/en/zmqsocket.getendpoints.php
     *
     * @return array contains two sub-arrays: 'connect' and 'bind'
     * @throws ZMQSocketException
     */
    public function getEndpoints() {}

    /**
     * Returns the persistent id string assigned of the object and NULL if socket is not persistent.
     *
     * @link https://secure.php.net/manual/en/zmqsocket.getpersistentid.php
     *
     * @return string|null <p>
     * Returns the persistent id string assigned of the object and <b>NULL</b> if socket is not persistent.
     * </p>
     */
    public function getPersistentId() {}

    /**
     * Returns the value of a socket option.
     * This method is available if ZMQ extension has been compiled against ZMQ version 2.0.7 or higher
     *
     * @link https://secure.php.net/manual/en/zmqsocket.getsockopt.php
     *
     * @since 0MQ 2.0.7
     * @param int $key An int representing the option. See the <b>ZMQ::SOCKOPT_*</b> constants.
     *
     * @return string|int <p>
     * Returns either a string or an integer depending on <b>key</b>. Throws
     * ZMQSocketException on error.
     * </p>
     * @throws ZMQSocketException
     */
    public function getSockOpt($key) {}

    /**
     * Return the socket type.
     * The socket type can be compared against ZMQ::SOCKET_* constants.
     *
     * @link https://secure.php.net/manual/en/zmqsocket.getsockettype.php
     *
     * @return int <p>
     * Returns an integer representing the socket type. The integer can be compared against
     * <b>ZMQ::SOCKET_*</b> constants.
     * </p>
     */
    public function getSocketType() {}

    /**
     * Check whether the socket is persistent.
     *
     * @link https://secure.php.net/manual/en/zmqsocket.ispersistent.php
     *
     * @return bool <p>Returns a boolean based on whether the socket is persistent or not.</p>
     */
    public function isPersistent() {}

    /**
     * Receive a message from a socket.
     * By default receiving will block until a message is available unless <b>ZMQ::MODE_NOBLOCK</b> flag is used.
     * <b>ZMQ::SOCKOPT_RCVMORE</b> socket option can be used for receiving multi-part messages.
     * Returns the message.
     * If <b>ZMQ::MODE_NOBLOCK</b> is used and the operation would block bool false shall be returned.
     *
     * @link https://secure.php.net/manual/en/zmqsocket.recv.php
     * @see ZMQSocket::setSockOpt()
     *
     * @param int $mode Pass mode flags to receive multipart messages or non-blocking operation. See ZMQ::MODE_* constants.
     *
     * @return string|false <p>Returns the message. Throws ZMQSocketException in error. If <b>ZMQ::MODE_NOBLOCK</b> is used and the operation would block boolean false shall be returned.</p>
     * @throws ZMQSocketException if receiving fails.
     */
    public function recv($mode = 0) {}

    /**
     * Receive an array multipart message from a socket.
     * By default receiving will block until a message is available unless ZMQ::MODE_NOBLOCK flag is used.
     * Returns the array of message parts.
     * If <b>ZMQ::MODE_NOBLOCK</b> is used and the operation would block bool false shall be returned.
     *
     * @link https://secure.php.net/manual/en/zmqsocket.recvmulti.php
     *
     * @param int $mode Pass mode flags to receive multipart messages or non-blocking operation. See ZMQ::MODE_* constants.
     *
     * @return string[] Returns the array of message parts. Throws ZMQSocketException in error. If ZMQ::MODE_NOBLOCK is used and the operation would block boolean false shall be returned.
     * @throws ZMQSocketException if receiving fails.
     */
    public function recvMulti($mode = 0) {}

    /**
     * Send a message using the socket. The operation can block unless ZMQ::MODE_NOBLOCK is used.
     * If <b>ZMQ::MODE_NOBLOCK</b> is used and the operation would block bool false shall be returned.
     *
     * @link https://secure.php.net/manual/en/zmqsocket.send.php
     *
     * @param string $message The message to send
     * @param int $mode Pass mode flags to receive multipart messages or non-blocking operation. See ZMQ::MODE_* constants.     *
     *
     * @return ZMQSocket
     * @throws ZMQSocketException if sending message fails
     */
    public function send($message, $mode = 0) {}

    /**
     * Send a multipart message using the socket. The operation can block unless ZMQ::MODE_NOBLOCK is used.
     * If <b>ZMQ::MODE_NOBLOCK</b> is used and the operation would block bool false shall be returned.
     *
     * @link https://secure.php.net/manual/en/zmqsocket.sendmulti.php
     *
     * @param array $message The message to send - an array of strings
     * @param int $mode Pass mode flags to receive multipart messages or non-blocking operation. See ZMQ::MODE_* constants.     *
     *
     * @return ZMQSocket
     * @throws ZMQSocketException if sending message fails
     */
    public function sendmulti(array $message, $mode = 0) {}

    /**
     * Sets a ZMQ socket option. The type of the value depends on the key.
     * @see ZMQ Constant Types for more information.
     *
     * @link https://secure.php.net/manual/en/zmqsocket.setsockopt.php
     *
     * @param int $key One of the <b>ZMQ::SOCKOPT_*</b> constants.
     * @param mixed $value The value of the parameter.
     *
     * @return ZMQSocket
     * @throws ZMQSocketException
     */
    public function setSockOpt($key, $value) {}

    /**
     * Unbind the socket from an endpoint.
     * The endpoint is defined in format transport://address
     * where transport is one of the following: inproc, ipc, tcp, pgm or epgm.
     *
     * @link https://secure.php.net/manual/en/zmqsocket.unbind.php
     *
     * @param string $dsn The previously bound dsn, for example transport://address.
     *
     * @return ZMQSocket
     * @throws ZMQSocketException if binding fails
     */
    public function unbind($dsn) {}
}
/**
 * Class ZMQPoll
 * @link https://secure.php.net/manual/en/class.zmqpoll.php
 */
class ZMQPoll
{
    /**
     * (PECL zmq &gt;= 0.5.0)
     * Adds a new item to the poll set and returns the internal id of the added item.
     * The item can be removed from the poll set using the returned string id.
     * Returns a string id of the added item which can be later used to remove the item.
     *
     * @link https://secure.php.net/manual/en/zmqpoll.add.php
     *
     * @param ZMQSocket $entry ZMQSocket object or a PHP stream resource
     * @param int $type Defines what activity the socket is polled for. See <b>ZMQ::POLL_IN</b> and <b>ZMQ::POLL_OUT</b> constants.
     *
     * @return int Returns a string id of the added item which can be later used to remove the item. Throws ZMQPollException on error.
     * @throws ZMQPollException if the object has not been initialized with polling
     */
    public function add(ZMQSocket $entry, $type) {}

    /**
     * (PECL zmq &gt;= 1.0.4)
     * Clears all elements from the poll set.
     *
     * @link https://secure.php.net/manual/en/zmqpoll.clear.php
     *
     * @return ZMQPoll Returns the current object.
     */
    public function clear() {}

    /**
     * (PECL zmq &gt;= 0.5.0)
     * Count the items in the poll set.
     *
     * @link https://secure.php.net/manual/en/zmqpoll.count.php
     *
     * @return int Returns an integer representing the amount of items in the poll set.
     */
    public function count() {}

    /**
     * (PECL zmq &gt;= 0.5.0)
     * Returns the ids of the objects that had errors in the last poll.
     * Returns an array containing ids for the items that had errors in the last poll.
     * Empty array is returned if there were no errors.
     *
     * @link https://secure.php.net/manual/en/zmqpoll.getlasterrors.php
     *
     * @return int[]
     */
    public function getLastErrors() {}

    /**
     * (PECL zmq &gt;= 0.5.0)
     * Polls the items in the current poll set.
     * The readable and writable items are returned in the readable and writable parameters.
     * ZMQPoll::getLastErrors() can be used to check if there were errors.
     * Returns an int representing amount of items with activity.
     *
     * @link https://secure.php.net/manual/en/zmqpoll.poll.php
     *
     * @param array &$readable Array where readable ZMQSockets/PHP streams are returned. The array will be cleared at the beginning of the operation.
     * @param array &$writable Array where writable ZMQSockets/PHP streams are returned. The array will be cleared at the beginning of the operation.
     * @param int $timeout Timeout for the operation. -1 means that poll waits until at least one item has activity. Please note that starting from version 1.0.0 the poll timeout is defined in milliseconds, rather than microseconds.
     *
     * @throws ZMQPollException if polling fails
     * @return int
     */
    public function poll(array &$readable, array &$writable, $timeout = -1) {}

    /**
     * (PECL zmq &gt;= 0.5.0)
     * Remove item from the poll set.
     * The item parameter can be ZMQSocket object, a stream resource or the id returned from ZMQPoll::add() method.
     * Returns true if the item was removed and false if the object with given id does not exist in the poll set.
     *
     * @link https://secure.php.net/manual/en/zmqpoll.remove.php
     *
     * @param ZMQSocket|string|mixed $item The ZMQSocket object, PHP stream or string id of the item.
     * @return bool Returns true if the item was removed and false if the object with given id does not exist in the poll set.
     */
    public function remove($item) {}
}
/**
 * Class ZMQDevice
 * @link https://secure.php.net/manual/en/class.zmqdevice.php
 */
class ZMQDevice
{
    /**
     * (PECL zmq &gt;= 1.0.4)
     * Construct a new device.
     * "ØMQ devices can do intermediation of addresses, services, queues, or any other abstraction you care
     * to define above the message and socket layers." -- zguide
     * Call to this method will prepare the device. Usually devices are very long running processes so running this method from interactive script is not recommended. This method throw ZMQDeviceException if the device cannot be started.
     *
     * @link https://secure.php.net/manual/en/zmqdevice.construct.php
     *
     * @param ZMQSocket $frontend Frontend parameter for the devices. Usually where there messages are coming.
     * @param ZMQSocket $backend Backend parameter for the devices. Usually where there messages going to.
     * @param null|ZMQSocket $listener Listener socket, which receives a copy of all messages going both directions. The type of this socket should be SUB, PULL or DEALER.
     */
    public function __construct(ZMQSocket $frontend, ZMQSocket $backend, ZMQSocket $listener = null) {}

    /**
     * Gets the idle callback timeout value.
     * This method returns the idle callback timeout value.
     * Added in ZMQ extension version 1.1.0.
     *
     * @link https://secure.php.net/manual/en/zmqdevice.getidletimeout.php
     *
     * @return int This method returns the idle callback timeout value.
     */
    public function getIdleTimeout() {}

    /**
     * Gets the timer callback timeout value.
     * Added in ZMQ extension version 1.1.0.
     *
     * @link https://secure.php.net/manual/en/zmqdevice.gettimertimeout.php
     *
     * @return int This method returns the timer timeout value.
     */
    public function getTimerTimeout() {}

    /**
     * Runs the device.
     * Call to this method will block until the device is running.
     * It is not recommended that devices are used from interactive scripts.
     *
     * @link https://secure.php.net/manual/en/zmqdevice.run.php
     *
     * @throws ZMQDeviceException
     */
    public function run() {}

    /**
     * Sets the idle callback function.
     * If idle timeout is defined the idle callback function shall be called if the internal poll loop times out
     * without events. If the callback function returns false or a value that evaluates to false the device is stopped.
     * The callback function signature is callback (mixed $user_data).
     *
     * @link https://secure.php.net/manual/en/zmqdevice.setidlecallback.php
     *
     * @param callable $cb_func Callback function to invoke when the device is idle. Returning false or a value that evaluates to false from this function will cause the device to stop.
     * @param int $timeout How often to invoke the idle callback in milliseconds. The idle callback is invoked periodically when there is no activity on the device. The timeout value guarantees that there is at least this amount of milliseconds between invocations of the callback function.
     * @param mixed $user_data Additional data to pass to the callback function.
     *
     * @return ZMQDevice On success this method returns the current object.
     */
    public function setIdleCallback($cb_func, $timeout, $user_data) {}

    /**
     * Sets the idle callback timeout value. The idle callback is invoked periodically when the device is idle.
     * On success this method returns the current object.
     *
     * @link https://secure.php.net/manual/en/zmqdevice.setidletimeout.php
     *
     * @param int $timeout The idle callback timeout value in milliseconds
     *
     * @return ZMQDevice On success this method returns the current object.
     */
    public function setIdleTimeout($timeout) {}

    /**
     * Sets the timer callback function. The timer callback will be invoked after timeout has passed.
     * The difference between idle and timer callbacks are that idle callback is invoked only when the device is idle.
     * The callback function signature is callback (mixed $user_data).
     * Added in ZMQ extension version 1.1.0.
     *
     * @link https://secure.php.net/manual/en/zmqdevice.settimercallback.php
     *
     * @param callable $cb_func Callback function to invoke when the device is idle. Returning false or a value that evaluates to false from this function will cause the device to stop.
     * @param int $timeout How often to invoke the idle callback in milliseconds. The idle callback is invoked periodically when there is no activity on the device. The timeout value guarantees that there is at least this amount of milliseconds between invocations of the callback function.
     * @param mixed $user_data Additional data to pass to the callback function.
     *
     * @return ZMQDevice
     */
    public function setTimerCallback($cb_func, $timeout, $user_data) {}

    /**
     * Sets the timer callback timeout value. The timer callback is invoked periodically if it's set.
     * Added in ZMQ extension version 1.1.0.
     *
     * @link https://secure.php.net/manual/en/zmqdevice.settimertimeout.php
     *
     * @param int $timeout The timer callback timeout value.
     *
     * @return ZMQDevice
     */
    public function setTimerTimeout($timeout) {}
}
class ZMQException extends Exception {}
class ZMQContextException extends ZMQException {}
class ZMQSocketException extends ZMQException {}
class ZMQPollException extends ZMQException {}
class ZMQDeviceException extends ZMQException {}
