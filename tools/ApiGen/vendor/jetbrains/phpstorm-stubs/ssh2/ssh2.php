<?php

// Start of ssh2 v.0.11.0-dev

/**
 * (PECL ssh2 &gt;= 0.9.0)<br/>
 * Connect to an SSH server
 * @link https://php.net/manual/en/function.ssh2-connect.php
 * @param string $host <p>
 * </p>
 * @param int $port [optional] <p>
 * </p>
 * @param null|array $methods [optional] <p>
 * methods may be an associative array with up to four parameters
 * as described below.
 * </p>
 * <p>
 * <table>
 * methods may be an associative array
 * with any or all of the following parameters.
 * <tr valign="top">
 * <td>Index</td>
 * <td>Meaning</td>
 * <td>Supported Values*</td>
 * </tr>
 * <tr valign="top">
 * <td>kex</td>
 * <td>
 * List of key exchange methods to advertise, comma separated
 * in order of preference.
 * </td>
 * <td>
 * diffie-hellman-group1-sha1,
 * diffie-hellman-group14-sha1, and
 * diffie-hellman-group-exchange-sha1
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>hostkey</td>
 * <td>
 * List of hostkey methods to advertise, come separated
 * in order of preference.
 * </td>
 * <td>
 * ssh-rsa and
 * ssh-dss
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>client_to_server</td>
 * <td>
 * Associative array containing crypt, compression, and
 * message authentication code (MAC) method preferences
 * for messages sent from client to server.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>server_to_client</td>
 * <td>
 * Associative array containing crypt, compression, and
 * message authentication code (MAC) method preferences
 * for messages sent from server to client.
 * </td>
 * </tr>
 * </table>
 * </p>
 * <p>
 * * - Supported Values are dependent on methods supported by underlying library.
 * See libssh2 documentation for additional
 * information.
 * </p>
 * <p>
 * <table>
 * client_to_server and
 * server_to_client may be an associative array
 * with any or all of the following parameters.
 * <tr valign="top">
 * <td>Index</td>
 * <td>Meaning</td>
 * <td>Supported Values*</td>
 * </tr>
 * <tr valign="top">
 * <td>crypt</td>
 * <td>List of crypto methods to advertise, comma separated
 * in order of preference.</td>
 * <td>
 * rijndael-cbc@lysator.liu.se,
 * aes256-cbc,
 * aes192-cbc,
 * aes128-cbc,
 * 3des-cbc,
 * blowfish-cbc,
 * cast128-cbc,
 * arcfour, and
 * none**
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>comp</td>
 * <td>List of compression methods to advertise, comma separated
 * in order of preference.</td>
 * <td>
 * zlib and
 * none
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>mac</td>
 * <td>List of MAC methods to advertise, come separated
 * in order of preference.</td>
 * <td>
 * hmac-sha1,
 * hmac-sha1-96,
 * hmac-ripemd160,
 * hmac-ripemd160@openssh.com, and
 * none**
 * </td>
 * </tr>
 * </table>
 * </p>
 * <p>
 * Crypt and MAC method "none"
 * <p>
 * For security reasons, none is disabled by the underlying
 * libssh2 library unless explicitly enabled
 * during build time by using the appropriate ./configure options. See documentation
 * for the underlying library for more information.
 * </p>
 * </p>
 * @param null|array $callbacks [optional] <p>
 * callbacks may be an associative array with any
 * or all of the following parameters.
 * <table>
 * Callbacks parameters
 * <tr valign="top">
 * <td>Index</td>
 * <td>Meaning</td>
 * <td>Prototype</td>
 * </tr>
 * <tr valign="top">
 * <td>ignore</td>
 * <td>
 * Name of function to call when an
 * SSH2_MSG_IGNORE packet is received
 * </td>
 * <td>void ignore_cb($message)</td>
 * </tr>
 * <tr valign="top">
 * <td>debug</td>
 * <td>
 * Name of function to call when an
 * SSH2_MSG_DEBUG packet is received
 * </td>
 * <td>void debug_cb($message, $language, $always_display)</td>
 * </tr>
 * <tr valign="top">
 * <td>macerror</td>
 * <td>
 * Name of function to call when a packet is received but the
 * message authentication code failed. If the callback returns
 * true, the mismatch will be ignored, otherwise the connection
 * will be terminated.
 * </td>
 * <td>bool macerror_cb($packet)</td>
 * </tr>
 * <tr valign="top">
 * <td>disconnect</td>
 * <td>
 * Name of function to call when an
 * SSH2_MSG_DISCONNECT packet is received
 * </td>
 * <td>void disconnect_cb($reason, $message, $language)</td>
 * </tr>
 * </table>
 * </p>
 * @return resource|false a resource on success, or false on error.
 */
