<?php
namespace Jeff\Code\Model\Entities;

class Status extends Entities
{
	public function __construct()
	{
		parent::__construct('statuses', 'status_id', 'status', '"order" asc');
	}
}
