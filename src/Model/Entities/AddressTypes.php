<?php
namespace Jeff\Code\Model\Entities;

class AddressTypes extends Entities
{
	public function __construct()
	{
		parent::__construct('address_types', 'address_type_id', 'name');
	}
}
