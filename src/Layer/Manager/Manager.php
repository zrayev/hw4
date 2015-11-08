<?php

namespace Layer\Manager;
use Layer\Connector\ConnectorClass;

/**
 * Class Manager
 * @package Layer\Manager
 */
abstract class Manager implements ManagerInterface
{
    /**
     * @var ConnectorClass
     */
    protected $connector;

    /**
     * @var string
     */
    public $tableName;

    /**
     * Manager constructor.
     * @param ConnectorClass $connector
     */
    public function __construct(ConnectorClass $connector)
    {
        $this->connector = $connector;
    }

    abstract protected function createObject(array $fields);

    /**
     * @param array $fields
     * @return object[]
     */
    protected function createObjects(array $fields)
    {
        $objects = [];

        foreach ($fields as $objectFields) {
            $objects[] = $this->createObject($objectFields);
        }

        return $objects;
    }

    /**
     * @param $id
     * @return object|null
     */
    public function find($id)
    {
        $statement = $this->connector->connect()
            ->prepare("SELECT * FROM {$this->tableName} WHERE id = :id LIMIT 1");
        $statement->bindValue(':id', (int) $id);
        $statement->execute();
        $result = $statement->fetchAll();

        if (count($result) === 1) {
            return $this->createObject($result[0]);
        }

        return null;
    }

    /**
     * @param array $ids
     * @return object|null
     */
    public function findByIds(array $ids)
    {
        $statement = $this->connector->connect()
            ->prepare("SELECT * FROM {$this->tableName} WHERE id IN(:ids)");
        $statement->bindValue(':id', $ids);
        $statement->execute();

        return $this->createObjects($statement->fetchAll());
    }
    /**
     * @return object
     */
    public function findAll()
    {
        $statement = $this->connector->connect()
            ->prepare("SELECT * FROM {$this->tableName}")
        ;
        $statement->execute();

        return $this->createObject($statement->fetchAll());
    }

    /**
     * @param array $criteria
     * @return object
     */
    public function findBy($criteria = [])
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE ";
        $lastKey = end(array_keys($criteria));

        foreach ($criteria as $fieldName => $fieldValue) {
            $sql .= "$fieldName = $fieldValue";

            if ($fieldName !== $lastKey) {
                $sql .= ' AND ';
            }
        }

        $statement = $this->connector->connect()
            ->prepare($sql)
        ;
        $statement->execute();

        return $this->createObject($statement->fetchAll());
    }

    /**
     * @param object $post
     * @return bool
     */
    public function remove($post)
    {
        $statement = $this->connector->connect()
            ->prepare("DELETE FROM {$this->tableName} WHERE id = :id");
        $statement->bindValue(':id', $post->getId(), \PDO::PARAM_INT);

        return $statement->execute();
    }
}