<?php

declare(strict_types=1);

namespace Swoole;

class Process
{
    public const IPC_NOWAIT = 256;
    public const PIPE_MASTER = 1;
    public const PIPE_WORKER = 2;
    public const PIPE_READ = 3;
    public const PIPE_WRITE = 4;
    public $pipe;
    public $msgQueueId;
    public $msgQueueKey;

    /**
     * Process ID. This is to uniquely identify the process in the OS.
     *
     * @var int
     */
    public $pid;

    /**
     * ID of the process.
     *
     * In a Swoole program (e.g., a Swoole-based server), there are different types of processes, including event worker
     * processes, task worker processes, and user worker processes. This ID is to uniquely identify the process in the
     * running Swoole program.
     *
     * @var int
     */
    public $id;
    private $callback;

    public function __construct(callable $callback, $redirect_stdin_and_stdout = null, $pipe_type = null, $enable_coroutine = null) {}

    public function __destruct() {}

    /**
     * @param mixed|null $blocking
     * @return mixed
     */
    public static function wait($blocking = null) {}

    /**
     * @param mixed $signal_no
     * @param mixed $callback
     * @return mixed
     */
    public static function signal($signal_no, $callback) {}

    /**
     * @param mixed $usec
     * @param mixed|null $type
     * @return mixed
     */
    public static function alarm($usec, $type = null) {}

    /**
     * @param mixed $pid
     * @param mixed|null $signal_no
     * @return mixed
     */
    public static function kill($pid, $signal_no = null) {}

    /**
     * @param mixed|null $nochdir
     * @param mixed|null $noclose
     * @param mixed|null $pipes
     * @return mixed
     */
    public static function daemon($nochdir = null, $noclose = null, $pipes = null) {}

    /**
     * @param mixed $which
     * @param mixed $priority
     * @return mixed
     */
    public function setPriority($which, $priority) {}

    /**
     * @param mixed $which
     * @return mixed
     */
    public function getPriority($which) {}

    /**
     * @return mixed
     */
    public function set(array $settings) {}

    /**
     * @param mixed $seconds
     * @return mixed
     */
    public function setTimeout($seconds) {}

    /**
     * @param mixed $blocking
     * @return mixed
     */
    public function setBlocking($blocking) {}

    /**
     * @param mixed|null $key
     * @param mixed|null $mode
     * @param mixed|null $capacity
     * @return mixed
     */
    public function useQueue($key = null, $mode = null, $capacity = null) {}

    /**
     * @return mixed
     */
    public function statQueue() {}

    /**
     * @return mixed
     */
    public function freeQueue() {}

    /**
     * @return mixed
     */
    public function start() {}

    /**
     * @param mixed $data
     * @return mixed
     */
    public function write($data) {}

    /**
     * @return mixed
     */
    public function close() {}

    /**
     * @param mixed|null $size
     * @return mixed
     */
    public function read($size = null) {}

    /**
     * @param mixed $data
     * @return mixed
     */
    public function push($data) {}

    /**
     * @param mixed|null $size
     * @return mixed
     */
    public function pop($size = null) {}

    /**
     * @param mixed|null $exit_code
     * @return mixed
     */
    public function exit($exit_code = null) {}

    /**
     * @param mixed $exec_file
     * @param mixed $args
     * @return mixed
     */
    public function exec($exec_file, $args) {}

    /**
     * @return mixed
     */
    public function exportSocket() {}

    /**
     * @param mixed $process_name
     * @return mixed
     */
    public function name($process_name) {}
}
