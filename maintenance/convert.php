<?php
/*
 * Stage zero: Set up the environment
 */

define("WARS", 1);
require_once 'config.inc.php';

$olddatabase = "p_acc_test";

require_once $baseMaintenancePath . 'convert.inc';

out("This script will convert an ACC-style database into a WARS database.");
out("Please ^C in 10 seconds if you do not wish to translate $olddatabase");
out("into $db_name_a .");
for ($i = 10; $i > 0; $i--) {
	out($i);
	sleep(1);
}

/*
 * Stage one: Create database tables
 */

out("Stage one: Create database tables");

foreach ($tables_sql as $key => $value)
{
	out("  - $key");
	query($value);
	out("    Done!");
}

/*
 * Stage two: Add existing simple data:
 *  * template
 *  * emails
 */
 
out("Stage two: Add existing simple data");

out("  - acc_template");
query("INSERT INTO acc_template SELECT * FROM $olddatabase.acc_template;");
out("    Done!");

out("  - acc_emails");
query("INSERT INTO acc_emails SELECT mail_id, mail_text, mail_count, mail_desc, mail_type, substr(mail_desc,1,45) FROM $olddatabase.acc_emails;");
out("    Done!");



/*
 * Stage three: Add dependant data:
 *  * user
 */
 
out("Stage three: Add dependant data");

out("  - acc_user");
query("INSERT INTO acc_user SELECT user_id, user_name, user_email, user_pass, user_level, user_onwikiname, user_welcome_sig, user_lastactive, user_lastip, user_forcelogout, user_secure, user_checkuser, user_identified, user_welcome_templateid, user_abortpref, user_confirmationdiff FROM $olddatabase.acc_user; ");
out("    Done!");

/*
 * Stage four: Add dependant data:
 *  * pend
 */

out("Stage four: Add dependant data");

out("  - acc_pend");
query("INSERT INTO acc_pend SELECT * FROM $olddatabase.acc_pend;");
out("    Done!");

/*
 * Stage five: Add partially calculated dependant data:
 *  * welcome
 *  * ban
 *  * cmt
 */
out("Stage five: Add partially calculated dependant data");

out("  - acc_welcome");
query("INSERT INTO acc_welcome SELECT welcome_id, user_id, acc_welcome.welcome_user, acc_welcome.welcome_status FROM $olddatabase.acc_welcome left join acc_user on user_name = welcome_uid;");
out("    Done!");

out("  - acc_ban");
query("INSERT INTO acc_ban SELECT ban_id, ban_type, ban_target, user_id, ban_reason, ban_date, ban_duration, ban_active FROM $olddatabase.acc_ban left join acc_user on user_name = ban_user;");
out("    Done!");

out("  - acc_cmt");
query("INSERT INTO acc_cmt SELECT cmt_id, cmt_time, user_id, cmt_comment, cmt_visability, pend_id FROM $olddatabase.acc_cmt left join acc_user on user_name = cmt_user;");
out("    Done!");

/*
 * Stage six: Split table columns:
 *  * log
 */
out("Stage six: Split table columns");

$i=0;
foreach ($log_updates as $q)
{
	$i++;
	out("  - $i / 27");
	query($q);
	out("    Done!");
}



/*
 * Stage seven: Add fully calculated data (run maintenance scripts)
 *  * trusted ips
 *  * titleblacklist
 */
out("Stage six: Add fully calculated data (run maintenance scripts)");

require_once $baseMaintenancePath . 'RecreateTitleBlacklist.php';

require_once $baseMaintenancePath . 'RecreateTrustedIPs.php';

/*
 * Stage eight: Add indices, foreign keys, and other assorted produce
 */