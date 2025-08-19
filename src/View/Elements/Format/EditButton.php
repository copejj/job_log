<?php
namespace Jeff\Code\View\Elements\Format;

abstract class EditButton extends ActionButton
{
	protected static function getAction(): string
	{
		return 'edit';
	}
}
