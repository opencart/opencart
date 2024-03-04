<?php
/**
 * Stubs for AMQP
 * https://pecl.php.net/package/amqp
 * https://github.com/pdezwart/php-amqp
 */

/**
 * Passing in this constant as a flag will forcefully disable all other flags.
 * Use this if you want to temporarily disable the amqp.auto_ack ini setting.
 */

use JetBrains\PhpStorm\Deprecated;

define('AMQP_NOPARAM', 0);

/**
 * Passing in this constant as a flag to proper methods will forcefully ignore all other flags.
 * Do not send basic.consume request during AMQPQueue::consume(). Use this if you want to run callback on top of previously
 * declared consumers.
 */
define('AMQP_JUST_CONSUME', 1);

/**
 * Durable exchanges and queues will survive a broker restart, complete with all of their data.
 */
define('AMQP_DURABLE', 2);

/**
 * Passive exchanges and queues will not be redeclared, but the broker will throw an error if the exchange or queue does not exist.
 */
define('AMQP_PASSIVE', 4);

/**
 * Valid for queues only, this flag indicates that only one client can be listening to and consuming from this queue.
 */
define('AMQP_EXCLUSIVE', 8);

/**
 * For exchanges, the auto delete flag indicates that the exchange will be deleted as soon as no more queues are bound
 * to it. If no queues were ever bound the exchange, the exchange will never be deleted. For queues, the auto delete
 * flag indicates that the queue will be deleted as soon as there are no more listeners subscribed to it. If no
 * subscription has ever been active, the queue will never be deleted. Note: Exclusive queues will always be
 * automatically deleted with the client disconnects.
 */
define('AMQP_AUTODELETE', 16);

/**
 * Clients are not allowed to make specific queue bindings to exchanges defined with this flag.
 */
define('AMQP_INTERNAL', 32);

/**
 * When passed to the consume method for a clustered environment, do not consume from the local node.
 */
define('AMQP_NOLOCAL', 64);

/**
 * When passed to the {@link AMQPQueue::get()} and {@link AMQPQueue::consume()} methods as a flag,
 * the messages will be immediately marked as acknowledged by the server upon delivery.
 */
define('AMQP_AUTOACK', 128);

/**
 * Passed on queue creation, this flag indicates that the queue should be deleted if it becomes empty.
 */
define('AMQP_IFEMPTY', 256);

/**
 * Passed on queue or exchange creation, this flag indicates that the queue or exchange should be
 * deleted when no clients are connected to the given queue or exchange.
 */
define('AMQP_IFUNUSED', 512);

/**
 * When publishing a message, the message must be routed to a valid queue. If it is not, an error will be returned.
 */
define('AMQP_MANDATORY', 1024);

/**
 * When publishing a message, mark this message for immediate processing by the broker. (High priority message.)
 */
define('AMQP_IMMEDIATE', 2048);

/**
 * If set during a call to {@link AMQPQueue::ack()}, the delivery tag is treated as "up to and including", so that multiple
 * messages can be acknowledged with a single method. If set to zero, the delivery tag refers to a single message.
 * If the AMQP_MULTIPLE flag is set, and the delivery tag is zero, this indicates acknowledgement of all outstanding
 * messages.
 */
define('AMQP_MULTIPLE', 4096);

/**
 * If set during a call to {@link AMQPExchange::bind()}, the server will not respond to the method.The client should not wait
 * for a reply method. If the server could not complete the method it will raise a channel or connection exception.
 */
define('AMQP_NOWAIT', 8192);

/**
 * If set during a call to {@link AMQPQueue::nack()}, the message will be placed back to the queue.
 */
define('AMQP_REQUEUE', 16384);

/**
 * A direct exchange type.
 */
define('AMQP_EX_TYPE_DIRECT', 'direct');

/**
 * A fanout exchange type.
 */
define('AMQP_EX_TYPE_FANOUT', 'fanout');

/**
 * A topic exchange type.
 */
define('AMQP_EX_TYPE_TOPIC', 'topic');

/**
 * A header exchange type.
 */
define('AMQP_EX_TYPE_HEADERS', 'headers');

/**
 * The error number of OS socket timeout.
 */
define('AMQP_OS_SOCKET_TIMEOUT_ERRNO', 536870923);

/**
 * The maximum number of channels that can be open on a connection.
 */
define('PHP_AMQP_MAX_CHANNELS', 256);

/**
 * SASL PLAIN authentication. This is enabled by default in the RabbitMQ server and clients, and is the default for most other clients.
 */
define('AMQP_SASL_METHOD_PLAIN', 0);

/**
 * Authentication happens using an out-of-band mechanism such as x509 certificate peer verification, client IP address range, or similar. Such mechanisms are usually provided by RabbitMQ plugins.
 */
define('AMQP_SASL_METHOD_EXTERNAL', 1);

/**
 * stub class representing AMQPBasicProperties from pecl-amqp
 */
class AMQPBasicProperties
{
    /**
     * @param string $content_type
     * @param string $content_encoding
     * @param array  $headers
     * @param int    $delivery_mode
     * @param int    $priority
     * @param string $correlation_id
     * @param string $reply_to
     * @param string $expiration
     * @param string $message_id
     * @param int    $timestamp
     * @param string $type
     * @param string $user_id
     * @param string $app_id
     * @param string $cluster_id
     */
    public function __construct(
        $content_type = "",
        $content_encoding = "",
        array $headers = [],
        $delivery_mode = 2,
        $priority = 0,
        $correlation_id = "",
        $reply_to = "",
        $expiration = "",
        $message_id = "",
        $timestamp = 0,
        $type = "",
        $user_id = "",
        $app_id = "",
        $cluster_id = ""
    ) {}

    /**
     * Get the message content type.
     *
     * @return string The content type of the message.
     */
    public function getContentType() {}

