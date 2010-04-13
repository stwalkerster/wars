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
	
class Request implements DataObject
{
	public static function getById($id)
	{
		$this->new = false;
	}
	
	public function __construct($name, $email, $comment)
	{
		// get globals needed
		global $confDefaultReserver;
		
		// it's new!
		$this->new = true;
		
		// new object, hasn't been assigned an id yet.
		$this->id = false;
		
		$this->email = $email;
		$this->ip = $_SERVER['REMOTE_ADDR'];
		$this->name = $name;
		$this->comment = $comment;
		$this->status = "Open";
		$this->date = null; // auto-inserted by database
		$this->checksum = null; // updated in code automatically later
		$this->emailsent = 0;
		$this->mailconfirm = null; //TODO:figure out
		$this->reserved = $confDefaultReserver;
		$this->useragent = $_ENV["HTTP_USER_AGENT"];
	}
	
	// ro = read only
	private $id; // ro
	private $email; // ro
	private $ip; // ro
	private $name; // ro
	private $comment; // ro
	private $status;
	private $date; // ro
	private $checksum;
	private $emailsent;
	private $mailconfirm;
	private $reserved;
	private $useragent; // ro
	
	private $new;
	
	
	public function save()
	{
		global $accDatabase;
		
		if($this->new)
		{ // INSERT
			
			$values = array(
				$this->email,
				$this->ip,
				$this->name,
				$this->comment,
				$this->status,
				$this->date,
				$this->checksum,
				$this->emailsent,
				$this->mailconfirm,
				$this->reserved,
				$this->useragent
			);
			
			$this->id = $accDatabase->insert('acc_pend', $values);
			
			$this->new = false;
			
			$this->save(); // save again to set the checksum.
		}
		else
		{ // UPDATE
			
			// update the checksum
			$this->checksum = md5($this->id . $this->name . $this->email . microtime());
			
			$values = array(
				'pend_status' => $this->status,
				'pend_checksum' => $this->checksum,
				'pend_emailsent' => $this->emailsent,
				'pend_mailconfirm' => $this->mailconfirm,
				'pend_reserved' => $this->reserved
			);
			
			$accDatabase->update('acc_pend', $values, array('pend_id' => $this->id));
		}
	}

	public function setStatus($newStatus)
	{
		$this->status = $newStatus;
	}
	public function setEmailsent();
	public function setMailconfirm();
	
	public function reserve()
	{
		$user = unserialize(WebRequest::sessionOrBlank('currentUser'));
		$this->reserved = $user->getId();
	}
	public function unreserve()
	{
		$this->reserved = 0;
	}
	
	public function getId()
	{
		
	}
	public function getEmail();
	public function getIp();
	public function getName();
	public function getComment();
	public function getStatus();
	public function getDate();
	public function getChecksum();
	public function getEmailsent();
	public function getMailconfirm();
	public function getReserved();
	public function getUseragent();
}