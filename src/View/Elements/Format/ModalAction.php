<?php
namespace Jeff\Code\View\Elements\Format;

abstract class ModalAction extends Action
{
	protected static function getAction(): string
	{
		return 'modal';
	}
}