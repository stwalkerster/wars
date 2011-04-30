<h2>Details for Request #{$requestid}:</h2>
<ul>
<li><strong>Username:</strong> {$requestname}<br />{include file="userlinks.tpl" ipaddress=$requestname}</li>
<li><strong>Email address:</strong>: <a href="mailto:{$requestemail}">{$requestemail}</a></li>
<li><strong>IP address: </strong>{$requestip}<br />{include file="iplinks.tpl" ipaddress=$requestip}</li>
<li><strong>Proxy IP address:</strong> {$requestproxyip}<br />{include file="iplinks.tpl" ipaddress=$requestproxyip}</li>
<li><strong>Date request made:</strong> {$requestdate}</li>
<li><strong>Requester comment</strong>:<pre>{$requestcmt}</pre></li>
{if $useragentallowed}
<li><strong>User agent:</strong><pre>{$requestuseragent}</pre></li>
{/if}
<li><strong>Ban:</strong> <a href="{$smarty.config.baselink}/Ban?ip={$requestip}">IP</a> | 
			<a href="{$smarty.config.baselink}/Ban?email={$requestemail}">E-Mail</a> | 
			<a href="{$smarty.config.baselink}/Ban?name={$requestname}">Name</a></li>
</ul>

<h2>Possibly conflicting usernames</h2> 
<i>None detected</i>

<h2>Other requests from {$requestip}:</h2> 
<i>None.</i> 

<h2>Other requests from {$requestemail}:</h2> 
<i>None.</i> 

<h2>Logs for this request:<small> (<a href='{$smarty.config.baselink}/Comment?id={$requestid}'>new comment</a>)</small></h2>

<table>
</table>
	
<form action='{$smarty.config.baselink}/Comment' method='post'>
	<input type='hidden' name='id' value='{$requestid}' />
	<input type='text' name='comment' size='75' />
	<input type='hidden' name='visibility' value='user' />
	<input type='submit' value='Quick Reply' />
</form>

<h2>Actions:</h2>

<ul>
<li><strong>Reservation: </strong>{if $isreserved eq "yes"}
{if $reservedusername eq $username}
You have reserved this request | <a href="{$smarty.config.baselink}/Unreserve?id=45">Unreserve</a>
{else}
{$reservedusername} has reserved this request | <a href="{$smarty.config.baselink}/Unreserve?id={$requestid}">Force break reservation</a>
{/if}
{else}
This request is not reserved | <a href="{$smarty.config.baselink}/Reserve?id=45">Reserve</a>
{/if}</li>
<li><strong>Defer: </strong><a href="{$smarty.config.baselink}/Defer?id=45&amp;sum={$checksum}&amp;target=admins">Flagged Users</a> | 
							<a href="{$smarty.config.baselink}/Defer?id=45&amp;sum={$checksum}&amp;target=cu">Checkusers</a></li>
<li><strong>Create: </strong><a href="Forward?link=http://en.wikipedia.org/w/index.php?title=Special:UserLogin/signup&amp;wpName={$requestname}&amp;wpEmail={$requestemail}&amp;uselang=en-acc&amp;wpReason=Requested+account+at+%5B%5BWP%3AACC%5D%5D%2C+request+%2345" target="_blank">Create!</a></li>
<li><strong>Close: </strong><a href="{$smarty.config.baselink}/Close?id=45&amp;email=1&amp;sum={$checksum}"><strong>Created!</strong></a> | 
							<a href="{$smarty.config.baselink}/Close?id=45&amp;email=2&amp;sum={$checksum}">Similar</a> | 
							<a href="{$smarty.config.baselink}/Close?id=45&amp;email=3&amp;sum={$checksum}">Taken</a> | 
							<a href="{$smarty.config.baselink}/Close?id=45&amp;email=26&amp;sum={$checksum}">SUL Taken</a> | 
							<a href="{$smarty.config.baselink}/Close?id=45&amp;email=4&amp;sum={$checksum}">UPolicy</a> | 
							<a href="{$smarty.config.baselink}/Close?id=45&amp;email=5&amp;sum={$checksum}">Invalid</a> | 
							<a href="{$smarty.config.baselink}/Close?id=45&amp;email=30&amp;sum={$checksum}">Password Reset</a> | 
							<a href="{$smarty.config.baselink}/Close?id=45&amp;email=custom&amp;sum={$checksum}">Custom</a> | 
							<a href="{$smarty.config.baselink}/Close?id=45&amp;email=0&amp;sum={$checksum}">Drop</a></li>
</ul>