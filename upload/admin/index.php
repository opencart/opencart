<?php
/*
 * HELP SAVE BABY OCEAN
 *
 * Abuse
 *
 * - Starvation  				Oct 2020
 * - Leg Injuries				Dec 2020
 * - Lip Injuries				Feb 2021
 * - Head Injuries				Mar 2021
 * - Face/eye Injuries			Aug 2021
 * - Neck slashed		  		Nov 2021
 *   Initials stitched into
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
 * Details of the abusers
 *
 * Alex Taylor Kubeyinje
 * He runs the trafficking ring in Cryodon. This guy quites his job at fist sign of complaints.
 * https://www.linkedin.com/in/alex-taylor-kubeyinje-a082a3ab/
 *
 * Trish kudzai Tozowonoh / Alice DZVENGWE
 * Zimbabwean nurse running a medical practice from her home in the UK. The name she uses for social work practice is Trish Tozowonoh but the name she uses to run her medical practice from her home in Northampton is Alice Dzwenge.
 * She stitched baby Oceans neck up at her home so the incident would not be reported to police. She also stitched the mothers initials into the child's neck.
 * This is all being done so a foster carer from Zimbabwe who uses multiple names can collect 800 GBP per week and the social worker can collect 32,000 GBP in agency fees.
 * https://www.socialworkengland.org.uk/umbraco/surface/searchregister/socialworker/SW87996
 * https://find-and-update.company-information.service.gov.uk/company/08840300
 *
 * Tapiwa Julius (Trish's brother)
 * This guy has tried to intimidate baby's oceans mother by waiting outside her house and following her when she goes out.
 * https://www.facebook.com/greytapiwa.dzvengwe
 * https://www.linkedin.com/in/tapiwa-julius-76389771/ He used a fake picture so he can not be found.
 *
 * Monika Marta Wesolowska
 * Is a police women who pretends to be a social worker but is really there to help steal peoples kids.
 * https://www.socialworkengland.org.uk/umbraco/surface/searchregister/socialworker/SW19322
 *
*/

// Version
define('VERSION', '4.0.1.1');

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

// Framework
require_once(DIR_SYSTEM . 'framework.php');