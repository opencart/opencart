<?php

// Start of sockets v.
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;

/**
 * (PHP 7 &gt;= 7.2.0)<br/>
 * Get array with contents of getaddrinfo about the given hostname.
 * @link https://www.php.net/manual/en/function.socket-addrinfo-lookup.php
 * @param string $host <p>
 * Hostname to search.
 * </p>
 * @param string $service [optional] <p>
 * The service to connect to. If service is a name, it is translated to the corresponding port number.
 * </p>
 * @param array $hints <p>
 * Hints provide criteria for selecting addresses returned. You may specify the hints as defined by getadrinfo.
 * </p>
 * @return AddressInfo[]|false of AddrInfo resource handles that can be used with the other socket_addrinfo functions.
 * @since 7.2
 */
function socket_addrinfo_lookup(string $host, ?string $service, array $hints = []): array|false {}

/**
 * Create a Socket resource, and connect it to the provided AddrInfo resource.<br/>
 * The return value of this function may be used with the rest of the socket functions.
 * @link https://www.php.net/manual/en/function.socket-addrinfo-connect.php
 * @param resource|AddressInfo $address <p>
 * Resource created from {@see socket_addrinfo_lookup()}
 * </p>
 * @return resource|Socket|null|false Socket resource on success or NULL on failure.
 * @since 7.2
 */
function socket_addrinfo_connect(AddressInfo $address): Socket|false {}

/**
 * (PHP 7 &gt;= 7.2.0)<br/>
 * Create a Socket resource, and bind it to the provided AddrInfo resource.<br/>
 * The return value of this function may be used with {@see socket_listen()}.
 * @link https://www.php.net/manual/en/function.socket-addrinfo-bind.php
 * @param resource|AddressInfo $address <p>
 * Resource created from {@see socket_addrinfo_lookup()}
 * </p>
 * @return resource|Socket|null|false Socket resource on success or NULL on failure.
 * @since 7.2
 */
function socket_addrinfo_bind(AddressInfo $address): Socket|false {}

/**
 * (PHP 7 &gt;= 7.2.0)<br/>
 * Get information about addrinfo
 * @link https://www.php.net/manual/en/function.socket-addrinfo-explain.php
 * @param resource|AddressInfo $address <p>
 * Resource created from {@see socket_addrinfo_lookup()}
 * </p>
 * @return array containing the fields in the addrinfo structure.
 * @since 7.2
 */
#[ArrayShape([
    'ai_flags' => 'int',
    'ai_family' => 'int',
    'ai_socktype' => 'int',
    'ai_protocol' => 'int',
    'ai_canonname' => 'string',
    'ai_addr' => [
        'sin_port' => 'int',
        'sin_addr' => 'string',
        'sin6_port' => 'int',
        'sin6_addr' => 'string',
    ]
])]
function socket_addrinfo_explain(AddressInfo $address): array {}

/**
 * Runs the select() system call on the given arrays of sockets with a specified timeout
 * @link https://php.net/manual/en/function.socket-select.php
 * @param array|null &$read <p>
 * The sockets listed in the <i>read</i> array will be
 * watched to see if characters become available for reading (more
 * precisely, to see if a read will not block - in particular, a socket
 * resource is also ready on end-of-file, in which case a
 * <b>socket_read</b> will return a zero length string).
 * </p>
 * @param array|null &$write <p>
 * The sockets listed in the <i>write</i> array will be
 * watched to see if a write will not block.
 * </p>
 * @param array|null &$except <p>
 * The sockets listed in the <i>except</i> array will be
 * watched for exceptions.
 * </p>
 * @param int|null $seconds <p>
 * The <i>tv_sec</i> and <i>tv_usec</i>
 * together form the timeout parameter. The
 * timeout is an upper bound on the amount of time
 * elapsed before <b>socket_select</b> return.
 * <i>tv_sec</i> may be zero , causing
 * <b>socket_select</b> to return immediately. This is useful
 * for polling. If <i>tv_sec</i> is <b>NULL</b> (no timeout),
 * <b>socket_select</b> can block indefinitely.
 * </p>
 * @param int $microseconds [optional]
 * @return int|false On success <b>socket_select</b> returns the number of
 * socket resources contained in the modified arrays, which may be zero if
 * the timeout expires before anything interesting happens. On error <b>FALSE</b>
 * is returned. The error code can be retrieved with
 * <b>socket_last_error</b>.
 * </p>
 * <p>
 * Be sure to use the === operator when checking for an
 * error. Since the <b>socket_select</b> may return 0 the
 * comparison with == would evaluate to <b>TRUE</b>:
 * Understanding <b>socket_select</b>'s result
 * <code>
 * $e = NULL;
 * if (false === socket_select($r, $w, $e, 0)) {
 * echo "socket_select() failed, reason: " .
 * socket_strerror(socket_last_error()) . "\n";
 * }
 * </code>
 */
function socket_select(?array &$read, ?array &$write, ?array &$except, ?int $seconds, int $microseconds = 0): int|false {}

/**
 * Create a socket (endpoint for communication)
 * @link https://php.net/manual/en/function.socket-create.php
 * @param int $domain <p>
 * The <i>domain</i> parameter specifies the protocol
 * family to be used by the socket.
 * </p>
 * <table>
 * Available address/protocol families
 * <tr valign="top">
 * <td>Domain</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td><b>AF_INET</b></td>
 * <td>
 * IPv4 Internet based protocols. TCP and UDP are common protocols of
 * this protocol family.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>AF_INET6</b></td>
 * <td>
 * IPv6 Internet based protocols. TCP and UDP are common protocols of
 * this protocol family.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>AF_UNIX</b></td>
 * <td>
 * Local communication protocol family. High efficiency and low
 * overhead make it a great form of IPC (Interprocess Communication).
 * </td>
 * </tr>
 * </table>
 * @param int $type <p>
 * The <i>type</i> parameter selects the type of communication
 * to be used by the socket.
 * </p>
 * <table>
 * Available socket types
 * <tr valign="top">
 * <td>Type</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td><b>SOCK_STREAM</b></td>
 * <td>
 * Provides sequenced, reliable, full-duplex, connection-based byte streams.
 * An out-of-band data transmission mechanism may be supported.
 * The TCP protocol is based on this socket type.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>SOCK_DGRAM</b></td>
 * <td>
 * Supports datagrams (connectionless, unreliable messages of a fixed maximum length).
 * The UDP protocol is based on this socket type.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>SOCK_SEQPACKET</b></td>
 * <td>
 * Provides a sequenced, reliable, two-way connection-based data transmission path for
 * datagrams of fixed maximum length; a consumer is required to read an
 * entire packet with each read call.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>SOCK_RAW</b></td>
 * <td>
 * Provides raw network protocol access. This special type of socket
 * can be used to manually construct any type of protocol. A common use
 * for this socket type is to perform ICMP requests (like ping).
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>SOCK_RDM</b></td>
 * <td>
 * Provides a reliable datagram layer that does not guarantee ordering.
 * This is most likely not implemented on your operating system.
 * </td>
 * </tr>
 * </table>
 * @param int $protocol <p>
 * The <i>protocol</i> parameter sets the specific
 * protocol within the specified <i>domain</i> to be used
 * when communicating on the returned socket. The proper value can be
 * retrieved by name by using <b>getprotobyname</b>. If
 * the desired protocol is TCP, or UDP the corresponding constants
 * <b>SOL_TCP</b>, and <b>SOL_UDP</b>
 * can also be used.
 * </p>
 * <table>
 * Common protocols
 * <tr valign="top">
 * <td>Name</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>icmp</td>
 * <td>
 * The Internet Control Message Protocol is used primarily by gateways
 * and hosts to report errors in datagram communication. The "ping"
 * command (present in most modern operating systems) is an example
 * application of ICMP.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>udp</td>
 * <td>
 * The User Datagram Protocol is a connectionless, unreliable,
 * protocol with fixed record lengths. Due to these aspects, UDP
 * requires a minimum amount of protocol overhead.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>tcp</td>
 * <td>
 * The Transmission Control Protocol is a reliable, connection based,
 * stream oriented, full duplex protocol. TCP guarantees that all data packets
 * will be received in the order in which they were sent. If any packet is somehow
 * lost during communication, TCP will automatically retransmit the packet until
 * the destination host acknowledges that packet. For reliability and performance
 * reasons, the TCP implementation itself decides the appropriate octet boundaries
 * of the underlying datagram communication layer. Therefore, TCP applications must
 * allow for the possibility of partial record transmission.
 * </td>
 * </tr>
 * </table>
 * @return resource|Socket|false <b>socket_create</b> returns a socket resource on success,
 * or <b>FALSE</b> on error. The actual error code can be retrieved by calling
 * <b>socket_last_error</b>. This error code may be passed to
 * <b>socket_strerror</b> to get a textual explanation of the
 * error.
 */
