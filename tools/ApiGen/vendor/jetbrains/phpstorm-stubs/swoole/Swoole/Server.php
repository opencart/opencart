<?php

declare(strict_types=1);

namespace Swoole;

class Server
{
    public $setting;
    public $connections;
    public $host = '';
    public $port = 0;
    public $type = 0;
    public $mode = 0;
    public $ports;
    public $master_pid = 0;
    public $manager_pid = 0;
    public $worker_id = -1;
    public $taskworker = false;
    public $worker_pid = 0;
    public $stats_timer;

    /**
     * @var \Swoole\Coroutine\Http\Server
     * @since 4.8.0
     */
    public $admin_server;

    /**
     * @var callable
     */
    private $onStart;

    /**
     * @var callable
     * @since 4.8.0
     */
    private $onBeforeShutdown;

    /**
     * @var callable
     */
    private $onShutdown;

    /**
     * @var callable
     */
    private $onWorkerStart;

    /**
     * @var callable
     */
    private $onWorkerStop;

    /**
     * @var callable
     */
    private $onBeforeReload;

    /**
     * @var callable
     */
    private $onAfterReload;

    /**
     * @var callable
     */
    private $onWorkerExit;

    /**
     * @var callable
     */
    private $onWorkerError;

    /**
     * @var callable
     */
    private $onTask;

    /**
     * @var callable
     */
    private $onFinish;

    /**
     * @var callable
     */
    private $onManagerStart;

    /**
     * @var callable
     */
    private $onManagerStop;

    /**
     * @var callable
     */
    private $onPipeMessage;

    public function __construct($host, $port = null, $mode = null, $sock_type = null) {}

    public function __destruct() {}

    /**
     * @param mixed $host
     * @param mixed $port
     * @param mixed $sock_type
     * @return mixed
     */
    public function listen($host, $port, $sock_type) {}

    /**
     * @param mixed $host
     * @param mixed $port
     * @param mixed $sock_type
     * @return mixed
     */
    public function addlistener($host, $port, $sock_type) {}

    /**
     * @param mixed $event_name
     * @return mixed
     */
    public function on($event_name, callable $callback) {}

    /**
     * @param mixed $event_name
     * @return mixed
     */
    public function getCallback($event_name) {}

    /**
     * @return mixed
     */
    public function set(array $settings) {}

    /**
     * @return mixed
     */
    public function start() {}

    /**
     * @param mixed $fd
     * @param mixed $send_data
     * @param mixed|null $server_socket
     * @return mixed
     */
    public function send($fd, $send_data, $server_socket = null) {}

    /**
     * @param mixed $ip
     * @param mixed $port
     * @param mixed $send_data
     * @param mixed|null $server_socket
     * @return mixed
     */
    public function sendto($ip, $port, $send_data, $server_socket = null) {}

    /**
     * @param mixed $conn_fd
     * @param mixed $send_data
     * @return mixed
     */
    public function sendwait($conn_fd, $send_data) {}

    /**
     * @param mixed $fd
     * @return mixed
     */
    public function exists($fd) {}

    /**
     * @param mixed $fd
     * @return mixed
     */
    public function exist($fd) {}

    /**
     * @param mixed $fd
     * @param mixed|null $is_protected
     * @return mixed
     */
    public function protect($fd, $is_protected = null) {}

    /**
     * @param mixed $conn_fd
     * @param mixed $filename
     * @param mixed|null $offset
     * @param mixed|null $length
     * @return mixed
     */
    public function sendfile($conn_fd, $filename, $offset = null, $length = null) {}

    /**
     * @param mixed $fd
     * @param mixed|null $reset
     * @return mixed
     */
    public function close($fd, $reset = null) {}

    /**
     * @param mixed $fd
     * @return mixed
     */
    public function confirm($fd) {}

    /**
     * @param mixed $fd
     * @return mixed
     */
    public function pause($fd) {}

    /**
     * @param mixed $fd
     * @return mixed
     */
    public function resume($fd) {}

    /**
     * @param mixed $data
     * @param mixed|null $worker_id
     * @return mixed
     */
    public function task($data, $worker_id = null, ?callable $finish_callback = null) {}

    /**
     * @param mixed $data
     * @param mixed|null $timeout
     * @param mixed|null $worker_id
     * @return mixed
     */
    public function taskwait($data, $timeout = null, $worker_id = null) {}

    /**
     * @param mixed|null $timeout
     * @return mixed
     */
    public function taskWaitMulti(array $tasks, $timeout = null) {}

    /**
     * @param mixed|null $timeout
     * @return mixed
     */
    public function taskCo(array $tasks, $timeout = null) {}

    /**
     * @param mixed $data
     * @return mixed
     */
    public function finish($data) {}

    /**
     * @return mixed
     */
    public function reload() {}

