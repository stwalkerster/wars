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
$db_host_a = "helpmebot.org.uk";
$db_user_a = "stwalkerster";
$db_pass_a = file_get_contents(".dbpw"); // grab the password from this file.
$db_name_a = 'louriepieterse_acc_2';

// Wikipedia database
$db_host_w = "helpmebot.org.uk";
$db_user_w = "stwalkerster";
$db_pass_w = file_get_contents(".dbpw"); // grab the password from this file.
$db_name_w = 'louriepieterse_acc_2';

/*
 * Paths and stuff
 */

// the web-accessible folder where index.php is stored
$baseFilePath = "/~stwalkerster/newacc/";

// the real file path where index.php is stored
$baseScriptPath = "/home/stwalkerster/public_helpmebot_html/newacc/";

// the real file path where all the logic code files are stored.
$baseIncludePath = $baseScriptPath . "src/";

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
$s_configFile = file_exists($s_configDir . 'local.config') ? 'local.config' : 'wars.config';

// set up the environment
$accDatabase = new Database($db_host_a, $db_user_a, $db_pass_a, $db_name_a);


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