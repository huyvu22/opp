<?php
//require_once '../config.php';

class Database
{
    private $driver = _DRIVER;
    private $host = _HOST;

    private $user = _USER;
    private $pass = _PASSWORD;
    private $dbname = _DB;
    private $conn = null;

    function __construct()
    {
        if (class_exists('PDO')) {
            try {
                $dsn = $this->driver . ':dbname=' . $this->dbname . ';host=' . $this->host;

                $options = [
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', //Set utf8
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ];

                $this->conn = new PDO($dsn, $this->user, $this->pass, $options);

                //var_dump($conn);
            } catch (Exception $e) {
                echo $e->getMessage();
                die();
            }
        } else {
            echo 'Vui lòng kích hoạt PDO để sử dụng';
        }
    }

    public function query($sql, $data = [], $isStatement = false)
    {
        try {
            $statement = $this->conn->prepare($sql);
            $status = $statement->execute($data);
            if ($isStatement) {
                return $statement;
            }
            return $status;
        } catch (Exception $e) {
            //var_dump(debug_backtrace());
            echo $e->getMessage();
            die();
        }
    }

    public function get($sql, $data = [])
    {
        $statement = $this->query($sql, $data, true);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function getFirst($sql, $data = [])
    {
        $statement = $this->query($sql, $data, true);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function create($table, $attributes = [])
    {
        //viết được câu lệnh sql
        //$sql = "INSERT INTO users(name, email, group_id) VALUES (:name, :email, :group_id)";
        if (!empty($attributes)) {
            $keys = array_keys($attributes);
            $sql = "INSERT INTO $table(" . implode(', ', $keys) . ") VALUES(" . ':' . implode(', :', $keys) . ")";
            $status = $this->query($sql, $attributes);
            return $status;
        }

        return false;
    }

    public function update($table, $attributes = [], $condition = null)
    {
        //$sql = "UPDATE users SET name=:name, email=:email WHERE id=1";
        if (!empty($attributes)) {
            $keys = array_keys($attributes);
            $updateStr = "";
            foreach ($keys as $key) {
                $updateStr .= "$key=:$key, ";
            }

            $updateStr = rtrim($updateStr, ', ');

            if (!empty($condition)) {
                $sql = "UPDATE $table SET $updateStr WHERE $condition";
            } else {
                $sql = "UPDATE $table SET $updateStr";
            }

            $status = $this->query($sql, $attributes);
            return $status;
        }

        return false;
    }

    public function delete($table, $condition = null)
    {
        if (!empty($condition)) {
            $sql = "DELETE FROM $table WHERE $condition";
        } else {
            $sql = "DELETE FROM $table";
        }

        $status = $this->query($sql);
        return $status;
    }

    public function createGetId($table, $attributes = [])
    {
        $this->create($table, $attributes);
        return $this->conn->lastInsertId();
    }

    public function getRow($sql)
    {
        $statement = $this->query($sql, [], true);
        return $statement->rowCount();
    }

}
