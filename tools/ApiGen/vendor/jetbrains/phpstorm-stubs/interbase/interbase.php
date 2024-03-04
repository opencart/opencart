<?php

// Start of interbase v.

/**
 * Open a connection to an InterBase database
 * @link https://php.net/manual/en/function.ibase-connect.php
 * @param string $database [optional] <p>
 * The database argument has to be a valid path to
 * database file on the server it resides on. If the server is not local,
 * it must be prefixed with either 'hostname:' (TCP/IP), '//hostname/'
 * (NetBEUI) or 'hostname@' (IPX/SPX), depending on the connection
 * protocol used.
 * </p>
 * @param string $username [optional] <p>
 * The user name. Can be set with the
 * ibase.default_user &php.ini; directive.
 * </p>
 * @param string $password [optional] <p>
 * The password for username. Can be set with the
 * ibase.default_password &php.ini; directive.
 * </p>
 * @param string $charset [optional] <p>
 * charset is the default character set for a
 * database.
 * </p>
 * @param int $buffers [optional] <p>
 * buffers is the number of database buffers to
 * allocate for the server-side cache. If 0 or omitted, server chooses
 * its own default.
 * </p>
 * @param int $dialect [optional] <p>
 * dialect selects the default SQL dialect for any
 * statement executed within a connection, and it defaults to the highest
 * one supported by client libraries. Functional only with InterBase 6
 * and up.
 * </p>
 * @param string $role [optional] <p>
 * Functional only with InterBase 5 and up.
 * </p>
 * @param int $sync [optional] <p>
 * </p>
 * @return resource|false an InterBase link identifier on success, or false on error.
 * @removed 7.4
 */
function ibase_connect($database = null, $username = null, $password = null, $charset = null, $buffers = null, $dialect = null, $role = null, $sync = null) {}

/**
 * Open a persistent connection to an InterBase database
 * @link https://php.net/manual/en/function.ibase-pconnect.php
 * @param string $database [optional] <p>
 * The database argument has to be a valid path to
 * database file on the server it resides on. If the server is not local,
 * it must be prefixed with either 'hostname:' (TCP/IP), '//hostname/'
 * (NetBEUI) or 'hostname@' (IPX/SPX), depending on the connection
 * protocol used.
 * </p>
 * @param string $username [optional] <p>
 * The user name. Can be set with the
 * ibase.default_user &php.ini; directive.
 * </p>
 * @param string $password [optional] <p>
 * The password for username. Can be set with the
 * ibase.default_password &php.ini; directive.
 * </p>
 * @param string $charset [optional] <p>
 * charset is the default character set for a
 * database.
 * </p>
 * @param int $buffers [optional] <p>
 * buffers is the number of database buffers to
 * allocate for the server-side cache. If 0 or omitted, server chooses
 * its own default.
 * </p>
 * @param int $dialect [optional] <p>
 * dialect selects the default SQL dialect for any
 * statement executed within a connection, and it defaults to the highest
 * one supported by client libraries. Functional only with InterBase 6
 * and up.
 * </p>
 * @param string $role [optional] <p>
 * Functional only with InterBase 5 and up.
 * </p>
 * @param int $sync [optional] <p>
 * </p>
 * @return resource|false an InterBase link identifier on success, or false on error.
 * @removed 7.4
 */
function ibase_pconnect($database = null, $username = null, $password = null, $charset = null, $buffers = null, $dialect = null, $role = null, $sync = null) {}

/**
 * Close a connection to an InterBase database
 * @link https://php.net/manual/en/function.ibase-close.php
 * @param resource $connection_id [optional] <p>
 * An InterBase link identifier returned from
 * ibase_connect. If omitted, the last opened link
 * is assumed.
 * </p>
 * @return bool true on success or false on failure.
 * @removed 7.4
 */
function ibase_close($connection_id = null) {}

/**
 * Drops a database
 * @link https://php.net/manual/en/function.ibase-drop-db.php
 * @param resource $connection [optional] <p>
 * An InterBase link identifier. If omitted, the last opened link is
 * assumed.
 * </p>
 * @return bool true on success or false on failure.
 * @removed 7.4
 */
function ibase_drop_db($connection = null) {}

/**
 * Execute a query on an InterBase database
 * @link https://php.net/manual/en/function.ibase-query.php
 * @param resource $link_identifier [optional] <p>
 * An InterBase link identifier. If omitted, the last opened link is
 * assumed.
 * </p>
 * @param string $query <p>
 * An InterBase query.
 * </p>
 * @param int $bind_args [optional] <p>
 * </p>
 * @return resource|bool If the query raises an error, returns false. If it is successful and
 * there is a (possibly empty) result set (such as with a SELECT query),
 * returns a result identifier. If the query was successful and there were
 * no results, returns true.
 * </p>
 * <p>
 * In PHP 5.0.0 and up, this function will return the number of rows
 * affected by the query for INSERT, UPDATE and DELETE statements. In order
 * to retain backward compatibility, it will return true for these
 * statements if the query succeeded without affecting any rows.
 * @removed 7.4
 */
function ibase_query($link_identifier = null, $query, $bind_args = null) {}

/**
 * Fetch a row from an InterBase database
 * @link https://php.net/manual/en/function.ibase-fetch-row.php
 * @param resource $result_identifier <p>
 * An InterBase result identifier.
 * </p>
 * @param int $fetch_flag [optional] <p>
 * fetch_flag is a combination of the constants
 * IBASE_TEXT and IBASE_UNIXTIME
 * ORed together. Passing IBASE_TEXT will cause this
 * function to return BLOB contents instead of BLOB ids. Passing
 * IBASE_UNIXTIME will cause this function to return
 * date/time values as Unix timestamps instead of as formatted strings.
 * </p>
 * @return array|false an array that corresponds to the fetched row, or false if there
 * are no more rows. Each result column is stored in an array offset,
 * starting at offset 0.
 * @removed 7.4
 */
function ibase_fetch_row($result_identifier, $fetch_flag = null) {}

/**
 * Fetch a result row from a query as an associative array
 * @link https://php.net/manual/en/function.ibase-fetch-assoc.php
 * @param resource $result <p>
 * The result handle.
 * </p>
 * @param int $fetch_flag [optional] <p>
 * fetch_flag is a combination of the constants
 * IBASE_TEXT and IBASE_UNIXTIME
 * ORed together. Passing IBASE_TEXT will cause this
 * function to return BLOB contents instead of BLOB ids. Passing
 * IBASE_UNIXTIME will cause this function to return
 * date/time values as Unix timestamps instead of as formatted strings.
 * </p>
 * @return array|false an associative array that corresponds to the fetched row.
 * Subsequent calls will return the next row in the result set, or false if
 * there are no more rows.
 * @removed 7.4
 */
function ibase_fetch_assoc($result, $fetch_flag = null) {}

/**
 * Get an object from a InterBase database
 * @link https://php.net/manual/en/function.ibase-fetch-object.php
 * @param resource $result_id <p>
 * An InterBase result identifier obtained either by
 * ibase_query or ibase_execute.
 * </p>
 * @param int $fetch_flag [optional] <p>
 * fetch_flag is a combination of the constants
 * IBASE_TEXT and IBASE_UNIXTIME
 * ORed together. Passing IBASE_TEXT will cause this
 * function to return BLOB contents instead of BLOB ids. Passing
 * IBASE_UNIXTIME will cause this function to return
 * date/time values as Unix timestamps instead of as formatted strings.
 * </p>
 * @return object|false an object with the next row information, or false if there are
 * no more rows.
 * @removed 7.4
 */
