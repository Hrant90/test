<?php

namespace Services;

use PDO;

class Database {
    private ?PDO $connection = null;

    public function __construct(
        private string $host,
        private string $dbname,
        private string $username,
        private string $password
    ) {
        $this->connect();
    }

    public function connect(): PDO {
        if ($this->connection === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return $this->connection;
    }

    public function getConnection(): ?PDO
    {
        return $this->connection;
    }
}
