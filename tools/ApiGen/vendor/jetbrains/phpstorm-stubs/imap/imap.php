<?php

// Start of imap v.
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;

/**
 * Open an IMAP stream to a mailbox
 * @link https://php.net/manual/en/function.imap-open.php
 * @param string $mailbox <p>
 * A mailbox name consists of a server and a mailbox path on this server.
 * The special name INBOX stands for the current users
 * personal mailbox. Mailbox names that contain international characters
 * besides those in the printable ASCII space have to be encoded width
 * <b>imap_utf7_encode</b>.
 * </p>
 * <p>
 * The server part, which is enclosed in '{' and '}', consists of the servers
 * name or ip address, an optional port (prefixed by ':'), and an optional
 * protocol specification (prefixed by '/').
 * </p>
 * <p>
 * The server part is mandatory in all mailbox
 * parameters.
 * </p>
 * <p>
 * All names which start with { are remote names, and are
 * in the form "{" remote_system_name [":" port] [flags] "}"
 * [mailbox_name] where:
 * remote_system_name - Internet domain name or
 * bracketed IP address of server.</p>
 * @param string $user <p>
 * The user name
 * </p>
 * @param string $password <p>
 * The password associated with the <i>username</i>
 * </p>
 * @param int $flags [optional] <p>
 * The <i>options</i> are a bit mask with one or more of
 * the following:
 * <b>OP_READONLY</b> - Open mailbox read-only</p>
 * @param int $retries [optional] <p>
 * Number of maximum connect attempts
 * </p>
 * @param null|array $options <p>
 * Connection parameters, the following (string) keys maybe used
 * to set one or more connection parameters:
 * DISABLE_AUTHENTICATOR - Disable authentication properties</p>
 * @return resource|false an IMAP stream on success or <b>FALSE</b> on error.
 */
#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection|false'], default: 'resource|false')]
function imap_open(string $mailbox, string $user, string $password, int $flags = 0, int $retries = 0, array $options = []) {}

/**
 * Reopen IMAP stream to new mailbox
 * @link https://php.net/manual/en/function.imap-reopen.php
 * @param resource $imap
 * @param string $mailbox <p>
 * The mailbox name, see <b>imap_open</b> for more
 * information
 * </p>
 * @param int $flags [optional] <p>
 * The <i>options</i> are a bit mask with one or more of
 * the following:
 * <b>OP_READONLY</b> - Open mailbox read-only</p>
 * @param int $retries [optional] <p>
 * Number of maximum connect attempts
 * </p>
 * @return bool <b>TRUE</b> if the stream is reopened, <b>FALSE</b> otherwise.
 */
function imap_reopen(
    #[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap,
    string $mailbox,
    int $flags = 0,
    int $retries = 0
): bool {}

/**
 * Close an IMAP stream
 * @link https://php.net/manual/en/function.imap-close.php
 * @param resource $imap
 * @param int $flags [optional] <p>
 * If set to <b>CL_EXPUNGE</b>, the function will silently
 * expunge the mailbox before closing, removing all messages marked for
 * deletion. You can achieve the same thing by using
 * <b>imap_expunge</b>
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function imap_close(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, int $flags = 0): bool {}

/**
 * Gets the number of messages in the current mailbox
 * @link https://php.net/manual/en/function.imap-num-msg.php
 * @param resource $imap
 * @return int|false Return the number of messages in the current mailbox, as an integer.
 */
function imap_num_msg(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap): int|false {}

/**
 * Gets the number of recent messages in current mailbox
 * @link https://php.net/manual/en/function.imap-num-recent.php
 * @param resource $imap
 * @return int the number of recent messages in the current mailbox, as an
 * integer.
 */
function imap_num_recent(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap): int {}

/**
 * Returns headers for all messages in a mailbox
 * @link https://php.net/manual/en/function.imap-headers.php
 * @param resource $imap
 * @return array|false an array of string formatted with header info. One
 * element per mail message.
 */
function imap_headers(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap): array|false {}

/**
 * Read the header of the message
 * @link https://php.net/manual/en/function.imap-headerinfo.php
 * @param resource|IMAP\Connection $imap An IMAP stream returned by imap_open().
 * @param int $message_num The message number
 * @param int $from_length [optional] Number of characters for the fetchfrom property. Must be greater than or equal to zero.
 * @param int $subject_length [optional] Number of characters for the fetchsubject property Must be greater than or equal to zero.
 * @param $default_host [optional]
 * @return stdClass|false Returns the information in an object with following properties:
 * <dl>
 * <dt>toaddress</dt><dd>full to: line, up to 1024 characters</dd>
 * <dt>to</dt><dd>an array of objects from the To: line, with the following properties: personal, adl, mailbox, and host</dd>
 * <dt>fromaddress</dt><dd>full from: line, up to 1024 characters</dd>
 * <dt>from</dt><dd>an array of objects from the From: line, with the following properties: personal, adl, mailbox, and host</dd>
 * <dt>ccaddress</dt><dd>full cc: line, up to 1024 characters</dd>
 * <dt>cc</dt><dd>an array of objects from the Cc: line, with the following properties: personal, adl, mailbox, and host</dd>
 * <dt>bccaddress</dt><dd>full bcc: line, up to 1024 characters</dd>
 * <dt>bcc</dt><dd>an array of objects from the Bcc: line, with the following properties: personal, adl, mailbox, and host</dd>
 * <dt>reply_toaddress</dt><dd>full Reply-To: line, up to 1024 characters</dd>
 * <dt>reply_to</dt><dd>an array of objects from the Reply-To: line, with the following properties: personal, adl, mailbox, and host</dd>
 * <dt>senderaddress</dt><dd>full sender: line, up to 1024 characters</dd>
 * <dt>sender</dt><dd>an array of objects from the Sender: line, with the following properties: personal, adl, mailbox, and host</dd>
 * <dt>return_pathaddress</dt><dd>full Return-Path: line, up to 1024 characters</dd>
 * <dt>return_path</dt><dd>an array of objects from the Return-Path: line, with the following properties: personal, adl, mailbox, and host</dd>
 * <dt>remail -</dt>
 * <dt>date</dt><dd>The message date as found in its headers</dd>
 * <dt>Date</dt><dd>Same as date</dd>
 * <dt>subject</dt><dd>The message subject</dd>
 * <dt>Subject</dt><dd>Same a subject</dd>
 * <dt>in_reply_to -</dt>
 * <dt>message_id -</dt>
 * <dt>newsgroups -</dt>
 * <dt>followup_to -</dt>
 * <dt>references -</dt>
 * <dt>Recent</dt><dd>R if recent and seen, N if recent and not seen, ' ' if not recent.</dd>
 * <dt>Unseen</dt><dd>U if not seen AND not recent, ' ' if seen OR not seen and recent</dd>
 * <dt>Flagged</dt><dd>F if flagged, ' ' if not flagged</dd>
 * <dt>Answered</dt><dd>A if answered, ' ' if unanswered</dd>
 * <dt>Deleted</dt><dd>D if deleted, ' ' if not deleted</dd>
 * <dt>Draft</dt><dd>X if draft, ' ' if not draft</dd>
 * <dt>Msgno</dt><dd>The message number</dd>
 * <dt>MailDate -</dt>
 * <dt>Size</dt><dd>The message size</dd>
 * <dt>udate</dt><dd>mail message date in Unix time</dd>
 * <dt>fetchfrom</dt><dd>from line formatted to fit fromlength characters</dd>
 * <dt>fetchsubject</dt><dd>subject line formatted to fit subjectlength characters</dd>
 * </dl>
 */
function imap_headerinfo(
    #[LanguageLevelTypeAware(['8.0' => 'IMAP\Connection'], default: 'resource')] $imap,
    int $message_num,
    int $from_length = 0,
    int $subject_length = 0,
    #[PhpStormStubsElementAvailable(to: '7.4')] $default_host = null
): stdClass|false {}

