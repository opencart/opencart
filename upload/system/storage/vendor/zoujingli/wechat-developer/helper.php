<?php

if (!function_exists('array_column')) {
    /**
     * PHP 版本兼容函数
     * @param $input
     * @param $columnKey
     * @param $indexKey
     * @return array
     */
    function array_column($input, $columnKey, $indexKey = null)
    {
        $result = array();
        foreach ($input as $row) {
            if (isset($row[$columnKey])) {
                if ($indexKey !== null && isset($row[$indexKey])) {
                    $result[$row[$indexKey]] = $row[$columnKey];
                } else {
                    $result[] = $row[$columnKey];
                }
            }
        }
        return $result;
    }
}