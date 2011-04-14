<?php
ini_set('display_errors',1);
$data=array();

if(isset($_POST['payload']))
	exec("git pull",$data);

print_r($data);

echo exec("git show | head -n1");
