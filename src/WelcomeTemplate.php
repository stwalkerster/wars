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
	
class WelcomeTemplate extends DataObject
{
	public static function getById($id)
	{
		$me = "WelcomeTemplate";
		global $accDatabase;
		$statement = $accDatabase->prepare("SELECT * FROM acc_".strtolower($me)." WHERE ".strtolower($me)."_id = :oid LIMIT 1;");
		$statement->bindParam(":oid",$id);
		$statement->execute();
		return $statement->fetchObject($me);
	}
	
	static function getDisplayList()
	{
		trigger_error("Not implemented.");
	}
	
	private $template_id, $template_usercode, $template_botcode;
	
	/**
	 * Saves the current state of the object to the database.
	 * 
	 * This is not transaction-based, all calls to it must be wrapped inside a transaction
	 * 
	 * @return true on success, false on failure
	 */
	protected function save()
	{
		trigger_error("Not implemented.");
	}
}