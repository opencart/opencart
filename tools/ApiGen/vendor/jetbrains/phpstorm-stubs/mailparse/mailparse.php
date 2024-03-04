<?php

// Start of mailparse v.

/**
 * (PECL mailparse >= 0.9.0)<br/>
 * Figures out the best way of encoding the content read from the given file pointer.
 * @link https://php.net/manual/en/function.mailparse-determine-best-xfer-encoding.php
 * @param resource $fp <p>
 * A valid file pointer, which must be seek-able.
 * </p>
 * @return string Returns one of the character encodings supported by the
 * {@link https://php.net/manual/en/ref.mbstring.php mbstring} module.
 */
function mailparse_determine_best_xfer_encoding($fp) {}

/**
 * (PECL mailparse >= 0.9.0)<br/>
 * Create a MIME mail resource.
 * @link https://php.net/manual/en/function.mailparse-msg-create.php
 * @return resource Returns a handle that can be used to parse a message.
 */
function mailparse_msg_create() {}

/**
 * (PECL mailparse >= 0.9.0)<br/>
 * Extracts/decodes a message section from the supplied filename.
 * The contents of the section will be decoded according to their transfer encoding - base64, quoted-printable and
 * uuencoded text are supported.
 * @link https://php.net/manual/en/function.mailparse-msg-extract-part-file.php
 * @param resource $mimemail <p>
 * A valid MIME resource, created with {@link https://php.net/manual/en/function.mailparse-msg-create.php mailparse_msg_create()}.
 * </p>
 * @param mixed $filename <p>
 * Can be a file name or a valid stream resource.
 * </p>
 * @param callable $callbackfunc [optional] <p>
 * If set, this must be either a valid callback that will be passed the extracted section, or <b>NULL</b> to make this
 * function return the extracted section.
 * </p>
 * <p>
 * If not specified, the contents will be sent to "stdout".
 * </p>
 * @return string|bool <p>
 * If callbackfunc is not <b>NULL</b> returns <b>TRUE</b> on success.
 * </p>
 * <p>
 * If callbackfunc is set to <b>NULL</b>, returns the extracted section as a string.
 * </p>
 * <p>
 * Returns FALSE on error.
 * </p>
 */
function mailparse_msg_extract_part_file($mimemail, $filename, $callbackfunc) {}

/**
 * (PECL mailparse >= 0.9.0)<br/>
 * Extracts/decodes a message section
 * @link https://php.net/manual/en/function.mailparse-msg-extract-part.php
 * @param resource $mimemail <p>
 * A valid MIME resource.
 * </p>
 * @param string $msgbody
 * @param callable $callbackfunc [optional]
 * @return void
 */
function mailparse_msg_extract_part($mimemail, $msgbody, $callbackfunc) {}

/**
 * (PECL mailparse >= 0.9.0)<br/>
 * Extracts a message section including headers without decoding the transfer encoding
 * @link https://php.net/manual/en/function.mailparse-msg-extract-whole-part-file.php
 * @param resource $mimemail <p>
 * A valid MIME resource
 * </p>
 * @param string $filename
 * @param callable $callbackfunc [optional]
 * @return string
 */
function mailparse_msg_extract_whole_part_file($mimemail, $filename, $callbackfunc) {}

/**
 * (PECL mailparse >= 0.9.0)<br/>
 * Frees a MIME resource.
 * @link https://php.net/manual/en/function.mailparse-msg-free.php
 * @param resource $mimemail <p>
 * A valid MIME resource allocated by
 * {@link https://php.net/manual/en/function.mailparse-msg-create.php mailparse_msg_create()} or
 * {@link https://php.net/manual/en/function.mailparse-msg-parse-file.php mailparse_msg_parse_file()}.
 * </p>
 * @return bool Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function mailparse_msg_free($mimemail) {}

/**
 * (PECL mailparse >= 0.9.0)<br/>
 * Returns an associative array of info about the message
 * @link https://php.net/manual/en/function.mailparse-msg-get-part-data.php
 * @param resource $mimemail <p>
 * A valid MIME resource.
 * </p>
 * @return array
 */
function mailparse_msg_get_part_data($mimemail) {}

/**
 * (PECL mailparse >= 0.9.0)<br/>
 * Returns a handle on a given section in a mimemessage
 * @link https://php.net/manual/en/function.mailparse-msg-get-part.php
 * @param resource $mimemail <p>
 * A valid MIME resource.
 * </p>
 * @param string $mimesection
 * @return resource
 */
