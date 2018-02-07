<?php

namespace makeup\services;

use makeup\lib\Service;
use makeup\lib\ServiceItem;


/**
 * Collection of Test
 * @package makeup\services
 */
class Test extends Service
{
    public function __construct()
    {
        /**
         * IMPORTANT: Please change the table name and columns that you provide to the parent constructor!
         * 
         * @param string $table Name of the table
         * @param string $columns Comma-separated list of columns (optional, default is *)
         */
        parent::__construct(
            "table_name",
            "col1, col2, col3, ..."
        );
    }
}


/**
 * Single item.
 *
 * Class DataItem
 * @package makeup\services
 */
class TestItem extends ServiceItem
{
    
}
