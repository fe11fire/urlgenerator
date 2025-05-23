<?php

namespace Api\Root;

use PDO;


class DB
{
    static string $server;
    static string $dbname;
    static string $user;
    static string $password;

    /**
     * @param string $server сервер СУБД
     * @param string $dbname имя БД
     * @param string $user логин
     * @param string $password пароль
     * @return void
     */
    public static function init(
        string $server = 'mariadb-10.4',
        string $dbname = 'urlgenerator',
        string $user = 'root',
        string $password = ''
    ): void {
        self::$server = $server;
        self::$dbname = $dbname;
        self::$user = $user;
        self::$password = $password;
    }

    public static function query($sql, $args = null, $fetch = null, $last_id = null, $exec_time = false)
    {
        $rows = [];

        $dsn = "mysql:host=" . self::$server . ";dbname=" . self::$dbname . ";charset=utf8mb4";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, self::$user, self::$password, $opt);

        if ((isset($args)) && (count($args) == 0)) {
            $args = null;
        }
        if ($args) {
            $prev_time = microtime(true);

            $stmt = $pdo->prepare($sql);
            $i = 0;
            foreach ($args as $key => $value) {
                $i++;
                if (is_null($value)) {
                    $stmt->bindValue($i, null);
                } else {
                    $stmt->bindValue($i, $value);
                }
            }
            $stmt->execute();

            $difference = microtime(true) - $prev_time;

            if ($exec_time) {
                // SEND_LOG(round($difference, 3), 1, true);
            }
        } else {
            $stmt = $pdo->query($sql);
        }

        if ($fetch) {
            $rows = $stmt->fetchAll();
        }
        if ($last_id) {
            return $pdo->lastInsertID();
        }

        $pdo = null;
        $dsn = null;
        if ($fetch) {
            return $rows;
        }
        return [];
    }
}
