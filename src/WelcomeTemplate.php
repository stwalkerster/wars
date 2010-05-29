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
	
class WelcomeTemplate implements DataObject
{
	static function getById($id)
	{
		trigger_error("Not implemented.");
	}
	
	static function getDisplayList()
	{
		$s = new DatabaseSelect();
		$s->fields = array('template_id', 'template_display');
		$s->from='welcometemplate';
		
		global $accDatabase;
		$rs = $accDatabase->getResultSet($s, MYSQL_ASSOC);
		
		$list = array();
		foreach ($rs as $r) 
		{
			$list[$r['template_id']] = $r['template_display'];
		}
		
		return $list;
	}
	
	function save()
	{
		trigger_error("Not implemented.");
	}
}