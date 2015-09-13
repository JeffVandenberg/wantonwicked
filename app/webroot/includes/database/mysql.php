<?php
use classes\core\repository\Database;

function ExecuteQueryData($sql = null, $connection = null)
{
    return Database::getInstance()->query($sql)->all();
}

function ExecuteQueryItem($sql, $connection = null)
{
    return Database::getInstance()->query($sql)->single();
}