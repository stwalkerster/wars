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
		$this->protectPage = false;
	}

	function runPage()
	{
		$id = WebRequest::getInt("id");
		if($id == 0)
		{
			$this->defaultPage();
		}
		else
		{
			$this->userDetailPage($id);
		}
	}
	
	private function userDetailPage($id)
	{
		$this->smarty->assign('subpage', 'page/UserManagementDetail.tpl');
	}
	
	private function defaultPage()
	{
		$users = array(
			"new" => User::query(array(
					USER_COLUMN_LEVEL => USER_LEVEL_NEW,
				)),
			"user" => User::query(array(
					USER_COLUMN_LEVEL => USER_LEVEL_USER,
				)),
			"admin" => User::query(array(
					USER_COLUMN_LEVEL => USER_LEVEL_ADMIN,
				)),
			"suspended" => User::query(array(
					USER_COLUMN_LEVEL => USER_LEVEL_SUSPENDED,
				)),
			"declined" => User::query(array(
					USER_COLUMN_LEVEL => USER_LEVEL_DECLINED,
				)),
			"checkuser" => User::query(array(
					USER_COLUMN_CHECKUSER => 1,
				)),
		);
		
		$this->smarty->assign('user_new', $users['new']);
		$this->smarty->assign('user_user', $users['user']);
		$this->smarty->assign('user_admin', $users['admin']);
		$this->smarty->assign('user_checkuser', $users['checkuser']);
		$this->smarty->assign('user_suspended', $users['suspended']);
		$this->smarty->assign('user_declined', $users['declined']);
		$this->smarty->assign('subpage', 'page/UserManagement.tpl');
	}
}