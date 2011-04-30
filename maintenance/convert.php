<?php
/*
 * Set up the environment
 */

define("WARS", 1);
require_once 'config.inc.php';

$olddatabase = "p_acc_test";

require_once $baseMaintenancePath . 'convert.inc';

out("This script will convert an ACC-style database into a WARS database.");
out("Please ^C in 10 seconds if you do not wish to translate $olddatabase");
out("into $db_name_a .");
for ($i = 10; $i > 0; $i--) {
	out($i."...",FALSE);
	sleep(1);
}


out("Starting conversion...");
pause();
/*
 * Stage one: Create database tables
 */

out("Stage one: Create database tables");

foreach ($tables_sql as $key => $value)
{
	out("  - $key");
	query("DROP TABLE IF EXISTS $key;");
	query($value);
	out("    Done!");
}
out(" Done!");
pause();
/*
 * Stage two: Make a hot spare of the old database
 */

out("Stage two: Make a hot spare of the old database");

$i=0;
$imax = count($backup);
foreach ($backup as $q)
{
	$i++;
	out("  - $i / $imax");
	query($q);
	out("    Done!");
}

out(" Done!");
pause();

/*
 * Stage three: Perform pre-transforms on existing database
 */

out("Stage three: Perform pre-transforms on data");

$i=0;
$imax = count($pre_transform);
foreach ($pre_transform as $q)
{
	$i++;
	out("  - $i / $imax");
	query($q);
	out("    Done!");
}

out(" Done!");
pause();
/*
 * Stage four: Add existing simple data:
 *  * template
 *  * emails
 */
 
out("Stage four: Add existing simple data");

out("  - acc_welcometemplate");
query("INSERT INTO acc_welcometemplate SELECT * FROM $olddatabase.acc_template;");
out("    Done!");

out("  - acc_message");
query("INSERT INTO acc_message SELECT mail_id, mail_text, mail_count, mail_desc, mail_type, substr(mail_desc,1,45) FROM $olddatabase.acc_emails;");
out("    Done!");


out(" Done!");
pause();
/*
 * Stage five: Add dependant data:
 *  * user
 */
 
out("Stage five: Add dependant data");

out("  - acc_user");
query("INSERT INTO acc_user SELECT user_id, user_name, user_email, user_pass, user_level, user_onwikiname, user_welcome_sig, user_lastactive, user_lastip, user_forcelogout, user_secure, user_checkuser, user_identified, user_welcome_templateid, user_abortpref, user_confirmationdiff FROM $olddatabase.acc_user; ");
out("    Done!");

out(" Done!");
pause();
/*
 * Stage six: Add dependant data:
 *  * pend
 */

out("Stage six: Add dependant data");

out("  - acc_request");
query("INSERT INTO acc_request SELECT * FROM $olddatabase.acc_pend;");
out("    Done!");

out(" Done!");
pause();
/*
 * Stage seven: Add partially calculated dependant data:
 *  * welcome
 *  * ban
 *  * cmt
 */
out("Stage seven: Add partially calculated dependant data");

out("  - acc_welcomequeue");
query("INSERT INTO acc_welcomequeue SELECT welcome_id, user_id, acc_welcome.welcome_user, acc_welcome.welcome_status FROM $olddatabase.acc_welcome left join acc_user on user_name = welcome_uid;");
out("    Done!");

out("  - acc_ban");
query("INSERT INTO acc_ban SELECT ban_id, ban_type, ban_target, user_id, ban_reason, ban_date, ban_duration, ban_active FROM $olddatabase.acc_ban left join acc_user on user_name = ban_user;");
out("    Done!");

out("  - acc_comment");
query("INSERT INTO acc_comment SELECT cmt_id, cmt_time, user_id, cmt_comment, cmt_visability, pend_id FROM $olddatabase.acc_cmt left join acc_user on user_name = cmt_user;");
out("    Done!");

out(" Done!");
pause();
/*
 * Stage eight: Split table columns:
 *  * log
 */
out("Stage eight: Split table columns");

out("  - acc_log");
query("INSERT INTO acc_log SELECT log_id, log_target_id, log_target_object, log_target_text, user_id, log_user_text, log_action, log_time, log_cmt FROM $olddatabase.acc_log left JOIN acc_user on user_name = log_user_text;");
out("    Done!");

out(" Done!");
pause();
/*
 * Stage nine: Add fully calculated data (run maintenance scripts)
 *  * trusted ips
 *  * titleblacklist
 */
out("Stage nine: Add fully calculated data (run maintenance scripts)");

require_once $baseMaintenancePath . 'RecreateTitleBlacklist.php';

require_once $baseMaintenancePath . 'RecreateTrustedIPs.php';

out(" Done!");
pause();
/* 
 * Stage ten: Run post-transform queries on the database
 */
out("Stage ten: Run post-transform queries on the database");

$i=0;
$imax = count($post_transform);
foreach ($post_transform as $q)
{
	$i++;
	out("  - $i / $imax");
	query($q);
	out("    Done!");
}

out(" Done!");
pause();
/*
 * Stage eleven: Add indices, foreign keys, and other assorted produce
 */
out("Stage eleven: Add indices, foreign keys, and other assorted produce");

/**
 * @todo add indices, foreign keys, etc
 */
out(" Done!");

out("Conversion Complete. $querycount queries were executed.");
pause();
