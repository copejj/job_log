<?php
namespace Jeff\Code\View\Elements\Format;

abstract class ViewButton extends ActionButton
{
	protected static function getAction(): string
	{
		return 'view';
	}
}
