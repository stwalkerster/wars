<h2>Welcome!</h2> 
<p>We need a few bits of information to create your account. 
However, you do not need an account to read the encyclopedia or look up information - that can be done by anyone with or without an account. 

The first is a username, and secondly, a <strong>valid email address that we can send your password to</strong> (please don't use temporary inboxes, or email aliasing, as this may cause your request to be rejected). 
If you want to leave any comments, feel free to do so. Note that if you use this form, your IP address will be recorded, and displayed to <a href="{$smarty.config.publicbaselink}userlist.php">those who review account requests</a>. 
When you are done, click the "Submit" button. If you have difficulty using this tool, send an email containing your account request (but not password) to <a href="mailto:{$smarty.config.toolemail}">{$smarty.config.toolemail}</a>, and we will try to deal with your requests that way.
</p>


<span class="reallyReallyImportant">Please Note: We do not have access to existing account data. If you have lost your password, please reset it using <a href="http://en.wikipedia.org/wiki/Special:UserLogin">this form</a> at wikipedia.org. If you are trying to 'take over' an account that already exists, please use <a href="http://en.wikipedia.org/wiki/WP:CHU/U">"Changing usernames/Usurpation"</a> at wikipedia.org. We cannot do either of these things for you.</span>
 
			<form action="{$smarty.config.publicbaselink}index.php" method="post"> 
				<div class="required"> 
					<label for="name">Desired Username</label> 
 
					<input type="text" name="name" id="name" size="30" /> 
					Case sensitive, first letter is always capitalized, you do not need to use all uppercase.  Note that this need not be your real name. Please make sure you don't leave any trailing spaces or underscores on your requested username.
				</div> 
				<div class="required"> 
					<label for="email">Your E-mail Address</label> 
					<input type="text" name="email" id="email" size="30" /> 
				<label for="email">Confirm your E-mail Address</label> 
					<input type="text" name="emailconfirm" id="emailconfirm" size="30" /> 
					We need this to send you your password. Without it, you will not receive your password, and will be unable to log in to your account.
				</div> 
				<div class="optional"> 
 
					<label for="comments">Comments (optional)</label> 
					<textarea id="comments" name="comments" rows="4" cols="40"></textarea> 
					Please do <strong>NOT</strong> ask for a specific password. One will be randomly created for you.
				</div> 
				<div class="forminfo">Please <span class="semiimportant">check all the information</span> supplied above is correct, and that you have specified a <span class="semiimportant">valid email address</span>, then click the submit button below.</div> 
				<div class="submit"> 
					<input type="submit" value="Submit" /> 
					<input type="reset" value="Reset" /> 
 
				</div> 
			</form> 