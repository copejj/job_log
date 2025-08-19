<?php
namespace Jeff\Code\View\Elements\Format;

use Exception;

use Jeff\Code\Model\Record;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\Format\Formatter;

abstract class ActionButton implements Formatter
{
	protected abstract static function getType(): string;
	protected abstract static function getAction(): string;

	protected static function getText(Record $data): string
	{
		return ucwords(static::getAction());
	}

	public static function format(string $key, Record $data): string
	{
		$action = static::getAction();
		if (empty($action))
		{
			throw new Exception('Action button requires an action');
		}

		$type = static::getType();
		if (empty($type))
		{
			throw new Exception('Action button requires a type');
		}

		$id = $data->__get("{$type}_id");
		if (empty($id))
		{
			return '';
		}

		return new Form([
			new Input('action', 'hidden', $action),
			new Input("{$type}_id", 'hidden', $id),
			new Input("{$action}_{$type}", 'submit', static::getText($data)),
		]);
	}
}