function socket_create(int $domain, int $type, int $protocol): Socket|false {}

/**
 * @param resource|Socket $socket
 * @return resource|Socket|false
 */
function socket_export_stream(Socket $socket) {}

/**
 * Opens a socket on port to accept connections
 * @link https://php.net/manual/en/function.socket-create-listen.php
 * @param int $port <p>
 * The port on which to listen on all interfaces.
 * </p>
 * @param int $backlog [optional] <p>
 * The <i>backlog</i> parameter defines the maximum length
 * the queue of pending connections may grow to.
 * <b>SOMAXCONN</b> may be passed as
 * <i>backlog</i> parameter, see
 * <b>socket_listen</b> for more information.
 * </p>
 * @return resource|Socket|false <b>socket_create_listen</b> returns a new socket resource
 * on success or <b>FALSE</b> on error. The error code can be retrieved with
 * <b>socket_last_error</b>. This code may be passed to
 * <b>socket_strerror</b> to get a textual explanation of the
 * error.
 */
function socket_create_listen(int $port, int $backlog = 128): Socket|false {}

/**
 * Creates a pair of indistinguishable sockets and stores them in an array
 * @link https://php.net/manual/en/function.socket-create-pair.php
 * @param int $domain <p>
 * The <i>domain</i> parameter specifies the protocol
 * family to be used by the socket. See <b>socket_create</b>
 * for the full list.
 * </p>
 * @param int $type <p>
 * The <i>type</i> parameter selects the type of communication
 * to be used by the socket. See <b>socket_create</b> for the
 * full list.
 * </p>
 * @param int $protocol <p>
 * The <i>protocol</i> parameter sets the specific
 * protocol within the specified <i>domain</i> to be used
 * when communicating on the returned socket. The proper value can be retrieved by
 * name by using <b>getprotobyname</b>. If
 * the desired protocol is TCP, or UDP the corresponding constants
 * <b>SOL_TCP</b>, and <b>SOL_UDP</b>
 * can also be used.
 * </p>
 * <p>
 * See <b>socket_create</b> for the full list of supported
 * protocols.
 * </p>
 * @param array &$pair <p>
 * Reference to an array in which the two socket resources will be inserted.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function socket_create_pair(int $domain, int $type, int $protocol, &$pair): bool {}

/**
 * Accepts a connection on a socket
 * @link https://php.net/manual/en/function.socket-accept.php
 * @param resource|Socket $socket <p>
 * A valid socket resource created with <b>socket_create</b>.
 * </p>
 * @return resource|Socket|false a new socket resource on success, or <b>FALSE</b> on error. The actual
 * error code can be retrieved by calling
 * <b>socket_last_error</b>. This error code may be passed to
 * <b>socket_strerror</b> to get a textual explanation of the
 * error.
 */
function socket_accept(Socket $socket): Socket|false {}

/**
 * Sets nonblocking mode for file descriptor fd
 * @link https://php.net/manual/en/function.socket-set-nonblock.php
 * @param resource|Socket $socket <p>
 * A valid socket resource created with <b>socket_create</b>
 * or <b>socket_accept</b>.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function socket_set_nonblock(Socket $socket): bool {}

/**
 * Sets blocking mode on a socket resource
 * @link https://php.net/manual/en/function.socket-set-block.php
 * @param resource|Socket $socket <p>
 * A valid socket resource created with <b>socket_create</b>
 * or <b>socket_accept</b>.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function socket_set_block(Socket $socket): bool {}

/**
 * Listens for a connection on a socket
 * @link https://php.net/manual/en/function.socket-listen.php
 * @param resource|Socket $socket <p>
 * A valid socket resource created with <b>socket_create</b>.
 * </p>
 * @param int $backlog [optional] <p>
 * A maximum of <i>backlog</i> incoming connections will be
 * queued for processing. If a connection request arrives with the queue
 * full the client may receive an error with an indication of
 * ECONNREFUSED, or, if the underlying protocol supports
 * retransmission, the request may be ignored so that retries may succeed.
 * </p>
 * <p>
 * The maximum number passed to the <i>backlog</i>
 * parameter highly depends on the underlying platform. On Linux, it is
 * silently truncated to <b>SOMAXCONN</b>. On win32, if
 * passed <b>SOMAXCONN</b>, the underlying service provider
 * responsible for the socket will set the backlog to a maximum
 * reasonable value. There is no standard provision to
 * find out the actual backlog value on this platform.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure. The error code can be retrieved with
 * <b>socket_last_error</b>. This code may be passed to
 * <b>socket_strerror</b> to get a textual explanation of the
 * error.
 */
function socket_listen(Socket $socket, int $backlog = 0): bool {}

/**
 * Closes a socket resource
 * @link https://php.net/manual/en/function.socket-close.php
 * @param resource|Socket $socket <p>
 * A valid socket resource created with <b>socket_create</b>
 * or <b>socket_accept</b>.
 * </p>
 * @return void No value is returned.
 */
function socket_close(Socket $socket): void {}

/**
 * Write to a socket
 * @link https://php.net/manual/en/function.socket-write.php
 * @param resource|Socket $socket
 * @param string $data <p>
 * The buffer to be written.
 * </p>
 * @param int|null $length <p>
 * The optional parameter <i>length</i> can specify an
 * alternate length of bytes written to the socket. If this length is
 * greater than the buffer length, it is silently truncated to the length
 * of the buffer.
 * </p>
 * @return int|false the number of bytes successfully written to the socket or <b>FALSE</b> on failure.
 * The error code can be retrieved with
 * <b>socket_last_error</b>. This code may be passed to
 * <b>socket_strerror</b> to get a textual explanation of the
 * error.
 * </p>
 * <p>
 * It is perfectly valid for <b>socket_write</b> to
 * return zero which means no bytes have been written. Be sure to use the
 * === operator to check for <b>FALSE</b> in case of an
 * error.
 */
function socket_write(Socket $socket, string $data, ?int $length = null): int|false {}

