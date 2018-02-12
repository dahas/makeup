<?php

namespace makeup\lib;


/**
 * Class ServiceItem
 * @package makeup\lib
 */
class ServiceItem
{
    private $DB = null;
    private $record = null;
    private $table = "";
    private $key = "";
    private $value = "";


    /**
     * ServiceItem constructor.
     * 
     * @param object $db Database
     * @param object $record Single record
     * @param object $table Table name
     * @param object $key Column name
     * @param object $value Column value
     */
    public function __construct($db, $record, $table, $key, $value)
    {
        $this->DB = $db;
        $this->record = $record;
        $this->table = $table;
        $this->key = $key;
        $this->value = $value;
    }


    /**
     * Access a property.
     * 
     * @param string $item
     * @return string $value
     */
    public function getProperty($item)
    {
        return isset($this->record->$item) ? $this->record->$item : null;
    }


    /**
     * Change the value of a property.
     * 
     * @param string $item
     * @param string $value
     */
    public function setProperty($item, $value)
    {
        $this->record->$item = $value;
    }


    /**
     * Update the record.
     * 
     * @return boolean $updated
     */
    public function update()
    {
        $set = [];

        foreach($this->record as $item => $value) {
            $set[] = "$item='$value'";
        }

        if (!empty($set)) {
            return $this->DB->update([
                "table" => $this->table,
                "set" => implode(", ", $set),
                "where" => $this->key . "=" . $this->value
            ]);
        }
        
        return false;
    }


    /**
     * Delete a record.
     * 
     * @return boolean $deleted
     */
    public function delete()
    {
        return $this->DB->delete([
            "from" => $this->table,
            "where" => $this->key . "=" . $this->value
        ]);
    }
}