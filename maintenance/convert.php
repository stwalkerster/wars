<?php
/*
 * Stage zero: Set up the environment
 */

define("WARS", 1);
require_once 'config.inc.php';

$olddatabase = "p_acc_sand";

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
	$result = $accDatabase->query($value);
	if(get_class($result) != "PDOStatement")
	{
		out("Error! Could not retrieve result!");
		echo $value;
		die();
	}
	$error = $result->errorInfo();
	if($error[0] != "0000")
	{
		print_r($error);
		die;
	}
	out("    Done!");
}

/*
 * Stage two: Add existing simple data:
 *  * template
 *  * emails
 */
 
out("Stage two: Add existing simple data");

out("  - acc_template");
$result = $accDatabase->query("INSERT INTO acc_template SELECT * FROM $olddatabase.acc_template;");
if(get_class($result) != "PDOStatement")
{
	out("Error! Could not retrieve result!");
	echo $value;
	die();
}
$error = $result->errorInfo();
if($error[0] != "0000")
{
	print_r($error);
	die;
}
out("    Done!");

out("  - acc_emails");
$result = $accDatabase->query("INSERT INTO acc_emails SELECT mail_id, mail_text, mail_count, mail_desc, mail_type, substr(mail_desc,1,45) FROM $olddatabase.acc_emails;");
if(get_class($result) != "PDOStatement")
{
	out("Error! Could not retrieve result!");
	echo $value;
	die();
}
$error = $result->errorInfo();
if($error[0] != "0000")
{
	print_r($error);
	die;
}
out("    Done!");



/*
 * Stage three: Add dependant data:
 *  * user
 */
 
out("Stage three: Add dependant data");

out("  - acc_user");
$result = $accDatabase->query("INSERT INTO acc_user SELECT user_id, user_name, user_email, user_pass, user_level, user_onwikiname, user_welcome_sig, user_lastactive, user_lastip, user_forcelogout, user_secure, user_checkuser, user_identified, user_welcome_templateid, user_abortpref, user_confirmationdiff FROM $olddatabase.acc_user; ");
if(get_class($result) != "PDOStatement")
{
	out("Error! Could not retrieve result!");
	echo $value;
	die();
}
$error = $result->errorInfo();
if($error[0] != "0000")
{
	print_r($error);
	die;
}
out("    Done!");

/*
 * Stage four: Add dependant data:
 *  * pend
 */

out("Stage four: Add dependant data");

out("  - acc_pend");
$result = $accDatabase->query("INSERT INTO acc_pend SELECT * FROM $olddatabase.acc_pend;");
if(get_class($result) != "PDOStatement")
{
	out("Error! Could not retrieve result!");
	echo $value;
	die();
}
$error = $result->errorInfo();
if($error[0] != "0000")
{
	print_r($error);
	die;
}
out("    Done!");

/*
 * Stage five: Add partially calculated dependant data:
 *  * welcome
 *  * ban
 *  * log
 *  * cmt
 */
out("Stage five: Add partially calculated dependant data");

/*
 * Stage six: Add fully calculated data (run maintenance scripts)
 *  * trusted ips
 *  * titleblacklist
 */
out("Stage six: Add fully calculated data (run maintenance scripts)");
 
/*
 * Stage seven: Add indices, foreign keys, and other assorted produce
 */