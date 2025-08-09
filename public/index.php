<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Jeff\Code\Config;
use Jeff\Code\D;

D::p('config', Config::getAll());

?>
Hello, PHP World.
