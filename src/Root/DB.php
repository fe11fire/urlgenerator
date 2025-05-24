<?php

namespace Api\Root;

use PDO;


class DB
{
    const DB_MYSQL = 'mysql';
    const DB_POSGRES = 'pgsql';
    const CHARSET_NULL = '';
    const CHARSET_UTF8MB4 = ';charset=utf8mb4';

    static string $db;
    static string $server;
    static string $dbname;
    static string $user;
    static string $password;
    static string $charset;

    /**
     * @param string $db СУБД const DB_MYSQL | DB_POSGRES
     * @param string $server сервер СУБД
     * @param string $dbname имя БД
     * @param string $user логин
     * @param string $password пароль
     * @param string $charset const CHARSET_NULL | CHARSET_UTF8MB4
     * @return void
     */
    public static function init(
        string $db = self::DB_MYSQL,
        string $server = 'mariadb-10.4',
        string $dbname = 'urlgenerator',
        string $user = 'root',
        string $password = '',
        string $charset = self::CHARSET_UTF8MB4
    ): void {
        self::$server = $server;
        self::$dbname = $dbname;
        self::$user = $user;
        self::$password = $password;
        self::$db = $db;
        self::$charset = $charset;
    }

    public static function query($sql, $args = null, $fetch = null, $last_id = null, $exec_time = false)
    {
        $rows = [];

        $dsn = self::$db . ":host=" . self::$server . ";dbname=" . self::$dbname . self::$charset;
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
