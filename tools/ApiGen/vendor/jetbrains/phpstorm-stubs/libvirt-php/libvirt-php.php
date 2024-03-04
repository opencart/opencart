<?php
declare(strict_types=1);

/**
 * Stubs for libvirt-php
 * https://libvirt.org/php/
 * https://github.com/inode64/phpstorm-stubs
 */

/* Domain metadata constants */
const VIR_DOMAIN_METADATA_DESCRIPTION = 0;
const VIR_DOMAIN_METADATA_TITLE = 1;
const VIR_DOMAIN_METADATA_ELEMENT = 2;
const VIR_DOMAIN_AFFECT_CURRENT = VIR_DOMAIN_AFFECT_CURRENT;
const VIR_DOMAIN_AFFECT_LIVE = 1;
const VIR_DOMAIN_AFFECT_CONFIG = 2;

const VIR_DOMAIN_STATS_STATE = 1;
const VIR_DOMAIN_STATS_CPU_TOTAL = 2;
const VIR_DOMAIN_STATS_BALLOON = 4;
const VIR_DOMAIN_STATS_VCPU = 8;
const VIR_DOMAIN_STATS_INTERFACE = 16;
const VIR_DOMAIN_STATS_BLOCK = 32;

/* XML constants */
const VIR_DOMAIN_XML_SECURE = 1;
const VIR_DOMAIN_XML_INACTIVE = 2;
const VIR_DOMAIN_XML_UPDATE_CPU = 4;
const VIR_DOMAIN_XML_MIGRATABLE = 8;

const VIR_NODE_CPU_STATS_ALL_CPUS = -1;

/* Domain constants */
const VIR_DOMAIN_NOSTATE = 0;
const VIR_DOMAIN_RUNNING = 1;
const VIR_DOMAIN_BLOCKED = 2;
const VIR_DOMAIN_PAUSED = 3;
const VIR_DOMAIN_SHUTDOWN = 4;
const VIR_DOMAIN_SHUTOFF = 5;
const VIR_DOMAIN_CRASHED = 6;
const VIR_DOMAIN_PMSUSPENDED = 7;

/* Volume constants */
const VIR_STORAGE_VOL_RESIZE_ALLOCATE = 1;
const VIR_STORAGE_VOL_RESIZE_DELTA = 2;
const VIR_STORAGE_VOL_RESIZE_SHRINK = 4;
const VIR_STORAGE_VOL_CREATE_PREALLOC_METADATA = 1;
const VIR_STORAGE_VOL_CREATE_REFLINK = 2;

/* Domain vCPU flags */
const VIR_DOMAIN_VCPU_CONFIG = VIR_DOMAIN_AFFECT_CONFIG;
const VIR_DOMAIN_VCPU_CURRENT = VIR_DOMAIN_AFFECT_CURRENT;
const VIR_DOMAIN_VCPU_LIVE = VIR_DOMAIN_AFFECT_LIVE;
const VIR_DOMAIN_VCPU_MAXIMUM = 4;
const VIR_DOMAIN_VCPU_GUEST = 8;

/* Domain snapshot constants */
const VIR_SNAPSHOT_DELETE_CHILDREN = 1;
const VIR_SNAPSHOT_DELETE_METADATA_ONLY = 2;
const VIR_SNAPSHOT_DELETE_CHILDREN_ONLY = 4;
const VIR_SNAPSHOT_CREATE_REDEFINE = 1;
const VIR_SNAPSHOT_CREATE_CURRENT = 2;
const VIR_SNAPSHOT_CREATE_NO_METADATA = 4;
const VIR_SNAPSHOT_CREATE_HALT = 8;
const VIR_SNAPSHOT_CREATE_DISK_ONLY = 16;
const VIR_SNAPSHOT_CREATE_REUSE_EXT = 32;
const VIR_SNAPSHOT_CREATE_QUIESCE = 64;
const VIR_SNAPSHOT_CREATE_ATOMIC = 128;
const VIR_SNAPSHOT_CREATE_LIVE = 256;
const VIR_SNAPSHOT_LIST_DESCENDANTS = 1;
const VIR_SNAPSHOT_LIST_ROOTS = 1;
const VIR_SNAPSHOT_LIST_METADATA = 2;
const VIR_SNAPSHOT_LIST_LEAVES = 4;
const VIR_SNAPSHOT_LIST_NO_LEAVES = 8;
const VIR_SNAPSHOT_LIST_NO_METADATA = 16;
const VIR_SNAPSHOT_LIST_INACTIVE = 32;
const VIR_SNAPSHOT_LIST_ACTIVE = 64;
const VIR_SNAPSHOT_LIST_DISK_ONLY = 128;
const VIR_SNAPSHOT_LIST_INTERNAL = 256;
const VIR_SNAPSHOT_LIST_EXTERNAL = 512;
const VIR_SNAPSHOT_REVERT_RUNNING = 1;
const VIR_SNAPSHOT_REVERT_PAUSED = 2;
const VIR_SNAPSHOT_REVERT_FORCE = 4;

/* Create flags */
const VIR_DOMAIN_NONE = 0;
const VIR_DOMAIN_START_PAUSED = 1;
const VIR_DOMAIN_START_AUTODESTROY = 2;
const VIR_DOMAIN_START_BYPASS_CACHE = 4;
const VIR_DOMAIN_START_FORCE_BOOT = 8;
const VIR_DOMAIN_START_VALIDATE = 16;

/* Memory constants */
const VIR_MEMORY_VIRTUAL = 1;
const VIR_MEMORY_PHYSICAL = 2;

/* Version checking constants */
const VIR_VERSION_BINDING = 1;
const VIR_VERSION_LIBVIRT = 2;

/* Network constants */
const VIR_NETWORKS_ACTIVE = 1;
const VIR_NETWORKS_INACTIVE = 2;
const VIR_NETWORKS_ALL = VIR_NETWORKS_ACTIVE|VIR_NETWORKS_INACTIVE;
const VIR_CONNECT_LIST_NETWORKS_INACTIVE = 1;
const VIR_CONNECT_LIST_NETWORKS_ACTIVE = 2;
const VIR_CONNECT_LIST_NETWORKS_PERSISTENT = 4;
const VIR_CONNECT_LIST_NETWORKS_TRANSIENT = 8;
const VIR_CONNECT_LIST_NETWORKS_AUTOSTART = 16;
const VIR_CONNECT_LIST_NETWORKS_NO_AUTOSTART = 32;

/* Credential constants */
const VIR_CRED_USERNAME = 1;
const VIR_CRED_AUTHNAME = 2;
/* RFC 1766 languages */
const VIR_CRED_LANGUAGE = 3;
/* Client supplied a nonce */
const VIR_CRED_CNONCE = 4;
/* Passphrase secret */
const VIR_CRED_PASSPHRASE = 5;
/* Challenge response */
const VIR_CRED_ECHOPROMPT = 6;
/* Challenge response */
const VIR_CRED_NOECHOPROMPT = 7;
/* Authentication realm */
const VIR_CRED_REALM = 8;
/* Externally managed credential More may be added - expect the unexpected */
const VIR_CRED_EXTERNAL = 9;

/* Domain memory constants */
/* The total amount of memory written out to swap space (in kB). */
const VIR_DOMAIN_MEMORY_STAT_SWAP_IN = 0;
/* Page faults occur when a process makes a valid access to virtual memory that is not available. */
/* When servicing the page fault, if disk IO is * required, it is considered a major fault. If not, */
/* it is a minor fault. * These are expressed as the number of faults that have occurred. */
const VIR_DOMAIN_MEMORY_STAT_SWAP_OUT = 1;
const VIR_DOMAIN_MEMORY_STAT_MAJOR_FAULT = 2;
/* The amount of memory left completely unused by the system. Memory that is available but used for */
/* reclaimable caches should NOT be reported as free. This value is expressed in kB. */
const VIR_DOMAIN_MEMORY_STAT_MINOR_FAULT = 3;
/* The total amount of usable memory as seen by the domain. This value * may be less than the amount */
/* of memory assigned to the domain if a * balloon driver is in use or if the guest OS does not initialize */
/* all * assigned pages. This value is expressed in kB.  */
const VIR_DOMAIN_MEMORY_STAT_UNUSED = 4;
/* The number of statistics supported by this version of the interface. To add new statistics, add them */
/* to the enum and increase this value. */
const VIR_DOMAIN_MEMORY_STAT_AVAILABLE = 5;
/* Current balloon value (in KB). */
const VIR_DOMAIN_MEMORY_STAT_ACTUAL_BALLOON = 6;
/* Resident Set Size of the process running the domain. This value is in kB */
const VIR_DOMAIN_MEMORY_STAT_RSS = 7;
/* The number of statistics supported by this version of the interface. */
/* To add new statistics, add them to the enum and increase this value. */
const VIR_DOMAIN_MEMORY_STAT_NR = 8;

/* Job constants */
const VIR_DOMAIN_JOB_NONE = 0;
/* Job with a finite completion time */
const VIR_DOMAIN_JOB_BOUNDED = 1;
/* Job without a finite completion time */
const VIR_DOMAIN_JOB_UNBOUNDED = 2;
/* Job has finished but it's not cleaned up yet */
const VIR_DOMAIN_JOB_COMPLETED = 3;
/* Job hit error but it's not cleaned up yet */
const VIR_DOMAIN_JOB_FAILED = 4;
/* Job was aborted but it's not cleanup up yet */
const VIR_DOMAIN_JOB_CANCELLED = 5;

const VIR_DOMAIN_BLOCK_COMMIT_SHALLOW = 1;
const VIR_DOMAIN_BLOCK_COMMIT_DELETE = 2;
const VIR_DOMAIN_BLOCK_COMMIT_ACTIVE = 4;
const VIR_DOMAIN_BLOCK_COMMIT_RELATIVE = 8;
const VIR_DOMAIN_BLOCK_COMMIT_BANDWIDTH_BYTES = 16;