/**
 * Parse mail headers from a string
 * @link https://php.net/manual/en/function.imap-rfc822-parse-headers.php
 * @param string $headers <p>
 * The parsed headers data
 * </p>
 * @param string $default_hostname [optional] <p>
 * The default host name
 * </p>
 * @return object|stdClass an object similar to the one returned by
 * <b>imap_header</b>, except for the flags and other
 * properties that come from the IMAP server.
 */
function imap_rfc822_parse_headers(string $headers, string $default_hostname = "UNKNOWN"): stdClass {}

/**
 * Returns a properly formatted email address given the mailbox, host, and personal info
 * @link https://php.net/manual/en/function.imap-rfc822-write-address.php
 * @param string $mailbox <p>
 * The mailbox name, see <b>imap_open</b> for more
 * information
 * </p>
 * @param string $hostname <p>
 * The email host part
 * </p>
 * @param string $personal <p>
 * The name of the account owner
 * </p>
 * @return string|false a string properly formatted email address as defined in RFC2822.
 */
function imap_rfc822_write_address(string $mailbox, string $hostname, string $personal): string|false {}

/**
 * Parses an address string
 * @link https://php.net/manual/en/function.imap-rfc822-parse-adrlist.php
 * @param string $string <p>
 * A string containing addresses
 * </p>
 * @param string $default_hostname <p>
 * The default host name
 * </p>
 * @return array an array of objects. The objects properties are:
 * <p>
 * mailbox - the mailbox name (username)
 * host - the host name
 * personal - the personal name
 * adl - at domain source route
 * </p>
 */
function imap_rfc822_parse_adrlist(string $string, string $default_hostname): array {}

/**
 * Read the message body
 * @link https://php.net/manual/en/function.imap-body.php
 * @param resource $imap
 * @param int $message_num <p>
 * The message number
 * </p>
 * @param int $flags [optional] <p>
 * The optional <i>options</i> are a bit mask
 * with one or more of the following:
 * <b>FT_UID</b> - The <i>msg_number</i> is a UID</p>
 * @return string|false the body of the specified message, as a string.
 */
function imap_body(
    #[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap,
    int $message_num,
    int $flags = 0
): string|false {}

/**
 * Read the structure of a specified body section of a specific message
 * @link https://php.net/manual/en/function.imap-bodystruct.php
 * @param resource $imap
 * @param int $message_num <p>
 * The message number
 * </p>
 * @param string $section <p>
 * The body section to read
 * </p>
 * @return object the information in an object, for a detailed description
 * of the object structure and properties see
 * <b>imap_fetchstructure</b>.
 */
#[LanguageLevelTypeAware(['8.1' => 'stdClass|false'], default: 'object')]
function imap_bodystruct(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, int $message_num, string $section) {}

/**
 * Fetch a particular section of the body of the message
 * @link https://php.net/manual/en/function.imap-fetchbody.php
 * @param resource $imap
 * @param int $message_num <p>
 * The message number
 * </p>
 * @param string $section <p>
 * The part number. It is a string of integers delimited by period which
 * index into a body part list as per the IMAP4 specification
 * </p>
 * @param int $flags [optional] <p>
 * A bitmask with one or more of the following:
 * <b>FT_UID</b> - The <i>msg_number</i> is a UID</p>
 * @return string|false a particular section of the body of the specified messages as a
 * text string.
 */
function imap_fetchbody(
    #[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap,
    int $message_num,
    string $section,
    int $flags = 0
): string|false {}

/**
 * Fetch MIME headers for a particular section of the message
 * @link https://php.net/manual/en/function.imap-fetchmime.php
 * @param resource $imap
 * @param int $message_num <p>
 * The message number
 * </p>
 * @param string $section <p>
 * The part number. It is a string of integers delimited by period which
 * index into a body part list as per the IMAP4 specification
 * </p>
 * @param int $flags [optional] <p>
 * A bitmask with one or more of the following:
 * <b>FT_UID</b> - The <i>msg_number</i> is a UID</p>
 * @return string|false the MIME headers of a particular section of the body of the specified messages as a
 * text string.
 * @since 5.3.6
 */
function imap_fetchmime(
    #[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap,
    int $message_num,
    string $section,
    int $flags = 0
): string|false {}

/**
 * Save a specific body section to a file
 * @link https://php.net/manual/en/function.imap-savebody.php
 * @param resource $imap
 * @param mixed $file <p>
 * The path to the saved file as a string, or a valid file descriptor
 * returned by <b>fopen</b>.
 * </p>
 * @param int $message_num <p>
 * The message number
 * </p>
 * @param string $section [optional] <p>
 * The part number. It is a string of integers delimited by period which
 * index into a body part list as per the IMAP4 specification
 * </p>
 * @param int $flags [optional] <p>
 * A bitmask with one or more of the following:
 * <b>FT_UID</b> - The <i>msg_number</i> is a UID</p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 * @since 5.1.3
 */
function imap_savebody(
    #[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap,
    $file,
    int $message_num,
    string $section = "",
    int $flags = 0
): bool {}

/**
 * Returns header for a message
 * @link https://php.net/manual/en/function.imap-fetchheader.php
 * @param resource $imap
 * @param int $message_num <p>
 * The message number
 * </p>
 * @param int $flags [optional] <p>
 * The possible <i>options</i> are:
 * <b>FT_UID</b> - The <i>msgno</i>
 * argument is a UID</p>
 * @return string|false the header of the specified message as a text string.
 */
function imap_fetchheader(
    #[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap,
    int $message_num,
    int $flags = 0
): string|false {}

/**
 * Read the structure of a particular message
 * @link https://php.net/manual/en/function.imap-fetchstructure.php
 * @param resource $imap
 * @param int $message_num <p>
 * The message number
 * </p>
 * @param int $flags [optional] <p>
 * This optional parameter only has a single option,
 * <b>FT_UID</b>, which tells the function to treat the
 * <i>msg_number</i> argument as a
 * UID.
 * </p>
 * @return object|stdClass|false an object includes the envelope, internal date, size, flags and
 * body structure along with a similar object for each mime attachment. The
 * structure of the returned objects is as follows:
 * </p>
 * <p>
 * <table>
 * Returned Objects for <b>imap_fetchstructure</b>
 * <tr valign="top">
 * <td>type</td>
 * <td>Primary body type</td>
 * </tr>
 * <tr valign="top">
 * <td>encoding</td>
 * <td>Body transfer encoding</td>
 * </tr>
 * <tr valign="top">
 * <td>ifsubtype</td>
 * <td><b>TRUE</b> if there is a subtype string</td>
 * </tr>
 * <tr valign="top">
 * <td>subtype</td>
 * <td>MIME subtype</td>
 * </tr>
 * <tr valign="top">
 * <td>ifdescription</td>
 * <td><b>TRUE</b> if there is a description string</td>
 * </tr>
 * <tr valign="top">
 * <td>description</td>
 * <td>Content description string</td>
 * </tr>
 * <tr valign="top">
 * <td>ifid</td>
 * <td><b>TRUE</b> if there is an identification string</td>
 * </tr>
 * <tr valign="top">
 * <td>id</td>
 * <td>Identification string</td>
 * </tr>
 * <tr valign="top">
 * <td>lines</td>
 * <td>Number of lines</td>
 * </tr>
 * <tr valign="top">
 * <td>bytes</td>
 * <td>Number of bytes</td>
 * </tr>
 * <tr valign="top">
 * <td>ifdisposition</td>
 * <td><b>TRUE</b> if there is a disposition string</td>
 * </tr>
 * <tr valign="top">
 * <td>disposition</td>
 * <td>Disposition string</td>
 * </tr>
 * <tr valign="top">
 * <td>ifdparameters</td>
 * <td><b>TRUE</b> if the dparameters array exists</td>
 * </tr>
 * <tr valign="top">
 * <td>dparameters</td>
 * <td>An array of objects where each object has an
 * "attribute" and a "value"
 * property corresponding to the parameters on the
 * Content-disposition MIME
 * header.</td>
 * </tr>
 * <tr valign="top">
 * <td>ifparameters</td>
 * <td><b>TRUE</b> if the parameters array exists</td>
 * </tr>
 * <tr valign="top">
 * <td>parameters</td>
 * <td>An array of objects where each object has an
 * "attribute" and a "value"
 * property.</td>
 * </tr>
 * <tr valign="top">
 * <td>parts</td>
 * <td>An array of objects identical in structure to the top-level
 * object, each of which corresponds to a MIME body
 * part.</td>
 * </tr>
 * </table>
 * </p>
 * <p>
 * <table>
 * Primary body type (may vary with used library)
 * <tr valign="top"><td>0</td><td>text</td></tr>
 * <tr valign="top"><td>1</td><td>multipart</td></tr>
 * <tr valign="top"><td>2</td><td>message</td></tr>
 * <tr valign="top"><td>3</td><td>application</td></tr>
 * <tr valign="top"><td>4</td><td>audio</td></tr>
 * <tr valign="top"><td>5</td><td>image</td></tr>
 * <tr valign="top"><td>6</td><td>video</td></tr>
 * <tr valign="top"><td>7</td><td>other</td></tr>
 * </table>
 * </p>
 * <p>
 * <table>
 * Transfer encodings (may vary with used library)
 * <tr valign="top"><td>0</td><td>7BIT</td></tr>
 * <tr valign="top"><td>1</td><td>8BIT</td></tr>
 * <tr valign="top"><td>2</td><td>BINARY</td></tr>
 * <tr valign="top"><td>3</td><td>BASE64</td></tr>
 * <tr valign="top"><td>4</td><td>QUOTED-PRINTABLE</td></tr>
 * <tr valign="top"><td>5</td><td>OTHER</td></tr>
 * </table>
 */
