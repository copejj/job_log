<?php
namespace Jeff\Code\Controller\Server;

use Jeff\Code\Util\Info\GitInfo;
use Jeff\Code\View\HeaderedContent;
use Jeff\Code\Util\Info\ServerInfo;

class Info extends HeaderedContent
{
	public function processing(): void
	{
		if (!$_SESSION['is_admin'])
		{
			$this->has_redirect = '/';
		}
	}

	protected function getTitle(): string
	{
		return "Server Info";
	}

	protected function content(): void
	{
		$serverInfo = new ServerInfo();
		$gitInfo = new GitInfo();

		?>
		<ul>
			<h4>Git Branch/Version</h4>
			<li>Branch: <?=$gitInfo->branch?> (<?=$gitInfo->hash?>)</li>
		</ul>
		<ul>
			<h4>Server Status</h4>
			<li>Status: <?=$serverInfo->health_status?></li>
			<li>Load: <?=$serverInfo->server_load?></li>
		</ul>
		<ul>
			<h4>Server Space</h4>
			<li>Total Space: <?=$serverInfo->total_space?></li>
			<li>Free Space: <?=$serverInfo->free_space?></li>
			<li>Disk Usage: <?=$serverInfo->disk_percent?>%</li>
		</ul>
		<?php
	}
}