function mailparse_msg_get_part($mimemail, $mimesection) {}

/**
 * (PECL mailparse >= 0.9.0)<br/>
 * Returns an array of mime section names in the supplied message
 * @link https://php.net/manual/en/function.mailparse-msg-get-structure.php
 * @param resource $mimemail <p>
 * A valid MIME resource.
 * </p>
 * @return array
 */
function mailparse_msg_get_structure($mimemail) {}

/**
 * (PECL mailparse >= 0.9.0)<br/>
 * Parses a file. This is the optimal way of parsing a mail file that you have on disk.
 * @link https://php.net/manual/en/function.mailparse-msg-parse-file.php
 * @param string $filename <p>
 * Path to the file holding the message. The file is opened and streamed through the parser.
 * </p>
 * @return resource|false Returns a MIME resource representing the structure, or <b>FALSE</b> on error.
 */
function mailparse_msg_parse_file($filename) {}

/**
 * (PECL mailparse >= 0.9.0)<br/>
 * Incrementally parse data into the supplied mime mail resource.
 * This function allow you to stream portions of a file at a time, rather than read and parse the whole thing.
 * @link https://php.net/manual/en/function.mailparse-msg-parse.php
 * @param resource $mimemail <p>
 * A valid MIME resource.
 * </p>
 * @param string $data
 * @return bool Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function mailparse_msg_parse($mimemail, $data) {}

/**
 * (PECL mailparse >= 0.9.0)<br/>
 * Parses a {@link http://www.faqs.org/rfcs/rfc822 RFC 822} compliant recipient list, such as that found in the To: header.
 * @link https://php.net/manual/en/function.mailparse-rfc822-parse-addresses.php
 * @param string $addresses <p>
 * A string containing addresses, like in: <pre>Wez Furlong <wez@example.com>, doe@example.com</pre>
 * Note: This string must not include the header name.
 * </p>
 * @return array <p>
 * Returns an array of associative arrays with the following keys for each recipient:
 * </p>
 * <table>
 * <tr valign="top">
 * <td>display</td>
 * <td>The recipient name, for display purpose. If this part is not set for a recipient, this key will hold the same value as address.</td>
 * </tr>
 * <tr valign="top">
 * <td>address</td>
 * <td>The email address</td>
 * </tr>
 * <tr valign="top">
 * <td>is_group</td>
 * <td><b>TRUE</b> if the recipient is a newsgroup, <b>FALSE</b> otherwise.</td>
 * </tr>
 * </table>
 */
function mailparse_rfc822_parse_addresses($addresses) {}

/**
 * (PECL mailparse >= 0.9.0)<br/>
 * Streams data from the source file pointer, apply encoding and write to the destination file pointer.
 * @link https://php.net/manual/en/function.mailparse-stream-encode.php
 * @param resource $sourcefp <p>
 * A valid file handle. The file is streamed through the parser.
 * </p>
 * @param resource $destfp <p>
 * The destination file handle in which the encoded data will be written.
 * </p>
 * @param string $encoding <p>
 * One of the character encodings supported by the {@link https://php.net/manual/en/ref.mbstring.php mbstring} module.
 * </p>
 * @return bool Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function mailparse_stream_encode($sourcefp, $destfp, $encoding) {}

/**
 * (PECL mailparse >= 0.9.0)<br/>
 * Scans the data from the given file pointer and extract each embedded uuencoded file into a temporary file.
 * @link https://php.net/manual/en/function.mailparse-uudecode-all.php
 * @param resource $fp <p>
 * A valid file pointer.
 * </p>
 * @return array <p>
 * Returns an array of associative arrays listing filename information.
 * </p>
 * <table>
 * <tr valign="top">
 * <td>filename</td>
 * <td>Path to the temporary file name created</td>
 * </tr>
 * <tr valign="top">
 * <td>origfilename</td>
 * <td>The original filename, for uuencoded parts only</td>
 * </tr>
 * </table>
 * <p>
 * The first filename entry is the message body. The next entries are the decoded uuencoded files.
 * </p>
 */
function mailparse_uudecode_all($fp) {}

define('MAILPARSE_EXTRACT_OUTPUT', 0);
define('MAILPARSE_EXTRACT_STREAM', 1);
define('MAILPARSE_EXTRACT_RETURN', 2);

// End of mailparse v.
