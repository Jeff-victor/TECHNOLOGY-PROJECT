<?php
/* Smarty version 5.8.0, created on 2026-05-22 12:52:51
  from 'file:admin_dashboard.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_6a1051a393d3b0_51468584',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '160c0ead073e5e7d3e83a4b4a72f4c716ae4e656' => 
    array (
      0 => 'admin_dashboard.tpl',
      1 => 1779453410,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:head.tpl' => 1,
    'file:admin_nav.tpl' => 1,
    'file:foot.tpl' => 1,
  ),
))) {
function content_6a1051a393d3b0_51468584 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\wamp64\\www\\passguard\\passguard\\templates';
$_smarty_tpl->renderSubTemplate("file:head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('page_title'=>"Admin Dashboard"), (int) 0, $_smarty_current_dir);
$_smarty_tpl->renderSubTemplate("file:admin_nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active_page'=>"dashboard"), (int) 0, $_smarty_current_dir);
?>
<div class="page-body">
  <div class="page-header">
    <h2>Admin Overview</h2>
    <span class="page-sub">Platform-wide statistics</span>
  </div>

  <!-- Stat Cards -->
  <div class="dash-grid" style="grid-template-columns:repeat(4,1fr);">
    <div class="stat-card">
      <div class="stat-label">Total Users</div>
      <div class="stat-value"><?php echo $_smarty_tpl->getValue('totalUsers');?>
</div>
      <div class="stat-sub"><?php echo $_smarty_tpl->getValue('activeUsers');?>
 active</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Total Credentials</div>
      <div class="stat-value"><?php echo $_smarty_tpl->getValue('totalCreds');?>
</div>
      <div class="stat-sub">across all vaults</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Avg Strength</div>
      <div class="stat-value" style="color:<?php echo $_smarty_tpl->getValue('avgColor');?>
;"><?php if ($_smarty_tpl->getValue('avgScore')) {
echo $_smarty_tpl->getValue('avgScore');
} else { ?>—<?php }?></div>
      <div class="stat-sub">out of 100</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Weak Passwords</div>
      <div class="stat-value" style="color:var(--weak);"><?php echo $_smarty_tpl->getValue('weakCreds');?>
</div>
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
                  stroke="<?php echo $_smarty_tpl->getValue('ringColor');?>
" stroke-width="10"
                  stroke-dasharray="<?php echo $_smarty_tpl->getValue('circ');?>
"
                  stroke-dashoffset="<?php echo $_smarty_tpl->getValue('offset');?>
"
                  stroke-linecap="round"
                  style="transform:rotate(-90deg);transform-origin:center;transition:stroke-dashoffset .6s;"/>
        </svg>
        <div class="score-ring-text">
          <span class="score-ring-num" style="color:<?php echo $_smarty_tpl->getValue('ringColor');?>
;"><?php echo $_smarty_tpl->getValue('globalScore');?>
</span>
          <span class="score-ring-label">/ 100</span>
        </div>
      </div>
      <h3>Platform Health</h3>
      <p>Average password<br>strength score</p>
      <div style="margin-top:16px;display:flex;gap:8px;flex-wrap:wrap;justify-content:center;">
        <span class="badge badge-strong"><?php echo $_smarty_tpl->getValue('strongCreds');?>
 strong</span>
        <span class="badge badge-medium"><?php echo $_smarty_tpl->getValue('mediumCreds');?>
 medium</span>
        <span class="badge badge-weak"><?php echo $_smarty_tpl->getValue('weakCreds');?>
 weak</span>
      </div>
    </div>

    <div class="chart-card">
      <h3>Strength Distribution</h3>
      <div class="bar-chart">
        <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('bars'), 'b');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('b')->value) {
$foreach0DoElse = false;
?>
        <div class="bar-col">
          <span class="bar-val"><?php echo $_smarty_tpl->getValue('b')['val'];?>
</span>
          <div class="bar" style="height:<?php echo $_smarty_tpl->getValue('b')['pct'];?>
%;background:<?php echo $_smarty_tpl->getValue('b')['color'];?>
;min-height:6px;"></div>
          <span class="bar-lbl"><?php echo $_smarty_tpl->getValue('b')['lbl'];?>
</span>
        </div>
        <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
      </div>

      <div class="chart-divider">
        <h3 style="margin-bottom:16px;">By Category (Global)</h3>
        <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('catDist'), 'cb');
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('cb')->value) {
$foreach1DoElse = false;
?>
        <div class="progress-row">
          <div class="progress-row-header">
            <span><?php echo htmlspecialchars((string)$_smarty_tpl->getValue('cb')['category'], ENT_QUOTES, 'UTF-8', true);?>
</span>
            <span><?php echo $_smarty_tpl->getValue('cb')['cnt'];?>
</span>
          </div>
          <div class="progress-track">
            <div class="progress-fill" style="width:<?php echo $_smarty_tpl->getValue('cb')['pct'];?>
%;background:var(--accent2);"></div>
          </div>
        </div>
        <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
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
        <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('userStats'), 'u');
$foreach2DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('u')->value) {
$foreach2DoElse = false;
?>
        <tr>
          <td style="font-weight:600;"><?php echo htmlspecialchars((string)$_smarty_tpl->getValue('u')['username'], ENT_QUOTES, 'UTF-8', true);?>
</td>
          <td><?php echo $_smarty_tpl->getValue('u')['cred_count'];?>
</td>
          <td style="color:<?php echo $_smarty_tpl->getValue('u')['color'];?>
;font-weight:600;"><?php if ($_smarty_tpl->getValue('u')['cred_count'] > 0) {
echo $_smarty_tpl->getValue('u')['avg_score'];?>
/100<?php } else { ?>—<?php }?></td>
          <td style="color:var(--weak);"><?php echo $_smarty_tpl->getValue('u')['weak_count'];?>
</td>
          <td>
            <?php if ($_smarty_tpl->getValue('u')['is_active']) {?>
              <span class="badge badge-strong">Active</span>
            <?php } else { ?>
              <span class="badge badge-weak">Disabled</span>
            <?php }?>
          </td>
          <td style="color:var(--muted);font-size:12px;"><?php if ($_smarty_tpl->getValue('u')['last_login']) {
echo htmlspecialchars((string)$_smarty_tpl->getValue('u')['last_login'], ENT_QUOTES, 'UTF-8', true);
} else { ?>Never<?php }?></td>
        </tr>
        <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
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
        <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('recentAudit'), 'a');
$foreach3DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('a')->value) {
$foreach3DoElse = false;
?>
        <tr>
          <td style="font-size:12px;color:var(--muted);"><?php echo htmlspecialchars((string)$_smarty_tpl->getValue('a')['created_at'], ENT_QUOTES, 'UTF-8', true);?>
</td>
          <td style="font-weight:600;"><?php echo htmlspecialchars((string)(($tmp = $_smarty_tpl->getValue('a')['username'] ?? null)===null||$tmp==='' ? 'Unknown' ?? null : $tmp), ENT_QUOTES, 'UTF-8', true);?>
</td>
          <td><span style="color:<?php echo $_smarty_tpl->getValue('a')['actionColor'];?>
;font-weight:500;"><?php echo htmlspecialchars((string)$_smarty_tpl->getValue('a')['action'], ENT_QUOTES, 'UTF-8', true);?>
</span></td>
          <td style="font-size:11px;color:var(--muted);"><?php if ($_smarty_tpl->getValue('a')['credential_id']) {
echo htmlspecialchars((string)$_smarty_tpl->getValue('a')['credential_id'], ENT_QUOTES, 'UTF-8', true);
} else { ?>—<?php }?></td>
          <td style="font-size:11px;color:var(--muted);"><?php echo htmlspecialchars((string)(($tmp = $_smarty_tpl->getValue('a')['ip_address'] ?? null)===null||$tmp==='' ? '—' ?? null : $tmp), ENT_QUOTES, 'UTF-8', true);?>
</td>
        </tr>
        <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
      </tbody>
    </table>
  </div>
</div>
<?php $_smarty_tpl->renderSubTemplate("file:foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
}
}
