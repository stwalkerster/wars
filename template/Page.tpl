<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"> 
<head> 
	<title>{$pagetitle} - {$headertitle}</title>	
	<link rel="stylesheet" type="text/css" href="{$smarty.config.stylepath}{$stylesheet}" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
</head> 
<body> 
	<div id="globalwrapper">
		<div id="header">
			<div id="header-title">
				{$headertitle}
			</div>
		</div>
		
		<div id="content">
			<div id="pagetitle">
				<h1>{$pagetitle}</h1>
			</div>
			{if $iserror eq 0}
			{else}
				{include file="Error.tpl"}
			{/if}
			{if $subpage neq ""}
				{include file=$subpage}
			{else}
				{include file="Error.tpl" error="error/UndefinedPage.tpl"}
			{/if}
		</div>
		<br class="clearall" />
		<div id="menu">
			<ul>
				{foreach key=key item=item from=$menu}
					<li id="{$key}"><a href="{$smarty.config.baselink}/{$item.link}">{$item.text}</a></li>
				{/foreach}
			</ul>
		</div>
		{if $showHeaderInfo eq '1'}
			<div id="header-info">
				{if $userid eq 0}
					Not logged in | <a href="{$smarty.config.baselink}/Login">Log in</a>
				{else}
					Logged in as <a href="{$smarty.config.baselink}/Statistics/Users/{$username}">{$username}</a>&nbsp;|&nbsp;<a href="{$smarty.config.baselink}/Logout">Logout</a>
				{/if}
			</div>
		{/if}
		<br class="clearall" />
		<div id="footer">
			Copyright &copy; <a href="https://github.com/enwikipedia-acc">WARS Development Team</a>
		</div>
	</div> 
</body> 
</html>