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

// create the page
$page = PageBase::create();

// execute the page
$page->execute();