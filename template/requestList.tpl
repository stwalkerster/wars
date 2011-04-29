<tr>
	<td><small>{$requestnumber}</small></td>
	<td><small><a class="request-src" href="{$smarty.config.baselink}/Zoom?id={$requestid}">Zoom</a></small></td>
	<td><small>[ </small></td>
	<td><small><a class="request-src" href="mailto:{$requestemail}">{$requestemail}</a></small></td>
	<td><small><span class="request-src"> (0)</span></small></td>
	<td><small> | </small></td>
	<td><small><a class="request-src" name="ip-link" href="{$smarty.config.baselink}/Forward?link=http://en.wikipedia.org/wiki/User_talk:{$requestip}" target="_blank">{$requestip}</a></small></td>
	<td><small><span class="request-src"> (0)</span></small></td>
	<td><small> | </small></td>
	<td><small><a class="request-req" href="{$smarty.config.baselink}/Forward?link=http://en.wikipedia.org/wiki/User:{$requestname}" target="_blank"><strong>{$requestname}</strong></a></small></td>
	<td><small> | </small></td>
{if $isreserved eq "yes"}
	{if $reservedusername eq $username}
		<td><small>Being handled by you - <a href="{$smarty.config.baselink}/Unreserve?id={$requestid}">Break reservation</a></small></td>
	{else}
		<td><small>Being handled by <a href="{$smarty.config.baselink}/Users/{$reservedusername}">{$reservedusername}</a> - <a href="{$smarty.config.baselink}/Unreserve?id={$requestid}">Force break reservation</a></small></td>
	{/if}
{else}
	<td><small><a href="{$smarty.config.baselink}/Reserve?id={$requestid}">Reserve</a></small></td>
{/if}
</tr>