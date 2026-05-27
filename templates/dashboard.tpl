{include file="head.tpl" page_title="Dashboard"}
{include file="nav.tpl" active_page="dashboard"}
<div class="page-body">
  <div class="page-header">
    <h2>Security Dashboard</h2>
    <a href="vault.php" class="btn btn-secondary btn-sm"> Back to Vault</a>
  </div>

  <!-- Stat Cards -->
  <div class="dash-grid">
    <div class="stat-card">
      <div class="stat-label">Total Credentials</div>
      <div class="stat-value">{$total}</div>
      <div class="stat-sub">stored in your vault</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Weak Passwords</div>
      <div class="stat-value" style="color:var(--weak);">{$weak}</div>
      <div class="stat-sub">score below 40</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Reused Passwords</div>
      <div class="stat-value" style="color:var(--warn);">{$reused}</div>
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
                  stroke="{$ringColor}" stroke-width="10"
                  stroke-dasharray="{$circ}"
                  stroke-dashoffset="{$offset}"
                  stroke-linecap="round"
                  style="transform:rotate(-90deg);transform-origin:center;transition:stroke-dashoffset .6s;"/>
        </svg>
        <div class="score-ring-text">
          <span class="score-ring-num" style="color:{$ringColor};">{$secScore}</span>
          <span class="score-ring-label">/ 100</span>
        </div>
      </div>
      <h3>Security Score</h3>
      <p>Based on strength,<br>reuse &amp; variety</p>
      <div style="margin-top:16px;display:flex;gap:8px;flex-wrap:wrap;justify-content:center;">
        <span class="badge badge-strong">{$dist.strong} strong</span>
        <span class="badge badge-medium">{$dist.medium} medium</span>
        <span class="badge badge-weak">{$dist.weak} weak</span>
      </div>
    </div>

    <div class="chart-card">
      <h3>Password Strength Distribution</h3>
      <div class="bar-chart">
        {foreach $bars as $b}
        <div class="bar-col">
          <span class="bar-val">{$b.val}</span>
          <div class="bar" style="height:{$b.pct}%;background:{$b.color};min-height:6px;"></div>
          <span class="bar-lbl">{$b.lbl}</span>
        </div>
        {/foreach}
      </div>
      <div class="legend">
        <div class="legend-item"><div class="legend-dot" style="background:var(--weak);"></div>Weak (&lt;40)</div>
        <div class="legend-item"><div class="legend-dot" style="background:var(--medium);"></div>Medium (40–69)</div>
        <div class="legend-item"><div class="legend-dot" style="background:var(--strong);"></div>Strong (70+)</div>
      </div>

      <!-- Category breakdown -->
      <div class="chart-divider">
        <h3 style="margin-bottom:16px;">By Category</h3>
        {foreach $catBars as $cb}
        <div class="progress-row">
          <div class="progress-row-header">
            <span>{$cb.name}</span>
            <span>{$cb.cnt}</span>
          </div>
          <div class="progress-track">
            <div class="progress-fill" style="width:{$cb.pct}%;background:var(--accent2);"></div>
          </div>
        </div>
        {/foreach}
      </div>
    </div>
  </div>

  <!-- Issues List -->
  <div class="weak-list-card">
    <h3>⚠️ Passwords to Fix</h3>
    {if $issues|@count == 0}
      <p style="color:var(--muted);font-size:13px;text-align:center;padding:24px 0;">
        ✅ No issues found! Your vault looks healthy.
      </p>
    {else}
      <table class="cred-table">
        <thead>
          <tr><th>Site</th><th>Username</th><th>Issue</th><th>Score</th><th>Action</th></tr>
        </thead>
        <tbody>
          {foreach $issues as $issue}
          <tr>
            <td>{$issue.site_name|escape}</td>
            <td>{$issue.username|escape}</td>
            <td style="color:var(--danger);">{$issue.issue_reason|escape}</td>
            <td style="color:{$issue.color};font-weight:600;">{$issue.strength_score}/100 — {$issue.label}</td>
            <td><a href="edit.php?id={$issue.id|escape}" class="btn btn-secondary btn-sm">Fix →</a></td>
          </tr>
          {/foreach}
        </tbody>
      </table>
    {/if}
  </div>
</div>
{include file="foot.tpl"}
