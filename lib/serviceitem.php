<?php

namespace makeup\lib;


/**
 * Class ServiceItem
 * @package makeup\lib
 */
class ServiceItem
{
    private $record = null;


    /**
     * DataItem constructor.
     * @param $record
     */
    public function __construct($record)
    {
        $this->record = $record;
    }


    /**
     * Getter function to access a property
     * @param $item
     * @return mixed
     */
    public function getProperty($item)
    {
        return isset($this->record->$item) ? $this->record->$item : null;
    }


    /**
     * Setter function to change the value of a property
     * @param $item
     * @param $value
     * @return mixed
     */
    public function setProperty($item, $value)
    {
        $this->record->$item = $value;
    }
}