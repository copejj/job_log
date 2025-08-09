<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Jeff\Code\Config;
use Jeff\Code\D;
use Jeff\Code\DB;

$sql = 
	"SELECT *
	from actions";
$actions = DB::getInstance()->fetchAll($sql);
D::p('actions', $actions);

$sql = 
	"SELECT *
	from methods";
$methods = DB::getInstance()->fetchAll($sql);
D::p('methods', $methods);
?>
Hello, PHP World.
