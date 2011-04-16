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
	
class Message extends DataObject
{	
	public function save()
	{
		trigger_error("Not implemented");
	}
	
	public function getMessage()
	{
		trigger_error("Not implemented");
	}
}