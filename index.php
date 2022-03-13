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
        $title = 'Connexion';
        $description = 'Connectez-vous pour accédez à votre compte en un clic !';
        $keywords = 'connexion, login, signin, se connecter, s\'identifier, s\'authentifier, accéder à un compte';
        $form = new LoginForm(APP_BASE);
        if ($form->validate($action, APP_BASE . $path, $_SERVER['REQUEST_METHOD'])) {
            $email = $form->getValue('email');
            $password = $form->getValue('password');
            $user = $users->findByEmail($email);
            $user_password = $user->getPassword();
            if (password_verify($password, $user_password)) {
                $_SESSION['user'] = $user;
                redirectTo(APP_BASE);
            }
        }
        break;
    case 'logout':
        $title = 'Déconnexion';
        $description = 'En cliquant sur ce bouton, vous vous déconnectez de votre compte.';
        $keywords = 'deconnexion, logout, signout, se déconnecter, quitter le site, quitter le compte revenir plus tard';
        $form = new LogoutForm(APP_BASE);
        if ($form->validate($action, APP_BASE . $path, $_SERVER['REQUEST_METHOD'])) {
            unset($_SESSION['user']);
            session_destroy();
            redirectTo(APP_BASE);
        }
        break;
    case 'signup':
        $title = 'Inscription';
        $description = 'Inscrivez-vous gratuitement pour devenir membre de notre communauté !';
        $keywords = 'inscription, signup, signin, s\'inscrire, devenir membre, créer un compte';
        $form = new SignupForm(APP_BASE);
        if ($form->validate($action, APP_BASE . $path, $_SERVER['REQUEST_METHOD'])) {
            $email = $form->getValue('email');
            $password = $form->getValue('password');
            $repeat_password = $form->getValue('repeat_password');
            if ($password !== $repeat_password) {
                $form->setError('repeat_password', 'Les mots de passe ne correspondent pas.');
            }
            if ($users->findByEmail($email)) {
                $form->setError('email', 'Cette adresse email est déjà utilisée.');
            }
            if ($form->hasErrors()) {
                break;
            }
            $user = new User();
            $user->setEmail($email);
            $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
            $users->save($user);
            $_SESSION['user'] = $user;
            redirectTo(APP_BASE);
        }
        break;
    default:
        $title = 'Rejoignez-nous !';
        $description = 'Créez votre compte pour accéder à toutes les fonctionnalités de notre site.';
        $keywords = 'inscription, signup, signin, s\'inscrire, devenir membre, créer un compte; se connecter, s\'identifier, s\'authentifier, accéder à un compte, page d\'accueil, demarrer';
        $motivation = '<p>Rejoignez-nous la communauté la plus simple et cool.</p>';
        $form = new JoinForm(APP_BASE);
        if ($form->validate()) {
            $action = $users->findByEmail($email = $form->getValue('email')) ? 'login' : 'signup';
            redirectTo("/?action=$action&email=$email");
        }
        break;
}

$view = view($path, [
    'pdo' => $pdo,
    'form' => $form,
    'title' => $title,
    'description' => $description,
    'keywords' => $keywords,
    'motivation' => $motivation ??= '',
]);

if (!isset($view)) {
    http_response_code(404);
    header('HTTP/1.0 404 Not Found');
    echo "404 Document $path Not Found";
    exit(1);
}
exit($view);
?>