function ibase_fetch_object($result_id, $fetch_flag = null) {}

/**
 * Free a result set
 * @link https://php.net/manual/en/function.ibase-free-result.php
 * @param resource $result_identifier <p>
 * A result set created by ibase_query or
 * ibase_execute.
 * </p>
 * @return bool true on success or false on failure.
 * @removed 7.4
 */
function ibase_free_result($result_identifier) {}

/**
 * Assigns a name to a result set
 * @link https://php.net/manual/en/function.ibase-name-result.php
 * @param resource $result <p>
 * An InterBase result set.
 * </p>
 * @param string $name <p>
 * The name to be assigned.
 * </p>
 * @return bool true on success or false on failure.
 * @removed 7.4
 */
function ibase_name_result($result, $name) {}

/**
 * Prepare a query for later binding of parameter placeholders and execution
 * @link https://php.net/manual/en/function.ibase-prepare.php
 * @param string $query <p>
 * An InterBase query.
 * </p>
 * @return resource|false a prepared query handle, or false on error.
 * @removed 7.4
 */
function ibase_prepare($query) {}

/**
 * Execute a previously prepared query
 * @link https://php.net/manual/en/function.ibase-execute.php
 * @param resource $query <p>
 * An InterBase query prepared by ibase_prepare.
 * </p>
 * @param mixed ...$bind_arg [optional] <p>
 * </p>
 * @return resource|bool If the query raises an error, returns false. If it is successful and
 * there is a (possibly empty) result set (such as with a SELECT query),
 * returns a result identifier. If the query was successful and there were
 * no results, returns true.
 * </p>
 * <p>
 * In PHP 5.0.0 and up, this function returns the number of rows affected by
 * the query (if > 0 and applicable to the statement type). A query that
 * succeeded, but did not affect any rows (e.g. an UPDATE of a non-existent
 * record) will return true.
 * @removed 7.4
 */
function ibase_execute($query, ...$bind_arg) {}

/**
 * Free memory allocated by a prepared query
 * @link https://php.net/manual/en/function.ibase-free-query.php
 * @param resource $query <p>
 * A query prepared with ibase_prepare.
 * </p>
 * @return bool true on success or false on failure.
 * @removed 7.4
 */
function ibase_free_query($query) {}

/**
 * Increments the named generator and returns its new value
 * @link https://php.net/manual/en/function.ibase-gen-id.php
 * @param string $generator
 * @param int $increment [optional]
 * @param resource $link_identifier [optional]
 * @return mixed new generator value as integer, or as string if the value is too big.
 * @removed 7.4
 */
function ibase_gen_id($generator, $increment = null, $link_identifier = null) {}

/**
 * Get the number of fields in a result set
 * @link https://php.net/manual/en/function.ibase-num-fields.php
 * @param resource $result_id <p>
 * An InterBase result identifier.
 * </p>
 * @return int the number of fields as an integer.
 * @removed 7.4
 */
function ibase_num_fields($result_id) {}

/**
 * Return the number of parameters in a prepared query
 * @link https://php.net/manual/en/function.ibase-num-params.php
 * @param resource $query <p>
 * The prepared query handle.
 * </p>
 * @return int the number of parameters as an integer.
 * @removed 7.4
 */
function ibase_num_params($query) {}

/**
 * Return the number of rows that were affected by the previous query
 * @link https://php.net/manual/en/function.ibase-affected-rows.php
 * @param resource $link_identifier [optional] <p>
 * A transaction context. If link_identifier is a
 * connection resource, its default transaction is used.
 * </p>
 * @return int the number of rows as an integer.
 * @removed 7.4
 */
function ibase_affected_rows($link_identifier = null) {}

/**
 * Get information about a field
 * @link https://php.net/manual/en/function.ibase-field-info.php
 * @param resource $result <p>
 * An InterBase result identifier.
 * </p>
 * @param int $field_number <p>
 * Field offset.
 * </p>
 * @return array an array with the following keys: name,
 * alias, relation,
 * length and type.
 * @removed 7.4
 */
function ibase_field_info($result, $field_number) {}

/**
 * Return information about a parameter in a prepared query
 * @link https://php.net/manual/en/function.ibase-param-info.php
 * @param resource $query <p>
 * An InterBase prepared query handle.
 * </p>
 * @param int $param_number <p>
 * Parameter offset.
 * </p>
 * @return array an array with the following keys: name,
 * alias, relation,
 * length and type.
 * @removed 7.4
 */
function ibase_param_info($query, $param_number) {}

/**
 * Begin a transaction
 * @link https://php.net/manual/en/function.ibase-trans.php
 * @param int $trans_args [optional] <p>
 * trans_args can be a combination of
 * IBASE_READ,
 * IBASE_WRITE,
 * IBASE_COMMITTED,
 * IBASE_CONSISTENCY,
 * IBASE_CONCURRENCY,
 * IBASE_REC_VERSION,
 * IBASE_REC_NO_VERSION,
 * IBASE_WAIT and
 * IBASE_NOWAIT.
 * </p>
 * @param resource $link_identifier [optional] <p>
 * An InterBase link identifier. If omitted, the last opened link is
 * assumed.
 * </p>
 * @return resource|false a transaction handle, or false on error.
 * @removed 7.4
 */
function ibase_trans($trans_args = null, $link_identifier = null) {}

/**
 * Commit a transaction
 * @link https://php.net/manual/en/function.ibase-commit.php
 * @param resource $link_or_trans_identifier [optional] <p>
 * If called without an argument, this function commits the default
 * transaction of the default link. If the argument is a connection
 * identifier, the default transaction of the corresponding connection
 * will be committed. If the argument is a transaction identifier, the
 * corresponding transaction will be committed.
 * </p>
 * @return bool true on success or false on failure.
 * @removed 7.4
 */
function ibase_commit($link_or_trans_identifier = null) {}

/**
 * Roll back a transaction
 * @link https://php.net/manual/en/function.ibase-rollback.php
 * @param resource $link_or_trans_identifier [optional] <p>
 * If called without an argument, this function rolls back the default
 * transaction of the default link. If the argument is a connection
 * identifier, the default transaction of the corresponding connection
 * will be rolled back. If the argument is a transaction identifier, the
 * corresponding transaction will be rolled back.
 * </p>
 * @return bool true on success or false on failure.
 * @removed 7.4
 */
function ibase_rollback($link_or_trans_identifier = null) {}

/**
 * Commit a transaction without closing it
 * @link https://php.net/manual/en/function.ibase-commit-ret.php
 * @param resource $link_or_trans_identifier [optional] <p>
 * If called without an argument, this function commits the default
 * transaction of the default link. If the argument is a connection
 * identifier, the default transaction of the corresponding connection
 * will be committed. If the argument is a transaction identifier, the
 * corresponding transaction will be committed. The transaction context
 * will be retained, so statements executed from within this transaction
 * will not be invalidated.
 * </p>
 * @return bool true on success or false on failure.
 * @removed 7.4
 */
function ibase_commit_ret($link_or_trans_identifier = null) {}

/**
 * Roll back a transaction without closing it
 * @link https://php.net/manual/en/function.ibase-rollback-ret.php
 * @param resource $link_or_trans_identifier [optional] <p>
 * If called without an argument, this function rolls back the default
 * transaction of the default link. If the argument is a connection
 * identifier, the default transaction of the corresponding connection
 * will be rolled back. If the argument is a transaction identifier, the
 * corresponding transaction will be rolled back. The transaction context
 * will be retained, so statements executed from within this transaction
 * will not be invalidated.
 * </p>
 * @return bool true on success or false on failure.
 * @removed 7.4
 */
