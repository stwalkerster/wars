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

class Database extends PDO
{
	protected $hasActiveTransaction = false;

	/**
	 * Determines if this connection has a transaction in progress or not
	 * @return true if there is a transaction in progress.
	 */
	public function hasActiveTransaction()
	{
		return $this->hasActiveTransaction;
	}
	
	public function beginTransaction () {
		if ( $this->hasActiveTransaction ) {
			return false;
		} else {
			$this->exec("SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;");
			$this->hasActiveTransaction = parent::beginTransaction();
			return $this->hasActiveTransaction;
		}
	}

	public function commit () {
		parent::commit ();
		$this->hasActiveTransaction = false;
	}

	public function rollBack () {
		parent::rollback ();
		$this->hasActiveTransaction = false;
	}
}