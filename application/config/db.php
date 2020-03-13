<?php

function connect_db()
{
    $host = '127.0.0.1';
    $db   = 'phpToDo';
    $user = 'root';
    $pass = 'Iddqd2010!';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int) $e->getCode());
    }

    return $pdo;
}

function getTotalPages($limit, $pdo) {
    $stmt = $pdo->query("SELECT username, email, text FROM phpToDo");
    while ($row = $stmt->fetch()) {
        $temp_data[] = $row;
    }
    
    if (empty($temp_data)) {
        return 0;
    }

    $total_records = count($temp_data);
    
    $total_pages = ceil($total_records / $limit);
    
    return $total_records;
}
