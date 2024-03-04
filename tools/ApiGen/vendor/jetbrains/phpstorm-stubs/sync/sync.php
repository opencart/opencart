<?php
/**
 * sync PECL extension stubs v.0.1
 * @link https://php.net/manual/en/book.sync.php
 */
/**
 * SyncMutex
 *
 * A cross-platform, native implementation of named and unnamed countable mutex objects.
 * A mutex is a mutual exclusion object that restricts access to a shared resource (e.g. a file) to a single instance. Countable mutexes acquire the mutex a single time and internally track the number of times the mutex is locked. The mutex is unlocked as soon as it goes out of scope or is unlocked the same number of times that it was locked.
 *
 * @link https://php.net/manual/en/class.syncmutex.php
 */
class SyncMutex
{
    /**
     * Constructs a new SyncMutex object
     *
     * Constructs a named or unnamed countable mutex.
     *
     * @param string $name [optional] The name of the mutex if this is a named mutex object. If the name already exists, it must be able to be opened by the current user that the process is running as or an exception will be thrown with a meaningless error message.
     * @throws Exception if the mutex cannot be created or opened
     * @link https://php.net/manual/en/syncmutex.construct.php
     */
    public function __construct($name) {}

    /**
     * Waits for an exclusive lock
     *
     * Obtains an exclusive lock on a SyncMutex object. If the lock is already acquired, then this increments an internal counter.
     *
     * @param int $wait [optional] The number of milliseconds to wait for the exclusive lock. A value of -1 is infinite.
     * @return bool TRUE if the lock was obtained, FALSE otherwise.
     * @see SyncMutex::unlock()
     * @link https://php.net/manual/en/syncmutex.lock.php
     */
    public function lock($wait = -1) {}

    /**
     * Unlocks the mutex
     *
     * Decreases the internal counter of a SyncMutex object. When the internal counter reaches zero, the actual lock on the object is released.
     *
     * @param bool $all [optional] Specifies whether or not to set the internal counter to zero and therefore release the lock.
     * @return bool TRUE if the unlock operation was successful, FALSE otherwise.
     * @see SyncMutex::lock()
     * @link https://php.net/manual/en/syncmutex.unlock.php
     */
    public function unlock($all = false) {}
    }

/**
 * SyncSemaphore
 *
 * A cross-platform, native implementation of named and unnamed semaphore objects.
 * A semaphore restricts access to a limited resource to a limited number of instances. Semaphores differ from mutexes in that they can allow more than one instance to access a resource at one time while a mutex only allows one instance at a time.
 *
 * @link https://php.net/manual/en/class.syncsemaphore.php
 */
class SyncSemaphore
{
    /**
     * Constructs a new SyncSemaphore object
     *
     * Constructs a named or unnamed semaphore.
     *
     * @param string $name       [optional] The name of the semaphore if this is a named semaphore object. Note: If the name already exists, it must be able to be opened by the current user that the process is running as or an exception will be thrown with a meaningless error message.
     * @param int    $initialval [optional] The initial value of the semaphore. This is the number of locks that may be obtained.
     * @param bool   $autounlock [optional] Specifies whether or not to automatically unlock the semaphore at the conclusion of the PHP script. Warning: If an object is: A named semaphore with an autounlock of FALSE, the object is locked, and the PHP script concludes before the object is unlocked, then the underlying semaphore will end up in an inconsistent state.
     * @throws Exception if the semaphore cannot be created or opened
     * @link https://php.net/manual/en/syncsemaphore.construct.php
     */
    public function __construct($name, $initialval = 1, $autounlock = true) {}

    /**
     * Decreases the count of the semaphore or waits
     *
     * Decreases the count of a SyncSemaphore object or waits until the semaphore becomes non-zero.
     *
     * @param int $wait The number of milliseconds to wait for the semaphore. A value of -1 is infinite.
     * @return bool TRUE if the lock operation was successful, FALSE otherwise.
     * @see SyncSemaphore::unlock()
     * @link https://php.net/manual/en/syncsemaphore.lock.php
     */
    public function lock($wait = -1) {}

