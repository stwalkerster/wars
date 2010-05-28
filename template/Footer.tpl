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
					Not logged in
				{else}
					Logged in as <a href="{$smarty.config.baselink}/Statistics/Users/{$username}">{$username}</a>&nbsp;|&nbsp;<a href="{$smarty.config.baselink}/Logout">Logout</a>
				{/if}
			</div>
		{/if}
		<br class="clearall" />
		<div id="footer">
			Copyright &copy; 2010 Simon Walker
		</div>
	</div> 
</body> 
</html>