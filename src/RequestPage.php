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
	}
	
	function runPage()
	{
		global $databases;
		$out = OutputPage::getInstance();
		
		$out->title = "Account Creation Assistance";
		
		if(WebRequest::wasPosted())
		{
			
		}
		else
		{	
			$this->showRequestForm();
		}
		
	}
	
	function showRequestForm()
	{
		
	}

}