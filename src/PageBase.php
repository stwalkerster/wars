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
	
	/**
	 * Write the standard page header
	 */
	private function standardHeader()
	{
		$out = OutputPage::getInstance();
		
		$out->title = "Wikipedia Internal Account Creation System";
		
		$out->tagStart("div", array("id" => "header"));
		
		$out->tagStart('div', array('id' => 'header-title'));
		$out->output($out->title);
		$out->tagEnd();
		
		$out->tagEnd();
		
		$out->tagStart("div", array('id'=>'content'));
		
		$out->tagStart('div', array('id' => 'pagetitle'));
		$out->wrap($this->subtitle, 'h2');
		$out->tagEnd();
	}
	
	/**
	 * Write the standard page footer
	 */
	private function standardFooter()
	{
		$out = OutputPage::getInstance();
		
		$out->tagEnd(); // end div#content
		$out->tag('br', array('class' => 'clearall'));
		
		// print the menu after the page content, adjust with CSS
		$this->standardMenu();
		
		$out->tag('br', array('class' => 'clearall'));
		
		$out->tagStart('div', array('id'=>'footer'));
		$out->outputHtml("Copyright &copy; 2010 Simon Walker");
		$out->tagEnd();
	}
	
	/**
	 * Write the standard menu
	 */
	public function standardMenu()
	{
		$linkBase = WebRequest::getScriptName();
		
		$pages = array(
				'Home' => $linkBase,
				'Logs' => $linkBase . '/Logs',
				'Users' => $linkBase . '/Users',
				'Bans' => $linkBase . '/Bans',
				'Messages' => $linkBase . '/Messages',
				'Search' => $linkBase . '/Search',
				'Statistics' => $linkBase . '/Stats',
				'Preferences' => $linkBase . '/Preferences',
				'Documentation' => $linkBase . '/Forward?link=http://en.wikipedia.org/wiki/Wikipedia:Request_an_account/Guide'
		);
		
		$this->showMenu('menu', $pages);
	}
	
	/**
	 * Write a menu
	 * 
	 * @param string $menuName
	 * @param array $pages
	 */
	public function showMenu($menuName, $pages)
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

		
		$this->runPage();
		
		
		
		
		$this->standardFooter();
		$out->sendPage();
	}
	
	/**
	 * Create a specific page, based on the Request URL
	 */
	static function create()
	{
		// calculate the name that the page definition should be at.
		$pageName = "Page" . WebRequest::getPageName();
		
		if(WebRequest::sessionOrBlank("dbPassword") == '')
			$pageName = "PageLogin";
		
		// check the page definition actually exists...
		if(file_exists( 'page/' . $pageName . ".php"))
		{	// ... and include it. If I'd not checked it existed, all code from this point on would fail.
			require_once( 'page/' . $pageName . ".php");
		}
		else
		{
			// page definition doesn't exist, let's continue but showing the main page instead.
			$pageName = "PageMain";
			require_once( 'page/' . $pageName . ".php");
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
