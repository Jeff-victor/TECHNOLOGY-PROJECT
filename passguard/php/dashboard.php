<?php
require_once __DIR__ . '/functions.php';
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

$maxCat = $catMap ? max(array_values($catMap)) : 1;
$circ   = 2 * M_PI * 54;
$offset = $circ - ($circ * $secScore / 100);
$ringColor = $secScore >= 70 ? 'var(--strong)' : ($secScore >= 40 ? 'var(--medium)' : 'var(--weak)');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>PassGuard — Dashboard</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=JetBrains+Mono:wght@300;400;500&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="../css/style.css"/>
</head>
<body>
<?= renderNav('dashboard') ?>
<div class="page-body">
  <div class="page-header">
    <h2>Security Dashboard</h2>
    <a href="vault.php" class="btn btn-secondary btn-sm">🔑 Back to Vault</a>
  </div>

  <!-- Stat Cards -->
  <div class="dash-grid">
    <div class="stat-card">
      <div class="stat-label">Total Credentials</div>
      <div class="stat-value"><?= $total ?></div>
      <div class="stat-sub">stored in your vault</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Weak Passwords</div>
      <div class="stat-value" style="color:var(--weak);"><?= $weak ?></div>
      <div class="stat-sub">score below 40</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Reused Passwords</div>
      <div class="stat-value" style="color:var(--warn);"><?= $reused ?></div>
      <div class="stat-sub">used more than once</div>
    </div>
  </div>

  <!-- Score + Bar Chart -->
  <div class="score-section">
    <div class="score-card">
      <div class="score-ring">
        <svg width="130" height="130" viewBox="0 0 130 130">
          <circle cx="65" cy="65" r="54" fill="none" stroke="var(--border)" stroke-width="10"/>
          <circle cx="65" cy="65" r="54" fill="none"
                  stroke="<?= $ringColor ?>" stroke-width="10"
                  stroke-dasharray="<?= round($circ,2) ?>"
                  stroke-dashoffset="<?= round($offset,2) ?>"
                  stroke-linecap="round"
                  style="transform:rotate(-90deg);transform-origin:center;transition:stroke-dashoffset .6s;"/>
        </svg>
        <div class="score-ring-text">
          <span class="score-ring-num" style="color:<?= $ringColor ?>;"><?= $secScore ?></span>
          <span class="score-ring-label">/ 100</span>
        </div>
      </div>
      <h3>Security Score</h3>
      <p>Based on strength,<br>reuse &amp; variety</p>
      <div style="margin-top:16px;display:flex;gap:8px;flex-wrap:wrap;justify-content:center;">
        <span class="badge badge-strong"><?= (int)($dist['strong']??0) ?> strong</span>
        <span class="badge badge-medium"><?= (int)($dist['medium']??0) ?> medium</span>
        <span class="badge badge-weak"><?= (int)($dist['weak']??0) ?> weak</span>
      </div>
    </div>

    <div class="chart-card">
      <h3>Password Strength Distribution</h3>
      <div class="bar-chart">
        <?php
        $maxBar = max(1, (int)($dist['weak']??0), (int)($dist['medium']??0), (int)($dist['strong']??0));
        $bars = [
            ['val'=>(int)($dist['weak']??0),   'color'=>'var(--weak)',   'lbl'=>'Weak'],
            ['val'=>(int)($dist['medium']??0), 'color'=>'var(--medium)', 'lbl'=>'Medium'],
            ['val'=>(int)($dist['strong']??0), 'color'=>'var(--strong)', 'lbl'=>'Strong'],
        ];
        foreach ($bars as $b):
            $pct = $maxBar > 0 ? round($b['val']/$maxBar*100) : 0;
        ?>
        <div class="bar-col">
          <span class="bar-val"><?= $b['val'] ?></span>
          <div class="bar" style="height:<?= $pct ?>%;background:<?= $b['color'] ?>;min-height:6px;"></div>
          <span class="bar-lbl"><?= $b['lbl'] ?></span>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="legend">
        <div class="legend-item"><div class="legend-dot" style="background:var(--weak);"></div>Weak (&lt;40)</div>
        <div class="legend-item"><div class="legend-dot" style="background:var(--medium);"></div>Medium (40–69)</div>
        <div class="legend-item"><div class="legend-dot" style="background:var(--strong);"></div>Strong (70+)</div>
      </div>

      <!-- Category breakdown -->
      <div class="chart-divider">
        <h3 style="margin-bottom:16px;">By Category</h3>
        <?php foreach (['Social','Work','Finance','Other'] as $cat):
          $cnt = $catMap[$cat] ?? 0;
          $pct = $maxCat > 0 ? round($cnt/$maxCat*100) : 0;
        ?>
        <div class="progress-row">
          <div class="progress-row-header">
            <span><?= $cat ?></span>
            <span><?= $cnt ?></span>
          </div>
          <div class="progress-track">
            <div class="progress-fill" style="width:<?= $pct ?>%;background:var(--accent2);"></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Issues List -->
  <div class="weak-list-card">
    <h3>⚠️ Passwords to Fix</h3>
    <?php if (empty($issues)): ?>
      <p style="color:var(--muted);font-size:13px;text-align:center;padding:24px 0;">
        ✅ No issues found! Your vault looks healthy.
      </p>
    <?php else: ?>
      <table class="cred-table">
        <thead>
          <tr><th>Site</th><th>Username</th><th>Issue</th><th>Score</th><th>Action</th></tr>
        </thead>
        <tbody>
          <?php foreach ($issues as $issue):
            $s = (int)$issue['strength_score'];
            $color = $s >= 70 ? 'var(--strong)' : ($s >= 40 ? 'var(--medium)' : 'var(--weak)');
            $label = $s >= 70 ? 'Strong' : ($s >= 40 ? 'Medium' : 'Weak');
          ?>
          <tr>
            <td><?= h($issue['site_name']) ?></td>
            <td><?= h($issue['username']) ?></td>
            <td style="color:var(--danger);"><?= h($issue['issue_reason']) ?></td>
            <td style="color:<?= $color ?>;font-weight:600;"><?= $s ?>/100 — <?= $label ?></td>
            <td><a href="edit.php?id=<?= h($issue['id']) ?>" class="btn btn-secondary btn-sm">Fix →</a></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
