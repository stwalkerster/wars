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

// Database connection info
$db_host = "localhost";
$db_user = "stwalkerster";
$db_pass = file_get_contents(".dbpw"); // grab the password from this file.
$db_name = 'louriepieterse_acc_2';

// the web-accessible folder where index.php is stored
$baseFilePath = "/~stwalkerster/newacc/";

// session configuration options
ini_set('session.cookie_path', $baseFilePath); // limit this session's scope to the base path of the site.
ini_set('session.name', 'WARSystem'); // set a (theoretically) unique session name

// disallow writes to database if this is set to 1, for debugging.
$readOnlyDb = 0;

// autoload classes that have not been defined in the current script.
// I therefore don't have to write a long list of require()s.
// This will be called each time an unknown class is referenced, with the 
// name of the unknown class in the parameter.
//
// http://php.net/manual/en/language.oop5.autoload.php
function __autoload($class)
{
	require_once('src/'.$class.".php");
}
