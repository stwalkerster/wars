<?php
/**************************************************************************
*                     Wikipedia Account Request System                    *
***************************************************************************
*                                                                         *
* Conceptualised by Incubez (author: X!) and ACC (author: SQL and others) *
*                                                                         *
* Please refer to /LICENCE for more info.                                 *
*                                                                         *
**************************************************************************/

/**************************************************************************
* Please note: This file was originally written by Simon Walker for a     *
* university assignment, and may need adapting for purpose.               *
*                                                                         *
* DO NOT CHANGE THE EXISTING INTERFACE OF THIS CLASS unless you really    *
* know what you're doing.                                                 *
**************************************************************************/

// check that this code is being called from a valid entry point. 
if(!defined("WARS"))
	die("Invalid code entry point!");

// show errors, for debugging purposes only. Disable on a live system for security
ini_set('display_errors',1);

/*
 * Database connection information
 */

// Tool database
$db_host_a = "dbmaster.helpmebot.org.uk";
$db_user_a = "wars";
$db_pass_a = trim(file_get_contents(".dbpw")); // grab the password from this file.
$db_name_a = 'wars';

// Wikipedia database
$db_host_w = "dbmaster.helpmebot.org.uk";
$db_user_w = "stwalkerster";
$db_pass_w = trim(file_get_contents(".dbpw")); // grab the password from this file.
$db_name_w = 'helpmebot_wiki';

/*
 * Paths and stuff
 */

// the web-accessible folder where index.php is stored
$baseFilePath = "/~stwalkerster/newacc/";

// the real file path where index.php is stored
$baseScriptPath = "/home/stwalkerster/public_helpmebot_html/newacc/";

// the real file path where all the logic code files are stored.
$baseIncludePath = "/home/stwalkerster/public_helpmebot_html/newacc/src/";

// the path which stores all the stylesheets
$baseStylePath = "/~stwalkerster/newacc/style/";

/*
 * Session data
 */

// session configuration options
ini_set('session.cookie_path', $baseFilePath); // limit this session's scope to the base path of the site.
ini_set('session.name', 'WARSystem'); // set a (theoretically) unique session name

/*
 * Configuration details
 */

// disallow writes to database if this is set to 1, for debugging.
$confReadOnlyDb = 0;

// reserve to one person by default?
$confDefaultReserver = 0;

// tool contact email address
$toolEmailAddress = "accounts-enwiki-l@lists.wikimedia.org";

// Is the database read-only at the moment?
$readOnlyDb = 0;

// Is the database available at the moment?
$dontUseDb = 0;

// default reserver
$defaultReserver = 0;

/*
 * Smarty configuration
 */
$s_cacheDir = $baseScriptPath . '/smartycache/';
$s_compileDir = $baseScriptPath . '/smartycompile/';
$s_templateDir = $baseScriptPath . '/template/';
$s_configDir = $baseScriptPath . '/smartyconfig/';

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// DO NOT EDIT PAST THIS LINE, unless you know what you are doing.

@ include_once('localconfig.inc.php');

// see if the smarty config file needs to be updated
if( // if it doesn't exist, OR
	(! file_exists($s_configDir . 'wars.config'))
	|| // the config file was modified more recently, OR
	filectime($s_configDir . 'wars.config') < filectime($baseScriptPath . 'config.inc.php')
	||
	(	// the local config file exists AND
		file_exists($baseScriptPath . 'localconfig.inc.php')
		&& // it was modified more recently
		filectime($s_configDir . 'wars.config') < filectime($baseScriptPath . 'localconfig.inc.php')
	)
) // the config file needs re-creating.
{
	$handle = fopen($s_configDir . 'wars.config', "w");
	fwrite($handle, "baselink = \"{$baseFilePath}internal.php\"\n");
	fwrite($handle, "publicbaselink = \"{$baseFilePath}\"\n");
	fwrite($handle, "toolemail = \"{$toolEmailAddress}\"\n");
	fwrite($handle, "stylepath = \"{$baseStylePath}\"\n");
	
	fclose($handle);
}

// set up the environment
$accDatabase = new PDO("mysql:dbname=".$db_name_a.";host=".$db_host_a, $db_user_a, $db_pass_a);


// autoload classes that have not been defined in the current script.
// I therefore don't have to write a long list of require()s.
// This will be called each time an unknown class is referenced, with the 
// name of the unknown class in the parameter.
//
// http://php.net/manual/en/language.oop5.autoload.php
function __autoload($class)
{
	global $baseIncludePath;
	require_once($baseIncludePath.$class.".php");
}

// include the smarty class, as it's not caught by the above function
require_once($baseIncludePath . 'smarty/Smarty.class.php');
