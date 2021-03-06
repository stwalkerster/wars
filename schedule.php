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

// define a constant which we can check for in other code files. 
//this will prevent other pages being used as a code entry point.
define("WARS", 1);

// load the configuration file, which in turn will set up the environment with everything we need.
require_once("config.inc.php");

// is this a web request?
if(! WebRequest::isConsoleSession())
{
	require_once($baseIncludePath . 'page/PageError.php');
	$page = new PageError();
	$page->errorPage = "AccessDenied";
	$page->execute();
	die();
}

echo "Cron'd jobs";