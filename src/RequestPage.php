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
		$out = OutputPage::getInstance();
		$out->title = "Account Creation Assistance";
	}
	
	function runPage()
	{
		global $databases;
		$out = OutputPage::getInstance();
		
		$out->title = "Account Creation Assistance";
		
		$out->wrap('Welcome!', 'h2');
		$out->output('We need a few bits of information to create your account. The first is a username, and secondly, a ');
		$out->wrap('valid email address that we can send your password to', 'strong');
		$out->output('. If you want to leave any comments, feel free to do so. Note that if you use this form, your IP address will be recorded, and displayed to ');
		$out->anchor('userlist.php','those who review account requests');
		$out->output('. When you are done, click the "Submit" button. If you have difficulty using this tool, send an email containing your account request (but not password) to ');
		$out->anchor('mailto:accounts-enwiki-l@lists.wikimedia.org', 'accounts-enwiki-l@lists.wikimedia.org');
		$out->output(', and we will try to deal with your requests that way.');
		
		$out->tagStart('span', array('class' => 'reallyImportant'));
		$out->output(' WE DO NOT HAVE ACCESS TO EXISTING ACCOUNT DATA. If you have lost your password, please reset it using ');
		$out->anchor('http://en.wikipedia.org/wiki/Special:UserLogin', 'this form');
		$out->output(' at wikipedia.org. If you are trying to \'take over\' an account that already exists, please use ');
		$out->anchor('http://en.wikipedia.org/wiki/WP:CHU/U', '"Changing usernames/Usurpation"');
		$out->output(' at wikipedia.org. We cannot do either of these things for you.');
		$out->tagEnd();
		
		$out->tagStart('form', array('action' => '', 'method' => 'post'));
		
		$out->tagStart('div', array('class' => 'required'));
		$out->wrap('Desired Username', 'label', array('for' => 'name'));
		$out->tag('input', array('name' => 'name', 'id' => 'name', 'type' => 'text', 'size' => 30));
		$out->output('Case sensitive, first letter is always capitalized, you do not need to use all uppercase.  Note that this need not be your real name. Please make sure you don\'t leave any trailing spaces or underscores on your requested username.');
		$out->tagEnd();
		
		$out->tagStart('div', array('class' => 'required'));
		$out->wrap('Your E-mail Address', 'label', array('for' => 'email'));
		$out->tag('input', array('name' => 'email', 'id' => 'email', 'type' => 'text', 'size' => 30));
		$out->wrap('Confirm your E-mail Address', 'label', array('for' => 'confirmemail'));
		$out->tag('input', array('name' => 'confirmemail', 'id' => 'confirmemail', 'type' => 'text', 'size' => 30));
		$out->output('We need this to send you your password. Without it, you will not receive your password, and will be unable to log in to your account.');
		$out->tagEnd();
		
		$out->tagStart('div', array('class' => 'optional'));
		$out->wrap('Comments (optional)', 'label', array('for' => 'comments'));
		$out->tagStart('textarea', array('name' => 'comments', 'id' => 'comments', 'rows' => 4, 'cols' => 40	));
		$out->forceWriteOut();
		$out->tagEnd();
		$out->output('Please do ');
		$out->wrap('NOT', 'strong');
		$out->output(' ask for a specific password. One will be randomly created for you.');
		$out->tagEnd();
		
		$out->tagStart('div', array('class' => 'forminfo'));
		$out->output('Please ');
		$out->wrap('check all the information', 'span', array('class' => 'semiimportant'));
		$out->output(' supplied above is correct, and that you have specified a ');
		$out->wrap('valid email address', 'span', array('class' => 'semiimportant'));
		$out->output(', then click the submit button below.');
		$out->tagEnd();
		
		$out->tagStart('div', array('class' => 'submit'));
		$out->tag('input', array('type' => 'submit', 'value' => 'Submit'));
		$out->tag('input', array('type' => 'reset', 'value' => 'Reset'));
		$out->tagEnd();
		
		$out->tagEnd();
	}
	
	/**
	 * Override the standard menu.
	 * 
	 * @see PageBase::standardMenu
	 */
	public function standardMenu()
	{
		$linkBase = WebRequest::getScriptName();
		
		$pages = array(
				'Wikipedia' => $linkBase . 'Forward?link=http://en.wikipedia.org/',
				'Wikipedia2' => $linkBase . 'Forward?link=http://en.wikipedia.org/',
				'Wikipedia3' => $linkBase . 'Forward?link=http://en.wikipedia.org/'
		);
		
		$this->showMenu('menu', $pages);
	}
}