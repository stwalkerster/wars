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

// check that this code is being called from a valid entry point. 
if(!defined("WARS"))
	die("Invalid code entry point!");

class PageLogout extends PageBase
{
	
	function __construct()
	{
		$this->title = "I'm a teapot!";
		$this->subtitle = "HyperText Coffee Pot Control Protocol";
	}

	function runPage()
	{
		$_SESSION = array();
		setcookie(session_name(), '', time() - 42000);
		session_destroy();
		
		WebRequest::redirect('Login');
	}
}