function imap_fetchstructure(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, int $message_num, int $flags = 0): stdClass|false {}

/**
 * Clears IMAP cache
 * @link https://php.net/manual/en/function.imap-gc.php
 * @param resource $imap
 * @param int $flags <p>
 * Specifies the cache to purge. It may one or a combination
 * of the following constants:
 * <b>IMAP_GC_ELT</b> (message cache elements),
 * <b>IMAP_GC_ENV</b> (enveloppe and bodies),
 * <b>IMAP_GC_TEXTS</b> (texts).
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function imap_gc(
    #[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] int $flags = 0,
    #[PhpStormStubsElementAvailable(from: '8.0')] int $flags
): bool {}

/**
 * Delete all messages marked for deletion
 * @link https://php.net/manual/en/function.imap-expunge.php
 * @param resource $imap
 * @return bool <b>TRUE</b>.
 */
function imap_expunge(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap): bool {}

/**
 * Mark a message for deletion from current mailbox
 * @link https://php.net/manual/en/function.imap-delete.php
 * @param resource $imap
 * @param string $message_nums <p>
 * The message number
 * </p>
 * @param int $flags [optional] <p>
 * You can set the <b>FT_UID</b> which tells the function
 * to treat the <i>msg_number</i> argument as an
 * UID.
 * </p>
 * @return bool <b>TRUE</b>.
 */
function imap_delete(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $message_nums, int $flags = 0): bool {}

/**
 * Unmark the message which is marked deleted
 * @link https://php.net/manual/en/function.imap-undelete.php
 * @param resource $imap
 * @param string $message_nums <p>
 * The message number
 * </p>
 * @param int $flags [optional]
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function imap_undelete(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $message_nums, int $flags = 0): bool {}

/**
 * Check current mailbox
 * @link https://php.net/manual/en/function.imap-check.php
 * @param resource $imap
 * @return object|stdClass|false the information in an object with following properties:
 * <b>Date</b> - current system time formatted according to RFC2822
 * <b>Driver</b> - protocol used to access this mailbox:
 * POP3, IMAP, NNTP
 * <b>Mailbox</b> - the mailbox name
 * <b>Nmsgs</b> - number of messages in the mailbox
 * <b>Recent</b> - number of recent messages in the mailbox
 * </p>
 * <p>
 * Returns <b>FALSE</b> on failure.
 */
function imap_check(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap): stdClass|false {}

/**
 * Returns the list of mailboxes that matches the given text
 * @link https://php.net/manual/en/function.imap-listscan.php
 * @param resource $imap
 * @param string $reference <p>
 * <i>ref</i> should normally be just the server
 * specification as described in <b>imap_open</b>
 * </p>
 * @param string $pattern Specifies where in the mailbox hierarchy
 * to start searching.</p>There are two special characters you can
 * pass as part of the <i>pattern</i>:
 * &#x00027;*&#x00027; and &#x00027;&#37;&#x00027;.
 * &#x00027;*&#x00027; means to return all mailboxes. If you pass
 * <i>pattern</i> as &#x00027;*&#x00027;, you will
 * get a list of the entire mailbox hierarchy.
 * &#x00027;&#37;&#x00027;
 * means to return the current level only.
 * &#x00027;&#37;&#x00027; as the <i>pattern</i>
 * parameter will return only the top level
 * mailboxes; &#x00027;~/mail/&#37;&#x00027; on UW_IMAPD will return every mailbox in the ~/mail directory, but none in subfolders of that directory.</p>
 * @param string $content <p>
 * The searched string
 * </p>
 * @return array|false an array containing the names of the mailboxes that have
 * <i>content</i> in the text of the mailbox.
 */
function imap_listscan(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $reference, string $pattern, string $content): array|false {}

/**
 * Copy specified messages to a mailbox
 * @link https://php.net/manual/en/function.imap-mail-copy.php
 * @param resource $imap
 * @param string $message_nums <p>
 * <i>msglist</i> is a range not just message
 * numbers (as described in RFC2060).
 * </p>
 * @param string $mailbox <p>
 * The mailbox name, see <b>imap_open</b> for more
 * information
 * </p>
 * @param int $flags [optional] <p>
 * <i>options</i> is a bitmask of one or more of
 * <b>CP_UID</b> - the sequence numbers contain UIDS</p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function imap_mail_copy(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $message_nums, string $mailbox, int $flags = 0): bool {}

/**
 * Move specified messages to a mailbox
 * @link https://php.net/manual/en/function.imap-mail-move.php
 * @param resource $imap
 * @param string $message_nums <p>
 * <i>msglist</i> is a range not just message numbers
 * (as described in RFC2060).
 * </p>
 * @param string $mailbox <p>
 * The mailbox name, see <b>imap_open</b> for more
 * information
 * </p>
 * @param int $flags [optional] <p>
 * <i>options</i> is a bitmask and may contain the single option:
 * <b>CP_UID</b> - the sequence numbers contain UIDS</p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function imap_mail_move(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $message_nums, string $mailbox, int $flags = 0): bool {}

/**
 * Create a MIME message based on given envelope and body sections
 * @link https://php.net/manual/en/function.imap-mail-compose.php
 * @param array $envelope <p>
 * An associative array of headers fields. Valid keys are: "remail",
 * "return_path", "date", "from", "reply_to", "in_reply_to", "subject",
 * "to", "cc", "bcc", "message_id" and "custom_headers" (which contains
 * associative array of other headers).
 * </p>
 * @param array $bodies <p>
 * An indexed array of bodies
 * </p>
 * <p>
 * A body is an associative array which can consist of the following keys:
 * "type", "encoding", "charset", "type.parameters", "subtype", "id",
 * "description", "disposition.type", "disposition", "contents.data",
 * "lines", "bytes" and "md5".
 * </p>
 * @return string|false the MIME message.
 */
function imap_mail_compose(array $envelope, array $bodies): string|false {}

