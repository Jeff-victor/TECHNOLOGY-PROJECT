<?php
require_once __DIR__ . '/smarty_init.php';
requireAdmin();
$pdo = getDB();

$totalUsers  = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$activeUsers = (int)$pdo->query('SELECT COUNT(*) FROM users WHERE is_active = 1')->fetchColumn();
$totalCreds  = (int)$pdo->query('SELECT COUNT(*) FROM credentials')->fetchColumn();
$avgScore    = (float)$pdo->query('SELECT ROUND(AVG(strength_score),1) FROM credentials')->fetchColumn();

$weakCreds   = (int)$pdo->query('SELECT COUNT(*) FROM credentials WHERE strength_score < 40')->fetchColumn();
$mediumCreds = (int)$pdo->query('SELECT COUNT(*) FROM credentials WHERE strength_score BETWEEN 40 AND 69')->fetchColumn();
$strongCreds = (int)$pdo->query('SELECT COUNT(*) FROM credentials WHERE strength_score >= 70')->fetchColumn();

// Per-user vault health
$userStats = $pdo->query(
    'SELECT u.id, u.username, u.is_active, u.last_login,
            COUNT(c.id) AS cred_count,
            ROUND(AVG(c.strength_score),0) AS avg_score,
            SUM(c.strength_score < 40) AS weak_count
     FROM users u LEFT JOIN credentials c ON c.user_id = u.id
     GROUP BY u.id ORDER BY avg_score ASC, cred_count DESC'
)->fetchAll();

foreach ($userStats as &$u) {
    $a = (int)($u['avg_score'] ?? 0);
    $u['color'] = $a >= 70 ? 'var(--strong)' : ($a >= 40 ? 'var(--medium)' : 'var(--weak)');
    $u['weak_count'] = (int)($u['weak_count'] ?? 0);
    $u['cred_count'] = (int)$u['cred_count'];
}
unset($u);

// Recent audit
$recentAudit = $pdo->query(
    'SELECT a.*, u.username FROM audit_log a
     LEFT JOIN users u ON u.id = a.user_id
     ORDER BY a.created_at DESC LIMIT 10'
)->fetchAll();

$actionColors = [
    'LOGIN_OK'=>'var(--strong)','LOGIN_FAIL'=>'var(--danger)','SIGNUP'=>'var(--accent2)',
    'LOGOUT'=>'var(--muted)','CRED_ADD'=>'var(--accent)','CRED_EDIT'=>'var(--medium)',
    'CRED_DELETE'=>'var(--danger)','ADMIN_TOGGLE_USER'=>'var(--accent2)',
    'ADMIN_PROMOTE'=>'var(--accent2)','ADMIN_DEMOTE'=>'var(--medium)',
    'ADMIN_DELETE_USER'=>'var(--danger)',
];
foreach ($recentAudit as &$a) {
    $a['actionColor'] = $actionColors[$a['action']] ?? 'var(--text)';
}
unset($a);

// Category distribution
$catDist = $pdo->query(
    'SELECT COALESCE(cat.name,"Uncategorized") AS category, COUNT(*) AS cnt
     FROM credentials c LEFT JOIN categories cat ON cat.id = c.category_id
     GROUP BY category ORDER BY cnt DESC'
)->fetchAll();
$maxCat = $catDist ? max(array_column($catDist, 'cnt')) : 1;
foreach ($catDist as &$cd) {
    $cd['pct'] = $maxCat > 0 ? round($cd['cnt']/$maxCat*100) : 0;
}
unset($cd);

// Bar chart
$maxBar = max(1, $weakCreds, $mediumCreds, $strongCreds);
$bars = [
    ['val'=>$weakCreds,   'color'=>'var(--weak)',   'lbl'=>'Weak',   'pct'=>round($weakCreds/$maxBar*100)],
    ['val'=>$mediumCreds, 'color'=>'var(--medium)', 'lbl'=>'Medium', 'pct'=>round($mediumCreds/$maxBar*100)],
    ['val'=>$strongCreds, 'color'=>'var(--strong)', 'lbl'=>'Strong', 'pct'=>round($strongCreds/$maxBar*100)],
];

// Score ring
$globalScore = $totalCreds > 0 ? (int)round($avgScore) : 0;
$circ   = round(2 * M_PI * 54, 2);
$offset = round($circ - ($circ * $globalScore / 100), 2);
$ringColor = $globalScore >= 70 ? 'var(--strong)' : ($globalScore >= 40 ? 'var(--medium)' : 'var(--weak)');
$avgColor  = $avgScore >= 70 ? 'var(--strong)' : ($avgScore >= 40 ? 'var(--medium)' : 'var(--weak)');

$smarty->assign([
    'totalUsers'  => $totalUsers,  'activeUsers'  => $activeUsers,
    'totalCreds'  => $totalCreds,  'avgScore'     => $avgScore ?: 0,
    'avgColor'    => $avgColor,
    'weakCreds'   => $weakCreds,   'mediumCreds'  => $mediumCreds,
    'strongCreds' => $strongCreds,
    'userStats'   => $userStats,   'recentAudit'  => $recentAudit,
    'catDist'     => $catDist,     'bars'         => $bars,
    'globalScore' => $globalScore, 'circ'         => $circ,
    'offset'      => $offset,      'ringColor'    => $ringColor,
]);
$smarty->display('admin_dashboard.tpl');