function ssh2_connect($host, $port = 22, ?array $methods = null, ?array $callbacks = null) {}

/**
 * (PECL ssh2 &gt;= 1.0)<br/>
 * Close a connection to a remote SSH server
 * @link https://php.net/manual/en/function.ssh2-disconnect.php
 * @param resource $session <p>
 * An SSH connection link identifier, obtained from a call to
 * ssh2_connect.
 * </p>
 * @return bool
 */
function ssh2_disconnect($session) {}

/**
 * (PECL ssh2 &gt;= 0.9.0)<br/>
 * Return list of negotiated methods
 * @link https://php.net/manual/en/function.ssh2-methods-negotiated.php
 * @param resource $session <p>
 * An SSH connection link identifier, obtained from a call to
 * ssh2_connect.
 * </p>
 * @return array
 */
function ssh2_methods_negotiated($session) {}

/**
 * (PECL ssh2 &gt;= 0.9.0)<br/>
 * Retrieve fingerprint of remote server
 * @link https://php.net/manual/en/function.ssh2-fingerprint.php
 * @param resource $session <p>
 * An SSH connection link identifier, obtained from a call to
 * ssh2_connect.
 * </p>
 * @param int $flags [optional] <p>
 * flags may be either of
 * SSH2_FINGERPRINT_MD5 or
 * SSH2_FINGERPRINT_SHA1 logically ORed with
 * SSH2_FINGERPRINT_HEX or
 * SSH2_FINGERPRINT_RAW.
 * </p>
 * @return string the hostkey hash as a string.
 */
function ssh2_fingerprint($session, $flags = null) {}

/**
 * (PECL ssh2 &gt;= 0.9.0)<br/>
 * Authenticate as "none"
 * @link https://php.net/manual/en/function.ssh2-auth-none.php
 * @param resource $session <p>
 * An SSH connection link identifier, obtained from a call to
 * ssh2_connect.
 * </p>
 * @param string $username <p>
 * Remote user name.
 * </p>
 * @return array|bool true if the server does accept "none" as an authentication
 * method, or an array of accepted authentication methods on failure.
 */
function ssh2_auth_none($session, $username) {}

/**
 * (PECL ssh2 &gt;= 0.9.0)<br/>
 * Authenticate over SSH using a plain password
 * @link https://php.net/manual/en/function.ssh2-auth-password.php
 * @param resource $session <p>
 * An SSH connection link identifier, obtained from a call to
 * ssh2_connect.
 * </p>
 * @param string $username <p>
 * Remote user name.
 * </p>
 * @param string $password <p>
 * Password for username
 * </p>
 * @return bool true on success or false on failure.
 */
function ssh2_auth_password($session, $username, $password) {}

/**
 * (PECL ssh2 &gt;= 0.9.0)<br/>
 * Authenticate using a public key
 * @link https://php.net/manual/en/function.ssh2-auth-pubkey-file.php
 * @param resource $session <p>
 * An SSH connection link identifier, obtained from a call to
 * ssh2_connect.
 * </p>
 * @param string $username <p>
 * </p>
 * @param string $pubkeyfile <p>
 * </p>
 * @param string $privkeyfile <p>
 * </p>
 * @param string $passphrase [optional] <p>
 * If privkeyfile is encrypted (which it should
 * be), the passphrase must be provided.
 * </p>
 * @return bool true on success or false on failure.
 */
function ssh2_auth_pubkey_file($session, $username, $pubkeyfile, $privkeyfile, $passphrase = null) {}

/**
 * (PECL ssh2 &gt;= 0.9.0)<br/>
 * Authenticate using a public hostkey
 * @link https://php.net/manual/en/function.ssh2-auth-hostbased-file.php
 * @param resource $session <p>
 * An SSH connection link identifier, obtained from a call to
 * ssh2_connect.
 * </p>
 * @param string $username <p>
 * </p>
 * @param string $hostname <p>
 * </p>
 * @param string $pubkeyfile <p>
 * </p>
 * @param string $privkeyfile <p>
 * </p>
 * @param string $passphrase [optional] <p>
 * If privkeyfile is encrypted (which it should
 * be), the passphrase must be provided.
 * </p>
 * @param string $local_username [optional] <p>
 * If local_username is omitted, then the value
 * for username will be used for it.
 * </p>
 * @return bool true on success or false on failure.
 */
