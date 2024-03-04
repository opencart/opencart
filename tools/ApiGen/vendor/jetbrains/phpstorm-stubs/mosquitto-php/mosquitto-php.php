<?php

namespace Mosquitto;

/**
 * @link https://mosquitto-php.readthedocs.io/en/latest/client.html
 **/
class Client
{
    /** @const LOG_DEBUG Identifies a debug-level log message */
    public const LOG_DEBUG = 16;

    /** @const LOG_INFO Identifies an info-level log message */
    public const LOG_INFO = 1;

    /** @const LOG_NOTICE Identifies a notice-level log message */
    public const LOG_NOTICE = 2;

    /** @const LOG_WARNING Identifies a warning-level log message */
    public const LOG_WARNING = 4;

    /** @const LOG_ERR Identifies an error-level log message */
    public const LOG_ERR = 8;

    /** @const SSL_VERIFY_NONE Used with `setTlsInsecure`. Do not verify the identity of the server, thus making the connection insecure. */
    public const SSL_VERIFY_NONE = 0;

    /** @const SSL_VERIFY_PEER Used with `setTlsInsecure`. Verify the identity of the server. */
    public const SSL_VERIFY_PEER = 1;

    /**
     * Construct a new Client instance.
     *
     * @param string|null $id The client ID. If omitted or null, one will be generated at random.
     * @param bool $cleanSession Set to true to instruct the broker to clean all messages and subscriptions on disconnect. Must be true if the $id parameter is null.
     */
    public function __construct($id = null, $cleanSession = true) {}

    /**
     * Set the username and password to use on connecting to the broker. Must be called before `connect`.
     *
     * @param string $username Username to supply to the broker
     * @param string $password Password to supply to the broker
     */
    public function setCredentials($username, $password) {}

    /**
     * Configure the client for certificate based SSL/TLS support. Must be called before connect. Cannot be used in
     * conjunction with setTlsPSK.
     *
     * Define the Certificate Authority certificates to be trusted (ie. the server certificate must be signed
     * with one of these certificates) using $caFile. If the server you are connecting to requires clients
     * to provide a certificate, define $certFile and $keyFile with your client certificate and private key.
     * If your private key is encrypted, provide the password as the fourth parameter.
     *
     * @param string $caPath Path to the PEM encoded trusted CA certificate files, or to a directory containing them.
     * @param string|null $certFile Path to the PEM encoded certificate file for this client. Optional.
     * @param string|null $keyFile Path to a file containing the PEM encoded private key for this client. Required if certfile is set.
     * @param string|null $password The password for the keyfile, if it is encrypted. If null, the password will be asked for on the command line.
     * @return int|null
     */
    public function setTlsCertificates($caPath, $certFile = null, $keyFile = null, $password = null) {}

    /**
     * Configure verification of the server hostname in the server certificate. If `$value` is `true`, it is impossible
     * to guarantee that the host you are connecting to is not impersonating your server. Do not use this function in a
     * real system. Must be called before `connect`.
     *
     * @param bool $value If set to false, the default, certificate hostname checking is performed. If set to `true`, no hostname checking is performed and the connection is insecure.
     */
    public function setTlsInsecure($value) {}

    /**
     * Set advanced SSL/TLS options. Must be called before `connect`.
     *
     * @param int $certReqs Whether or not to verify the server. Can be Client::SSL_VERIFY_NONE, to disable certificate verification, or Client::SSL_VERIFY_PEER (the default), to verify the server certificate.
     * @param string|null $tlsVersion The TLS version to use. If `null`, a default is used. The default value depends on the version of OpenSSL the library was compiled against. Available options on OpenSSL >= 1.0.1 are `tlsv1.2`, `tlsv1.1` and `tlsv1`.
     * @param string|null $ciphers A string describing the ciphers available for use. See the `openssl ciphers` tool for more information. If `null`, the default set will be used.
     * @return int
     */
    public function setTlsOptions($certReqs, $tlsVersion = null, $ciphers = null) {}

