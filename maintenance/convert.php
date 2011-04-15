<?php
require_once 'config.inc.php';

$olddatabase = "p_acc_sand";

require_once $baseMaintenancePath . 'convert.inc';

/*
 * Stage one: Create database tables
 */

/*
 * Stage two: Add existing simple data:
 *  * user
 *  * pend
 *  * template
 *  * emails
 */
 
/*
 * Stage three: Add partially calculated dependant data:
 *  * welcome
 *  * log
 *  * ban
 *  * cmt
 */
 
/*
 * Stage four: Add fully calculated data (run maintenance scripts)
 *  * trusted ips
 *  * titleblacklist
 */
 
/*
 * Stage five: Add indices, foreign keys, and other assorted produce
 */