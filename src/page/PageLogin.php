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
		
		if(WebRequest::wasPosted())
		{
			if(User::authenticate(WebRequest::postString('username'),WebRequest::postString('password')))
			{
				// redirect to main page
				WebRequest::redirect('Main');
			}
			else
			{
				$this->smarty->assign('iserror','1');
				$this->smarty->assign('error', 'error/AccessDenied.tpl');
				$this->smarty->assign('subpage', 'page/LoginForm.tpl');
			}
		}
		else
		{
			$this->smarty->assign('subpage', 'page/LoginForm.tpl');
		}
	}
	
	function runRegister()
	{
		$this->subtitle="Register for a Tool Account";
		
		if(WebRequest::wasPosted())
		{
			if(WebRequest::postBool('guidelines') == 0)
			{
				$this->error('NoAcceptGuidelines');
				return;
			}
			
			if(WebRequest::postString('pass') != WebRequest::postString('pass2'))
			{
				$this->error('PasswordMismatch');
				return;
			}
			
			if(WebRequest::postString('pass') == ""
				|| WebRequest::postString('name') == ""
				|| WebRequest::postString('wname') == ""
				|| WebRequest::postString('email') == ""
			)
			{
				$this->error('RequiredInfoMissing');
				return;
			}
			
			
			$u = new User(
				WebRequest::postString('name'),
				WebRequest::postString('pass'),
				WebRequest::postString('email'),
				WebRequest::postString('wname')
				);
				
			$u->setSecureStatus(WebRequest::postBool('secureenable'));
			$u->setWelcomeStatus(WebRequest::postBool('welcomeenable'));
			$u->setTemplate(WebRequest::postInt('template'));
			$u->setSignature(WebRequest::postString('sig'));
			
			
			$u->save();
			
			$this->smarty->assign('subpage', 'page/Registered.tpl');
			
		}
		else
		{
			$this->smarty->assign('welcometemplates', WelcomeTemplate::getDisplayList());
			$this->smarty->assign('subpage', 'page/RegisterForm.tpl');
		}
	}
	
	function runForgotPw()
	{
		$this->subtitle="Forgotten your password?";
		$this->smarty->assign('subpage', 'page/ForgotPwForm.tpl');
	}
}
