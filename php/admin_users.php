<?php
require_once __DIR__ . '/smarty_init.php';
requireAdmin();
$pdo     = getDB();
$current = currentUser();
$msg     = '';
$msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action   = $_POST['action']  ?? '';
    $targetId = (int)($_POST['user_id'] ?? 0);

    if ($targetId === $current['id'] && in_array($action, ['toggle', 'delete'])) {
        $msg = 'You cannot disable or delete your own account.';
        $msgType = 'danger';
    } elseif ($targetId > 0) {
        switch ($action) {
            case 'toggle':
                $pdo->prepare('UPDATE users SET is_active = NOT is_active WHERE id = ?')
                    ->execute([$targetId]);
                auditLog($current['id'], 'ADMIN_TOGGLE_USER', (string)$targetId);
                $msg = 'User status updated.'; $msgType = 'success';
                break;
            case 'promote':
                $pdo->prepare('UPDATE users SET is_admin = 1 WHERE id = ?')
                    ->execute([$targetId]);
                auditLog($current['id'], 'ADMIN_PROMOTE', (string)$targetId);
                $msg = 'User promoted to admin.'; $msgType = 'success';
                break;
            case 'demote':
                if ($targetId === $current['id']) {
                    $msg = 'You cannot demote yourself.'; $msgType = 'danger';
                } else {
                    $pdo->prepare('UPDATE users SET is_admin = 0 WHERE id = ?')
                        ->execute([$targetId]);
                    auditLog($current['id'], 'ADMIN_DEMOTE', (string)$targetId);
                    $msg = 'Admin rights removed.'; $msgType = 'success';
                }
                break;
            case 'delete':
                $pdo->prepare('DELETE FROM credentials WHERE user_id = ?')->execute([$targetId]);
                $pdo->prepare('DELETE FROM audit_log WHERE user_id = ?')->execute([$targetId]);
                $pdo->prepare('DELETE FROM users WHERE id = ?')->execute([$targetId]);
                auditLog($current['id'], 'ADMIN_DELETE_USER', (string)$targetId);
                $msg = 'User and all their data deleted.'; $msgType = 'success';
                break;
        }
    }
}

$search = trim($_GET['search'] ?? '');
$sql    = 'SELECT u.*, COUNT(c.id) AS cred_count, ROUND(AVG(c.strength_score),0) AS avg_score
           FROM users u LEFT JOIN credentials c ON c.user_id = u.id';
$params = [];
if ($search) {
    $sql    .= ' WHERE u.username LIKE ?';
    $params[] = '%'.$search.'%';
}
$sql .= ' GROUP BY u.id ORDER BY u.created_at DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll();

foreach ($users as &$u) {
    $a = (int)($u['avg_score'] ?? 0);
    $u['color']      = $a >= 70 ? 'var(--strong)' : ($a >= 40 ? 'var(--medium)' : 'var(--weak)');
    $u['cred_count'] = (int)$u['cred_count'];
    $u['avg_score']  = $a;
}
unset($u);

$smarty->assign([
    'msg'       => $msg,
    'msgType'   => $msgType,
    'search'    => $search,
    'users'     => $users,
    'currentId' => $current['id'],
]);
$smarty->display('admin_users.tpl');
