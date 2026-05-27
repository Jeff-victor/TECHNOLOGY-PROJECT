<?php
require_once __DIR__ . '/smarty_init.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: vault.php'); exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password']         ?? '';
    $confirm  = $_POST['confirm']          ?? '';

    if (!$username || !$password || !$confirm) {
        $error = 'Please fill in all fields.';
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $error = 'Username must be between 3 and 50 characters.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (scorePassword($password)['score'] < 40) {
        $error = 'Please choose a stronger master password (at least Medium strength).';
    } else {
        $stmt = getDB()->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = 'Username already taken.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            getDB()->prepare('INSERT INTO users (username, password_hash) VALUES (?,?)')
                   ->execute([$username, $hash]);
            $userId = (int) getDB()->lastInsertId();

            session_regenerate_id(true);
            $_SESSION['user_id']       = $userId;
            $_SESSION['username']      = $username;
            $_SESSION['is_admin']      = false;
            $_SESSION['last_activity'] = time();
            auditLog($userId, 'SIGNUP');

            header('Location: vault.php'); exit;
        }
    }
}

$pw    = $_POST['password'] ?? '';
$score = $pw ? scorePassword($pw) : null;

$smarty->assign('error',          $error);
$smarty->assign('username_value', $_POST['username'] ?? '');
$smarty->assign('score',          $score);
$smarty->display('signup.tpl');
