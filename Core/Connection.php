<?php

namespace Core;

use PDO;
use PDOException;

class Connection
{
    private $server = "pgsql:host=localhost;dbname=bee_jee_db";

    private $user = "bee_jee_db_user";

    private $pass = "BeeJee#42";

    private $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    protected $connection;

    public function openConnection()
    {
        try {
            $this->connection = new PDO($this->server, $this->user, $this->pass, $this->options);
            return $this->connection;
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    public function closeConnection()
    {
        $this->connection = null;
    }
}
