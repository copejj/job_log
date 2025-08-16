<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Jeff\Code\Decider;
use Jeff\Code\Util\Config;

if (Config::get('ENVIRONMENT') !== 'prod')
{
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$content = Decider::getContent($_GET, $_POST);
$content->display();
