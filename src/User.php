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
	
class User extends DataObject
{
	/**
	 * Checks the user's credentials, and logs in the user.
	 * @param string $username The user's username
	 * @param string $password The raw password
	 * @return boolean login was successful.
	 */
	public static function authenticate($username, $password)
	{
		global $accDatabase;
		$statement = $accDatabase->prepare("SELECT * FROM user WHERE user_name = :username AND user_password = :password LIMIT 1;");
		$statement->bindParam(":username", $username);
		$statement->bindParam(":password", self::saltPassword($username, $password));
		$resultUser = $statement->fetchObject("User");
	
		if($resultUser != false)
		{
			$_SESSION['currentUser'] = serialize($resultUser);
			return true;
		}
		else
			return false;
		
	} 

	public static function getByName($username)
	{		
		global $accDatabase;
		$statement = $accDatabase->prepare("SELECT * FROM acc_user WHERE user_name = :username LIMIT 1;");
		$statement->bindParam(":username",$username);
		return $statement->fetchObject("User");
	}
	
	public static function saltPassword($username, $password)
	{
		return md5('ACC-' . $username . '-' . $password);
	}
	
	public function __construct($username, $password, $email, $onwiki)
	{
		$this->user_id = 0;
		$this->user_name = $username;
		$this->user_email = $email;
		$this->user_pass = User::saltPassword($username, $password);
		$this->user_level = "New";
		$this->user_onwikiname = $onwiki;
		$this->user_welcome_sig = "";
		$this->user_welcome_templateid = 0;
		$this->user_lastactive = date("Y-m-d H:i:s", 0);
		$this->user_lastip = "0.0.0.0";
		$this->user_forcelogout = 0;
		$this->user_secure = 0;
		$this->user_checkuser = 0;
		$this->user_identified = 0;
		$this->user_abortpref = 0;
		$this->user_confirmationdiff = 0;
		
		$this->newRecord = 1;
	}
	
	private $user_id, $user_name, $user_email, $user_pass, $user_level, $user_onwikiname, $user_welcome_sig, 
		$user_welcome_templateid, $user_lastactive, $user_lastip, $user_forcelogout, $user_secure, 
		$user_checkuser, $user_identified, $user_abortpref, $user_confirmationdiff;
	
	private $newRecord;
	
	public function getId()
	{ 
		return $this->user_id; 
	}
	
	public function getUsername()
	{ 
		return $this->user_name; 
	}
	
	public function getEmail() // looking into privacy at the moment
	{
		return $this->user_email;
	} 
	public function setEmail($newemail) // security, is current user an admin, or is this the current user?
	{
		$this->user_email = $newemail;
	} 
	
	public function setPass($newpass, $oldpass)
	{
		if(User::saltPassword($this->username, $oldpass) == $this->pass)
		{
			$this->user_pass = User::saltPassword($this->username, $newpass);
		}
	}
	
	public function isAdmin() 
	{
		return $this->user_level == "Admin";
	}
	public function promoteAdmin( ) // security, is current user an admin?
	{
		// TODO: Implement function;
		trigger_error("Not implemented.");
	}
	public function demoteAdmin( $reason ) // security, is current user an admin?
	{
		// TODO: Implement function;
		trigger_error("Not implemented.");
	}
	
	public function getDeveloper() 
	{
		// TODO: Implement function;
		trigger_error("Not implemented.");
	}
	
	public function isSuspended() 
	{
		return $this->user_level == "Suspended";
	}
	public function suspend( $reason ) // security, is current user an admin?
	{
		// TODO: Implement function;
		trigger_error("Not implemented.");
	}
	public function unsuspend( ) // security, is current user an admin?
	{
		// TODO: Implement function;
		trigger_error("Not implemented.");
	}
	
	public function isNew() 
	{
		return $this->user_level == "New";
	}
	public function approve( ) // security, is current user an admin?
	{
		// TODO: Implement function;
		trigger_error("Not implemented.");
	}
	
	public function isAllowedPrivateData()
	{
		// TODO: Implement function;
		trigger_error("Not implemented.");
	}
	
	public function getOnwikiName()
	{
		return $this->user_onwikiname;
	} 
	public function setOnwikiName($username) // security, is current user an admin?
	{
		trigger_error("Not implemented.");
	}
	
	public function getTemplate()
	{
		return $this->user_welcome_templateid;
	}
	public function setTemplate($id)
	{
		$this->user_welcome_templateid = $id;
	}
	
	public function getSecureStatus()
	{
		return $this->user_secure;
	}
	public function setSecureStatus($useSecureServer)
	{
		$this->user_secure = $useSecureServer;
	}
	
	public function getSignature()
	{
		return $this->user_welcome_sig;
	}
	public function setSignature($signature)
	{
		$this->user_welcome_sig=$signature;
	}
	
	
	public function save()
	{
		global $accDatabase;
		if($this->newRecord)
		{ // INSERT
			$statement = $accDatabase->prepare("INSERT INTO `acc_user`(`user_name`,`user_email`,`user_pass`,`user_onwikiname`,`user_confirmationdiff`)VALUES(:username,:useremail,:userpass,:useronwikiname,:userconfirmationdiff);");
			$statement->bindValue(":username", $this->user_name);
			$statement->bindValue(":useremail", $this->user_email);
			$statement->bindValue(":useronwikiname", $this->user_onwikiname);
			$statement->bindValue(":userpass", $this->user_pass);
			$statement->bindValue(":userconfirmationdiff", $this->user_confirmationdiff);
			if(!$statement->execute())
				return false;
			$this->newRecord = 0;
			return true;
		}
		else
		{ // UPDATE
			$sqlText = "UPDATE `acc_user` SET `user_name` = :username, `user_email` = :useremail, `user_pass` = :userpass, `user_level` = :userlevel, `user_onwikiname` = :useronwikiname, `user_welcome_sig` = :userwelcomesig, `user_lastactive` = :userlastactive, `user_lastip` = :userlastip, `user_forcelogout` = :userforcelogout, `user_secure` = :usersecure, `user_checkuser` = :usercheckuser, `user_identified` = :useridentified, `user_welcome_templateid` = :userwelcometemplateid, `user_abortpref` = :userabortpref, WHERE `user_id` = :userid;";
			$statement = $accDatabase->prepare($sqlText);
			$statement->bindValue(":username", $this->user_name);
			$statement->bindValue(":useremail", $this->user_email);
			$statement->bindValue(":userpass", $this->user_pass);
			$statement->bindValue(":userlevel", $this->user_level);
			$statement->bindValue(":useronwikiname", $this->user_onwikiname);
			$statement->bindValue(":userwelcomesig", $this->user_welcome_sig);
			$statement->bindValue(":userlastactive", $this->user_lastactive);
			$statement->bindValue(":userlastip", $this->user_lastip);
			$statement->bindValue(":userforcelogout", $this->user_forcelogout);
			$statement->bindValue(":usersecure", $this->user_secure);
			$statement->bindValue(":usercheckuser", $this->user_checkuser);
			$statement->bindValue(":useridentified", $this->user_identified);
			$statement->bindValue(":userwelcometemplateid", $this->user_welcome_templateid);
			$statement->bindValue(":userabortpref", $this->user_abortpref);
			$statement->bindValue(":userid", $this->user_id);
			return $statement->execute();
		}
	}
}