<?php 

class mysqli_result implements \IteratorAggregate
{
    public function __construct(mysqli $mysql, int $result_mode = MYSQLI_STORE_RESULT)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_free_result
     * @return void
     */
    public function close()
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_free_result
     * @return void
     */
    public function free()
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_data_seek
     * @return bool
     */
    public function data_seek(int $offset)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_fetch_field
     * @return (object | false)
     */
    public function fetch_field()
    {
    }
    /**
     * @return array
     * @alias mysqli_fetch_fields
     */
    public function fetch_fields()
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_fetch_field_direct
     * @return (object | false)
     */
    public function fetch_field_direct(int $index)
    {
    }
    #if defined(MYSQLI_USE_MYSQLND)
    /**
     * @return array
     * @alias mysqli_fetch_all
     */
    public function fetch_all(int $mode = MYSQLI_NUM)
    {
    }
    #endif
    /**
     * @return array|null|false
     * @alias mysqli_fetch_array
     */
    public function fetch_array(int $mode = MYSQLI_BOTH)
    {
    }
    /**
     * @return array|null|false
     * @alias mysqli_fetch_assoc
     */
    public function fetch_assoc()
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_fetch_object
     * @return (object | null | false)
     */
    public function fetch_object(string $class = "stdClass", array $constructor_args = [])
    {
    }
    /**
     * @return array|null|false
     * @alias mysqli_fetch_row
     */
    public function fetch_row()
    {
    }
    /** @alias mysqli_fetch_column */
    #[\Since('8.1')]
    public function fetch_column(int $column = 0) : null|int|float|string|false
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_field_seek
     * @return bool
     */
    public function field_seek(int $index)
    {
    }
    /**
     * @tentative-return-type
     * @alias mysqli_free_result
     * @return void
     */
    public function free_result()
    {
    }
    public function getIterator() : Iterator;
}