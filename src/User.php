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
		$statement = $accDatabase->prepare("SELECT * FROM acc_user WHERE user_name = :username LIMIT 1;");
		$statement->bindParam(":username", $username);
		$statement->execute();
		$resultUser = $statement->fetchObject("User");

		if($resultUser != false)
		{
			if($resultUser->checkPass($password))
			{
				$_SESSION['currentUser'] = serialize($resultUser);
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}

	}

	public static function getById($id)
	{
		$me = "User";
		global $accDatabase;
		$statement = $accDatabase->prepare("SELECT * FROM acc_".strtolower($me)." WHERE ".strtolower($me)."_id = :oid LIMIT 1;");
		$statement->bindParam(":oid",$id);
		$statement->execute();
		return $statement->fetchObject($me);
	}
	
	public static function getByName($username)
	{
		global $accDatabase;
		$statement = $accDatabase->prepare("SELECT * FROM acc_user WHERE user_name = :username LIMIT 1;");
		$statement->bindParam(":username",$username);
		$statement->execute();
		return $statement->fetchObject("User");
	}

	public static function saltPassword($username, $password)
	{
		return "$2$" . md5('ACC-' . $username . '-' . $password);
	}

	public function __construct()
	{

	}

	public static function create($username, $password, $email, $onwiki)
	{
		$instance = new self();
		$instance->user_id = 0;
		$instance->user_name = $username;
		$instance->user_email = $email;
		$instance->user_pass = User::saltPassword($username, $password);
		$instance->user_level = "New";
		$instance->user_onwikiname = $onwiki;
		$instance->user_welcome_sig = "";
		$instance->user_welcome_templateid = 0;
		$instance->user_lastactive = date("Y-m-d H:i:s", 0);
		$instance->user_lastip = "0.0.0.0";
		$instance->user_forcelogout = 0;
		$instance->user_secure = 0;
		$instance->user_checkuser = 0;
		$instance->user_identified = 0;
		$instance->user_abortpref = 0;
		$instance->user_confirmationdiff = 0;

		$instance->newRecord = 1;

		return $instance;
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

		//TODO: save
	}

	public function setPass($newpass, $oldpass)
	{
		if(User::saltPassword($this->username, $oldpass) == $this->user_pass)
		{
			$this->user_pass = User::saltPassword($this->username, $newpass);
		}

		//TODO: save
	}

	/**
	 * Checks if this user's password is the value specified.
	 *
	 * This function will also update the user's password to the latest hash version
	 *
	 * @param string $cleartext
	 * @return boolean
	 */
	public function checkPass($cleartext)
	{
		$version = substr($this->user_pass, 0, 3);
		if($version == "$2$")
		{
			return ($this->user_pass == self::saltPassword($this->user_name, $cleartext));
		}
		else
		{
			$calc = md5($cleartext);
				
			if(md5($cleartext) == $this->user_pass)
			{
				// update password to new spec

				global $accDatabase;
				$accDatabase->beginTransaction();
				$this->user_pass = self::saltPassword($this->user_name, $cleartext);
				if($this->save())
				{
					$accDatabase->commit();
				}
				else
				{
					$accDatabase->rollBack();
				}
				return true;
			}
			else return false;
		}
	}

	public function isAdmin()
	{
		return $this->user_level == "Admin";
	}
	
	public function isCheckuser()
	{
		return $this->user_checkuser == "1";	
	}
	
	public function promoteAdmin( ) // security, is current user an admin?
	{
		// TODO: Implement function;
		throw new NotImplementedException();
	}
	public function demoteAdmin( $reason ) // security, is current user an admin?
	{
		// TODO: Implement function;
		throw new NotImplementedException();
	}

	public function getDeveloper()
	{
		// TODO: Implement function;
		throw new NotImplementedException();
	}

	public function isSuspended()
	{
		return $this->user_level == "Suspended";
	}
	public function suspend( $reason ) // security, is current user an admin?
	{
		// TODO: Implement function;
		throw new NotImplementedException();
	}
	public function unsuspend( ) // security, is current user an admin?
	{
		// TODO: Implement function;
		throw new NotImplementedException();
	}

	public function isNew()
	{
		return $this->user_level == "New";
	}
	public function approve( ) // security, is current user an admin?
	{
		// TODO: Implement function;
		throw new NotImplementedException();
	}

	/**
	 * @return boolean true if the user is allowed to access private data
	 * Returns true if the user is allowed to access private data, false otherwise
	 * 
	 * At the moment, this is a simple check if the user is an admin or checkuser.
	 */
	public function isAllowedPrivateData()
	{
		if($this->isCheckuser())
		{
			return true;
		}
		if($this->isAdmin())
		{
			return true;
		}

		return false;
	}

	public function getOnwikiName()
	{
		return $this->user_onwikiname;
	}
	public function setOnwikiName($username) // security, is current user an admin?
	{
		throw new NotImplementedException();
	}

	public function getTemplate()
	{
		return $this->user_welcome_templateid;
	}
	public function setTemplate($id)
	{
		$this->user_welcome_templateid = $id;
		//TODO: save
	}

	public function getSecureStatus()
	{
		return $this->user_secure;
	}
	public function setSecureStatus($useSecureServer)
	{
		$this->user_secure = $useSecureServer;
		//TODO: save
	}

	public function getSignature()
	{
		return $this->user_welcome_sig;
	}
	public function setSignature($signature)
	{
		$this->user_welcome_sig=$signature;
		//TODO:save
	}

	/**
	 * Saves the current state of the object to the database.
	 *
	 * This is not transaction-based, all calls to it must be wrapped inside a transaction
	 *
	 * @return true on success, false on failure
	 */
	protected function save()
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
			$sqlText = "UPDATE `acc_user` SET `user_name` = :username, `user_email` = :useremail, `user_pass` = :userpass, `user_level` = :userlevel, `user_onwikiname` = :useronwikiname, `user_welcome_sig` = :userwelcomesig, `user_lastactive` = :userlastactive, `user_lastip` = :userlastip, `user_forcelogout` = :userforcelogout, `user_secure` = :usersecure, `user_checkuser` = :usercheckuser, `user_identified` = :useridentified, `user_welcome_templateid` = :userwelcometemplateid, `user_abortpref` = :userabortpref WHERE `user_id` = :userid;";
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