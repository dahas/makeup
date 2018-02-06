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
		 */
		parent::__construct(
			"data", // Name of the table
			"uid, name, age, city, country",	// Comma-separated list of columns (optional, default is *.)
			"name LIKE '%a%'"	// WHERE clause (optional)
		);
	}


	/**
	 * Function with a more complex SQL statement. If you have defined
	 * a setup before a custom select, the setup will be overwritten.
	 * @param type $name	Parameter for where clause.
	 */
	public function selectUsers($name)
	{
		$rs = $this->DB->select([
			"columns" => $this->columns,
			"from" => $this->table,
			"where" => "name='$name'"
		]);
		
		return $this->useService($rs);
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

