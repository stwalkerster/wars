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

class RequestPage extends PageBase
{
	function __construct()
	{
		$this->subtitle = "Request an account on the English Wikipedia";
		OutputPage::getInstance()->title = "Account Creation Assistance";
	}
	
	function runPage()
	{
		global $databases;
		$out = OutputPage::getInstance();
		
		$out->wrap('Welcome!', 'h2');
		$out->output('We need a few bits of information to create your account. The first is a username, and secondly, a ');
		$out->wrap('valid email address that we can send your password to', 'strong');
		$out->output('. If you want to leave any comments, feel free to do so. Note that if you use this form, your IP address will be recorded, and displayed to ');
		$out->anchor('userlist.php','those who review account requests');
		$out->output('. When you are done, click the "Submit" button. If you have difficulty using this tool, send an email containing your account request (but not password) to ');
		$out->anchor('mailto:accounts-enwiki-l@lists.wikimedia.org', 'accounts-enwiki-l@lists.wikimedia.org');
		$out->output(', and we will try to deal with your requests that way.');
		
		$out->tagStart('span', array('class' => 'reallyImportant'));
		$out->output('WE DO NOT HAVE ACCESS TO EXISTING ACCOUNT DATA. If you have lost your password, please reset it using ');
		$out->anchor('http://en.wikipedia.org/wiki/Special:UserLogin', 'this form');
		$out->output('at Wikipedia. If you are trying to \'take over\' an account that already exists, please use ');
		$out->anchor('http://en.wikipedia.org/wiki/WP:CHU/U', '"Changing usernames/Usurpation"');
		$out->output(' at wikipedia.org. We cannot do either of these things for you.');
		$out->tagEnd();
	}
	
	/**
	 * Override the standard menu.
	 * 
	 * @see PageBase::standardMenu
	 */
	function standardMenu()
	{
		$linkBase = WebRequest::getScriptName();
		
		$pages = array(
				'Wikipedia' => $linkBase . '/Forward?link=http://en.wikipedia.org/'
		);
		
		$this->showMenu('menu', $pages);
	}
}