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

class PageMain extends PageBase
{
	function __construct()
	{
		$this->subtitle = "Account Requests";
	}
	
	function runPage()
	{
		$this->smarty->assign('subpage', 'page/MainPage.tpl');
	}
}