    /**
     * Get the content encoding of the message.
     *
     * @return string The content encoding of the message.
     */
    public function getContentEncoding() {}

    /**
     * Get the headers of the message.
     *
     * @return array An array of key value pairs associated with the message.
     */
    public function getHeaders() {}

    /**
     * Get the delivery mode of the message.
     *
     * @return int The delivery mode of the message.
     */
    public function getDeliveryMode() {}

    /**
     * Get the priority of the message.
     *
     * @return int The message priority.
     */
    public function getPriority() {}

    /**
     * Get the message correlation id.
     *
     * @return string The correlation id of the message.
     */
    public function getCorrelationId() {}

    /**
     * Get the reply-to address of the message.
     *
     * @return string The contents of the reply to field.
     */
    public function getReplyTo() {}

    /**
     * Get the expiration of the message.
     *
     * @return string The message expiration.
     */
    public function getExpiration() {}

    /**
     * Get the message id of the message.
     *
     * @return string The message id
     */
    public function getMessageId() {}

    /**
     * Get the timestamp of the message.
     *
     * @return string The message timestamp.
     */
    public function getTimestamp() {}

    /**
     * Get the message type.
     *
     * @return string The message type.
     */
    public function getType() {}

    /**
     * Get the message user id.
     *
     * @return string The message user id.
     */
    public function getUserId() {}

    /**
     * Get the application id of the message.
     *
     * @return string The application id of the message.
     */
    public function getAppId() {}

    /**
     * Get the cluster id of the message.
     *
     * @return string The cluster id of the message.
     */
    public function getClusterId() {}
}

/**
 * stub class representing AMQPChannel from pecl-amqp
 */
class AMQPChannel
{
    /**
     * Commit a pending transaction.
     *
     * @throws AMQPChannelException    If no transaction was started prior to
     *                                 calling this method.
     * @throws AMQPConnectionException If the connection to the broker was lost.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function commitTransaction() {}

    /**
     * Create an instance of an AMQPChannel object.
     *
     * @param AMQPConnection $amqp_connection An instance of AMQPConnection
     *                                        with an active connection to a
     *                                        broker.
     *
     * @throws AMQPConnectionException        If the connection to the broker
     *                                        was lost.
     */
    public function __construct(AMQPConnection $amqp_connection) {}

    /**
     * Check the channel connection.
     *
     * @return bool Indicates whether the channel is connected.
     */
    public function isConnected() {}

    /**
     * Closes the channel.
     */
    public function close() {}

    /**
     * Return internal channel ID
     *
     * @return int
     */
    public function getChannelId() {}

    /**
     * Set the Quality Of Service settings for the given channel.
     *
     * Specify the amount of data to prefetch in terms of window size (octets)
     * or number of messages from a queue during a AMQPQueue::consume() or
     * AMQPQueue::get() method call. The client will prefetch data up to size
     * octets or count messages from the server, whichever limit is hit first.
     * Setting either value to 0 will instruct the client to ignore that
     * particular setting. A call to AMQPChannel::qos() will overwrite any
     * values set by calling AMQPChannel::setPrefetchSize() and
     * AMQPChannel::setPrefetchCount(). If the call to either
     * AMQPQueue::consume() or AMQPQueue::get() is done with the AMQP_AUTOACK
     * flag set, the client will not do any prefetching of data, regardless of
     * the QOS settings.
     *
     * @param int $size   The window size, in octets, to prefetch.
     * @param int $count  The number of messages to prefetch.
     * @param bool    $global TRUE for global, FALSE for consumer. FALSE by default.
     *
     * @throws AMQPConnectionException If the connection to the broker was lost.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function qos($size, $count, $global = false) {}

    /**
     * Rollback a transaction.
     *
     * Rollback an existing transaction. AMQPChannel::startTransaction() must
     * be called prior to this.
     *
     * @throws AMQPChannelException    If no transaction was started prior to
     *                                 calling this method.
     * @throws AMQPConnectionException If the connection to the broker was lost.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function rollbackTransaction() {}

    /**
     * Set the number of messages to prefetch from the broker for each consumer.
     *
     * Set the number of messages to prefetch from the broker during a call to
     * AMQPQueue::consume() or AMQPQueue::get().
     *
     * @param int $count The number of messages to prefetch.
     *
     * @throws AMQPConnectionException If the connection to the broker was lost.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function setPrefetchCount($count) {}

    /**
     * Get the number of messages to prefetch from the broker for each consumer.
     *
     * @return int
     */
    public function getPrefetchCount() {}

    /**
     * Set the window size to prefetch from the broker for each consumer.
     *
     * Set the prefetch window size, in octets, during a call to
     * AMQPQueue::consume() or AMQPQueue::get(). Any call to this method will
     * automatically set the prefetch message count to 0, meaning that the
     * prefetch message count setting will be ignored. If the call to either
     * AMQPQueue::consume() or AMQPQueue::get() is done with the AMQP_AUTOACK
     * flag set, this setting will be ignored.
     *
     * @param int $size The window size, in octets, to prefetch.
     *
     * @throws AMQPConnectionException If the connection to the broker was lost.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function setPrefetchSize($size) {}

    /**
     * Get the window size to prefetch from the broker for each consumer.
     *
     * @return int
     */
    public function getPrefetchSize() {}

    /**
     * Set the number of messages to prefetch from the broker across all consumers.
     *
     * Set the number of messages to prefetch from the broker during a call to
     * AMQPQueue::consume() or AMQPQueue::get().
     *
     * @param int $count The number of messages to prefetch.
     *
     * @throws AMQPConnectionException If the connection to the broker was lost.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function setGlobalPrefetchCount($count) {}

    /**
     * Get the number of messages to prefetch from the broker across all consumers.
     *
     * @return int
     */
    public function getGlobalPrefetchCount() {}