/**
 * Reads a maximum of length bytes from a socket
 * @link https://php.net/manual/en/function.socket-read.php
 * @param resource|Socket $socket <p>
 * A valid socket resource created with <b>socket_create</b>
 * or <b>socket_accept</b>.
 * </p>
 * @param int $length <p>
 * The maximum number of bytes read is specified by the
 * <i>length</i> parameter. Otherwise you can use
 * <b>&#92;r</b>, <b>&#92;n</b>,
 * or <b>&#92;0</b> to end reading (depending on the <i>type</i>
 * parameter, see below).
 * </p>
 * @param int $mode [optional] <p>
 * Optional <i>type</i> parameter is a named constant:
 * <b>PHP_BINARY_READ</b> (Default) - use the system
 * recv() function. Safe for reading binary data.
 * @return string|false <b>socket_read</b> returns the data as a string on success,
 * or <b>FALSE</b> on error (including if the remote host has closed the
 * connection). The error code can be retrieved with
 * <b>socket_last_error</b>. This code may be passed to
 * <b>socket_strerror</b> to get a textual representation of
 * the error.
 * </p>
 * <p>
 * <b>socket_read</b> returns a zero length string ("")
 * when there is no more data to read.</p>
 */
function socket_read(Socket $socket, int $length, int $mode = PHP_BINARY_READ): string|false {}

/**
 * Queries the local side of the given socket which may either result in host/port or in a Unix filesystem path, dependent on its type
 * @link https://php.net/manual/en/function.socket-getsockname.php
 * @param resource|Socket $socket <p>
 * A valid socket resource created with <b>socket_create</b>
 * or <b>socket_accept</b>.
 * </p>
 * @param string &$address <p>
 * If the given socket is of type <b>AF_INET</b>
 * or <b>AF_INET6</b>, <b>socket_getsockname</b>
 * will return the local IP address in appropriate notation (e.g.
 * 127.0.0.1 or fe80::1) in the
 * <i>address</i> parameter and, if the optional
 * <i>port</i> parameter is present, also the associated port.
 * </p>
 * <p>
 * If the given socket is of type <b>AF_UNIX</b>,
 * <b>socket_getsockname</b> will return the Unix filesystem
 * path (e.g. /var/run/daemon.sock) in the
 * <i>address</i> parameter.
 * </p>
 * @param int &$port [optional] <p>
 * If provided, this will hold the associated port.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure. <b>socket_getsockname</b> may also return
 * <b>FALSE</b> if the socket type is not any of <b>AF_INET</b>,
 * <b>AF_INET6</b>, or <b>AF_UNIX</b>, in which
 * case the last socket error code is not updated.
 */
function socket_getsockname(Socket $socket, &$address, &$port = null): bool {}

/**
 * Queries the remote side of the given socket which may either result in host/port or in a Unix filesystem path, dependent on its type
 * @link https://php.net/manual/en/function.socket-getpeername.php
 * @param resource|Socket $socket <p>
 * A valid socket resource created with <b>socket_create</b>
 * or <b>socket_accept</b>.
 * </p>
 * @param string &$address <p>
 * If the given socket is of type <b>AF_INET</b> or
 * <b>AF_INET6</b>, <b>socket_getpeername</b>
 * will return the peers (remote) IP address in
 * appropriate notation (e.g. 127.0.0.1 or
 * fe80::1) in the <i>address</i>
 * parameter and, if the optional <i>port</i> parameter is
 * present, also the associated port.
 * </p>
 * <p>
 * If the given socket is of type <b>AF_UNIX</b>,
 * <b>socket_getpeername</b> will return the Unix filesystem
 * path (e.g. /var/run/daemon.sock) in the
 * <i>address</i> parameter.
 * </p>
 * @param int &$port [optional] <p>
 * If given, this will hold the port associated to
 * <i>address</i>.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure. <b>socket_getpeername</b> may also return
 * <b>FALSE</b> if the socket type is not any of <b>AF_INET</b>,
 * <b>AF_INET6</b>, or <b>AF_UNIX</b>, in which
 * case the last socket error code is not updated.
 */
function socket_getpeername(Socket $socket, &$address, &$port = null): bool {}

/**
 * Initiates a connection on a socket
 * @link https://php.net/manual/en/function.socket-connect.php
 * @param resource|Socket $socket
 * @param string $address <p>
 * The <i>address</i> parameter is either an IPv4 address
 * in dotted-quad notation (e.g. 127.0.0.1) if
 * <i>socket</i> is <b>AF_INET</b>, a valid
 * IPv6 address (e.g. ::1) if IPv6 support is enabled and
 * <i>socket</i> is <b>AF_INET6</b>
 * or the pathname of a Unix domain socket, if the socket family is
 * <b>AF_UNIX</b>.
 * </p>
 * @param int|null $port <p>
 * The <i>port</i> parameter is only used and is mandatory
 * when connecting to an <b>AF_INET</b> or an
 * <b>AF_INET6</b> socket, and designates
 * the port on the remote host to which a connection should be made.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure. The error code can be retrieved with
 * <b>socket_last_error</b>. This code may be passed to
 * <b>socket_strerror</b> to get a textual explanation of the
 * error.
 * </p>
 * <p>
 * If the socket is non-blocking then this function returns <b>FALSE</b> with an
 * error Operation now in progress.
 */
function socket_connect(Socket $socket, string $address, ?int $port = null): bool {}

/**
 * Return a string describing a socket error
 * @link https://php.net/manual/en/function.socket-strerror.php
 * @param int $error_code <p>
 * A valid socket error number, likely produced by
 * <b>socket_last_error</b>.
 * </p>
 * @return string the error message associated with the <i>errno</i>
 * parameter.
 */
function socket_strerror(int $error_code): string {}

/**
 * Binds a name to a socket
 * @link https://php.net/manual/en/function.socket-bind.php
 * @param resource|Socket $socket <p>
 * A valid socket resource created with <b>socket_create</b>.
 * </p>
 * @param string $address <p>
 * If the socket is of the <b>AF_INET</b> family, the
 * <i>address</i> is an IP in dotted-quad notation
 * (e.g. 127.0.0.1).
 * </p>
 * <p>
 * If the socket is of the <b>AF_UNIX</b> family, the
 * <i>address</i> is the path of a
 * Unix-domain socket (e.g. /tmp/my.sock).
 * </p>
 * @param int $port [optional] <p>
 * The <i>port</i> parameter is only used when
 * binding an <b>AF_INET</b> socket, and designates
 * the port on which to listen for connections.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 * <p>
 * The error code can be retrieved with <b>socket_last_error</b>.
 * This code may be passed to <b>socket_strerror</b> to get a
 * textual explanation of the error.
 * </p>
 */
function socket_bind(Socket $socket, string $address, int $port = 0): bool {}

/**
 * Receives data from a connected socket
 * @link https://php.net/manual/en/function.socket-recv.php
 * @param resource|Socket $socket <p>
 * The <i>socket</i> must be a socket resource previously
 * created by socket_create().
 * </p>
 * @param string &$data <p>
 * The data received will be fetched to the variable specified with
 * <i>buf</i>. If an error occurs, if the
 * connection is reset, or if no data is
 * available, <i>buf</i> will be set to <b>NULL</b>.
 * </p>
 * @param int $length <p>
 * Up to <i>len</i> bytes will be fetched from remote host.
 * </p>
 * @param int $flags <p>
 * The value of <i>flags</i> can be any combination of
 * the following flags, joined with the binary OR (|)
 * operator.
 * </p>
 * <table>
 * Possible values for <i>flags</i>
 * <tr valign="top">
 * <td>Flag</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td><b>MSG_OOB</b></td>
 * <td>
 * Process out-of-band data.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>MSG_PEEK</b></td>
 * <td>
 * Receive data from the beginning of the receive queue without
 * removing it from the queue.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>MSG_WAITALL</b></td>
 * <td>
 * Block until at least <i>len</i> are received.
 * However, if a signal is caught or the remote host disconnects, the
 * function may return less data.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>MSG_DONTWAIT</b></td>
 * <td>
 * With this flag set, the function returns even if it would normally
 * have blocked.
 * </td>
 * </tr>
 * </table>
 * @return int|false <b>socket_recv</b> returns the number of bytes received,
 * or <b>FALSE</b> if there was an error. The actual error code can be retrieved by
 * calling <b>socket_last_error</b>. This error code may be
 * passed to <b>socket_strerror</b> to get a textual explanation
 * of the error.
 */
