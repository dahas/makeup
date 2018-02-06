<?php

namespace makeup\services;

use makeup\lib\Service;
use makeup\lib\ServiceItem;


/**
 * Class members
 * @package makeup\services
 */
class members extends Service
{
	public function __construct()
	{
		/**
		 * Call of parent constructor is mandatory.
		 * 
		 * @param String $table Name of the table
		 * @param String $columns Comma-separated list of columns (optional, default is *)
		 * @param String $where Where clause (optional)
		 */
		parent::__construct(
			"data",
			"uid, name, age, city, country",
			"name LIKE '%a%' AND age > 30"
		);
	}


	/**
	 * READ from database
	 * 
	 * @param string $where	Where clause.
	 * @return int $count
	 */
	public function read()
	{
		$this->recordset = $this->DB->select([
			"columns" => $this->columns,
			"from" => $this->table
		]);

		return $this->count();
	}

}


/**
 * Name this class according to the service but add "Item" as suffix.
 *
 * Class DataItem
 * @package makeup\services
 */
class membersItem extends ServiceItem
{
	
}

