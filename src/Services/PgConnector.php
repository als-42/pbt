<?php

namespace Rater\Services;


class PgConnector
{
    private \PDO $pdo;

    public function createPdo(): self
    {
        $conStr = sprintf("pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
            getenv("POSTGRES_HOST"),
            getenv("POSTGRES_PORT"),
            getenv("POSTGRES_DB"),
            getenv("POSTGRES_USER"),
            getenv("POSTGRES_PASSWORD")
        );

        $this->pdo = new \PDO($conStr);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $this;
    }

    public function context(): \PDO
    {
        return $this->pdo;
    }
}