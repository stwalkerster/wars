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

/**
 * RequestPage class
 * 
 * THIS IS OUTSIDE THE PAGE SEARCH DIRECTORY FOR A REASON.
 *  
 * @author stwalkerster
 *
 */
class RequestPage extends PageBase
{
	function __construct()
	{
		$this->subtitle = "Request an account on the English Wikipedia";
		
		$this->title = "Account Creation Assistance";
		
		$this->menu  = array('enwiki' => array('text' => 'Wikipedia', 'link' => 'Forward?link=http://en.wikipedia.org/'));
	}
	
	function runPage()
	{
		$this->smarty->assign('showHeaderInfo', 0);
		$this->smarty->assign('stylesheet', 'simple-request.css');
		
		global $databases;
		
		if(WebRequest::wasPosted())
		{
			if(WebRequest::postString("email") == WebRequest::postString("emailconfirm"))
				$this->addRequest(WebRequest::postString("name"),WebRequest::postString("email"),WebRequest::postString("comments"));
			else
				$this->error("EmailConfirmationMismatch");
		}
		else
		{	
			$this->smarty->assign('subpage', 'page/RequestForm.tpl');
		}
		
	}
	
	function addRequest($username,$email,$comments)
	{
		// validate
		if($username == "")
		{
			$this->error('BlankUsername');
			return;
		}
		
		if($email == "")
		{
			$this->error('BlankEmail');
			return;
		}
		
		// create object
		
		$r = new Request($username,$email,$comments);
		$r->save();
		
		// send confirmation email
	}
	
}