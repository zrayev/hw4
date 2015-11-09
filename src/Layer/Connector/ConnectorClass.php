<?php

namespace Layer\Connector;
/**
 * Class ConnectorClass
 * @package Layer\Connector
 */
class ConnectorClass implements ConnectorInterface
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var string
     */
    private $databasename;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @param string $databasename
     * @param string $user
     * @param string $password
     */
    public function  __construct($databasename, $user, $password)
    {
        $this->databasename = $databasename;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @return \PDO
     */
    public function connect()
    {
        if (!$this->pdo) {
            $this->pdo = new \PDO(
                'mysql:host=localhost;dbname=' . $this->databasename . ';charset=UTF8',
                $this->user,
                $this->password
            );
        }

        return $this->pdo;
    }

    /**
     * @return null
     */
    public function disconnect()
    {
        return $this->pdo = null;
    }

    /**
     * @param string $databasename
     * @param string $user
     * @param string $password
     * @param string $host
     */
    public function createDataBase($databasename, $user, $password, $host)
    {
        $conn = new \PDO("mysql:host=$host", $user, $password);
        $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE DATABASE $databasename";
        $conn->exec($sql);
    }
}