const VIR_DOMAIN_BLOCK_COPY_SHALLOW = 1;
const VIR_DOMAIN_BLOCK_COPY_REUSE_EXT = 2;

const VIR_DOMAIN_BLOCK_JOB_ABORT_ASYNC = 1;
const VIR_DOMAIN_BLOCK_JOB_ABORT_PIVOT = 2;
const VIR_DOMAIN_BLOCK_JOB_SPEED_BANDWIDTH_BYTES = 1;

const VIR_DOMAIN_BLOCK_JOB_INFO_BANDWIDTH_BYTES = 1;

const VIR_DOMAIN_BLOCK_JOB_TYPE_UNKNOWN = 0;
const VIR_DOMAIN_BLOCK_JOB_TYPE_PULL = 1;
const VIR_DOMAIN_BLOCK_JOB_TYPE_COPY = 2;
const VIR_DOMAIN_BLOCK_JOB_TYPE_COMMIT = 3;
const VIR_DOMAIN_BLOCK_JOB_TYPE_ACTIVE_COMMIT = 4;

const VIR_DOMAIN_BLOCK_PULL_BANDWIDTH_BYTES = 128;

const VIR_DOMAIN_BLOCK_REBASE_SHALLOW = 1;
const VIR_DOMAIN_BLOCK_REBASE_REUSE_EXT = 2;
const VIR_DOMAIN_BLOCK_REBASE_COPY_RAW = 4;
const VIR_DOMAIN_BLOCK_REBASE_COPY = 8;
const VIR_DOMAIN_BLOCK_REBASE_RELATIVE = 16;
const VIR_DOMAIN_BLOCK_REBASE_COPY_DEV = 32;
const VIR_DOMAIN_BLOCK_REBASE_BANDWIDTH_BYTES = 64;

const VIR_DOMAIN_BLOCK_RESIZE_BYTES = 1;

/* Migration constants */
const VIR_MIGRATE_LIVE = 1;
/* direct source -> dest host control channel Note the less-common spelling that we're stuck with: */
/* VIR_MIGRATE_TUNNELLED should be VIR_MIGRATE_TUNNELED */
const VIR_MIGRATE_PEER2PEER = 2;
/* tunnel migration data over libvirtd connection */
const VIR_MIGRATE_TUNNELLED = 4;
/* persist the VM on the destination */
const VIR_MIGRATE_PERSIST_DEST = 8;
/* undefine the VM on the source */
const VIR_MIGRATE_UNDEFINE_SOURCE = 16;
/* pause on remote side */
const VIR_MIGRATE_PAUSED = 32;
/* migration with non-shared storage with full disk copy */
const VIR_MIGRATE_NON_SHARED_DISK = 64;
/* migration with non-shared storage with incremental copy (same base image shared between source and destination) */
const VIR_MIGRATE_NON_SHARED_INC = 128;
/* protect for changing domain configuration through the whole migration process; this will be used automatically
 when supported */
const VIR_MIGRATE_CHANGE_PROTECTION = 256;
/* force migration even if it is considered unsafe */
const VIR_MIGRATE_UNSAFE = 512;
/* offline migrate */
const VIR_MIGRATE_OFFLINE = 1024;
/* compress data during migration */
const VIR_MIGRATE_COMPRESSED = 2048;
/* abort migration on I/O errors happened during migration */
const VIR_MIGRATE_ABORT_ON_ERROR = 4096;
/* force convergence */
const VIR_MIGRATE_AUTO_CONVERGE = 8192;

/* Modify device allocation based on current domain state */
const VIR_DOMAIN_DEVICE_MODIFY_CURRENT = 0;
/* Modify live device allocation */
const VIR_DOMAIN_DEVICE_MODIFY_LIVE = 1;
/* Modify persisted device allocation */
const VIR_DOMAIN_DEVICE_MODIFY_CONFIG = 2;
/* Forcibly modify device (ex. force eject a cdrom) */
const VIR_DOMAIN_DEVICE_MODIFY_FORCE = 4;

/* REGISTER_LONG_CONSTANT */
const VIR_STORAGE_POOL_BUILD_NEW = 0;
/* Repair / reinitialize */
const VIR_STORAGE_POOL_BUILD_REPAIR = 1;
/* Extend existing pool */
const VIR_STORAGE_POOL_BUILD_RESIZE = 2;

/* Domain flags */
const VIR_DOMAIN_FLAG_FEATURE_ACPI = 1;
const VIR_DOMAIN_FLAG_FEATURE_APIC = 2;
const VIR_DOMAIN_FLAG_FEATURE_PAE = 4;
const VIR_DOMAIN_FLAG_CLOCK_LOCALTIME = 8;
const VIR_DOMAIN_FLAG_TEST_LOCAL_VNC = 16;
const VIR_DOMAIN_FLAG_SOUND_AC97 = 32;
const VIR_DOMAIN_DISK_FILE = 1;
const VIR_DOMAIN_DISK_BLOCK = 2;
const VIR_DOMAIN_DISK_ACCESS_ALL = 4;

const VIR_CONNECT_GET_ALL_DOMAINS_STATS_ACTIVE = 1;
const VIR_CONNECT_GET_ALL_DOMAINS_STATS_INACTIVE = 2;
const VIR_CONNECT_GET_ALL_DOMAINS_STATS_OTHER = 4;
const VIR_CONNECT_GET_ALL_DOMAINS_STATS_PAUSED = 8;
const VIR_CONNECT_GET_ALL_DOMAINS_STATS_PERSISTENT = 16;
const VIR_CONNECT_GET_ALL_DOMAINS_STATS_RUNNING = 32;
const VIR_CONNECT_GET_ALL_DOMAINS_STATS_SHUTOFF = 64;
const VIR_CONNECT_GET_ALL_DOMAINS_STATS_TRANSIENT = 128;
const VIR_CONNECT_GET_ALL_DOMAINS_STATS_ENFORCE_STATS = 2 ^ 31;

const VIR_DOMAIN_MEM_CONFIG = VIR_DOMAIN_AFFECT_CONFIG;
const VIR_DOMAIN_MEM_CURRENT = VIR_DOMAIN_AFFECT_CURRENT;
const VIR_DOMAIN_MEM_LIVE = VIR_DOMAIN_AFFECT_LIVE;
const VIR_DOMAIN_MEM_MAXIMUM = 4;

const VIR_DOMAIN_INTERFACE_ADDRESSES_SRC_LEASE = 0;
const VIR_DOMAIN_INTERFACE_ADDRESSES_SRC_AGENT = 1;
const VIR_DOMAIN_INTERFACE_ADDRESSES_SRC_ARP = VIR_DOMAIN_INTERFACE_ADDRESSES_SRC_LEASE;

/* Connect flags */
const VIR_CONNECT_FLAG_SOUNDHW_GET_NAMES = 1;

/* Keycodeset constants */
const VIR_KEYCODE_SET_LINUX = 0;
const VIR_KEYCODE_SET_XT = 1;
const VIR_KEYCODE_SET_ATSET1 = 6;
const VIR_KEYCODE_SET_ATSET2 = 2;
const VIR_KEYCODE_SET_ATSET3 = 3;
const VIR_KEYCODE_SET_OSX = 4;
const VIR_KEYCODE_SET_XT_KBD = 5;
const VIR_KEYCODE_SET_USB = 6;
const VIR_KEYCODE_SET_WIN32 = 7;
const VIR_KEYCODE_SET_RFB = 8;

/* virDomainUndefineFlagsValues */
const VIR_DOMAIN_UNDEFINE_MANAGED_SAVE = 1;
const VIR_DOMAIN_UNDEFINE_SNAPSHOTS_METADATA = 2;
const VIR_DOMAIN_UNDEFINE_NVRAM = 4;
const VIR_DOMAIN_UNDEFINE_KEEP_NVRAM = 8;

/* Connect functions */

/**
 * Function is used to connect to the specified libvirt daemon using the specified URL, user can also set the readonly
 * flag and/or set credentials for connection
 * @param string $url URI for connection
 * @param bool $readonly [optional] flag whether to use read-only connection or not, default true
 * @param array $credentials [optional] array of connection credentials
 * @return resource libvirt connection resource
 * @since 0.4.1(-1)
 */
function libvirt_connect(string $url, bool $readonly = true, array $credentials) {}

/**
 * Query statistics for all domains on a given connection
 * @param resource $conn resource for connection
 * @param int $stats [optional] the statistic groups from VIR_DOMAIN_STATS_*
 * @param int $flags [optional] the statistic groups from VIR_DOMAIN_STATS_*
 * @return array|false assoc array with statistics or false on error
 * @since 0.5.1(-1)
 */
function libvirt_connect_get_all_domain_stats($conn, int $stats = 0, int $flags = 0): array|false {}

/**
 * Function is used to get the capabilities information from the connection
 * @param resource $conn resource for connection
 * @param string|null $xpath [optional] xPath query to be applied on the result
 * @return string capabilities XML from the connection or FALSE for error
 * @since 0.4.1(-2)
 */
function libvirt_connect_get_capabilities($conn, ?string $xpath): string {}

/**
 * Function is used to get the emulator for requested connection/architecture
 * @param resource $conn libvirt connection resource
 * @param string|null $arch [optional] architecture string, can be NULL to get default
 * @return string path to the emulator
 * @since 0.4.5
 */
function libvirt_connect_get_emulator($conn, ?string $arch): string {}

/**
 * Function is used to get the information whether the connection is encrypted or not
 * @param resource $conn resource for connection
 * @return int 1 if encrypted, 0 if not encrypted, -1 on error
 * @since 0.4.1(-2)
 */
function libvirt_connect_get_encrypted($conn): int {}

/**
 * Function is used to get the hostname of the guest associated with the connection
 * @param resource $conn resource for connection
 * @return string|false hostname of the host node or FALSE for error
 * @since 0.4.1(-1)
 */
function libvirt_connect_get_hostname($conn): string|false {}

