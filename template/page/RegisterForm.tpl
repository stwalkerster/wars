<span style="font-weight:bold;color:red;font-size:20px;">This form is for requesting tool access. If you want to request an account for Wikipedia, then go to <a href="http://toolserver.org/~acc">http://toolserver.org/~acc</a></span>	<form action="acc.php?action=sreg" method="post"> 
    <table cellpadding="1" cellspacing="0" border="0"> 
            <tr> 
                <td>Desired Username:</td> 
                <td><input type="text" name="name"></td> 
            </tr> 
            <tr> 
                <td>E-mail Address:</td> 
                <td><input type="text" name="email"></td> 
            </tr> 
            <tr> 
                <td>Wikipedia username:</td> 
                <td><input type="text" name="wname"></td> 
            </tr> 
            <tr> 
                <td>Desired password (<strong>PLEASE DO NOT USE THE SAME PASSWORD AS ON WIKIPEDIA.</strong>):</td> 
                <td><input type="password" name="pass"></td> 
            </tr> 
            <tr> 
                <td>Desired password(again):</td> 
                <td><input type="password" name="pass2"></td> 
            </tr> 
            <tr> 
                <td>Enable use of the secure server:</td> 
                <td><input type="checkbox" name="secureenable"></td> 
            </tr> 
            <tr> 
                <td>Enable <a href="http://en.wikipedia.org/wiki/User:SQLBot-Hello">SQLBot-Hello</a> welcoming of the users I create:</td> 
                <td><input type="checkbox" name="welcomeenable"></td> 
            </tr> 
            <tr> 
                <td>Your signature (wikicode)<br /><i>This would be the same as ~~~ on-wiki. No date, please.  Not needed if you left the checkbox above unchecked.</i></td> 
                <td><input type="text" name="sig" size ="40"></td> 
            </tr> 
            <tr> 
                <td>Template you would like the bot to welcome with?<br /><i>If you'd like more templates added, please contact <a href="http://en.wikipedia.org/wiki/User_talk:SQL">SQL</a>, <a href="http://en.wikipedia.org/wiki/User_talk:Cobi">Cobi</a>, or <a href="http://en.wikipedia.org/wiki/User_talk:FastLizard4">FastLizard4</a>.</i>  Not needed if you left the checkbox above unchecked.</td> 
                <td> 
                	<select name="template" size="0"> 
                		<option value="welcome">{{welcome|user}} ~~~~</option> 
                		<option value="welcomeg">{{welcomeg|user}} ~~~~</option> 
                		<option value="welcome-personal">{{welcome-personal|user}} ~~~~</option> 
                		<option value="werdan7">{{User:Werdan7/W}} ~~~~</option> 
                		<option value="welcomemenu">{{WelcomeMenu|sig=~~~~}}</option> 
                		<option value="welcomeicon">{{WelcomeIcon}} ~~~~</option> 
                		<option value="welcomeshout">{{WelcomeShout|user}} ~~~~</option> 
                		<option value="welcomesmall">{{WelcomeSmall|user}} ~~~~</option> 
                		<option value="hopes">{{Hopes Welcome}} ~~~~</option> 
	                	<option value="welcomeshort">{{Welcomeshort|user}} ~~~~</option> 
						<option value="w-riana">{{User:Riana/Welcome|name=user|sig=~~~~}}</option> 
						<option value="w-screen">{{w-screen|sig=~~~~}}</option> 
						<option value="wodup">{{User:WODUP/Welcome}} ~~~~</option> 
						<option value="williamh">{{User:WilliamH/Welcome|user}} ~~~~</option> 
						<option value="malinaccier">{{User:Malinaccier/Welcome|~~~~}}</option> 
						<option value="laquatique">{{User:L'Aquatique/welcome}} ~~~~</option> 
						<option value="coffee">{{User:Coffee/welcome|user|||~~~~}}</option> 
						<option value="matt-t">{{User:Matt.T/C}} ~~~~</option> 
						<option value="roux">{{User:Roux/W}} ~~~~</option> 
						<option value="staffwaterboy">{{User:Staffwaterboy/Welcome}} ~~~~</option> 
						<option value="maedin">{{User:Maedin/Welcome}} ~~~~</option> 
						<option value="chzz">{{User:Chzz/botwelcome|name=user|sig=~~~~}}</option> 
						<option value="phantomsteve">{{User:Phantomsteve/bot welcome}} ~~~~</option> 
					</select> 
				</td> 
            </tr> 
			<tr> 
				<td><b>I have read and understand the <a href="http://en.wikipedia.org/wiki/Wikipedia:Request_an_account/Guide">interface guidelines.</a><b></td> 
				<td><input type="checkbox" name="guidelines"></td> 
            <tr> 
                <td></td> 
                <td><input type="submit"><input type="reset"></td> 
            </tr> 
    </table> 
    </form><br /><br /> 