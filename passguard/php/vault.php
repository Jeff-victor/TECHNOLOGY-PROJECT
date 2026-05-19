<?php
require_once __DIR__ . '/functions.php';
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

// Decrypt passwords
foreach ($creds as &$c) {
    $c['password'] = decryptPassword($c['password_enc']);
}
unset($c);

$total = count($creds);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>PassGuard — Vault</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=JetBrains+Mono:wght@300;400;500&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="../css/style.css"/>
</head>
<body>
<?= renderNav('vault') ?>
<div class="page-body">
  <div class="page-header">
    <h2>My Vault <span class="page-sub">— <?= $total ?> credential<?= $total !== 1 ? 's' : '' ?></span></h2>
    <a href="add.php" class="btn btn-primary">+ Add Credential</a>
  </div>

  <!-- Search & Filter -->
  <form method="GET" class="search-row">
    <input type="search" name="search" placeholder="🔍 Search credentials…"
           value="<?= h($search) ?>"/>
    <select name="category" onchange="this.form.submit()">
      <option value="">All categories</option>
      <?php foreach (['Social','Work','Finance','Other'] as $cat): ?>
        <option <?= $category === $cat ? 'selected' : '' ?>><?= $cat ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit" class="btn btn-secondary btn-sm">Search</button>
    <?php if ($search || $category): ?>
      <a href="vault.php" class="btn btn-secondary btn-sm">✕ Clear</a>
    <?php endif; ?>
  </form>

  <!-- Credentials Table -->
  <div class="section-card">
    <table class="cred-table">
      <thead>
        <tr>
          <th>Site</th>
          <th>Username</th>
          <th>Password</th>
          <th>Strength</th>
          <th>Category</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($creds)): ?>
          <tr>
            <td colspan="6" style="text-align:center;padding:48px;color:var(--muted);">
              🔑 No credentials found.
              <a href="add.php" style="color:var(--accent);">Add your first one →</a>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($creds as $c):
            $s = scorePassword($c['password']);
          ?>
          <tr>
            <td>
              <strong><?= h($c['site_name']) ?></strong>
              <?php if ($c['site_url']): ?>
                <br><a href="<?= h($c['site_url']) ?>" target="_blank"
                       style="font-size:11px;color:var(--muted);"><?= h($c['site_url']) ?></a>
              <?php endif; ?>
            </td>
            <td><?= h($c['username']) ?></td>
            <td>
              <span class="pw-cell" data-pw="<?= h($c['password']) ?>">••••••••</span>
              <button type="button" class="btn-icon" onclick="togglePwCell(this)" title="Show/hide">👁</button>
              <button type="button" class="btn-icon" onclick="copyPw(this)" title="Copy">📋</button>
            </td>
            <td>
              <span style="color:<?= $s['color'] ?>;font-weight:600;"><?= $s['label'] ?></span>
              <br><span style="font-size:11px;color:var(--muted);"><?= $s['score'] ?>/100</span>
            </td>
            <td><?= h($c['category'] ?? '—') ?></td>
            <td class="actions">
              <a href="edit.php?id=<?= h($c['id']) ?>" class="btn btn-secondary btn-sm">Edit</a>
              <a href="delete.php?id=<?= h($c['id']) ?>"
                 class="btn btn-sm"
                 style="background:var(--danger);color:#fff;border:none;"
                 onclick="return confirm('Delete this credential?')">Delete</a>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<script>
function togglePwCell(btn) {
  const cell    = btn.closest('td').querySelector('.pw-cell');
  const showing = cell.textContent !== '••••••••';
  cell.textContent    = showing ? '••••••••' : cell.dataset.pw;
  btn.textContent     = showing ? '👁' : '🙈';
}
function copyPw(btn) {
  const pw = btn.closest('td').querySelector('.pw-cell').dataset.pw;
  navigator.clipboard.writeText(pw).then(() => {
    btn.textContent = '✅';
    setTimeout(() => { btn.textContent = '📋'; }, 1500);
  });
}
</script>
</body>
</html>
