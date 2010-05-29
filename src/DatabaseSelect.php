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

/**
 * Database connection class
 * 
 * @author Simon Walker
 *
 */
class DatabaseSelect
{	
	var $fields = array();
	
	var $from = '';
	
	private $joins = array();
	function addJoin($type, $table, $colA, $colB)
	{
		$joins[] = array(
			'type' => $type,
			'table' => $table,
			'colA' => $colA,
			'colB' => $colB
		);
		
	}
	
	private $wheres = array();
	function addWhere($column, $operator, $value)
	{
		$wheres[] = array(
			'column' => $column,
			'operator' => $operator,
			'value' => $value
		);
	}
	
	var $groups = array();
	
	/**
	 * column => direction
	 * @var associative array
	 */
	var $orders = array();
	
	var $limit = 0;
	
	function toString()
	{
		$query = 'SELECT ';
	
		$first = true;
		foreach ($this->fields as $f) {
			if(!$first)
				$query .= ', ';
				
			$query .= mysql_escape_string($f);
			
			$first = false;
		}
		
		$query .= ' FROM ' . mysql_escape_string($this->from) . ' ';
		
		foreach ($this->joins as $j) {
			$query .=  mysql_escape_string($j['type']) . ' JOIN ' . mysql_escape_string($j['table']);
			$query .=  ' ON ' . mysql_escape_string($j['colA']) . ' = ' . mysql_escape_string($j['colB']);
		}
		
		$first = true;
		foreach ($this->wheres as $w) {
			if(!$first)
				$query .= ' AND';
				
			$query .= ' WHERE ' . mysql_escape_string($w['column']) . ' ' . mysql_escape_string($w['operator']);
			$query .= ' "' . mysql_escape_string($w['value']) . '"';
			
			$first = false;
		}
		
		$first = true;
		foreach ($this->groups as $g) {
			if(!$first)
				$query .= ' GROUP BY ';
			else
				$query .= ', ';
				
			$query .= mysql_escape_string($g);
			
			$first = false;
		}
		
		$first = true;
		foreach ($this->orders as $c => $o) {
			if(!$first)
				$query .= ' ORDER BY ';
			else
				$query .= ', ';
				
			$query .= mysql_escape_string($c) .' '. mysql_escape_string($o);
			
			$first = false;
		}
		
		if($this->limit > 0)
			$query .= ' LIMIT ' . mysql_escape_string($this->limit);
			
		$query .= ';';
		
		return $query;
	}
}