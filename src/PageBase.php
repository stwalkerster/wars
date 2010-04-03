<?php

/********************************************************************************************
* Farm Claims Subsidy System																*
* 																							*
* Written by Simon Walker ( 090931545 ) for module F27DB: Introduction to Database Systems	*
* Lecturer: Brian Palmer and Adil Ibrahim													*
* 																							*
* Page superclass definition file															*
*********************************************************************************************/

// check that this code is being called from a valid entry point. 
if(!defined("FARMSYSTEM"))
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
	
	/**
	 * Write the standard page header
	 */
	private function standardHeader()
	{
		$out = OutputPage::getInstance();
		
		$out->title = "Simon's Farm Subsidy Claims System";
		
		$out->tagStart("div", array("id" => "header"));
		$out->tagStart("h1");
		$out->output("Farm Subsidy Claims System");
		$out->tagEnd();
		$out->tagStart("h2");
		$out->output($this->subtitle);
		$out->tagEnd();
		$this->standardMenu();
		$out->tagEnd();
	}
	
	/**
	 * Write the standard page footer
	 */
	private function standardFooter()
	{
		$out = OutputPage::getInstance();
		
		$out->tagStart('div', array('id'=>'footer'));
		$out->outputHtml("Copyright &copy; 2010 Simon Walker");
		$out->tagEnd();
	}
	
	/**
	 * Write the standard menu
	 */
	private function standardMenu()
	{
		$linkBase = Request::getScriptName();
		
		$pages = array(
				'Home' => $linkBase,
				'New' => $linkBase . '/Claim',
				'Add' => $linkBase . '/AddClaim',
				'Find' => $linkBase . '/View',
				'List' => $linkBase . '/List',
				'Logout' => $linkBase . '/SessionDestroy'
		);
		
		$this->showMenu('menu', $pages);
	}
	
	/**
	 * Write a menu
	 * 
	 * @param string $menuName
	 * @param array $pages
	 */
	function showMenu($menuName, $pages)
	{
		$out = OutputPage::getInstance();
		
		$out->tagStart('div', array('id' => $menuName));
		$out->tagStart('ul');
		
		foreach ($pages as $key => $value) {
			$out->tagStart('li', array('id' => $menuName . $key));
			$out->tagStart('a', array('href' => $value));
			$out->output($key);
			$out->tagEnd();
			$out->tagEnd();
		}
		
		$out->tagEnd();
		$out->tagEnd();
	}
	
	/**
	 * Page-specific code, performing the logic required on a page-specific level.
	 */
	abstract function runPage();
	
	/**
	 * Actually runs the page.
	 */
	function execute()
	{
		$out = OutputPage::getInstance();
		$this->standardHeader();
		$out->tagStart("div", array('id'=>'content'));
		
		$this->runPage();
		
		$out->tagStart('br', array('class' => 'clearall'));
		$out->tagEnd();
		
		$out->tagEnd();
		$this->standardFooter();
		$out->sendPage();
	}
	
	/**
	 * Create a specific page, based on the Request URL
	 */
	static function create()
	{
		// calculate the name that the page definition should be at.
		$pageName = "Page" . Request::getPageName();
		
		if(Request::sessionOrBlank("dbPassword") == '')
			$pageName = "PageLogin";
		
		// check the page definition actually exists...
		if(file_exists( $pageName . ".php"))
		{	// ... and include it. If I'd not checked it existed, all code from this point on would fail.
			require_once( $pageName . ".php");
		}
		else
		{
			// page definition doesn't exist, let's continue but showing the main page instead.
			$pageName = "PageMain";
			require_once( $pageName . ".php");
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
