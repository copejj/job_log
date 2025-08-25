<?php
namespace Jeff\Code\View\Elements\Format;

abstract class AddAction extends Action
{
	protected static function getAction(): string
	{
		return 'new';
	}
}
