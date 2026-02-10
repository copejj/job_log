<?php
namespace Jeff\Code\Controller\Server;

use Jeff\Code\Util\D;
use Jeff\Code\View\HeaderedContent;

class Info extends HeaderedContent
{
	protected function getTitle(): string
	{
		return "Server Info";
	}

	protected function content(): void
	{
		$load = sys_getloadavg();
		$health_status = ($load[0] < 2.0) ? "Healthy" : "High Load"; // Threshold depends on your CPU cores

		$free_space = disk_free_space("/");
		$total_space = disk_total_space("/");
		$disk_percent = round((($total_space - $free_space) / $total_space) * 100, 2);

		$version = exec('/home/logs/git symbolic-ref --short -q HEAD || git describe --tags --exact-match', $output, $result_code);

		// 1. Get the current branch
		$branch = trim(shell_exec('git rev-parse --abbrev-ref HEAD 2>&1'));

		// 2. Get the current tag (suppress error if no tag exists)
		$tag = trim(shell_exec('git describe --tags --exact-match 2>/dev/null') ?? '');
		?>
		<h3>Git Branch/Version</h3>
		<ul>
			<li>Version: <?=$tag?></li>
			<li>Branch: <?=$branch?></li>
		</ul>
		<h3>Server Status</h3>
		<ul>
			<li>Status: <?=$health_status?></li>
			<li>Load: <?=$load[0]?></li>
			<li>Disk Usage: <?=$disk_percent?>%</li>
		</ul>
		<?php
	}
}