<?php
namespace Jeff\Code\Model\Entities;

class Actions extends Entities
{
	public function __construct()
	{
		parent::__construct('actions', 'action_id', 'name', 'sequence nulls last, name asc');
	}
}
