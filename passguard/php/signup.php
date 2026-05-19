<?php
require_once __DIR__ . '/functions.php';
startSecureSession();

if (!empty($_SESSION['user_id'])) {
    header('Location: vault.php'); exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password']         ?? '';
    $confirm  = $_POST['confirm']          ?? '';

    if (!$username || !$password || !$confirm) {
        $error = 'Please fill in all fields.';
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $error = 'Username must be between 3 and 50 characters.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (scorePassword($password)['score'] < 40) {
        $error = 'Please choose a stronger master password (at least Medium strength).';
    } else {
        $stmt = getDB()->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = 'Username already taken.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            getDB()->prepare('INSERT INTO users (username, password_hash) VALUES (?,?)')
                   ->execute([$username, $hash]);
            $userId = (int) getDB()->lastInsertId();

            session_regenerate_id(true);
            $_SESSION['user_id']       = $userId;
            $_SESSION['username']      = $username;
            $_SESSION['last_activity'] = time();
            auditLog($userId, 'SIGNUP');

            header('Location: vault.php'); exit;
        }
    }
}

$pw    = $_POST['password'] ?? '';
$score = $pw ? scorePassword($pw) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>PassGuard — Sign Up</title>
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
      <p>Create your secure vault</p>
    </div>

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
               placeholder="Choose a username" required autofocus/>
      </div>
      <div class="field">
        <label for="password">Master Password</label>
        <div class="input-icon-wrap">
          <input type="password" id="password" name="password"
                 placeholder="••••••••" required oninput="liveStrength(this.value)"/>
          <span class="input-action" onclick="togglePw('password',this)">👁</span>
        </div>
        <div class="strength-bar">
          <div class="strength-fill" id="strength-fill"
               style="width:<?= $score ? $score['score'] : 0 ?>%;
                      background:<?= $score ? $score['color'] : 'var(--muted)' ?>;"></div>
        </div>
        <span id="strength-label" style="font-size:11px;color:var(--muted);">
          <?= $score ? h($score['label']) : 'Enter a password' ?>
        </span>
      </div>
      <div class="field">
        <label for="confirm">Confirm Master Password</label>
        <div class="input-icon-wrap">
          <input type="password" id="confirm" name="confirm"
                 placeholder="••••••••" required/>
          <span class="input-action" onclick="togglePw('confirm',this)">👁</span>
        </div>
      </div>
      <div class="warning-box">
        ⚠️ Your master password cannot be recovered if lost. Store it somewhere safe.
      </div>
      <button type="submit" class="btn btn-primary btn-block">Create Account →</button>
    </form>

    <div class="auth-divider">Already have an account?</div>
    <a href="login.php" class="btn btn-secondary btn-block">Sign In</a>
  </div>
</div>
<script src="../js/strength.js"></script>
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
  document.getElementById('strength-label').textContent     = pw ? label : 'Enter a password';
}
</script>
</body>
</html>
