<?php

// Start of sysvmsg v.
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

/**
 * Create or attach to a message queue
 * @link https://php.net/manual/en/function.msg-get-queue.php
 * @param int $key <p>
 * Message queue numeric ID
 * </p>
 * @param int $permissions [optional] <p>
 * Queue permissions. Default to 0666. If the message queue already
 * exists, the <i>perms</i> will be ignored.
 * </p>
 * @return resource|SysvMessageQueue|false a resource handle that can be used to access the System V message queue.
 */
#[LanguageLevelTypeAware(["8.0" => "SysvMessageQueue|false"], default: "resource|false")]
function msg_get_queue(int $key, int $permissions = 0666) {}

/**
 * Send a message to a message queue
 * @link https://php.net/manual/en/function.msg-send.php
 * @param SysvMessageQueue|resource $queue
 * @param int $message_type
 * @param mixed $message
 * @param bool $serialize [optional] <p>
 * The optional <i>serialize</i> controls how the
 * <i>message</i> is sent. <i>serialize</i>
 * defaults to <b>TRUE</b> which means that the <i>message</i> is
 * serialized using the same mechanism as the session module before being
 * sent to the queue. This allows complex arrays and objects to be sent to
 * other PHP scripts, or if you are using the WDDX serializer, to any WDDX
 * compatible client.
 * </p>
 * @param bool $blocking [optional] <p>
 * If the message is too large to fit in the queue, your script will wait
 * until another process reads messages from the queue and frees enough
 * space for your message to be sent.
 * This is called blocking; you can prevent blocking by setting the
 * optional <i>blocking</i> parameter to <b>FALSE</b>, in which
 * case <b>msg_send</b> will immediately return <b>FALSE</b> if the
 * message is too big for the queue, and set the optional
 * <i>errorcode</i> to <b>MSG_EAGAIN</b>,
 * indicating that you should try to send your message again a little
 * later on.
 * </p>
 * @param int &$error_code [optional]
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 * <p>
 * Upon successful completion the message queue data structure is updated as
 * follows: <i>msg_lspid</i> is set to the process-ID of the
 * calling process, <i>msg_qnum</i> is incremented by 1 and
 * <i>msg_stime</i> is set to the current time.
 * </p>
 */
