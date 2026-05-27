<?php
require_once __DIR__ . '/smarty_init.php';
requireLogin();
$user  = currentUser();
$error = '';

$id = $_GET['id'] ?? $_POST['id'] ?? '';
if (!$id) { header('Location: vault.php'); exit; }

// Load existing credential
$stmt = getDB()->prepare(
    'SELECT c.*, cat.name AS category
     FROM   credentials c
     LEFT   JOIN categories cat ON cat.id = c.category_id
     WHERE  c.id = ? AND c.user_id = ?'
);
$stmt->execute([$id, $user['id']]);
$cred = $stmt->fetch();
if (!$cred) { header('Location: vault.php'); exit; }

$cred['password'] = decryptPassword($cred['password_enc']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site     = trim($_POST['site_name'] ?? '');
    $url      = trim($_POST['site_url']  ?? '');
    $username = trim($_POST['username']  ?? '');
    $newPw    = $_POST['password']          ?? '';
    $category = trim($_POST['category']  ?? '');
    $notes    = trim($_POST['notes']     ?? '');

    if (!$site || !$username) {
        $error = 'Site name and username are required.';
    } else {
        $encPw      = $newPw ? encryptPassword($newPw) : $cred['password_enc'];
        $score      = $newPw ? scorePassword($newPw)['score'] : $cred['strength_score'];
        $categoryId = getCategoryId($category);

        getDB()->prepare(
            'UPDATE credentials
             SET site_name=?, site_url=?, username=?, password_enc=?,
                 category_id=?, notes=?, strength_score=?
             WHERE id=? AND user_id=?'
        )->execute([$site, $url ?: null, $username, $encPw,
                    $categoryId, $notes ?: null, $score, $id, $user['id']]);

        auditLog($user['id'], 'CRED_EDIT', $id);
        header('Location: vault.php'); exit;
    }
}

$smarty->assign('error',   $error);
$smarty->assign('cred_id', $id);
$smarty->assign('v', [
    'site_name' => $_POST['site_name'] ?? $cred['site_name'],
    'site_url'  => $_POST['site_url']  ?? $cred['site_url'],
    'username'  => $_POST['username']  ?? $cred['username'],
    'category'  => $_POST['category']  ?? $cred['category'],
    'notes'     => $_POST['notes']     ?? $cred['notes'],
]);
$smarty->display('edit.tpl');
