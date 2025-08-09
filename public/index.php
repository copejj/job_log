<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Jeff\Code\Page\Index;

Index::display();