<?php

namespace App\Services;

use MongoDB\Client;
use MongoDB\Database;

class MongoConnection
{
    /** @var Client */
    private $client;

    /** @var Database */
    private $db;

    /**
     * @param array $params
     *      Available Parameters:
     *          username  - DB user
     *          password  - DB password
     *          host      - DB host
     *          database  - DB name
     */
    public function __construct(array $params)
    {
        $connectionString = 'mongodb://';
        if (!empty($params['username']) && !empty($params['password'])) {
            $connectionString .= $params['username'] . ':' . $params['password'] . '@';
        }

        $connectionString .= $params['host'].'/'.$params['database'];

        $this->client = new Client($connectionString);
        $this->db = $this->client->selectDatabase($params['database']);
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getDb(): Database
    {
        return $this->db;
    }
}
