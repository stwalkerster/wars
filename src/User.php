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
	
class User implements DataObject
{
	public static function authenticate($username, $password)
	{
		
	} 

	public static function getByName($username);
	
	public static function getById($id);
	
	public function __construct();
	
	public function getId();
	
	public function getUsername();
	
	public function isAllowedPrivateData();
	
	public function save();
}