/**
 * Function is used to get the information about the hypervisor on the connection identified by the connection pointer
 * @param resource $conn libvirt-php: PHP API Reference guide
 * @return array array of hypervisor information if available
 */
function libvirt_connect_get_hypervisor($conn): array {}

/**
 * Function is used to get the information about the connection
 * @param resource $conn resource for connection
 * @return array array of information about the connection
 * @since 0.4.1(-2)
 */
function libvirt_connect_get_information($conn): array {}

/**
 * Function is used to get machine types supported by hypervisor on the connection
 * @param resource $conn resource for connection
 * @return array array of machine types for the connection incl. maxCpus if appropriate
 * @since 0.4.9
 */
function libvirt_connect_get_machine_types($conn): array {}

/**
 * Function is used to get maximum number of VCPUs per VM on the hypervisor connection
 * @param resource $conn resource for connection
 * @return int|false number of VCPUs available per VM on the connection or FALSE for error
 * @since 0.4.1(-2)
 */
function libvirt_connect_get_maxvcpus($conn): int|false {}

/**
 * Function is used to get NIC models for requested connection/architecture
 * @param resource $conn libvirt connection resource
 * @param string|null $arch [optional] architecture string, can be NULL to get default
 * @return array array of models
 * @since 0.4.9
 */
function libvirt_connect_get_nic_models($conn, ?string $arch): array {}

/**
 * Function is used to get the information whether the connection is secure or not
 * @param resource $conn resource for connection
 * @return int 1 if secure, 0 if not secure, -1 on error
 * @since 0.4.1(-2)
 */
function libvirt_connect_get_secure($conn): int {}

/**
 * Function is used to get sound hardware models for requested connection/architecture
 * @param resource $conn libvirt connection resource
 * @param string|null $arch [optional] architecture string, can be NULL to get default
 * @param int $flags [optional] flags for getting sound hardware. Can be either 0 or VIR_CONNECT_SOUNDHW_GET_NAMES
 * @return array array of models
 * @since 0.4.9
 */
function libvirt_connect_get_soundhw_models($conn, ?string $arch, int $flags = 0): array {}

/**
 * Function is used to get the system information from connection if available
 * @param resource $conn resource for connection
 * @return string|false XML description of system information from the connection or FALSE for error
 * @since 0.4.1(-2)
 */
function libvirt_connect_get_sysinfo($conn): string|false {}

/**
 * Function is used to get the connection URI. This is useful to check the hypervisor type of host machine
 * when using "null" uri to libvirt_connect()
 * @param resource $conn resource for connection
 * @return string|false connection URI string or FALSE for error
 * @since 0.4.1(-1)
 */
function libvirt_connect_get_uri($conn): string|false {}

/* Domain functions */

/**
 * Function is used to attach a virtual device to a domain
 * @param resource $res libvirt domain resource
 * @param string $xml XML description of one device
 * @param int $flags [optional] flags
 * @return bool TRUE for success, FALSE on error
 * @since 0.5.3
 */
function libvirt_domain_attach_device($res, string $xml, int $flags = 0): bool {}

/**
 * Function is used to commit block job
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @param string $disk path to the block device, or device shorthand
 * @param string|null $base [optional] path to backing file to merge into, or device shorthand, or NULL for default
 * @param string|null $top [optional] path to file within backing chain that contains data to be merged,
 *                                    or device shorthand, or NULL to merge all possible data
 * @param int $bandwidth [optional] specify bandwidth limit; flags determine the unit
 * @param int $flags [optional] bitwise-OR of VIR_DOMAIN_BLOCK_COMMIT_*
 * @return bool true on success fail on error
 * @since 0.5.2(-1)
 */
function libvirt_domain_block_commit($res, string $disk, ?string $base, ?string $top, int $bandwidth = 0, int $flags = 0): bool {}

/**
 * Function is used to abort block job
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @param string $path device path to resize
 * @param int $flags [optional] bitwise-OR of VIR_DOMAIN_BLOCK_JOB_ABORT_*
 * @return bool true on success fail on error
 * @since 0.5.1(-1)
 */
function libvirt_domain_block_job_abort($res, string $path, int $flags = 0): bool {}

/**
 * Function is used to attach a virtual device to a domain
 * @param resource $res libvirt domain resource
 * @param string $disk path to the block device, or device shorthand
 * @param int $flags [optional] bitwise-OR of VIR_DOMAIN_BLOCK_COMMIT_*
 * @return array Array with status virDomainGetBlockJobInfo and blockjob information
 * @since 0.5.2(-1)
 */
function libvirt_domain_block_job_info($res, string $disk, int $flags = 0): array {}

/**
 * Function is used to set speed of block job
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @param string $path device path to resize
 * @param int $bandwidth bandwidth
 * @param int $flags [optional] bitwise-OR of VIR_DOMAIN_BLOCK_JOB_SPEED_BANDWIDTH_*
 * @return bool true on success fail on error
 * @since 0.4.1(-1)
 */
function libvirt_domain_block_job_set_speed($res, string $path, int $bandwidth, int $flags = 0): bool {}

/**
 * Function is used to resize the domain's block device
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @param string $path device path to resize
 * @param int $size size of device
 * @param int $flags [optional] bitwise-OR of VIR_DOMAIN_BLOCK_RESIZE_*
 * @return bool true on success fail on error
 * @since 0.5.1(-1)
 */
function libvirt_domain_block_resize($res, string $path, int $size, int $flags = 0): bool {}

/**
 * Function is used to get the domain's block stats
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @param string $path device path to get statistics about
 * @return array domain block stats array, fields are rd_req, rd_bytes, wr_req, wr_bytes and errs
 * @since 0.4.1(-1)
 */
function libvirt_domain_block_stats($res, string $path): array {}

/**
 * Function is used to change the domain boot devices
 * @param resource $res libvirt domain resource
 * @param string $first first boot device to be set
 * @param string $second second boot device to be set
 * @param int $flags [optional] flags
 * @return resource new domain resource
 * @since 0.4.2
 */
function libvirt_domain_change_boot_devices($res, string $first, string $second, int $flags = 0) {}

/**
 * Function is used to change the domain memory allocation
 * @param resource $res libvirt domain resource
 * @param int $allocMem number of MiBs to be set as immediate memory value
 * @param int $allocMax number of MiBs to be set as the maximum allocation
 * @param int $flags [optional] flags
 * @return resource new domain resource
 * @since 0.4.2
 */
function libvirt_domain_change_memory($res, int $allocMem, int $allocMax, int $flags = 0) {}

/**
 * Function is used to change the VCPU count for the domain
 * @param resource $res libvirt domain resource
 * @param int $numCpus number of VCPUs to be set for the guest
 * @param int $flags [optional] flags for virDomainSetVcpusFlags
 *                              (available at http://libvirt.org/html/libvirt-libvirt.html#virDomainVcpuFlags)
 * @return bool true on success, false on error
 * @since 0.4.2
 */
function libvirt_domain_change_vcpus($res, int $numCpus, int $flags = 0): bool {}

/**
 * Function is used to dump core of the domain identified by it's resource
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @param string $to to
 * @return bool TRUE for success, FALSE on error
 * @since 0.4.1(-2)
 */
function libvirt_domain_core_dump($res, string $to): bool {}

/**
 * Function is used to create the domain identified by it's resource
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @return bool result of domain creation (startup)
 * @since 0.4.1(-1)
 */
function libvirt_domain_create($res): bool {}

/**
 * Function is used to create the domain identified by it's resource
 * @param resource $conn libvirt connection resource
 * @param string $xml XML string to create guest from
 * @param int $flags [optional] flags
 * @return resource newly started/created domain resource
 * @since 0.4.1(-1)
 */
function libvirt_domain_create_xml($conn, string $xml, int $flags = 0) {}

/**
 * Function is used to define the domain from XML string
 * @param resource $conn libvirt connection resource
 * @param string $xml XML string to define guest from
 * @return resource newly defined domain resource
 * @since 0.4.1(-1)
 */
function libvirt_domain_define_xml($conn, string $xml) {}

/**
 * Function is used to destroy the domain identified by it's resource
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @return bool result of domain destroy
 * @since 0.4.1(-1)
 */
function libvirt_domain_destroy($res): bool {}

/**
 * Function is used to detach a virtual device from a domain
 * @param resource $res libvirt domain resource
 * @param string $xml XML description of one device
 * @param int $flags [optional] flags to control how the device is attached. Defaults to VIR_DOMAIN_AFFECT_LIVE
 * @return bool TRUE for success, FALSE on error
 * @since 0.5.3
 */
function libvirt_domain_detach_device($res, string $xml, int $flags = VIR_DOMAIN_AFFECT_LIVE): bool {}

/**
 * Function is used to add the disk to the virtual machine using set of API functions to make it as simple
 * as possible for the user
 * @param resource $res libvirt domain resource
 * @param string $img string for the image file on the host system
 * @param string $dev string for the device to be presented to the guest (e.g. hda)
 * @param string $typ bus type for the device in the guest, usually 'ide' or 'scsi'
 * @param string $driver driver type to be specified, like 'raw' or 'qcow2'
 * @param int $flags [optional] flags for getting the XML description
 * @return resource new domain resource
 * @since 0.4.2
 */
function libvirt_domain_disk_add($res, string $img, string $dev, string $typ, string $driver, int $flags = 0) {}

/**
 * Function is used to remove the disk from the virtual machine using set of API functions to make it
 * as simple as possible
 * @param resource $res libvirt domain resource
 * @param string $dev string for the device to be removed from the guest (e.g. 'hdb')
 * @param int $flags [optional] flags for getting the XML description
 * @return resource new domain resource
 * @since 0.4.2
 */
function libvirt_domain_disk_remove($res, string $dev, int $flags = 0) {}

/**
 * Function is getting the autostart value for the domain
 * @param resource $res libvirt domain resource
 * @return int autostart value or -1
 * @since 0.4.1(-1)
 */