    /**
     * Set the window size to prefetch from the broker for all consumers.
     *
     * Set the prefetch window size, in octets, during a call to
     * AMQPQueue::consume() or AMQPQueue::get(). Any call to this method will
     * automatically set the prefetch message count to 0, meaning that the
     * prefetch message count setting will be ignored. If the call to either
     * AMQPQueue::consume() or AMQPQueue::get() is done with the AMQP_AUTOACK
     * flag set, this setting will be ignored.
     *
     * @param int $size The window size, in octets, to prefetch.
     *
     * @throws AMQPConnectionException If the connection to the broker was lost.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function setGlobalPrefetchSize($size) {}

    /**
     * Get the window size to prefetch from the broker for all consumers.
     *
     * @return int
     */
    public function getGlobalPrefetchSize() {}

    /**
     * Start a transaction.
     *
     * This method must be called on the given channel prior to calling
     * AMQPChannel::commitTransaction() or AMQPChannel::rollbackTransaction().
     *
     * @throws AMQPConnectionException If the connection to the broker was lost.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function startTransaction() {}

    /**
     * Get the AMQPConnection object in use
     *
     * @return AMQPConnection
     */
    public function getConnection() {}

    /**
     * Redeliver unacknowledged messages.
     *
     * @param bool $requeue
     */
    public function basicRecover($requeue = true) {}

    /**
     * Set the channel to use publisher acknowledgements. This can only used on a non-transactional channel.
     */
    public function confirmSelect() {}

    /**
     * Set callback to process basic.ack and basic.nac AMQP server methods (applicable when channel in confirm mode).
     *
     * @param callable|null $ack_callback
     * @param callable|null $nack_callback
     *
     * Callback functions with all arguments have the following signature:
     *
     *      function ack_callback(int $delivery_tag, bool $multiple) : bool;
     *      function nack_callback(int $delivery_tag, bool $multiple, bool $requeue) : bool;
     *
     * and should return boolean false when wait loop should be canceled.
     *
     * Note, basic.nack server method will only be delivered if an internal error occurs in the Erlang process
     * responsible for a queue (see https://www.rabbitmq.com/confirms.html for details).
     */
    public function setConfirmCallback(callable $ack_callback = null, callable $nack_callback = null) {}

    /**
     * Wait until all messages published since the last call have been either ack'd or nack'd by the broker.
     *
     * Note, this method also catch all basic.return message from server.
     *
     * @param float $timeout Timeout in seconds. May be fractional.
     *
     * @throws AMQPQueueException If timeout occurs.
     */
    public function waitForConfirm($timeout = 0.0) {}

    /**
     * Set callback to process basic.return AMQP server method
     *
     * @param callable|null $return_callback
     *
     * Callback function with all arguments has the following signature:
     *
     *      function callback(int $reply_code,
     *                        string $reply_text,
     *                        string $exchange,
     *                        string $routing_key,
     *                        AMQPBasicProperties $properties,
     *                        string $body) : bool;
     *
     * and should return boolean false when wait loop should be canceled.
     */
    public function setReturnCallback(callable $return_callback = null) {}

    /**
     * Start wait loop for basic.return AMQP server methods
     *
     * @param float $timeout Timeout in seconds. May be fractional.
     *
     * @throws AMQPQueueException If timeout occurs.
     */
    public function waitForBasicReturn($timeout = 0.0) {}

    /**
     * Return array of current consumers where key is consumer and value is AMQPQueue consumer is running on
     *
     * @return AMQPQueue[]
     */
    public function getConsumers() {}
}

/**
 * stub class representing AMQPChannelException from pecl-amqp
 */
class AMQPChannelException extends AMQPException {}

/**
 * stub class representing AMQPConnection from pecl-amqp
 */
class AMQPConnection
{
    /**
     * Establish a transient connection with the AMQP broker.
     *
     * This method will initiate a connection with the AMQP broker.
     *
     * @throws AMQPConnectionException
     * @return bool TRUE on success or throw an exception on failure.
     */
    public function connect() {}

    /**
     * Create an instance of AMQPConnection.
     *
     * Creates an AMQPConnection instance representing a connection to an AMQP
     * broker. A connection will not be established until
     * AMQPConnection::connect() is called.
     *
     *  $credentials = array(
     *      'host'  => amqp.host The host to connect too. Note: Max 1024 characters.
     *      'port'  => amqp.port Port on the host.
     *      'vhost' => amqp.vhost The virtual host on the host. Note: Max 128 characters.
     *      'login' => amqp.login The login name to use. Note: Max 128 characters.
     *      'password' => amqp.password Password. Note: Max 128 characters.
     *      'read_timeout'  => Timeout in for income activity. Note: 0 or greater seconds. May be fractional.
     *      'write_timeout' => Timeout in for outcome activity. Note: 0 or greater seconds. May be fractional.
     *      'connect_timeout' => Connection timeout. Note: 0 or greater seconds. May be fractional.
     *      'rpc_timeout' => RPC timeout. Note: 0 or greater seconds. May be fractional.
     *
     *      Connection tuning options (see http://www.rabbitmq.com/amqp-0-9-1-reference.html#connection.tune for details):
     *      'channel_max' => Specifies highest channel number that the server permits. 0 means standard extension limit
     *                       (see PHP_AMQP_MAX_CHANNELS constant)
     *      'frame_max'   => The largest frame size that the server proposes for the connection, including frame header
     *                       and end-byte. 0 means standard extension limit (depends on librabbimq default frame size limit)
     *      'heartbeat'   => The delay, in seconds, of the connection heartbeat that the server wants.
     *                       0 means the server does not want a heartbeat. Note, librabbitmq has limited heartbeat support,
     *                       which means heartbeats checked only during blocking calls.
     *
     *      TLS support (see https://www.rabbitmq.com/ssl.html for details):
     *      'cacert' => Path to the CA cert file in PEM format..
     *      'cert'   => Path to the client certificate in PEM foramt.
     *      'key'    => Path to the client key in PEM format.
     *      'verify' => Enable or disable peer verification. If peer verification is enabled then the common name in the
     *                  server certificate must match the server name. Peer verification is enabled by default.
     * )
     *
     * @param array $credentials Optional array of credential information for
     *                           connecting to the AMQP broker.
     */
    public function __construct(array $credentials = []) {}

