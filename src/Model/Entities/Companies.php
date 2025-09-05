<?php
namespace Jeff\Code\Model\Entities;

class Companies extends Entities
{
	public function __construct()
	{
		parent::__construct('companies', 'company_id', 'name', 'name');
	}
}
