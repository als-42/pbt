<?php

namespace Rater\Services;


class Connection
{
    static \PDO $PDO;

    static int $i = 0;
    public function pg(): \PDO
    {
        // static hotfix or need setup correct DI singleton instance \ factory or some
        //{"http_status_code":500,"http_status_message":"Internal Server Error","errors":[
        //"SQLSTATE[55000]: Object not in prerequisite state: 7 ERROR: currval of sequence
        // \u0022requested_credit_limits_history_id_seq\u0022 is not yet defined in this session"],"response":[]}
        if (static::$i === 0)
        {
            static::$i += 1;

            $conStr = sprintf("pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
                getenv("POSTGRES_HOST"),
                getenv("POSTGRES_PORT"),
                getenv("POSTGRES_DB"),
                getenv("POSTGRES_USER"),
                getenv("POSTGRES_PASSWORD")
            );

            $pdo = new \PDO($conStr);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            static::$PDO = $pdo;
        }

        return static::$PDO;
    }
}