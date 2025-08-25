<?php
namespace Jeff\Code\View\Elements\Format;

abstract class ViewAction extends Action
{
	protected static function getAction(): string
	{
		return 'view';
	}
}
