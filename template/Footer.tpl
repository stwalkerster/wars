		</div>
		<br class="clearall" />
		<div id="menu">
			<ul>
			{foreach key=key item=item from=$menu}
				<li id="{$key}"><a href="{#baselink}/{$item[link]}">{$item[text]}</a></li>
			{/foreach}
			</ul>
		</div>
		<br class="clearall" />
		<div id="footer">
			Copyright &copy; 2010 Simon Walker
		</div>
	</div> 
</body> 
</html>