<?php
namespace Jeff\Code\Template;

abstract class Content
{
	protected array $get = [];
	protected array $post = [];
	
	protected function processing(): void { }
	protected function header(): void { }
	protected function top(): void { }
	protected function content(): void {}
	protected function bottom(): void {}
	protected function footer(): void {}

	public function setArgs(array $get, array $post)
	{
		$this->get = $get ?? [];
		$this->post= $post ?? [];
	}

	public function display(): void
	{
		$this->processing();
		$this->header();
		$this->top();
		$this->content();
		$this->bottom();
		$this->footer();
	}
}