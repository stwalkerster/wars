<?
if (isset($_SERVER['REQUEST_METHOD'])) {
	die();
} // Web clients die.
define("WARS",1);
require_once 'config.inc.php';

$strictMode = 0;

function entryFromString($line) {
	// Function adapted from MediaWiki's source code. Original function can be found at:
	// http://svn.wikimedia.org/svnroot/mediawiki/trunk/extensions/TitleBlacklist/TitleBlacklist.list.php
	$options = array();
	$line = preg_replace ("/^\\s*([^#]*)\\s*((.*)?)$/", "\\1", $line);
	$line = trim ($line);
	preg_match('/^(.*?)(\s*<([^<>]*)>)?$/', $line, $pockets);
	@list($full, $regex, $null, $opts_str) = $pockets;
	$regex = trim($regex);
	$regex = str_replace('_', ' ', $regex);
	$opts_str = trim($opts_str);
	$opts = preg_split('/\s*\|\s*/', $opts_str);
	$casesensitive = false;
	foreach ($opts as $opt) {
		$opt2 = strtolower($opt);
		if ($opt2 == 'moveonly') {
			return null;
		}
		if ($opt2 == 'casesensitive') {
			$casesensitive = true;
		}
	}
	if ($regex) {
		return array($regex, $casesensitive);
	} else {
		return null;
	}
}

$queryresult = unserialize(HttpClient::getInstance()->get("http://en.wikipedia.org/w/api.php?action=query&format=php&prop=revisions&titles=MediaWiki:Titleblacklist&rvprop=content"));
$queryresult = current($queryresult['query']['pages']);

$text = $queryresult['revisions'][0]['*'];
$lines = preg_split("/\r?\n/", $text);
$result = array();
foreach ($lines as $line) {
	$line = entryFromString($line);
	if ($line) {
		$entries[] = $line;
	}
}

$sanitycheck=array();


if($accDatabase->beginTransaction())
{
	$success1 = $accDatabase->exec("DELETE FROM `acc_titleblacklist`;");
	if($success1 === FALSE )
	{
		print_r($accDatabase->errorInfo());
		$accDatabase->rollBack();
		die;
	}
		
	$statement = $accDatabase->prepare("INSERT INTO `acc_titleblacklist` (`titleblacklist_regex`, `titleblacklist_casesensitive`) VALUES ( ? , ? );");
	foreach ($entries as $entry) {
		list($rregex, $casesensitive) = $entry;
		
		$regex = $accDatabase->quote($rregex);
		
		if(array_key_exists($regex, $sanitycheck))
			continue;
			
		$sanitycheck[$regex]=1;
		
		if ($casesensitive)
			$rquery = 'TRUE';
		else
			$rquery = 'FALSE';
		
		$statement->bindParam(1, $rregex);
		$statement->bindParam(2, $rquery);
		
		try 
		{
			$success2 = $statement->execute();
			if($success2 === FALSE )
			{
				print_r($statement->errorInfo());
				if($strictMode == 1)
				{
					$accDatabase->rollBack();
					echo "Error in transaction.\n";
					die;
				}
			}
		} 
		catch (PDOException $e)
		{
				print_r($statement->errorInfo());
				if($strictMode == 1)
				{
					$accDatabase->rollBack();
					echo "Error in transaction.\n";
					die;
				}
		}
	}
	
	$accDatabase->commit();
	echo "The title blacklist table has been recreated.\n";
}
else
	echo "Error starting transaction.\n";
