<?php

namespace App\Services;

class MongoService
{
    private $config;
    private $connections = array();

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getConnection(string $name): MongoConnection
    {
        if (empty($this->connections[$name])) {
            $this->connections[$name] = $this->makeConnection($name);
        }

        return $this->connections[$name];
    }

    public function getDefaultConnection(): MongoConnection
    {
        return $this->getConnection($this->config['mongodb']);
    }

    private function makeConnection(string $name): MongoConnection
    {
        $params = $this->config['connections'][$name];

        return new MongoConnection($params);
    }
}
