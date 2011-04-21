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

// initialise the default checksum for new requests
define("WARS_REQUEST_DEFAULT_CHECKSUM", md5("WARS_REQUEST_DEFAULT_CHECKSUM"));	

define("REQUEST_STATUS_NEW", "New");
define("REQUEST_STATUS_OPEN", "Open");

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
		$this->request_checksum = WARS_REQUEST_DEFAULT_CHECKSUM;
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
	

	protected function save($checksum)
	{
		global $accDatabase;
		
		if($this->new)
		{ 
			$statement = $accDatabase->prepare("INSERT INTO acc_user ( request_id, request_email, request_ip, request_name, request_cmt, request_status, request_date, request_checksum, request_emailsent, request_mailconfirm, request_reserved,  request_useragent, request_proxyip ) VALUES (  :request_id, :request_email, :request_ip, :request_name, :request_cmt, :request_status, :request_date, :request_checksum, :request_emailsent, :request_mailconfirm, :request_reserved, :request_useragent, :request_proxyip );");

			$this->request_id = 0; 
			
			$statement->bindParam(":request_id",$this->request_id);
			$statement->bindParam(":request_email",$this->request_email);
			$statement->bindParam(":request_ip",$this->request_ip);
			$statement->bindParam(":request_name",$this->request_name);
			$statement->bindParam(":request_cmt",$this->request_cmt);
			$statement->bindParam(":request_status",$this->request_status);
			$statement->bindParam(":request_date",$this->request_date);
			$statement->bindParam(":request_checksum",$this->request_checksum);
			$statement->bindParam(":request_emailsent",$this->request_emailsent);
			$statement->bindParam(":request_mailconfirm",$this->request_mailconfirm);
			$statement->bindParam(":request_reserved",$this->request_reserved);
			$statement->bindParam(":request_useragent",$this->request_useragent);
			$statement->bindParam(":request_proxyip",$this->request_proxyip);
			
			
			
			if(!$statement->execute())
			{
				return false;	
			}
			
			$this->request_id = $accDatabase->lastInsertId();
			
			$this->request_mailconfirm = self::generateConfirmationHash($this->request_id);
			
			$this->new = false;
			
			// save again to set the checksum and mail confirmation.
			// this needs the default checksum because this is the initial save
			if(!$this->save(WARS_REQUEST_DEFAULT_CHECKSUM))
			{
				return false;
			} 
			
			return true;
		}
		else
		{ 			
			if($this->request_checksum != $checksum)
				return false;
				
			// update the checksum
			$this->request_checksum = md5($this->id . $this->name . $this->email . microtime());
			
			$statement = $accDatabase->prepare("UPDATE acc_request SET request_email = :request_email, request_ip = :request_ip, request_name = :request_name, request_cmt = :request_cmt, request_status = :request_status, request_date = :request_date, request_checksum = :request_checksum, request_emailsent = :request_emailsent, request_mailconfirm = :request_mailconfirm, request_reserved = :request_reserved, request_useragent = :request_useragent, request_proxyip = :request_proxyip WHERE request_id = :request_id AND request_checksum = :checksum;");
			
			$statement->bindParam(":request_id",$this->request_id);
			$statement->bindParam(":request_email",$this->request_email);
			$statement->bindParam(":request_ip",$this->request_ip);
			$statement->bindParam(":request_name",$this->request_name);
			$statement->bindParam(":request_cmt",$this->request_cmt);
			$statement->bindParam(":request_status",$this->request_status);
			$statement->bindParam(":request_date",$this->request_date);
			$statement->bindParam(":request_checksum",$this->request_checksum); // (update to new checksum)
			$statement->bindParam(":request_emailsent",$this->request_emailsent);
			$statement->bindParam(":request_mailconfirm",$this->request_mailconfirm);
			$statement->bindParam(":request_reserved",$this->request_reserved);
			$statement->bindParam(":request_useragent",$this->request_useragent);
			$statement->bindParam(":request_proxyip",$this->request_proxyip);
			$statement->bindParam(":checksum", $checksum); // (check old checksum AGAIN)
			
			if(!$statement->execute())
			{
				return false;
			}
			
			return true;
		}
		return true;
	}

	public function confirm($emailChecksum)
	{
		if($this->request_mailconfirm == $emailChecksum)
		{
			// check we're not gonna double-confirm this
			if($this->request_status == REQUEST_STATUS_NEW)
			{
				
				$this->request_status == REQUEST_STATUS_OPEN;
				
				// TODO: add a log entry to the new entries queue
			}
			
			// TODO: return success code - correct code
			// should report success even if it did nothing
		}
		else
		{
			// TODO: return error code - wrong hash
		}
	}
	
	/**
	 * @todo bind return values of statement to the correct fields
	 * @todo add log entry
	 * @todo sort out return values, defining values as appropriate
	 * @param string $checksum The checksum of the request
	 * @return mixed true for success, numeric for error
	 */
	public function reserve($checksum)
	{
		global $accDatabase;
		$accDatabase->beginTransaction();
		
		// check reserved status
		$statement = $accDatabase->prepare("SELECT request_reserved, request_checksum FROM acc_request WHERE request_id = :request_id");
		$statement->bindParam(":request_id", $this->request_id);
		
		// TODO: bind other columns to 
		
		if($this->request_reserved != 0)
		{
			if($this->request_reserved == WebRequest::getCurrentUser()->getId())
			{
				// looks like this user already has this request reserved
				// return suitable error message
			}
			else
			{
				// looks like someone else already has this request reserved
				// return suitable error message
			}
		}
		
		// reserve
		$this->request_reserved = WebRequest::getCurrentUser()->getId();
		
		// add log entry
		
		
		
		// save
		if($this->save($checksum))
		{
			$accDatabase->commit();
			return true; //TODO: define as a constant
		}
		else
		{
			$accDatabase->rollBack();
			return; // something failed. return suitable error code
		}
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