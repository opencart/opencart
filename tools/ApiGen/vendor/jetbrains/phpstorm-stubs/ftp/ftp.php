<?php

// Start of ftp v.
use JetBrains\PhpStorm\ExpectedValues as EV;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;

/**
 * append the contents of a file to another file on the ftp server
 * @param resource $ftp
 * @param string $remote_filename
 * @param string $local_filename
 * @param int $mode Optional since PHP 7.3
 * @return bool
 * @since 7.2
 */
function ftp_append(
    #[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp,
    string $remote_filename,
    string $local_filename,
    #[EV([FTP_ASCII, FTP_BINARY])]
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.2')]
    int $mode,
    #[EV([FTP_ASCII, FTP_BINARY])]
    #[PhpStormStubsElementAvailable(from: '7.3')]
    int $mode = FTP_BINARY
): bool {}

/**
 * returns a list of files in the given directory
 * @param resource $ftp
 * @param string $directory
 * @return array|false
 * @since 7.2
 */
function ftp_mlsd(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp, string $directory): array|false {}

/**
 * Opens an FTP connection
 * @link https://php.net/manual/en/function.ftp-connect.php
 * @param string $hostname <p>
 * The FTP server address. This parameter shouldn't have any trailing
 * slashes and shouldn't be prefixed with ftp://.
 * </p>
 * @param int $port [optional] <p>
 * This parameter specifies an alternate port to connect to. If it is
 * omitted or set to zero, then the default FTP port, 21, will be used.
 * </p>
 * @param int $timeout [optional] <p>
 * This parameter specifies the timeout for all subsequent network operations.
 * If omitted, the default value is 90 seconds. The timeout can be changed and
 * queried at any time with <b>ftp_set_option</b> and
 * <b>ftp_get_option</b>.
 * </p>
 * @return resource|false a FTP stream on success or <b>FALSE</b> on error.
 */
#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection|false'], default: 'resource|false')]
function ftp_connect(string $hostname, int $port = 21, int $timeout = 90) {}

/**
 * Opens a Secure SSL-FTP connection
 * @link https://php.net/manual/en/function.ftp-ssl-connect.php
 * @param string $hostname <p>
 * The FTP server address. This parameter shouldn't have any trailing
 * slashes and shouldn't be prefixed with ftp://.
 * </p>
 * @param int $port [optional] <p>
 * This parameter specifies an alternate port to connect to. If it is
 * omitted or set to zero, then the default FTP port, 21, will be used.
 * </p>
 * @param int $timeout [optional] <p>
 * This parameter specifies the timeout for all subsequent network operations.
 * If omitted, the default value is 90 seconds. The timeout can be changed and
 * queried at any time with <b>ftp_set_option</b> and
 * <b>ftp_get_option</b>.
 * </p>
 * @return resource|false a SSL-FTP stream on success or <b>FALSE</b> on error.
 */
#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection|false'], default: 'resource|false')]
function ftp_ssl_connect(string $hostname, int $port = 21, int $timeout = 90) {}

/**
 * Logs in to an FTP connection
 * @link https://php.net/manual/en/function.ftp-login.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param string $username <p>
 * The username (USER).
 * </p>
 * @param string $password <p>
 * The password (PASS).
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 * If login fails, PHP will also throw a warning.
 */
function ftp_login(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp, string $username, string $password): bool {}

/**
 * Returns the current directory name
 * @link https://php.net/manual/en/function.ftp-pwd.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @return string|false the current directory name or <b>FALSE</b> on error.
 */
function ftp_pwd(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp): string|false {}

/**
 * Changes to the parent directory
 * @link https://php.net/manual/en/function.ftp-cdup.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function ftp_cdup(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp): bool {}

/**
 * Changes the current directory on a FTP server
 * @link https://php.net/manual/en/function.ftp-chdir.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param string $directory <p>
 * The target directory.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 * If changing directory fails, PHP will also throw a warning.
 */
function ftp_chdir(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp, string $directory): bool {}

/**
 * Requests execution of a command on the FTP server
 * @link https://php.net/manual/en/function.ftp-exec.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param string $command <p>
 * The command to execute.
 * </p>
 * @return bool <b>TRUE</b> if the command was successful (server sent response code:
 * 200); otherwise returns <b>FALSE</b>.
 */
function ftp_exec(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp, string $command): bool {}

/**
 * Sends an arbitrary command to an FTP server
 * @link https://php.net/manual/en/function.ftp-raw.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param string $command <p>
 * The command to execute.
 * </p>
 * @return string[] the server's response as an array of strings.
 * No parsing is performed on the response string, nor does
 * <b>ftp_raw</b> determine if the command succeeded.
 */