function libvirt_domain_get_autostart($res): int {}

/**
 * Function is used to get the domain's block device information
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @param string $dev device to get block information about
 * @return array domain block device information array of device, file or partition, capacity,
 *               allocation and physical size
 * @since 0.4.1(-1)
 */
function libvirt_domain_get_block_info($res, string $dev): array {}

/**
 * Function is used to get the domain's connection resource. This function should *not* be used!
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @return resource libvirt connection resource
 * @since 0.4.1(-1)
 */
function libvirt_domain_get_connect($res) {}

/**
 * Function is getting domain counts for all, active and inactive domains
 * @param resource $conn libvirt connection resource from libvirt_connect()
 * @return array array of total, active and inactive (but defined) domain counts
 * @since 0.4.1(-1)
 */
function libvirt_domain_get_counts($conn): array {}

/**
 * Function is used to get disk devices for the domain
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @return array|false list of domain disk devices
 * @since 0.4.4
 */
function libvirt_domain_get_disk_devices($res): array|false {}

/**
 * Function is used to get the domain's ID, applicable to running guests only
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @return int running domain ID or -1 if not running
 * @since 0.4.1(-1)
 */
function libvirt_domain_get_id($res): int {}

/**
 * Function is used to get the domain's information
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @return array domain information array
 * @since 0.4.4
 */
function libvirt_domain_get_info($res): array {}

/**
 * Function is used to get network interface devices for the domain
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @return array|false list of domain interface devices
 * @since 0.4.4
 */
function libvirt_domain_get_interface_devices($res): array|false {}

/**
 * Function is used get job information for the domain
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @return array job information array of type, time, data, mem and file fields
 * @since 0.4.1(-1)
 */
function libvirt_domain_get_job_info($res): array {}

/**
 * Function retrieve appropriate domain element given by $type
 * @param resource $res libvirt domain resource
 * @param int $type virDomainMetadataType type of description
 * @param string $uri XML namespace identifier
 * @param int $flags bitwise-OR of virDomainModificationImpact
 * @return string|null|false metadata string, NULL on error or FALSE on API not supported
 * @since 0.4.9
 */
function libvirt_domain_get_metadata($res, int $type, string $uri, int $flags = 0): string|null|false {}

/**
 * Function is used to get domain name from it's resource
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @return string domain name string
 * @since 0.4.1(-1)
 */
function libvirt_domain_get_name($res): string {}

/**
 * Function is used to get the domain's network information
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @param string $mac mac address of the network device
 * @return array domain network info array of MAC address, network name and type of NIC card
 * @since 0.4.1(-1)
 */
function libvirt_domain_get_network_info($res, string $mac): array {}

/**
 * This functions can be used to get the next free slot if you intend to add a new device identified
 * by slot to the domain, e.g. NIC device
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @return array next free slot number for the domain
 * @since 0.4.2
 */
function libvirt_domain_get_next_dev_ids($res): array {}

/**
 * Function get screen dimensions of the VNC window
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @param string $server server string of the host machine
 * @return array|false array of height and width on success, FALSE otherwise
 * @since 0.4.2
 */
function libvirt_domain_get_screen_dimensions($res, string $server): array|false {}

/**
 * Function uses gvnccapture (if available) to get the screenshot of the running domain
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @param string $server server string for the host machine
 * @param int $scancode [optional] integer value of the scancode to be send to refresh screen, default is 10
 * @return string PNG image binary data
 * @since 0.4.2
 */
function libvirt_domain_get_screenshot($res, string $server, int $scancode = 10): string {}

/**
 * Function is trying to get domain screenshot using libvirt virGetDomainScreenshot() API if available
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_get_by_*()
 * @param int $screenID [optional] monitor ID from where to take screenshot
 * @return array array of filename and mime type as type is hypervisor specific,
 *               caller is responsible for temporary file deletion
 * @since 0.4.5
 */
function libvirt_domain_get_screenshot_api($res, int $screenID = 0): array {}

/**
 * Function is used to get the domain's UUID in binary format
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @return string domain UUID in binary format
 * @since 0.4.1(-1)
 */
function libvirt_domain_get_uuid($res): string {}

/**
 * Function is used to get the domain's UUID in string format
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @return string domain UUID string
 * @since 0.4.1(-1)
 */
function libvirt_domain_get_uuid_string($res): string {}

/**
 * Function is used to get the domain's XML description
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @param string|null $xpath xPath expression string to get just this entry, can be NULL
 * @param int $flags [optional] flags
 * @return string domain XML description string or result of xPath expression
 * @since 0.4.2
 */
function libvirt_domain_get_xml_desc($res, ?string $xpath, int $flags = 0): string {}

/**
 * Function is used to get network interface addresses for the domain
 * @param resource $res libvirt domain resource
 * @param int $source one of the VIR_DOMAIN_ADDRESSES_SRC_* flags
 * @return array|false interface array of a domain holding information about addresses resembling
 *                     the virDomainInterface structure, false on error
 * @since 0.5.5
 */
function libvirt_domain_interface_addresses($res, int $source): array|false {}

/**
 * Function is used to get the domain's interface stats
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @param string $path path to interface device
 * @return array interface stats array of {tx|rx}_{bytes|packets|errs|drop} fields
 * @since 0.4.1(-1)
 */
function libvirt_domain_interface_stats($res, string $path): array {}

/**
 * Function is getting information whether domain identified by resource is active or not
 * @param resource $res libvirt domain resource
 * @return bool virDomainIsActive() result on the domain
 * @since 0.4.1(-1)
 */
function libvirt_domain_is_active($res): bool {}

/**
 * Function to get information whether domain is persistent or not
 * @param resource $res libvirt domain resource
 * @return bool TRUE for persistent, FALSE for not persistent, -1 on error
 * @since 0.4.9
 */
function libvirt_domain_is_persistent($res): bool {}

/**
 * Function is used to get domain by it's ID, applicable only to running guests
 * @param resource $conn libvirt connection resource from libvirt_connect()
 * @param string $id domain id to look for
 * @return resource libvirt domain resource
 * @since 0.4.1(-1)
 */
function libvirt_domain_lookup_by_id($conn, string $id) {}

/**
 * Function is used to lookup for domain by it's name
 * @param resource $res libvirt connection resource from libvirt_connect()
 * @param string $name domain name to look for
 * @return resource libvirt domain resource
 * @since 0.4.1(-1)
 */
function libvirt_domain_lookup_by_name($res, string $name) {}

/**
 * Function is used to lookup for domain by it's UUID in the binary format
 * @param resource $res libvirt connection resource from libvirt_connect()
 * @param string $uuid binary defined UUID to look for
 * @return resource libvirt domain resource
 * @since 0.4.1(-1)
 */
function libvirt_domain_lookup_by_uuid($res, string $uuid) {}

/**
 * Function is used to get the domain by it's UUID that's accepted in string format
 * @param resource $res libvirt connection resource from libvirt_connect()
 * @param string $uuid domain UUID [in string format] to look for
 * @return resource libvirt domain resource
 * @since 0.4.1(-1)
 */
function libvirt_domain_lookup_by_uuid_string($res, string $uuid) {}

/**
 * Function is used to managed save the domain (domain was unloaded from memory and it state saved to disk)
 * identified by it's resource
 * @param resource $res TRUE for success, FALSE on error
 * @return bool TRUE for success, FALSE on error
 * @since 0.4.1(-1)
 */
function libvirt_domain_managedsave($res): bool {}

/**
 * Function is used to get the domain's memory peek value
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @param int $start start
 * @param int $size size
 * @param int $flags flags
 * @return int domain memory peek
 * @since 0.4.1(-1)
 */
function libvirt_domain_memory_peek($res, int $start, int $size, int $flags = 0): int {}

/**
 * Function is used to get the domain's memory stats
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @param int $flags [optional] flags
 * @return array domain memory stats array (same fields as virDomainMemoryStats, please see libvirt documentation)
 * @since 0.4.1(-1)
 */
function libvirt_domain_memory_stats($res, int $flags = 0): array {}

/**
 * Function is used migrate domain to another domain
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @param string $dest_conn destination host connection object
 * @param int $flags migration flags
 * @param string $dname [optional] domain name to rename domain to on destination side
 * @param int $bandwidth [optional] migration bandwidth in Mbps
 * @return resource libvirt domain resource for migrated domain
 * @since 0.4.1(-1)
 */
function libvirt_domain_migrate($res, string $dest_conn, int $flags, string $dname, int $bandwidth = 0) {}

/**
 * Function is used migrate domain to another libvirt daemon specified by it's URI
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @param string $dest_uri destination URI to migrate to
 * @param int $flags migration flags
 * @param string $dname [optional] domain name to rename domain to on destination side
 * @param int $bandwidth [optional] migration bandwidth in Mbps
 * @return bool TRUE for success, FALSE on error
 * @since 0.4.1(-1)
 */
function libvirt_domain_migrate_to_uri($res, string $dest_uri, int $flags, string $dname, int $bandwidth = 0): bool {}

/**
 * Function is used migrate domain to another libvirt daemon specified by it's URI
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @param string $dconnuri URI for target libvirtd
 * @param string $miguri URI for invoking the migration
 * @param string $dxml XML config for launching guest on target
 * @param int $flags migration flags
 * @param string $dname [optional] domain name to rename domain to on destination side
 * @param int $bandwidth [optional] migration bandwidth in Mbps
 * @return bool TRUE for success, FALSE on error
 * @since 0.4.6(-1)
 */
function libvirt_domain_migrate_to_uri2($res, string $dconnuri, string $miguri, string $dxml, int $flags, string $dname, int $bandwidth = 0): bool {}

