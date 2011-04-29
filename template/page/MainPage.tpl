<h2>Open Requests</h2>
{if count($req_open) neq 0}
	<table cellspacing="0">
	{foreach item=request from=$req_open name="requests"}
		{assign var="reservinguser" value=$request->getReserved()}
		{if isset($reservinguser)}
			{assign var="reservingusername" value=$reservinguser->getUsername()}
			{assign var="isreserved" value="yes"}
		{else}
			{assign var="reservingusername" value=""}
			{assign var="isreserved" value="no"}
		{/if}
		{include file='requestList.tpl' 
			requestip=$request->getIp()
			requestid=$request->getId()
			requestname=$request->getName()
			requestemail=$request->getEmail()
			requestnumber=$smarty.foreach.requests.iteration
			reservedusername=$reservingusername
		}
	{/foreach}
	</table>
{else}
	<i>No requests at this time</i>
{/if}
<h2>Flagged user needed</h2>
{if count($req_flagged) neq 0}
	<table cellspacing="0">
	{foreach item=request from=$req_flagged name="requests"}
		{assign var="reservinguser" value=$request->getReserved()}
		{if isset($reservinguser)}
			{assign var="reservingusername" value=$reservinguser->getUsername()}
			{assign var="isreserved" value="yes"}
		{else}
			{assign var="reservingusername" value=""}
			{assign var="isreserved" value="no"}
		{/if}
		{include file='requestList.tpl' 
			requestip=$request->getIp()
			requestid=$request->getId()
			requestname=$request->getName()
			requestemail=$request->getEmail()
			requestnumber=$smarty.foreach.requests.iteration
			reservedusername=$reservingusername
		}
	{/foreach}
	</table>
{else}
	<i>No requests at this time</i>
{/if}
<h2>Checkuser needed</h2>
{if count($req_checkuser) neq 0}
	<table cellspacing="0">
	{foreach item=request from=$req_checkuser name="requests"}
		{assign var="reservinguser" value=$request->getReserved()}
		{if isset($reservinguser)}
			{assign var="reservingusername" value=$reservinguser->getUsername()}
			{assign var="isreserved" value="yes"}
		{else}
			{assign var="reservingusername" value=""}
			{assign var="isreserved" value="no"}
		{/if}
		{include file='requestList.tpl' 
			requestip=$request->getIp()
			requestid=$request->getId()
			requestname=$request->getName()
			requestemail=$request->getEmail()
			requestnumber=$smarty.foreach.requests.iteration
			reservedusername=$reservingusername
		}
	{/foreach}
	</table>
{else}
	<i>No requests at this time</i>
{/if}
<hr />
<h2>Last 5 closed Requests</h2>