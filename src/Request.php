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
	
class Request extends DataObject
{	
	static function generateConfirmationHash($id)
	{
		// Sets the seed variable as the current Unix timestamp with microseconds.
		// The following lines of code ensure that the HASH is unique.
		$seed = microtime(true);
		
		// Delay execution for a random number of miliseconds.
		// Adds the current Unix timestamp to the seed variable.
		usleep(rand(0,3000));
		$seed = $seed +  microtime(true);
		
		// Delay execution for a random number of miliseconds.
		// Adds the current Unix timestamp to the seed variable.
		usleep(rand(0,300));
		$seed = $seed +  microtime(true);
		
		// Delay execution for a random number of miliseconds.
		// Subtracts the current Unix timestamp to the seed variable.
		usleep(rand(0,300));
		$seed = $seed -  microtime(true);
		
		// Seed the better random number generator.
		mt_srand($seed);
		
		// Generates the salt which would be used to generate the HASH.
		$salt = mt_rand();
		
		// Generates the HASH.
		return md5($id . $salt);
	}
	
	public function __construct($name, $email, $comment)
	{
		// get globals needed
		global $defaultReserver;
		
		// it's new!
		$this->new = true;
		
		// new object, hasn't been assigned an id yet.
		$this->request_id = false;
		
		$this->request_email = $email;
		$this->request_ip = $_SERVER['REMOTE_ADDR'];
		$this->request_name = $name;
		$this->request_cmt = $comment;
		$this->request_status = "New";
		$this->request_date = date("Y-m-d H:i:s");
		$this->request_checksum = "";
		$this->request_emailsent = '';
		$this->request_mailconfirm = '';
		$this->request_reserved = $defaultReserver;
		$this->request_useragent = $_SERVER['HTTP_USER_AGENT'];
		$this->request_proxyip = ''; //TODO: set xff header
		
	}

	
	private $new;
	
	private $request_id, $request_email, $request_ip, $request_name,
		$request_cmt, $request_status, $request_date, $request_checksum,
		$request_emailsent, $request_mailconfirm, $request_reserved, 
		$request_useragent, $request_proxyip;
	
	
	public function save($checksum)
	{
		global $accDatabase;
		
		if($this->new)
		{ 
			// TODO: save
			
			$this->request_id = 0; // get last insert id
			
			$this->request_mailconfirm = self::generateConfirmationHash($this->request_id);
			
			$this->new = false;
			
			$this->save(null); // save again to set the checksum and mail confirmation.
			
			return true;
		}
		else
		{ // TODO: UPDATE
			
			if($this->request_checksum != $checksum)
				return false;
				
			// update the checksum
			$this->checksum = md5($this->id . $this->name . $this->email . microtime());
			
//			$values = array(
//				'request_email' => $this->email,
//				'request_ip' => $this->ip,
//				'request_status' => $this->status,
//				'request_checksum' => $this->checksum,
//				'request_emailconfirmation' => $this->emailconfirmation,
//				'request_reserved' => $this->reserved,
//				'request_emailsent' => $this->emailsent
//			);
//			
//			$accDatabase->update('request', $values, array('pend_id' => $this->id));
			
			return true;
		}
		return true;
	}

	public function confirm($emailChecksum)
	{
//TODO
	}
	
	public function reserve($checksum)
	{
		//TODO
	}
	public function unreserve($checksum)
	{
		//TODO
	}
	
	public function getId()
	{
		return $this->request_id;	
	}
	public function getEmail()
	{
		//TODO
	}
	public function getIp()
	{
		//TODO
	}
	public function getName()
	{
		return $this->request_name;
	}
	public function getComment()
	{
		return $this->request_cmt;
	}
	public function getStatus()
	{
		return $this->request_status;
	}
	public function getDate()
	{
		return $this->request_date;
	}
	public function getChecksum()
	{
		return $this->request_checksum;
	}
	public function emailsent()
	{
		return $this->request_emailsent;
	}
	public function getEmailConfirmation()
	{
		return $this->request_mailconfirm;
	}
	public function getReserved()
	{
		return $this->request_reserved;
	}
	public function getUseragent()
	{
		//TODO
	}

	public function defer($target, $checksum)
	{
		$this->status = $target;
		$this->save($checksum);
	}
	public function close($emailId, $checksum)
	{
		//TODO
	}
	
	public function customClose($created, $message, $checksum)
	{
		//TODO
	}

	
	/**
	 * Is the current request in an open state?
	 * @return boolean
	 */
	public function isOpen()
	{
		//TODO
	}
	
	/**
	 * 
	 * Is the request available to the current user
	 * @returns true if unreserved or reserved by the current user
	 */
	public function isAvailableForCurrentUser()
	{
		if($this->getReserved() == 0)
			return true;
		if($this->getReserved() == WebRequest::getCurrentUser()->getId())
			return true;
		
		return false;
	}
}