function ibase_rollback_ret($link_or_trans_identifier = null) {}

/**
 * Return blob length and other useful info
 * @link https://php.net/manual/en/function.ibase-blob-info.php
 * @param resource $link_identifier <p>
 * An InterBase link identifier. If omitted, the last opened link is
 * assumed.
 * </p>
 * @param string $blob_id <p>
 * A BLOB id.
 * </p>
 * @return array an array containing information about a BLOB. The information returned
 * consists of the length of the BLOB, the number of segments it contains, the size
 * of the largest segment, and whether it is a stream BLOB or a segmented BLOB.
 * @removed 7.4
 */
function ibase_blob_info($link_identifier, $blob_id) {}

/**
 * Create a new blob for adding data
 * @link https://php.net/manual/en/function.ibase-blob-create.php
 * @param resource $link_identifier [optional] <p>
 * An InterBase link identifier. If omitted, the last opened link is
 * assumed.
 * </p>
 * @return resource|false a BLOB handle for later use with
 * ibase_blob_add or false on failure.
 * @removed 7.4
 */
function ibase_blob_create($link_identifier = null) {}

/**
 * Add data into a newly created blob
 * @link https://php.net/manual/en/function.ibase-blob-add.php
 * @param resource $blob_handle <p>
 * A blob handle opened with ibase_blob_create.
 * </p>
 * @param string $data <p>
 * The data to be added.
 * </p>
 * @return void
 * @removed 7.4
 */
function ibase_blob_add($blob_handle, $data) {}

/**
 * Cancel creating blob
 * @link https://php.net/manual/en/function.ibase-blob-cancel.php
 * @param resource $blob_handle <p>
 * A BLOB handle opened with ibase_blob_create.
 * </p>
 * @return bool true on success or false on failure.
 * @removed 7.4
 */
function ibase_blob_cancel($blob_handle) {}

/**
 * Close blob
 * @link https://php.net/manual/en/function.ibase-blob-close.php
 * @param resource $blob_handle <p>
 * A BLOB handle opened with ibase_blob_create or
 * ibase_blob_open.
 * </p>
 * @return mixed If the BLOB was being read, this function returns true on success, if
 * the BLOB was being written to, this function returns a string containing
 * the BLOB id that has been assigned to it by the database. On failure, this
 * function returns false.
 * @removed 7.4
 */
function ibase_blob_close($blob_handle) {}

/**
 * Open blob for retrieving data parts
 * @link https://php.net/manual/en/function.ibase-blob-open.php
 * @param resource $link_identifier <p>
 * An InterBase link identifier. If omitted, the last opened link is
 * assumed.
 * </p>
 * @param string $blob_id <p>
 * A BLOB id.
 * </p>
 * @return resource|false a BLOB handle for later use with
 * ibase_blob_get or false on failure.
 * @removed 7.4
 */
function ibase_blob_open($link_identifier, $blob_id) {}

/**
 * Get len bytes data from open blob
 * @link https://php.net/manual/en/function.ibase-blob-get.php
 * @param resource $blob_handle <p>
 * A BLOB handle opened with ibase_blob_open.
 * </p>
 * @param int $len <p>
 * Size of returned data.
 * </p>
 * @return string|false at most len bytes from the BLOB, or false
 * on failure.
 * @removed 7.4
 */
function ibase_blob_get($blob_handle, $len) {}

/**
 * Output blob contents to browser
 * @link https://php.net/manual/en/function.ibase-blob-echo.php
 * @param string $blob_id <p>
 * </p>
 * @return bool true on success or false on failure.
 * @removed 7.4
 */
function ibase_blob_echo($blob_id) {}

/**
 * Create blob, copy file in it, and close it
 * @link https://php.net/manual/en/function.ibase-blob-import.php
 * @param resource $link_identifier <p>
 * An InterBase link identifier. If omitted, the last opened link is
 * assumed.
 * </p>
 * @param resource $file_handle <p>
 * The file handle is a handle returned by fopen.
 * </p>
 * @return string|false the BLOB id on success, or false on error.
 * @removed 7.4
 */
function ibase_blob_import($link_identifier, $file_handle) {}

/**
 * Return error messages
 * @link https://php.net/manual/en/function.ibase-errmsg.php
 * @return string|false the error message as a string, or false if no error occurred.
 * @removed 7.4
 */
function ibase_errmsg() {}

/**
 * Return an error code
 * @link https://php.net/manual/en/function.ibase-errcode.php
 * @return int|false the error code as an integer, or false if no error occurred.
 * @removed 7.4
 */
function ibase_errcode() {}

/**
 * Add a user to a security database (only for IB6 or later)
 * @link https://php.net/manual/en/function.ibase-add-user.php
 * @param resource $service_handle
 * @param string $user_name
 * @param string $password
 * @param string $first_name [optional]
 * @param string $middle_name [optional]
 * @param string $last_name [optional]
 * @return bool true on success or false on failure.
 * @removed 7.4
 */
function ibase_add_user($service_handle, $user_name, $password, $first_name = null, $middle_name = null, $last_name = null) {}

/**
 * Modify a user to a security database (only for IB6 or later)
 * @link https://php.net/manual/en/function.ibase-modify-user.php
 * @param resource $service_handle
 * @param string $user_name
 * @param string $password
 * @param string $first_name [optional]
 * @param string $middle_name [optional]
 * @param string $last_name [optional]
 * @return bool true on success or false on failure.
 * @removed 7.4
 */
function ibase_modify_user($service_handle, $user_name, $password, $first_name = null, $middle_name = null, $last_name = null) {}

/**
 * Delete a user from a security database (only for IB6 or later)
 * @link https://php.net/manual/en/function.ibase-delete-user.php
 * @param resource $service_handle
 * @param string $user_name
 * @return bool true on success or false on failure.
 * @removed 7.4
 */
function ibase_delete_user($service_handle, $user_name) {}

/**
 * Connect to the service manager
 * @link https://php.net/manual/en/function.ibase-service-attach.php
 * @param string $host
 * @param string $dba_username
 * @param string $dba_password
 * @return resource|false
 * @removed 7.4
 */
function ibase_service_attach($host, $dba_username, $dba_password) {}

/**
 * Disconnect from the service manager
 * @link https://php.net/manual/en/function.ibase-service-detach.php
 * @param resource $service_handle
 * @return bool true on success or false on failure.
 * @removed 7.4
 */
function ibase_service_detach($service_handle) {}

/**
 * Initiates a backup task in the service manager and returns immediately
 * @link https://php.net/manual/en/function.ibase-backup.php
 * @param resource $service_handle
 * @param string $source_db
 * @param string $dest_file
 * @param int $options [optional]
 * @param bool $verbose [optional]
 * @return mixed
 * @removed 7.4
 */
function ibase_backup($service_handle, $source_db, $dest_file, $options = null, $verbose = null) {}

/**
 * Initiates a restore task in the service manager and returns immediately
 * @link https://php.net/manual/en/function.ibase-restore.php
 * @param resource $service_handle
 * @param string $source_file
 * @param string $dest_db
 * @param int $options [optional]
 * @param bool $verbose [optional]
 * @return mixed
 * @removed 7.4
 */
function ibase_restore($service_handle, $source_file, $dest_db, $options = null, $verbose = null) {}

/**
 * Execute a maintenance command on the database server
 * @link https://php.net/manual/en/function.ibase-maintain-db.php
 * @param resource $service_handle
 * @param string $db
 * @param int $action
 * @param int $argument [optional]
 * @return bool true on success or false on failure.
 * @removed 7.4
 */
