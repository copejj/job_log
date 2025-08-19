<?php
namespace Jeff\Code\Model\Entities;

use Jeff\Code\Model\Company\Company;
use Jeff\Code\Model\Record;

use Jeff\Code\View\Elements\Date;

use Jeff\Code\Util\DB;

class Companies extends Entities
{
	public function __construct()
	{
		parent::__construct('companies', 'company_id', 'name');
	}
}
