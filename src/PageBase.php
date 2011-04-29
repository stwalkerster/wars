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
 * Base page class
 *
 * @author Simon Walker
 *
 */
abstract class PageBase
{
	/**
	 * @var string subtitle of page
	 */
	var $subtitle;

	var $title = "Wikipedia Account Request System";

	var $pagetemplate = "Page.tpl";
	
	var $menu = array(
		'menuHome' => array(
			'link' => '',
			'text' => 'Account Requests',
	),
		'menuLogs' => array(
			'link' => 'Logs',
			'text' => 'Logs',
	),
		'menuUsers' => array(
			'link' => 'Users',
			'text' => 'Users',
	),
		'menuBans' => array(
			'link' => 'Bans',
			'text' => 'Ban Management',
	),
		'menuMessages' => array(
			'link' => 'Messages',
			'text' => 'Message Management',
	),
		'menuTemplates' => array(
			'link' => 'Templates',
			'text' => 'Template Management',
	),
		'menuUsers' => array(
			'link' => 'UserManagement',
			'text' => 'User Management',
	),
		'menuSearch' => array(
			'link' => 'Search',
			'text' => 'Search',
	),
		'menuStatistics' => array(
			'link' => 'Stats',
			'text' => 'Statistics',
	),
		'menuPreferences' => array(
			'link' => 'Preferences',
			'text' => 'Preferences',
	),
		'menuDocumentation' => array(
			'link' => 'Forward?link=http://en.wikipedia.org/wiki/Wikipedia:Request_an_account/Guide',
			'text' => 'Guide',
	),
		'menuChat' => array(
			'link' => 'Forward?link=http://webchat.freenode.net/?channels=wikipedia-en-accounts',
			'text' => 'Chat',
	),
	);

	var $smarty;
	/**
	 * Page-specific code, performing the logic required on a page-specific level.
	 */
	abstract function runPage();

	/**
	 * Actually runs the page.
	 */
	function execute()
	{
		$this->smarty = new Smarty();

		global $s_templateDir, $s_compileDir, $s_configDir, $s_cacheDir, $s_configFile;
		
		$this->smarty->template_dir = $s_templateDir;
		$this->smarty->compile_dir = $s_compileDir;
		$this->smarty->config_dir = $s_configDir;
		$this->smarty->cache_dir = $s_cacheDir;

		$this->smarty->config_load("wars.config","global");
		
		$curUser = WebRequest::getCurrentUser();
		$curuserid = 0;
		$curusername = "";
		if($curUser != false)
		{
			$curuserid = $curUser->getId();
			$curusername = $curUser->getUsername();
		}
 
		// assign settings that can be overridden in the implemented page
		$this->smarty->assign('showHeaderInfo', 1);
		$this->smarty->assign('stylesheet', "cmelbye.css");
		
		$this->runPage();
		
		// assign settings that depend on a value that can be changed by the implemented page
		$this->smarty->assign('menu', $this->menu);
		$this->smarty->assign('headertitle', $this->title);
		$this->smarty->assign('pagetitle', $this->subtitle);
		
		// assign settings that should never be changed.
		$this->smarty->assign('userid', $curuserid );
		$this->smarty->assign('username', $curusername );
		
		$this->smarty->display($this->pagetemplate);
	}

	/**
	 * Show an error.
	 * @param $errorName Name of the error file.
	 * @return unknown_type
	 */
	function error($errorName, $isfatal =false)
	{
		if($isfatal)
		{
			$this->smarty->assign('subpage', 'Error.tpl');
		}
		else 
		{
			$this->smarty->assign('iserror', '1');
		}
		$this->smarty->assign('error', 'error/'.$errorName.'.tpl');
		
	}
	
	/**
	 * Create a specific page, based on the Request URL
	 */
	static function create()
	{
		global $baseIncludePath;

		// calculate the name that the page definition should be at.
		$pageName = "Page" . WebRequest::getPageName();

		
		if(WebRequest::getCurrentUser() == false)
			$pageName = "PageLogin";

		// check the page definition actually exists...
		if(file_exists( $baseIncludePath .'page/' . $pageName . ".php"))
		{	// ... and include it. If I'd not checked it existed, all code from this point on would fail.
			require_once( $baseIncludePath .'page/' . $pageName . ".php");
		}
		else
		{
			// page definition doesn't exist, let's continue but showing the main page instead.
			$pageName = "PageMain";
			require_once( $baseIncludePath .'page/' . $pageName . ".php");
		}

		// now I've brought the page definition class file into the script, let's actually check that
		// page definition class exists in that file.
		if(class_exists($pageName))
		{
			// create the page object
			$page = new $pageName;
				
			// check the newly created page object has inherits from PageBase class
			if(get_parent_class($page)=="PageBase")
			{
				// return the new page object
				return $page;
			}
			else
			{
				// oops. this is our class, named correctly, but it's a bad definition.
				die();
			}
		}
		else
		{
			// file exists, but no definition of the class
			die();
		}

	}
}
