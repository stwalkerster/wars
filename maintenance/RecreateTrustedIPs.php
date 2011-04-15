<?php
if (isset($_SERVER['REQUEST_METHOD'])) {
	die();
} // Web clients die.
define("WARS",1);
require_once 'config.inc.php';

$htmlfile = HttpClient::getInstance()->get('http://www.wikimedia.org/trusted-xff.html');
$matchfound = preg_match_all('/(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)/', $htmlfile, $matches, PREG_SET_ORDER);
if (!$matchfound)
	die('ERROR: No IPs found on trusted XFF page.');

$ip=array();
	
foreach ($matches as $match) {
	$ip[] = $match[0];
}

$ip=array_unique($ip);

$sqlquery = 'INSERT INTO `acc_trustedips` (`trustedips_ipaddr`) VALUES ';
foreach ($ip as $i) {
	$sqlquery .= "('$i'), ";
}
$sqlquery = substr($sqlquery, 0, -2) . ';';

if($accDatabase->beginTransaction())
{
	
	$success1 = $accDatabase->exec("DELETE FROM `acc_trustedips`;");
	if($success1 === FALSE )
	{
		print_r($accDatabase->errorInfo());
		$accDatabase->rollBack();
		die;
	}

	$success2 = $accDatabase->exec($sqlquery);
	if($success2 === FALSE )
	{
		print_r($accDatabase->errorInfo());
		$accDatabase->rollBack();
		die;
	}

	$accDatabase->commit();
	echo "Trusted IPs table has been recreated.\n";
}
else
	echo "Error starting transaction.\n";