function socket_recv(Socket $socket, &$data, int $length, int $flags): int|false {}

/**
 * Sends data to a connected socket
 * @link https://php.net/manual/en/function.socket-send.php
 * @param resource|Socket $socket <p>
 * A valid socket resource created with <b>socket_create</b>
 * or <b>socket_accept</b>.
 * </p>
 * @param string $data <p>
 * A buffer containing the data that will be sent to the remote host.
 * </p>
 * @param int $length <p>
 * The number of bytes that will be sent to the remote host from
 * <i>buf</i>.
 * </p>
 * @param int $flags <p>
 * The value of <i>flags</i> can be any combination of
 * the following flags, joined with the binary OR (|)
 * operator.
 * <table>
 * Possible values for <i>flags</i>
 * <tr valign="top">
 * <td><b>MSG_OOB</b></td>
 * <td>
 * Send OOB (out-of-band) data.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>MSG_EOR</b></td>
 * <td>
 * Indicate a record mark. The sent data completes the record.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>MSG_EOF</b></td>
 * <td>
 * Close the sender side of the socket and include an appropriate
 * notification of this at the end of the sent data. The sent data
 * completes the transaction.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>MSG_DONTROUTE</b></td>
 * <td>
 * Bypass routing, use direct interface.
 * </td>
 * </tr>
 * </table>
 * </p>
 * @return int|false <b>socket_send</b> returns the number of bytes sent, or <b>FALSE</b> on error.
 */
function socket_send(Socket $socket, string $data, int $length, int $flags): int|false {}

/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Send a message
 * @link https://secure.php.net/manual/en/function.socket-sendmsg.php
 * @param resource|Socket $socket
 * @param array $message
 * @param int $flags
 * @return int|false
 * @since 5.5
 */
function socket_sendmsg(
    Socket $socket,
    array $message,
    #[PhpStormStubsElementAvailable(from: '5.5', to: '7.4')] int $flags,
    #[PhpStormStubsElementAvailable(from: '8.0')] int $flags = 0
): int|false {}

/**
 * Receives data from a socket whether or not it is connection-oriented
 * @link https://php.net/manual/en/function.socket-recvfrom.php
 * @param resource|Socket $socket <p>
 * The <i>socket</i> must be a socket resource previously
 * created by socket_create().
 * </p>
 * @param string &$data <p>
 * The data received will be fetched to the variable specified with
 * <i>buf</i>.
 * </p>
 * @param int $length <p>
 * Up to <i>len</i> bytes will be fetched from remote host.
 * </p>
 * @param int $flags <p>
 * The value of <i>flags</i> can be any combination of
 * the following flags, joined with the binary OR (|)
 * operator.
 * </p>
 * <table>
 * Possible values for <i>flags</i>
 * <tr valign="top">
 * <td>Flag</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td><b>MSG_OOB</b></td>
 * <td>
 * Process out-of-band data.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>MSG_PEEK</b></td>
 * <td>
 * Receive data from the beginning of the receive queue without
 * removing it from the queue.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>MSG_WAITALL</b></td>
 * <td>
 * Block until at least <i>len</i> are received.
 * However, if a signal is caught or the remote host disconnects, the
 * function may return less data.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>MSG_DONTWAIT</b></td>
 * <td>
 * With this flag set, the function returns even if it would normally
 * have blocked.
 * </td>
 * </tr>
 * </table>
 * @param string &$address <p>
 * If the socket is of the type <b>AF_UNIX</b> type,
 * <i>name</i> is the path to the file. Else, for
 * unconnected sockets, <i>name</i> is the IP address of,
 * the remote host, or <b>NULL</b> if the socket is connection-oriented.
 * </p>
 * @param int &$port [optional] <p>
 * This argument only applies to <b>AF_INET</b> and
 * <b>AF_INET6</b> sockets, and specifies the remote port
 * from which the data is received. If the socket is connection-oriented,
 * <i>port</i> will be <b>NULL</b>.
 * </p>
 * @return int|false <b>socket_recvfrom</b> returns the number of bytes received,
 * or <b>FALSE</b> if there was an error. The actual error code can be retrieved by
 * calling <b>socket_last_error</b>. This error code may be
 * passed to <b>socket_strerror</b> to get a textual explanation
 * of the error.
 */
function socket_recvfrom(Socket $socket, &$data, int $length, int $flags, &$address, &$port = null): int|false {}

/**
 * Read a message
 * @link https://secure.php.net/manual/en/function.socket-recvmsg.php
 * @param resource|Socket $socket
 * @param array &$message
 * @param int $flags
 * @return int|false
 * @since 5.5
 */
function socket_recvmsg(
    Socket $socket,
    array &$message,
    #[PhpStormStubsElementAvailable(from: '5.5', to: '7.4')] int $flags,
    #[PhpStormStubsElementAvailable(from: '8.0')] int $flags = 0
): int|false {}

/**
 * Sends a message to a socket, whether it is connected or not
 * @link https://php.net/manual/en/function.socket-sendto.php
 * @param resource|Socket $socket <p>
 * A valid socket resource created using <b>socket_create</b>.
 * </p>
 * @param string $data <p>
 * The sent data will be taken from buffer <i>buf</i>.
 * </p>
 * @param int $length <p>
 * <i>len</i> bytes from <i>buf</i> will be
 * sent.
 * </p>
 * @param int $flags <p>
 * The value of <i>flags</i> can be any combination of
 * the following flags, joined with the binary OR (|)
 * operator.
 * <table>
 * Possible values for <i>flags</i>
 * <tr valign="top">
 * <td><b>MSG_OOB</b></td>
 * <td>
 * Send OOB (out-of-band) data.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>MSG_EOR</b></td>
 * <td>
 * Indicate a record mark. The sent data completes the record.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>MSG_EOF</b></td>
 * <td>
 * Close the sender side of the socket and include an appropriate
 * notification of this at the end of the sent data. The sent data
 * completes the transaction.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>MSG_DONTROUTE</b></td>
 * <td>
 * Bypass routing, use direct interface.
 * </td>
 * </tr>
 * </table>
 * </p>
 * @param string $address <p>
 * IP address of the remote host.
 * </p>
 * @param int|null $port <p>
 * <i>port</i> is the remote port number at which the data
 * will be sent.
 * </p>
 * @return int|false <b>socket_sendto</b> returns the number of bytes sent to the
 * remote host, or <b>FALSE</b> if an error occurred.
 */
function socket_sendto(Socket $socket, string $data, int $length, int $flags, string $address, ?int $port = null): int|false {}

