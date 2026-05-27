<?php
require_once __DIR__ . '/smarty_init.php';
requireAdmin();
$pdo = getDB();

$filterAction = trim($_GET['action'] ?? '');
$filterUser   = trim($_GET['user']   ?? '');
$filterIp     = trim($_GET['ip']     ?? '');
$page         = max(1, (int)($_GET['page'] ?? 1));
$perPage      = 25;
$offset       = ($page - 1) * $perPage;

$where  = [];
$params = [];
if ($filterAction) { $where[] = 'a.action = ?';       $params[] = $filterAction; }
if ($filterUser)   { $where[] = 'u.username LIKE ?';   $params[] = '%'.$filterUser.'%'; }
if ($filterIp)     { $where[] = 'a.ip_address LIKE ?'; $params[] = '%'.$filterIp.'%'; }
$whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Count
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM audit_log a LEFT JOIN users u ON u.id = a.user_id $whereSQL");
$countStmt->execute($params);
$totalRows  = (int)$countStmt->fetchColumn();
$totalPages = max(1, (int)ceil($totalRows / $perPage));

// Fetch
$stmt = $pdo->prepare(
    "SELECT a.*, u.username FROM audit_log a
     LEFT JOIN users u ON u.id = a.user_id
     $whereSQL ORDER BY a.created_at DESC LIMIT $perPage OFFSET $offset"
);
$stmt->execute($params);
$logs = $stmt->fetchAll();

// Distinct actions
$actions = $pdo->query('SELECT DISTINCT action FROM audit_log ORDER BY action')->fetchAll(PDO::FETCH_COLUMN);

// Stats
$totalEvents = (int)$pdo->query('SELECT COUNT(*) FROM audit_log')->fetchColumn();
$loginFails  = (int)$pdo->query("SELECT COUNT(*) FROM audit_log WHERE action = 'LOGIN_FAIL'")->fetchColumn();
$todayEvents = (int)$pdo->query("SELECT COUNT(*) FROM audit_log WHERE DATE(created_at) = CURDATE()")->fetchColumn();

$actionColors = [
    'LOGIN_OK'=>'var(--strong)','LOGIN_FAIL'=>'var(--danger)','SIGNUP'=>'var(--accent2)',
    'LOGOUT'=>'var(--muted)','CRED_ADD'=>'var(--accent)','CRED_EDIT'=>'var(--medium)',
    'CRED_DELETE'=>'var(--danger)','ADMIN_TOGGLE_USER'=>'var(--accent2)',
    'ADMIN_PROMOTE'=>'var(--accent2)','ADMIN_DEMOTE'=>'var(--medium)',
    'ADMIN_DELETE_USER'=>'var(--danger)',
];
$actionIcons = [
    'LOGIN_OK'=>'✅','LOGIN_FAIL'=>'❌','SIGNUP'=>'🆕','LOGOUT'=>'🔒',
    'CRED_ADD'=>'➕','CRED_EDIT'=>'✏️','CRED_DELETE'=>'🗑',
    'ADMIN_TOGGLE_USER'=>'🔄','ADMIN_PROMOTE'=>'⬆','ADMIN_DEMOTE'=>'⬇',
    'ADMIN_DELETE_USER'=>'💀',
];

foreach ($logs as &$log) {
    $log['actionColor'] = $actionColors[$log['action']] ?? 'var(--text)';
    $log['icon']        = $actionIcons[$log['action']]  ?? '•';
}
unset($log);

// Pagination URLs
function buildQuery(array $overrides): string {
    $params = array_merge($_GET, $overrides);
    return 'admin_audit.php?' . http_build_query($params);
}

$smarty->assign([
    'filterAction' => $filterAction, 'filterUser' => $filterUser, 'filterIp' => $filterIp,
    'page'         => $page,         'totalPages' => $totalPages, 'totalRows' => $totalRows,
    'logs'         => $logs,         'actions'    => $actions,
    'totalEvents'  => $totalEvents,  'loginFails' => $loginFails, 'todayEvents' => $todayEvents,
    'today'        => date('M j, Y'),
    'prevUrl'      => buildQuery(['page' => $page - 1]),
    'nextUrl'      => buildQuery(['page' => $page + 1]),
]);
$smarty->display('admin_audit.tpl');
