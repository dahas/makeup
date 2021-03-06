<?php

namespace makeup\lib;

/**
 * Class DB
 * @package makeup\lib
 */
class DB
{
    private static $instance = null;
    private $conn = null;
    private $db = "";
    private $host = "";
    private $user = "";
    private $pass = "";
    private $charset = "utf8";

    /**
     * Protected constructor, because we use a singleton.
     */
    protected function __construct()
    {
        $this->db = Config::get('database', 'db_name');
        $this->host = Config::get('database', 'host');
        $this->user = Config::get('database', 'username');
        $this->pass = Config::get('database', 'password');
        $this->charset = Config::get('database', 'charset');

        $this->conn = mysqli_connect($this->host, $this->user, $this->pass) or die("Connection to database failed!");

        mysqli_select_db($this->conn, $this->db) or die("Database doesn´t exist!");

        mysqli_set_charset($this->conn, $this->charset);
    }

    /**
     * Singleton
     * @return null|DB
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new DB();
        }

        return self::$instance;
    }

    /**
     * @param array $conf
     * @return Recordset
     */
    public function select($conf)
    {
        $sql = "SELECT";
        if (isset($conf['columns'])) {
            $sql .= " {$conf['columns']}";
        }

        if (isset($conf['from'])) {
            $sql .= " FROM {$conf['from']}";
        }

        if (isset($conf['where'])) {
            $sql .= " WHERE {$conf['where']}";
        }

        if (isset($conf['groupBy'])) {
            $sql .= " GROUP BY {$conf['groupBy']}";
        }

        if (isset($conf['orderBy'])) {
            $sql .= " ORDER BY {$conf['orderBy']}";
        }

        if (isset($conf['limit'])) {
            $sql .= " LIMIT {$conf['limit']}";
        }

        $rs = mysqli_query($this->conn, $sql);
        return new Recordset($rs);
    }

    /**
     * @param array $conf
     * @return int
     */
    public function insert($conf)
    {
        $sql = "INSERT INTO";
        if (isset($conf['into'])) {
            $sql .= " {$conf['into']}";
        }

        if (isset($conf['columns'])) {
            $sql .= " ({$conf['columns']})";
        }

        if (isset($conf['values'])) {
            $valArr = explode(",", $conf['values']);
            $newArr = array();
            foreach ($valArr as $val) {
                $newArr[] = "'" . trim(mysqli_real_escape_string($this->conn, $val)) . "'";
            }
            $newValues = implode(",", $newArr);
            $sql .= " VALUES ($newValues)";
        }
        $res = mysqli_query($this->conn, $sql);
        if ($res) {
            return mysqli_insert_id($this->conn);
        }

        return 0;
    }

    /**
     * @param array $conf
     * @return bool|mysqli_result
     */
    public function update($conf)
    {
        $sql = "UPDATE";
        if (isset($conf['table'])) {
            $sql .= " {$conf['table']}";
        }

        if (isset($conf['set'])) {
            $sql .= " SET {$conf['set']}";
        }

        if (isset($conf['where'])) {
            $sql .= " WHERE {$conf['where']}";
        }

        return mysqli_query($this->conn, $sql);
    }

    /**
     * @param array $conf
     * @return bool|mysqli_result
     */
    public function delete($conf)
    {
        $sql = "DELETE FROM";
        if (isset($conf['from'])) {
            $sql .= " {$conf['from']}";
        }

        if (isset($conf['where'])) {
            $sql .= " WHERE {$conf['where']}";
        }

        return mysqli_query($this->conn, $sql);
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        if (mysqli_close($this->conn)) {
            $this->conn = null;
        }
    }

}

class Recordset
{
    private $recordset = null;

    /**
     * Recordset constructor.
     * @param $rs
     */
    public function __construct($rs)
    {
        $this->recordset = $rs;
    }

    /**
     * @return int
     */
    public function getRecordCount()
    {
        return $this->recordset ? mysqli_num_rows($this->recordset) : 0;
    }

    /**
     * @return bool
     */
    public function reset()
    {
        return $this->recordset ? mysqli_data_seek($this->recordset, 0) : null;
    }

    /**
     * Iterate through collection
     * 
     * @return array|null|object
     */
    public function next()
    {
        $record = $this->recordset ? mysqli_fetch_object($this->recordset) : null;
        if ($record) {
            return $record;
        } else {
            return null;
        }
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        if ($this->recordset)
            mysqli_free_result($this->recordset);
    }

}


/**
 * Class Record
 * @package makeup\lib
 */
class Record
{
    private $record = null;

    /**
     * Record constructor.
     * 
     * @param object $record Single record
     */
    public function __construct($record)
    {
        $this->record = $record;
    }

    /**
     * Access a property.
     * 
     * @param string $item
     * @return mixed $value
     */
    public function getProperty($item)
    {
        return isset($this->record->$item) ? $this->record->$item : null;
    }

    /**
     * Change the value of a property.
     * 
     * @param string $item
     * @param mixed $value
     */
    public function setProperty($item, $value)
    {
        $this->record->$item = $value;
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        unset($this->record);
    }
}
