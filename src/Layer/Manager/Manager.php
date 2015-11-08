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
     * Manager constructor.
     * @param ConnectorClass $connector
     */
    public function __construct(ConnectorClass $connector)
    {
        $this->connector = $connector;
    }

    abstract protected function createObject(array $fields);

}