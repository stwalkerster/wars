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

function error_handler($error_level, $error_message, $error_file = "", 
							$error_line  = 0, $error_context = array())
{
	throw new WarsException($error_message);
}

set_error_handler("error_handler");

function exception_handler($exception)
{
	$smarty = new Smarty();
	global $s_templateDir, $s_compileDir, $s_configDir, $s_cacheDir, $s_configFile;
	
	$smarty->template_dir = $s_templateDir;
	$smarty->compile_dir = $s_compileDir;
	$smarty->config_dir = $s_configDir;
	$smarty->cache_dir = $s_cacheDir;

	$smarty->config_load("wars.config","global");
	$smarty->assign('showHeaderInfo',0);
	$smarty->assign('stylesheet', "cmelbye.css");
	$smarty->assign('menu', array());
	
	$smarty->assign('headertitle', "Wikipeda Account Request System");
	
	if(strstr($_SERVER['SCRIPT_NAME'], "internal.php"))
	{
		$smarty->assign('subpage', 'page/InternalFatal.tpl');
		$smarty->assign('pagetitle', 'Whoops!');
	}	
	else
	{
		$smarty->assign('subpage', 'page/Fatal.tpl');
		$smarty->assign('pagetitle', 'Our apologies!');
	}
	
	$smarty->display('Page.tpl');
}
set_exception_handler("exception_handler");