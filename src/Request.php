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
	
class Request implements DataObject
{
	public static function getById($id)
	{
		$this->new = false;
		
		return false;
	}
	
	public function __construct($name, $email, $comment)
	{
		// get globals needed
		global $defaultReserver;
		
		// it's new!
		$this->new = true;
		
		// new object, hasn't been assigned an id yet.
		$this->id = false;
		
		$this->name = $name;
		$this->email = $email;
		$this->ip = $_SERVER['REMOTE_ADDR'];
		$this->comment = $comment;
		$this->status = "New";
		$this->checksum = null; // updated in code automatically later
		$this->emailconfirmation = md5(time() . "|" . $name);
		$this->reserved = $defaultReserver;
		$this->useragent = $_ENV["HTTP_USER_AGENT"];
		$this->ip_hash = md5($this->ip);
		$this->email_hash = md5($this->email);
		$this->emailsent = 0;
		$this->date = null; // auto-inserted by database
	}
	
	// ro = read only
	private $id; // ro
	private $name; // ro
	private $email; // ro
	private $ip; // ro
	private $comment; // ro
	private $status;
	private $checksum;
	private $emailconfirmation;
	private $reserved;
	private $useragent; // ro
	private $ip_hash;
	private $email_hash;
	private $emailsent;
	private $date; // ro
	
	private $new;
	
	
	public function save($checksum)
	{
		global $accDatabase;
		
		if($this->new)
		{ // INSERT
			
			$values = array(
				$this->name,
				$this->email,
				$this->ip,
				$this->comment,
				$this->status,
				$this->checksum,
				$this->emailconfirmation,
				$this->reserved,
				$this->useragent,
				$this->email_hash,
				$this->ip_hash,
				$this->emailsent,
				$this->date
			);
			
			$this->id = $accDatabase->insert('request', $values);
			
			$this->new = false;
			
			$this->save(null); // save again to set the checksum.
			
			return true;
		}
		else
		{ // UPDATE
			
			if($this->checksum != $checksum)
				return false;
				
			// update the checksum
			$this->checksum = md5($this->id . $this->name . $this->email . microtime());
			
			$values = array(
				'request_email' => $this->email,
				'request_ip' => $this->ip,
				'request_status' => $this->status,
				'request_checksum' => $this->checksum,
				'request_emailconfirmation' => $this->emailconfirmation,
				'request_reserved' => $this->reserved,
				'request_emailsent' => $this->emailsent
			);
			
			$accDatabase->update('request', $values, array('pend_id' => $this->id));
			
			return true;
		}
		return true;
	}

	public function confirm($emailChecksum)
	{
		if($emailChecksum == $this->emailconfirmation)
		{
			$this->status = "Open";
			return $this->save($this->checksum);
		}
		return false;
	}
	
	public function reserve($checksum)
	{
		$user = unserialize(WebRequest::sessionOrBlank('currentUser'));
		$this->reserved = $user->getId();
		$this->save($checksum);
	}
	public function unreserve($checksum)
	{
		$this->reserved = 0;
		$this->save($checksum);
	}
	
	public function getId()
	{
		return $this->id;	
	}
	public function getEmail()
	{
		$user = unserialize(WebRequest::sessionOrBlank('currUser'));
		if($user->isAllowedPrivateData() || $this->isOpen() )
		{
			return $this->email;
		}
		return $this->email_hash;
	}
	public function getIp()
	{
		$user = unserialize(WebRequest::sessionOrBlank('currUser'));
		if($user->isAllowedPrivateData() || $this->isOpen() )
		{
			return $this->ip;
		}
		return $this->ip_hash;
	}
	public function getName()
	{
		return $this->name;
	}
	public function getComment()
	{
		return $this->comment;
	}
	public function getStatus()
	{
		return $this->status;
	}
	public function getDate()
	{
		return $this->date;
	}
	public function getChecksum()
	{
		return $this->checksum;
	}
	public function emailsent()
	{
		return $this->emailsent;
	}
	public function getEmailConfirmation()
	{
		return $this->emailconfirmation;
	}
	public function getReserved()
	{
		return $this->reserved;
	}
	public function getUseragent()
	{
		$user = unserialize(WebRequest::sessionOrBlank('currUser'));
		if($user->isAllowedPrivateData() )
		{
			return $this->useragent;
		}
		return false;
	}

	public function defer($target, $checksum)
	{
		$this->status = $target;
		$this->save($checksum);
	}
	public function close($emailId, $checksum)
	{
		if($emailId == 1)
			$this->status = "Created";
		else
			$this->status = "Declined";
			
		if($emailId != 0)
		{
			$message = Message::getById($emailId);
			mail($this->email, "Re: English Wikipedia Account Request", $message->getMessage());
		}
		return $this->save($checksum);
	}
	
	public function customClose($created, $message, $checksum)
	{
		if($created == 1)
			$this->status = "Created";
		else
			$this->status = "Declined";
			
		mail($this->email, "Re: English Wikipedia Account Request", $message);
		return $this->save($checksum);
	}

	
	/**
	 * Is the current request in an open state?
	 * @return boolean
	 */
	public function isOpen()
	{
		if($this->status != "Created"
		&& $this->status != "Declined"
		&& $this->status != "New")
			return true;
		else
			return false;
	}
}