/**
 * Create a new mailbox
 * @link https://php.net/manual/en/function.imap-createmailbox.php
 * @param resource $imap
 * @param string $mailbox <p>
 * The mailbox name, see <b>imap_open</b> for more
 * information. Names containing international characters should be
 * encoded by <b>imap_utf7_encode</b>
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function imap_createmailbox(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $mailbox): bool {}

/**
 * Rename an old mailbox to new mailbox
 * @link https://php.net/manual/en/function.imap-renamemailbox.php
 * @param resource $imap
 * @param string $from <p>
 * The old mailbox name, see <b>imap_open</b> for more
 * information
 * </p>
 * @param string $to <p>
 * The new mailbox name, see <b>imap_open</b> for more
 * information
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function imap_renamemailbox(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $from, string $to): bool {}

/**
 * Delete a mailbox
 * @link https://php.net/manual/en/function.imap-deletemailbox.php
 * @param resource $imap
 * @param string $mailbox <p>
 * The mailbox name, see <b>imap_open</b> for more
 * information
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function imap_deletemailbox(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $mailbox): bool {}

/**
 * Subscribe to a mailbox
 * @link https://php.net/manual/en/function.imap-subscribe.php
 * @param resource $imap
 * @param string $mailbox <p>
 * The mailbox name, see <b>imap_open</b> for more
 * information
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function imap_subscribe(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $mailbox): bool {}

/**
 * Unsubscribe from a mailbox
 * @link https://php.net/manual/en/function.imap-unsubscribe.php
 * @param resource $imap
 * @param string $mailbox <p>
 * The mailbox name, see <b>imap_open</b> for more
 * information
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function imap_unsubscribe(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $mailbox): bool {}

/**
 * Append a string message to a specified mailbox
 * @link https://php.net/manual/en/function.imap-append.php
 * @param resource $imap
 * @param string $folder <p>
 * The mailbox name, see <b>imap_open</b> for more
 * information
 * </p>
 * @param string $message <p>
 * The message to be append, as a string
 * </p>
 * <p>
 * When talking to the Cyrus IMAP server, you must use "\r\n" as
 * your end-of-line terminator instead of "\n" or the operation will
 * fail
 * </p>
 * @param string $options [optional] <p>
 * If provided, the <i>options</i> will also be written
 * to the <i>mailbox</i>
 * </p>
 * @param string $internal_date [optional] <p>
 * If this parameter is set, it will set the INTERNALDATE on the appended message. The parameter should be a date string that conforms to the rfc2060 specifications for a date_time value.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function imap_append(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $folder, string $message, ?string $options = null, ?string $internal_date = null): bool {}

/**
 * Check if the IMAP stream is still active
 * @link https://php.net/manual/en/function.imap-ping.php
 * @param resource $imap
 * @return bool <b>TRUE</b> if the stream is still alive, <b>FALSE</b> otherwise.
 */
function imap_ping(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap): bool {}

/**
 * Decode BASE64 encoded text
 * @link https://php.net/manual/en/function.imap-base64.php
 * @param string $string <p>
 * The encoded text
 * </p>
 * @return string|false the decoded message as a string.
 */
function imap_base64(string $string): string|false {}

/**
 * Convert a quoted-printable string to an 8 bit string
 * @link https://php.net/manual/en/function.imap-qprint.php
 * @param string $string <p>
 * A quoted-printable string
 * </p>
 * @return string|false an 8 bits string.
 */
function imap_qprint(string $string): string|false {}

/**
 * Convert an 8bit string to a quoted-printable string
 * @link https://php.net/manual/en/function.imap-8bit.php
 * @param string $string <p>
 * The 8bit string to convert
 * </p>
 * @return string|false a quoted-printable string.
 */
function imap_8bit(string $string): string|false {}

/**
 * Convert an 8bit string to a base64 string
 * @link https://php.net/manual/en/function.imap-binary.php
 * @param string $string <p>
 * The 8bit string
 * </p>
 * @return string|false a base64 encoded string.
 */
function imap_binary(string $string): string|false {}

/**
 * Converts MIME-encoded text to UTF-8
 * @link https://php.net/manual/en/function.imap-utf8.php
 * @param string $mime_encoded_text <p>
 * A MIME encoded string. MIME encoding method and the UTF-8
 * specification are described in RFC2047 and RFC2044 respectively.
 * </p>
 * @return string an UTF-8 encoded string.
 */
function imap_utf8(string $mime_encoded_text): string {}

/**
 * Returns status information on a mailbox
 * @link https://php.net/manual/en/function.imap-status.php
 * @param resource $imap
 * @param string $mailbox <p>
 * The mailbox name, see <b>imap_open</b> for more
 * information
 * </p>
 * @param int $flags <p>
 * Valid flags are:
 * <b>SA_MESSAGES</b> - set $status->messages to the
 * number of messages in the mailbox
 * @return object This function returns an object containing status information.
 * The object has the following properties: messages,
 * recent, unseen,
 * uidnext, and uidvalidity.
 * </p>
 * <p>
 * flags is also set, which contains a bitmask which can
 * be checked against any of the above constants.</p>
 */
#[LanguageLevelTypeAware(['8.1' => 'stdClass|false'], default: 'object')]
function imap_status(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $mailbox, int $flags) {}

/**
 * @param $stream_id
 * @param $options
 */
function imap_status_current($stream_id, $options) {}

/**
 * Get information about the current mailbox
 * @link https://php.net/manual/en/function.imap-mailboxmsginfo.php
 * @param resource $imap
 * @return object|stdClass|false the information in an object with following properties:
 * <table>
 * Mailbox properties
 * <tr valign="top">
 * <td>Date</td>
 * <td>date of last change (current datetime)</td>
 * </tr>
 * <tr valign="top">
 * <td>Driver</td>
 * <td>driver</td>
 * </tr>
 * <tr valign="top">
 * <td>Mailbox</td>
 * <td>name of the mailbox</td>
 * </tr>
 * <tr valign="top">
 * <td>Nmsgs</td>
 * <td>number of messages</td>
 * </tr>
 * <tr valign="top">
 * <td>Recent</td>
 * <td>number of recent messages</td>
 * </tr>
 * <tr valign="top">
 * <td>Unread</td>
 * <td>number of unread messages</td>
 * </tr>
 * <tr valign="top">
 * <td>Deleted</td>
 * <td>number of deleted messages</td>
 * </tr>
 * <tr valign="top">
 * <td>Size</td>
 * <td>mailbox size</td>
 * </tr>
 * </table>
 * </p>
 * <p>
 * Returns <b>FALSE</b> on failure.
 */
function imap_mailboxmsginfo(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap): stdClass {}

/**
 * Sets flags on messages
 * @link https://php.net/manual/en/function.imap-setflag-full.php
 * @param resource $imap
 * @param string $sequence <p>
 * A sequence of message numbers. You can enumerate desired messages
 * with the X,Y syntax, or retrieve all messages
 * within an interval with the X:Y syntax
 * </p>
 * @param string $flag <p>
 * The flags which you can set are \Seen,
 * \Answered, \Flagged,
 * \Deleted, and \Draft as
 * defined by RFC2060.
 * </p>
 * @param int $options [optional] <p>
 * A bit mask that may contain the single option:
 * <b>ST_UID</b> - The sequence argument contains UIDs
 * instead of sequence numbers</p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function imap_setflag_full(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $sequence, string $flag, int $options = NIL): bool {}

/**
 * Clears flags on messages
 * @link https://php.net/manual/en/function.imap-clearflag-full.php
 * @param resource $imap
 * @param string $sequence <p>
 * A sequence of message numbers. You can enumerate desired messages
 * with the X,Y syntax, or retrieve all messages
 * within an interval with the X:Y syntax
 * </p>
 * @param string $flag <p>
 * The flags which you can unset are "\\Seen", "\\Answered", "\\Flagged",
 * "\\Deleted", and "\\Draft" (as defined by RFC2060)
 * </p>
 * @param int $options [optional] <p>
 * <i>options</i> are a bit mask and may contain
 * the single option:
 * <b>ST_UID</b> - The sequence argument contains UIDs
 * instead of sequence numbers</p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function imap_clearflag_full(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $sequence, string $flag, int $options = 0): bool {}

/**
 * Gets and sort messages
 * @link https://php.net/manual/en/function.imap-sort.php
 * @param resource $imap
 * @param int $criteria <p>
 * Criteria can be one (and only one) of the following:
 * <b>SORTDATE</b> - message Date</p>
 * @param bool $reverse <p>
 * Set this to 1 for reverse sorting
 * </p>
 * @param int $flags [optional] <p>
 * The <i>options</i> are a bitmask of one or more of the
 * following:
 * <b>SE_UID</b> - Return UIDs instead of sequence numbers</p>
 * @param string|null $search_criteria [optional]
 * @param string|null $charset [optional]
 * @return array|false an array of message numbers sorted by the given
 * parameters.
 */