    /**
     * Closes the transient connection with the AMQP broker.
     *
     * This method will close an open connection with the AMQP broker.
     *
     * @return bool true if connection was successfully closed, false otherwise.
     */
    public function disconnect() {}

    /**
     * Get the configured host.
     *
     * @return string The configured hostname of the broker
     */
    public function getHost() {}

    /**
     * Get the configured login.
     *
     * @return string The configured login as a string.
     */
    public function getLogin() {}

    /**
     * Get the configured password.
     *
     * @return string The configured password as a string.
     */
    public function getPassword() {}

    /**
     * Get the configured port.
     *
     * @return int The configured port as an integer.
     */
    public function getPort() {}

    /**
     * Get the configured vhost.
     *
     * @return string The configured virtual host as a string.
     */
    public function getVhost() {}

    /**
     * Check whether the connection to the AMQP broker is still valid.
     *
     * It does so by checking the return status of the last connect-command.
     *
     * @return bool True if connected, false otherwise.
     */
    public function isConnected() {}

    /**
     * Establish a persistent connection with the AMQP broker.
     *
     * This method will initiate a connection with the AMQP broker
     * or reuse an existing one if present.
     *
     * @throws AMQPConnectionException
     * @return bool TRUE on success or throws an exception on failure.
     */
    public function pconnect() {}

    /**
     * Closes a persistent connection with the AMQP broker.
     *
     * This method will close an open persistent connection with the AMQP
     * broker.
     *
     * @return bool true if connection was found and closed,
     *                 false if no persistent connection with this host,
     *                 port, vhost and login could be found,
     */
    public function pdisconnect() {}

    /**
     * Close any open transient connections and initiate a new one with the AMQP broker.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function reconnect() {}

    /**
     * Close any open persistent connections and initiate a new one with the AMQP broker.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function preconnect() {}

    /**
     * Set the hostname used to connect to the AMQP broker.
     *
     * @param string $host The hostname of the AMQP broker.
     *
     * @throws AMQPConnectionException If host is longer then 1024 characters.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function setHost($host) {}

    /**
     * Set the login string used to connect to the AMQP broker.
     *
     * @param string $login The login string used to authenticate
     *                      with the AMQP broker.
     *
     * @throws AMQPConnectionException If login is longer then 32 characters.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function setLogin($login) {}

    /**
     * Set the password string used to connect to the AMQP broker.
     *
     * @param string $password The password string used to authenticate
     *                         with the AMQP broker.
     *
     * @throws AMQPConnectionException If password is longer then 32characters.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function setPassword($password) {}

    /**
     * Set the port used to connect to the AMQP broker.
     *
     * @param int $port The port used to connect to the AMQP broker.
     *
     * @throws AMQPConnectionException If port is longer not between
     *                                 1 and 65535.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function setPort($port) {}

    /**
     * Sets the virtual host to which to connect on the AMQP broker.
     *
     * @param string $vhost The virtual host to use on the AMQP
     *                      broker.
     *
     * @throws AMQPConnectionException If host is longer then 32 characters.
     *
     * @return bool true on success or false on failure.
     */
    public function setVhost($vhost) {}

    /**
     * Sets the interval of time to wait for income activity from AMQP broker
     *
     * @param float $timeout
     *
     * @throws AMQPConnectionException If timeout is less than 0.
     *
     * @return bool
     */
    #[Deprecated(replacement: "%class%->setReadTimout(%parameter0%)")]
    public function setTimeout($timeout) {}

    /**
     * Get the configured interval of time to wait for income activity
     * from AMQP broker
     *
     * @return float
     */
    #[Deprecated(replacement: '%class%->getReadTimout(%parameter0%)')]
    public function getTimeout() {}

    /**
     * Sets the interval of time to wait for income activity from AMQP broker
     *
     * @param float $timeout
     *
     * @throws AMQPConnectionException If timeout is less than 0.
     *
     * @return bool
     */
    public function setReadTimeout($timeout) {}

    /**
     * Get the configured interval of time to wait for income activity
     * from AMQP broker
     *
     * @return float
     */
    public function getReadTimeout() {}

    /**
     * Sets the interval of time to wait for outcome activity to AMQP broker
     *
     * @param float $timeout
     *
     * @throws AMQPConnectionException If timeout is less than 0.
     *
     * @return bool
     */
    public function setWriteTimeout($timeout) {}

    /**
     * Get the configured interval of time to wait for outcome activity
     * to AMQP broker
     *
     * @return float
     */
    public function getWriteTimeout() {}

    /**
     * Sets the interval of time to wait for RPC activity to AMQP broker
     *
     * @param float $timeout
     *
     * @throws AMQPConnectionException If timeout is less than 0.
     *
     * @return bool
     */
    public function setRpcTimeout($timeout) {}

    /**
     * Get the configured interval of time to wait for RPC activity
     * to AMQP broker
     *
     * @return float
     */
    public function getRpcTimeout() {}

    /**
     * Return last used channel id during current connection session.
     *
     * @return int
     */
    public function getUsedChannels() {}

    /**
     * Get the maximum number of channels the connection can handle.
     *
     * When connection is connected, effective connection value returned, which is normally the same as original
     * correspondent value passed to constructor, otherwise original value passed to constructor returned.
     *
     * @return int
     */
    public function getMaxChannels() {}