function ibase_maintain_db($service_handle, $db, $action, $argument = null) {}

/**
 * Request statistics about a database
 * @link https://php.net/manual/en/function.ibase-db-info.php
 * @param resource $service_handle
 * @param string $db
 * @param int $action
 * @param int $argument [optional]
 * @return string
 * @removed 7.4
 */
function ibase_db_info($service_handle, $db, $action, $argument = null) {}

/**
 * Request information about a database server
 * @link https://php.net/manual/en/function.ibase-server-info.php
 * @param resource $service_handle
 * @param int $action
 * @return string
 * @removed 7.4
 */
function ibase_server_info($service_handle, $action) {}

/**
 * Wait for an event to be posted by the database
 * @link https://php.net/manual/en/function.ibase-wait-event.php
 * @param string $event_name1 <p>
 * The event name.
 * </p>
 * @param string $event_name2 [optional] <p>
 * </p>
 * @param string ...$_ [optional]
 * @return string the name of the event that was posted.
 * @removed 7.4
 */
function ibase_wait_event($event_name1, $event_name2 = null, ...$_) {}

/**
 * Register a callback function to be called when events are posted
 * @link https://php.net/manual/en/function.ibase-set-event-handler.php
 * @param callable $event_handler <p>
 * The callback is called with the event name and the link resource as
 * arguments whenever one of the specified events is posted by the
 * database.
 * </p>
 * <p>
 * The callback must return false if the event handler should be
 * canceled. Any other return value is ignored. This function accepts up
 * to 15 event arguments.
 * </p>
 * @param string $event_name1 <p>
 * An event name.
 * </p>
 * @param string $event_name2 [optional] <p>
 * At most 15 events allowed.
 * </p>
 * @param string ...$_ [optional]
 * @return resource The return value is an event resource. This resource can be used to free
 * the event handler using ibase_free_event_handler.
 * @removed 7.4
 */
function ibase_set_event_handler($event_handler, $event_name1, $event_name2 = null, ...$_) {}

/**
 * Cancels a registered event handler
 * @link https://php.net/manual/en/function.ibase-free-event-handler.php
 * @param resource $event <p>
 * An event resource, created by
 * ibase_set_event_handler.
 * </p>
 * @return bool true on success or false on failure.
 * @removed 7.4
 */
function ibase_free_event_handler($event) {}

/**
 * This is an alias of ibase_connect
 * Open a connection to an InterBase database
 * @link https://php.net/manual/en/function.fbird-connect.php
 * @param string $database [optional] <p>
 * The database argument has to be a valid path to
 * database file on the server it resides on. If the server is not local,
 * it must be prefixed with either 'hostname:' (TCP/IP), '//hostname/'
 * (NetBEUI) or 'hostname@' (IPX/SPX), depending on the connection
 * protocol used.
 * </p>
 * @param string $username [optional] <p>
 * The user name. Can be set with the
 * fbird.default_user &php.ini; directive.
 * </p>
 * @param string $password [optional] <p>
 * The password for username. Can be set with the
 * fbird.default_password &php.ini; directive.
 * </p>
 * @param string $charset [optional] <p>
 * charset is the default character set for a
 * database.
 * </p>
 * @param int $buffers [optional] <p>
 * buffers is the number of database buffers to
 * allocate for the server-side cache. If 0 or omitted, server chooses
 * its own default.
 * </p>
 * @param int $dialect [optional] <p>
 * dialect selects the default SQL dialect for any
 * statement executed within a connection, and it defaults to the highest
 * one supported by client libraries. Functional only with InterBase 6
 * and up.
 * </p>
 * @param string $role [optional] <p>
 * Functional only with InterBase 5 and up.
 * </p>
 * @param int $sync [optional] <p>
 * </p>
 * @return resource|false an InterBase link identifier on success, or false on error.
 */
function fbird_connect($database = null, $username = null, $password = null, $charset = null, $buffers = null, $dialect = null, $role = null, $sync = null) {}

/**
 * This is an alias of ibase_pconnect
 * Open a persistent connection to an InterBase database
 * @link https://php.net/manual/en/function.fbird-pconnect.php
 * @param string $database [optional] <p>
 * The database argument has to be a valid path to
 * database file on the server it resides on. If the server is not local,
 * it must be prefixed with either 'hostname:' (TCP/IP), '//hostname/'
 * (NetBEUI) or 'hostname@' (IPX/SPX), depending on the connection
 * protocol used.
 * </p>
 * @param string $username [optional] <p>
 * The user name. Can be set with the
 * fbird.default_user &php.ini; directive.
 * </p>
 * @param string $password [optional] <p>
 * The password for username. Can be set with the
 * fbird.default_password &php.ini; directive.
 * </p>
 * @param string $charset [optional] <p>
 * charset is the default character set for a
 * database.
 * </p>
 * @param int $buffers [optional] <p>
 * buffers is the number of database buffers to
 * allocate for the server-side cache. If 0 or omitted, server chooses
 * its own default.
 * </p>
 * @param int $dialect [optional] <p>
 * dialect selects the default SQL dialect for any
 * statement executed within a connection, and it defaults to the highest
 * one supported by client libraries. Functional only with InterBase 6
 * and up.
 * </p>
 * @param string $role [optional] <p>
 * Functional only with InterBase 5 and up.
 * </p>
 * @param int $sync [optional] <p>
 * </p>
 * @return resource|false an InterBase link identifier on success, or false on error.
 */
function fbird_pconnect($database = null, $username = null, $password = null, $charset = null, $buffers = null, $dialect = null, $role = null, $sync = null) {}

/**
 * This is an alias of ibase_close
 * Close a connection to an InterBase database
 * @link https://php.net/manual/en/function.fbird-close.php
 * @param resource $connection_id [optional] <p>
 * An InterBase link identifier returned from
 * fbird_connect. If omitted, the last opened link
 * is assumed.
 * </p>
 * @return bool true on success or false on failure.
 */
function fbird_close($connection_id = null) {}

/**
 * This is an alias of ibase_drop_db
 * Drops a database
 * @link https://php.net/manual/en/function.fbird-drop-db.php
 * @param resource $connection [optional] <p>
 * An InterBase link identifier. If omitted, the last opened link is
 * assumed.
 * </p>
 * @return bool true on success or false on failure.
 */
function fbird_drop_db($connection = null) {}

/**
 * This is an alias of ibase_query
 * Execute a query on an InterBase database
 * @link https://php.net/manual/en/function.fbird-query.php
 * @param resource $link_identifier [optional] <p>
 * An InterBase link identifier. If omitted, the last opened link is
 * assumed.
 * </p>
 * @param string $query <p>
 * An InterBase query.
 * </p>
 * @param int $bind_args [optional] <p>
 * </p>
 * @return resource|bool If the query raises an error, returns false. If it is successful and
 * there is a (possibly empty) result set (such as with a SELECT query),
 * returns a result identifier. If the query was successful and there were
 * no results, returns true.
 * </p>
 * <p>
 * In PHP 5.0.0 and up, this function will return the number of rows
 * affected by the query for INSERT, UPDATE and DELETE statements. In order
 * to retain backward compatibility, it will return true for these
 * statements if the query succeeded without affecting any rows.
 */
function fbird_query($link_identifier = null, $query, $bind_args = null) {}

