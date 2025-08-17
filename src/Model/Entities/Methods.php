<?php
namespace Jeff\Code\Model\Entities;

class Methods extends Entities
{
	public function __construct()
	{
		parent::__construct('methods', 'method_id', 'name', 'name');
	}
}