function ssh2_auth_hostbased_file($session, $username, $hostname, $pubkeyfile, $privkeyfile, $passphrase = null, $local_username = null) {}

function ssh2_forward_listen() {}

function ssh2_forward_accept() {}

/**
 * (PECL ssh2 &gt;= 0.9.0)<br/>
 * Request an interactive shell
 * @link https://php.net/manual/en/function.ssh2-shell.php
 * @param resource $session <p>
 * An SSH connection link identifier, obtained from a call to
 * ssh2_connect.
 * </p>
 * @param string $term_type [optional] <p>
 * term_type should correspond to one of the
 * entries in the target system's /etc/termcap file.
 * </p>
 * @param null|array $env [optional] <p>
 * env may be passed as an associative array of
 * name/value pairs to set in the target environment.
 * </p>
 * @param null|int $width [optional] <p>
 * Width of the virtual terminal.
 * </p>
 * @param null|int $height [optional] <p>
 * Height of the virtual terminal.
 * </p>
 * @param null|int $width_height_type [optional] <p>
 * width_height_type should be one of
 * SSH2_TERM_UNIT_CHARS or
 * SSH2_TERM_UNIT_PIXELS.
 * </p>
 * @return resource
 */
function ssh2_shell($session, $term_type = null, ?array $env = null, $width = null, $height = null, $width_height_type = null) {}

/**
 * (PECL ssh2 &gt;= 0.9.0)<br/>
 * Execute a command on a remote server
 * @link https://php.net/manual/en/function.ssh2-exec.php
 * @param resource $session <p>
 * An SSH connection link identifier, obtained from a call to
 * ssh2_connect.
 * </p>
 * @param string $command <p>
 * </p>
 * @param null|string $pty [optional] <p>
 * </p>
 * @param null|array $env [optional] <p>
 * env may be passed as an associative array of
 * name/value pairs to set in the target environment.
 * </p>
 * @param null|int $width [optional] <p>
 * Width of the virtual terminal.
 * </p>
 * @param null|int $height [optional] <p>
 * Height of the virtual terminal.
 * </p>
 * @param null|int $width_height_type [optional] <p>
 * width_height_type should be one of
 * SSH2_TERM_UNIT_CHARS or
 * SSH2_TERM_UNIT_PIXELS.
 * </p>
 * @return resource|false a stream on success or false on failure.
 */
function ssh2_exec($session, $command, $pty = null, ?array $env = null, $width = null, $height = null, $width_height_type = null) {}

/**
 * (PECL ssh2 &gt;= 0.9.0)<br/>
 * Open a tunnel through a remote server
 * @link https://php.net/manual/en/function.ssh2-tunnel.php
 * @param resource $session <p>
 * An SSH connection link identifier, obtained from a call to
 * ssh2_connect.
 * </p>
 * @param string $host <p>
 * </p>
 * @param int $port <p>
 * </p>
 * @return resource
 */
function ssh2_tunnel($session, $host, $port) {}

/**
 * (PECL ssh2 &gt;= 0.9.0)<br/>
 * Request a file via SCP
 * @link https://php.net/manual/en/function.ssh2-scp-recv.php
 * @param resource $session <p>
 * An SSH connection link identifier, obtained from a call to
 * ssh2_connect.
 * </p>
 * @param string $remote_file <p>
 * Path to the remote file.
 * </p>
 * @param string $local_file <p>
 * Path to the local file.
 * </p>
 * @return bool true on success or false on failure.
 */
function ssh2_scp_recv($session, $remote_file, $local_file) {}

/**
 * (PECL ssh2 &gt;= 0.9.0)<br/>
 * Send a file via SCP
 * @link https://php.net/manual/en/function.ssh2-scp-send.php
 * @param resource $session <p>
 * An SSH connection link identifier, obtained from a call to
 * ssh2_connect.
 * </p>
 * @param string $local_file <p>
 * Path to the local file.
 * </p>
 * @param string $remote_file <p>
 * Path to the remote file.
 * </p>
 * @param int $create_mode [optional] <p>
 * The file will be created with the mode specified by
 * create_mode.
 * </p>
 * @return bool true on success or false on failure.
 */
function ssh2_scp_send($session, $local_file, $remote_file, $create_mode = null) {}

