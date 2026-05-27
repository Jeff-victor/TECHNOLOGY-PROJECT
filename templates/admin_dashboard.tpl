{include file="head.tpl" page_title="Admin Dashboard"}
{include file="admin_nav.tpl" active_page="dashboard"}
<div class="page-body">
  <div class="page-header">
    <h2>Admin Overview</h2>
    <span class="page-sub">Platform-wide statistics</span>
  </div>

  <!-- Stat Cards -->
  <div class="dash-grid" style="grid-template-columns:repeat(4,1fr);">
    <div class="stat-card">
      <div class="stat-label">Total Users</div>
      <div class="stat-value">{$totalUsers}</div>
      <div class="stat-sub">{$activeUsers} active</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Total Credentials</div>
      <div class="stat-value">{$totalCreds}</div>
      <div class="stat-sub">across all vaults</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Avg Strength</div>
      <div class="stat-value" style="color:{$avgColor};">{if $avgScore}{$avgScore}{else}—{/if}</div>
      <div class="stat-sub">out of 100</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Weak Passwords</div>
      <div class="stat-value" style="color:var(--weak);">{$weakCreds}</div>
      <div class="stat-sub">score below 40</div>
    </div>
  </div>

  <!-- Score + Strength Distribution -->
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
          <span class="score-ring-num" style="color:{$ringColor};">{$globalScore}</span>
          <span class="score-ring-label">/ 100</span>
        </div>
      </div>
      <h3>Platform Health</h3>
      <p>Average password<br>strength score</p>
      <div style="margin-top:16px;display:flex;gap:8px;flex-wrap:wrap;justify-content:center;">
        <span class="badge badge-strong">{$strongCreds} strong</span>
        <span class="badge badge-medium">{$mediumCreds} medium</span>
        <span class="badge badge-weak">{$weakCreds} weak</span>
      </div>
    </div>

    <div class="chart-card">
      <h3>Strength Distribution</h3>
      <div class="bar-chart">
        {foreach $bars as $b}
        <div class="bar-col">
          <span class="bar-val">{$b.val}</span>
          <div class="bar" style="height:{$b.pct}%;background:{$b.color};min-height:6px;"></div>
          <span class="bar-lbl">{$b.lbl}</span>
        </div>
        {/foreach}
      </div>

      <div class="chart-divider">
        <h3 style="margin-bottom:16px;">By Category (Global)</h3>
        {foreach $catDist as $cb}
        <div class="progress-row">
          <div class="progress-row-header">
            <span>{$cb.category|escape}</span>
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

  <!-- User Vault Health -->
  <div class="weak-list-card">
    <h3> User Vault Health</h3>
    <table class="cred-table">
      <thead>
        <tr><th>User</th><th>Credentials</th><th>Avg Score</th><th>Weak</th><th>Status</th><th>Last Login</th></tr>
      </thead>
      <tbody>
        {foreach $userStats as $u}
        <tr>
          <td style="font-weight:600;">{$u.username|escape}</td>
          <td>{$u.cred_count}</td>
          <td style="color:{$u.color};font-weight:600;">{if $u.cred_count > 0}{$u.avg_score}/100{else}—{/if}</td>
          <td style="color:var(--weak);">{$u.weak_count}</td>
          <td>
            {if $u.is_active}
              <span class="badge badge-strong">Active</span>
            {else}
              <span class="badge badge-weak">Disabled</span>
            {/if}
          </td>
          <td style="color:var(--muted);font-size:12px;">{if $u.last_login}{$u.last_login|escape}{else}Never{/if}</td>
        </tr>
        {/foreach}
      </tbody>
    </table>
  </div>

  <!-- Recent Activity -->
  <div class="weak-list-card" style="margin-top:16px;">
    <h3> Recent Activity</h3>
    <table class="cred-table">
      <thead>
        <tr><th>Time</th><th>User</th><th>Action</th><th>Credential ID</th><th>IP</th></tr>
      </thead>
      <tbody>
        {foreach $recentAudit as $a}
        <tr>
          <td style="font-size:12px;color:var(--muted);">{$a.created_at|escape}</td>
          <td style="font-weight:600;">{$a.username|default:'Unknown'|escape}</td>
          <td><span style="color:{$a.actionColor};font-weight:500;">{$a.action|escape}</span></td>
          <td style="font-size:11px;color:var(--muted);">{if $a.credential_id}{$a.credential_id|escape}{else}—{/if}</td>
          <td style="font-size:11px;color:var(--muted);">{$a.ip_address|default:'—'|escape}</td>
        </tr>
        {/foreach}
      </tbody>
    </table>
  </div>
</div>
{include file="foot.tpl"}