#[LanguageLevelTypeAware(['8.0' => 'array|null'], default: 'array')]
function ftp_raw(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp, string $command) {}

/**
 * Creates a directory
 * @link https://php.net/manual/en/function.ftp-mkdir.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param string $directory <p>
 * The name of the directory that will be created.
 * </p>
 * @return string|false the newly created directory name on success or <b>FALSE</b> on error.
 */
function ftp_mkdir(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp, string $directory): string|false {}

/**
 * Removes a directory
 * @link https://php.net/manual/en/function.ftp-rmdir.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param string $directory <p>
 * The directory to delete. This must be either an absolute or relative
 * path to an empty directory.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function ftp_rmdir(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp, string $directory): bool {}

/**
 * Set permissions on a file via FTP
 * @link https://php.net/manual/en/function.ftp-chmod.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param int $permissions <p>
 * The new permissions, given as an octal value.
 * </p>
 * @param string $filename <p>
 * The remote file.
 * </p>
 * @return int|false the new file permissions on success or <b>FALSE</b> on error.
 */
function ftp_chmod(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp, int $permissions, string $filename): int|false {}

/**
 * Allocates space for a file to be uploaded
 * @link https://php.net/manual/en/function.ftp-alloc.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param int $size <p>
 * The number of bytes to allocate.
 * </p>
 * @param string &$response [optional] <p>
 * A textual representation of the servers response will be returned by
 * reference in <i>result</i> if a variable is provided.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function ftp_alloc(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp, int $size, &$response): bool {}

/**
 * Returns a list of files in the given directory
 * @link https://php.net/manual/en/function.ftp-nlist.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param string $directory <p>
 * The directory to be listed. This parameter can also include arguments, eg.
 * ftp_nlist($conn_id, "-la /your/dir");
 * Note that this parameter isn't escaped so there may be some issues with
 * filenames containing spaces and other characters.
 * </p>
 * @return string[]|false an array of filenames from the specified directory on success or
 * <b>FALSE</b> on error.
 */
function ftp_nlist(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp, string $directory): array|false {}

/**
 * Returns a detailed list of files in the given directory
 * @link https://php.net/manual/en/function.ftp-rawlist.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param string $directory <p>
 * The directory path. May include arguments for the LIST
 * command.
 * </p>
 * @param bool $recursive [optional] <p>
 * If set to <b>TRUE</b>, the issued command will be LIST -R.
 * </p>
 * @return string[]|false an array where each element corresponds to one line of text.
 * <p>
 * The output is not parsed in any way. The system type identifier returned by
 * <b>ftp_systype</b> can be used to determine how the results
 * should be interpreted.
 * </p>
 */
function ftp_rawlist(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp, string $directory, bool $recursive = false): array|false {}

/**
 * Returns the system type identifier of the remote FTP server
 * @link https://php.net/manual/en/function.ftp-systype.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @return string|false the remote system type, or <b>FALSE</b> on error.
 */
function ftp_systype(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp): string|false {}