    /**
     * Get max supported frame size per connection in bytes.
     *
     * When connection is connected, effective connection value returned, which is normally the same as original
     * correspondent value passed to constructor, otherwise original value passed to constructor returned.
     *
     * @return int
     */
    public function getMaxFrameSize() {}

    /**
     * Get number of seconds between heartbeats of the connection in seconds.
     *
     * When connection is connected, effective connection value returned, which is normally the same as original
     * correspondent value passed to constructor, otherwise original value passed to constructor returned.
     *
     * @return int
     */
    public function getHeartbeatInterval() {}

    /**
     * Whether connection persistent.
     *
     * When connection is not connected, boolean false always returned
     *
     * @return bool
     */
    public function isPersistent() {}

    /**
     * Get path to the CA cert file in PEM format
     *
     * @return string
     */
    public function getCACert() {}

    /**
     * Set path to the CA cert file in PEM format
     *
     * @param string $cacert
     */
    public function setCACert($cacert) {}

    /**
     * Get path to the client certificate in PEM format
     *
     * @return string
     */
    public function getCert() {}

    /**
     * Set path to the client certificate in PEM format
     *
     * @param string $cert
     */
    public function setCert($cert) {}

    /**
     * Get path to the client key in PEM format
     *
     * @return string
     */
    public function getKey() {}

    /**
     * Set path to the client key in PEM format
     *
     * @param string $key
     */
    public function setKey($key) {}

    /**
     * Get whether peer verification enabled or disabled
     *
     * @return bool
     */
    public function getVerify() {}

    /**
     * Enable or disable peer verification
     *
     * @param bool $verify
     */
    public function setVerify($verify) {}

    /**
     * set authentication method
     *
     * @param int $method AMQP_SASL_METHOD_PLAIN | AMQP_SASL_METHOD_EXTERNAL
     */
    public function setSaslMethod($method) {}

    /**
     * Get authentication mechanism configuration
     *
     * @return int AMQP_SASL_METHOD_PLAIN | AMQP_SASL_METHOD_EXTERNAL
     */
    public function getSaslMethod() {}

    /**
     * Return the connection name
     * @return string|null
     */
    public function getConnectionName() {}

    /**
     * Set the connection name
     * @param string $connection_name
     * @return void
     */
    public function setConnectionName($connection_name) {}
}

/**
 * stub class representing AMQPConnectionException from pecl-amqp
 */
class AMQPConnectionException extends AMQPException {}

/**
 * stub class representing AMQPDecimal from pecl-amqp
 */
final class AMQPDecimal
{
    public const EXPONENT_MIN = 0;
    public const EXPONENT_MAX = 255;
    public const SIGNIFICAND_MIN = 0;
    public const SIGNIFICAND_MAX = 4294967295;

    /**
     * @param $exponent
     * @param $significand
     *
     * @throws AMQPExchangeValue
     */
    public function __construct($exponent, $significand) {}

    /** @return int */
    public function getExponent() {}

    /** @return int */
    public function getSignificand() {}
}

/**
 * stub class representing AMQPEnvelope from pecl-amqp
 */
class AMQPEnvelope extends AMQPBasicProperties
{
    public function __construct() {}

    /**
     * Get the body of the message.
     *
     * @return string The contents of the message body.
     */
    public function getBody() {}

    /**
     * Get the routing key of the message.
     *
     * @return string The message routing key.
     */
    public function getRoutingKey() {}

    /**
     * Get the consumer tag of the message.
     *
     * @return string The consumer tag of the message.
     */
    public function getConsumerTag() {}

    /**
     * Get the delivery tag of the message.
     *
     * @return string The delivery tag of the message.
     */
    public function getDeliveryTag() {}

    /**
     * Get the exchange name on which the message was published.
     *
     * @return string The exchange name on which the message was published.
     */
    public function getExchangeName() {}

    /**
     * Whether this is a redelivery of the message.
     *
     * Whether this is a redelivery of a message. If this message has been
     * delivered and AMQPEnvelope::nack() was called, the message will be put
     * back on the queue to be redelivered, at which point the message will
     * always return TRUE when this method is called.
     *
     * @return bool TRUE if this is a redelivery, FALSE otherwise.
     */
    public function isRedelivery() {}

    /**
     * Get a specific message header.
     *
     * @param string $header_key Name of the header to get the value from.
     *
     * @return string|false The contents of the specified header or FALSE
     *                        if not set.
     */
    public function getHeader($header_key) {}

    /**
     * Check whether specific message header exists.
     *
     * @param string $header_key Name of the header to check.
     *
     * @return bool
     */
    public function hasHeader($header_key) {}
}

/**
 * stub class representing AMQPEnvelopeException from pecl-amqp
 */
class AMQPEnvelopeException extends AMQPException
{
    /**
     * @var AMQPEnvelope
     */
    public $envelope;
}

/**
 * stub class representing AMQPException from pecl-amqp
 */
class AMQPException extends Exception {}

/**
 * stub class representing AMQPExchange from pecl-amqp
 */
class AMQPExchange
{
    /**
     * Bind to another exchange.
     *
     * Bind an exchange to another exchange using the specified routing key.
     *
     * @param string $exchange_name Name of the exchange to bind.
     * @param string $routing_key   The routing key to use for binding.
     * @param array  $arguments     Additional binding arguments.
     *
     * @throws AMQPExchangeException   On failure.
     * @throws AMQPChannelException    If the channel is not open.
     * @throws AMQPConnectionException If the connection to the broker was lost.
     * @return bool TRUE on success or FALSE on failure.
     */
    public function bind($exchange_name, $routing_key = '', array $arguments = []) {}

