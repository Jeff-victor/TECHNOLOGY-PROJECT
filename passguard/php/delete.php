<?php
require_once __DIR__ . '/functions.php';
requireLogin();
$user = currentUser();

$id = $_GET['id'] ?? '';
if (!$id) { header('Location: vault.php'); exit; }

$stmt = getDB()->prepare('SELECT id FROM credentials WHERE id = ? AND user_id = ?');
$stmt->execute([$id, $user['id']]);
if ($stmt->fetch()) {
    getDB()->prepare('DELETE FROM credentials WHERE id = ? AND user_id = ?')
           ->execute([$id, $user['id']]);
    auditLog($user['id'], 'CRED_DELETE', $id);
}

header('Location: vault.php'); exit;
