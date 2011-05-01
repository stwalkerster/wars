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
	
/*
 * This is not a class file, but rather a file containing all the error 
 * handling code
 * 
 * This requires a certain amount of stuff to be working to begin with, 
 * including Smarty
 */

	/**
	 * 
	 * @param $error_level
	 * @param $error_message
	 * @param $error_file
	 * @param $error_line
	 * @param $error_context
	 * @return unknown_type
	 */
function error_handler($error_level, $error_message, $error_file = "", 
							$error_line  = 0, $error_context = array())
{
	throw new ErrorException($error_message, 0, $error_level, $error_file, $error_line);
}



/**
 * 
 * @param unknown_type $exception
 * @return unknown_type
 * @todo sort out display of the exception if required, maybe logging too
 */
function exception_handler(Exception $exception)
{
	ob_end_clean();
	global $baseIncludePath;
	require_once($baseIncludePath."smarty/Smarty.class.php");
	
	$smarty = new Smarty();
	global $s_templateDir, $s_compileDir, $s_configDir, $s_cacheDir, $s_configFile;
	
	$smarty->template_dir = $s_templateDir;
	$smarty->compile_dir = $s_compileDir;
	$smarty->config_dir = $s_configDir;
	$smarty->cache_dir = $s_cacheDir;

	$smarty->config_load("wars.config","global");
	$smarty->assign('showHeaderInfo',0);
	$smarty->assign('stylesheet', "cmelbye.css");
		
	$smarty->assign('headertitle', "Wikipedia Account Request System");
	
	if(strstr($_SERVER['SCRIPT_NAME'], "internal.php"))
	{
		$smarty->assign('subpage', 'page/InternalFatal.tpl');
		$smarty->assign('pagetitle', 'Whoops!');
				$smarty->assign('menu', array(
					'menuIrc' => array(
						'link' => 'Forward?link=irc://irc.freenode.net/#wikipedia-en-accounts',
						'text' => 'IRC',
					),
					'menuMailingList' => array(
						'link' => 'Forward?link=https://lists.wikimedia.org/mailman/listinfo/accounts-enwiki-l',
						'text' => 'Mailing list',
					),
					'menuJira' => array(
						'link' => 'Forward?https://jira.toolserver.org/browse/ACC',
						'text' => 'Bugtracker',
					),
					'menuCandles' => array(
						'link' => 'Forward?http://shop.ebay.com/i.html?_nkw=pillar+candles',
						'text' => 'Candles',
					),
				)
			);
		
		$msg = $exception->getMessage();
		$smarty->assign("offlineType", "error");
		$smarty->assign("offlineReason", "Unhandled Exception" );
		$smarty->assign("offlineTechMsg", $msg);
		$smarty->assign("offlineTechTrace", $exception->getTraceAsString() );
		$smarty->assign("offlineTechType", get_clasS($exception));
	}	
	else
	{
		$smarty->assign('subpage', 'page/Fatal.tpl');
		$smarty->assign('pagetitle', 'Our apologies!');
		$smarty->assign('menu', array(
					'menuWikipedia' => array(
						'link' => 'Forward?link=http://en.wikipedia.org/',
						'text' => 'Go back to Wikipedia',
					),
					'menuMailTeam' => array(
						'link' => 'Forward?link=mailto:accounts-enwiki-l@lists.wikimedia.org',
						'text' => 'Email the Team',
					),
				)
			);
	}
	
	$smarty->display('Page.tpl');
}
if($useErrorHandler)
{
	set_error_handler("error_handler", E_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR | E_COMPILE_ERROR | E_CORE_ERROR );
	set_exception_handler("exception_handler");
	
	// only enable output buffering if we have a remote client (ie: requested via http)
	if(isset($_SERVER['REMOTE_ADDR']))
	{
		ob_start();
	}
}