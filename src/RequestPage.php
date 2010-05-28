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
		global $databases;
		
		if(WebRequest::wasPosted())
		{
			
		}
		else
		{	
			$this->smarty->assign('subpage', 'page/RequestForm.tpl');
		}
		
	}
}