<?php
namespace Jeff\Code;

use Jeff\Code\Util\Web\GoDaddyAPI;

class IpUpdate
{
	public static function getCurrentIp(): ?array
	{
		$godaddy = new GoDaddyAPI();
		$result = $godaddy->query('GET', 'v1/domains/braindribbler.com/records/A');
		//$result = $godaddy->query('GET', 'v1/domains/braindribbler.com/');
		//$result = $godaddy->query('GET', 'v1/domains/braindribbler.com/records');
		
		return $result;
	}
}
