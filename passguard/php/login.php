<?php
require_once __DIR__ . '/functions.php';
startSecureSession();

// Already logged in → go to vault
if (!empty($_SESSION['user_id'])) {
    header('Location: vault.php'); exit;
}

$error   = '';
$timeout = isset($_GET['timeout']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        $error = 'Please fill in all fields.';
    } else {
        $stmt = getDB()->prepare('SELECT id, password_hash FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']       = $user['id'];
            $_SESSION['username']      = $username;
            $_SESSION['last_activity'] = time();

            getDB()->prepare('UPDATE users SET last_login = NOW() WHERE id = ?')
                   ->execute([$user['id']]);
            auditLog($user['id'], 'LOGIN_OK');

            header('Location: vault.php'); exit;
        } else {
            $error = 'Invalid username or password.';
            if ($user) auditLog($user['id'], 'LOGIN_FAIL');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>PassGuard — Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=JetBrains+Mono:wght@300;400;500&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="../css/style.css"/>
</head>
<body>
<div class="auth-page">
  <div class="auth-card">
    <div class="auth-logo">
      <div class="auth-logo-mark">🔐</div>
      <h1>Pass<span>Guard</span></h1>
      <p>Your secure password vault</p>
    </div>

    <?php if ($timeout): ?>
      <div class="warning-box" style="color:var(--warn);border-color:var(--warn);">
        ⏱ Session expired after 15 minutes of inactivity.
      </div>
    <?php endif; ?>

    <?php if ($error): ?>
      <div class="warning-box" style="color:var(--danger);border-color:var(--danger);">
        ⚠️ <?= h($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST">
      <div class="field">
        <label for="username">Username</label>
        <input type="text" id="username" name="username"
               value="<?= h($_POST['username'] ?? '') ?>"
               placeholder="Enter your username" required autofocus/>
      </div>
      <div class="field">
        <label for="password">Master Password</label>
        <div class="input-icon-wrap">
          <input type="password" id="password" name="password"
                 placeholder="••••••••••••" required/>
          <span class="input-action" onclick="togglePw('password',this)">👁</span>
        </div>
      </div>
      <button type="submit" class="btn btn-primary btn-block" style="margin-top:4px;">
        Unlock Vault →
      </button>
    </form>

    <div class="auth-divider">Don't have an account?</div>
    <a href="signup.php" class="btn btn-secondary btn-block">Create Account</a>
  </div>
</div>
<script>
function togglePw(id, btn) {
  const input = document.getElementById(id);
  input.type  = input.type === 'password' ? 'text' : 'password';
  btn.textContent = input.type === 'password' ? '👁' : '🙈';
}
</script>
</body>
</html>
