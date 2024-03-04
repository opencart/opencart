<?php

// Start of Zend Extensions

// Constants for jobs status
define('JOB_QUEUE_STATUS_SUCCESS', 1);             // Job was processed and succeeded
define('JOB_QUEUE_STATUS_WAITING', 2);             // Job is waiting for being processed (was not scheduled)
define('JOB_QUEUE_STATUS_SUSPENDED', 3);           // Job was suspended
define('JOB_QUEUE_STATUS_SCHEDULED', 4);           // Job is scheduled and waiting in queue
define('JOB_QUEUE_STATUS_WAITING_PREDECESSOR', 5); // Job is waiting for it's predecessor to be completed
define('JOB_QUEUE_STATUS_IN_PROCESS', 6);          // Job is in process in Queue
define('JOB_QUEUE_STATUS_EXECUTION_FAILED', 7);    // Job execution failed in the ZendEnabler
define('JOB_QUEUE_STATUS_LOGICALLY_FAILED', 8);    // Job was processed and failed logically either
                                                   // because of job_fail command or script parse or
                                                   // fatal error

// Constants for different priorities of jobs
define('JOB_QUEUE_PRIORITY_LOW', 0);
define('JOB_QUEUE_PRIORITY_NORMAL', 1);
define('JOB_QUEUE_PRIORITY_HIGH', 2);
define('JOB_QUEUE_PRIORITY_URGENT', 3);

// Constants for saving global variables bit mask
define('JOB_QUEUE_SAVE_POST', 1);
define('JOB_QUEUE_SAVE_GET', 2);
define('JOB_QUEUE_SAVE_COOKIE', 4);
define('JOB_QUEUE_SAVE_SESSION', 8);
define('JOB_QUEUE_SAVE_RAW_POST', 16);
define('JOB_QUEUE_SAVE_SERVER', 32);
define('JOB_QUEUE_SAVE_FILES', 64);
define('JOB_QUEUE_SAVE_ENV', 128);

// End of Zend Extensions
