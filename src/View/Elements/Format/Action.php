<?php
namespace Jeff\Code\View\Elements\Format;

use Exception;

use Jeff\Code\Model\Record;
use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\Format\Formatter;

abstract class Action implements Formatter
{
	protected abstract static function getType(): string;
	protected abstract static function getAction(): string;

	protected static function getAttributes(): Attributes
	{
		return new Attributes();
	}

	protected static function getText(Record $data): string
	{
		return ucwords(static::getAction());
	}

	protected static function getForm(string $action, string $type, Record $data): string
	{
		$type_id = "{$type}_id";
		$id = $data->$type_id;
		if (empty($id))
		{
			return '';
		}

		return new Form([
			new Input('action', 'hidden', $action),
			new Input($type_id, 'hidden', $id),
			new Input("{$action}_{$type}", 'submit', static::getText($data)),
		], 'post', static::getAttributes());
	}

	public static function format(string $key, Record $data): string
	{
		$action = static::getAction();
		if (empty($action))
		{
			throw new Exception('Actions requires an action');
		}

		$type = static::getType();
		if (empty($type))
		{
			throw new Exception('Actions requires a type');
		}

		return static::getForm($action, $type, $data);
	}
}
