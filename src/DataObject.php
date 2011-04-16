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
	
abstract class DataObject
{
	public static function getById($id)
	{
		$me = get_class($this);
		global $accDatabase;
		$statement = $accDatabase->prepare("SELECT * FROM acc_".strtolower($me)." WHERE ".strtolower($me)."_id = :oid LIMIT 1;");
		$statement->bindParam(":oid",$id);
		return $statement->fetchObject($me);
	}
	
	abstract function save();
}