/**
 * Function is used to install a new virtual machine to the machine
 * @param resource $conn libvirt connection resource
 * @param string $name name of the new domain
 * @param string|null|false $arch optional architecture string, can be NULL to get default (or false)
 * @param int $memMB number of megabytes of RAM to be allocated for domain
 * @param int $maxmemMB maximum number of megabytes of RAM to be allocated for domain
 * @param int $vcpus number of VCPUs to be allocated to domain
 * @param string $iso_image installation ISO image for domain
 * @param array $disks array of disk devices for domain, consist of keys as 'path' (storage location),
 *                     'driver' (image type, e.g. 'raw' or 'qcow2'), 'bus' (e.g. 'ide', 'scsi'),
 *                     'dev' (device to be presented to the guest - e.g. 'hda'),
 *                     'size' (with 'M' or 'G' suffixes, like '10G' for 10 gigabytes image etc.) and
 *                     'flags' (VIR_DOMAIN_DISK_FILE or VIR_DOMAIN_DISK_BLOCK, optionally VIR_DOMAIN_DISK_ACCESS_ALL
 *                     to allow access to the disk for all users on the host system)
 * @param array $networks array of network devices for domain, consists of keys as 'mac' (for MAC address),
 *                        'network' (for network name) and optional 'model' for model of NIC device
 * @param int $flags [optional] bit array of flags
 * @return resource a new domain resource
 * @since 0.4.5
 */
function libvirt_domain_new($conn, string $name, string|null|false $arch, int $memMB, int $maxmemMB, int $vcpus, string $iso_image, array $disks, array $networks, int $flags = 0) {}

/**
 * Function is used to get the VNC server location for the newly created domain (newly started installation)
 * @return string|null a VNC server for a newly created domain resource (if any)
 * @since 0.4.5
 */
function libvirt_domain_new_get_vnc(): string|null {}

/**
 * Function is used to add the NIC card to the virtual machine using set of API functions to make it as simple
 * as possible for the user
 * @param resource $res libvirt domain resource
 * @param string $mac libvirt domain resource
 * @param string $network network name where to connect this NIC
 * @param string $model string of the NIC model
 * @param int $flags [optional] flags for getting the XML description
 * @return resource new domain resource
 * @since 0.4.2
 */
function libvirt_domain_nic_add($res, string $mac, string $network, string $model, int $flags = 0) {}

/**
 * Function is used to remove the NIC from the virtual machine using set of API functions to make it
 * as simple as possible
 * @param resource $res libvirt domain resource
 * @param string $dev string representation of the IP address to be removed (e.g. 54:52:00:xx:yy:zz)
 * @param int $flags [optional] flags for getting the XML description
 * @return resource new domain resource
 * @since 0.4.2
 */
function libvirt_domain_nic_remove($res, string $dev, int $flags = 0) {}

/**
 * Function is used to send qemu-ga command
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @param string $cmd command
 * @param int $timeout [optional] timeout
 * @param int $flags [optional] unknown
 * @return string|false String on success and FALSE on error
 * @since 0.5.2(-1)
 */
function libvirt_domain_qemu_agent_command($res, string $cmd, $timeout = -1, int $flags = 0): string|false {}

/**
 * Function is used to reboot the domain identified by it's resource
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @param int $flags [optional] flags
 * @return bool TRUE for success, FALSE on error
 * @since 0.4.1(-1)
 */
function libvirt_domain_reboot($res, int $flags = 0): bool {}

/**
 * Function is used to reset the domain identified by its resource
 * @param resource $res libvirt domain resource
 * @param int $flags [optional] @flags
 * @return bool true on success, false on error
 * @since 0.5.5
 */
function libvirt_domain_reset($res, int $flags = 0): bool {}

/**
 * Function is used to resume the domain identified by it's resource
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @return resource result of domain resume
 * @since 0.4.1(-1)
 */
function libvirt_domain_resume($res) {}

/**
 * Function sends keys to domain via libvirt API
 * @param resource $res libvirt domain resource
 * @param int $codeset the codeset of keycodes, from virKeycodeSet
 * @param int $holdtime the duration (in milliseconds) that the keys will be held
 * @param array $keycodes array of keycodes
 * @param int $flags [optional] extra flags; not used yet so callers should always pass 0
 * @return bool TRUE for success, FALSE for failure
 * @since 0.5.3
 */
function libvirt_domain_send_key_api($res, int $codeset, int $holdtime, array $keycodes, int $flags = 0): bool {}

/**
 * Function sends keys to the domain's VNC window
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @param string $server server string of the host machine
 * @param int $scancode integer scancode to be sent to VNC window
 * @return bool TRUE on success, FALSE otherwise
 * @since 0.4.2
 */
function libvirt_domain_send_keys($res, string $server, int $scancode): bool {}

/**
 * Function sends keys to the domain's VNC window
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @param string $server server string of the host machine
 * @param int $pos_x position on x-axis
 * @param int $pos_y position on y-axis
 * @param int $clicked mask of clicked buttons (0 for none, bit 1 for button #1, bit 8 for button #8)
 * @param bool $release [optional] boolean value (0 or 1) whether to release the buttons automatically once pressed,
 *                      default true
 * @return bool TRUE on success, FALSE otherwise
 * @since 0.4.2
 */
function libvirt_domain_send_pointer_event($res, string $server, int $pos_x, int $pos_y, int $clicked, bool $release = true): bool {}

/**
 * Function is setting the autostart value for the domain
 * @param resource $res libvirt domain resource
 * @param bool $flags flag to enable/disable autostart
 * @return bool TRUE on success, FALSE on error
 * @since 0.4.1(-1)
 */
function libvirt_domain_set_autostart($res, bool $flags): bool {}

/**
 * Function to set max memory for domain
 * @param resource $res libvirt domain resource
 * @param int $memory memory size in 1024 bytes (Kb)
 * @return bool TRUE for success, FALSE for failure
 * @since 0.5.1
 */
function libvirt_domain_set_max_memory($res, int $memory): bool {}

/**
 * Function to set memory for domain
 * @param resource $res libvirt domain resource
 * @param int $memory memory size in 1024 bytes (Kb)
 * @return bool TRUE for success, FALSE for failure
 * @since 0.5.1
 */
function libvirt_domain_set_memory($res, int $memory): bool {}

/**
 * Function to set max memory for domain
 * @param resource $res libvirt domain resource
 * @param int $memory memory size in 1024 bytes (Kb)
 * @param int $flags [optional] bitwise-OR VIR_DOMAIN_MEM_* flags
 * @return bool TRUE for success, FALSE for failure
 * @since 0.5.1
 */
function libvirt_domain_set_memory_flags($res, int $memory = 0, int $flags = 0): bool {}

/**
 * Function sets the appropriate domain element given by $type to the value of $metadata. No new lines are permitted
 * @param resource $res libvirt domain resource
 * @param int $type virDomainMetadataType type of description
 * @param string $metadata new metadata text
 * @param string $key XML namespace key or empty string (alias of NULL)
 * @param string $uri XML namespace identifier or empty string (alias of NULL)
 * @param int $flags bitwise-OR of virDomainModificationImpact
 * @return int -1 on error, 0 on success
 * @since 0.4.9
 */
function libvirt_domain_set_metadata($res, int $type, string $metadata, string $key, string $uri, int $flags = 0): int {}

/**
 * Function is used to shutdown the domain identified by it's resource
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @return bool TRUE for success, FALSE on error
 * @since 0.4.1(-1)
 */
function libvirt_domain_shutdown($res): bool {}

/**
 * Function is used to suspend the domain identified by it's resource
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @return bool TRUE for success, FALSE on error
 * @since 0.4.1(-1)
 */
function libvirt_domain_suspend($res): bool {}

/**
 * Function is used to undefine the domain identified by it's resource
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @return bool TRUE for success, FALSE on error
 * @since 0.4.1(-1)
 */
function libvirt_domain_undefine($res): bool {}

/**
 * Function is used to undefine(with flags) the domain identified by it's resource
 * @param resource $res libvirt domain resource
 * @param int $flags [optional] flags
 * @return bool TRUE if success, FALSE on error
 * @since 999 https://github.com/yzslab/php-libvirt-client
 */
function libvirt_domain_undefine_flags($res, int $flags = 0): bool {}

/**
 * Function is used to update the domain's devices from the XML string
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @param string $xml XML string for the update
 * @param int $flags Flags to update the device (VIR_DOMAIN_DEVICE_MODIFY_CURRENT, VIR_DOMAIN_DEVICE_MODIFY_LIVE,
 *                   VIR_DOMAIN_DEVICE_MODIFY_CONFIG, VIR_DOMAIN_DEVICE_MODIFY_FORCE)
 * @return bool TRUE for success, FALSE on error
 * @since 0.4.1(-1)
 */
function libvirt_domain_update_device($res, string $xml, int $flags): bool {}

/**
 * Function is used to convert native configuration data to libvirt domain XML
 * @param resource $conn libvirt connection resource
 * @param string $format configuration format converting from
 * @param string $config_data content of the native config file
 * @return string|false libvirt domain XML, FALSE on error
 * @since 0.5.3
 */
function libvirt_domain_xml_from_native($conn, string $format, string $config_data): string|false {}

/**
 * Function is used to convert libvirt domain XML to native configuration
 * @param resource $conn libvirt connection resource
 * @param string $format configuration format converting from
 * @param string $xml_data content of the libvirt domain xml file
 * @return string|false contents of the native data file, FALSE on error
 * @since 0.5.3
 */
function libvirt_domain_xml_to_native($conn, string $format, string $xml_data): string|false {}

/**
 * Function is used to get the result of xPath expression that's run against the domain
 * @param resource $res libvirt domain resource, e.g. from libvirt_domain_lookup_by_*()
 * @param string $xpath xPath expression to parse against the domain
 * @param int $flags [optional] flags
 * @return array result of the expression in an array
 * @since 0.4.1(-1)
 */
function libvirt_domain_xml_xpath($res, string $xpath, int $flags = 0): array {}

/**
 * Function is used to list active domain IDs on the connection
 * @param resource $res libvirt connection resource
 * @return array libvirt active domain ids array for the connection
 * @since 0.4.1(-1)
 */
