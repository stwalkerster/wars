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

	var $title = "Wikipeda Account Request System";

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
			'text' => 'Documentation',
	),
	);

	/**
	 * Page-specific code, performing the logic required on a page-specific level.
	 */
	abstract function runPage();

	/**
	 * Actually runs the page.
	 */
	function execute()
	{
		$content = $this->runPage();

		$smarty = new Smarty();

		global $baseScriptPath;
		$smarty->template_dir = $baseScriptPath . '/template/';
		$smarty->compile_dir = $baseScriptPath . '/smartycompile/';
		$smarty->config_dir = $baseScriptPath . '/smartyconfig/';
		$smarty->cache_dir = $baseScriptPath . '/smartycache/';

		$smarty->assign('content', $content);
		$smarty->assign('menu', $this->menu);
		$smarty->assign('headertitle', $this->title);
		$smarty->assign('pagetitle', $this->subtitle);

		$smarty->display('Page.tpl');
	}

	/**
	 * Create a specific page, based on the Request URL
	 */
	static function create()
	{
		global $baseScriptPath;

		// calculate the name that the page definition should be at.
		$pageName = "Page" . WebRequest::getPageName();

		//if(WebRequest::sessionOrBlank("dbPassword") == '')
		//	$pageName = "PageLogin";

		// check the page definition actually exists...
		if(file_exists( $baseScriptPath . 'src/page/' . $pageName . ".php"))
		{	// ... and include it. If I'd not checked it existed, all code from this point on would fail.
			require_once( $baseScriptPath . 'src/page/' . $pageName . ".php");
		}
		else
		{
			// page definition doesn't exist, let's continue but showing the main page instead.
			$pageName = "PageMain";
			require_once( $baseScriptPath . 'src/page/' . $pageName . ".php");
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