/**
 * (PECL ssh2 &gt;= 0.9.0)<br/>
 * Fetch an extended data stream
 * @link https://php.net/manual/en/function.ssh2-fetch-stream.php
 * @param resource $channel <p>
 * </p>
 * @param int $streamid <p>
 * An SSH2 channel stream.
 * </p>
 * @return resource the requested stream resource.
 */
function ssh2_fetch_stream($channel, $streamid) {}

/**
 * @param array &$var1
 */
function ssh2_poll(&$var1) {}

/**
 * (PECL ssh2 &gt;= 0.9.0)<br/>
 * Initialize SFTP subsystem
 * @link https://php.net/manual/en/function.ssh2-sftp.php
 * @param resource $session <p>
 * An SSH connection link identifier, obtained from a call to
 * ssh2_connect.
 * </p>
 * @return resource|false This method returns an SSH2 SFTP resource for use with
 * all other ssh2_sftp_*() methods and the
 * ssh2.sftp:// fopen wrapper.
 */
function ssh2_sftp($session) {}

/**
 * (PECL ssh2 &gt;= 0.9.0)<br/>
 * Rename a remote file
 * @link https://php.net/manual/en/function.ssh2-sftp-rename.php
 * @param resource $sftp <p>
 * An SSH2 SFTP resource opened by ssh2_sftp.
 * </p>
 * @param string $from <p>
 * The current file that is being renamed.
 * </p>
 * @param string $to <p>
 * The new file name that replaces from.
 * </p>
 * @return bool true on success or false on failure.
 */
function ssh2_sftp_rename($sftp, $from, $to) {}

/**
 * (PECL ssh2 &gt;= 0.9.0)<br/>
 * Delete a file
 * @link https://php.net/manual/en/function.ssh2-sftp-unlink.php
 * @param resource $sftp <p>
 * An SSH2 SFTP resource opened by ssh2_sftp.
 * </p>
 * @param string $filename <p>
 * </p>
 * @return bool true on success or false on failure.
 */
function ssh2_sftp_unlink($sftp, $filename) {}

/**
 * (PECL ssh2 &gt;= 0.9.0)<br/>
 * Create a directory
 * @link https://php.net/manual/en/function.ssh2-sftp-mkdir.php
 * @param resource $sftp <p>
 * An SSH2 SFTP resource opened by ssh2_sftp.
 * </p>
 * @param string $dirname <p>
 * Path of the new directory.
 * </p>
 * @param int $mode [optional] <p>
 * Permissions on the new directory.
 * </p>
 * @param bool $recursive [optional] <p>
 * If recursive is true any parent directories
 * required for dirname will be automatically created as well.
 * </p>
 * @return bool true on success or false on failure.
 */
function ssh2_sftp_mkdir($sftp, $dirname, $mode = null, $recursive = null) {}

/**
 * (PECL ssh2 &gt;= 0.9.0)<br/>
 * Remove a directory
 * @link https://php.net/manual/en/function.ssh2-sftp-rmdir.php
 * @param resource $sftp <p>
 * An SSH2 SFTP resource opened by ssh2_sftp.
 * </p>
 * @param string $dirname <p>
 * </p>
 * @return bool true on success or false on failure.
 */
function ssh2_sftp_rmdir($sftp, $dirname) {}

/**
 * (PECL ssh2 &gt;= 0.9.0)<br/>
 * Stat a file on a remote filesystem
 * @link https://php.net/manual/en/function.ssh2-sftp-stat.php
 * @param resource $sftp <p>
 * An SSH2 SFTP resource opened by ssh2_sftp.
 * </p>
 * @param string $path <p>
 * </p>
 * @return array|false See the documentation for stat for details on the
 * values which may be returned.
 */
function ssh2_sftp_stat($sftp, $path) {}

/**
 * (PECL ssh2 &gt;= 0.9.0)<br/>
 * Stat a symbolic link
 * @link https://php.net/manual/en/function.ssh2-sftp-lstat.php
 * @param resource $sftp <p>
 * </p>
 * @param string $path <p>
 * Path to the remote symbolic link.
 * </p>
 * @return array See the documentation for stat for details on the
 * values which may be returned.
 */
function ssh2_sftp_lstat($sftp, $path) {}

/**
 * (PECL ssh2 &gt;= 0.9.0)<br/>
 * Create a symlink
 * @link https://php.net/manual/en/function.ssh2-sftp-symlink.php
 * @param resource $sftp <p>
 * An SSH2 SFTP resource opened by ssh2_sftp.
 * </p>
 * @param string $target <p>
 * Target of the symbolic link.
 * </p>
 * @param string $link <p>
 * </p>
 * @return bool true on success or false on failure.
 */