function libvirt_list_active_domain_ids($res): array {}

/**
 * Function is used to list active domain names on the connection
 * @param resource $res libvirt connection resource
 * @return array libvirt active domain names array for the connection
 * @since 0.4.1(-1)
 */
function libvirt_list_active_domains($res): array {}

/**
 * Function is used to list domain resources on the connection
 * @param resource $res libvirt connection resource
 * @return array libvirt domain resources array for the connection
 * @since 0.4.1(-1)
 */
function libvirt_list_domain_resources($res): array {}

/**
 * Function is used to list domains on the connection
 * @param resource $res libvirt connection resource
 * @return array libvirt domain names array for the connection
 * @since 0.4.1(-1)
 */
function libvirt_list_domains($res): array {}

/**
 * Function is used to list inactive domain names on the connection
 * @param resource $res libvirt connection resource
 * @return array libvirt inactive domain names array for the connection
 * @since 0.4.1(-1)
 */
function libvirt_list_inactive_domains($res): array {}

/* Network functions */

/**
 * Function is used to list networks on the connection
 * @param resource $conn libvirt connection resource
 * @param int $flags [optional] flags to filter the results for a smaller list of targeted networks
 *                              (bitwise-OR VIR_CONNECT_LIST_NETWORKS_* constants)
 * @return array libvirt network resources array for the connection
 * @since 0.5.3
 */
function libvirt_list_all_networks($conn, int $flags = VIR_CONNECT_LIST_NETWORKS_ACTIVE|VIR_CONNECT_LIST_NETWORKS_INACTIVE): array {}

/**
 * Function is used to list networks on the connection
 * @param resource $res libvirt connection resource
 * @param int $flags [optional] flags whether to list active,
 *                              inactive or all networks (VIR_NETWORKS_{ACTIVE|INACTIVE|ALL} constants)
 * @return array libvirt network names array for the connection
 * @since 0.4.1(-1)
 */
function libvirt_list_networks($res, int $flags = 0): array {}

/**
 * Function is used to define a new virtual network based on the XML description
 * @param resource $res libvirt connection resource
 * @param string $xml XML string definition of network to be defined
 * @return resource libvirt network resource of newly defined network
 * @since 0.4.2
 */
function libvirt_network_define_xml($res, string $xml) {}

/**
 * Function is used to get the network resource from name
 * @param resource $res libvirt connection resource
 * @param string $name network name string
 * @return resource libvirt network resource
 * @since 0.4.1(-1)
 */
function libvirt_network_get($res, string $name) {}

/**
 * Function is used to get the activity state of the network
 * @param resource $res libvirt network resource
 * @return int|false 1 when active, 0 when inactive, FALSE on error
 * @since 0.4.1(-1)
 */
function libvirt_network_get_active($res): int|false {}

/**
 * Function is getting the autostart value for the network
 * @param resource $res libvirt network resource
 * @return int autostart value or -1 on error
 * @since 0.5.4
 */
function libvirt_network_get_autostart($res): int {}

/**
 * Function is used to get the bridge associated with the network
 * @param resource $res libvirt network resource
 * @return string bridge name string
 * @since 0.4.1(-1)
 */
function libvirt_network_get_bridge($res): string {}

/**
 * Function is used to get the network information
 * @param resource $res libvirt network resource
 * @return array network information array
 * @since 0.4.1(-1)
 */
function libvirt_network_get_information($res): array {}

/**
 * Function is used to get network's name
 * @param resource $res libvirt network resource
 * @return string|false network name string or FALSE on failure
 * @since 0.5.3
 */
function libvirt_network_get_name($res): string|false {}

/**
 * Function is used to get network's UUID in binary format
 * @param resource $res libvirt network resource
 * @return string|false network UUID in binary format or FALSE on failure
 * @since 0.5.3
 */
function libvirt_network_get_uuid($res): string|false {}

/**
 * Function is used to get network's UUID in string format
 * @param resource $res libvirt network resource
 * @return string|false network UUID string or FALSE on failure
 * @since 0.5.3
 */
function libvirt_network_get_uuid_string($res): string|false {}

/**
 * Function is used to get the XML description for the network
 * @param resource $res libvirt network resource
 * @param string|null $xpath [optional] xPath expression string to get just this entry, can be NULL
 * @return string|false network XML string or result of xPath expression
 * @since 0.4.1(-1)
 */
function libvirt_network_get_xml_desc($res, ?string $xpath): string|false {}

/**
 * Function is used to set the activity state of the network
 * @param resource $res libvirt network resource
 * @param int $flags active
 * @return bool TRUE if success, FALSE on error
 * @since 0.4.1(-1)
 */
function libvirt_network_set_active($res, int $flags): bool {}

/**
 * Function is setting the autostart value for the network
 * @param resource $res libvirt network resource
 * @param int $flags flag to enable/disable autostart
 * @return bool TRUE on success, FALSE on error
 * @since 0.5.4
 */
function libvirt_network_set_autostart($res, int $flags): bool {}

/**
 * Function is used to undefine already defined network
 * @param resource $res libvirt network resource
 * @return bool TRUE for success, FALSE on error
 * @since 0.4.2
 */
function libvirt_network_undefine($res): bool {}

/* Node functions */

/**
 * Function is used to get the CPU stats per nodes
 * @param resource $conn resource for connection
 * @param int $cpunr [optional] CPU number to get information about,
 *                              defaults to VIR_NODE_CPU_STATS_ALL_CPUS to get information about all CPUs
 * @return array|false array of node CPU statistics including time (in seconds since UNIX epoch),
 *                     cpu number and total number of CPUs on node or FALSE for error
 * @since 0.4.6
 */
function libvirt_node_get_cpu_stats($conn, int $cpunr = VIR_NODE_CPU_STATS_ALL_CPUS): array|false {}

/**
 * Function is used to get the CPU stats for each CPU on the host node
 * @param resource $conn resource for connection
 * @param int $time [optional] time in seconds to get the information about, without aggregation for further processing
 * @return array|false array of node CPU statistics for each CPU including time (in seconds since UNIX epoch),
 *                           cpu number and total number of CPUs on node or FALSE for error
 * @since 0.4.6
 */
function libvirt_node_get_cpu_stats_for_each_cpu($conn, int $time = 0): array|false {}

/**
 * Function is used to get free memory available on the node
 * @param resource $conn libvirt connection resource
 * @return string|false The available free memory in bytes as string or FALSE for error
 * @since 0.5.3
 */
function libvirt_node_get_free_memory($conn): string|false {}

/**
 * Function is used to get the information about host node, mainly total memory installed,
 * total CPUs installed and model information are useful
 * @param resource $conn resource for connection
 * @return array|false array of node information or FALSE for error
 * @since 0.4.1(-1)
 */
function libvirt_node_get_info($conn): array|false {}

/**
 * Function is used to get the memory stats per node
 * @param resource $conn resource for connection
 * @return array array of node memory statistics including time (in seconds since UNIX epoch) or FALSE for error
 * @since 0.4.6
 */
function libvirt_node_get_mem_stats($conn): array {}

/* Nodedev functions */

/**
 * Function is used to list node devices on the connection
 * @param resource $res libvirt connection resource
 * @param string|null $cap [optional] capability string
 * @return array libvirt nodedev names array for the connection
 * @since 0.4.1(-1)
 */
function libvirt_list_nodedevs($res, ?string $cap): array {}

/**
 * Function is used to list node devices by capabilities
 * @param resource $res libvirt nodedev resource
 * @return array nodedev capabilities array
 * @since 0.4.1(-1)
 */
function libvirt_nodedev_capabilities($res): array {}

/**
 * Function is used to get the node device by it's name
 * @param resource $res libvirt connection resource
 * @param string $name name of the nodedev to get resource
 * @return resource libvirt nodedev resource
 * @since 0.4.1(-1)
 */
function libvirt_nodedev_get($res, string $name) {}

/**
 * Function is used to get the node device's information
 * @param resource $res libvirt nodedev resource
 * @return array nodedev information array
 * @since 0.4.1(-1)
 */
function libvirt_nodedev_get_information($res): array {}

/**
 * Function is used to get the node device's XML description
 * @param resource $res libvirt nodedev resource
 * @param string|null $xpath [optional] xPath expression string to get just this entry, can be NULL
 * @return string nodedev XML description string or result of xPath expression
 * @since 0.4.2
 */
function libvirt_nodedev_get_xml_desc($res, ?string $xpath): string {}

/* Nwfilter functions */

/**
 * Function is used to list nwfilters on the connection
 * @param resource $res libvirt domain resource
 * @return array libvirt nwfilter resources array for the connection
 * @since 0.5.4
 */
function libvirt_list_all_nwfilters($res): array {}

/**
 * Function is used to list nwfilters on the connection
 * @param resource $conn libvirt connection resource
 * @return array libvirt nwfilter names array for the connection
 * @since 0.5.4
 */
function libvirt_list_nwfilters($conn): array {}

/**
 * Function is used to define a new nwfilter based on the XML description
 * @param resource $conn libvirt connection resource
 * @param string $xml XML string definition of nwfilter to be defined
 * @return resource|false libvirt nwfilter resource of newly defined nwfilter or false on error
 * @since 0.5.4
 */
function libvirt_nwfilter_define_xml($conn, string $xml) {}

/**
 * Function is used to get nwfilter's name
 * @param resource $res libvirt nwfilter resource
 * @return string|false nwfilter name string or FALSE on failure
 * @since 0.5.4
 */
function libvirt_nwfilter_get_name($res): string|false {}

/**
 * Function is used to get nwfilter's UUID in binary format
 * @param resource $res libvirt nwfilter resource
 * @return string|false nwfilter UUID in binary format or FALSE on failure
 * @since 0.5.3
 */
function libvirt_nwfilter_get_uuid($res): string|false {}

/**
 * Function is used to get nwfilter's UUID in string format
 * @param resource $res libvirt nwfilter resource
 * @return string|false nwfilter UUID string or FALSE on failure
 * @since 0.5.4
 */