/**
 * This is an alias of ibase_fetch_row
 * Fetch a row from an InterBase database
 * @link https://php.net/manual/en/function.fbird-fetch-row.php
 * @param resource $result_identifier <p>
 * An InterBase result identifier.
 * </p>
 * @param int $fetch_flag [optional] <p>
 * fetch_flag is a combination of the constants
 * IBASE_TEXT and IBASE_UNIXTIME
 * ORed together. Passing IBASE_TEXT will cause this
 * function to return BLOB contents instead of BLOB ids. Passing
 * IBASE_UNIXTIME will cause this function to return
 * date/time values as Unix timestamps instead of as formatted strings.
 * </p>
 * @return array|false an array that corresponds to the fetched row, or false if there
 * are no more rows. Each result column is stored in an array offset,
 * starting at offset 0.
 */
function fbird_fetch_row($result_identifier, $fetch_flag = null) {}

/**
 * This is an alias of ibase_fetch_assoc
 * Fetch a result row from a query as an associative array
 * @link https://php.net/manual/en/function.fbird-fetch-assoc.php
 * @param resource $result <p>
 * The result handle.
 * </p>
 * @param int $fetch_flag [optional] <p>
 * fetch_flag is a combination of the constants
 * IBASE_TEXT and IBASE_UNIXTIME
 * ORed together. Passing IBASE_TEXT will cause this
 * function to return BLOB contents instead of BLOB ids. Passing
 * IBASE_UNIXTIME will cause this function to return
 * date/time values as Unix timestamps instead of as formatted strings.
 * </p>
 * @return array|false an associative array that corresponds to the fetched row.
 * Subsequent calls will return the next row in the result set, or false if
 * there are no more rows.
 */
function fbird_fetch_assoc($result, $fetch_flag = null) {}

/**
 * This is an alias of ibase_fetch_object
 * Get an object from a InterBase database
 * @link https://php.net/manual/en/function.fbird-fetch-object.php
 * @param resource $result_id <p>
 * An InterBase result identifier obtained either by
 * fbird_query or fbird_execute.
 * </p>
 * @param int $fetch_flag [optional] <p>
 * fetch_flag is a combination of the constants
 * IBASE_TEXT and IBASE_UNIXTIME
 * ORed together. Passing IBASE_TEXT will cause this
 * function to return BLOB contents instead of BLOB ids. Passing
 * IBASE_UNIXTIME will cause this function to return
 * date/time values as Unix timestamps instead of as formatted strings.
 * </p>
 * @return object|false an object with the next row information, or false if there are
 * no more rows.
 */
function fbird_fetch_object($result_id, $fetch_flag = null) {}

/**
 * This is an alias of ibase_free_result
 * Free a result set
 * @link https://php.net/manual/en/function.fbird-free-result.php
 * @param resource $result_identifier <p>
 * A result set created by fbird_query or
 * fbird_execute.
 * </p>
 * @return bool true on success or false on failure.
 */
function fbird_free_result($result_identifier) {}

/**
 * This is an alias of ibase_name_result
 * Assigns a name to a result set
 * @link https://php.net/manual/en/function.fbird-name-result.php
 * @param resource $result <p>
 * An InterBase result set.
 * </p>
 * @param string $name <p>
 * The name to be assigned.
 * </p>
 * @return bool true on success or false on failure.
 */
function fbird_name_result($result, $name) {}

/**
 * This is an alias of ibase_prepare
 * Prepare a query for later binding of parameter placeholders and execution
 * @link https://php.net/manual/en/function.fbird-prepare.php
 * @param string $query <p>
 * An InterBase query.
 * </p>
 * @return resource|false a prepared query handle, or false on error.
 */
function fbird_prepare($query) {}

/**
 * This is an alias of ibase_execute
 * Execute a previously prepared query
 * @link https://php.net/manual/en/function.fbird-execute.php
 * @param resource $query <p>
 * An InterBase query prepared by fbird_prepare.
 * </p>
 * @param mixed ...$bind_arg [optional]
 * @return resource|bool <p>If the query raises an error, returns false. If it is successful and
 * there is a (possibly empty) result set (such as with a SELECT query),
 * returns a result identifier. If the query was successful and there were
 * no results, returns true.
 * </p>
 * <p>
 * In PHP 5.0.0 and up, this function returns the number of rows affected by
 * the query (if > 0 and applicable to the statement type). A query that
 * succeeded, but did not affect any rows (e.g. an UPDATE of a non-existent
 * record) will return true.</p>
 */
function fbird_execute($query, ...$bind_arg) {}

/**
 * This is an alias of ibase_free_query
 * Free memory allocated by a prepared query
 * @link https://php.net/manual/en/function.fbird-free-query.php
 * @param resource $query <p>
 * A query prepared with fbird_prepare.
 * </p>
 * @return bool true on success or false on failure.
 */
function fbird_free_query($query) {}

/**
 * This is an alias of ibase_gen_id
 * Increments the named generator and returns its new value
 * @link https://php.net/manual/en/function.fbird-gen-id.php
 * @param string $generator
 * @param int $increment [optional]
 * @param resource $link_identifier [optional]
 * @return mixed new generator value as integer, or as string if the value is too big.
 */
function fbird_gen_id($generator, $increment = null, $link_identifier = null) {}

/**
 * This is an alias of ibase_num_fields
 * Get the number of fields in a result set
 * @link https://php.net/manual/en/function.fbird-num-fields.php
 * @param resource $result_id <p>
 * An InterBase result identifier.
 * </p>
 * @return int the number of fields as an integer.
 */
function fbird_num_fields($result_id) {}

/**
 * This is an alias of ibase_num_params
 * Return the number of parameters in a prepared query
 * @link https://php.net/manual/en/function.fbird-num-params.php
 * @param resource $query <p>
 * The prepared query handle.
 * </p>
 * @return int the number of parameters as an integer.
 */
function fbird_num_params($query) {}

/**
 * This is an alias of ibase_affected_rows
 * Return the number of rows that were affected by the previous query
 * @link https://php.net/manual/en/function.fbird-affected-rows.php
 * @param resource $link_identifier [optional] <p>
 * A transaction context. If link_identifier is a
 * connection resource, its default transaction is used.
 * </p>
 * @return int the number of rows as an integer.
 */
function fbird_affected_rows($link_identifier = null) {}

/**
 * This is an alias of ibase_field_info
 * Get information about a field
 * @link https://php.net/manual/en/function.fbird-field-info.php
 * @param resource $result <p>
 * An InterBase result identifier.
 * </p>
 * @param int $field_number <p>
 * Field offset.
 * </p>
 * @return array an array with the following keys: name,
 * alias, relation,
 * length and type.
 */
function fbird_field_info($result, $field_number) {}

/**
 * This is an alias of ibase_param_info
 * Return information about a parameter in a prepared query
 * @link https://php.net/manual/en/function.fbird-param-info.php
 * @param resource $query <p>
 * An InterBase prepared query handle.
 * </p>
 * @param int $param_number <p>
 * Parameter offset.
 * </p>
 * @return array an array with the following keys: name,
 * alias, relation,
 * length and type.
 */
function fbird_param_info($query, $param_number) {}

/**
 * This is an alias of ibase_trans
 * Begin a transaction
 * @link https://php.net/manual/en/function.fbird-trans.php
 * @param int $trans_args [optional] <p>
 * trans_args can be a combination of
 * IBASE_READ,
 * IBASE_WRITE,
 * IBASE_COMMITTED,
 * IBASE_CONSISTENCY,
 * IBASE_CONCURRENCY,
 * IBASE_REC_VERSION,
 * IBASE_REC_NO_VERSION,
 * IBASE_WAIT and
 * IBASE_NOWAIT.
 * </p>
 * @param resource $link_identifier [optional] <p>
 * An InterBase link identifier. If omitted, the last opened link is
 * assumed.
 * </p>
 * @return resource|false a transaction handle, or false on error.
 */
