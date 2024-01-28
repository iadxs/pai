<?php
require_once('singleton.php');

class Database extends Singleton
{
    private $conn;

    /**
     * the constructor creates a connection to the database
     * @param string $server server name
     * @param string $username User Name
     * @param string $password password
     * @param string $database_name database name
     * @param int $port port number
     */
    public function __construct($server = '', $username = '', $password = '', $database_name = '', $port = 3306)
    {
        if (empty($server) && empty($username) && empty($password) && empty($database_name) && $port === 3306) {
            // global $db_host, $db_username, $db_password, $db_database, $db_port;
            $this->conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
        } else {
            $this->conn = @mysqli_connect($server, $username, $password, $database_name);
        }

        if (!$this->isConnected()) {
            exit;
        }

        mysqli_set_charset($this->conn, 'utf8');
    }

    /**
     * the destructor closes the connection to the database if the connection is active
     */
    public function __destruct()
    {
        if ($this->conn) {
            mysqli_close($this->conn);
        }
    }

    /**
     * if connection is true it returns the last connection error, if it is false it returns the last query error
     * @param bull $conn connection to the database
     * @return string error, error number, error message
     */
    public function error($conn = false)
    {
        if ($conn == true) {
            return 'Błąd ' . mysqli_connect_errno() . ': ' . mysqli_connect_error();
        } else {
            return 'Błąd' . mysqli_errno($this->conn) . ': ' . mysqli_error($this->conn);
        }
    }

    /**
     * if it failed to connect to the database shows the error number and the error message
     * @return true if the connection to the database was successful otherwise returns false
     */
    public function isConnected()
    {
        if (mysqli_connect_errno() !== 0) {
            // sendResponse(500, "Błąd: " . mysqli_connect_errno() . ': ' . mysqli_connect_error());
            response("Błąd: " . mysqli_connect_errno() . ': ' . mysqli_connect_error(), false, 500);
            return false;
        }
        return true;
    }

    /**
     * executes the query and returns what mysql
     * @param mixed $sql the content of the mysql query
     * @return any the result of the mysql query
     */
    public function query($sql)
    {
        $result = mysqli_query($this->conn, $sql);
        if (!$result) {
            throw new Exception($this->error());
        }
        return $result;
    }

    /**
     * returns all records as an associative array
     * @param mixed $result query result from the 'query' function
     * @return array
     */
    public function fetchByAssoc($result)
    {
        $rows = [];

        while ($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        }
        return $rows;
    }

    /**
     * returns the first row and only the first column of the first row - only the value
     * @param mixed $result query result from the 'query' function
     * @return mixed
     */
    public function fetchSingle($result)
    {
        $value = $this->fetchByAssoc($result);

        if (empty($value)) {
            return '';
        }

        $value = array_shift($value);
        $value = array_shift($value);

        return $value;
    }

    /**
     * returns a single row as an associative array
     * @param mixed $result query result from the 'query' function
     * @return mixed 
     */
    public function fetchSingleRow($result)
    {
        return mysqli_fetch_assoc($result);
    }

    /**
     * returns the number of records after the query
     * @param mixed $sql the content of the mysql query
     * @return int $num_rows
     */
    public function rows($sql)
    {
        $num_rows = mysqli_num_rows($sql);
        return $num_rows;
    }

    /**
     * returns a list of modified records upon query
     * @return int
     */
    public function affectedRows()
    {
        return mysqli_affected_rows($this->conn);
    }

    public function begin_transaction()
    {
        return mysqli_begin_transaction($this->conn);
    }

    public function commit()
    {
        return mysqli_commit($this->conn);
    }

    public function rollback()
    {
        return mysqli_rollback($this->conn);
    }
}
