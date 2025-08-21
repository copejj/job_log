<?php
namespace Jeff\Code\View\Elements\Format;

use Jeff\Code\View\Display\Attributes;

abstract class ViewButton extends ActionButton
{
	protected static function getAction(): string
	{
		return 'view';
	}
}
