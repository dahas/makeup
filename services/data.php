<?php

namespace makeup\services;

use makeup\lib\Service;
use makeup\lib\ServiceItem;


/**
 * Class Data
 * @package makeup\services
 */
class Data extends Service
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getCollection()
    {
        return $this->DB->select([
            "columns" => "*",
            "from" => "data"
        ]);
    }
}


/**
 * Name this class according to the service but add "Item" as suffix.
 *
 * Class DataItem
 * @package makeup\services
 */
class DataItem extends ServiceItem
{

}