/**
 * Gets socket options for the socket
 * @link https://php.net/manual/en/function.socket-get-option.php
 * @param resource|Socket $socket <p>
 * A valid socket resource created with <b>socket_create</b>
 * or <b>socket_accept</b>.
 * </p>
 * @param int $level <p>
 * The <i>level</i> parameter specifies the protocol
 * level at which the option resides. For example, to retrieve options at
 * the socket level, a <i>level</i> parameter of
 * <b>SOL_SOCKET</b> would be used. Other levels, such as
 * <b>TCP</b>, can be used by
 * specifying the protocol number of that level. Protocol numbers can be
 * found by using the <b>getprotobyname</b> function.
 * </p>
 * @param int $option <table>
 * Available Socket Options
 * <tr valign="top">
 * <td>Option</td>
 * <td>Description</td>
 * <td>Type</td>
 * </tr>
 * <tr valign="top">
 * <td><b>SO_DEBUG</b></td>
 * <td>
 * Reports whether debugging information is being recorded.
 * </td>
 * <td>
 * int
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>SO_BROADCAST</b></td>
 * <td>
 * Reports whether transmission of broadcast messages is supported.
 * </td>
 * <td>
 * int
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>SO_REUSEADDR</b></td>
 * <td>
 * Reports whether local addresses can be reused.
 * </td>
 * <td>
 * int
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>SO_KEEPALIVE</b></td>
 * <td>
 * Reports whether connections are kept active with periodic transmission
 * of messages. If the connected socket fails to respond to these messages,
 * the connection is broken and processes writing to that socket are notified
 * with a SIGPIPE signal.
 * </td>
 * <td>
 * int
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>SO_LINGER</b></td>
 * <td>
 * <p>
 * Reports whether the <i>socket</i> lingers on
 * <b>socket_close</b> if data is present. By default,
 * when the socket is closed, it attempts to send all unsent data.
 * In the case of a connection-oriented socket,
 * <b>socket_close</b> will wait for its peer to
 * acknowledge the data.
 * </p>
 * <p>
 * If l_onoff is non-zero and
 * l_linger is zero, all the
 * unsent data will be discarded and RST (reset) is sent to the
 * peer in the case of a connection-oriented socket.
 * </p>
 * <p>
 * On the other hand, if l_onoff is
 * non-zero and l_linger is non-zero,
 * <b>socket_close</b> will block until all the data
 * is sent or the time specified in l_linger
 * elapses. If the socket is non-blocking,
 * <b>socket_close</b> will fail and return an error.
 * </p>
 * </td>
 * <td>
 * array. The array will contain two keys:
 * l_onoff and
 * l_linger.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>SO_OOBINLINE</b></td>
 * <td>
 * Reports whether the <i>socket</i> leaves out-of-band data inline.
 * </td>
 * <td>
 * int
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>SO_SNDBUF</b></td>
 * <td>
 * Reports the size of the send buffer.
 * </td>
 * <td>
 * int
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>SO_RCVBUF</b></td>
 * <td>
 * Reports the size of the receive buffer.
 * </td>
 * <td>
 * int
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>SO_ERROR</b></td>
 * <td>
 * Reports information about error status and clears it.
 * </td>
 * <td>
 * int (cannot be set by <b>socket_set_option</b>)
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>SO_TYPE</b></td>
 * <td>
 * Reports the <i>socket</i> type (e.g.
 * <b>SOCK_STREAM</b>).
 * </td>
 * <td>
 * int (cannot be set by <b>socket_set_option</b>)
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>SO_DONTROUTE</b></td>
 * <td>
 * Reports whether outgoing messages bypass the standard routing facilities.
 * </td>
 * <td>
 * int
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>SO_RCVLOWAT</b></td>
 * <td>
 * Reports the minimum number of bytes to process for <i>socket</i>
 * input operations.
 * </td>
 * <td>
 * int
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>SO_RCVTIMEO</b></td>
 * <td>
 * Reports the timeout value for input operations.
 * </td>
 * <td>
 * array. The array will contain two keys:
 * sec which is the seconds part on the timeout
 * value and usec which is the microsecond part
 * of the timeout value.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>SO_SNDTIMEO</b></td>
 * <td>
 * Reports the timeout value specifying the amount of time that an output
 * function blocks because flow control prevents data from being sent.
 * </td>
 * <td>
 * array. The array will contain two keys:
 * sec which is the seconds part on the timeout
 * value and usec which is the microsecond part
 * of the timeout value.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>SO_SNDLOWAT</b></td>
 * <td>
 * Reports the minimum number of bytes to process for <i>socket</i> output operations.
 * </td>
 * <td>
 * int
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>TCP_NODELAY</b></td>
 * <td>
 * Reports whether the Nagle TCP algorithm is disabled.
 * </td>
 * <td>
 * int
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>MCAST_JOIN_GROUP</b></td>
 * <td>
 * Joins a multicast group. (added in PHP 5.4)
 * </td>
 * <td>
 * array with keys "group", specifying
 * a string with an IPv4 or IPv6 multicast address and
 * "interface", specifying either an interface
 * number (type int) or a string with
 * the interface name, like "eth0".
 * 0 can be specified to indicate the interface
 * should be selected using routing rules. (can only be used in
 * <b>socket_set_option</b>)
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>MCAST_LEAVE_GROUP</b></td>
 * <td>
 * Leaves a multicast group. (added in PHP 5.4)
 * </td>
 * <td>
 * array. See <b>MCAST_JOIN_GROUP</b> for
 * more information. (can only be used in
 * <b>socket_set_option</b>)
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>MCAST_BLOCK_SOURCE</b></td>
 * <td>
 * Blocks packets arriving from a specific source to a specific
 * multicast group, which must have been previously joined.
 * (added in PHP 5.4)
 * </td>
 * <td>
 * array with the same keys as
 * <b>MCAST_JOIN_GROUP</b>, plus one extra key,
 * source, which maps to a string
 * specifying an IPv4 or IPv6 address of the source to be blocked.
 * (can only be used in <b>socket_set_option</b>)
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>MCAST_UNBLOCK_SOURCE</b></td>
 * <td>
 * Unblocks (start receiving again) packets arriving from a specific
 * source address to a specific multicast group, which must have been
 * previously joined. (added in PHP 5.4)
 * </td>
 * <td>
 * array with the same format as
 * <b>MCAST_BLOCK_SOURCE</b>.
 * (can only be used in <b>socket_set_option</b>)
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>MCAST_JOIN_SOURCE_GROUP</b></td>
 * <td>
 * Receive packets destined to a specific multicast group whose source
 * address matches a specific value. (added in PHP 5.4)
 * </td>
 * <td>
 * array with the same format as
 * <b>MCAST_BLOCK_SOURCE</b>.
 * (can only be used in <b>socket_set_option</b>)
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>MCAST_LEAVE_SOURCE_GROUP</b></td>
 * <td>
 * Stop receiving packets destined to a specific multicast group whose
 * soure address matches a specific value. (added in PHP 5.4)
 * </td>
 * <td>
 * array with the same format as
 * <b>MCAST_BLOCK_SOURCE</b>.
 * (can only be used in <b>socket_set_option</b>)
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>IP_MULTICAST_IF</b></td>
 * <td>
 * The outgoing interface for IPv4 multicast packets.
 * (added in PHP 5.4)
 * </td>
 * <td>
 * Either int specifying the interface number or a
 * string with an interface name, like
 * eth0. The value 0 can be used to
 * indicate the routing table is to used in the interface selection.
 * The function <b>socket_get_option</b> returns an
 * interface index.
 * Note that, unlike the C API, this option does NOT take an IP
 * address. This eliminates the interface difference between
 * <b>IP_MULTICAST_IF</b> and
 * <b>IPV6_MULTICAST_IF</b>.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>IPV6_MULTICAST_IF</b></td>
 * <td>
 * The outgoing interface for IPv6 multicast packets.
 * (added in PHP 5.4)
 * </td>
 * <td>
 * The same as <b>IP_MULTICAST_IF</b>.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>IP_MULTICAST_LOOP</b></td>
 * <td>
 * The multicast loopback policy for IPv4 packets, which
 * determines whether multicast packets sent by this socket also reach
 * receivers in the same host that have joined the same multicast group
 * on the outgoing interface used by this socket. This is the case by
 * default.
 * (added in PHP 5.4)
 * </td>
 * <td>
 * int (either 0 or
 * 1). For <b>socket_set_option</b>
 * any value will be accepted and will be converted to a boolean
 * following the usual PHP rules.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>IPV6_MULTICAST_LOOP</b></td>
 * <td>
 * Analogous to <b>IP_MULTICAST_LOOP</b>, but for IPv6.
 * (added in PHP 5.4)
 * </td>
 * <td>
 * int. See <b>IP_MULTICAST_LOOP</b>.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>IP_MULTICAST_TTL</b></td>
 * <td>
 * The time-to-live of outgoing IPv4 multicast packets. This should be
 * a value between 0 (don't leave the interface) and 255. The default
 * value is 1 (only the local network is reached).
 * (added in PHP 5.4)
 * </td>
 * <td>
 * int between 0 and 255.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>IPV6_MULTICAST_HOPS</b></td>
 * <td>
 * Analogous to <b>IP_MULTICAST_TTL</b>, but for IPv6
 * packets. The value -1 is also accepted, meaning the route default
 * should be used.
 * (added in PHP 5.4)
 * </td>
 * <td>
 * int between -1 and 255.
 * </td>
 * </tr>
 * </table>
 * @return array|int|false the value of the given option, or <b>FALSE</b> on errors.
 */
