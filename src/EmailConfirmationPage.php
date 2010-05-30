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
if(!defined("ACCSYSTEM"))
	die("Invalid code entry point!");

/**
 * EmailConfirmationPage class
 * 
 * THIS IS OUTSIDE THE PAGE SEARCH DIRECTORY FOR A REASON.
 *  
 * @author stwalkerster
 *
 */
class EmailConfirmationPage extends PageBase
{
	public function runPage()
	{
		$id = WebRequest::getInt('rid');
		$checksum = WebRequest::getInt('rc');
		
		$r = Request::getById($id);
		$r->confirm($checksum);
		
	}
}