function imap_sort(
    #[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap,
    int $criteria,
    #[LanguageLevelTypeAware(['8.0' => 'bool'], default: 'int')] $reverse,
    int $flags = 0,
    ?string $search_criteria = null,
    ?string $charset = null
): array|false {}

/**
 * This function returns the UID for the given message sequence number
 * @link https://php.net/manual/en/function.imap-uid.php
 * @param resource $imap
 * @param int $message_num <p>
 * The message number.
 * </p>
 * @return int|false The UID of the given message.
 */
function imap_uid(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, int $message_num): int|false {}

/**
 * Gets the message sequence number for the given UID
 * @link https://php.net/manual/en/function.imap-msgno.php
 * @param resource $imap
 * @param int $message_uid <p>
 * The message UID
 * </p>
 * @return int the message sequence number for the given
 * <i>uid</i>.
 */
function imap_msgno(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, int $message_uid): int {}

/**
 * Read the list of mailboxes
 * @link https://php.net/manual/en/function.imap-list.php
 * @param resource $imap
 * @param string $reference <p>
 * <i>ref</i> should normally be just the server
 * specification as described in <b>imap_open</b>.
 * </p>
 * @param string $pattern Specifies where in the mailbox hierarchy
 * to start searching.</p>There are two special characters you can
 * pass as part of the <i>pattern</i>:
 * &#x00027;*&#x00027; and &#x00027;&#37;&#x00027;.
 * &#x00027;*&#x00027; means to return all mailboxes. If you pass
 * <i>pattern</i> as &#x00027;*&#x00027;, you will
 * get a list of the entire mailbox hierarchy.
 * &#x00027;&#37;&#x00027;
 * means to return the current level only.
 * &#x00027;&#37;&#x00027; as the <i>pattern</i>
 * parameter will return only the top level
 * mailboxes; &#x00027;~/mail/&#37;&#x00027; on UW_IMAPD will return every mailbox in the ~/mail directory, but none in subfolders of that directory.</p>
 * @return array|false an array containing the names of the mailboxes.
 */
function imap_list(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $reference, string $pattern): array|false {}

/**
 * List all the subscribed mailboxes
 * @link https://php.net/manual/en/function.imap-lsub.php
 * @param resource $imap
 * @param string $reference <p>
 * <i>ref</i> should normally be just the server
 * specification as described in <b>imap_open</b>
 * </p>
 * @param string $pattern Specifies where in the mailbox hierarchy
 * to start searching.</p>There are two special characters you can
 * pass as part of the <i>pattern</i>:
 * &#x00027;*&#x00027; and &#x00027;&#37;&#x00027;.
 * &#x00027;*&#x00027; means to return all mailboxes. If you pass
 * <i>pattern</i> as &#x00027;*&#x00027;, you will
 * get a list of the entire mailbox hierarchy.
 * &#x00027;&#37;&#x00027;
 * means to return the current level only.
 * &#x00027;&#37;&#x00027; as the <i>pattern</i>
 * parameter will return only the top level
 * mailboxes; &#x00027;~/mail/&#37;&#x00027; on UW_IMAPD will return every mailbox in the ~/mail directory, but none in subfolders of that directory.</p>
 * @return array|false an array of all the subscribed mailboxes.
 */
function imap_lsub(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $reference, string $pattern): array|false {}

/**
 * Read an overview of the information in the headers of the given message
 * @link https://php.net/manual/en/function.imap-fetch-overview.php
 * @param resource $imap
 * @param string $sequence <p>
 * A message sequence description. You can enumerate desired messages
 * with the X,Y syntax, or retrieve all messages
 * within an interval with the X:Y syntax
 * </p>
 * @param int $flags [optional] <p>
 * <i>sequence</i> will contain a sequence of message
 * indices or UIDs, if this parameter is set to
 * <b>FT_UID</b>.
 * </p>
 * @return array|false an array of objects describing one message header each.
 * The object will only define a property if it exists. The possible
 * properties are:
 * subject - the messages subject
 * from - who sent it
 * to - recipient
 * date - when was it sent
 * message_id - Message-ID
 * references - is a reference to this message id
 * in_reply_to - is a reply to this message id
 * size - size in bytes
 * uid - UID the message has in the mailbox
 * msgno - message sequence number in the mailbox
 * recent - this message is flagged as recent
 * flagged - this message is flagged
 * answered - this message is flagged as answered
 * deleted - this message is flagged for deletion
 * seen - this message is flagged as already read
 * draft - this message is flagged as being a draft
 */
function imap_fetch_overview(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $sequence, int $flags = 0): array|false {}

/**
 * Returns all IMAP alert messages that have occurred
 * @link https://php.net/manual/en/function.imap-alerts.php
 * @return array|false an array of all of the IMAP alert messages generated or <b>FALSE</b> if
 * no alert messages are available.
 */
function imap_alerts(): array|false {}

/**
 * Returns all of the IMAP errors that have occurred
 * @link https://php.net/manual/en/function.imap-errors.php
 * @return array|false This function returns an array of all of the IMAP error messages
 * generated since the last <b>imap_errors</b> call,
 * or the beginning of the page. Returns <b>FALSE</b> if no error messages are
 * available.
 */
function imap_errors(): array|false {}

/**
 * Gets the last IMAP error that occurred during this page request
 * @link https://php.net/manual/en/function.imap-last-error.php
 * @return string|false the full text of the last IMAP error message that occurred on the
 * current page. Returns <b>FALSE</b> if no error messages are available.
 */
function imap_last_error(): string|false {}

/**
 * This function returns an array of messages matching the given search criteria
 * @link https://php.net/manual/en/function.imap-search.php
 * @param resource $imap
 * @param string $criteria <p>
 * A string, delimited by spaces, in which the following keywords are
 * allowed. Any multi-word arguments (e.g.
 * FROM "joey smith") must be quoted. Results will match
 * all <i>criteria</i> entries.
 * ALL - return all messages matching the rest of the criteria</p>
 * @param int $flags [optional] <p>
 * Valid values for <i>options</i> are
 * <b>SE_UID</b>, which causes the returned array to
 * contain UIDs instead of messages sequence numbers.
 * </p>
 * @param string $charset
 * @return array|false an array of message numbers or UIDs.
 * <p>
 * Return <b>FALSE</b> if it does not understand the search
 * <i>criteria</i> or no messages have been found.
 * </p>
 */
function imap_search(
    #[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap,
    string $criteria,
    int $flags = SE_FREE,
    string $charset = ""
): array|false {}

/**
 * Decodes a modified UTF-7 encoded string
 * @link https://php.net/manual/en/function.imap-utf7-decode.php
 * @param string $string <p>
 * A modified UTF-7 encoding string, as defined in RFC 2060, section 5.1.3 (original UTF-7
 * was defined in RFC1642).
 * </p>
 * @return string|false a string that is encoded in ISO-8859-1 and consists of the same
 * sequence of characters in <i>text</i>, or <b>FALSE</b>
 * if <i>text</i> contains invalid modified UTF-7 sequence
 * or <i>text</i> contains a character that is not part of
 * ISO-8859-1 character set.
 */
function imap_utf7_decode(string $string): string|false {}

/**
 * Converts ISO-8859-1 string to modified UTF-7 text
 * @link https://php.net/manual/en/function.imap-utf7-encode.php
 * @param string $string <p>
 * An ISO-8859-1 string.
 * </p>
 * @return string <i>data</i> encoded with the modified UTF-7
 * encoding as defined in RFC 2060,
 * section 5.1.3 (original UTF-7 was defined in RFC1642).
 */