    /**
     * Configure the client for pre-shared-key based TLS support. Must be called before `connect`. Cannot be used in
     * conjunction with setTlsCertificates.
     *
     * @param string $psk The pre-shared key in hex format with no leading "0x".
     * @param string $identity The identity of this client. May be used as the username depending on server settings.
     * @param string|null $ciphers Optional. A string describing the ciphers available for use. See the `openssl ciphers` tool for more information. If `null`, the default set will be used.
     * @return int
     */
    public function setTlsPSK($psk, $identity, $ciphers = null) {}

    /**
     * Set the client “last will and testament”, which will be sent on an unclean disconnection from the broker.
     * Must be called before `connect`.
     *
     * @param string $topic The topic on which to publish the will.
     * @param string $payload The data to send.
     * @param int $qos Optional. Default 0. Integer 0, 1, or 2 indicating the Quality of Service to be used.
     * @param bool $retain Optional. Default false. If true, the message will be retained.
     */
    public function setWill($topic, $payload, $qos = 0, $retain = false) {}

    /**
     * Remove a previously-set will. No parameters.
     */
    public function clearWill() {}

    /**
     * Control the behaviour of the client when it has unexpectedly disconnected in Client::loopForever().
     * The default behaviour if this method is not used is to repeatedly attempt to reconnect with a delay of 1 second
     * until the connection succeeds.
     *
     * @param int $reconnectDelay Set delay between successive reconnection attempts.
     * @param int $exponentialDelay Set max delay between successive reconnection attempts when exponential backoff is enabled
     * @param bool $exponentialBackoff Pass `true` to enable exponential backoff
     */
    public function setReconnectDelay($reconnectDelay, $exponentialDelay = 0, $exponentialBackoff = false) {}

    /**
     * Connect to an MQTT broker.
     *
     * @param string $host Hostname to connect to
     * @param int $port Optional. Port number to connect to. Defaults to 1883.
     * @param int $keepalive Optional. Number of sections after which the broker should PING the client if no messages have been received.
     * @param string|null $interface Optional. The address or hostname of a local interface to bind to for this connection.
     * @return int
     */
    public function connect($host, $port = 1883, $keepalive = 60, $interface = null) {}

    /**
     * Disconnect from the broker. No parameters.
     */
    public function disconnect() {}

    /**
     * Set the connect callback. This is called when the broker sends a CONNACK message in response to a connection.
     *
     * (int) $rc, (string) $message
     * function ($rc, $message) {}
     *
     * Response codes:
     * 0 = Success
     * 1 = Connection refused (unacceptable protocol version)
     * 2 = Connection refused (identifier rejected)
     * 3 = Connection refused (broker unavailable)
     * 4-255 = Reserved for future use
     *
     * @param callable $callback
     */
    public function onConnect($callback) {}

    /**
     * Set the disconnect callback. This is called when the broker has received the DISCONNECT command and has
     * disconnected the client.
     *
     * (int) $rc
     * function ($rc) {}
     *
     * Response codes:
     * 0 = requested by client
     * <0 = indicates an unexpected disconnection.
     *
     * @param callable $callback
     */
    public function onDisconnect($callback) {}

    /**
     * Set the logging callback.
     *
     * (int) $level, (string) $str
     * function ($level, $str) {}
     *
     * Log levels:
     * Client::LOG_DEBUG
     * Client::LOG_INFO
     * Client::LOG_NOTICE
     * Client::LOG_WARNING
     * Client::LOG_ERR
     *
     * @param callable $callback
     */
    public function onLog($callback) {}

    /**
     * Set the subscribe callback. This is called when the broker responds to a subscription request.
     *
     * (int) $mid, (int) $qosCount
     * function ($mid, $qosCount) {}
     *
     * @param callable $callback
     */
    public function onSubscribe($callback) {}

    /**
     * Set the unsubscribe callback. This is called when the broker responds to a unsubscribe request.
     *
     * (int) $mid
     * function ($mid) {}
     *
     * @param callable $callback
     */
    public function onUnsubscribe($callback) {}

    /**
     * Set the message callback. This is called when a message is received from the broker.
     *
     * (object) $message
     * function (Mosquitto\Message $message) {}
     *
     * @param callable $callback
     */
    public function onMessage($callback) {}

