<?php
if(isset($_POST['payload']))
	exec("git pull",$data);

print_r($data);