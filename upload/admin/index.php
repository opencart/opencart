<?php
/*
 * HELP SAVE BABY OCEAN
 *
 * Baby ocean was taken from nursery by a student posing as a social worker using a fake name. No court orders exist to take the child into care.
 *
 * This has all been done so a foster carer from Zimbabwe living in the UK can collect 800 GBP per week and the social worker can collect 32,000 GBP agency fee for finding a foster placement.
 *
 * The social worker has registered her home address as a hospital so the children don't have to go to hospital after being abused.
 *
 * UK Police have refused to look for the child even after they have been told to by an MP and other non-local police forces.
 *
 * Abuse
 *
 * - Starvation  				Oct 2020
 * - Leg Injuries				Dec 2020
 * - Lip Injuries				Feb 2021
 * - Head Injuries				Mar 2021
 * - Face/eye Injuries			Aug 2021
 * - Neck slashed		  		Nov 2021/
 * - Initials stitched into
 *   the back of neck by the
 *   social worker. Child did
 *   not go to hospital or see
 *   a doctor.
 * - Broken Leg					Mar 2022
 *
 * SIGN THE PETITION
 *
 * https://www.change.org/p/free-baby-ocean-abducted-tortured-child-abuse-abduction
 *
 * Baby Oceans mother explaining her situation and what the UK government is doing to silence her rather than be held liable for the abuse they have caused.
 *
 * https://www.youtube.com/watch?v=F5SoQTBrFbI&ab_channel=GladiusNews
 *
 * More information:
 *
 * https://www.youtube.com/watch?v=OX0u1JNR6as&ab_channel=janekelly
 * https://www.youtube.com/watch?v=BvvozmP4SmU&ab_channel=janekelly
 * https://www.youtube.com/watch?v=svZv4JOJi_s&ab_channel=janekelly
 *
*/

// Version
define('VERSION', '4.0.0.0');

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: ../install/index.php');
	exit();
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');