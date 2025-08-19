<?php
namespace Jeff\Code\View\Elements\Format;

use Jeff\Code\Model\Record;

abstract class AddButton extends ActionButton
{
	protected static function getAction(): string
	{
		return 'new';
	}
}
