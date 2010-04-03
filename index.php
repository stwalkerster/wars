<?php

/********************************************************************************************
* Farm Claims Subsidy System																*
* 																							*
* Written by Simon Walker ( 090931545 ) for module F27DB: Introduction to Database Systems	*
* Lecturer: Brian Palmer and Adil Ibrahim													*
* 																							*
* Main code entry point.																	*
*********************************************************************************************/


// define a constant which we can check for in other code files. 
//this will prevent other pages being used as a code entry point.
define("FARMSYSTEM", 1);

// load the configuration file, which in turn will set up the environment with everything we need.
require_once("config.inc.php");

// Start the session
session_start();

// create the page
$page = PageBase::create();

// execute the page
$page->execute();