function imap_utf7_encode(string $string): string {}

/**
 * Decode MIME header elements
 * @link https://php.net/manual/en/function.imap-mime-header-decode.php
 * @param string $string <p>
 * The MIME text
 * </p>
 * @return array|false The decoded elements are returned in an array of objects, where each
 * object has two properties, charset and
 * text.
 * </p>
 * <p>
 * If the element hasn't been encoded, and in other words is in
 * plain US-ASCII, the charset property of that element is
 * set to default.
 */
function imap_mime_header_decode(string $string): array|false {}

/**
 * Returns a tree of threaded message
 * @link https://php.net/manual/en/function.imap-thread.php
 * @param resource $imap
 * @param int $flags [optional]
 * @return array|false <b>imap_thread</b> returns an associative array containing
 * a tree of messages threaded by REFERENCES, or <b>FALSE</b>
 * on error.
 * </p>
 * <p>
 * Every message in the current mailbox will be represented by three entries
 * in the resulting array:
 * <p>
 * $thread["XX.num"] - current message number
 * </p>
 * <p>
 * $thread["XX.next"]
 * </p>
 * <p>
 * $thread["XX.branch"]
 * </p>
 */
function imap_thread(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, int $flags = SE_FREE): array|false {}

/**
 * Set or fetch imap timeout
 * @link https://php.net/manual/en/function.imap-timeout.php
 * @param int $timeout_type <p>
 * One of the following:
 * <b>IMAP_OPENTIMEOUT</b>,
 * <b>IMAP_READTIMEOUT</b>,
 * <b>IMAP_WRITETIMEOUT</b>, or
 * <b>IMAP_CLOSETIMEOUT</b>.
 * </p>
 * @param int $timeout [optional] <p>
 * The timeout, in seconds.
 * </p>
 * @return int|bool If the <i>timeout</i> parameter is set, this function
 * returns <b>TRUE</b> on success and <b>FALSE</b> on failure.
 * </p>
 * <p>
 * If <i>timeout</i> is not provided or evaluates to -1,
 * the current timeout value of <i>timeout_type</i> is
 * returned as an integer.
 */
function imap_timeout(int $timeout_type, int $timeout = -1): int|bool {}

/**
 * Retrieve the quota level settings, and usage statics per mailbox
 * @link https://php.net/manual/en/function.imap-get-quota.php
 * @param resource $imap
 * @param string $quota_root <p>
 * <i>quota_root</i> should normally be in the form of
 * user.name where name is the mailbox you wish to
 * retrieve information about.
 * </p>
 * @return array|false an array with integer values limit and usage for the given
 * mailbox. The value of limit represents the total amount of space
 * allowed for this mailbox. The usage value represents the mailboxes
 * current level of capacity. Will return <b>FALSE</b> in the case of failure.
 * </p>
 * <p>
 * As of PHP 4.3, the function more properly reflects the
 * functionality as dictated by the RFC2087.
 * The array return value has changed to support an unlimited number of returned
 * resources (i.e. messages, or sub-folders) with each named resource receiving
 * an individual array key. Each key value then contains an another array with
 * the usage and limit values within it.
 * </p>
 * <p>
 * For backwards compatibility reasons, the original access methods are
 * still available for use, although it is suggested to update.
 */
#[ArrayShape(["usage" => "int", "limit" => "int"])]
function imap_get_quota(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $quota_root): array|false {}

/**
 * Retrieve the quota settings per user
 * @link https://php.net/manual/en/function.imap-get-quotaroot.php
 * @param resource $imap
 * @param string $mailbox <p>
 * <i>quota_root</i> should normally be in the form of
 * which mailbox (i.e. INBOX).
 * </p>
 * @return array|false an array of integer values pertaining to the specified user
 * mailbox. All values contain a key based upon the resource name, and a
 * corresponding array with the usage and limit values within.
 * </p>
 * <p>
 * This function will return <b>FALSE</b> in the case of call failure, and an
 * array of information about the connection upon an un-parsable response
 * from the server.
 */
function imap_get_quotaroot(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $mailbox): array|false {}

/**
 * Sets a quota for a given mailbox
 * @link https://php.net/manual/en/function.imap-set-quota.php
 * @param resource $imap
 * @param string $quota_root <p>
 * The mailbox to have a quota set. This should follow the IMAP standard
 * format for a mailbox: user.name.
 * </p>
 * @param int $mailbox_size <p>
 * The maximum size (in KB) for the <i>quota_root</i>
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function imap_set_quota(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $quota_root, int $mailbox_size): bool {}

/**
 * Sets the ACL for a given mailbox
 * @link https://php.net/manual/en/function.imap-setacl.php
 * @param resource $imap
 * @param string $mailbox <p>
 * The mailbox name, see <b>imap_open</b> for more
 * information
 * </p>
 * @param string $user_id <p>
 * The user to give the rights to.
 * </p>
 * @param string $rights <p>
 * The rights to give to the user. Passing an empty string will delete
 * acl.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function imap_setacl(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $mailbox, string $user_id, string $rights): bool {}

/**
 * Gets the ACL for a given mailbox
 * @link https://php.net/manual/en/function.imap-getacl.php
 * @param resource $imap
 * @param string $mailbox <p>
 * The mailbox name, see <b>imap_open</b> for more
 * information
 * </p>
 * @return array|false an associative array of "folder" => "acl" pairs.
 */
function imap_getacl(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $mailbox): array|false {}

/**
 * @param $stream_id
 * @param $mailbox
 */
function imap_myrights($stream_id, $mailbox) {}

/**
 * @param $stream_id
 * @param $mailbox
 * @param $entry
 * @param $attr
 * @param $value
 */
function imap_setannotation($stream_id, $mailbox, $entry, $attr, $value) {}

/**
 * @param $stream_id
 * @param $mailbox
 * @param $entry
 * @param $attr
 */
function imap_getannotation($stream_id, $mailbox, $entry, $attr) {}

