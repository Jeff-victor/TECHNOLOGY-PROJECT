<?php
require_once __DIR__ . '/database.php';

// ── Session ───────────────────────────────────────────────────────────────────

function startSecureSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'secure'   => false,   // set true on HTTPS
            'httponly' => true,
            'samesite' => 'Strict',
        ]);
        session_start();
    }
}

function requireLogin(): void {
    startSecureSession();
    if (empty($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
    // Session timeout: 15 minutes
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 900) {
        session_destroy();
        header('Location: login.php?timeout=1');
        exit;
    }
    $_SESSION['last_activity'] = time();
}

function currentUser(): array {
    return [
        'id'       => $_SESSION['user_id']       ?? 0,
        'username' => $_SESSION['username']       ?? '',
    ];
}

// ── Password scoring (PHP version of strength.js) ────────────────────────────

function scorePassword(string $pw): array {
    $common = ['123456','password','123456789','12345678','12345','1234567',
               'qwerty','abc123','111111','123123','admin','letmein','welcome',
               'monkey','dragon','master','sunshine','princess','shadow',
               'superman','football','iloveyou','trustno1','passw0rd','hello'];

    if (!$pw) return ['score' => 0, 'label' => 'No password', 'color' => 'var(--muted)'];

    $score = 0;
    if (strlen($pw) >= 6)  $score += 10;
    if (strlen($pw) >= 8)  $score += 10;
    if (strlen($pw) >= 12) $score += 10;
    if (strlen($pw) >= 16) $score += 10;
    if (preg_match('/[a-z]/', $pw))        $score += 10;
    if (preg_match('/[A-Z]/', $pw))        $score += 10;
    if (preg_match('/[0-9]/', $pw))        $score += 10;
    if (preg_match('/[^a-zA-Z0-9]/', $pw)) $score += 10;

    $unique = count(array_unique(str_split($pw)));
    $score += (int) round(($unique / strlen($pw)) * 20);

    if (in_array(strtolower($pw), $common)) $score = min($score, 15);

    $score = min(100, max(0, $score));

    if ($score < 40)      { $label = 'Weak';   $color = 'var(--weak)';   }
    elseif ($score < 70)  { $label = 'Medium'; $color = 'var(--medium)'; }
    else                  { $label = 'Strong'; $color = 'var(--strong)'; }

    return ['score' => $score, 'label' => $label, 'color' => $color];
}

// ── Encryption ────────────────────────────────────────────────────────────────
// Simple reversible encryption for stored passwords.
// Key is derived from a constant — change this to something secret!
define('ENC_KEY', hex2bin('0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef'));

function encryptPassword(string $plain): string {
    $iv         = random_bytes(12);
    $ciphertext = openssl_encrypt($plain, 'aes-256-gcm', ENC_KEY, OPENSSL_RAW_DATA, $iv, $tag);
    return base64_encode($iv . $tag . $ciphertext);
}

function decryptPassword(string $encoded): string {
    $raw        = base64_decode($encoded);
    $iv         = substr($raw, 0, 12);
    $tag        = substr($raw, 12, 16);
    $ciphertext = substr($raw, 28);
    $plain = openssl_decrypt($ciphertext, 'aes-256-gcm', ENC_KEY, OPENSSL_RAW_DATA, $iv, $tag);
    return $plain !== false ? $plain : '';
}

// ── Utilities ─────────────────────────────────────────────────────────────────

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function genId(): string {
    return substr(base_convert(bin2hex(random_bytes(6)), 16, 36), 0, 8)
         . base_convert((string)time(), 10, 36);
}

function getCategoryId(string $name): ?int {
    if (!$name) return null;
    $stmt = getDB()->prepare('SELECT id FROM categories WHERE name = ?');
    $stmt->execute([$name]);
    $row = $stmt->fetch();
    return $row ? (int)$row['id'] : null;
}

function auditLog(int $userId, string $action, ?string $credId = null): void {
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    getDB()->prepare(
        'INSERT INTO audit_log (user_id, action, credential_id, ip_address) VALUES (?,?,?,?)'
    )->execute([$userId, $action, $credId, $ip]);
}

// ── Layout helpers ────────────────────────────────────────────────────────────

function renderNav(string $active = 'vault'): string {
    $vaultClass = $active === 'vault'     ? 'active' : '';
    $dashClass  = $active === 'dashboard' ? 'active' : '';
    return '<nav class="topbar">'
         . '<a href="vault.php" class="topbar-logo">Pass<span>Guard</span></a>'
         . '<div class="topbar-nav">'
         . '<a href="vault.php" class="' . $vaultClass . '">🔑 Vault</a>'
         . '<a href="dashboard.php" class="' . $dashClass . '">📊 Dashboard</a>'
         . '<a href="logout.php" class="danger">🔒 Lock</a>'
         . '</div></nav>';
}
