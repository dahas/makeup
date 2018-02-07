<?php

namespace makeup\services;

use makeup\lib\Service;
use makeup\lib\ServiceItem;


// /**
//  * You inject a service via annotaion in a specific module, like this:
//  *
//  * @Inject("makeup\services\Members")
//  * @var
//  */
// private $members;


/**
 * Collection of members
 * @package makeup\services
 */
class members extends Service
{
	public function __construct()
	{
		/**
		 * Call of parent constructor is mandatory.
		 * 
		 * @param string $table Name of the table
		 * @param string $columns Comma-separated list of columns (optional, default is *)
		 */
		parent::__construct(
			"data",
			"uid, name, age, city, country"
		);
	}
}


/**
 * Single item.
 *
 * Class DataItem
 * @package makeup\services
 */
class membersItem extends ServiceItem
{
	
}

