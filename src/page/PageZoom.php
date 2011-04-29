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
		
		
	}
}