function socket_get_option(Socket $socket, int $level, int $option): array|int|false {}

/**
 * Sets socket options for the socket
 * @link https://php.net/manual/en/function.socket-set-option.php
 * @param resource|Socket $socket <p>
 * A valid socket resource created with <b>socket_create</b>
 * or <b>socket_accept</b>.
 * </p>
 * @param int $level <p>
 * The <i>level</i> parameter specifies the protocol
 * level at which the option resides. For example, to retrieve options at
 * the socket level, a <i>level</i> parameter of
 * <b>SOL_SOCKET</b> would be used. Other levels, such as
 * TCP, can be used by specifying the protocol number of that level.
 * Protocol numbers can be found by using the
 * <b>getprotobyname</b> function.
 * </p>
 * @param int $option <p>
 * The available socket options are the same as those for the
 * <b>socket_get_option</b> function.
 * </p>
 * @param mixed $value <p>
 * The option value.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function socket_set_option(Socket $socket, int $level, int $option, $value): bool {}

/**
 * Shuts down a socket for receiving, sending, or both
 * @link https://php.net/manual/en/function.socket-shutdown.php
 * @param resource|Socket $socket <p>
 * A valid socket resource created with <b>socket_create</b>.
 * </p>
 * @param int $mode [optional] <p>
 * The value of <i>how</i> can be one of the following:
 * <table>
 * possible values for <i>how</i>
 * <tr valign="top">
 * <td>0</td>
 * <td>
 * Shutdown socket reading
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>1</td>
 * <td>
 * Shutdown socket writing
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>2</td>
 * <td>
 * Shutdown socket reading and writing
 * </td>
 * </tr>
 * </table>
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function socket_shutdown(Socket $socket, int $mode = 2): bool {}

/**
 * Returns the last error on the socket
 * @link https://php.net/manual/en/function.socket-last-error.php
 * @param resource|Socket $socket [optional] <p>
 * A valid socket resource created with <b>socket_create</b>.
 * </p>
 * @return int This function returns a socket error code.
 */
function socket_last_error(?Socket $socket = null): int {}

/**
 * Clears the error on the socket or the last error code
 * @link https://php.net/manual/en/function.socket-clear-error.php
 * @param resource|Socket|null $socket [optional] <p>
 * A valid socket resource created with <b>socket_create</b>.
 * </p>
 * @return void No value is returned.
 */
function socket_clear_error(?Socket $socket = null): void {}

/**
 * Import a stream
 * @link https://php.net/manual/en/function.socket-import-stream.php
 * @param resource|Socket $stream <p>
 * The stream resource to import.
 * </p>
 * @return resource|Socket|false|null <b>FALSE</b> or <b>NULL</b> on failure.
 * @since 5.4
 */
function socket_import_stream($stream): Socket|false {}

/**
 * Calculate message buffer size
 * @link https://php.net/manual/en/function.socket-cmsg-space.php
 * @param int $level
 * @param int $type
 * @param int $num [optional]
 * @return int|null
 * @since 5.5
 */
function socket_cmsg_space(
    int $level,
    int $type,
    #[PhpStormStubsElementAvailable(from: '8.0')] int $num = 0
): ?int {}

/**
 * Alias of {@see socket_get_option}
 * @param Socket $socket
 * @param int $level
 * @param int $option
 */
function socket_getopt(Socket $socket, int $level, int $option): array|int|false {}

/**
 * Alias of {@see socket_set_option}
 * @param Socket $socket
 * @param int $level
 * @param int $option
 * @param $value
 * @return bool
 */
function socket_setopt(Socket $socket, int $level, int $option, $value): bool {}

/**
 * Exports the WSAPROTOCOL_INFO Structure
 *
 * @link https://www.php.net/manual/en/function.socket-wsaprotocol-info-export.php
 *
 * @param resource|Socket $socket
 * @param int $target_pid
 * @return string|false
 *
 * @since 7.3
 */
function socket_wsaprotocol_info_export($socket, $target_pid) {}

/**
 * Imports a Socket from another Process
 *
 * @link https://www.php.net/manual/en/function.socket-wsaprotocol-info-import.php
 *
 * @param string $info_id
 * @return resource|Socket|false
 *
 * @since 7.3
 */
function socket_wsaprotocol_info_import($info_id) {}

/**
 * Releases an exported WSAPROTOCOL_INFO Structure
 *
 * @link https://www.php.net/manual/en/function.socket-wsaprotocol-info-release.php
 *
 * @param string $info_id
 * @return bool
 *
 * @since 7.3
 */
function socket_wsaprotocol_info_release($info_id) {}

define('AF_UNIX', 1);
define('AF_INET', 2);

/**
 * Only available if compiled with IPv6 support.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('AF_INET6', 10);
define('SOCK_STREAM', 1);
define('SOCK_DGRAM', 2);
define('SOCK_RAW', 3);
define('SOCK_SEQPACKET', 5);
define('SOCK_RDM', 4);
define('MSG_OOB', 1);
define('MSG_WAITALL', 256);
define('MSG_CTRUNC', 8);
define('MSG_TRUNC', 32);
define('MSG_PEEK', 2);
define('MSG_DONTROUTE', 4);

/**
 * Not available on Windows platforms.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('MSG_EOR', 128);

/**
 * Not available on Windows platforms.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('MSG_EOF', 512);
define('MSG_CONFIRM', 2048);
define('MSG_ERRQUEUE', 8192);
define('MSG_NOSIGNAL', 16384);
define('MSG_DONTWAIT', 64);
define('MSG_MORE', 32768);
define('MSG_WAITFORONE', 65536);
define('MSG_CMSG_CLOEXEC', 1073741824);
define('SO_DEBUG', 1);
define('SO_REUSEADDR', 2);

/**
 * This constant is only available in PHP 5.4.10 or later on platforms that
 * support the <b>SO_REUSEPORT</b> socket option: this
 * includes Mac OS X and FreeBSD, but does not include Linux or Windows.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SO_REUSEPORT', 15);
define('SO_KEEPALIVE', 9);
define('SO_DONTROUTE', 5);
define('SO_LINGER', 13);
define('SO_BROADCAST', 6);
define('SO_OOBINLINE', 10);
define('SO_SNDBUF', 7);
define('SO_RCVBUF', 8);
define('SO_SNDLOWAT', 19);
define('SO_RCVLOWAT', 18);
define('SO_SNDTIMEO', 21);
define('SO_RCVTIMEO', 20);
define('SO_TYPE', 3);
define('SO_ERROR', 4);
define('SO_BINDTODEVICE', 25);
define('SOL_SOCKET', 1);
define('SOMAXCONN', 128);
/**
 * @since 8.1
 */
