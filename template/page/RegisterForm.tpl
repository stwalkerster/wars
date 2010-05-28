			<span class="reallyReallyImportant">This form is for requesting tool access. If you want to request an account for Wikipedia, then go to <a href="{$smarty.config.publicbaselink}">{$smarty.config.publicbaselink}</a></span>
			<form action="{$smarty.config.baselink}/Register" method="post"> 
			<div class="required">
				<label for="name">Desired username</label>
				This is the username you will use to log in to the tool.
				<input type="text" name="name" id="name" /> 
			</div>
			<div class="required">
				<label for="email">E-mail Address:</label> 
				This is the email address used for password resets.
				<input type="text" name="email" id="email"/>
			</div>
			<div class="required">
				<label for="wname">Wikipedia username:</label> 
				<input type="text" name="wname" id="wname"/>
			</div>
			<div class="required">
				<label for="pass">Desired password:</label>
				<strong>PLEASE DO NOT USE THE SAME PASSWORD AS ON WIKIPEDIA.</strong>
				<input type="password" name="pass" id="pass"/>
			</div>
			<div class="required">
				<label for="pass2">Confirm desired password:</label>
				This is to make sure you didn't make a mistake. 
				<input type="password" name="pass2" id="pass2"/>
			</div>
			<div class="optional">
				<input type="checkbox" name="secureenable" id="secureenable">Enable use of the secure server</input> 
			</div>
			<div class="optional">
				<input type="checkbox" name="welcomeenable" id="welcomeenable">Enable <a href="http://en.wikipedia.org/wiki/User:SQLBot-Hello">SQLBot-Hello</a> welcoming of the users I create</input>
			</div>
			<div class="optional">
				<label for="sig">Your signature (wikicode)<br /><i>This would be the same as ~~~ on-wiki. No date, please.  Not needed if you left the checkbox above unchecked.</i></label> 
				<input type="text" name="sig" size="40" id="sig" />
			</div>
			<div class="optional">
				<label for="template">Template you would like the bot to welcome with?<br /><i>If you'd like more templates added, please contact <a href="http://en.wikipedia.org/wiki/User_talk:SQL">SQL</a>, <a href="http://en.wikipedia.org/wiki/User_talk:Cobi">Cobi</a>, or <a href="http://en.wikipedia.org/wiki/User_talk:FastLizard4">FastLizard4</a>.</i>  Not needed if you left the checkbox above unchecked.</label>
				{html_options options=$welcometemplates name="template" id="template"}
			</div>
			<div class="required">
				<input type="checkbox" name="guidelines" id="guidelines">I have read and understand the <a href="http://en.wikipedia.org/wiki/Wikipedia:Request_an_account/Guide">interface guidelines.</a></input>
						</div>
			<div class="required">
				<input type="submit" /><input type="reset" />
			</div>
			</form>