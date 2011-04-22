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
		if(!isset($_SERVER['PATH_INFO']))
			return 'Main';
			
		$pathInfo = $_SERVER['PATH_INFO'];
		$page = trim($pathInfo ,'/');
		if($page == "")
			return "Main";
			
		$pa = explode('/', $page);
			
		return $pa[0];
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
		return $_GET[$name];
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
	
	public static function postString($name)
	{
		return $_POST[$name];
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
	
	public static function postBool($name)
	{
		if(isset($_POST[$name]))
		{	
			$result = ($_POST[$name] == "on" ? 1 : 0);
			return $result;
		}
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
		header("Location: $baseFilePath/internal.php/$page");
		die();
	}
	
	public static function redirectUrl($url)
	{
		header("Location: $url");
		die();
	}
	
	/**
	 * @todo check the name of this session variable, probably convert the name to a defined constant
	 * @return unknown_type
	 */
	public static function getCurrentUser()
	{
		if(isset($_SESSION['currUser']))
			return unserialize($_SESSION['currentUser']);
		else
			return false;
	}
	
	public static function getSubpages()
	{
		if(isset($_SERVER['PATH_INFO']))
		{
			return explode('/',trim($_SERVER['PATH_INFO'],'/'));
		}
		else
		{
			return array('Main');
		}
	}
	
	public static function isConsoleSession()
	{
		return ! isset($_SERVER['REQUEST_METHOD']);
	}
}