    /**
     * Increases the count of the semaphore
     *
     * Increases the count of a SyncSemaphore object.
     *
     * @param int &$prevcount Returns the previous count of the semaphore.
     * @return bool TRUE if the unlock operation was successful, FALSE otherwise.
     * @see SyncSemaphore::lock()
     * @link https://php.net/manual/en/syncsemaphore.unlock.php
     */
    public function unlock(&$prevcount = 0) {}
    }

/**
 * SyncEvent
 *
 * A cross-platform, native implementation of named and unnamed event objects. Both automatic and manual event objects are supported.
 * An event object waits, without polling, for the object to be fired/set. One instance waits on the event object while another instance fires/sets the event. Event objects are useful wherever a long-running process would otherwise poll a resource (e.g. checking to see if uploaded data needs to be processed).
 *
 * @link https://php.net/manual/en/class.syncevent.php
 */
class SyncEvent
{
    /**
     * SyncEvent constructor.
     *
     * @param string $name    The name of the event if this is a named event object. Note: If the name already exists, it must be able to be opened by the current user that the process is running as or an exception will be thrown with a meaningless error message.
     * @param bool   $manual  [optional] Specifies whether or not the event object must be reset manually. Note: Manual reset event objects allow all waiting processes through until the object is reset.
     * @param bool   $prefire [optional] Specifies whether or not to prefire (signal) the event object. Note: Only has impact if the calling process/thread is the first to create the object.
     * @throws Exception if the event object cannot be created or opened
     * @since 1.0.0
     * @since 1.1.0 Added $prefire
     * @link https://php.net/manual/en/syncevent.construct.php
     */
    public function __construct(string $name, bool $manual = false, bool $prefire = false) {}

    /**
     * Fires/sets the event
     *
     * Fires/sets a SyncEvent object. Lets multiple threads through that are waiting if the event object was created with a manual value of TRUE.
     *
     * @return bool TRUE if the event was fired, FALSE otherwise.
     * @see SyncEvent::wait()
     * @link https://php.net/manual/en/syncevent.fire.php
     */
    public function fire() {}

    /**
     * Resets a manual event
     *
     * Resets a SyncEvent object that has been fired/set. Only valid for manual event objects.
     *
     * @return bool TRUE if the object was successfully reset, FALSE otherwise.
     * @link https://php.net/manual/en/syncevent.reset.php
     */
    public function reset() {}

    /**
     * Waits for the event to be fired/set
     *
     * Waits for the SyncEvent object to be fired.
     *
     * @param int $wait The number of milliseconds to wait for the event to be fired. A value of -1 is infinite.
     * @return bool TRUE if the event was fired, FALSE otherwise.
     * @see SyncEvent::fire()
     * @link https://php.net/manual/en/syncevent.wait.php
     */
    public function wait($wait = -1) {}
    }

/**
 * SyncReaderWriter
 *
 * A cross-platform, native implementation of named and unnamed reader-writer objects.
 * A reader-writer object allows many readers or one writer to access a resource. This is an efficient solution for managing resources where access will primarily be read-only but exclusive write access is occasionally necessary.
 *
 * @link https://php.net/manual/en/class.syncreaderwriter.php
 */
class SyncReaderWriter
{
    /**
     * Constructs a new SyncReaderWriter object
     *
     * Constructs a named or unnamed reader-writer object.
     *
     * @param string $name       [optional] The name of the reader-writer if this is a named reader-writer object. Note: If the name already exists, it must be able to be opened by the current user that the process is running as or an exception will be thrown with a meaningless error message.
     * @param bool   $autounlock [optional] Specifies whether or not to automatically unlock the reader-writer at the conclusion of the PHP script. Warning: If an object is: A named reader-writer with an autounlock of FALSE, the object is locked for either reading or writing, and the PHP script concludes before the object is unlocked, then the underlying objects will end up in an inconsistent state.
     * @throws Exception if the reader-writer cannot be created or opened.
     * @link https://php.net/manual/en/syncreaderwriter.construct.php
     */
    public function __construct($name, $autounlock = true) {}

    /**
     * Waits for a read lock
     *
     * Obtains a read lock on a SyncReaderWriter object.
     *
     * @param int $wait [optional] The number of milliseconds to wait for a lock. A value of -1 is infinite.
     * @return bool TRUE if the lock was obtained, FALSE otherwise.
     * @see SyncReaderWriter::readunlock()
     * @link https://php.net/manual/en/syncreaderwriter.readlock.php
     */
    public function readlock($wait = -1) {}