function ssh2_sftp_symlink($sftp, $target, $link) {}

/**
 * (PECL ssh2 &gt;= 0.9.0)<br/>
 * Return the target of a symbolic link
 * @link https://php.net/manual/en/function.ssh2-sftp-readlink.php
 * @param resource $sftp <p>
 * An SSH2 SFTP resource opened by ssh2_sftp.
 * </p>
 * @param string $link <p>
 * Path of the symbolic link.
 * </p>
 * @return string the target of the symbolic link.
 */
function ssh2_sftp_readlink($sftp, $link) {}

/**
 * (PECL ssh2 &gt;= 0.9.0)<br/>
 * Resolve the realpath of a provided path string
 * @link https://php.net/manual/en/function.ssh2-sftp-realpath.php
 * @param resource $sftp <p>
 * An SSH2 SFTP resource opened by ssh2_sftp.
 * </p>
 * @param string $filename <p>
 * </p>
 * @return string the real path as a string.
 */
function ssh2_sftp_realpath($sftp, $filename) {}

/**
 * (PECL ssh2 &gt;= 0.10)<br/>
 * Initialize Publickey subsystem
 * @link https://php.net/manual/en/function.ssh2-publickey-init.php
 * @param resource $session <p>
 * </p>
 * @return resource|false an SSH2 Publickey Subsystem resource for use
 * with all other ssh2_publickey_*() methods or false on failure.
 */
function ssh2_publickey_init($session) {}

/**
 * (PECL ssh2 &gt;= 0.10)<br/>
 * Add an authorized publickey
 * @link https://php.net/manual/en/function.ssh2-publickey-add.php
 * @param resource $pkey <p>
 * Publickey Subsystem resource created by ssh2_publickey_init.
 * </p>
 * @param string $algoname <p>
 * Publickey algorithm (e.g.): ssh-dss, ssh-rsa
 * </p>
 * @param string $blob <p>
 * Publickey blob as raw binary data
 * </p>
 * @param null|bool $overwrite [optional] <p>
 * If the specified key already exists, should it be overwritten?
 * </p>
 * @param null|array $attributes [optional] <p>
 * Associative array of attributes to assign to this public key.
 * Refer to ietf-secsh-publickey-subsystem for a list of supported attributes.
 * To mark an attribute as mandatory, precede its name with an asterisk.
 * If the server is unable to support an attribute marked mandatory,
 * it will abort the add process.
 * </p>
 * @return bool true on success or false on failure.
 */
function ssh2_publickey_add($pkey, $algoname, $blob, $overwrite = null, ?array $attributes = null) {}

/**
 * (PECL ssh2 &gt;= 0.10)<br/>
 * Remove an authorized publickey
 * @link https://php.net/manual/en/function.ssh2-publickey-remove.php
 * @param resource $pkey <p>
 * Publickey Subsystem Resource
 * </p>
 * @param string $algoname <p>
 * Publickey algorithm (e.g.): ssh-dss, ssh-rsa
 * </p>
 * @param string $blob <p>
 * Publickey blob as raw binary data
 * </p>
 * @return bool true on success or false on failure.
 */
function ssh2_publickey_remove($pkey, $algoname, $blob) {}

/**
 * (PECL ssh2 &gt;= 0.10)<br/>
 * List currently authorized publickeys
 * @link https://php.net/manual/en/function.ssh2-publickey-list.php
 * @param resource $pkey <p>
 * Publickey Subsystem resource
 * </p>
 * @return array a numerically indexed array of keys,
 * each of which is an associative array containing:
 * name, blob, and attrs elements.
 * </p>
 * <p>
 * <table>
 * Publickey elements
 * <tr valign="top">
 * <td>Array Key</td>
 * <td>Meaning</td>
 * </tr>
 * <tr valign="top">
 * <td>name</td>
 * <td>Name of algorithm used by this publickey, for example:
 * ssh-dss or ssh-rsa.</td>
 * </tr>
 * <tr valign="top">
 * <td>blob</td>
 * <td>Publickey blob as raw binary data.</td>
 * </tr>
 * <tr valign="top">
 * <td>attrs</td>
 * <td>Attributes assigned to this publickey. The most common
 * attribute, and the only one supported by publickey version 1
 * servers, is comment, which may be any freeform
 * string.</td>
 * </tr>
 * </table>
 */
function ssh2_publickey_list($pkey) {}

