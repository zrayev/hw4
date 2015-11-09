<?php

namespace Layer\Connector;

interface ConnectorInterface
{
    /**
     * @return mixed
     */
    public function connect();

    /**
     * @return mixed
     */
    public function disconnect();
}