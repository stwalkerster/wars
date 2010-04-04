<?php

/********************************************************************************************
* Farm Claims Subsidy System																*
* 																							*
* Written by Simon Walker ( 090931545 ) for module F27DB: Introduction to Database Systems	*
* Lecturer: Brian Palmer and Adil Ibrahim													*
* 																							*
* Request class: Handles all request data, and accesses to the superglobal variables.		*
*********************************************************************************************/

/**
 * @author Simon Walker
 *
 */
class WebRequest {
	
	/**
	 * Was the request submitted with a POST repsonse?
	 * 
	 * @return bool
	 */
	public static function wasPosted()
	{
		if($_SERVER['REQUEST_METHOD'] == "POST")
			return true;
		else
			return false;
	}
	
	/**
	 * Get the name of the page that has been requested
	 * 
	 * @return string Page name
	 */
	public static function getPageName()
	{
		$pathInfo = $_SERVER['PATH_INFO'];
		$page = trim($pathInfo ,'/');
		if($page == "")
			$page = "Main";
			
		return $page;
	}
	
	/**
	 * Return the name of the script
	 * 
	 * @return string
	 */
	public static function getScriptName()
	{
		return $_SERVER["SCRIPT_NAME"];
	}
	
	/**
	 * Retrieve a URL parameter
	 * 
	 * @param string $name name of the GET parameter to retrieve
	 * @return string
	 */
	public static function getString($name)
	{
		return htmlentities($_GET[$name]);
	}
	
	/**
	 * @param string $name
	 * @return number
	 */
	public static function getInt($name)
	{
		$raw = $_GET[$name];

		if(is_numeric($raw) && (int)$raw == $raw)
			return (int)$raw;
		else
			return 0;	
	}
	
	/**
	 * @param unknown_type $name
	 * @return unknown
	 */
	public static function post($name)
	{
		if(isset($_POST[$name]))
			return $_POST[$name];	
		else
			return null;
	}
	
	/**
	 * @param unknown_type $name
	 * @return number|number
	 */
	public static function postInt($name)
	{
		$raw = $_POST[$name];
		
		if(is_numeric($raw) && (int)$raw == $raw)
			return (int)$raw;
		else
			return 0;	
	}
	
	public static function unsetPost($name = null)
	{
		if($name == null)
			unset($_POST);
		else
			unset($_POST[$name]);
	}

	/**
	 * Save all POST values to the session data store.
	 */
	public static function savePostToSession()
	{
		foreach ($_POST as $key => $val) {
			$_SESSION[htmlentities($key)] = htmlentities($val);
		}	
	}
	
	/**
	 * Return a value from the session data, or return the empty string.
	 * 
	 * @param string $name
	 * @return string|string
	 */
	public static function sessionOrBlank($name)
	{
		if(isset($_SESSION[$name]))
			return $_SESSION[$name];
		else
			return "";
	}
	
	public static function redirect($page)
	{
		global $baseFilePath;
		header("Location: $baseFilePath/index.php/$page");
		die();
	}
}