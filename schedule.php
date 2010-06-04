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
echo "foo";
// load the configuration file, which in turn will set up the environment with everything we need.
require_once("config.inc.php");
echo "foo";
// is this a web request?
if(! WebRequest::isConsoleSession())
{
	echo "foo";
	require_once($baseIncludePath . 'page/PageError.php');echo "foo";
	$page = new ErrorPage("AccessDenied");echo "foo";
	$page->execute();echo "foo";
	die();
}
echo "foo";
echo "Cron'd jobs";echo "foo";