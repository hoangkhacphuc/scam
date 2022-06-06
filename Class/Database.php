<?php

class Database
{

    // Config Database
    private $host = "localhost";
    private $db_name = "scam";
    private $username = "root";
    private $password = "";

    private $conn;
    private $isConnection = false;
    private $query = "";
    private $result = null;

    public function __construct()
    {
        $this->dbConnection();
    }

    public function dbConnection() :bool
    {
        $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        if (!$this->conn) {
            return false;
        }
        return true;
    }

    public function isConnected() :bool
    {
        return $this->isConnection;
    }

    public function query($query)
    {
        $this->query = $query;
        $this->result = mysqli_query($this->conn, $this->query);
        return $this->result;
    }

    public function select($table, $where = null, $order = null, $limit = null)
    {
        $sql = "SELECT * FROM `$table`";
        if ($where != null) {
            if (is_array($where)) {
                $sql .= " WHERE ";
                foreach ($where as $key => $value) {
                    $sql .= "`$key` = '$value' AND ";
                }
                $sql = substr($sql, 0, -5);
            }
            else $sql .= " WHERE $where";
        }
        if ($order != null) {
            $sql .= " ORDER BY $order";
        }
        if ($limit != null) {
            $sql .= " LIMIT $limit";
        }
        $this->query = $sql;
        $this->result = mysqli_query($this->conn, $sql);
        if (!$this->result) {
            return array();
        }
        return $this->getResultArray($this->result);
    }

    public function insert($table, $data)
    {
        $sql = "INSERT INTO `$table` (";
        foreach ($data as $key => $value) {
            $sql .= "`$key`, ";
        }
        $sql = substr($sql, 0, -2);
        $sql .= ") VALUES (";
        foreach ($data as $key => $value) {
            $sql .= "'$value', ";
        }
        $sql = substr($sql, 0, -2);
        $sql .= ")";
        $this->query = $sql;
        $this->result = mysqli_query($this->conn, $sql);
        if (!$this->result) {
            return false;
        }
        return $this->conn->insert_id;
    }

    public function update($table, $data, $where)
    {
        $sql = "UPDATE `$table` SET ";
        foreach ($data as $key => $value) {
            $sql .= "`$key` = '$value', ";
        }
        $sql = substr($sql, 0, -2);
        if (is_array($where)) {
            $sql .= " WHERE ";
            foreach ($where as $key => $value) {
                $sql .= "`$key` = '$value' AND ";
            }
            $sql = substr($sql, 0, -5);
        }
        else $sql .= " WHERE $where";
        $this->query = $sql;
        $this->result = mysqli_query($this->conn, $sql);
        if (!$this->result) {
            return false;
        }
        return true;
    }

    public function delete($table, $where)
    {
        $sql = "DELETE FROM `$table`";
        if (is_array($where)) {
            $sql .= " WHERE ";
            foreach ($where as $key => $value) {
                $sql .= "`$key` = '$value' AND ";
            }
            $sql = substr($sql, 0, -5);
        }
        else $sql .= " WHERE $where";
        $this->query = $sql;
        $this->result = mysqli_query($this->conn, $sql);
        if (!$this->result) {
            return false;
        }
        return true;
    }

    public function getResultArray($result) : array
    {
        $array = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $array[] = $row;
        }
        return $array;
    }

    public function getQueryString() :string
    {
        return $this->query;
    }
}

