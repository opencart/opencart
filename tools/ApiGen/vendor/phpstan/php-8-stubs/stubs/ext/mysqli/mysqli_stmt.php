<?php 

class mysqli_stmt
{
    /**
     * @tentative-return-type
     * @alias mysqli_stmt_execute
     * @return bool
     */
    #[\Until('8.1')]
    public function execute()
    {
    }
    public function __construct(mysqli $mysql, ?string $query = null)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_stmt_attr_get
     * @return int
     */
    public function attr_get(int $attribute)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_stmt_attr_set
     * @return bool
     */
    public function attr_set(int $attribute, int $value)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_stmt_bind_param
     * @return bool
     */
    public function bind_param(string $types, mixed &...$vars)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_stmt_bind_result
     * @return bool
     */
    public function bind_result(mixed &...$vars)
    {
    }
    /**
     * @return bool
     * @alias mysqli_stmt_close
     */
    public function close()
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_stmt_data_seek
     * @return void
     */
    public function data_seek(int $offset)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_stmt_execute
     */
    #[\Since('8.1')]
    public function execute(?array $params = null)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_stmt_fetch
     * @return (bool | null)
     */
    public function fetch()
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_stmt_get_warnings
     * @return (mysqli_warning | false)
     */
    public function get_warnings()
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_stmt_result_metadata
     * @return (mysqli_result | false)
     */
    public function result_metadata()
    {
    }
    #if defined(MYSQLI_USE_MYSQLND)
    /**
     * @tentative-return-type
     * @alias mysqli_stmt_more_results
     * @return bool
     */
    public function more_results()
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_stmt_next_result
     * @return bool
     */
    public function next_result()
    {
    }
    #endif
    /**
     * @tentative-return-type
     * @alias mysqli_stmt_num_rows
     * @return (int | string)
     */
    public function num_rows()
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_stmt_send_long_data
     * @return bool
     */
    public function send_long_data(int $param_num, string $data)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_stmt_free_result
     * @return void
     */
    public function free_result()
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_stmt_reset
     * @return bool
     */
    public function reset()
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_stmt_prepare
     * @return bool
     */
    public function prepare(string $query)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_stmt_store_result
     * @return bool
     */
    public function store_result()
    {
    }
    #if defined(MYSQLI_USE_MYSQLND)
    /**
     * @tentative-return-type
     * @alias mysqli_stmt_get_result
     * @return (mysqli_result | false)
     */
    public function get_result()
    {
    }
}