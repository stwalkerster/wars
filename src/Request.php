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
 */
define("REQUEST_STATUS_FLAGGEDUSER", "Admin");
define("REQUEST_STATUS_CLOSED", "Closed");

define("REQUEST_PRIVACY_EMAIL", "email@currently.hidden");
define("REQUEST_PRIVACY_IP", "127.0.0.1");

define("REQUEST_COLUMN_ID", "request_id");
define("REQUEST_COLUMN_EMAIL", "request_email");
define("REQUEST_COLUMN_IP", "request_ip");
define("REQUEST_COLUMN_NAME", "request_name");
define("REQUEST_COLUMN_CMT", "request_cmt");
define("REQUEST_COLUMN_STATUS", "request_status");
define("REQUEST_COLUMN_DATE", "request_date");
define("REQUEST_COLUMN_CHECKSUM", "request_checksum");
define("REQUEST_COLUMN_EMAILSENT", "request_emailsent");
define("REQUEST_COLUMN_MAILCONFIRM", "request_mailconfirm");
define("REQUEST_COLUMN_RESERVED", "request_reserved");
define("REQUEST_COLUMN_USERAGENT", "request_useragent");
define("REQUEST_COLUMN_PROXYIP", "request_proxyip");

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

	public function __construct()
	{

	}

	/**
	 * Creates a new request
	 * @param string $name The requested name
	 * @param string $email The email address of the requestor
	 * @param string $comment The comment left by the requestor
	 * @todo set the proxy IP if available
	 */
	public static function create($name, $email, $comment = "")
	{
		// get globals needed
		global $defaultReserver;

		$instance = new self();

		// it's new!
		$instance->new = true;

		// new object, hasn't been assigned an id yet.
		$instance->request_id = false;

		$instance->request_email = $email;
		$instance->request_ip = $_SERVER['REMOTE_ADDR'];
		$instance->request_name = $name;
		$instance->request_cmt = $comment;
		$instance->request_status = "New";
		$instance->request_date = date("Y-m-d H:i:s");
		$instance->request_checksum = WARS_REQUEST_DEFAULT_CHECKSUM;
		$instance->request_emailsent = '';
		$instance->request_mailconfirm = '';
		$instance->request_reserved = $defaultReserver;
		$instance->request_useragent = $_SERVER['HTTP_USER_AGENT'];
		$instance->request_proxyip = '';

		return $instance;
	}

	public static function getById($id)
	{
		$me = "Request";
		global $accDatabase;
		$statement = $accDatabase->prepare("SELECT * FROM acc_".strtolower($me)." WHERE ".strtolower($me)."_id = :oid LIMIT 1;");
		$statement->bindParam(":oid",$id);
		$statement->execute();
		return $statement->fetchObject($me);
	}
	
	private $new;

	private $request_id, $request_email, $request_ip, $request_name,
	$request_cmt, $request_status, $request_date, $request_checksum,
	$request_emailsent, $request_mailconfirm, $request_reserved,
	$request_useragent, $request_proxyip;

	/**
	 * Saves the current state of the object to the database.
	 *
	 * This is not transaction-based, all calls to it must be wrapped inside a transaction
	 *
	 * @return true on success, false on failure
	 */
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
			{
				return false;
			}
				
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
		throw new NotImplementedException();
	}
	/**
	 * @todo implement
	 * @param unknown_type $emailId
	 * @param unknown_type $checksum
	 * @return unknown_type
	 */
	public function close($emailId, $checksum)
	{
		throw new NotImplementedException();
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
		throw new NotImplementedException();
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
		{
			return true;
		}
		if($this->getReserved() == WebRequest::getCurrentUser()->getId())
		{
			return true;
		}
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
		throw new NotImplementedException();
	}

	/**
	 * Static methods to allow data retrieval
	 */

	/**
	 * Method to retrieve a list of requests which match the specified conditions
	 * @param array $whereconds Associative array of column name constant to column value
	 * @return array List of requests which match the conditions
	 */
	public static function query($whereconds)
	{
		// build the basic statement
		$sql = "SELECT * FROM acc_request";

		$statement = null;

		global $accDatabase;

		// add where conditions
		if(empty($whereconds))
		{
			$sql.=";";
			$statement = $accDatabase->prepare($sql);
		}
		else
		{
			$sql.=" WHERE";

			$vals=array();

			$first =true;

			// iterate through, setting columns up with parameters
			foreach($whereconds as $col => $val)
			{
				switch($col)
				{
					case REQUEST_COLUMN_ID:
						if($first)
						{
							$first = false;
						}
						else
						{
							$sql.= " AND";
						}
						$sql.=" ".REQUEST_COLUMN_ID." = ?";
						break;
					case REQUEST_COLUMN_EMAIL:
						if($first)
						{
							$first = false;
						}
						else
						{
							$sql.= " AND";
						}
						$sql.=" ".REQUEST_COLUMN_EMAIL." = ?";
						break;
					case REQUEST_COLUMN_IP:
						if($first)
						{
							$first = false;
						}
						else
						{
							$sql.= " AND";
						}
						$sql.=" ".REQUEST_COLUMN_IP." = ?";
						break;
					case REQUEST_COLUMN_NAME:
						if($first)
						{
							$first = false;
						}
						else
						{
							$sql.= " AND";
						}
						$sql.=" ".REQUEST_COLUMN_NAME." = ?";
						break;
					case REQUEST_COLUMN_CMT:
						if($first)
						{
							$first = false;
						}
						else
						{
							$sql.= " AND";
						}
						$sql.=" ".REQUEST_COLUMN_CMT." = ?";
						break;
					case REQUEST_COLUMN_STATUS:
						if($first)
						{
							$first = false;
						}
						else
						{
							$sql.= " AND";
						}
						$sql.=" ".REQUEST_COLUMN_STATUS." = ?";
						break;
					case REQUEST_COLUMN_DATE:
						if($first)
						{
							$first = false;
						}
						else
						{
							$sql.= " AND";
						}
						$sql.=" ".REQUEST_COLUMN_DATE." = ?";
						break;
					case REQUEST_COLUMN_CHECKSUM:
						if($first)
						{
							$first = false;
						}
						else
						{
							$sql.= " AND";
						}
						$sql.=" ".REQUEST_COLUMN_CHECKSUM." = ?";
						break;
					case REQUEST_COLUMN_EMAILSENT:
						if($first)
						{
							$first = false;
						}
						else
						{
							$sql.= " AND";
						}
						$sql.=" ".REQUEST_COLUMN_EMAILSENT." = ?";
						break;
					case REQUEST_COLUMN_MAILCONFIRM:
						if($first)
						{
							$first = false;
						}
						else
						{
							$sql.= " AND";
						}
						$sql.=" ".REQUEST_COLUMN_MAILCONFIRM." = ?";
						break;
					case REQUEST_COLUMN_RESERVED:
						if($first)
						{
							$first = false;
						}
						else
						{
							$sql.= " AND";
						}
						$sql.=" ".REQUEST_COLUMN_RESERVED." = ?";
						break;
					case REQUEST_COLUMN_USERAGENT:
						if($first)
						{
							$first = false;
						}
						else
						{
							$sql.= " AND";
						}
						$sql.=" ".REQUEST_COLUMN_USERAGENT." = ?";
						break;
					case REQUEST_COLUMN_PROXYIP:
						if($first)
						{
							$first = false;
						}
						else
						{
							$sql.= " AND";
						}
						$sql.=" ".REQUEST_COLUMN_PROXYIP." = ?";
						break;
					default: // not a column!
						throw new WarsException("Unknown column!");
				}

				$vals[] = $val;
			}
			$statement = $accDatabase->prepare($sql);

			$i=1;
		}

		//retrieve resultset

		$statement->execute($vals);

		return $statement->fetchAll(PDO::FETCH_CLASS, "Request");
	}
}