/**
 * (PECL ssh2 &gt;= 0.12)<br/>
 * ssh2_sftp_chmod — Changes file mode
 * @link https://php.net/manual/en/function.ssh2-sftp-chmod.php
 * @param resource $sftp <p>An SSH2 SFTP resource opened by ssh2_sftp().</p>
 * @param string $filename <p>Path to the file.</p>
 * @param int $mode <p>Permissions on the file. See the chmod() for more details on this parameter.</p>
 * @return bool <p>Returns TRUE on success or FALSE on failure.</p>
 */
function ssh2_sftp_chmod($sftp, $filename, $mode) {}

/**
 * (PECL ssh2 &gt;= 0.12)<br/>
 * ssh2_auth_agent — Authenticate over SSH using the ssh agent
 * @link https://php.net/manual/en/function.ssh2-auth-agent.php
 * <p>
 * Authenticate over SSH using the ssh agent
 * </p>
 * <p>
 * Note: The ssh2_auth_agent() function will only be available when the ssh2 extension is compiled with libssh >= 1.2.3.
 * </p>
 * @param resource $session <p>An SSH connection link identifier, obtained from a call to ssh2_connect().</p>
 * @param string $username <p>Remote user name.</p>
 * @return bool <p>Returns TRUE on success or FALSE on failure.</p>
 */
function ssh2_auth_agent($session, $username) {}

/**
 * Flag to ssh2_fingerprint requesting hostkey
 * fingerprint as an MD5 hash.
 * @link https://php.net/manual/en/ssh2.constants.php
 */
define('SSH2_FINGERPRINT_MD5', 0);

/**
 * Flag to ssh2_fingerprint requesting hostkey
 * fingerprint as an SHA1 hash.
 * @link https://php.net/manual/en/ssh2.constants.php
 */
define('SSH2_FINGERPRINT_SHA1', 1);

/**
 * Flag to ssh2_fingerprint requesting hostkey
 * fingerprint as a string of hexits.
 * @link https://php.net/manual/en/ssh2.constants.php
 */
define('SSH2_FINGERPRINT_HEX', 0);

/**
 * Flag to ssh2_fingerprint requesting hostkey
 * fingerprint as a raw string of 8-bit characters.
 * @link https://php.net/manual/en/ssh2.constants.php
 */
define('SSH2_FINGERPRINT_RAW', 2);

/**
 * Flag to ssh2_shell specifying that
 * width and height
 * are provided as character sizes.
 * @link https://php.net/manual/en/ssh2.constants.php
 */
define('SSH2_TERM_UNIT_CHARS', 0);

/**
 * Flag to ssh2_shell specifying that
 * width and height
 * are provided in pixel units.
 * @link https://php.net/manual/en/ssh2.constants.php
 */
define('SSH2_TERM_UNIT_PIXELS', 1);

/**
 * Default terminal type (e.g. vt102, ansi, xterm, vanilla) requested
 * by ssh2_shell.
 * @link https://php.net/manual/en/ssh2.constants.php
 */
define('SSH2_DEFAULT_TERMINAL', "vanilla");

/**
 * Default terminal width requested by ssh2_shell.
 * @link https://php.net/manual/en/ssh2.constants.php
 */
define('SSH2_DEFAULT_TERM_WIDTH', 80);

/**
 * Default terminal height requested by ssh2_shell.
 * @link https://php.net/manual/en/ssh2.constants.php
 */
define('SSH2_DEFAULT_TERM_HEIGHT', 25);

/**
 * Default terminal units requested by ssh2_shell.
 * @link https://php.net/manual/en/ssh2.constants.php
 */
define('SSH2_DEFAULT_TERM_UNIT', 0);

/**
 * Flag to ssh2_fetch_stream requesting STDIO subchannel.
 * @link https://php.net/manual/en/ssh2.constants.php
 */
define('SSH2_STREAM_STDIO', 0);

/**
 * Flag to ssh2_fetch_stream requesting STDERR subchannel.
 * @link https://php.net/manual/en/ssh2.constants.php
 */
define('SSH2_STREAM_STDERR', 1);
define('SSH2_POLLIN', 1);
define('SSH2_POLLEXT', 2);
define('SSH2_POLLOUT', 4);
define('SSH2_POLLERR', 8);
define('SSH2_POLLHUP', 16);
define('SSH2_POLLNVAL', 32);
define('SSH2_POLL_SESSION_CLOSED', 16);
define('SSH2_POLL_CHANNEL_CLOSED', 128);
define('SSH2_POLL_LISTENER_CLOSED', 128);
