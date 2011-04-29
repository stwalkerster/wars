<p>After much experimentation, something finally managed to kill the system. So, the tool is currently offline while our resident developers pound their skulls against the furniture.</p> 
<p>Apparently, this is supposed to fix it.</p>
<p>Once the nature of the problem is known, we will insert it here: <b>{$offlineReason}</b></p>
{if $offlineType neq "error"}
<p>Once the identity of the culprit(s) is known, trout should be applied here: <b>{$offlineCulprit}</b></p>
{/if}
<p>Although the tool is dead and the Bot is sleeping, email still works fine. So, we expect a swarm of irate potential editors to bury us in requests shortly. Please keep an eye on the mailing list. Remember to 'cc' or 'bcc' accounts-enwiki-l when you reply to let others know you have replied.</p> 
<p>For more information, <a href="irc://irc.freenode.net/#wikipedia-en-accounts">join IRC</a>, check the mailing list (<a href="https://lists.wikimedia.org/mailman/listinfo/accounts-enwiki-l">sign up if you need to</a>) or just light candles - they may help too.</p>
{if $offlineType eq "error"}
<hr />
<h3>Technical information about this error</h3>
<ul>
  <li><strong>Error:</strong> {$offlineTechType}</li>
  <li><strong>Message:</strong> {$offlineTechMsg}</li>
  <li><strong>Trace:</strong><pre>{$offlineTechTrace}</pre></li>
</ul>
{/if}