    /**
     * Releases a read lock
     *
     * Releases a read lock on a SyncReaderWriter object.
     *
     * @return bool TRUE if the unlock operation was successful, FALSE otherwise.
     * @see SyncReaderWriter::readlock()
     * @link https://php.net/manual/en/syncreaderwriter.readunlock.php
     */
    public function readunlock() {}

    /**
     * Waits for an exclusive write lock
     *
     * Obtains an exclusive write lock on a SyncReaderWriter object.
     *
     * @param int $wait [optional] The number of milliseconds to wait for a lock. A value of -1 is infinite.
     * @return bool TRUE if the lock was obtained, FALSE otherwise.
     * @see SyncReaderWriter::writeunlock()
     * @link https://php.net/manual/en/syncreaderwriter.writelock.php
     */
    public function writelock($wait = -1) {}

    /**
     * Releases a write lock
     *
     * Releases a write lock on a SyncReaderWriter object.
     *
     * @return bool TRUE if the unlock operation was successful, FALSE otherwise.
     * @see SyncReaderWriter::writelock()
     * @link https://php.net/manual/en/syncreaderwriter.writeunlock.php
     */
    public function writeunlock() {}
    }

/**
 * SyncSharedMemory
 *
 * A cross-platform, native, consistent implementation of named shared memory objects.
 * Shared memory lets two separate processes communicate without the need for complex pipes or sockets. There are several integer-based shared memory implementations for PHP. Named shared memory is an alternative.
 * Synchronization objects (e.g. SyncMutex) are still required to protect most uses of shared memory.
 *
 * @since 1.1.0
 * @link https://php.net/manual/en/class.syncsharedmemory.php
 */
class SyncSharedMemory
{
    /**
     * Constructs a new SyncSharedMemory object
     *
     * Constructs a named shared memory object.
     *
     * @param string $name The name of the shared memory object. Note: If the name already exists, it must be able to be opened by the current user that the process is running as or an exception will be thrown with a meaningless error message.
     * @param int    $size The size, in bytes, of shared memory to reserve. Note: The amount of memory cannot be resized later. Request sufficient storage up front.
     * @throws Exception if the shared memory object cannot be created or opened.
     * @link https://php.net/manual/en/syncsharedmemory.construct.php
     */
    public function __construct($name, $size) {}

    /**
     * Check to see if the object is the first instance system-wide of named shared memory
     *
     * Retrieves the system-wide first instance status of a SyncSharedMemory object.
     *
     * @return bool TRUE if the object is the first instance system-wide, FALSE otherwise.
     * @link https://php.net/manual/en/syncsharedmemory.first.php
     */
    public function first() {}

    /**
     * Copy data from named shared memory
     *
     * Copies data from named shared memory.
     *
     * @param int $start  [optional] The start/offset, in bytes, to begin reading. Note: If the value is negative, the starting position will begin at the specified number of bytes from the end of the shared memory segment.
     * @param int $length [optional] The number of bytes to read. Note: If unspecified, reading will stop at the end of the shared memory segment. If the value is negative, reading will stop the specified number of bytes from the end of the shared memory segment.
     * @return string containing the data read from shared memory.
     * @see SyncSharedMemory::write()
     * @link https://php.net/manual/en/syncsharedmemory.read.php
     */
    public function read($start = 0, $length) {}

    /**
     * Returns the size of the named shared memory
     *
     * Retrieves the shared memory size of a SyncSharedMemory object.
     *
     * @return int containing the size of the shared memory. This will be the same size that was passed to the constructor.
     * @link https://php.net/manual/en/syncsharedmemory.size.php
     */
    public function size() {}

    /**
     * Copy data to named shared memory
     *
     * Copies data to named shared memory.
     *
     * @param string $string The data to write to shared memoy. Note: If the size of the data exceeds the size of the shared memory, the number of bytes written returned will be less than the length of the input.
     * @param int    $start  The start/offset, in bytes, to begin writing. Note: If the value is negative, the starting position will begin at the specified number of bytes from the end of the shared memory segment.
     * @return int containing the number of bytes written to shared memory.
     * @link https://php.net/manual/en/syncsharedmemory.write.php
     */
    public function write($string, $start = 0) {}
    }