    /**
     * Set the publish callback. This is called when a message is published by the client itself.
     *
     * Warning: this may be called before the method publish returns the message id, so, you need to create a queue to
     * deal with the MID list.
     *
     * (int) $mid - the message id returned by `publish`
     * function ($mid) {}
     *
     * @param callable $callback
     */
    public function onPublish($callback) {}

    /**
     * Set the number of QoS 1 and 2 messages that can be “in flight” at one time. An in flight message is part way
     * through its delivery flow. Attempts to send further messages with publish will result in the messages
     * being queued until the number of in flight messages reduces.
     *
     * Set to 0 for no maximum.
     *
     * @param int $maxInFlightMessages
     */
    public function setMaxInFlightMessages($maxInFlightMessages) {}

    /**
     * Set the number of seconds to wait before retrying messages. This applies to publishing messages with QoS > 0.
     * May be called at any time.
     *
     * @param int $messageRetryPeriod The retry period
     */
    public function setMessageRetry($messageRetryPeriod) {}

    /**
     * Publish a message on a given topic.
     * Return the message ID returned by the broker. Warning: the message ID is not unique.
     *
     * @param string $topic The topic to publish on
     * @param string $payload The message payload
     * @param int $qos Integer value 0, 1 or 2 indicating the QoS for this message
     * @param bool $retain If true, retain this message
     * @return int
     */
    public function publish($topic, $payload, $qos = 0, $retain = false) {}

    /**
     * Subscribe to a topic.
     * Return the message ID of the subscription message, so this can be matched up in the `onSubscribe` callback.
     *
     * @param string $topic
     * @param int $qos
     * @return int
     */
    public function subscribe($topic, $qos) {}

    /**
     * Unsubscribe from a topic.
     * Return the message ID of the subscription message, so this can be matched up in the `onUnsubscribe` callback.
     *
     * @param string $topic
     * @param int $qos
     * @return int
     */
    public function unsubscribe($topic, $qos) {}

    /**
     * The main network loop for the client. You must call this frequently in order to keep communications between
     * the client and broker working. If incoming data is present it will then be processed. Outgoing commands,
     * from e.g. `publish`, are normally sent immediately that their function is called, but this is not always possible.
     * `loop` will also attempt to send any remaining outgoing messages, which also includes commands that are part
     * of the flow for messages with QoS > 0.
     *
     * @param int $timeout Optional. Number of milliseconds to wait for network activity. Pass 0 for instant timeout.
     */
    public function loop($timeout = 1000) {}

    /**
     * Call loop() in an infinite blocking loop. Callbacks will be called as required. This will handle reconnecting
     * if the connection is lost. Call `disconnect` in a callback to disconnect and return from the loop. Alternatively,
     * call `exitLoop` to exit the loop without disconnecting. You will need to re-enter the loop again afterwards
     * to maintain the connection.
     *
     * @param int $timeout Optional. Number of milliseconds to wait for network activity. Pass 0 for instant timeout.
     */
    public function loopForever($timeout = 1000) {}

    /**
     * Exit the `loopForever` event loop without disconnecting. You will need to re-enter the loop afterwards
     * in order to maintain the connection.
     */
    public function exitLoop() {}
}

/**
 * @link https://mosquitto-php.readthedocs.io/en/latest/message.html
 */
class Message
{
    /** @var string */
    public $topic;

    /** @var string */
    public $payload;

    /** @var int */
    public $mid;

    /** @var int */
    public $qos;

    /** @var bool */
    public $retain;

    /**
     * Returns true if the supplied topic matches the supplied description, and otherwise false.
     *
     * @param string $topic The topic to match
     * @param string $subscription The subscription to match
     * @return bool
     */
    public static function topicMatchesSub($topic, $subscription) {}

    /**
     * Tokenise a topic or subscription string into an array of strings representing the topic hierarchy.
     *
     * @param string $topic
     * @return array
     */
    public static function tokeniseTopic($topic) {}
}

/**
 * @link https://mosquitto-php.readthedocs.io/en/latest/exception.html
 */
class Exception extends \Exception {}
