<?php

namespace Api\Root;

use Exception;

class URL
{
    private static string $short = '';

    public static function test(string $url): bool
    {
        $row = DB::query('SELECT `short` FROM `urls` WHERE `url` = ? LIMIT 1', [$url], true);

        if (count($row) == 1) {
            self::$short = $row[0]['short'];
            return true;
        }

        return false;
    }

    public static function getShort(): string
    {
        return self::$short;
    }

    public static function make($url): void
    {
        $id = self::insertURL($url);
        self::$short = self::hashId($id);
        self::updateURL($id);
    }

    public static function validateUrl($url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    public static function getURL($short): string
    {
        $row = DB::query('SELECT `url` FROM `urls` WHERE `short` = ? LIMIT 1', [$short], true);

        if (count($row) == 1) {
            return $row[0]['url'];
        }

        throw new Exception('URL not found');
    }

    private static function insertURL(string $url): string
    {
        return DB::query('INSERT INTO `urls` (`url`) VALUES (?)', [$url], last_id: true);
    }

    private static function updateURL(string $id): void
    {
        DB::query('UPDATE `urls` SET `short` = ? WHERE `id` = ?', [self::$short, $id]);
    }

    private static function hashId(int $id): string
    {
        return hash("crc32", $id);
    }
}
