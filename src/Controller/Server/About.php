<?php
namespace Jeff\Code\Controller\Server;

use Jeff\Code\View\HeaderedContent;

class About extends HeaderedContent
{
	protected function getTitle(): string
	{
		return "About";
	}

	protected function content(): void
	{
		$load = sys_getloadavg();
		$health_status = ($load[0] < 2.0) ? "Healthy" : "High Load"; // Threshold depends on your CPU cores

		$free_space = disk_free_space("/");
		$total_space = disk_total_space("/");
		$disk_percent = round((($total_space - $free_space) / $total_space) * 100, 2);

		$version = exec('git rev-parse --short HEAD');

		?>
		Server:
		<ul>
			<li>Status: <?=$health_status?></li>
			<li>Load: <?=$load[0]?></li>
			<li>Disk Usage: <?=$disk_percent?>%</li>
			<li>Version: <?=$version?>
		</ul>
		<?php
	}
}