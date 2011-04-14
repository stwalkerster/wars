<p>Welcome!</p>
<p>We need a few bits of information to create your account.</p>
<p>The first is a username, and secondly, a <strong>valid email address that we can send your password to</strong>. If you want to leave any comments, feel free to do so.</p>
<p>Note that if you use this form, your IP address will be recorded, and displayed to <a href="{$smarty.config.publicbaselink}userlist.php">those who review account requests</a>.</p>
<p>When you are done, click the "Submit" button. If you have difficulty using this tool, send an email containing your account request (but not password) to {mailto address=$smarty.config.toolemail}, and we will try to deal with your requests that way.</p>
<form action="{$smarty.config.publicbaselink}index.php" method="post"> 
	<div class="required"> 
		<label for="name">Desired Username</label> 
		<input type="text" name="name" id="name" size="30" /> 
		Case sensitive, first letter is always capitalized.
	</div> 
	<div class="required"> 
		<label for="email">Your E-mail Address</label> 
		<input type="text" name="email" id="email" size="30" /> 
		<label for="emailconfirm">Confirm your E-mail Address</label> 
		<input type="text" name="emailconfirm" id="emailconfirm" size="30" /> 
		We will send a random password to this email address, so make sure it's correct!
	</div> 
	<div class="optional"> 
		<label for="comments">Comments (optional)</label> 
		<textarea id="comments" name="comments" rows="4" cols="40"></textarea> 
	</div> 
	<div class="forminfo">Please <span class="semiimportant">check all the information</span> supplied above is correct, and that you have specified a <span class="semiimportant">valid email address</span>, then click the submit button below.</div> 
	<div class="submit"> 
		<input type="submit" value="Submit" name="submit" /> 
	</div> 
</form> 