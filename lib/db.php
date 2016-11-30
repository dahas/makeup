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
        if (self::$instance == null)
            self::$instance = new DB();
        return self::$instance;
    }


    /**
     * @param array $conf
     * @return Recordset
     */
    public function select($conf)
    {
        $sql = "SELECT";
        if (isset($conf['columns']))
            $sql .= " {$conf['columns']}";
        if (isset($conf['from']))
            $sql .= " FROM {$conf['from']}";
        if (isset($conf['where']))
            $sql .= " WHERE {$conf['where']}";
        if (isset($conf['groupBy']))
            $sql .= " GROUP BY {$conf['groupBy']}";
        if (isset($conf['orderBy']))
            $sql .= " ORDER BY {$conf['orderBy']}";
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
        if (isset($conf['into']))
            $sql .= " {$conf['into']}";
        if (isset($conf['columns']))
            $sql .= " ({$conf['columns']})";
        if (isset($conf['values'])) {
            $valArr = explode(",", $conf['values']);
            $newArr = array();
            foreach ($valArr as $val) {
                $newArr[] = "'" . mysqli_real_escape_string($this->conn, $val) . "'";
            }
            $newValues = implode(",", $newArr);
            $sql .= " VALUES ($newValues)";
        }
        $res = mysqli_query($this->conn, $sql);
        if ($res)
            return mysqli_insert_id($this->conn);
        return 0;
    }


    /**
     * @param array $conf
     * @return bool|\mysqli_result
     */
    public function update($conf)
    {
        $sql = "UPDATE";
        if (isset($conf['table']))
            $sql .= " {$conf['table']}";
        if (isset($conf['set']))
            $sql .= " SET {$conf['set']}";
        if (isset($conf['where']))
            $sql .= " WHERE {$conf['where']}";
        return mysqli_query($this->conn, $sql);
    }


    /**
     * @param array $conf
     * @return bool|\mysqli_result
     */
    public function delete($conf)
    {
        $sql = "DELETE FROM";
        if (isset($conf['from']))
            $sql .= " {$conf['from']}";
        if (isset($conf['where']))
            $sql .= " WHERE {$conf['where']}";
        return mysqli_query($this->conn, $sql);
    }


    /**
     * Destructor
     */
    public function __destruct()
    {
        if (mysqli_close($this->conn))
            $this->conn = null;
    }

}


class Recordset
{
    private $recordset = null;

    const FETCH_ROW = 0;
    const FETCH_ASSOC = 1;
    const FETCH_OBJECT = 2;


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
        return mysqli_num_rows($this->recordset);
    }


    /**
     * @return bool
     */
    public function reset()
    {
        return mysqli_data_seek($this->recordset, 0);
    }


    /**
     * @param int $fetch
     * @return array|null|object
     */
    public function getRecord($fetch = self::FETCH_OBJECT)
    {
        if ($fetch === self::FETCH_ASSOC)
            return mysqli_fetch_assoc($this->recordset);
        if ($fetch === self::FETCH_OBJECT)
            return mysqli_fetch_object($this->recordset);
        return mysqli_fetch_row($this->recordset);
    }


    /**
     * Destructor
     */
    public function __destruct()
    {
        mysqli_free_result($this->recordset);
        unset($this);
    }

}