function libvirt_nwfilter_get_uuid_string($res): string|false {}

/**
 * Function is used to lookup for nwfilter identified by UUID string
 * @param resource $res libvirt nwfilter resource
 * @param string|null $xpath [optional] xPath expression string to get just this entry, can be NULL
 * @return string nwfilter XML string or result of xPath expression
 * @since 0.5.4
 */
function libvirt_nwfilter_get_xml_desc($res, ?string $xpath): string {}

/**
 * This functions is used to lookup for the nwfilter by it's name
 * @param resource $conn libvirt connection resource
 * @param string $name name of the nwfilter to get the resource
 * @return resource|false libvirt nwfilter resource
 * @since 0.5.4
 */
function libvirt_nwfilter_lookup_by_name($conn, string $name) {}

/**
 * Function is used to lookup for nwfilter identified by UUID string
 * @param resource $conn libvirt connection resource
 * @param string $uuid UUID string to look for nwfilter
 * @return resource|false libvirt nwfilter resource
 * @since 0.5.4
 */
function libvirt_nwfilter_lookup_by_uuid_string($conn, string $uuid) {}

/**
 * Function is used to undefine already defined nwfilter
 * @param resource $res libvirt nwfilter resource
 * @return bool true on success, false on error
 * @since 0.5.4
 */
function libvirt_nwfilter_undefine($res): bool {}

/* Libvirt functions */

/**
 * Function is used to check major, minor and micro (also sometimes called release) versions of libvirt-php
 * or libvirt itself. This could useful when you want your application to support only versions of libvirt
 * or libvirt-php higher than some version specified
 * @param int $major major version number to check for
 * @param int $minor minor version number to check for
 * @param int $micro micro (also release) version number to check for
 * @param int $type type of checking, VIR_VERSION_BINDING to check against libvirt-php binding or
 *                  VIR_VERSION_LIBVIRT to check against libvirt version
 * @return bool TRUE if version is equal or higher than required, FALSE if not,
 *              FALSE with error [for libvirt_get_last_error()] on unsupported version type check
 * @since 0.4.1(-1)
 */
function libvirt_check_version(int $major, int $minor, int $micro, int $type): bool {}

/**
 * Function to get the ISO images on path and return them in the array
 * @param string $path string of path where to look for the ISO images
 * @return array|false ISO image array on success, FALSE otherwise
 */
function libvirt_get_iso_images(string $path): array|false {}

/**
 * This function is used to get the last error coming either from libvirt or the PHP extension itself
 * @return string last error string
 */
function libvirt_get_last_error(): string {}

/**
 * This function is used to get the last error code coming either from libvirt or the PHP extension itself
 * @since 999 https://github.com/yzslab/php-libvirt-client
 * @return int last error code
 */
function libvirt_get_last_error_code(): int {}

/**
 * This function is used to get the what part of the library raised the last error
 * @since 999 https://github.com/yzslab/php-libvirt-client
 * @return int last error domain
 */
function libvirt_get_last_error_domain(): int {}

/**
 * Function to check for feature existence for working libvirt instance
 * @param string $name feature name
 * @return bool TRUE if feature is supported, FALSE otherwise
 * @since 0.4.1(-3)
 */
function libvirt_has_feature(string $name): bool {}

/**
 * Function is used to create the image of desired name, size and format.
 * The image will be created in the image path (libvirt.image_path INI variable). Works only o
 * @param resource $conn libvirt connection resource
 * @param string $name name of the image file that will be created in the libvirt.image_path directory
 * @param int $size size of the image in MiBs
 * @param string $format format of the image, may be raw, qcow or qcow2
 * @return string|false hostname of the host node or FALSE for error
 * @since 0.4.2
 */
function libvirt_image_create($conn, string $name, int $size, string $format): string|false {}

/**
 * Function is used to create the image of desired name, size and format.
 * The image will be created in the image path (libvirt.image_path INI variable). Works only on local systems!
 * @param resource $conn libvirt connection resource
 * @param string $image name of the image file that should be deleted
 * @return string|false hostname of the host node or FALSE for error
 * @since 0.4.2
 */
function libvirt_image_remove($conn, string $image): string|false {}

/**
 * Function to set the log file for the libvirt module instance
 * @param string|null $filename log filename or NULL to disable logging
 * @param int $maxsize [optional] maximum log file size argument in KiB, default value can be found in PHPInfo() output
 * @return bool TRUE if log file has been successfully set, FALSE otherwise
 * @since 0.4.2
 */
function libvirt_logfile_set(?string $filename, int $maxsize): bool {}

/**
 * Function to print the binding resources, although the resource information are printed,
 * they are returned in the return_value
 * @return resource bindings resource information
 * @since 0.4.2
 */
function libvirt_print_binding_resources() {}

/**
 * Function is used to get libvirt, driver and libvirt-php version numbers. Can be used for information purposes,
 * for version checking please use libvirt_check_version() defined below
 * @param string $type [optional] type string to identify driver to look at
 * @return array libvirt, type (driver) and connector (libvirt-php) version numbers array
 * @since 0.4.1(-1)
 */
function libvirt_version(string $type): array {}

/* Snapshot functions */

/**
 * Function is used to get the information whether domain has the current snapshot
 * @param resource $res libvirt domain resource
 * @param int $flags [optional] extra flags; not used yet so callers should always pass 0
 * @return bool TRUE is domain has the current snapshot, otherwise FALSE (you may need to check
 *              for error using libvirt_get_last_error())
 * @since 0.4.1(-2)
 */
function libvirt_domain_has_current_snapshot($res, int $flags = 0): bool {}

/**
 * This function creates the domain snapshot for the domain identified by it's resource
 * @param resource $res libvirt domain resource
 * @param int $flags [optional] libvirt snapshot flags
 * @return resource domain snapshot resource
 * @since 0.4.1(-2)
 */
function libvirt_domain_snapshot_create($res, int $flags = 0) {}

/**
 * Function is used to lookup the current snapshot for given domain
 * @param resource $res libvirt domain resource
 * @param int $flags [optional] libvirt snapshot flags
 * @return resource domain snapshot resource
 * @since 0.5.6
 */
function libvirt_domain_snapshot_current($res, int $flags = 0) {}

/**
 * Function is used to revert the domain state to the state identified by the snapshot
 * @param resource $res libvirt domain resource
 * @param int $flags [optional] 0 to delete just snapshot,
 *                              VIR_SNAPSHOT_DELETE_CHILDREN to delete snapshot children as well
 * @return bool TRUE on success, FALSE on error
 * @since 0.4.1(-2)
 */
function libvirt_domain_snapshot_delete($res, int $flags = 0): bool {}

/**
 * Function is used to get the XML description of the snapshot identified by it's resource
 * @param resource $res libvirt snapshot resource
 * @param int $flags [optional] libvirt snapshot flags
 * @return string XML description string for the snapshot
 * @since 0.4.1(-2)
 */
function libvirt_domain_snapshot_get_xml($res, int $flags = 0): string {}

/**
 * This functions is used to lookup for the snapshot by it's name
 * @param resource $res libvirt domain resource
 * @param string $name name of the snapshot to get the resource
 * @param int $flags [optional] libvirt snapshot flags
 * @return resource domain snapshot resource
 * @since 0.4.1(-2)
 */
function libvirt_domain_snapshot_lookup_by_name($res, string $name, int $flags = 0) {}

/**
 * Function is used to revert the domain state to the state identified by the snapshot
 * @param resource $res libvirt snapshot resource
 * @param int $flags [optional] libvirt snapshot flags
 * @return bool TRUE on success, FALSE on error
 * @since 0.4.1(-2)
 */
function libvirt_domain_snapshot_revert($res, int $flags = 0): bool {}

/**
 * Function is used to list domain snapshots for the domain specified by it's resource
 * @param resource $res libvirt domain resource
 * @param int $flags [optional] libvirt snapshot flags
 * @return array libvirt domain snapshot names array
 * @since 0.4.1(-2)
 */
function libvirt_list_domain_snapshots($res, int $flags = 0): array {}

/* Storage functions */

/**
 * Function is used to list active storage pools on the connection
 * @param resource $res libvirt connection resource
 * @return array libvirt storagepool names array for the connection
 * @since 0.4.1(-1)
 */
function libvirt_list_active_storagepools($res): array {}

/**
 * Function is used to list inactive storage pools on the connection
 * @param resource $res libvirt connection resource
 * @return array libvirt storagepool names array for the connection
 * @since 0.4.1(-1)
 */
function libvirt_list_inactive_storagepools($res): array {}

/**
 * Function is used to list storage pools on the connection
 * @param resource $res libvirt connection resource
 * @return array libvirt storagepool names array for the connection
 * @since 0.4.1(-1)
 */
function libvirt_list_storagepools($res): array {}

/**
 * Function is used to Build the underlying storage pool, e.g. create the destination directory for NFS
 * @param resource $res libvirt storagepool resource
 * @return bool TRUE if success, FALSE on error
 * @since 0.4.2
 */
function libvirt_storagepool_build($res): bool {}

/**
 * Function is used to create/start the storage pool
 * @param resource $res libvirt storagepool resource
 * @return bool TRUE if success, FALSE on error
 * @since 0.4.1(-1)
 */
function libvirt_storagepool_create($res): bool {}

/**
 * Function is used to define the storage pool from XML string and return it's resource
 * @param resource $res libvirt connection resource
 * @param string $xml XML string definition of storagepool
 * @param int $flags [optional] flags to define XML
 * @return resource libvirt storagepool resource
 * @since 0.4.1(-1)
 */
function libvirt_storagepool_define_xml($res, string $xml, int $flags = 0) {}

/**
 * Function is used to Delete the underlying storage pool, e.g. remove the destination directory for NFS
 * @param resource $res libvirt storagepool resource
 * @return bool TRUE if success, FALSE on error
 * @since 0.4.6
 */
