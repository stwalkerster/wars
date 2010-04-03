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

/**
 * Database connection class
 * 
 * @author Simon Walker
 *
 */
class Database
{

	/**
	 * @var MySQL link resource
	 */
	private $link;
	
	/**
	 * Constructor. Creates the instance of the class using the global coniguration data
	 */
	public function __construct()
	{
		// use the global values of these variables.
		global $db_host, $db_user, $db_name;

		// connect to mysql, and select the database
		$this->link = mysql_connect($db_host, $db_user, Request::sessionOrBlank('dbPassword'));
		mysql_select_db($db_name, $this->link);
	}
	
	/**
	 * Retrieves a resultset from a query in array form
	 * 
	 * @param string $query The query to execute
	 * @param unknown_type $result_type The type of the resultset to return (associative, indexed, or both)
	 * @return array The result set in array form.
	 */
	public function getResultSet($query, $result_type = MYSQL_BOTH)
	{
		// run the query
		// TODO: add security to this function, as this is a major SQL injection problem,
		//       especially since all database security is left to this class.
		$result = mysql_query($query, $this->link) or die(mysql_error() . "<br />" . debug_print_backtrace());
		
		// initialise a new array
		$set = array();
		
		// iterate through the resultset, adding each row to an array
		while($row = mysql_fetch_array($result, $result_type))
			$set[] = $row;
			
		// return the array
		return $set;
	}
	
	/**
	 * Inserts a row into a table
	 * 
	 * @param string $table The table to insert into
	 * @param array $values The array of values, in order.
	 * @return number Last insert ID
	 */
	public function insert($table, $values)
	{
		// Build the start of the query, applying escaping to the table name to prevent SQL injection attacks.
		// Bear in mind this statement assumes an auto_increment primary key in the first column.
		$query = 'INSERT INTO ' . mysql_escape_string($table) . ' VALUES ( null';
		
		// iterate through the values to insert
		foreach ($values as $val) {
			
			// is the value null, if so, it's null.
			if($val == null)
				$query .= ', null';
			else
				// add new data item to the query securely (to keep him and Mr Database safe!),
				// escaping any naughty characters to stop SQL injections, cos they hurt.
				$query .= ', "' . mysql_escape_string($val) . '"';
		}
		
		// finish off the query
		$query .= ');';
		
		// am I allowed to write to the database according to the config file?
		global $readOnlyDb;
		if(! $readOnlyDb )
			// yep, execute the query as normal, unless there's a problem, in which case tell me, and tell me where.
			mysql_query($query, $this->link) or die(mysql_error() . "<br />" . debug_print_backtrace() );
		else
			// nope, tell me what the query should've been.
			echo $query;
			
		// what was the last insert ID? return to the calling function.
		return mysql_insert_id($this->link);
	}
	
	/**
	 * Update a row in the database
	 * @param string $table Table to update
	 * @param array $values associative array of columns and new values 
	 * @param array $where associative array of equal items
	 * @param numeric $limit maximum number of rows to affect
	 * @return null
	 */
	public function update($table, $values, $where, $limit = 1)
	{
		// build the initial query.
		$query = "UPDATE " . mysql_escape_string($table) . " SET ";
		
		// iterate through columns to be changed.
		foreach ($values as $column => $value) {
			// add securely each clause
			$query .= mysql_escape_string($column) . '= "' . mysql_escape_string($value) . '",';
		}
		// trim extra commas from the end. 
		$query = rtrim($query, ',');
		
		$query .= ' WHERE ';
		
		// iterate through the where clause, adding securely again
		foreach ($where as $column => $value) {
			$query .= mysql_escape_string($column) . '= "' . mysql_escape_string($value) . '",';
		}
		
		// again, trim commas
		$query = rtrim($query, ',');
		
		// add limit clause (securely)
		$query .= " LIMIT " . mysql_escape_string($limit);
		
		$query .= ';';
		
		// am I allowed to write to the database according to the config file?
		global $readOnlyDb;
		if(! $readOnlyDb )
			// yep, execute the query as normal, unless there's a problem, in which case tell me, and tell me where.
			mysql_query($query, $this->link) or die(mysql_error() . "<br />" . debug_print_backtrace() );
		else
			// nope, tell me what the query should've been.
			echo $query;
	}
}
