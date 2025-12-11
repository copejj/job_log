<?php
namespace Jeff\Code\Util;

class IPCheck {
    public function runScript($arguments = "") {
		$cmd = dirname(__DIR__, 2) . "/bin/ip_query.sh " . escapeshellarg($arguments);
		return shell_exec($cmd);
    }
}