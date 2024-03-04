<?php

/**
 * Helper autocomplete for php redis extension
 *
 * @mixin \Redis
 *
 * @link   https://github.com/phpredis/phpredis/blob/develop/redis_array.stub.php
 */
class RedisArray
{
    /**
     * Constructor
     *
     * @param string|string[] $hosts Name of the redis array from redis.ini or array of hosts to construct the array with
     * @param null|array      $opts  Array of options
     */
    public function __construct(string|array $hosts, ?array $opts = null) {}

    /**
     * @return bool|array returns a list of points on continuum; may be useful with custom distributor function.
     */
    public function _continuum(): bool|array {}

    /**
     * @return bool|array returns a custom distributor function.
     */
    public function _distributor(): bool|callable {}

    /**
     * @return bool|callable the name of the function used to extract key parts during consistent hashing.
     */
    public function _function(): bool|callable {}

    /**
     * @return bool|array list of hosts for the selected array or false
     */
    public function _hosts(): bool|array {}

    /**
     * @param string $host The host you want to retrieve the instance for
     *
     * @return bool|null|\Redis a redis instance connected to a specific node
     */
    public function _instance(string $host): bool|null|Redis {}

    /**
     * Use this function when a new node is added and keys need to be rehashed.
     *
     * @return bool|null rehash result
     */
    public function _rehash(callable $fn = null): bool|null {}

    /**
     * @param string $key The key for which you want to lookup the host
     *
     * @return bool|string|null the host to be used for a certain key
     */
    public function _target(string $key): bool|string|null {}

    /**
     * @param string $host Host
     * @param int    $mode \Redis::MULTI|\Redis::PIPELINE
     *
     * @return bool|string|null the host to be used for a certain key
     */
    public function multi(string $host, int $mode = Redis::MULTI): bool|RedisArray {}

    /**
     * Returns a hosts array of associative array of strings and integers, with the following keys:
     * - redis_version
     * - redis_git_sha1
     * - redis_git_dirty
     * - redis_build_id
     * - redis_mode
     * - os
     * - arch_bits
     * - multiplexing_api
     * - atomicvar_api
     * - gcc_version
     * - process_id
     * - run_id
     * - tcp_port
     * - uptime_in_seconds
     * - uptime_in_days
     * - hz
     * - lru_clock
     * - executable
     * - config_file
     * - connected_clients
     * - client_longest_output_list
     * - client_biggest_input_buf
     * - blocked_clients
     * - used_memory
     * - used_memory_human
     * - used_memory_rss
     * - used_memory_rss_human
     * - used_memory_peak
     * - used_memory_peak_human
     * - used_memory_peak_perc
     * - used_memory_peak
     * - used_memory_overhead
     * - used_memory_startup
     * - used_memory_dataset
     * - used_memory_dataset_perc
     * - total_system_memory
     * - total_system_memory_human
     * - used_memory_lua
     * - used_memory_lua_human
     * - maxmemory
     * - maxmemory_human
     * - maxmemory_policy
     * - mem_fragmentation_ratio
     * - mem_allocator
     * - active_defrag_running
     * - lazyfree_pending_objects
     * - mem_fragmentation_ratio
     * - loading
     * - rdb_changes_since_last_save
     * - rdb_bgsave_in_progress
     * - rdb_last_save_time
     * - rdb_last_bgsave_status
     * - rdb_last_bgsave_time_sec
     * - rdb_current_bgsave_time_sec
     * - rdb_last_cow_size
     * - aof_enabled
     * - aof_rewrite_in_progress
     * - aof_rewrite_scheduled
     * - aof_last_rewrite_time_sec
     * - aof_current_rewrite_time_sec
     * - aof_last_bgrewrite_status
     * - aof_last_write_status
     * - aof_last_cow_size
     * - changes_since_last_save
     * - aof_current_size
     * - aof_base_size
     * - aof_pending_rewrite
     * - aof_buffer_length
     * - aof_rewrite_buffer_length
     * - aof_pending_bio_fsync
     * - aof_delayed_fsync
     * - loading_start_time
     * - loading_total_bytes
     * - loading_loaded_bytes
     * - loading_loaded_perc
     * - loading_eta_seconds
     * - total_connections_received
     * - total_commands_processed
     * - instantaneous_ops_per_sec
     * - total_net_input_bytes
     * - total_net_output_bytes
     * - instantaneous_input_kbps
     * - instantaneous_output_kbps
     * - rejected_connections
     * - maxclients
     * - sync_full
     * - sync_partial_ok
     * - sync_partial_err
     * - expired_keys
     * - evicted_keys
     * - keyspace_hits
     * - keyspace_misses
     * - pubsub_channels
     * - pubsub_patterns
     * - latest_fork_usec
     * - migrate_cached_sockets
     * - slave_expires_tracked_keys
     * - active_defrag_hits
     * - active_defrag_misses
     * - active_defrag_key_hits
     * - active_defrag_key_misses
     * - role
     * - master_replid
     * - master_replid2
     * - master_repl_offset
     * - second_repl_offset
     * - repl_backlog_active
     * - repl_backlog_size
     * - repl_backlog_first_byte_offset
     * - repl_backlog_histlen
     * - master_host
     * - master_port
     * - master_link_status
     * - master_last_io_seconds_ago
     * - master_sync_in_progress
     * - slave_repl_offset
     * - slave_priority
     * - slave_read_only
     * - master_sync_left_bytes
     * - master_sync_last_io_seconds_ago
     * - master_link_down_since_seconds
     * - connected_slaves
     * - min-slaves-to-write
     * - min-replicas-to-write
     * - min_slaves_good_slaves
     * - used_cpu_sys
     * - used_cpu_user
     * - used_cpu_sys_children
     * - used_cpu_user_children
     * - cluster_enabled
     *
     * @link    https://redis.io/commands/info
     * @return  bool|array
     * @example
     * <pre>
     * $redis->info();
     * </pre>
     */
    public function info(): bool|array {}
}
