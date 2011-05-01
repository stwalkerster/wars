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

define('LOG_COLUMN_ID', 'log_id');
define('LOG_COLUMN_TARGETID', 'log_target_id');
define('LOG_COLUMN_TARGETOBJECTTYPE', 'log_target_objecttype');
define('LOG_COLUMN_TARGETTEXT', 'log_target_text');
define('LOG_COLUMN_USERID', 'log_user_id');
define('LOG_COLUMN_USERTEXT', 'log_user_text');
define('LOG_COLUMN_ACTION', 'log_action');
define('LOG_COLUMN_TIME', 'log_time');
define('LOG_COLUMN_CMT', 'log_cmt');

define('LOG_ACTION_APPROVED','Approved');
define('LOG_ACTION_BANNED','Banned');
define('LOG_ACTION_BREAKRESERVE','BreakReserve');
define('LOG_ACTION_CLOSEDCREATED','Closed Created');
define('LOG_ACTION_CLOSEDDROPPED','Closed Dropped');
define('LOG_ACTION_CLOSEDPWRESET','Closed Password Reset');
define('LOG_ACTION_CLOSEDSUL','Closed SUL Taken');
define('LOG_ACTION_CLOSEDSIMILAR','Closed Similar');
define('LOG_ACTION_CLOSEDTAKEN','Closed Taken');
define('LOG_ACTION_CLOSEDTECHNICAL','Closed Technical');
define('LOG_ACTION_CLOSEDUPOLICY','Closed UPolicy');
define('LOG_ACTION_CLOSEDCUSTOMN','Closed custom-n');
define('LOG_ACTION_CLOSEDCUSTOMY','Closed custom-y');
define('LOG_ACTION_CREATEDTEMPLATE','CreatedTemplate');
define('LOG_ACTION_DECLINED','Declined');
define('LOG_ACTION_DEFERCHECKUSERS','Deferred to checkusers');
define('LOG_ACTION_DEFERFLAGGEDUSERS','Deferred to flagged users');
define('LOG_ACTION_DEFERUSERS','Deferred to users');
define('LOG_ACTION_DELETEDTEMPLATE','DeletedTemplate');
define('LOG_ACTION_DEMOTED','Demoted');
define('LOG_ACTION_EDITED','Edited');
define('LOG_ACTION_EDITEDTEMPLATE','EditedTemplate');
define('LOG_ACTION_EMAILCONFIRMED','Email Confirmed');
define('LOG_ACTION_PREFCHANGE','Prefchange');
define('LOG_ACTION_PROMOTED','Promoted');
define('LOG_ACTION_RENAMED','Renamed');
define('LOG_ACTION_RESERVED','Reserved');
define('LOG_ACTION_SUSPENDED','Suspended');
define('LOG_ACTION_UNBANNED','Unbanned');
define('LOG_ACTION_UNRESERVED','Unreserved');


class Log extends DataObject
{
	/**
	 * Saves the current state of the object to the database.
	 * 
	 * This is not transaction-based, all calls to it must be wrapped inside a transaction
	 * 
	 * @return true on success, false on failure
	 */
	protected function save()
	{
		
	}
	
	public static function getById($id)
	{
		$me = "Log";
		global $accDatabase;
		$statement = $accDatabase->prepare("SELECT * FROM acc_".strtolower($me)." WHERE ".strtolower($me)."_id = :oid LIMIT 1;");
		$statement->bindParam(":oid",$id);
		$statement->execute();
		return $statement->fetchObject($me);
	}
}
