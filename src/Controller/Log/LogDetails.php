<?php
namespace Jeff\Code\Controller\Log;

use Jeff\Code\View\HeadlessContent;

class LogDetails extends HeadlessContent
{
	protected function getTitle(): string
	{
		return 'Log Details';
	}

	protected function content(): void
	{
		\Jeff\Code\Util\D::p('request', ['get' => $this->get, 'post' => $this->post]);
	}
}