function fbird_trans($trans_args = null, $link_identifier = null) {}

/**
 * This is an alias of ibase_commit
 * Commit a transaction
 * @link https://php.net/manual/en/function.fbird-commit.php
 * @param resource $link_or_trans_identifier [optional] <p>
 * If called without an argument, this function commits the default
 * transaction of the default link. If the argument is a connection
 * identifier, the default transaction of the corresponding connection
 * will be committed. If the argument is a transaction identifier, the
 * corresponding transaction will be committed.
 * </p>
 * @return bool true on success or false on failure.
 */
function fbird_commit($link_or_trans_identifier = null) {}

/**
 * This is an alias of ibase_rollback
 * Roll back a transaction
 * @link https://php.net/manual/en/function.fbird-rollback.php
 * @param resource $link_or_trans_identifier [optional] <p>
 * If called without an argument, this function rolls back the default
 * transaction of the default link. If the argument is a connection
 * identifier, the default transaction of the corresponding connection
 * will be rolled back. If the argument is a transaction identifier, the
 * corresponding transaction will be rolled back.
 * </p>
 * @return bool true on success or false on failure.
 */
function fbird_rollback($link_or_trans_identifier = null) {}

/**
 * This is an alias of ibase_commit_ret
 * Commit a transaction without closing it
 * @link https://php.net/manual/en/function.fbird-commit-ret.php
 * @param resource $link_or_trans_identifier [optional] <p>
 * If called without an argument, this function commits the default
 * transaction of the default link. If the argument is a connection
 * identifier, the default transaction of the corresponding connection
 * will be committed. If the argument is a transaction identifier, the
 * corresponding transaction will be committed. The transaction context
 * will be retained, so statements executed from within this transaction
 * will not be invalidated.
 * </p>
 * @return bool true on success or false on failure.
 */
function fbird_commit_ret($link_or_trans_identifier = null) {}

/**
 * This is an alias of ibase_rollback_ret
 * Roll back a transaction without closing it
 * @link https://php.net/manual/en/function.fbird-rollback-ret.php
 * @param resource $link_or_trans_identifier [optional] <p>
 * If called without an argument, this function rolls back the default
 * transaction of the default link. If the argument is a connection
 * identifier, the default transaction of the corresponding connection
 * will be rolled back. If the argument is a transaction identifier, the
 * corresponding transaction will be rolled back. The transaction context
 * will be retained, so statements executed from within this transaction
 * will not be invalidated.
 * </p>
 * @return bool true on success or false on failure.
 */
function fbird_rollback_ret($link_or_trans_identifier = null) {}

/**
 * This is an alias of ibase_blob_info
 * Return blob length and other useful info
 * @link https://php.net/manual/en/function.fbird-blob-info.php
 * @param resource $link_identifier <p>
 * An InterBase link identifier. If omitted, the last opened link is
 * assumed.
 * </p>
 * @param string $blob_id <p>
 * A BLOB id.
 * </p>
 * @return array an array containing information about a BLOB. The information returned
 * consists of the length of the BLOB, the number of segments it contains, the size
 * of the largest segment, and whether it is a stream BLOB or a segmented BLOB.
 */
function fbird_blob_info($link_identifier, $blob_id) {}

/**
 * This is an alias of ibase_blob_create
 * Create a new blob for adding data
 * @link https://php.net/manual/en/function.fbird-blob-create.php
 * @param resource $link_identifier [optional] <p>
 * An InterBase link identifier. If omitted, the last opened link is
 * assumed.
 * </p>
 * @return resource|false a BLOB handle for later use with
 * fbird_blob_add or false on failure.
 */
function fbird_blob_create($link_identifier = null) {}

/**
 * This is an alias of ibase_blob_add
 * Add data into a newly created blob
 * @link https://php.net/manual/en/function.fbird-blob-add.php
 * @param resource $blob_handle <p>
 * A blob handle opened with fbird_blob_create.
 * </p>
 * @param string $data <p>
 * The data to be added.
 * </p>
 * @return void
 */
function fbird_blob_add($blob_handle, $data) {}

/**
 * This is an alias of ibase_blob_cancel
 * Cancel creating blob
 * @link https://php.net/manual/en/function.fbird-blob-cancel.php
 * @param resource $blob_handle <p>
 * A BLOB handle opened with fbird_blob_create.
 * </p>
 * @return bool true on success or false on failure.
 */
function fbird_blob_cancel($blob_handle) {}

/**
 * This is an alias of ibase_blob_close
 * Close blob
 * @link https://php.net/manual/en/function.fbird-blob-close.php
 * @param resource $blob_handle <p>
 * A BLOB handle opened with fbird_blob_create or
 * fbird_blob_open.
 * </p>
 * @return mixed If the BLOB was being read, this function returns true on success, if
 * the BLOB was being written to, this function returns a string containing
 * the BLOB id that has been assigned to it by the database. On failure, this
 * function returns false.
 */
function fbird_blob_close($blob_handle) {}

/**
 * This is an alias of ibase_blob_open
 * Open blob for retrieving data parts
 * @link https://php.net/manual/en/function.fbird-blob-open.php
 * @param resource $link_identifier <p>
 * An InterBase link identifier. If omitted, the last opened link is
 * assumed.
 * </p>
 * @param string $blob_id <p>
 * A BLOB id.
 * </p>
 * @return resource|false a BLOB handle for later use with
 * fbird_blob_get or false on failure.
 */
function fbird_blob_open($link_identifier, $blob_id) {}

/**
 * This is an alias of ibase_blob_get
 * Get len bytes data from open blob
 * @link https://php.net/manual/en/function.fbird-blob-get.php
 * @param resource $blob_handle <p>
 * A BLOB handle opened with fbird_blob_open.
 * </p>
 * @param int $len <p>
 * Size of returned data.
 * </p>
 * @return string|false at most len bytes from the BLOB, or false
 * on failure.
 */
function fbird_blob_get($blob_handle, $len) {}

/**
 * This is an alias of ibase_blob_echo
 * Output blob contents to browser
 * @link https://php.net/manual/en/function.fbird-blob-echo.php
 * @param string $blob_id <p>
 * </p>
 * @return bool true on success or false on failure.
 */
function fbird_blob_echo($blob_id) {}

/**
 * This is an alias of ibase_blob_import
 * Create blob, copy file in it, and close it
 * @link https://php.net/manual/en/function.fbird-blob-import.php
 * @param resource $link_identifier <p>
 * An InterBase link identifier. If omitted, the last opened link is
 * assumed.
 * </p>
 * @param resource $file_handle <p>
 * The file handle is a handle returned by fopen.
 * </p>
 * @return string|false the BLOB id on success, or false on error.
 */
function fbird_blob_import($link_identifier, $file_handle) {}

/**
 * This is an alias of ibase_errmsg
 * Return error messages
 * @link https://php.net/manual/en/function.fbird-errmsg.php
 * @return string|false the error message as a string, or false if no error occurred.
 */
function fbird_errmsg() {}

/**
 * This is an alias of ibase_errcode
 * Return an error code
 * @link https://php.net/manual/en/function.fbird-errcode.php
 * @return int|false the error code as an integer, or false if no error occurred.
 */
function fbird_errcode() {}

/**
 * This is an alias of ibase_add_user
 * Add a user to a security database (only for IB6 or later)
 * @link https://php.net/manual/en/function.fbird-add-user.php
 * @param resource $service_handle
 * @param string $user_name
 * @param string $password
 * @param string $first_name [optional]
 * @param string $middle_name [optional]
 * @param string $last_name [optional]
 * @return bool true on success or false on failure.
 */
