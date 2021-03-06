<p>This page allows you to manage the users of this system.</p>

{if count($user_new) neq 0}
<div id="usermgmt-newusers">
<h2>New Users</h2>
<ol>
{foreach item=user from=$user_new name="users"}
	{include file='tooluser-short.tpl' 
		username=$user->getUsername()
		useronwikiname=$user->getOnwikiName()
		useruserid=$user->getId()
	}
{/foreach}
</ol>
</div>
{/if}

<div id="usermgmt-users">
<h2>Users</h2>
{if count($user_user) neq 0}
<ol>
{foreach item=user from=$user_user name="users"}
	{include file='tooluser-short.tpl' 
		username=$user->getUsername()
		useronwikiname=$user->getOnwikiName()
		useruserid=$user->getId()
	}
{/foreach}
</ol>
{/if}
</div>

<div id="usermgmt-admins">
<h2>Admins</h2>
{if count($user_admin) neq 0}
<ol>
{foreach item=user from=$user_admin name="users"}
	{include file='tooluser-short.tpl' 
		username=$user->getUsername()
		useronwikiname=$user->getOnwikiName()
		useruserid=$user->getId()
	}
{/foreach}
</ol>
{/if}
</div>

<div id="usermgmt-checkusers">
<h2>Checkusers</h2>
{if count($user_checkuser) neq 0}
<ol>
{foreach item=user from=$user_checkuser name="users"}
	{include file='tooluser-short.tpl' 
		username=$user->getUsername()
		useronwikiname=$user->getOnwikiName()
		useruserid=$user->getId()
	}
{/foreach}
</ol>
{/if}
</div>

<div id="usermgmt-suspended">
<h2>Suspended Users</h2>
{if count($user_suspended) neq 0}
<ol>
{foreach item=user from=$user_suspended name="users"}
	{include file='tooluser-short.tpl' 
		username=$user->getUsername()
		useronwikiname=$user->getOnwikiName()
		useruserid=$user->getId()
	}
{/foreach}
</ol>
{/if}
</div>

<div id="usermgmt-declined">
<h2>Declined Users</h2>
{if count($user_declined) neq 0}
<ol>
{foreach item=user from=$user_declined name="users"}
	{include file='tooluser-short.tpl' 
		username=$user->getUsername()
		useronwikiname=$user->getOnwikiName()
		useruserid=$user->getId()
	}
{/foreach}
</ol>
{/if}
</div>