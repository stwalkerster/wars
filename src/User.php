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
		
		return false;
		
	} 

	public static function getByName($username){}	
	public static function getById($id){}
	
	public static function saltPassword($username, $password)
	{
		return md5('ACC-' . $username . '-' . $password);
	}
	
	public function __construct($username, $password, $email, $onwiki)
	{
		$this->id = 0;
		$this->name = $username;
		$this->email = $email;
		$this->pass = User::saltPassword($username, $password);
		$this->admin = 0;
		$this->developer = 0;
		$this->suspended = 0;
		$this->new = 1;
		$this->viewprivate = 0;
		$this->onwiki = $onwiki;
		$this->welcome = 0;
		$this->welcometemplate = 1;
		$this->secureserver = 0;
		$this->signature = "";
		
		$this->newRecord = 1;
	}
	
	private $id, $name, $email, $pass, $admin, $developer, $suspended, $new;
	private $viewprivate, $onwiki, $welcome, $welcometemplate, $secureserver;
	private $signature;
	
	private $newRecord;
	
	public function getId(){ return $id; }
	
	public function getUsername(){ return $name; }
	
	public function getEmail(){} // looking into privacy at the moment
	public function setEmail(){} // security, is current user an admin, or is this the current user?
	
	public function setPass($newpass, $oldpass){}
	
	public function isAdmin() {return $admin;}
	public function promoteAdmin( ){} // security, is current user an admin?
	public function demoteAdmin( $reason ){} // security, is current user an admin?
	
	public function getDeveloper() {return $developer;}
	
	public function isSuspended() {return $suspended;}
	public function suspend( $reason ){} // security, is current user an admin?
	public function unsuspend( ){} // security, is current user an admin?
	
	public function isNew() {return $new;}
	public function approve( ){} // security, is current user an admin?
	
	public function isAllowedPrivateData(){return $viewprivate;}
	
	public function getOnwikiName(){return $onwiki;} 
	public function setOnwikiName(){} // security, is current user an admin?
	
	public function getWelcomeStatus(){return $welcome;}
	public function setWelcomeStatus($welcomeUsers){} // security, is current user an admin, or is this the current user?
	
	public function getTemplate(){return $welcomeTemplate;}
	public function setTemplate($id) {}  // security, is current user an admin, or is this the current user?
	
	public function getSecureStatus(){return $secureserver;}
	public function setSecureStatus($useSecureServer){} // security, is current user an admin, or is this the current user?
	
	public function getSignature(){return $signature;}
	public function setSignature($signature){} // security, is current user an admin, or is this the current user?
	
	public function save()
	{
		
	}
}