function fbird_add_user($service_handle, $user_name, $password, $first_name = null, $middle_name = null, $last_name = null) {}

/**
 * This is an alias of ibase_modify_user
 * Modify a user to a security database (only for IB6 or later)
 * @link https://php.net/manual/en/function.fbird-modify-user.php
 * @param resource $service_handle
 * @param string $user_name
 * @param string $password
 * @param string $first_name [optional]
 * @param string $middle_name [optional]
 * @param string $last_name [optional]
 * @return bool true on success or false on failure.
 */
function fbird_modify_user($service_handle, $user_name, $password, $first_name = null, $middle_name = null, $last_name = null) {}

/**
 * This is an alias of ibase_delete_user
 * Delete a user from a security database (only for IB6 or later)
 * @link https://php.net/manual/en/function.fbird-delete-user.php
 * @param resource $service_handle
 * @param string $user_name
 * @return bool true on success or false on failure.
 */
function fbird_delete_user($service_handle, $user_name) {}

/**
 * This is an alias of ibase_service_attach
 * Connect to the service manager
 * @link https://php.net/manual/en/function.fbird-service-attach.php
 * @param string $host
 * @param string $dba_username
 * @param string $dba_password
 * @return resource|false
 */
function fbird_service_attach($host, $dba_username, $dba_password) {}

/**
 * This is an alias of ibase_service_detach
 * Disconnect from the service manager
 * @link https://php.net/manual/en/function.fbird-service-detach.php
 * @param resource $service_handle
 * @return bool true on success or false on failure.
 */
function fbird_service_detach($service_handle) {}

/**
 * This is an alias of ibase_backup
 * Initiates a backup task in the service manager and returns immediately
 * @link https://php.net/manual/en/function.fbird-backup.php
 * @param resource $service_handle
 * @param string $source_db
 * @param string $dest_file
 * @param int $options [optional]
 * @param bool $verbose [optional]
 * @return mixed
 */
function fbird_backup($service_handle, $source_db, $dest_file, $options = null, $verbose = null) {}

/**
 * This is an alias of ibase_restore
 * Initiates a restore task in the service manager and returns immediately
 * @link https://php.net/manual/en/function.fbird-restore.php
 * @param resource $service_handle
 * @param string $source_file
 * @param string $dest_db
 * @param int $options [optional]
 * @param bool $verbose [optional]
 * @return mixed
 */
function fbird_restore($service_handle, $source_file, $dest_db, $options = null, $verbose = null) {}

/**
 * This is an alias of ibase_maintain_db
 * Execute a maintenance command on the database server
 * @link https://php.net/manual/en/function.fbird-maintain-db.php
 * @param resource $service_handle
 * @param string $db
 * @param int $action
 * @param int $argument [optional]
 * @return bool true on success or false on failure.
 */
function fbird_maintain_db($service_handle, $db, $action, $argument = null) {}

/**
 * This is an alias of ibase_db_info
 * Request statistics about a database
 * @link https://php.net/manual/en/function.fbird-db-info.php
 * @param resource $service_handle
 * @param string $db
 * @param int $action
 * @param int $argument [optional]
 * @return string
 */
function fbird_db_info($service_handle, $db, $action, $argument = null) {}

/**
 * This is an alias of ibase_server_info
 * Request information about a database server
 * @link https://php.net/manual/en/function.fbird-server-info.php
 * @param resource $service_handle
 * @param int $action
 * @return string
 */
function fbird_server_info($service_handle, $action) {}

/**
 * This is an alias of ibase_wait_event
 * Wait for an event to be posted by the database
 * @link https://php.net/manual/en/function.fbird-wait-event.php
 * @param string $event_name1 <p>
 * The event name.
 * </p>
 * @param string $event_name2 [optional] <p>
 * </p>
 * @param string ...$_ [optional]
 * @return string the name of the event that was posted.
 */
function fbird_wait_event($event_name1, $event_name2 = null, ...$_) {}

/**
 * This is an alias of ibase_set_event_handler
 * Register a callback function to be called when events are posted
 * @link https://php.net/manual/en/function.fbird-set-event-handler.php
 * @param callable $event_handler <p>
 * The callback is called with the event name and the link resource as
 * arguments whenever one of the specified events is posted by the
 * database.
 * </p>
 * <p>
 * The callback must return false if the event handler should be
 * canceled. Any other return value is ignored. This function accepts up
 * to 15 event arguments.
 * </p>
 * @param string $event_name1 <p>
 * An event name.
 * </p>
 * @param string $event_name2 [optional] <p>
 * At most 15 events allowed.
 * </p>
 * @param string ...$_ [optional]
 * @return resource The return value is an event resource. This resource can be used to free
 * the event handler using fbird_free_event_handler.
 */
function fbird_set_event_handler($event_handler, $event_name1, $event_name2 = null, ...$_) {}

/**
 * This is an alias of ibase_free_event_handler
 * Cancels a registered event handler
 * @link https://php.net/manual/en/function.fbird-free-event-handler.php
 * @param resource $event <p>
 * An event resource, created by
 * fbird_set_event_handler.
 * </p>
 * @return bool true on success or false on failure.
 */
function fbird_free_event_handler($event) {}

/**
 * The default transaction settings are to be used.
 * This default is determined by the client library, which defines it as IBASE_WRITE|IBASE_CONCURRENCY|IBASE_WAIT in most cases.
 * @link https://www.php.net/manual/en/ibase.constants.php
 * @removed 7.4
 */
define('IBASE_DEFAULT', 0);
/**
 * @link https://www.php.net/manual/en/ibase.constants.php
 */
define('IBASE_CREATE', 0);
/**
 * Causes BLOB contents to be fetched inline, instead of being fetched as BLOB identifiers.
 * @link https://www.php.net/manual/en/ibase.constants.php
 */
define('IBASE_TEXT', 1);
/**
 * Also available as IBASE_TEXT for backward compatibility.
 * Causes BLOB contents to be fetched inline, instead of being fetched as BLOB identifiers.
 * @link https://www.php.net/manual/en/ibase.constants.php
 * @removed 7.4
 */
define('IBASE_FETCH_BLOBS', 1);
/**
 * Causes arrays to be fetched inline. Otherwise, array identifiers are returned.
 * Array identifiers can only be used as arguments to INSERT operations, as no functions to handle array identifiers are currently available.
 * @link https://www.php.net/manual/en/ibase.constants.php
 * @removed 7.4
 */
define('IBASE_FETCH_ARRAYS', 2);
/**
 * Causes date and time fields not to be returned as strings, but as UNIX timestamps (the number of seconds since the epoch, which is 1-Jan-1970 0:00 UTC).
 * Might be problematic if used with dates before 1970 on some systems.
 * @link https://www.php.net/manual/en/ibase.constants.php
 * @removed 7.4
 */
define('IBASE_UNIXTIME', 4);
/**
 * Starts a read-write transaction.
 * @link https://www.php.net/manual/en/ibase.constants.php
 * @removed 7.4
 */
define('IBASE_WRITE', 1);
/**
 * Starts a read-only transaction.
 * @link https://www.php.net/manual/en/ibase.constants.php
 * @removed 7.4
 */
