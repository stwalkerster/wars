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

class RequestPage extends PageBase
{
	function __construct()
	{
		$this->subtitle = "Request an account on the English Wikipedia";
		OutputPage::getInstance()->title = "Account Creation Assistance";
	}
	
	function runPage()
	{
		
	}
	
	function standardMenu()
	{
		$linkBase = WebRequest::getScriptName();
		
		$pages = array(
				'Wikipedia' => $linkBase . '/Forward?link=http://en.wikipedia.org/'
		);
		
		$this->showMenu('menu', $pages);
	}
}