<?php

namespace makeup\lib;


/**
 * Class Service
 * @package makeup\lib
 */
abstract class Service
{
	protected $DB = null;
	protected $recordset = null;
	
	protected $table = "";
	protected $columns = "*";


	/**
	 * 
	 * @param string $table Name of the table
	 * @param string $columns Comma-separaated list of columns (optional, default is *)
	 */
	public function __construct($table = "", $columns = "*")
	{
		// Get the database instance
		$this->DB = DB::getInstance();

		$this->table = $table;
		$this->columns = $columns;
	}


	/**
	 * CREATE a new record
	 * 
	 * @param string $columns Comma-separeted columns
	 * @param string $values Comma-separeted values
	 * @return boolean $inserted
	 */
	public function create($columns, $values)
	{
		return $this->DB->insert([
			"into" => $this->table,
			"columns" => $columns,
			"values" => $values
		]);
	}


	/**
	 * READ from database. 
	 * @return int $count
	 */
	public function read($where = "")
	{
		$statement = [
			"columns" => $this->columns,
			"from" => $this->table
		];
		if ($where) {
			$statement = array_merge($statement, ["where" => $where]);
		}
		$this->recordset = $this->DB->select($statement);
		
		return $this->count();
	}


	/**
	 * UPDATE an existing record
	 * 
	 * @param string $columns Comma-separeted columns
	 * @param string $values Comma-separeted values
	 * @return boolean $inserted
	 */
	public function update($set, $where)
	{
		return $this->DB->update([
			"table" => $this->table,
			"set" => $set,
			"where" => $where
		]);
	}


	/**
	 * DELETE a record
	 * 
	 * @param string $columns Comma-separeted columns
	 * @param string $values Comma-separeted values
	 * @return boolean $inserted
	 */
	public function delete($where)
	{
		return $this->DB->delete([
			"from" => $this->table,
			"where" => $where
		]);
	}


	/**
	 * Returns the amount of records.
	 * 
	 * @return int $count
	 */
	public function count()
	{
		return $this->recordset->getRecordCount();
	}
	
	
	/**
	 * Get a single record by the given column and its value.
	 * 
	 * @param string $key Column
	 * @param string $value Value
	 * @return object $serviceItem
	 */
	public function getByKey($key, $value)
	{
		$this->recordset = $this->DB->select([
			"columns" => $this->columns,
			"from" => $this->table,
			"where" => "$key='$value'"
		]);
		
		return $this->next($key, $value);
	}
	
	/**
	 * Creates the model of the data provided by the service.
	 * Cannot be executed before useService() has been run.
	 * @return object|null $serviceItem
	 * @throws \Exception
	 */
	public function next($key = "", $value = "")
	{
		if (!$this->recordset) {
			throw new \Exception('No collection found! Create a recordset first.');
		}

		if ($record = $this->recordset->getRecord()) {
			// Getting name of child class (our service)
			$serviceItem = get_class($this) . "Item";
			return new $serviceItem($this->DB, $record, $this->table, $key, $value);
		}

		$this->recordset->reset();

		return null;
	}


}