/**
 * Send an email message
 * @link https://php.net/manual/en/function.imap-mail.php
 * @param string $to <p>
 * The receiver
 * </p>
 * @param string $subject <p>
 * The mail subject
 * </p>
 * @param string $message <p>
 * The mail body, see <b>imap_mail_compose</b>
 * </p>
 * @param string $additional_headers [optional] <p>
 * As string with additional headers to be set on the mail
 * </p>
 * @param string $cc [optional]
 * @param string $bcc [optional] <p>
 * The receivers specified in <i>bcc</i> will get the
 * mail, but are excluded from the headers.
 * </p>
 * @param string $return_path [optional] <p>
 * Use this parameter to specify return path upon mail delivery failure.
 * This is useful when using PHP as a mail client for multiple users.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function imap_mail(string $to, string $subject, string $message, ?string $additional_headers = null, ?string $cc = null, ?string $bcc = null, ?string $return_path = null): bool {}

/**
 * Alias of <b>imap_headerinfo</b>
 * @link https://php.net/manual/en/function.imap-header.php
 * @param resource $stream_id An IMAP stream returned by imap_open().
 * @param int $msg_no The message number
 * @param int $from_length [optional] Number of characters for the fetchfrom property. Must be greater than or equal to zero.
 * @param int $subject_length [optional] Number of characters for the fetchsubject property Must be greater than or equal to zero.
 * @param $default_host [optional]
 * @return object Returns the information in an object with following properties:
 * <dl>
 * <dt>toaddress</dt><dd>full to: line, up to 1024 characters</dd>
 * <dt>to</dt><dd>an array of objects from the To: line, with the following properties: personal, adl, mailbox, and host</dd>
 * <dt>fromaddress</dt><dd>full from: line, up to 1024 characters</dd>
 * <dt>from</dt><dd>an array of objects from the From: line, with the following properties: personal, adl, mailbox, and host</dd>
 * <dt>ccaddress</dt><dd>full cc: line, up to 1024 characters</dd>
 * <dt>cc</dt><dd>an array of objects from the Cc: line, with the following properties: personal, adl, mailbox, and host</dd>
 * <dt>bccaddress</dt><dd>full bcc: line, up to 1024 characters</dd>
 * <dt>bcc</dt><dd>an array of objects from the Bcc: line, with the following properties: personal, adl, mailbox, and host</dd>
 * <dt>reply_toaddress</dt><dd>full Reply-To: line, up to 1024 characters</dd>
 * <dt>reply_to</dt><dd>an array of objects from the Reply-To: line, with the following properties: personal, adl, mailbox, and host</dd>
 * <dt>senderaddress</dt><dd>full sender: line, up to 1024 characters</dd>
 * <dt>sender</dt><dd>an array of objects from the Sender: line, with the following properties: personal, adl, mailbox, and host</dd>
 * <dt>return_pathaddress</dt><dd>full Return-Path: line, up to 1024 characters</dd>
 * <dt>return_path</dt><dd>an array of objects from the Return-Path: line, with the following properties: personal, adl, mailbox, and host</dd>
 * <dt>remail -</dt>
 * <dt>date</dt><dd>The message date as found in its headers</dd>
 * <dt>Date</dt><dd>Same as date</dd>
 * <dt>subject</dt><dd>The message subject</dd>
 * <dt>Subject</dt><dd>Same a subject</dd>
 * <dt>in_reply_to -</dt>
 * <dt>message_id -</dt>
 * <dt>newsgroups -</dt>
 * <dt>followup_to -</dt>
 * <dt>references -</dt>
 * <dt>Recent</dt><dd>R if recent and seen, N if recent and not seen, ' ' if not recent.</dd>
 * <dt>Unseen</dt><dd>U if not seen AND not recent, ' ' if seen OR not seen and recent</dd>
 * <dt>Flagged</dt><dd>F if flagged, ' ' if not flagged</dd>
 * <dt>Answered</dt><dd>A if answered, ' ' if unanswered</dd>
 * <dt>Deleted</dt><dd>D if deleted, ' ' if not deleted</dd>
 * <dt>Draft</dt><dd>X if draft, ' ' if not draft</dd>
 * <dt>Msgno</dt><dd>The message number</dd>
 * <dt>MailDate -</dt>
 * <dt>Size</dt><dd>The message size</dd>
 * <dt>udate</dt><dd>mail message date in Unix time</dd>
 * <dt>fetchfrom</dt><dd>from line formatted to fit fromlength characters</dd>
 * <dt>fetchsubject</dt><dd>subject line formatted to fit subjectlength characters</dd>
 * </dl>
 */
#[PhpStormStubsElementAvailable(to: '7.4')]
function imap_header($stream_id, $msg_no, $from_length = 0, $subject_length = 0, $default_host = null) {}

/**
 * Alias of <b>imap_list</b>
 * @link https://php.net/manual/en/function.imap-listmailbox.php
 * @param resource $imap
 * @param string $reference
 * @param string $pattern
 * @return array|false
 */
function imap_listmailbox(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $reference, string $pattern): array|false {}

/**
 * Read the list of mailboxes, returning detailed information on each one
 * @link https://php.net/manual/en/function.imap-getmailboxes.php
 * @param resource $imap
 * @param string $reference <p>
 * <i>ref</i> should normally be just the server
 * specification as described in <b>imap_open</b>
 * </p>
 * @param string $pattern Specifies where in the mailbox hierarchy
 * to start searching.</p>There are two special characters you can
 * pass as part of the <i>pattern</i>:
 * &#x00027;*&#x00027; and &#x00027;&#37;&#x00027;.
 * &#x00027;*&#x00027; means to return all mailboxes. If you pass
 * <i>pattern</i> as &#x00027;*&#x00027;, you will
 * get a list of the entire mailbox hierarchy.
 * &#x00027;&#37;&#x00027;
 * means to return the current level only.
 * &#x00027;&#37;&#x00027; as the <i>pattern</i>
 * parameter will return only the top level
 * mailboxes; &#x00027;~/mail/&#37;&#x00027; on UW_IMAPD will return every mailbox in the ~/mail directory, but none in subfolders of that directory.</p>
 * @return array|false an array of objects containing mailbox information. Each
 * object has the attributes <i>name</i>, specifying
 * the full name of the mailbox; <i>delimiter</i>,
 * which is the hierarchy delimiter for the part of the hierarchy
 * this mailbox is in; and
 * <i>attributes</i>. <i>Attributes</i>
 * is a bitmask that can be tested against:
 * <p>
 * <b>LATT_NOINFERIORS</b> - This mailbox contains, and may not contain any
 * "children" (there are no mailboxes below this one). Calling
 * <b>imap_createmailbox</b> will not work on this mailbox.
 * </p>
 * <p>
 * <b>LATT_NOSELECT</b> - This is only a container,
 * not a mailbox - you cannot open it.
 * </p>
 * <p>
 * <b>LATT_MARKED</b> - This mailbox is marked. This means that it may
 * contain new messages since the last time it was checked. Not provided by all IMAP
 * servers.
 * </p>
 * <p>
 * <b>LATT_UNMARKED</b> - This mailbox is not marked, does not contain new
 * messages. If either <b>MARKED</b> or <b>UNMARKED</b> is
 * provided, you can assume the IMAP server supports this feature for this mailbox.
 * </p>
 */
function imap_getmailboxes(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $reference, string $pattern): array|false {}

/**
 * Alias of <b>imap_listscan</b>
 * @link https://php.net/manual/en/function.imap-scanmailbox.php
 * @param $imap
 * @param $reference
 * @param $pattern
 * @param $content
 */
function imap_scanmailbox(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $reference, string $pattern, string $content): array|false {}

/**
 * Alias of <b>imap_lsub</b>
 * @link https://php.net/manual/en/function.imap-listsubscribed.php
 * @param resource $imap
 * @param string $reference
 * @param string $pattern
 * @return array|false
 */
function imap_listsubscribed(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $reference, string $pattern): array|false {}

/**
 * List all the subscribed mailboxes
 * @link https://php.net/manual/en/function.imap-getsubscribed.php
 * @param resource $imap
 * @param string $reference <p>
 * <i>ref</i> should normally be just the server
 * specification as described in <b>imap_open</b>
 * </p>
 * @param string $pattern Specifies where in the mailbox hierarchy
 * to start searching.</p>There are two special characters you can
 * pass as part of the <i>pattern</i>:
 * &#x00027;*&#x00027; and &#x00027;&#37;&#x00027;.
 * &#x00027;*&#x00027; means to return all mailboxes. If you pass
 * <i>pattern</i> as &#x00027;*&#x00027;, you will
 * get a list of the entire mailbox hierarchy.
 * &#x00027;&#37;&#x00027;
 * means to return the current level only.
 * &#x00027;&#37;&#x00027; as the <i>pattern</i>
 * parameter will return only the top level
 * mailboxes; &#x00027;~/mail/&#37;&#x00027; on UW_IMAPD will return every mailbox in the ~/mail directory, but none in subfolders of that directory.</p>
 * @return array|false an array of objects containing mailbox information. Each
 * object has the attributes <i>name</i>, specifying
 * the full name of the mailbox; <i>delimiter</i>,
 * which is the hierarchy delimiter for the part of the hierarchy
 * this mailbox is in; and
 * <i>attributes</i>. <i>Attributes</i>
 * is a bitmask that can be tested against:
 * <b>LATT_NOINFERIORS</b> - This mailbox has no
 * "children" (there are no mailboxes below this one).
 * <b>LATT_NOSELECT</b> - This is only a container,
 * not a mailbox - you cannot open it.
 * <b>LATT_MARKED</b> - This mailbox is marked.
 * Only used by UW-IMAPD.
 * <b>LATT_UNMARKED</b> - This mailbox is not marked.
 * Only used by UW-IMAPD.
 */
function imap_getsubscribed(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $reference, string $pattern): array|false {}