/**
 * Turns passive mode on or off
 * @link https://php.net/manual/en/function.ftp-pasv.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param bool $enable <p>
 * If <b>TRUE</b>, the passive mode is turned on, else it's turned off.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function ftp_pasv(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp, bool $enable): bool {}

/**
 * Downloads a file from the FTP server
 * @link https://php.net/manual/en/function.ftp-get.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param string $local_filename <p>
 * The local file path (will be overwritten if the file already exists).
 * </p>
 * @param string $remote_filename <p>
 * The remote file path.
 * </p>
 * @param int $mode <p>
 * The transfer mode. Must be either <b>FTP_ASCII</b> or <b>FTP_BINARY</b>. Optional since PHP 7.3
 * </p>
 * @param int $offset [optional] <p>
 * The position in the remote file to start downloading from.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function ftp_get(
    #[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp,
    string $local_filename,
    string $remote_filename,
    #[EV([FTP_ASCII, FTP_BINARY])]
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.2')]
    int $mode,
    #[EV([FTP_ASCII, FTP_BINARY])]
    #[PhpStormStubsElementAvailable(from: '7.3')]
    int $mode = FTP_BINARY,
    int $offset = 0
): bool {}

/**
 * Downloads a file from the FTP server and saves to an open file
 * @link https://php.net/manual/en/function.ftp-fget.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param resource $stream <p>
 * An open file pointer in which we store the data.
 * </p>
 * @param string $remote_filename <p>
 * The remote file path.
 * </p>
 * @param int $mode <p>
 * The transfer mode. Must be either <b>FTP_ASCII</b> or <b>FTP_BINARY</b>. Since PHP 7.3 parameter is optional
 * </p>
 * @param int $offset [optional] <p>
 * The position in the remote file to start downloading from.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function ftp_fget(
    #[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp,
    $stream,
    string $remote_filename,
    #[EV([FTP_ASCII, FTP_BINARY])]
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.2')]
    int $mode,
    #[EV([FTP_ASCII, FTP_BINARY])]
    #[PhpStormStubsElementAvailable(from: '7.3')]
    int $mode = FTP_BINARY,
    int $offset = 0
): bool {}

/**
 * Uploads a file to the FTP server
 * @link https://php.net/manual/en/function.ftp-put.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param string $remote_filename <p>
 * The remote file path.
 * </p>
 * @param string $local_filename <p>
 * The local file path.
 * </p>
 * @param int $mode <p>
 * The transfer mode. Must be either <b>FTP_ASCII</b> or <b>FTP_BINARY</b>. Optional since PHP 7.3
 * </p>
 * @param int $offset [optional] <p>The position in the remote file to start uploading to.</p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function ftp_put(
    #[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp,
    string $remote_filename,
    string $local_filename,
    #[EV([FTP_ASCII, FTP_BINARY])]
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.2')]
    int $mode,
    #[EV([FTP_ASCII, FTP_BINARY])]
    #[PhpStormStubsElementAvailable(from: '7.3')]
    int $mode = FTP_BINARY,
    int $offset = 0
): bool {}

/**
 * Uploads from an open file to the FTP server
 * @link https://php.net/manual/en/function.ftp-fput.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param string $remote_filename <p>
 * The remote file path.
 * </p>
 * @param resource $stream <p>
 * An open file pointer on the local file. Reading stops at end of file.
 * </p>
 * @param int $mode <p>
 * The transfer mode. Must be either <b>FTP_ASCII</b> or <b>FTP_BINARY</b>. Optional since PHP 7.3
 * </p>
 * @param int $offset [optional] <p>The position in the remote file to start uploading to.</p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function ftp_fput(
    #[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp,
    string $remote_filename,
    $stream,
    #[EV([FTP_ASCII, FTP_BINARY])]
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.2')]
    int $mode,
    #[EV([FTP_ASCII, FTP_BINARY])]
    #[PhpStormStubsElementAvailable(from: '7.3')]
    int $mode = FTP_BINARY,
    int $offset = 0
): bool {}

/**
 * Returns the size of the given file
 * @link https://php.net/manual/en/function.ftp-size.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param string $filename <p>
 * The remote file.
 * </p>
 * @return int the file size on success, or -1 on error.
 */
function ftp_size(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp, string $filename): int {}

/**
 * Returns the last modified time of the given file
 * @link https://php.net/manual/en/function.ftp-mdtm.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param string $filename <p>
 * The file from which to extract the last modification time.
 * </p>
 * @return int the last modified time as a Unix timestamp on success, or -1 on
 * error.
 */
function ftp_mdtm(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp, string $filename): int {}

/**
 * Renames a file or a directory on the FTP server
 * @link https://php.net/manual/en/function.ftp-rename.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param string $from <p>
 * The old file/directory name.
 * </p>
 * @param string $to <p>
 * The new name.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function ftp_rename(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp, string $from, string $to): bool {}

/**
 * Deletes a file on the FTP server
 * @link https://php.net/manual/en/function.ftp-delete.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param string $filename <p>
 * The file to delete.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function ftp_delete(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp, string $filename): bool {}

/**
 * Sends a SITE command to the server
 * @link https://php.net/manual/en/function.ftp-site.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param string $command <p>
 * The SITE command. Note that this parameter isn't escaped so there may
 * be some issues with filenames containing spaces and other characters.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function ftp_site(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp, string $command): bool {}

/**
 * Closes an FTP connection
 * @link https://php.net/manual/en/function.ftp-close.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function ftp_close(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp): bool {}

/**
 * Set miscellaneous runtime FTP options
 * @link https://php.net/manual/en/function.ftp-set-option.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param int $option <p>
 * Currently, the following options are supported:
 * <table>
 * Supported runtime FTP options
 * <tr valign="top">
 * <td><b>FTP_TIMEOUT_SEC</b></td>
 * <td>
 * Changes the timeout in seconds used for all network related
 * functions. <i>value</i> must be an integer that
 * is greater than 0. The default timeout is 90 seconds.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>FTP_AUTOSEEK</b></td>
 * <td>
 * When enabled, GET or PUT requests with a
 * <i>resumepos</i> or <i>startpos</i>
 * parameter will first seek to the requested position within the file.
 * This is enabled by default.
 * </td>
 * </tr>
 * </table>
 * </p>
 * @param mixed $value <p>
 * This parameter depends on which <i>option</i> is chosen
 * to be altered.
 * </p>
 * @return bool <b>TRUE</b> if the option could be set; <b>FALSE</b> if not. A warning
 * message will be thrown if the <i>option</i> is not
 * supported or the passed <i>value</i> doesn't match the
 * expected value for the given <i>option</i>.
 */