function libvirt_storagepool_delete($res): bool {}

/**
 * Function is used to destroy the storage pool
 * @param resource $res libvirt storagepool resource
 * @return bool TRUE if success, FALSE on error
 * @since 0.4.1(-1)
 */
function libvirt_storagepool_destroy($res): bool {}

/**
 * Function is used to get autostart of the storage pool
 * @param resource $res libvirt storagepool resource
 * @return bool TRUE for autostart enabled, FALSE for autostart disabled, FALSE with last_error set for error
 * @since 0.4.1(-1)
 */
function libvirt_storagepool_get_autostart($res): bool {}

/**
 * Function is used to get information about the storage pool
 * @param resource $res libvirt storagepool resource
 * @return array storage pool information array of state, capacity, allocation and available space
 * @since 0.4.1(-1)
 */
function libvirt_storagepool_get_info($res): array {}

/**
 * Function is used to get storage pool name from the storage pool resource
 * @param resource $res libvirt storagepool resource
 * @return string storagepool name string
 * @since 0.4.1(-1)
 */
function libvirt_storagepool_get_name($res): string {}

/**
 * Function is used to get storage pool by UUID string
 * @param resource $res libvirt storagepool resource
 * @return string storagepool UUID string
 * @since 0.4.1(-1)
 */
function libvirt_storagepool_get_uuid_string($res): string {}

/**
 * Function is used to get storage volume count in the storage pool
 * @param resource $res libvirt storagepool resource
 * @return int number of volumes in the pool
 * @since 0.4.1(-1)
 */
function libvirt_storagepool_get_volume_count($res): int {}

/**
 * Function is used to get the XML description for the storage pool identified by res
 * @param resource $res libvirt storagepool resource
 * @param string|null $xpath [optional] xPath expression string to get just this entry, can be NULL
 * @return string storagepool XML description string or result of xPath expression
 * @since 0.4.2
 */
function libvirt_storagepool_get_xml_desc($res, ?string $xpath): string {}

/**
 * Function is used to get information whether storage pool is active or not
 * @param resource $res libvirt storagepool resource
 * @return bool result of virStoragePoolIsActive
 * @since 0.4.1(-1)
 */
function libvirt_storagepool_is_active($res): bool {}

/**
 * Function is used to list volumes in the specified storage pool
 * @param resource $res libvirt storagepool resource
 * @return array list of storage volume names in the storage pool in an array using default keys (indexes)
 * @since 0.4.1(-1)
 */
function libvirt_storagepool_list_volumes($res): array {}

/**
 * Function is used to lookup for storage pool by it's name
 * @param resource $res volume resource of storage pool
 * @param string $name storage pool name
 * @return resource libvirt storagepool resource
 * @since 0.4.1(-1)
 */
function libvirt_storagepool_lookup_by_name($res, string $name) {}

/**
 * Function is used to lookup for storage pool identified by UUID string
 * @param resource $res libvirt connection resource
 * @param string $uuid UUID string to look for storagepool
 * @return resource libvirt storagepool resource
 * @since 0.4.1(-1)
 */
function libvirt_storagepool_lookup_by_uuid_string($res, string $uuid) {}

/**
 * Function is used to lookup for storage pool by a volume
 * @param resource $res volume resource of storage pool
 * @return resource libvirt storagepool resource
 * @since 0.4.1(-1)
 */
function libvirt_storagepool_lookup_by_volume($res) {}

/**
 * Function is used to refresh the storage pool information
 * @param resource $res libvirt storagepool resource
 * @param int $flags [optional] refresh flags
 * @return bool TRUE if success, FALSE on error
 * @since 0.4.1(-1)
 */
function libvirt_storagepool_refresh($res, int $flags = 0): bool {}

/**
 * Function is used to set autostart of the storage pool
 * @param resource $res libvirt storagepool resource
 * @param bool $flags flags to set autostart
 * @return bool result on setting storagepool autostart value
 */
function libvirt_storagepool_set_autostart($res, bool $flags): bool {}

/**
 * Function is used to undefine the storage pool identified by it's resource
 * @param resource $res libvirt storagepool resource
 * @return bool TRUE if success, FALSE on error
 * @since 0.4.1(-1)
 */
function libvirt_storagepool_undefine($res): bool {}

/**
 * Function is used to create the new storage pool and return the handle to new storage pool
 * @param resource $res libvirt storagepool resource
 * @param string $xml XML string to create the storage volume in the storage pool
 * @param int $flags [optional] virStorageVolCreateXML flags
 * @return resource libvirt storagevolume resource
 * @since 0.4.1(-1)
 */
function libvirt_storagevolume_create_xml($res, string $xml, int $flags = 0) {}

/**
 * Function is used to clone the new storage volume into pool from the original volume
 * @param resource $pool libvirt storagepool resource
 * @param string $xml XML string to create the storage volume in the storage pool
 * @param resource $original_volume libvirt storagevolume resource
 * @return resource libvirt storagevolume resource
 * @since 0.4.1(-2)
 */
function libvirt_storagevolume_create_xml_from($pool, string $xml, $original_volume) {}

/**
 * Function is used to delete to volume identified by it's resource
 * @param resource $res libvirt storagevolume resource
 * @param int $flags [optional] flags for the storage volume deletion for virStorageVolDelete()
 * @return bool TRUE for success, FALSE on error
 * @since 0.4.2
 */
function libvirt_storagevolume_delete($res, int $flags = 0): bool {}

/**
 * Function is used to download volume identified by it's resource
 * @param resource $res libvirt storagevolume resource
 * @param resource $stream stream to use as output
 * @param int $offset [optional] position to start reading from
 * @param int $length [optional] limit on amount of data to download
 * @param int $flags [optional] flags for the storage volume download for virStorageVolDownload()
 * @return int
 * @since 0.5.0
 */
function libvirt_storagevolume_download($res, $stream, int $offset = 0, int $length = 0, int $flags = 0): int {}

/**
 * Function is used to get the storage volume information
 * @param resource $res libvirt storagevolume resource
 * @return array storage volume information array of type, allocation and capacity
 * @since 0.4.1(-1)
 */
function libvirt_storagevolume_get_info($res): array {}

/**
 * Function is used to get the storage volume name
 * @param resource $res libvirt storagevolume resource
 * @return string storagevolume name
 * @since 0.4.1(-2)
 */
function libvirt_storagevolume_get_name($res): string {}

/**
 * Function is used to get the storage volume path
 * @param resource $res libvirt storagevolume resource
 * @return string storagevolume path
 * @since 0.4.1(-2)
 */
function libvirt_storagevolume_get_path($res): string {}

/**
 * Function is used to get the storage volume XML description
 * @param resource $res libvirt storagevolume resource
 * @param string|null $xpath [optional] xPath expression string to get just this entry, can be NULL
 * @param int $flags [optional] flags
 * @return string storagevolume XML description or result of xPath expression
 * @since 0.4.2
 */
function libvirt_storagevolume_get_xml_desc($res, ?string $xpath, int $flags = 0): string {}

/**
 * Function is used to lookup for storage volume by it's name
 * @param resource $res libvirt storagepool resource
 * @param string $name name of the storage volume to look for
 * @return resource libvirt storagevolume resource
 * @since 0.4.1(-1)
 */
function libvirt_storagevolume_lookup_by_name($res, string $name) {}

/**
 * Function is used to lookup for storage volume by it's path
 * @param resource $res libvirt connection resource
 * @param string $path path of the storage volume to look for
 * @return resource libvirt storagevolume resource
 * @since 0.4.1(-2)
 */
function libvirt_storagevolume_lookup_by_path($res, string $path) {}

/**
 * Function is used to resize volume identified by it's resource
 * @param resource $res libvirt storagevolume resource
 * @param int $capacity capacity for the storage volume
 * @param int $flags [optional] flags for the storage volume resize for virStorageVolResize()
 * @return int
 * @since 0.5.0
 */
function libvirt_storagevolume_resize($res, int $capacity, int $flags = 0): int {}

/**
 * Function is used to upload volume identified by it's resource
 * @param resource $res libvirt storagevolume resource
 * @param resource $stream stream to use as input
 * @param int $offset [optional] position to start writing to
 * @param int $length [optional] limit on amount of data to upload
 * @param int $flags [optional] flags for the storage volume upload for virStorageVolUpload()
 * @return int
 * @since 0.5.0
 */
function libvirt_storagevolume_upload($res, $stream, int $offset = 0, int $length = 0, int $flags = 0): int {}

/* Stream functions */

/**
 * Function is used to abort transfer
 * @param resource $res libvirt stream resource from libvirt_stream_create()
 * @return int
 * @since 0.5.0
 */
function libvirt_stream_abort($res): int {}

/**
 * Function is used to close stream
 * @param resource $res libvirt stream resource from libvirt_stream_create()
 * @return int
 * @since 0.5.0
 */
function libvirt_stream_close($res): int {}

/**
 * Function is used to create new stream from libvirt conn
 * @param resource $res libvirt connection resource from libvirt_connect()
 * @return resource resource libvirt stream resource
 * @since 0.5.0
 */
function libvirt_stream_create($res) {}

/**
 * Function is used to finish transfer
 * @param resource $res libvirt stream resource from libvirt_stream_create()
 * @return int
 * @since 0.5.0
 */
function libvirt_stream_finish($res): int {}

/**
 * Function is used to close stream from libvirt conn
 * @param resource $res libvirt stream resource from libvirt_stream_create()
 * @param string $data buffer
 * @param int $len [optional] amount of data to receive
 * @return int
 * @since 0.5.0
 */
function libvirt_stream_recv($res, string $data, int $len = 0): int {}

/**
 * Function is used to close stream from libvirt conn
 * @param resource $res libvirt stream resource from libvirt_stream_create()
 * @param string $data buffer
 * @param int $length [optional] amount of data to send
 * @return int
 * @since 0.5.0
 */
function libvirt_stream_send($res, string $data, int $length = 0): int {}
