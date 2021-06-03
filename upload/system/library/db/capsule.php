<?php

namespace DB;

include_once dirname(DIR_APPLICATION) . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as CapsuleManager;

final class Capsule
{
    private $queryBuilder;
    private $connection;

    public function __construct($hostname, $username, $password, $database, $port = '3306')
    {
        $this->connection = new CapsuleManager();
        $this->connection->addConnection([
            'driver' => 'mysql',
            'port' => $port,
            'host' => $hostname,
            'database' => $database,
            'username' => $username,
            'password' => $password,
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => DB_PREFIX,
        ]);

        //  $capsule->setEventDispatcher(new Dispatcher(new Container()));
        $this->connection->setAsGlobal();
        $this->connection->bootEloquent();
        $this->connection->setFetchMode(\PDO::FETCH_ASSOC);

        return $this;
    }

    public function query($sql)
    {

        $data = $this->connection->getConnection()->select($sql);

        $result = new \stdClass();
        $result->num_rows = count($data);
        $result->row = isset($data[0]) ? $data[0] : array();
        $result->rows = $data;

        return $result;
    }

    public function table($table)
    {
        $this->queryBuilder = $this->connection->getConnection()->table($table);
        return $this;
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->queryBuilder, $name)) {
            return $this->queryBuilder->$name(...$arguments);
        }

        return $this;
    }

    public function escape($value)
    {
        return $value;
    }

    public function countAffected()
    {
        throw new \Exception('Laravel capsule doesn\'t support count affected.');
    }

    public function getLastId()
    {
        return $this->connection->getConnection()->getPdo()->lastInsertId();
    }

    public function connected()
    {
        throw new \Exception('Laravel capsule doesn\'t support connected method.');
    }

    public function __destruct()
    {
        // close connection
    }
}
