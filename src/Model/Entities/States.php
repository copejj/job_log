<?php
namespace Jeff\Code\Model\Entities;

class States extends Entities
{
	public function __construct()
	{
		parent::__construct('states', 'state_id', 'abbr');
	}
}