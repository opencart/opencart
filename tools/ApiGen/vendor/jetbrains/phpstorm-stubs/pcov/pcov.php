<?php

/** @link https://github.com/krakjoe/pcov/blob/develop/README.md */

namespace {
    define('pcov\all', 0);
    define('pcov\inclusive', 1);
    define('pcov\exclusive', 2);
    define('pcov\version', '1.0.6');
}

namespace pcov
{
    /**
     * (PHP &gt;= 7.0, PECL pcov &gt;= 1.0.0)<br/>
     * Shall start recording coverage information
     * @return void
     */
    function start() {}

    /**
     * (PHP &gt;= 7.0, PECL pcov &gt;= 1.0.0)<br/>
     * Shall stop recording coverage information
     * @return void
     */
    function stop() {}

    /**
     * (PHP &gt;= 7.0, PECL pcov &gt;= 1.0.0)<br/>
     * Shall collect coverage information
     * @param int $type [optional] <p>
     * pcov\all shall collect coverage information for all files
     * pcov\inclusive shall collect coverage information for the specified files
     * pcov\exclusive shall collect coverage information for all but the specified files
     * </p>
     * @param array $filter <p>
     * path of files (realpath) that should be filtered
     * </p>
     * @return array
     */
    function collect(int $type = all, array $filter = []) {}

    /**
     * (PHP &gt;= 7.0, PECL pcov &gt;= 1.0.0)<br/>
     * Shall clear stored information
     * @param bool $files [optional] <p>
     * set true to clear file tables
     * Note: clearing the file tables may have surprising consequences
     * </p>
     * @return void
     */
    function clear(bool $files = false) {}

    /**
     * (PHP &gt;= 7.0, PECL pcov &gt;= 1.0.0)<br/>
     * Shall return list of files waiting to be collected
     * @return array
     */
    function waiting() {}

    /**
     * (PHP &gt;= 7.0, PECL pcov &gt;= 1.0.0)<br/>
     * Shall return the current size of the trace and cfg arena
     * @return int
     */
    function memory() {}
}
