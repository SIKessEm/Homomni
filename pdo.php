<?php
try {
    $host = env('DB_HOST', 'localhost');
    $port = env('DB_PORT', '3306');
    $name = env('DB_NAME');
    $username = env('DB_USERNAME');
    $password = env('DB_PASSWORD');
    $charset = env('DB_CHARSET', 'utf8');
    $dialect = env('DB_DIALECT', 'mysql');
    $dsn = "$dialect:host=$host;port=$port;dbname=$name;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, $username, $password, $options);
    return $pdo;
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}