    /**
     * Remove binding to another exchange.
     *
     * Remove a routing key binding on an another exchange from the given exchange.
     *
     * @param string $exchange_name Name of the exchange to bind.
     * @param string $routing_key   The routing key to use for binding.
     * @param array  $arguments     Additional binding arguments.
     *
     * @throws AMQPExchangeException   On failure.
     * @throws AMQPChannelException    If the channel is not open.
     * @throws AMQPConnectionException If the connection to the broker was lost.
     * @return bool TRUE on success or FALSE on failure.
     */
    public function unbind($exchange_name, $routing_key = '', array $arguments = []) {}

    /**
     * Create an instance of AMQPExchange.
     *
     * Returns a new instance of an AMQPExchange object, associated with the
     * given AMQPChannel object.
     *
     * @param AMQPChannel $amqp_channel A valid AMQPChannel object, connected
     *                                  to a broker.
     *
     * @throws AMQPExchangeException   When amqp_channel is not connected to
     *                                 a broker.
     * @throws AMQPConnectionException If the connection to the broker was
     *                                 lost.
     */
    public function __construct(AMQPChannel $amqp_channel) {}

    /**
     * Declare a new exchange on the broker.
     *
     * @throws AMQPExchangeException   On failure.
     * @throws AMQPChannelException    If the channel is not open.
     * @throws AMQPConnectionException If the connection to the broker was lost.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function declareExchange() {}

    /**
     * Delete the exchange from the broker.
     *
     * @param string  $exchangeName Optional name of exchange to delete.
     * @param int $flags        Optionally AMQP_IFUNUSED can be specified
     *                              to indicate the exchange should not be
     *                              deleted until no clients are connected to
     *                              it.
     *
     * @throws AMQPExchangeException   On failure.
     * @throws AMQPChannelException    If the channel is not open.
     * @throws AMQPConnectionException If the connection to the broker was lost.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function delete($exchangeName = null, $flags = AMQP_NOPARAM) {}

    /**
     * Get the argument associated with the given key.
     *
     * @param string $key The key to look up.
     *
     * @return string|int|false The string or integer value associated
     *                             with the given key, or FALSE if the key
     *                             is not set.
     */
    public function getArgument($key) {}

    /**
     * Check whether argument associated with the given key exists.
     *
     * @param string $key The key to look up.
     *
     * @return bool
     */
    public function hasArgument($key) {}

    /**
     * Get all arguments set on the given exchange.
     *
     * @return array An array containing all of the set key/value pairs.
     */
    public function getArguments() {}

    /**
     * Get all the flags currently set on the given exchange.
     *
     * @return int An integer bitmask of all the flags currently set on this
     *             exchange object.
     */
    public function getFlags() {}

    /**
     * Get the configured name.
     *
     * @return string The configured name as a string.
     */
    public function getName() {}

    /**
     * Get the configured type.
     *
     * @return string The configured type as a string.
     */
    public function getType() {}

    /**
     * Publish a message to an exchange.
     *
     * Publish a message to the exchange represented by the AMQPExchange object.
     *
     * @param string  $message     The message to publish.
     * @param string  $routing_key The optional routing key to which to
     *                             publish to.
     * @param int $flags       One or more of AMQP_MANDATORY and
     *                             AMQP_IMMEDIATE.
     * @param array   $attributes  One of content_type, content_encoding,
     *                             message_id, user_id, app_id, delivery_mode,
     *                             priority, timestamp, expiration, type
     *                             or reply_to, headers.
     *
     * @throws AMQPExchangeException   On failure.
     * @throws AMQPChannelException    If the channel is not open.
     * @throws AMQPConnectionException If the connection to the broker was lost.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function publish(
        $message,
        $routing_key = null,
        $flags = AMQP_NOPARAM,
        array $attributes = []
    ) {}

    /**
     * Set the value for the given key.
     *
     * @param string         $key   Name of the argument to set.
     * @param string|int $value Value of the argument to set.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function setArgument($key, $value) {}

    /**
     * Set all arguments on the exchange.
     *
     * @param array $arguments An array of key/value pairs of arguments.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function setArguments(array $arguments) {}

    /**
     * Set the flags on an exchange.
     *
     * @param int $flags A bitmask of flags. This call currently only
     *                       considers the following flags:
     *                       AMQP_DURABLE, AMQP_PASSIVE
     *                       (and AMQP_DURABLE, if librabbitmq version >= 0.5.3)
     *
     * @return void
     */
    public function setFlags($flags) {}

    /**
     * Set the name of the exchange.
     *
     * @param string $exchange_name The name of the exchange to set as string.
     *
     * @return void
     */
    public function setName($exchange_name) {}

    /**
     * Set the type of the exchange.
     *
     * Set the type of the exchange. This can be any of AMQP_EX_TYPE_DIRECT,
     * AMQP_EX_TYPE_FANOUT, AMQP_EX_TYPE_HEADERS or AMQP_EX_TYPE_TOPIC.
     *
     * @param string $exchange_type The type of exchange as a string.
     *
     * @return void
     */
    public function setType($exchange_type) {}

    /**
     * Get the AMQPChannel object in use
     *
     * @return AMQPChannel
     */
    public function getChannel() {}

    /**
     * Get the AMQPConnection object in use
     *
     * @return AMQPConnection
     */
    public function getConnection() {}

    /**
     * Declare a new exchange on the broker.
     * @return int
     * @throws AMQPExchangeException
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     * @see AMQPExchange::declareExchange()
     */
    #[Deprecated]
    public function declare() {}
}

/**
 * stub class representing AMQPExchangeException from pecl-amqp
 */
class AMQPExchangeException extends AMQPException {}

/**
 * stub class representing AMQPQueue from pecl-amqp
 */
