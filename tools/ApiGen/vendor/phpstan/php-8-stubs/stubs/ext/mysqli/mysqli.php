<?php 

class mysqli
{
    public function __construct(?string $hostname = null, ?string $username = null, ?string $password = null, ?string $database = null, ?int $port = null, ?string $socket = null)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_autocommit
     * @return bool
     */
    public function autocommit(bool $enable)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_begin_transaction
     * @return bool
     */
    public function begin_transaction(int $flags = 0, ?string $name = null)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_change_user
     * @return bool
     */
    public function change_user(string $username, string $password, ?string $database)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_character_set_name
     * @return string
     */
    public function character_set_name()
    {
    }
    /**
     * @return bool
     * @alias mysqli_close
     */
    public function close()
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_commit
     * @return bool
     */
    public function commit(int $flags = 0, ?string $name = null)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_connect
     * @no-verify
     * @return (bool | null)
     */
    public function connect(?string $hostname = null, ?string $username = null, ?string $password = null, ?string $database = null, ?int $port = null, ?string $socket = null)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_dump_debug_info
     * @return bool
     */
    public function dump_debug_info()
    {
    }
    /**
     * @return bool
     * @alias mysqli_debug
     * @no-verify Should really be a static method
     */
    public function debug(string $options)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_get_charset
     * @return (object | null)
     */
    public function get_charset()
    {
    }
    /**
     * @alias mysqli_execute_query
     */
    #[\Since('8.2')]
    public function execute_query(string $query, ?array $params = null) : mysqli_result|bool
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_get_client_info
     * @deprecated 8.1.0
     * @return string
     */
    public function get_client_info()
    {
    }
    #if defined(MYSQLI_USE_MYSQLND)
    /**
     * @return array
     * @alias mysqli_get_connection_stats
     */
    public function get_connection_stats()
    {
    }
    #endif
    /**
     * @tentative-return-type
     * @alias mysqli_get_server_info
     * @return string
     */
    public function get_server_info()
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_get_warnings
     * @return (mysqli_warning | false)
     */
    public function get_warnings()
    {
    }
    /**
     * @return null|false
     */
    public function init()
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_kill
     * @return bool
     */
    public function kill(int $process_id)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_multi_query
     * @return bool
     */
    public function multi_query(string $query)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_more_results
     * @return bool
     */
    public function more_results()
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_next_result
     * @return bool
     */
    public function next_result()
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_ping
     * @return bool
     */
    public function ping()
    {
    }
    #if defined(MYSQLI_USE_MYSQLND)
    /**
     * @tentative-return-type
     * @alias mysqli_poll
     * @return (int | false)
     */
    public static function poll(?array &$read, ?array &$error, array &$reject, int $seconds, int $microseconds = 0)
    {
    }
    #endif
    /**
     * @tentative-return-type
     * @alias mysqli_prepare
     * @return (mysqli_stmt | false)
     */
    public function prepare(string $query)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_query
     * @return (mysqli_result | bool)
     */
    public function query(string $query, int $result_mode = MYSQLI_STORE_RESULT)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_real_connect
     * @return bool
     */
    public function real_connect(?string $hostname = null, ?string $username = null, ?string $password = null, ?string $database = null, ?int $port = null, ?string $socket = null, int $flags = 0)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_real_escape_string
     * @return string
     */
    public function real_escape_string(string $string)
    {
    }
    #if defined(MYSQLI_USE_MYSQLND)
    /**
     * @tentative-return-type
     * @alias mysqli_reap_async_query
     * @return (mysqli_result | bool)
     */
    public function reap_async_query()
    {
    }
    #endif
    /**
     * @tentative-return-type
     * @alias mysqli_real_escape_string
     * @return string
     */
    public function escape_string(string $string)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_real_query
     * @return bool
     */
    public function real_query(string $query)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_release_savepoint
     * @return bool
     */
    public function release_savepoint(string $name)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_rollback
     * @return bool
     */
    public function rollback(int $flags = 0, ?string $name = null)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_savepoint
     * @return bool
     */
    public function savepoint(string $name)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_select_db
     * @return bool
     */
    public function select_db(string $database)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_set_charset
     * @return bool
     */
    public function set_charset(string $charset)
    {
    }
    /**
     * @param (string | int) $value
     * @tentative-return-type
     * @alias mysqli_options
     * @return bool
     */
    public function options(int $option, $value)
    {
    }
    /**
     * @param (string | int) $value
     * @tentative-return-type
     * @alias mysqli_options
     * @return bool
     */
    public function set_opt(int $option, $value)
    {
    }
    /**
     * @return bool
     * @alias mysqli_ssl_set
     */
    public function ssl_set(?string $key, ?string $certificate, ?string $ca_certificate, ?string $ca_path, ?string $cipher_algos)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_stat
     * @return (string | false)
     */
    public function stat()
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_stmt_init
     * @return (mysqli_stmt | false)
     */
    public function stmt_init()
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_store_result
     * @return (mysqli_result | false)
     */
    public function store_result(int $mode = 0)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_thread_safe
     * @return bool
     */
    public function thread_safe()
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_use_result
     * @return (mysqli_result | false)
     */
    public function use_result()
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_refresh
     * @return bool
     */
    public function refresh(int $flags)
    {
    }
}