    /**
     * @return mixed
     */
    public function shutdown() {}

    /**
     * @param mixed|null $worker_id
     * @return mixed
     */
    public function stop($worker_id = null) {}

    /**
     * @return mixed
     */
    public function getLastError() {}

    /**
     * @param mixed $reactor_id
     * @return mixed
     */
    public function heartbeat($reactor_id) {}

    /**
     * @param mixed $fd
     * @param mixed|null $reactor_id
     * @return mixed
     */
    public function getClientInfo($fd, $reactor_id = null) {}

    /**
     * @param mixed $start_fd
     * @param mixed|null $find_count
     * @return mixed
     */
    public function getClientList($start_fd, $find_count = null) {}

    /**
     * Get the ID of current worker (either an event worker or a task worker).
     *
     * @return int|false Returns the ID of current worker. Returns false if not called within a worker process (either
     *                   an event worker process or a task worker process).
     */
    public function getWorkerId() {}

    /**
     * Get the process ID of a given worker process (specified by a worker ID).
     *
     * @return int|false Returns the process ID of a given worker process (specified by a worker ID). If the worker ID
     *                   is a negative integer or not passed in, returns the process ID of current worker process.
     *                   Returns false if something wrong happens (e.g., the worker process doesn't exist, or an invalid
     *                   worker ID specified.).
     */
    public function getWorkerPid(int $worker_id = -1) {}

    /**
     * @param mixed|null $worker_id
     * @return mixed
     */
    public function getWorkerStatus($worker_id = null) {}

    /**
     * Run a customized command in a specified process of Swoole.
     *
     * @param mixed $data
     * @param bool $json_encode If the callback function of the command returns a JSON encoded string back, it can be decoded automatically by setting this parameter to TRUE.
     * @return mixed|false
     * @see \Swoole\Server::addCommand()
     * @since 4.8.0
     */
    public function command(string $name, int $process_id, int $process_type, $data, bool $json_decode = true) {}

    /**
     * Add a customized command.
     *
     * Commands can be added to the master process, the manager process, or worker processes. Commands can only be added
     * before the server is started.
     *
     * @param int $accepted_process_types One or multiple types of processes. e.g., "SWOOLE_SERVER_COMMAND_EVENT_WORKER | SWOOLE_SERVER_COMMAND_TASK_WORKER".
     * @param callable $callback The callback function should return a (serialized) string back.
     * @return bool TRUE if succeeds, otherwise FALSE.
     * @see \Swoole\Server::command()
     * @see SWOOLE_SERVER_COMMAND_MASTER
     * @see SWOOLE_SERVER_COMMAND_MANAGER
     * @see SWOOLE_SERVER_COMMAND_EVENT_WORKER
     * @see SWOOLE_SERVER_COMMAND_TASK_WORKER
     * @since 4.8.0
     */
    public function addCommand(string $name, int $accepted_process_types, callable $callback) {}

    /**
     * @return mixed
     */
    public function getManagerPid() {}

    /**
     * @return mixed
     */
    public function getMasterPid() {}

    /**
     * @param mixed $fd
     * @param mixed|null $reactor_id
     * @return mixed
     */
    public function connection_info($fd, $reactor_id = null) {}

    /**
     * @param mixed $start_fd
     * @param mixed|null $find_count
     * @return mixed
     */
    public function connection_list($start_fd, $find_count = null) {}

    /**
     * @param mixed $message
     * @param mixed $dst_worker_id
     * @return mixed
     */
    public function sendMessage($message, $dst_worker_id) {}

    /**
     * @param \Swoole\Process $process
     * @return int|false Return the ID of the process (\Swoole\Process::$id) back if succeeds; otherwise return FALSE.
     * @see \Swoole\Process::$id
     */
    public function addProcess(Process $process) {}

    /**
     * @return mixed
     */
    public function stats() {}

    /**
     * @param mixed|null $port
     * @return mixed
     */
    public function getSocket($port = null) {}

    /**
     * @param mixed $fd
     * @param mixed $uid
     * @return mixed
     */
    public function bind($fd, $uid) {}

    /**
     * Alias of method \Swoole\Timer::after().
     *
     * @return int
     * @see \Swoole\Timer::after()
     */
    public function after(int $ms, callable $callback, ...$params) {}

    /**
     * Alias of method \Swoole\Timer::tick().
     *
     * @return int
     * @see \Swoole\Timer::tick()
     */
    public function tick(int $ms, callable $callback, ...$params) {}

    /**
     * Alias of method \Swoole\Timer::clear().
     *
     * @return bool
     * @see \Swoole\Timer::clear()
     */
    public function clearTimer(int $timer_id) {}

    /**
     * Alias of method \Swoole\Event::defer().
     *
     * @return true
     * @see \Swoole\Event::defer()
     */
    public function defer(callable $callback) {}
}