define('IBASE_READ', 2);
/**
 * Starts a transaction with the isolation level set to 'read committed'.
 * This flag should be combined with either <b>IBASE_REC_VERSION</b> or <b>IBASE_REC_NO_VERSION</b>.
 * This isolation level allows access to changes that were committed after the transaction was started.
 * If <b>IBASE_REC_NO_VERSION</b> was specified, only the latest version of a row can be read.
 * If <b>IBASE_REC_VERSION</b> was specified, a row can even be read when a modification to it is pending in a concurrent transaction.
 * @link https://www.php.net/manual/en/ibase.constants.php
 * @removed 7.4
 */
define('IBASE_COMMITTED', 8);
/**
 * Starts a transaction with the isolation level set to 'consistency',
 * which means the transaction cannot read from tables that are being modified by other concurrent transactions.
 * @link https://www.php.net/manual/en/ibase.constants.php
 * @removed 7.4
 */
define('IBASE_CONSISTENCY', 16);
/**
 * Starts a transaction with the isolation level set to 'concurrency' (or 'snapshot'),
 * which means the transaction has access to all tables,
 * but cannot see changes that were committed by other transactions after the transaction was started.
 * @link https://www.php.net/manual/en/ibase.constants.php
 * @removed 7.4
 */
define('IBASE_CONCURRENCY', 4);
/**
 * Row can even be read when a modification to it is pending in a concurrent transaction.
 * @link https://www.php.net/manual/en/ibase.constants.php
 */
define('IBASE_REC_VERSION', 64);
/**
 * Only the latest version of a row can be read
 * @link https://www.php.net/manual/en/ibase.constants.php
 */
define('IBASE_REC_NO_VERSION', 32);
/**
 * Indicated that a transaction should fail immediately when a conflict occurs.
 * @link https://www.php.net/manual/en/ibase.constants.php
 * @removed 7.4
 */
define('IBASE_NOWAIT', 256);
/**
 * Indicated that a transaction should wait and retry when a conflict occurs.
 * @link https://www.php.net/manual/en/ibase.constants.php
 * @removed 7.4
 */
define('IBASE_WAIT', 128);
/**
 * @removed 7.4
 */
define('IBASE_BKP_IGNORE_CHECKSUMS', 1);
/**
 * @removed 7.4
 */
define('IBASE_BKP_IGNORE_LIMBO', 2);
/**
 * @removed 7.4
 */
define('IBASE_BKP_METADATA_ONLY', 4);
/**
 * @removed 7.4
 */
define('IBASE_BKP_NO_GARBAGE_COLLECT', 8);
/**
 * @removed 7.4
 */
define('IBASE_BKP_OLD_DESCRIPTIONS', 16);
/**
 * @removed 7.4
 */
define('IBASE_BKP_NON_TRANSPORTABLE', 32);

/**
 * Options to ibase_backup
 * @link https://php.net/manual/en/ibase.constants.php
 * @removed 7.4
 */
define('IBASE_BKP_CONVERT', 64);
/**
 * @removed 7.4
 */
define('IBASE_RES_DEACTIVATE_IDX', 256);
/**
 * @removed 7.4
 */
define('IBASE_RES_NO_SHADOW', 512);
/**
 * @removed 7.4
 */
define('IBASE_RES_NO_VALIDITY', 1024);
/**
 * @removed 7.4
 */
define('IBASE_RES_ONE_AT_A_TIME', 2048);
/**
 * @removed 7.4
 */
define('IBASE_RES_REPLACE', 4096);
/**
 * @removed 7.4
 */
define('IBASE_RES_CREATE', 8192);

/**
 * Options to ibase_restore
 * @link https://php.net/manual/en/ibase.constants.php
 * @removed 7.4
 */
define('IBASE_RES_USE_ALL_SPACE', 16384);
/**
 * @removed 7.4
 */
define('IBASE_PRP_PAGE_BUFFERS', 5);
/**
 * @removed 7.4
 */
define('IBASE_PRP_SWEEP_INTERVAL', 6);
/**
 * @removed 7.4
 */
define('IBASE_PRP_SHUTDOWN_DB', 7);
/**
 * @removed 7.4
 */
define('IBASE_PRP_DENY_NEW_TRANSACTIONS', 10);
/**
 * @removed 7.4
 */
define('IBASE_PRP_DENY_NEW_ATTACHMENTS', 9);
/**
 * @removed 7.4
 */
define('IBASE_PRP_RESERVE_SPACE', 11);
/**
 * @removed 7.4
 */
define('IBASE_PRP_RES_USE_FULL', 35);
/**
 * @removed 7.4
 */
define('IBASE_PRP_RES', 36);
/**
 * @removed 7.4
 */
define('IBASE_PRP_WRITE_MODE', 12);
/**
 * @removed 7.4
 */
define('IBASE_PRP_WM_ASYNC', 37);
/**
 * @removed 7.4
 */
define('IBASE_PRP_WM_SYNC', 38);
/**
 * @removed 7.4
 */
define('IBASE_PRP_ACCESS_MODE', 13);
/**
 * @removed 7.4
 */
define('IBASE_PRP_AM_READONLY', 39);
/**
 * @removed 7.4
 */
define('IBASE_PRP_AM_READWRITE', 40);
/**
 * @removed 7.4
 */
define('IBASE_PRP_SET_SQL_DIALECT', 14);
/**
 * @removed 7.4
 */
define('IBASE_PRP_ACTIVATE', 256);
/**
 * @removed 7.4
 */
define('IBASE_PRP_DB_ONLINE', 512);
/**
 * @removed 7.4
 */
define('IBASE_RPR_CHECK_DB', 16);
/**
 * @removed 7.4
 */
define('IBASE_RPR_IGNORE_CHECKSUM', 32);
/**
 * @removed 7.4
 */
define('IBASE_RPR_KILL_SHADOWS', 64);
/**
 * @removed 7.4
 */
define('IBASE_RPR_MEND_DB', 4);
/**
 * @removed 7.4
 */
define('IBASE_RPR_VALIDATE_DB', 1);
/**
 * @removed 7.4
 */
define('IBASE_RPR_FULL', 128);

/**
 * Options to ibase_maintain_db
 * @link https://php.net/manual/en/ibase.constants.php
 * @removed 7.4
 */
define('IBASE_RPR_SWEEP_DB', 2);
/**
 * @removed 7.4
 */
define('IBASE_STS_DATA_PAGES', 1);
/**
 * @removed 7.4
 */
define('IBASE_STS_DB_LOG', 2);
/**
 * @removed 7.4
 */
define('IBASE_STS_HDR_PAGES', 4);
/**
 * @removed 7.4
 */
define('IBASE_STS_IDX_PAGES', 8);

/**
 * Options to ibase_db_info
 * @link https://php.net/manual/en/ibase.constants.php
 * @removed 7.4
 */
define('IBASE_STS_SYS_RELATIONS', 16);
/**
 * @removed 7.4
 */
define('IBASE_SVC_SERVER_VERSION', 55);
/**
 * @removed 7.4
 */
define('IBASE_SVC_IMPLEMENTATION', 56);
/**
 * @removed 7.4
 */
define('IBASE_SVC_GET_ENV', 59);
/**
 * @removed 7.4
 */
define('IBASE_SVC_GET_ENV_LOCK', 60);
/**
 * @removed 7.4
 */
define('IBASE_SVC_GET_ENV_MSG', 61);
/**
 * @removed 7.4
 */
define('IBASE_SVC_USER_DBPATH', 58);
/**
 * @removed 7.4
 */
define('IBASE_SVC_SVR_DB_INFO', 50);

/**
 * Options to ibase_server_info
 * @link https://php.net/manual/en/ibase.constants.php
 * @removed 7.4
 */
define('IBASE_SVC_GET_USERS', 68);

// End of interbase v.