/**
 * (PHP 4, PHP 5)<br/>
 * Alias of imap_body()
 * @param resource $imap An IMAP stream returned by imap_open()
 * @param int $message_num message number
 * @param int $flags [optional] A bitmask with one or more of the following:<ul>
 * <li>FT_UID - The msg_number is a UID
 * <li>FT_PEEK - Do not set the \Seen flag if not already set
 * <li>FT_INTERNAL - The return string is in internal format, will not canonicalize to CRLF.</ul><p>
 * @return string|false body of the specified message
 */
function imap_fetchtext(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, int $message_num, int $flags = 0): string|false {}

/**
 * Alias of <b>imap_listscan</b>
 * @link https://php.net/manual/en/function.imap-scan.php
 * @param $imap
 * @param $reference
 * @param $pattern
 * @param $content
 */
function imap_scan(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $reference, string $pattern, string $content): array|false {}

/**
 * Alias of <b>imap_createmailbox</b>
 * @link https://php.net/manual/en/function.imap-create.php
 * @param $imap
 * @param $mailbox
 */
function imap_create(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $mailbox): bool {}

/**
 * Alias of <b>imap_renamemailbox</b>
 * @link https://php.net/manual/en/function.imap-rename.php
 * @param $imap
 * @param $from
 * @param $to
 */
function imap_rename(#[LanguageLevelTypeAware(['8.1' => 'IMAP\Connection'], default: 'resource')] $imap, string $from, string $to): bool {}

/**
 * Decode a modified UTF-7 string to UTF-8
 *
 * @link https://www.php.net/manual/en/function.imap-mutf7-to-utf8.php
 *
 * @param string $string
 * @return string|false
 */
function imap_mutf7_to_utf8(string $string): string|false {}

/**
 * Encode a UTF-8 string to modified UTF-7
 *
 * @link https://www.php.net/manual/en/function.imap-utf8-to-mutf7.php
 *
 * @param string $string
 * @return string|false
 */
function imap_utf8_to_mutf7(string $string): string|false {}

/**
 * @deprecated 8.1
 */
define('NIL', 0);
define('IMAP_OPENTIMEOUT', 1);
define('IMAP_READTIMEOUT', 2);
define('IMAP_WRITETIMEOUT', 3);
define('IMAP_CLOSETIMEOUT', 4);
define('OP_DEBUG', 1);

/**
 * Open mailbox read-only
 * @link https://php.net/manual/en/imap.constants.php
 */
define('OP_READONLY', 2);

/**
 * Don't use or update a .newsrc for news
 * (NNTP only)
 * @link https://php.net/manual/en/imap.constants.php
 */
define('OP_ANONYMOUS', 4);
define('OP_SHORTCACHE', 8);
define('OP_SILENT', 16);
define('OP_PROTOTYPE', 32);

/**
 * For IMAP and NNTP
 * names, open a connection but don't open a mailbox.
 * @link https://php.net/manual/en/imap.constants.php
 */
define('OP_HALFOPEN', 64);
define('OP_EXPUNGE', 128);
define('OP_SECURE', 256);

/**
 * silently expunge the mailbox before closing when
 * calling <b>imap_close</b>
 * @link https://php.net/manual/en/imap.constants.php
 */
define('CL_EXPUNGE', 32768);

/**
 * The parameter is a UID
 * @link https://php.net/manual/en/imap.constants.php
 */
define('FT_UID', 1);

/**
 * Do not set the \Seen flag if not already set
 * @link https://php.net/manual/en/imap.constants.php
 */
define('FT_PEEK', 2);
define('FT_NOT', 4);

/**
 * The return string is in internal format, will not canonicalize to CRLF.
 * @link https://php.net/manual/en/imap.constants.php
 */
define('FT_INTERNAL', 8);
define('FT_PREFETCHTEXT', 32);

/**
 * The sequence argument contains UIDs instead of sequence numbers
 * @link https://php.net/manual/en/imap.constants.php
 */
define('ST_UID', 1);
define('ST_SILENT', 2);
define('ST_SET', 4);

/**
 * the sequence numbers contain UIDS
 * @link https://php.net/manual/en/imap.constants.php
 */
define('CP_UID', 1);

/**
 * Delete the messages from the current mailbox after copying
 * with <b>imap_mail_copy</b>
 * @link https://php.net/manual/en/imap.constants.php
 */
define('CP_MOVE', 2);

/**
 * Return UIDs instead of sequence numbers
 * @link https://php.net/manual/en/imap.constants.php
 */
define('SE_UID', 1);
define('SE_FREE', 2);

/**
 * Don't prefetch searched messages
 * @link https://php.net/manual/en/imap.constants.php
 */
define('SE_NOPREFETCH', 4);
define('SO_FREE', 8);
define('SO_NOSERVER', 8);
define('SA_MESSAGES', 1);
define('SA_RECENT', 2);
define('SA_UNSEEN', 4);
define('SA_UIDNEXT', 8);
define('SA_UIDVALIDITY', 16);
define('SA_ALL', 31);

/**
 * This mailbox has no "children" (there are no
 * mailboxes below this one).
 * @link https://php.net/manual/en/imap.constants.php
 */
define('LATT_NOINFERIORS', 1);

/**
 * This is only a container, not a mailbox - you
 * cannot open it.
 * @link https://php.net/manual/en/imap.constants.php
 */
define('LATT_NOSELECT', 2);

/**
 * This mailbox is marked. Only used by UW-IMAPD.
 * @link https://php.net/manual/en/imap.constants.php
 */
define('LATT_MARKED', 4);

/**
 * This mailbox is not marked. Only used by
 * UW-IMAPD.
 * @link https://php.net/manual/en/imap.constants.php
 */
define('LATT_UNMARKED', 8);
define('LATT_REFERRAL', 16);
define('LATT_HASCHILDREN', 32);
define('LATT_HASNOCHILDREN', 64);

/**
 * Sort criteria for <b>imap_sort</b>:
 * message Date
 * @link https://php.net/manual/en/imap.constants.php
 */
define('SORTDATE', 0);

/**
 * Sort criteria for <b>imap_sort</b>:
 * arrival date
 * @link https://php.net/manual/en/imap.constants.php
 */
define('SORTARRIVAL', 1);

/**
 * Sort criteria for <b>imap_sort</b>:
 * mailbox in first From address
 * @link https://php.net/manual/en/imap.constants.php
 */
define('SORTFROM', 2);

/**
 * Sort criteria for <b>imap_sort</b>:
 * message subject
 * @link https://php.net/manual/en/imap.constants.php
 */
define('SORTSUBJECT', 3);

/**
 * Sort criteria for <b>imap_sort</b>:
 * mailbox in first To address
 * @link https://php.net/manual/en/imap.constants.php
 */
define('SORTTO', 4);

/**
 * Sort criteria for <b>imap_sort</b>:
 * mailbox in first cc address
 * @link https://php.net/manual/en/imap.constants.php
 */
define('SORTCC', 5);

/**
 * Sort criteria for <b>imap_sort</b>:
 * size of message in octets
 * @link https://php.net/manual/en/imap.constants.php
 */
define('SORTSIZE', 6);
define('TYPETEXT', 0);
define('TYPEMULTIPART', 1);
define('TYPEMESSAGE', 2);
define('TYPEAPPLICATION', 3);
define('TYPEAUDIO', 4);
define('TYPEIMAGE', 5);
define('TYPEVIDEO', 6);
define('TYPEMODEL', 7);
define('TYPEOTHER', 8);
define('ENC7BIT', 0);
define('ENC8BIT', 1);
define('ENCBINARY', 2);
define('ENCBASE64', 3);
define('ENCQUOTEDPRINTABLE', 4);
define('ENCOTHER', 5);

/**
 * Garbage collector, clear message cache elements.
 * @link https://php.net/manual/en/imap.constants.php
 */
define('IMAP_GC_ELT', 1);

/**
 * Garbage collector, clear envelopes and bodies.
 * @link https://php.net/manual/en/imap.constants.php
 */
define('IMAP_GC_ENV', 2);

/**
 * Garbage collector, clear texts.
 * @link https://php.net/manual/en/imap.constants.php
 */
define('IMAP_GC_TEXTS', 4);