define('SO_MARK', 36);
/**
 * Used to disable Nagle TCP algorithm.
 * Added in PHP 5.2.7.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('TCP_NODELAY', 1);
define('PHP_NORMAL_READ', 1);
define('PHP_BINARY_READ', 2);
/**
 * Joins a multicast group.
 * @since 5.4
 * @link https://php.net/manual/en/function.socket-get-option.php
 */
define('MCAST_JOIN_GROUP', 42);
/**
 * Leaves a multicast group.
 * @since 5.4
 * @link https://php.net/manual/en/function.socket-get-option.php
 */
define('MCAST_LEAVE_GROUP', 45);
/**
 * Blocks packets arriving from a specific source to a specific multicast group,
 * which must have been previously joined.
 * @since 5.4
 * @link https://php.net/manual/en/function.socket-get-option.php
 */
define('MCAST_BLOCK_SOURCE', 43);
/**
 * Unblocks (start receiving again) packets arriving from
 * a specific source address to a specific multicast group,
 * which must have been previously joined.
 * @since 5.4
 * @link https://php.net/manual/en/function.socket-get-option.php
 */
define('MCAST_UNBLOCK_SOURCE', 44);
/**
 * Receive packets destined to a specific multicast group
 * whose source address matches a specific value.
 * @since 5.4
 * @link https://php.net/manual/en/function.socket-get-option.php
 */
define('MCAST_JOIN_SOURCE_GROUP', 46);
/**
 * Stop receiving packets destined to a specific multicast group
 * whose soure address matches a specific value.
 * @since 5.4
 * @link https://php.net/manual/en/function.socket-get-option.php
 */
define('MCAST_LEAVE_SOURCE_GROUP', 47);
/**
 * The outgoing interface for IPv4 multicast packets.
 * @since 5.4
 * @link https://php.net/manual/en/function.socket-get-option.php
 */
define('IP_MULTICAST_IF', 32);
/**
 * The outgoing interface for IPv6 multicast packets.
 * @since 5.4
 * @link https://php.net/manual/en/function.socket-get-option.php
 */
define('IP_MULTICAST_TTL', 33);
/**
 * The multicast loopback policy for IPv4 packets,
 * which determines whether multicast packets sent by this socket
 * also reach receivers in the same host that have joined the same multicast group
 * on the outgoing interface used by this socket. This is the case by default.
 * @since 5.4
 * @link https://php.net/manual/en/function.socket-get-option.php
 */
define('IP_MULTICAST_LOOP', 34);
/**
 * Analogous to IP_MULTICAST_LOOP, but for IPv6.
 * @since 5.4
 * @link https://php.net/manual/en/function.socket-get-option.php
 */
define('IPV6_MULTICAST_IF', 17);
/**
 * The time-to-live of outgoing IPv4 multicast packets.
 * This should be a value between 0 (don't leave the interface) and 255.
 * The default value is 1 (only the local network is reached).
 * @since 5.4
 * @link https://php.net/manual/en/function.socket-get-option.php
 */
define('IPV6_MULTICAST_HOPS', 18);
/**
 * Analogous to IP_MULTICAST_TTL, but for IPv6 packets.
 * The value -1 is also accepted, meaning the route default should be used.
 * @since 5.4
 * @link https://php.net/manual/en/function.socket-get-option.php
 */
define('IPV6_MULTICAST_LOOP', 19);
define('IPV6_V6ONLY', 26);

/**
 * Operation not permitted.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EPERM', 1);

/**
 * No such file or directory.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENOENT', 2);

/**
 * Interrupted system call.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EINTR', 4);

/**
 * I/O error.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EIO', 5);

/**
 * No such device or address.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENXIO', 6);

/**
 * Arg list too long.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_E2BIG', 7);

/**
 * Bad file number.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EBADF', 9);

/**
 * Try again.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EAGAIN', 11);

/**
 * Out of memory.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENOMEM', 12);

/**
 * Permission denied.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EACCES', 13);

/**
 * Bad address.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EFAULT', 14);

/**
 * Block device required.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENOTBLK', 15);

/**
 * Device or resource busy.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EBUSY', 16);

/**
 * File exists.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EEXIST', 17);

/**
 * Cross-device link.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EXDEV', 18);

/**
 * No such device.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENODEV', 19);

/**
 * Not a directory.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENOTDIR', 20);

/**
 * Is a directory.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EISDIR', 21);

/**
 * Invalid argument.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EINVAL', 22);

/**
 * File table overflow.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENFILE', 24);

/**
 * Too many open files.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EMFILE', 24);

/**
 * Not a typewriter.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENOTTY', 25);

/**
 * No space left on device.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENOSPC', 28);

/**
 * Illegal seek.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ESPIPE', 29);

/**
 * Read-only file system.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EROFS', 30);

/**
 * Too many links.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EMLINK', 31);

/**
 * Broken pipe.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EPIPE', 32);

/**
 * File name too long.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENAMETOOLONG', 36);

/**
 * No record locks available.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENOLCK', 37);

/**
 * Function not implemented.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENOSYS', 38);

/**
 * Directory not empty.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENOTEMPTY', 39);

/**
 * Too many symbolic links encountered.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ELOOP', 40);

/**
 * Operation would block.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EWOULDBLOCK', 11);

/**
 * No message of desired type.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENOMSG', 42);

/**
 * Identifier removed.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EIDRM', 43);

/**
 * Channel number out of range.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ECHRNG', 44);

/**
 * Level 2 not synchronized.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EL2NSYNC', 45);

/**
 * Level 3 halted.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EL3HLT', 46);

/**
 * Level 3 reset.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EL3RST', 47);

/**
 * Link number out of range.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ELNRNG', 48);

/**
 * Protocol driver not attached.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EUNATCH', 49);

/**
 * No CSI structure available.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENOCSI', 50);

/**
 * Level 2 halted.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EL2HLT', 51);

/**
 * Invalid exchange.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EBADE', 52);

/**
 * Invalid request descriptor.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EBADR', 53);

/**
 * Exchange full.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EXFULL', 54);

/**
 * No anode.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENOANO', 55);

/**
 * Invalid request code.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EBADRQC', 56);

/**
 * Invalid slot.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EBADSLT', 57);

/**
 * Device not a stream.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENOSTR', 60);

/**
 * No data available.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENODATA', 61);

/**
 * Timer expired.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ETIME', 62);

/**
 * Out of streams resources.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENOSR', 63);

/**
 * Machine is not on the network.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENONET', 64);

/**
 * Object is remote.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EREMOTE', 66);

/**
 * Link has been severed.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENOLINK', 67);

/**
 * Advertise error.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EADV', 68);

/**
 * Srmount error.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ESRMNT', 69);

/**
 * Communication error on send.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ECOMM', 70);

/**
 * Protocol error.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EPROTO', 71);

/**
 * Multihop attempted.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EMULTIHOP', 72);

/**
 * Not a data message.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EBADMSG', 74);

/**
 * Name not unique on network.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENOTUNIQ', 76);

/**
 * File descriptor in bad state.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EBADFD', 77);

/**
 * Remote address changed.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EREMCHG', 78);

/**
 * Interrupted system call should be restarted.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ERESTART', 85);

/**
 * Streams pipe error.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ESTRPIPE', 86);

/**
 * Too many users.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EUSERS', 87);

/**
 * Socket operation on non-socket.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENOTSOCK', 88);

/**
 * Destination address required.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EDESTADDRREQ', 89);

/**
 * Message too long.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EMSGSIZE', 90);

/**
 * Protocol wrong type for socket.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EPROTOTYPE', 91);
define('SOCKET_ENOPROTOOPT', 92);

/**
 * Protocol not supported.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EPROTONOSUPPORT', 93);

/**
 * Socket type not supported.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ESOCKTNOSUPPORT', 94);

/**
 * Operation not supported on transport endpoint.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EOPNOTSUPP', 95);

/**
 * Protocol family not supported.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EPFNOSUPPORT', 96);

/**
 * Address family not supported by protocol.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EAFNOSUPPORT', 97);
define('SOCKET_EADDRINUSE', 98);

/**
 * Cannot assign requested address.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EADDRNOTAVAIL', 99);

/**
 * Network is down.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENETDOWN', 100);

/**
 * Network is unreachable.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENETUNREACH', 101);

/**
 * Network dropped connection because of reset.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENETRESET', 102);

/**
 * Software caused connection abort.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ECONNABORTED', 103);

/**
 * Connection reset by peer.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ECONNRESET', 103);

/**
 * No buffer space available.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENOBUFS', 105);

/**
 * Transport endpoint is already connected.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EISCONN', 106);

/**
 * Transport endpoint is not connected.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENOTCONN', 107);

/**
 * Cannot send after transport endpoint shutdown.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ESHUTDOWN', 108);

/**
 * Too many references: cannot splice.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ETOOMANYREFS', 109);

/**
 * Connection timed out.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ETIMEDOUT', 110);

/**
 * Connection refused.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ECONNREFUSED', 111);

/**
 * Host is down.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EHOSTDOWN', 112);

/**
 * No route to host.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EHOSTUNREACH', 113);

/**
 * Operation already in progress.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EALREADY', 114);

/**
 * Operation now in progress.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EINPROGRESS', 115);

/**
 * Is a named type file.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EISNAM', 120);

/**
 * Remote I/O error.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EREMOTEIO', 121);

/**
 * Quota exceeded.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EDQUOT', 122);

/**
 * No medium found.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_ENOMEDIUM', 123);

/**
 * Wrong medium type.
 * @link https://php.net/manual/en/sockets.constants.php
 */
