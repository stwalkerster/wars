			<form action="{$smarty.config.baselink}/Login" method="post"> 
				<div class="required"> 
					<label for="username">Username:</label> 
					<input id="username" type="text" name="username"/> 
				</div> 
				<div class="required"> 
					<label for="password">Password:</label> 
					<input id="password" type="password" name="password"/> 
				</div>
				<div class="submit"> 
					<input type="submit" value="Login"/> 
				</div> 
			</form> 
			<p>Need Tool access?<br /><a href="{$smarty.config.baselink}/Login/Register">Register!</a> (Requires approval).</p>
			<p><a href="{$smarty.config.baselink}/Login/Forgot">Forgot your password?</a></p>