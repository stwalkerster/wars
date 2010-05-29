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
	
class User implements DataObject
{
	public static function authenticate($username, $password)
	{
		$select = new DatabaseSelect();
		$select->fields = array('user_id');
		$select->from = 'user';
		$select->addWhere('user_pass', '=', User::saltPassword($username , $password));
		$select->addWhere('user_name', '=', $username );
		
		global $accDatabase;
		
		$resultset = $accDatabase->getResultSet($select,MYSQL_ASSOC);
		
		print_r($resultset);
		
	} 

	public static function getByName($username);
	
	public static function getById($id);
	
	public static function saltPassword($username, $password)
	{
		return md5('ACC-' . $username . '-' . $password);
	}
	
	public function __construct();
	
	public function getId();
	
	public function getUsername();
	
	public function isAllowedPrivateData();
	
	public function save();
}