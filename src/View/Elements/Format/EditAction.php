<?php
namespace Jeff\Code\View\Elements\Format;

abstract class EditAction extends Action
{
	protected static function getAction(): string
	{
		return 'edit';
	}
}
