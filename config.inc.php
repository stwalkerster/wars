<?php

/********************************************************************************************
* Farm Claims Subsidy System																*
* 																							*
* Written by Simon Walker ( 090931545 ) for module F27DB: Introduction to Database Systems	*
* Lecturer: Brian Palmer and Adil Ibrahim													*
* 																							*
* Main configuration file.																	*
*********************************************************************************************/

// check that this code is being called from a valid entry point. 
if(!defined("FARMSYSTEM"))
	die("Invalid code entry point!");

// show errors, for debugging purposes only. Disable on a live system for security
ini_set('display_errors',1);

// Database connection info
$db_host = "anubis";
$db_user = "stw3";
$db_pass = file_get_contents(".xcalibur"); // grab the password from this file.
$db_name = 'stw3';

// the web-accessible folder where index.php is stored
$baseFilePath = "/~stw3/farm/dbcoursework/";

// session configuration options
ini_set('session.cookie_path', $baseFilePath); // limit this session's scope to the base path of the site.
ini_set('session.name', 'FARM|090931545|FARM'); // set a (theoretically) unique session name

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
	require_once($class.".php");
}
