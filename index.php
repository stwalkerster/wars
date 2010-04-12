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

// define a constant which we can check for in other code files. 
//this will prevent other pages being used as a code entry point.
define("WARS", 1);

// load the configuration file, which in turn will set up the environment with everything we need.
require_once("config.inc.php");

// Start the session
session_start();

// is this a forwarding request?
if(WebRequest::getPageName() == "Forward")
{
	// perform the forward
	require('src/page/PageForward.php');
	$page = new PageForward();
}
else
{
	// create the request page
	$page = new RequestPage();
}

// execute the page
$page->execute();