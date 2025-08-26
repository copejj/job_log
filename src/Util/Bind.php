<?php
namespace Jeff\Code\Util;

class Bind
{
	public static function get(array $data, array &$bind): string
	{
		$qs = [];
		if (!empty($data))
		{
			foreach ($data as $key => $value)
			{
				$bind[] = $value;
				$qs[] = '?';
			}
		}
		return implode(',', $qs);
	}
}