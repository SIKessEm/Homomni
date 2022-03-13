<?php
require_once __DIR__ . '/init.php';

$pdo = require APP_ROOT . '/pdo.php';

$users = new UsersManager($pdo);
if (!$users->tableExists()) {
    $users->createTable();
}

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = preg_replace('/[\/]+/', '/', $path);
$path = trim($path, '/');

$action = $_REQUEST['action'] ?? '';
if (!in_array($action, ['login', 'logout', 'signup'])) {
    $action = 'join';
}

switch ($action) {
    case 'login':
        $form = new LoginForm(APP_BASE);
        break;
    case 'logout':
        $form = new LogoutForm(APP_BASE);
        break;
    case 'signup':
        $form = new SignupForm(APP_BASE);
        break;
    default:
        $form = new JoinForm(APP_BASE);
        if ($form->validate($action, APP_BASE . $path, $_SERVER['REQUEST_METHOD'])) {
            $action = $users->findByEmail($email = $form->getValue('email')) ? 'login' : 'signup';
            redirectTo("/?action=$action&email=$email");
        }
        break;
}

$view = view($path, [
    'pdo' => $pdo,
    'form' => $form,
]);

if (!isset($view)) {
    http_response_code(404);
    header('HTTP/1.0 404 Not Found');
    echo "404 Document $path Not Found";
    exit(1);
}
exit($view);
?>