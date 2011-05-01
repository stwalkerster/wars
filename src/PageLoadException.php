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

class PageLoadException extends WarsException
{
	public function __construct($message = null, $code = 0, Exception $previous = null)
	{
		$msg = $message == null ? "The requested page has a definition, but could not be loaded." : $message;
		parent::__construct($msg, $code);
	}
}