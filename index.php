<?php
require_once __DIR__ . '/init.php';

$pdo = require APP_ROOT . '/pdo.php';
$pdo->exec('SET NAMES UTF8');


$users = new Users($pdo);
if (!$users->tableExists()) {
    $users->createTable();
}

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$view = view($path, [
    'pdo' => $pdo,
]);

if (!isset($view)) {
    http_response_code(404);
    header('HTTP/1.0 404 Not Found');
    echo "404 Document $path Not Found";
    exit(1);
}
exit($view);
?>