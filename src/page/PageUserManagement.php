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

class PageUserManagement extends PageBase
{
	
	function __construct()
	{
		$this->subtitle = "User Management";
	}

	function runPage()
	{
		$users = array();
		for ($i = 0; $i < 50; $i++) {
			$u = User::getById($i);
			if($u)
			$users[]=$u;
		}
		
		$this->smarty->assign('user_new', $users);
		$this->smarty->assign('user_user', $users);
		$this->smarty->assign('user_admin', $users);
		$this->smarty->assign('user_checkuser', $users);
		$this->smarty->assign('user_suspended', $users);
		$this->smarty->assign('user_declined', $users);
		$this->smarty->assign('subpage', 'page/UserManagement.tpl');
	}
}