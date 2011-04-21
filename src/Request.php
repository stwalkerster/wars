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
define("REQUEST_STATUS_CHECKUSER", "Checkuser");
/**
 * @todo check the possible statuses in the database
 * @var unknown_type
 */
define("REQUEST_STATUS_FLAGGEDUSER", "Flagged User");
define("REQUEST_STATUS_CLOSED", "Closed");

define("REQUEST_PRIVACY_EMAIL", "email@currently.hidden");
define("REQUEST_PRIVACY_IP", "127.0.0.1");

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

	/**
	 * 
	 * Confirms the email address of the request, and sets the request to the open state
	 * @todo add log entry to transaction
	 * @param string $emailChecksum checksum passed via email
	 * @return true if the confirmation was successful
	 */
	public function confirm($emailChecksum)
	{
		if($this->request_mailconfirm == $emailChecksum)
		{
			// check we're not gonna double-confirm this
			if($this->request_status == REQUEST_STATUS_NEW)
			{
				global $accDatabase;
								
				$this->request_status == REQUEST_STATUS_OPEN;
				
				$accDatabase->beginTransaction();
				
				// TODO: add a log entry to the new entries queue
				
				// override the checksum requirement - this is an external edit
				$this->save($this->request_checksum);
			}
			
			return true;
			// should report success even if it did nothing
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Reserves a request to the current user
	 * 
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
				
				$accDatabase->rollBack();
				
				// return suitable error message
				return;
			}
			else
			{
				// looks like someone else already has this request reserved
				
				$accDatabase->rollBack();
				
				// return suitable error message
				return;
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
	
	/**
	 * Unreserves a request if it's reserved by the current user, or an admin. 
	 * If an admin attempts to unreserve a request that's not theirs, it will 
	 * either become unreserved, or reserved to the current user
	 * 
	 * @todo set up sane return values for errors
	 * @todo set up logging
	 * @param unknown_type $checksum
	 * @return muxed true for success, numeric for errors
	 */
	public function unreserve($checksum)
	{
		global $accDatabase;
		
		$accDatabase->beginTransaction();
		
		// check it's actually reserved
		if($this->request_reserved == 0)
			return; // TODO: set sane return codes up
		
		// check it's this user who has got it reserved
		if($this->request_reserved == WebRequest::getCurrentUser()->getId())
		{
			// unreserve
			$this->request_reserved = 0;
			
			// TODO: add log entry
			
			// save
			if($this->save($checksum))
			{
				$accDatabase->commit();
				return true;
			}
			else
			{
				$accDatabase->rollBack();
				return; //TODO: return code stuff
			}
		}
		else
		{	// not the user who's got it reserved, could be an admin breaking the reservation		
			if(WebRequest::getCurrentUser()->isAdmin())
			{
				// admin requesting to break the reservation, unreserve it and re-reserve to current user
				global $adminsStealReservations;
				$this->request_reserved = $adminsStealReservations ? WebRequest::getCurrentUser()->getId() : 0;
				
				// add break log entry
				
				if($adminsStealReservations)
				{
					// add reserve log entry
				}
				
				if($this->save())
				{
					$accDatabase->commit();
					return true;
				}
				else
				{
					$accDatabase->rollBack();
					return; // set up return value
				}
			}
		}
	}
	
	/**
	 * Returns the request ID
	 * @return int
	 */
	public function getId()
	{
		return $this->request_id;	
	}
	
	/**
	 * Returns the request email, if the current user is allowed it
	 * @return string
	 */
	public function getEmail()
	{
		if($this->allowPrivateDataRelease())
		{
			return $this->request_email;
		}
		else
		{
			return REQUEST_PRIVACY_EMAIL;
		}
	}
	
	/**
	 * Returns the request IP address, if the current user is allowed it
	 * @return string
	 */
	public function getIp()
	{
		if($this->allowPrivateDataRelease())
		{
			return $this->request_ip;
		}
		else
		{
			return REQUEST_PRIVACY_IP;
		}
	}

	/**
	 * Returns the requested name of this request
	 * @return string
	 */
	public function getName()
	{
		return $this->request_name;
	}
	
	/**
	 * Returns the comment left by the requestor
	 * @return string
	 */
	public function getComment()
	{
		return $this->request_cmt;
	}
	
	/**
	 * Returns the current status of the request
	 * @return unknown_type
	 */
	public function getStatus()
	{
		return $this->request_status;
	}
	
	/**
	 * Returns the date the current request was submitted
	 * @return timestamp
	 */
	public function getDate()
	{
		return $this->request_date;
	}
	
	/**
	 * Returns the current request checksum
	 * 
	 * DO NOT PASS THIS DIRECTLY BACK TO THIS OBJECT.
	 * 
	 * It should be sent to the user, and passed in with the user's next request.
	 * This is to help prevent "edit conflicts"
	 * 
	 * @return string
	 */
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
	
	/**
	 * If the current user is a checkuser, returns the useragent of the browser the request was submitted from
	 * @return mixed useragent string, or false if the user isn't a checkuser
	 */
	public function getUseragent()
	{
		if(WebRequest::getCurrentUser()->isCheckuser())
		{
			return $this->request_useragent;
		}
		else
		{
			return false;
		}
	}

	/**
	 * @todo implement
	 * @param unknown_type $target
	 * @param unknown_type $checksum
	 * @return unknown_type
	 */
	public function defer($target, $checksum)
	{
		//TODO
	}
	/**
	 * @todo implement
	 * @param unknown_type $emailId
	 * @param unknown_type $checksum
	 * @return unknown_type
	 */
	public function close($emailId, $checksum)
	{
		//TODO
	}
	
	/**
	 * @todo implement
	 * @param unknown_type $created
	 * @param unknown_type $message
	 * @param unknown_type $checksum
	 * @return unknown_type
	 */
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
		switch($this->request_status)
		{
			case REQUEST_STATUS_CHECKUSER:
			case REQUEST_STATUS_FLAGGEDUSER:
			case REQUEST_STATUS_OPEN:
				return true;
			default:
				return false;
		}
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
	
	/**
	 * Checks to see if the current user is able to see the private data associated with this request
	 * 
	 * @return boolean
	 * @todo implement
	 */
	private function allowPrivateDataRelease()
	{
		
		
	}
}