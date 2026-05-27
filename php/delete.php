<?php
require_once __DIR__ . '/smarty_init.php';
requireLogin();
$user = currentUser();

$id = $_GET['id'] ?? '';
if (!$id) { header('Location: vault.php'); exit; }

$stmt = getDB()->prepare('DELETE FROM credentials WHERE id = ? AND user_id = ?');
$stmt->execute([$id, $user['id']]);

if ($stmt->rowCount() > 0) {
    auditLog($user['id'], 'CRED_DELETE', $id);
}

header('Location: vault.php');
exit;
