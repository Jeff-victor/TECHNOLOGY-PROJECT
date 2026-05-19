<?php
require_once __DIR__ . '/functions.php';
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

// Pre-fill from DB (or from failed POST)
$v = [
    'site_name' => $_POST['site_name'] ?? $cred['site_name'],
    'site_url'  => $_POST['site_url']  ?? $cred['site_url'],
    'username'  => $_POST['username']  ?? $cred['username'],
    'category'  => $_POST['category']  ?? $cred['category'],
    'notes'     => $_POST['notes']     ?? $cred['notes'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>PassGuard — Edit Credential</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=JetBrains+Mono:wght@300;400;500&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="../css/style.css"/>
</head>
<body>
<?= renderNav('vault') ?>
<div class="page-body">
  <div class="page-header">
    <h2>Edit Credential</h2>
    <a href="vault.php" class="btn btn-secondary btn-sm">← Back to Vault</a>
  </div>

  <?php if ($error): ?>
    <div class="warning-box" style="color:var(--danger);border-color:var(--danger);max-width:560px;">
      ⚠️ <?= h($error) ?>
    </div>
  <?php endif; ?>

  <div class="form-card">
    <form method="POST">
      <input type="hidden" name="id" value="<?= h($id) ?>"/>

      <div class="field">
        <label for="site_name">Site Name</label>
        <input type="text" id="site_name" name="site_name"
               value="<?= h($v['site_name']) ?>" required/>
      </div>
      <div class="field">
        <label for="site_url">Website URL</label>
        <input type="url" id="site_url" name="site_url"
               value="<?= h($v['site_url'] ?? '') ?>"/>
      </div>
      <div class="field">
        <label for="username">Username / Email</label>
        <input type="text" id="username" name="username"
               value="<?= h($v['username']) ?>" required/>
      </div>
      <div class="field">
        <label for="category">Category</label>
        <select id="category" name="category">
          <option value="">Select a category…</option>
          <?php foreach (['Social','Work','Finance','Other'] as $cat): ?>
            <option <?= $v['category'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Password Generator -->
      <div class="generator-box">
        <h4>🎲 Password Generator</h4>
        <div class="gen-output" id="gen-output">Click Generate →</div>
        <div class="gen-options">
          <label class="checkbox-label"><input type="checkbox" id="gen-upper"   checked/> Uppercase</label>
          <label class="checkbox-label"><input type="checkbox" id="gen-lower"   checked/> Lowercase</label>
          <label class="checkbox-label"><input type="checkbox" id="gen-numbers" checked/> Numbers</label>
          <label class="checkbox-label"><input type="checkbox" id="gen-symbols" checked/> Symbols</label>
        </div>
        <div class="gen-length-row">
          <label>Length: <strong id="gen-length-display">16</strong></label>
          <input type="range" id="gen-length" min="8" max="64" value="16"/>
          <button type="button" class="btn btn-primary btn-sm">↻ Generate</button>
          <button type="button" class="btn btn-secondary btn-sm">Use this →</button>
        </div>
      </div>

      <div class="field">
        <label for="password">New Password <span style="color:var(--muted);font-size:11px;">(leave blank to keep current)</span></label>
        <div class="input-icon-wrap">
          <input type="password" id="password" name="password"
                 placeholder="Leave blank to keep current password"
                 oninput="liveStrength(this.value)"/>
          <span class="input-action" onclick="togglePw('password',this)">👁</span>
        </div>
        <div class="strength-bar">
          <div class="strength-fill" id="strength-fill" style="width:0%;"></div>
        </div>
        <div style="display:flex;justify-content:space-between;margin-top:4px;">
          <span id="strength-label" style="font-size:11px;color:var(--muted);">Leave blank to keep current</span>
          <span id="strength-score" style="font-size:11px;color:var(--muted);"></span>
        </div>
      </div>

      <div class="field">
        <label for="notes">Notes (optional)</label>
        <textarea id="notes" name="notes" rows="3"
                  style="resize:vertical;"><?= h($v['notes'] ?? '') ?></textarea>
      </div>

      <div style="display:flex;gap:12px;margin-top:8px;">
        <button type="submit" class="btn btn-primary" style="flex:1;">Save Changes</button>
        <a href="vault.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
<script src="../js/strength.js"></script>
<script src="../js/generator.js"></script>
<script>
function togglePw(id, btn) {
  const input = document.getElementById(id);
  input.type  = input.type === 'password' ? 'text' : 'password';
  btn.textContent = input.type === 'password' ? '👁' : '🙈';
}
function liveStrength(pw) {
  const { score, label, color } = scorePassword(pw);
  document.getElementById('strength-fill').style.width      = score + '%';
  document.getElementById('strength-fill').style.background = color;
  document.getElementById('strength-label').textContent     = pw ? label : 'Leave blank to keep current';
  document.getElementById('strength-score').textContent     = pw ? score + '/100' : '';
}
document.addEventListener('DOMContentLoaded', initGenerator);
</script>
</body>
</html>