class AMQPQueue
{
    /**
     * Acknowledge the receipt of a message.
     *
     * This method allows the acknowledgement of a message that is retrieved
     * without the AMQP_AUTOACK flag through AMQPQueue::get() or
     * AMQPQueue::consume()
     *
     * @param string  $delivery_tag The message delivery tag of which to
     *                              acknowledge receipt.
     * @param int $flags        The only valid flag that can be passed is
     *                              AMQP_MULTIPLE.
     *
     * @throws AMQPChannelException    If the channel is not open.
     * @throws AMQPConnectionException If the connection to the broker was lost.
     *
     * @return bool
     */
    public function ack($delivery_tag, $flags = AMQP_NOPARAM) {}

    /**
     * Bind the given queue to a routing key on an exchange.
     *
     * @param string $exchange_name Name of the exchange to bind to.
     * @param string $routing_key   Pattern or routing key to bind with.
     * @param array  $arguments     Additional binding arguments.
     *
     * @throws AMQPChannelException    If the channel is not open.
     * @throws AMQPConnectionException If the connection to the broker was lost.
     *
     * @return bool
     */
    public function bind($exchange_name, $routing_key = null, array $arguments = []) {}

    /**
     * Cancel a queue that is already bound to an exchange and routing key.
     *
     * @param string $consumer_tag The consumer tag cancel. If no tag provided,
     *                             or it is empty string, the latest consumer
     *                             tag on this queue will be used and after
     *                             successful request it will set to null.
     *                             If it also empty, no `basic.cancel`
     *                             request will be sent. When consumer_tag give
     *                             and it equals to latest consumer_tag on queue,
     *                             it will be interpreted as latest consumer_tag usage.
     *
     * @throws AMQPChannelException    If the channel is not open.
     * @throws AMQPConnectionException If the connection to the broker was lost.
     *
     * @return bool
     */
    public function cancel($consumer_tag = '') {}

    /**
     * Create an instance of an AMQPQueue object.
     *
     * @param AMQPChannel $amqp_channel The amqp channel to use.
     *
     * @throws AMQPQueueException      When amqp_channel is not connected to a
     *                                 broker.
     * @throws AMQPConnectionException If the connection to the broker was lost.
     */
    public function __construct(AMQPChannel $amqp_channel) {}

    /**
     * Consume messages from a queue.
     *
     * Blocking function that will retrieve the next message from the queue as
     * it becomes available and will pass it off to the callback.
     *
     * @param callable|null $callback    A callback function to which the
     *                              consumed message will be passed. The
     *                              function must accept at a minimum
     *                              one parameter, an AMQPEnvelope object,
     *                              and an optional second parameter
     *                              the AMQPQueue object from which callback
     *                              was invoked. The AMQPQueue::consume() will
     *                              not return the processing thread back to
     *                              the PHP script until the callback
     *                              function returns FALSE.
     *                              If the callback is omitted or null is passed,
     *                              then the messages delivered to this client will
     *                              be made available to the first real callback
     *                              registered. That allows one to have a single
     *                              callback consuming from multiple queues.
     * @param int $flags        A bitmask of any of the flags: AMQP_AUTOACK,
     *                              AMQP_JUST_CONSUME. Note: when AMQP_JUST_CONSUME
     *                              flag used all other flags are ignored and
     *                              $consumerTag parameter has no sense.
     *                              AMQP_JUST_CONSUME flag prevent from sending
     *                              `basic.consume` request and just run $callback
     *                              if it provided. Calling method with empty $callback
     *                              and AMQP_JUST_CONSUME makes no sense.
     * @param string   $consumerTag A string describing this consumer. Used
     *                              for canceling subscriptions with cancel().
     *
     * @throws AMQPChannelException    If the channel is not open.
     * @throws AMQPConnectionException If the connection to the broker was lost.
     * @throws AMQPEnvelopeException   When no queue found for envelope.
     * @throws AMQPQueueException      If timeout occurs or queue is not exists.
     *
     * @return void
     */
    public function consume(
        callable $callback = null,
        $flags = AMQP_NOPARAM,
        $consumerTag = null
    ) {}

    /**
     * Declare a new queue on the broker.
     *
     * @throws AMQPChannelException    If the channel is not open.
     * @throws AMQPConnectionException If the connection to the broker was lost.
     * @throws AMQPQueueException      On failure.
     *
     * @return int the message count.
     */
    public function declareQueue() {}

    /**
     * Delete a queue from the broker.
     *
     * This includes its entire contents of unread or unacknowledged messages.
     *
     * @param int $flags        Optionally AMQP_IFUNUSED can be specified
     *                              to indicate the queue should not be
     *                              deleted until no clients are connected to
     *                              it.
     *
     * @throws AMQPChannelException    If the channel is not open.
     * @throws AMQPConnectionException If the connection to the broker was lost.
     *
     * @return int The number of deleted messages.
     */
    public function delete($flags = AMQP_NOPARAM) {}

    /**
     * Retrieve the next message from the queue.
     *
     * Retrieve the next available message from the queue. If no messages are
     * present in the queue, this function will return FALSE immediately. This
     * is a non blocking alternative to the AMQPQueue::consume() method.
     * Currently, the only supported flag for the flags parameter is
     * AMQP_AUTOACK. If this flag is passed in, then the message returned will
     * automatically be marked as acknowledged by the broker as soon as the
     * frames are sent to the client.
     *
     * @param int $flags A bitmask of supported flags for the
     *                       method call. Currently, the only the
     *                       supported flag is AMQP_AUTOACK. If this
     *                       value is not provided, it will use the
     *                       value of ini-setting amqp.auto_ack.
     *
     * @throws AMQPChannelException    If the channel is not open.
     * @throws AMQPConnectionException If the connection to the broker was lost.
     * @throws AMQPQueueException      If queue is not exist.
     *
     * @return AMQPEnvelope|false
     */
    public function get($flags = AMQP_NOPARAM) {}

