<?php
require_once __DIR__ . '/smarty_init.php';
requireLogin();
$user = currentUser();

$search   = trim($_GET['search']   ?? '');
$category = trim($_GET['category'] ?? '');

$sql    = 'SELECT c.id, c.site_name, c.site_url, c.username, c.password_enc,
                  cat.name AS category, c.notes, c.strength_score
           FROM   credentials c
           LEFT   JOIN categories cat ON cat.id = c.category_id
           WHERE  c.user_id = ?';
$params = [$user['id']];

if ($search) {
    $sql    .= ' AND (c.site_name LIKE ? OR c.username LIKE ?)';
    $params[] = '%'.$search.'%';
    $params[] = '%'.$search.'%';
}
if ($category) {
    $sql    .= ' AND cat.name = ?';
    $params[] = $category;
}
$sql .= ' ORDER BY c.site_name ASC';

$stmt = getDB()->prepare($sql);
$stmt->execute($params);
$creds = $stmt->fetchAll();

// Decrypt passwords and compute strength for display
foreach ($creds as &$c) {
    $c['password'] = decryptPassword($c['password_enc']);
    $c['strength'] = scorePassword($c['password']);
}
unset($c);

$smarty->assign('creds',    $creds);
$smarty->assign('search',   $search);
$smarty->assign('category', $category);
$smarty->assign('total',    count($creds));
$smarty->display('vault.tpl');
