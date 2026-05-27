<?php
require_once __DIR__ . '/smarty_init.php';
requireLogin();
$user = currentUser();
$pdo  = getDB();

// Stats
$stmt = $pdo->prepare(
    'SELECT COUNT(*) AS total, SUM(strength_score < 40) AS weak,
            ROUND(AVG(strength_score),0) AS avg_score
     FROM credentials WHERE user_id = ?'
);
$stmt->execute([$user['id']]);
$stats = $stmt->fetch();

// Reused
$stmt2 = $pdo->prepare(
    'SELECT COUNT(*) AS reused FROM credentials
     WHERE user_id = ? AND password_enc IN (
         SELECT password_enc FROM credentials WHERE user_id = ?
         GROUP BY password_enc HAVING COUNT(*) > 1)'
);
$stmt2->execute([$user['id'], $user['id']]);
$reused = (int)$stmt2->fetchColumn();

$total    = (int)$stats['total'];
$weak     = (int)$stats['weak'];
$avg      = (float)($stats['avg_score'] ?? 0);
$secScore = $total > 0
    ? max(0, (int)round($avg - ($weak/$total*30) - ($reused/$total*20)))
    : 0;

// Distribution
$stmt3 = $pdo->prepare(
    'SELECT SUM(strength_score < 40) AS weak,
            SUM(strength_score BETWEEN 40 AND 69) AS medium,
            SUM(strength_score >= 70) AS strong
     FROM credentials WHERE user_id = ?'
);
$stmt3->execute([$user['id']]);
$dist = $stmt3->fetch();
$dist = [
    'weak'   => (int)($dist['weak']   ?? 0),
    'medium' => (int)($dist['medium'] ?? 0),
    'strong' => (int)($dist['strong'] ?? 0),
];

// Bar chart data
$maxBar = max(1, $dist['weak'], $dist['medium'], $dist['strong']);
$bars = [
    ['val' => $dist['weak'],   'color' => 'var(--weak)',   'lbl' => 'Weak',   'pct' => round($dist['weak']/$maxBar*100)],
    ['val' => $dist['medium'], 'color' => 'var(--medium)', 'lbl' => 'Medium', 'pct' => round($dist['medium']/$maxBar*100)],
    ['val' => $dist['strong'], 'color' => 'var(--strong)', 'lbl' => 'Strong', 'pct' => round($dist['strong']/$maxBar*100)],
];

// Categories
$stmt4 = $pdo->prepare(
    'SELECT COALESCE(cat.name,"Other") AS category, COUNT(*) AS cnt
     FROM credentials c
     LEFT JOIN categories cat ON cat.id = c.category_id
     WHERE c.user_id = ? GROUP BY category ORDER BY cnt DESC'
);
$stmt4->execute([$user['id']]);
$categories = $stmt4->fetchAll();
$catMap = [];
foreach ($categories as $c) $catMap[$c['category']] = (int)$c['cnt'];
$maxCat = $catMap ? max(array_values($catMap)) : 1;

$catBars = [];
foreach (['Social','Work','Finance','Other'] as $cat) {
    $cnt = $catMap[$cat] ?? 0;
    $catBars[] = ['name' => $cat, 'cnt' => $cnt, 'pct' => $maxCat > 0 ? round($cnt/$maxCat*100) : 0];
}

// Issues
$stmt5 = $pdo->prepare(
    'SELECT c.id, c.site_name, c.username, c.strength_score,
            CASE WHEN c.strength_score < 40 THEN "Weak password"
                 ELSE "Reused password" END AS issue_reason
     FROM credentials c
     WHERE c.user_id = ? AND (
         c.strength_score < 40 OR c.password_enc IN (
             SELECT password_enc FROM credentials WHERE user_id = ?
             GROUP BY password_enc HAVING COUNT(*) > 1))
     ORDER BY c.strength_score ASC'
);
$stmt5->execute([$user['id'], $user['id']]);
$issues = $stmt5->fetchAll();

foreach ($issues as &$issue) {
    $s = (int)$issue['strength_score'];
    $issue['color'] = $s >= 70 ? 'var(--strong)' : ($s >= 40 ? 'var(--medium)' : 'var(--weak)');
    $issue['label'] = $s >= 70 ? 'Strong' : ($s >= 40 ? 'Medium' : 'Weak');
}
unset($issue);

// Score ring
$circ      = 2 * M_PI * 54;
$offset    = round($circ - ($circ * $secScore / 100), 2);
$ringColor = $secScore >= 70 ? 'var(--strong)' : ($secScore >= 40 ? 'var(--medium)' : 'var(--weak)');

$smarty->assign('total',     $total);
$smarty->assign('weak',      $weak);
$smarty->assign('reused',    $reused);
$smarty->assign('secScore',  $secScore);
$smarty->assign('dist',      $dist);
$smarty->assign('bars',      $bars);
$smarty->assign('catBars',   $catBars);
$smarty->assign('issues',    $issues);
$smarty->assign('circ',      round($circ, 2));
$smarty->assign('offset',    $offset);
$smarty->assign('ringColor', $ringColor);
$smarty->display('dashboard.tpl');