    /**
     * Get the argument associated with the given key.
     *
     * @param string $key The key to look up.
     *
     * @return string|int|false The string or integer value associated
     *                             with the given key, or false if the key
     *                             is not set.
     */
    public function getArgument($key) {}

    /**
     * Get all set arguments as an array of key/value pairs.
     *
     * @return array An array containing all of the set key/value pairs.
     */
    public function getArguments() {}

    /**
     * Get all the flags currently set on the given queue.
     *
     * @return int An integer bitmask of all the flags currently set on this
     *             exchange object.
     */
    public function getFlags() {}

    /**
     * Get the configured name.
     *
     * @return string The configured name as a string.
     */
    public function getName() {}

    /**
     * Mark a message as explicitly not acknowledged.
     *
     * Mark the message identified by delivery_tag as explicitly not
     * acknowledged. This method can only be called on messages that have not
     * yet been acknowledged, meaning that messages retrieved with by
     * AMQPQueue::consume() and AMQPQueue::get() and using the AMQP_AUTOACK
     * flag are not eligible. When called, the broker will immediately put the
     * message back onto the queue, instead of waiting until the connection is
     * closed. This method is only supported by the RabbitMQ broker. The
     * behavior of calling this method while connected to any other broker is
     * undefined.
     *
     * @param string  $delivery_tag Delivery tag of last message to reject.
     * @param int $flags        AMQP_REQUEUE to requeue the message(s),
     *                              AMQP_MULTIPLE to nack all previous
     *                              unacked messages as well.
     *
     * @throws AMQPChannelException    If the channel is not open.
     * @throws AMQPConnectionException If the connection to the broker was lost.
     *
     * @return bool
     */
    public function nack($delivery_tag, $flags = AMQP_NOPARAM) {}

    /**
     * Mark one message as explicitly not acknowledged.
     *
     * Mark the message identified by delivery_tag as explicitly not
     * acknowledged. This method can only be called on messages that have not
     * yet been acknowledged, meaning that messages retrieved with by
     * AMQPQueue::consume() and AMQPQueue::get() and using the AMQP_AUTOACK
     * flag are not eligible.
     *
     * @param string  $delivery_tag Delivery tag of the message to reject.
     * @param int $flags        AMQP_REQUEUE to requeue the message(s).
     *
     * @throws AMQPChannelException    If the channel is not open.
     * @throws AMQPConnectionException If the connection to the broker was lost.
     *
     * @return bool
     */
    public function reject($delivery_tag, $flags = AMQP_NOPARAM) {}

    /**
     * Purge the contents of a queue.
     *
     * @throws AMQPChannelException    If the channel is not open.
     * @throws AMQPConnectionException If the connection to the broker was lost.
     *
     * @return bool
     */
    public function purge() {}

    /**
     * Set a queue argument.
     *
     * @param string $key   The key to set.
     * @param mixed  $value The value to set.
     *
     * @return bool
     */
    public function setArgument($key, $value) {}

    /**
     * Set all arguments on the given queue.
     *
     * All other argument settings will be wiped.
     *
     * @param array $arguments An array of key/value pairs of arguments.
     *
     * @return bool
     */
    public function setArguments(array $arguments) {}

    /**
     * Check whether a queue has specific argument.
     *
     * @param string $key   The key to check.
     *
     * @return bool
     */
    public function hasArgument($key) {}

    /**
     * Set the flags on the queue.
     *
     * @param int $flags A bitmask of flags:
     *                       AMQP_DURABLE, AMQP_PASSIVE,
     *                       AMQP_EXCLUSIVE, AMQP_AUTODELETE.
     *
     * @return bool
     */
    public function setFlags($flags) {}

    /**
     * Set the queue name.
     *
     * @param string $queue_name The name of the queue.
     *
     * @return bool
     */
    public function setName($queue_name) {}

    /**
     * Remove a routing key binding on an exchange from the given queue.
     *
     * @param string $exchange_name The name of the exchange on which the
     *                              queue is bound.
     * @param string $routing_key   The binding routing key used by the
     *                              queue.
     * @param array  $arguments     Additional binding arguments.
     *
     * @throws AMQPChannelException    If the channel is not open.
     * @throws AMQPConnectionException If the connection to the broker was lost.
     *
     * @return bool
     */
    public function unbind($exchange_name, $routing_key = null, array $arguments = []) {}

    /**
     * Get the AMQPChannel object in use
     *
     * @return AMQPChannel
     */
    public function getChannel() {}

    /**
     * Get the AMQPConnection object in use
     *
     * @return AMQPConnection
     */
    public function getConnection() {}

    /**
     * Get latest consumer tag. If no consumer available or the latest on was canceled null will be returned.
     *
     * @return string|null
     */
    public function getConsumerTag() {}

    /**
     * Declare a new queue
     * @return int
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     * @see AMQPQueue::declareQueue()
     */
    #[Deprecated]
    public function declare() {}
}

/**
 * stub class representing AMQPQueueException from pecl-amqp
 */
class AMQPQueueException extends AMQPException {}

/**
 * stub class representing AMQPTimestamp from pecl-amqp
 */
final class AMQPTimestamp
{
    public const MIN = "0";
    public const MAX = "18446744073709551616";

    /**
     * @param string $timestamp
     *
     * @throws AMQPExchangeValue
     */
    public function __construct($timestamp) {}

    /** @return string */
    public function getTimestamp() {}

    /** @return string */
    public function __toString() {}
}

/**
 * stub class representing AMQPExchangeValue from pecl-amqp
 */
class AMQPExchangeValue extends AMQPException {}

/**
 * stub class representing AMQPExchangeValue from pecl-amqp
 */
class AMQPValueException extends AMQPException {}
