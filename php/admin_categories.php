<?php
require_once __DIR__ . '/smarty_init.php';
requireAdmin();
$pdo     = getDB();
$current = currentUser();
$msg     = '';
$msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'add':
            $name = trim($_POST['name'] ?? '');
            if (!$name) {
                $msg = 'Category name cannot be empty.'; $msgType = 'danger';
            } else {
                $check = $pdo->prepare('SELECT id FROM categories WHERE name = ?');
                $check->execute([$name]);
                if ($check->fetch()) {
                    $msg = 'A category with that name already exists.'; $msgType = 'danger';
                } else {
                    $pdo->prepare('INSERT INTO categories (name) VALUES (?)')->execute([$name]);
                    auditLog($current['id'], 'ADMIN_CAT_ADD');
                    $msg = "Category \"$name\" created."; $msgType = 'success';
                }
            }
            break;

        case 'rename':
            $catId   = (int)($_POST['cat_id'] ?? 0);
            $newName = trim($_POST['name'] ?? '');
            if (!$newName || !$catId) {
                $msg = 'Please provide a new name.'; $msgType = 'danger';
            } else {
                $check = $pdo->prepare('SELECT id FROM categories WHERE name = ? AND id != ?');
                $check->execute([$newName, $catId]);
                if ($check->fetch()) {
                    $msg = 'A category with that name already exists.'; $msgType = 'danger';
                } else {
                    $pdo->prepare('UPDATE categories SET name = ? WHERE id = ?')->execute([$newName, $catId]);
                    auditLog($current['id'], 'ADMIN_CAT_RENAME');
                    $msg = 'Category renamed.'; $msgType = 'success';
                }
            }
            break;

        case 'delete':
            $catId = (int)($_POST['cat_id'] ?? 0);
            if ($catId) {
                $pdo->prepare('UPDATE credentials SET category_id = NULL WHERE category_id = ?')
                    ->execute([$catId]);
                $pdo->prepare('DELETE FROM categories WHERE id = ?')->execute([$catId]);
                auditLog($current['id'], 'ADMIN_CAT_DELETE');
                $msg = 'Category deleted. Affected credentials moved to "Uncategorized".'; $msgType = 'success';
            }
            break;
    }
}

$categories = $pdo->query(
    'SELECT cat.id, cat.name, COUNT(c.id) AS usage_count
     FROM categories cat LEFT JOIN credentials c ON c.category_id = cat.id
     GROUP BY cat.id ORDER BY cat.name ASC'
)->fetchAll();

$uncategorized = (int)$pdo->query(
    'SELECT COUNT(*) FROM credentials WHERE category_id IS NULL'
)->fetchColumn();

$smarty->assign([
    'msg'           => $msg,
    'msgType'       => $msgType,
    'categories'    => $categories,
    'uncategorized' => $uncategorized,
]);
$smarty->display('admin_categories.tpl');
