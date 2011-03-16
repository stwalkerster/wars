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
		
		if(isset($resultset[0]))
		{
			$u = User::getById($resultset[0]['user_id']);
			$_SESSION['currUser'] = serialize($u);
			
			return true;
		}
		else
			return false;
		
	} 

	public static function getByName($username)
	{		
		$select = new DatabaseSelect();
		$select->fields = array('*');
		$select->from = 'user';
		$select->addWhere('user_name', '=', $username );
		$select->limit=1;
		
		global $accDatabase;
		$resultset = $accDatabase->getResultSet($select,MYSQL_ASSOC);
		
		$c = new User();
		
		$c->id = $resultset[0]['user_id'];
		$c->name = $username;
		$c->email = $resultset[0]['user_email'];
		$c->pass = $resultset[0]['user_pass'];
		$c->admin = $resultset[0]['user_admin'];
		$c->developer = $resultset[0]['user_developer'];
		$c->suspended = $resultset[0]['user_suspended'];
		$c->new = $resultset[0]['user_new'];
		$c->viewprivate = $resultset[0]['user_viewprivate'];
		$c->onwiki = $resultset[0]['user_onwiki'];
		$c->welcome = $resultset[0]['user_welcome'];
		$c->welcometemplate = $resultset[0]['user_welcometemplate'];
		$c->secureserver = $resultset[0]['user_secureserver'];
		$c->signature = $resultset[0]['user_signature'];
		
		$c->newRecord = 0;
		
		return $c;
	}
	public static function getById($id)
	{
		$select = new DatabaseSelect();
		$select->fields = array('*');
		$select->from = 'user';
		$select->addWhere('user_id', '=', $id );
		$select->limit=1;
		
		global $accDatabase;
		$resultset = $accDatabase->getResultSet($select,MYSQL_ASSOC);
		
		$c = new User();
		
		$c->id = $id;
		$c->name = $resultset[0]['user_name'];
		$c->email = $resultset[0]['user_email'];
		$c->pass = $resultset[0]['user_pass'];
		$c->admin = $resultset[0]['user_admin'];
		$c->developer = $resultset[0]['user_developer'];
		$c->suspended = $resultset[0]['user_suspended'];
		$c->new = $resultset[0]['user_new'];
		$c->viewprivate = $resultset[0]['user_viewprivate'];
		$c->onwiki = $resultset[0]['user_onwiki'];
		$c->welcome = $resultset[0]['user_welcome'];
		$c->welcometemplate = $resultset[0]['user_welcometemplate'];
		$c->secureserver = $resultset[0]['user_secureserver'];
		$c->signature = $resultset[0]['user_signature'];
		
		$c->newRecord = 0;
		
		return $c;	
	}
	
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
	
	public function getId()
	{ 
		return $this->id; 
	}
	
	public function getUsername()
	{ 
		return $this->name; 
	}
	
	public function getEmail() // looking into privacy at the moment
	{
		return $this->email;
	} 
	public function setEmail($newemail) // security, is current user an admin, or is this the current user?
	{
		$this->email = $newemail;
	} 
	
	public function setPass($newpass, $oldpass)
	{
		if(User::saltPassword($this->username, $oldpass) == $this->pass)
		{
			$this->pass = User::saltPassword($this->username, $newpass);
		}
	}
	
	public function isAdmin() 
	{
		return $this->admin;
	}
	public function promoteAdmin( ) // security, is current user an admin?
	{
		trigger_error("Not implemented.");
	}
	public function demoteAdmin( $reason ) // security, is current user an admin?
	{
		trigger_error("Not implemented.");
	}
	
	public function getDeveloper() 
	{
		return $this->developer;
	}
	
	public function isSuspended() 
	{
		return $this->suspended;
	}
	public function suspend( $reason ) // security, is current user an admin?
	{
		trigger_error("Not implemented.");
	}
	public function unsuspend( ) // security, is current user an admin?
	{
		trigger_error("Not implemented.");
	}
	
	public function isNew() 
	{
		return $this->new;
	}
	public function approve( ) // security, is current user an admin?
	{
		trigger_error("Not implemented.");
	}
	
	public function isAllowedPrivateData()
	{
		return $this->viewprivate;
	}
	
	public function getOnwikiName()
	{
		return $this->onwiki;
	} 
	public function setOnwikiName($username) // security, is current user an admin?
	{
		trigger_error("Not implemented.");
	}
	
	public function getWelcomeStatus()
	{
		return $this->welcome;
	}
	public function setWelcomeStatus($welcomeUsers)
	{
		$this->welcome = $welcomeUsers ;	
	}
	
	public function getTemplate()
	{
		return $this->welcomeTemplate;
	}
	public function setTemplate($id)
	{
		$this->welcometemplate = $id;
	}
	
	public function getSecureStatus()
	{
		return $this->secureserver;
	}
	public function setSecureStatus($useSecureServer)
	{
		$this->secureserver = $useSecureServer;
	}
	
	public function getSignature()
	{
		return $this->signature;
	}
	public function setSignature($signature)
	{
		$this->signature=$signature;
	}
	
	public function save()
	{
		global $accDatabase;
		if($this->newRecord)
		{ // INSERT
//			$values = array(
//					$this->name, $this->email, $this->pass, $this->admin, $this->developer, 
//					$this->suspended, $this->new, $this->viewprivate, $this->onwiki, $this->welcome, 
//					$this->welcometemplate, $this->secureserver, $this->signature
//					);
//			
//			$this->id = $accDatabase->insert('user', $values);
			
			$this->newRecord = 0;
		}
		else
		{ // UPDATE
//			$values = array(
//					'user_email' => $this->email, 'user_pass' => $this->pass, 'user_admin' => $this->admin, 
//					'user_developer' => $this->developer, 'user_suspended' => $this->suspended, 
//					'user_new' => $this->new, 'user_onwiki' => $this->onwiki, 
//					'user_welcome' => $this->welcome, 'user_welcometemplate' => $this->welcometemplate, 
//					'user_secureserver' => $this->secureserver, 'user_signature' => $this->signature
//					);
//			
//			$accDatabase->update('user', $values, "user_id = $this->id", 1);
		}
	}
}