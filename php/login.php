<?php
require_once __DIR__ . '/smarty_init.php';

// Already logged in → go to vault
if (!empty($_SESSION['user_id'])) {
    header('Location: vault.php'); exit;
}

$error   = '';
$timeout = isset($_GET['timeout']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        $error = 'Please fill in all fields.';
    } else {
        $stmt = getDB()->prepare('SELECT id, password_hash, is_admin, is_active FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && !$user['is_active']) {
            $error = 'This account has been disabled. Contact an administrator.';
        } elseif ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']       = $user['id'];
            $_SESSION['username']      = $username;
            $_SESSION['is_admin']      = (bool)$user['is_admin'];
            $_SESSION['last_activity'] = time();

            getDB()->prepare('UPDATE users SET last_login = NOW() WHERE id = ?')
                   ->execute([$user['id']]);
            auditLog($user['id'], 'LOGIN_OK');

            header('Location: vault.php'); exit;
        } else {
            $error = 'Invalid username or password.';
            if ($user) auditLog($user['id'], 'LOGIN_FAIL');
        }
    }
}

$smarty->assign('error',          $error);
$smarty->assign('timeout',        $timeout);
$smarty->assign('username_value', $_POST['username'] ?? '');
$smarty->display('login.tpl');
