<?php
define("WARS", 1);
require_once 'config.inc.php';

$olddatabase = "p_acc_test";

require_once $baseMaintenancePath . 'convert.inc';

query("DROP TABLE $olddatabase.acc_log;");
query("CREATE TABLE $olddatabase.acc_log LIKE $olddatabase.log_bu;");
query("INSERT INTO $olddatabase.acc_log SELECT * FROM $olddatabase.log_bu;");
out("done");