function msg_send(#[LanguageLevelTypeAware(["8.0" => "SysvMessageQueue"], default: "resource")] $queue, int $message_type, $message, bool $serialize = true, bool $blocking = true, &$error_code): bool {}

/**
 * Receive a message from a message queue
 * @link https://php.net/manual/en/function.msg-receive.php
 * @param SysvMessageQueue|resource $queue
 * @param int $desired_message_type <p>
 * If <i>desiredmsgtype</i> is 0, the message from the front
 * of the queue is returned. If <i>desiredmsgtype</i> is
 * greater than 0, then the first message of that type is returned.
 * If <i>desiredmsgtype</i> is less than 0, the first
 * message on the queue with the lowest type less than or equal to the
 * absolute value of <i>desiredmsgtype</i> will be read.
 * If no messages match the criteria, your script will wait until a suitable
 * message arrives on the queue. You can prevent the script from blocking
 * by specifying <b>MSG_IPC_NOWAIT</b> in the
 * <i>flags</i> parameter.
 * </p>
 * @param int &$received_message_type <p>
 * The type of the message that was received will be stored in this
 * parameter.
 * </p>
 * @param int $max_message_size <p>
 * The maximum size of message to be accepted is specified by the
 * <i>maxsize</i>; if the message in the queue is larger
 * than this size the function will fail (unless you set
 * <i>flags</i> as described below).
 * </p>
 * @param mixed &$message <p>
 * The received message will be stored in <i>message</i>,
 * unless there were errors receiving the message.
 * </p>
 * @param bool $unserialize [optional] <p>
 * If set to
 * <b>TRUE</b>, the message is treated as though it was serialized using the
 * same mechanism as the session module. The message will be unserialized
 * and then returned to your script. This allows you to easily receive
 * arrays or complex object structures from other PHP scripts, or if you
 * are using the WDDX serializer, from any WDDX compatible source.
 * </p>
 * <p>
 * If <i>unserialize</i> is <b>FALSE</b>, the message will be
 * returned as a binary-safe string.
 * </p>
 * @param int $flags [optional] <p>
 * The optional <i>flags</i> allows you to pass flags to the
 * low-level msgrcv system call. It defaults to 0, but you may specify one
 * or more of the following values (by adding or ORing them together).
 * <table>
 * Flag values for msg_receive
 * <tr valign="top">
 * <td><b>MSG_IPC_NOWAIT</b></td>
 * <td>If there are no messages of the
 * <i>desiredmsgtype</i>, return immediately and do not
 * wait. The function will fail and return an integer value
 * corresponding to <b>MSG_ENOMSG</b>.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>MSG_EXCEPT</b></td>
 * <td>Using this flag in combination with a
 * <i>desiredmsgtype</i> greater than 0 will cause the
 * function to receive the first message that is not equal to
 * <i>desiredmsgtype</i>.</td>
 * </tr>
 * <tr valign="top">
 * <td><b>MSG_NOERROR</b></td>
 * <td>
 * If the message is longer than <i>maxsize</i>,
 * setting this flag will truncate the message to
 * <i>maxsize</i> and will not signal an error.
 * </td>
 * </tr>
 * </table>
 * </p>
 * @param int $error_code [optional] <p>
 * If the function fails, the optional <i>errorcode</i>
 * will be set to the value of the system errno variable.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 * <p>
 * Upon successful completion the message queue data structure is updated as
 * follows: msg_lrpid is set to the process-ID of the
 * calling process, msg_qnum is decremented by 1 and
 * msg_rtime is set to the current time.
 * </p>
 */
function msg_receive(#[LanguageLevelTypeAware(["8.0" => "SysvMessageQueue"], default: "resource")] $queue, int $desired_message_type, &$received_message_type, int $max_message_size, mixed &$message, bool $unserialize = true, int $flags = 0, &$error_code): bool {}

/**
 * Destroy a message queue
 * @link https://php.net/manual/en/function.msg-remove-queue.php
 * @param SysvMessageQueue|resource $queue <p>
 * Message queue resource handle
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function msg_remove_queue(#[LanguageLevelTypeAware(["8.0" => "SysvMessageQueue"], default: "resource")] $queue): bool {}

/**
 * Returns information from the message queue data structure
 * @link https://php.net/manual/en/function.msg-stat-queue.php
 * @param SysvMessageQueue|resource $queue <p>
 * Message queue resource handle
 * </p>
 * @return array|false The return value is an array whose keys and values have the following
 * meanings:
 * <table>
 * Array structure for msg_stat_queue
 * <tr valign="top">
 * <td>msg_perm.uid</td>
 * <td>
 * The uid of the owner of the queue.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>msg_perm.gid</td>
 * <td>
 * The gid of the owner of the queue.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>msg_perm.mode</td>
 * <td>
 * The file access mode of the queue.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>msg_stime</td>
 * <td>
 * The time that the last message was sent to the queue.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>msg_rtime</td>
 * <td>
 * The time that the last message was received from the queue.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>msg_ctime</td>
 * <td>
 * The time that the queue was last changed.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>msg_qnum</td>
 * <td>
 * The number of messages waiting to be read from the queue.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>msg_qbytes</td>
 * <td>
 * The maximum number of bytes allowed in one message queue. On
 * Linux, this value may be read and modified via
 * /proc/sys/kernel/msgmnb.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>msg_lspid</td>
 * <td>
 * The pid of the process that sent the last message to the queue.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>msg_lrpid</td>
 * <td>
 * The pid of the process that received the last message from the queue.
 * </td>
 * </tr>
 * </table>
 */
#[ArrayShape([
    "msg_perm.uid" => "int",
    "msg_perm.gid" => "int",
    "msg_perm.mode" => "int",
    "msg_stime" => "int",
    "msg_rtime" => "int",
    "msg_ctime" => "int",
    "msg_qnum" => "int",
    "msg_qbytes" => "int",
    "msg_lspid" => "int",
    "msg_lrpid" => "int",
])]
function msg_stat_queue(#[LanguageLevelTypeAware(["8.0" => "SysvMessageQueue"], default: "resource")] $queue): array|false {}

/**
 * Set information in the message queue data structure
 * @link https://php.net/manual/en/function.msg-set-queue.php
 * @param SysvMessageQueue|resource $queue <p>
 * Message queue resource handle
 * </p>
 * @param array $data <p>
 * You specify the values you require by setting the value of the keys
 * that you require in the <i>data</i> array.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function msg_set_queue(#[LanguageLevelTypeAware(["8.0" => "SysvMessageQueue"], default: "resource")] $queue, array $data): bool {}

/**
 * Check whether a message queue exists
 * @link https://php.net/manual/en/function.msg-queue-exists.php
 * @param int $key <p>
 * Queue key.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function msg_queue_exists(int $key): bool {}

define('MSG_IPC_NOWAIT', 1);
define('MSG_EAGAIN', 11);
define('MSG_ENOMSG', 42);
define('MSG_NOERROR', 2);
define('MSG_EXCEPT', 4);

/**
 * @since 8.0
 */
final class SysvMessageQueue
{
    /**
     * Cannot directly construct SysvMessageQueue, use msg_get_queue() instead
     * @see msg_get_queue()
     */
    private function __construct() {}
}

// End of sysvmsg v.
