<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Jeff\Code\IpUpdate;

echo print_r(IpUpdate::getCurrentIp(), true).PHP_EOL;