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

class PageZoom extends PageBase
{
	private $request;
	
	function __construct()
	{
		$this->request = Request::getById(WebRequest::getInt("id"));
		$this->subtitle = "Request Zoom";
	}
	
	function runPage()
	{
		if($this->request == null)
		{
			$this->error("NoSuchRequest", true);
			return;
		}
		$this->smarty->assign("requestip", "127.0.0.1");
		$this->smarty->assign("requestproxyip", "127.0.0.2");
		$this->smarty->assign("requestdate", "2011-04-29 13:26:24");
		$this->smarty->assign("requestemail", "wars@helpmebot.org.uk");
		$this->smarty->assign("requestname", "stwalkerster");
		$this->smarty->assign("requestid", "1234");
		$this->smarty->assign("isreserved", "yes");
		$this->smarty->assign("reservedusername", "Stwalkerster");
		$this->smarty->assign("requestuseragent", "WARS/0.1 (+http://toolserver.org/~acc/; +https://github.com/enwikipedia-acc/wars)");
		$this->smarty->assign("requestcmt", "I can haz account nao pls?");
		$this->smarty->assign("checksum", "CHECKSUM");
		$this->smarty->assign("useragentallowed", WebRequest::getCurrentUser()->isCheckuser());
		
		$this->smarty->assign("subpage", "page/ZoomPage.tpl");
		
	}
}