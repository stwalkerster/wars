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

class PageLogin extends PageBase
{
	function __construct()
	{ 
		
	}
	
	function runPage()
	{
		$subpages = WebRequest::getSubpages();
		if(isset($subpages[1]))
		{
			if($subpages[1] == 'Register')
			{
				$this->runRegister();
			}
			else if($subpages[1] == 'Forgot')
			{
				$this->runForgotPw();
			}
		}
		else
		{
			$this->runLogin();
		}
	}
	
	function runLogin()
	{
		$this->subtitle="Login";
		$this->smarty->assign('subpage', 'page/LoginForm.tpl');
	}
	
	function runRegister()
	{
		$this->subtitle="Register";
		$this->smarty->assign('subpage', 'page/RegisterForm.tpl');
	}
	
	function runForgotPw()
	{
		$this->subtitle="Forgot password";
		$this->smarty->assign('subpage', 'page/ForgotPwForm.tpl');
	}
}