function ftp_set_option(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp, #[EV(flags: [FTP_TIMEOUT_SEC, FTP_AUTOSEEK, FTP_USEPASVADDRESS])] int $option, $value): bool {}

/**
 * Retrieves various runtime behaviours of the current FTP stream
 * @link https://php.net/manual/en/function.ftp-get-option.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param int $option <p>
 * Currently, the following options are supported:
 * <table>
 * Supported runtime FTP options
 * <tr valign="top">
 * <td><b>FTP_TIMEOUT_SEC</b></td>
 * <td>
 * Returns the current timeout used for network related operations.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>FTP_AUTOSEEK</b></td>
 * <td>
 * Returns <b>TRUE</b> if this option is on, <b>FALSE</b> otherwise.
 * </td>
 * </tr>
 * </table>
 * </p>
 * @return int|bool the value on success or <b>FALSE</b> if the given
 * <i>option</i> is not supported. In the latter case, a
 * warning message is also thrown.
 */
function ftp_get_option(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp, #[EV(flags: [FTP_TIMEOUT_SEC, FTP_AUTOSEEK])] int $option): int|bool {}

/**
 * Retrieves a file from the FTP server and writes it to an open file (non-blocking)
 * @link https://php.net/manual/en/function.ftp-nb-fget.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param resource $stream <p>
 * An open file pointer in which we store the data.
 * </p>
 * @param string $remote_filename <p>
 * The remote file path.
 * </p>
 * @param int $mode <p>
 * The transfer mode. Must be either <b>FTP_ASCII</b> or <b>FTP_BINARY</b>. Optional since PHP 7.3
 * </p>
 * @param int $offset [optional] <p>The position in the remote file to start downloading from.</p>
 * @return int <b>FTP_FAILED</b> or <b>FTP_FINISHED</b>
 * or <b>FTP_MOREDATA</b>.
 */
#[EV([FTP_FAILED, FTP_FINISHED, FTP_MOREDATA])]
function ftp_nb_fget(
    #[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp,
    $stream,
    string $remote_filename,
    #[EV([FTP_ASCII, FTP_BINARY])]
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.2')]
    int $mode,
    #[EV([FTP_ASCII, FTP_BINARY])]
    #[PhpStormStubsElementAvailable(from: '7.3')]
    int $mode = FTP_BINARY,
    int $offset = 0
): int {}

/**
 * Retrieves a file from the FTP server and writes it to a local file (non-blocking)
 * @link https://php.net/manual/en/function.ftp-nb-get.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param string $local_filename <p>
 * The local file path (will be overwritten if the file already exists).
 * </p>
 * @param string $remote_filename <p>
 * The remote file path.
 * </p>
 * @param int $mode  <p>
 * The transfer mode. Must be either <b>FTP_ASCII</b> or <b>FTP_BINARY</b>. Optional since PHP 7.3
 * </p>
 * @param int $offset [optional] <p>The position in the remote file to start downloading from.</p>
 * @return int <b>FTP_FAILED</b> or <b>FTP_FINISHED</b>
 * or <b>FTP_MOREDATA</b>.
 */
#[EV([FTP_FAILED, FTP_FINISHED, FTP_MOREDATA])]
function ftp_nb_get(
    #[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp,
    string $local_filename,
    string $remote_filename,
    #[EV([FTP_ASCII, FTP_BINARY])]
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.2')]
    int $mode,
    #[EV([FTP_ASCII, FTP_BINARY])]
    #[PhpStormStubsElementAvailable(from: '7.3')]
    int $mode = FTP_BINARY,
    int $offset = 0
): int {}

/**
 * Continues retrieving/sending a file (non-blocking)
 * @link https://php.net/manual/en/function.ftp-nb-continue.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @return int <b>FTP_FAILED</b> or <b>FTP_FINISHED</b>
 * or <b>FTP_MOREDATA</b>.
 */
#[EV([FTP_FAILED, FTP_FINISHED, FTP_MOREDATA])]
function ftp_nb_continue(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp): int {}

/**
 * Stores a file on the FTP server (non-blocking)
 * @link https://php.net/manual/en/function.ftp-nb-put.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param string $remote_filename <p>
 * The remote file path.
 * </p>
 * @param string $local_filename <p>
 * The local file path.
 * </p>
 * @param int $mode <p>
 * The transfer mode. Must be either <b>FTP_ASCII</b> or <b>FTP_BINARY</b>. Optional since PHP 7.3
 * </p>
 * @param int $offset [optional] <p>The position in the remote file to start uploading to.</p>
 * @return int|false <b>FTP_FAILED</b> or <b>FTP_FINISHED</b>
 * or <b>FTP_MOREDATA</b>.
 */
#[EV([FTP_FAILED, FTP_FINISHED, FTP_MOREDATA])]
function ftp_nb_put(
    #[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp,
    string $remote_filename,
    string $local_filename,
    #[EV([FTP_ASCII, FTP_BINARY])]
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.2')]
    int $mode,
    #[EV([FTP_ASCII, FTP_BINARY])]
    #[PhpStormStubsElementAvailable(from: '7.3')]
    int $mode = FTP_BINARY,
    int $offset = 0
): int|false {}

/**
 * Stores a file from an open file to the FTP server (non-blocking)
 * @link https://php.net/manual/en/function.ftp-nb-fput.php
 * @param resource $ftp <p>
 * The link identifier of the FTP connection.
 * </p>
 * @param string $remote_filename <p>
 * The remote file path.
 * </p>
 * @param resource $stream <p>
 * An open file pointer on the local file. Reading stops at end of file.
 * </p>
 * @param int $mode <p>
 * The transfer mode. Must be either <b>FTP_ASCII</b> or <b>FTP_BINARY</b>. Optional since PHP 7.3
 * </p>
 * @param int $offset [optional] <p>The position in the remote file to start uploading to.</p>
 * @return int <b>FTP_FAILED</b> or <b>FTP_FINISHED</b>
 * or <b>FTP_MOREDATA</b>.
 */
#[EV([FTP_FAILED, FTP_FINISHED, FTP_MOREDATA])]
function ftp_nb_fput(
    #[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp,
    string $remote_filename,
    $stream,
    #[EV([FTP_ASCII, FTP_BINARY])]
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.2')]
    int $mode,
    #[EV([FTP_ASCII, FTP_BINARY])]
    #[PhpStormStubsElementAvailable(from: '7.3')]
    int $mode = FTP_BINARY,
    int $offset = 0
): int {}

/**
 * Alias of <b>ftp_close</b>
 * @link https://php.net/manual/en/function.ftp-quit.php
 * @param resource $ftp
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function ftp_quit(#[LanguageLevelTypeAware(['8.1' => 'FTP\Connection'], default: 'resource')] $ftp): bool {}

/**
 * <p></p>
 * @link https://php.net/manual/en/ftp.constants.php
 */
define('FTP_ASCII', 1);

/**
 * <p></p>
 * @link https://php.net/manual/en/ftp.constants.php
 */
define('FTP_TEXT', 1);

/**
 * <p></p>
 * @link https://php.net/manual/en/ftp.constants.php
 */
define('FTP_BINARY', 2);

/**
 * <p></p>
 * @link https://php.net/manual/en/ftp.constants.php
 */
define('FTP_IMAGE', 2);

/**
 * <p>
 * Automatically determine resume position and start position for GET and PUT requests
 * (only works if FTP_AUTOSEEK is enabled)
 * </p>
 * @link https://php.net/manual/en/ftp.constants.php
 */
define('FTP_AUTORESUME', -1);

/**
 * <p>
 * See <b>ftp_set_option</b> for information.
 * </p>
 * @link https://php.net/manual/en/ftp.constants.php
 */
define('FTP_TIMEOUT_SEC', 0);

/**
 * <p>
 * See <b>ftp_set_option</b> for information.
 * </p>
 * @link https://php.net/manual/en/ftp.constants.php
 */
define('FTP_AUTOSEEK', 1);

define('FTP_USEPASVADDRESS', 2);

/**
 * <p>
 * Asynchronous transfer has failed
 * </p>
 * @link https://php.net/manual/en/ftp.constants.php
 */
define('FTP_FAILED', 0);

/**
 * <p>
 * Asynchronous transfer has finished
 * </p>
 * @link https://php.net/manual/en/ftp.constants.php
 */
define('FTP_FINISHED', 1);

/**
 * <p>
 * Asynchronous transfer is still active
 * </p>
 * @link https://php.net/manual/en/ftp.constants.php
 */
define('FTP_MOREDATA', 2);

// End of ftp v.
