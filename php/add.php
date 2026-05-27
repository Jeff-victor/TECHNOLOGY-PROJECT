<?php
require_once __DIR__ . '/smarty_init.php';
requireLogin();
$user  = currentUser();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site     = trim($_POST['site_name'] ?? '');
    $url      = trim($_POST['site_url']  ?? '');
    $username = trim($_POST['username']  ?? '');
    $password = $_POST['password']          ?? '';
    $category = trim($_POST['category']  ?? '');
    $notes    = trim($_POST['notes']     ?? '');

    if (!$site || !$username || !$password) {
        $error = 'Site name, username, and password are required.';
    } else {
        $score      = scorePassword($password)['score'];
        $encPw      = encryptPassword($password);
        $newId      = genId();
        $categoryId = getCategoryId($category);

        getDB()->prepare(
            'INSERT INTO credentials
               (id, user_id, site_name, site_url, username, password_enc,
                category_id, notes, strength_score)
             VALUES (?,?,?,?,?,?,?,?,?)'
        )->execute([$newId, $user['id'], $site, $url ?: null, $username,
                    $encPw, $categoryId, $notes ?: null, $score]);

        auditLog($user['id'], 'CRED_ADD', $newId);
        header('Location: vault.php'); exit;
    }
}

$smarty->assign('error', $error);
$smarty->assign('post', [
    'site_name' => $_POST['site_name'] ?? '',
    'site_url'  => $_POST['site_url']  ?? '',
    'username'  => $_POST['username']  ?? '',
    'category'  => $_POST['category']  ?? '',
    'notes'     => $_POST['notes']     ?? '',
]);
$smarty->display('add.tpl');