define('SOCKET_EMEDIUMTYPE', 124);
define('IPPROTO_IP', 0);
define('IPPROTO_IPV6', 41);
define('SOL_TCP', 6);
define('SOL_UDP', 17);
define('IPV6_UNICAST_HOPS', 16);
define('IPV6_RECVPKTINFO', 49);
define('IPV6_PKTINFO', 50);
define('IPV6_RECVHOPLIMIT', 51);
define('IPV6_HOPLIMIT', 52);
define('IPV6_RECVTCLASS', 66);
define('IPV6_TCLASS', 67);
define('SCM_RIGHTS', 1);
define('SCM_CREDENTIALS', 2);
define('SO_PASSCRED', 16);

define('SOCKET_EPROCLIM', 10067);
define('SOCKET_ESTALE', 10070);
define('SOCKET_EDISCON', 10101);
define('SOCKET_SYSNOTREADY', 10091);
define('SOCKET_VERNOTSUPPORTED', 10092);
define('SOCKET_NOTINITIALISED', 10093);
define('SOCKET_HOST_NOT_FOUND', 11001);
define('SOCKET_TRY_AGAIN', 11002);
define('SOCKET_NO_RECOVERY', 11003);
define('SOCKET_NO_DATA', 11004);
define('SOCKET_NO_ADDRESS', 11004);

define('AI_PASSIVE', 1);
define('AI_CANONNAME', 2);
define('AI_NUMERICHOST', 4);
define('AI_ADDRCONFIG', 32);
define('AI_NUMERICSERV', 1024);
define('AI_V4MAPPED', 8);
define('AI_ALL', 16);

/**
 * @since 8.1
 */
define('TCP_DEFER_ACCEPT', 9);

/**
 * @since 8.2
 */
define('SO_INCOMING_CPU', 49);

/**
 * @since 8.2
 */
define('SO_MEMINFO', 55);

/**
 * @since 8.2
 */
define('SO_BPF_EXTENSIONS', 48);

/**
 * @since 8.2
 */
define('SKF_AD_OFF', -4096);

/**
 * @since 8.2
 */
define('SKF_AD_PROTOCOL', 0);

/**
 * @since 8.2
 */
define('SKF_AD_PKTTYPE', 4);

/**
 * @since 8.2
 */
define('SKF_AD_IFINDEX', 8);

/**
 * @since 8.2
 */
define('SKF_AD_NLATTR', 12);

/**
 * @since 8.2
 */
define('SKF_AD_NLATTR_NEST', 16);

/**
 * @since 8.2
 */
define('SKF_AD_MARK', 20);

/**
 * @since 8.2
 */
define('SKF_AD_QUEUE', 24);

/**
 * @since 8.2
 */
define('SKF_AD_HATYPE', 28);

/**
 * @since 8.2
 */
define('SKF_AD_RXHASH', 32);

/**
 * @since 8.2
 */
define('SKF_AD_CPU', 36);

/**
 * @since 8.2
 */
define('SKF_AD_ALU_XOR_X', 40);

/**
 * @since 8.2
 */
define('SKF_AD_VLAN_TAG', 44);

/**
 * @since 8.2
 */
define('SKF_AD_VLAN_TAG_PRESENT', 48);

/**
 * @since 8.2
 */
define('SKF_AD_PAY_OFFSET', 52);

/**
 * @since 8.2
 */
define('SKF_AD_RANDOM', 56);

/**
 * @since 8.2
 */
define('SKF_AD_VLAN_TPID', 60);

/**
 * @since 8.2
 */
define('SKF_AD_MAX', 64);

/**
 * @since 8.2
 */
define('TCP_CONGESTION', 13);

/**
 * @since 8.2
 */
define('TCP_NOTSENT_LOWAT', 25);

/**
 * @since 8.2
 */
define('TCP_KEEPIDLE', 4);

/**
 * @since 8.2
 */
define('TCP_KEEPINTVL', 5);

/**
 * @since 8.2
 */
define('TCP_KEEPCNT', 6);

/**
 * Socket_set_option for the socket_send* functions.
 * It avoids copy b/w userland and kernel for both TCP and UDP protocols.
 * @since 8.2
 */
define('SO_ZEROCOPY', 60);

/**
 * Socket_set_option for the socket_send* functions.
 * It avoids copy b/w userland and kernel for both TCP and UDP protocols.
 * @since 8.2
 */
define('MSG_ZEROCOPY', 67108864);

/**
 * @since 8.0
 */
final class Socket
{
    /**
     * Cannot directly construct Socket, use socket_create() instead
     * @see socket_create()
     */
    private function __construct() {}
}

/**
 * @since 8.0
 */
final class AddressInfo
{
    /**
     * Cannot directly construct AddressInfo, use socket_addrinfo_lookup() instead
     * @see socket_addrinfo_lookup()
     */
    private function __construct() {}
}
