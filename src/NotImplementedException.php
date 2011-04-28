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

class NotImplementedException extends WarsException
{
	public function __construct($message = null, $code = 0, Exception $previous = null)
	{
		$msg = $message == null ? "The method or function is not implemented." : $message;
		parent::__construct($msg, $code);
	}
}