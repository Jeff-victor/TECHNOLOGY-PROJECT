<?php
/* Smarty version 5.8.0, created on 2026-05-22 13:04:29
  from 'file:dashboard.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_6a10545d317a53_21238706',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3a3d849c9668ab3c28ea217b819b800150f5b1ba' => 
    array (
      0 => 'dashboard.tpl',
      1 => 1779453368,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:head.tpl' => 1,
    'file:nav.tpl' => 1,
    'file:foot.tpl' => 1,
  ),
))) {
function content_6a10545d317a53_21238706 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\wamp64\\www\\passguard\\passguard\\templates';
$_smarty_tpl->renderSubTemplate("file:head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('page_title'=>"Dashboard"), (int) 0, $_smarty_current_dir);
$_smarty_tpl->renderSubTemplate("file:nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active_page'=>"dashboard"), (int) 0, $_smarty_current_dir);
?>
<div class="page-body">
  <div class="page-header">
    <h2>Security Dashboard</h2>
    <a href="vault.php" class="btn btn-secondary btn-sm"> Back to Vault</a>
  </div>

  <!-- Stat Cards -->
  <div class="dash-grid">
    <div class="stat-card">
      <div class="stat-label">Total Credentials</div>
      <div class="stat-value"><?php echo $_smarty_tpl->getValue('total');?>
</div>
      <div class="stat-sub">stored in your vault</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Weak Passwords</div>
      <div class="stat-value" style="color:var(--weak);"><?php echo $_smarty_tpl->getValue('weak');?>
</div>
      <div class="stat-sub">score below 40</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Reused Passwords</div>
      <div class="stat-value" style="color:var(--warn);"><?php echo $_smarty_tpl->getValue('reused');?>
</div>
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
;"><?php echo $_smarty_tpl->getValue('secScore');?>
</span>
          <span class="score-ring-label">/ 100</span>
        </div>
      </div>
      <h3>Security Score</h3>
      <p>Based on strength,<br>reuse &amp; variety</p>
      <div style="margin-top:16px;display:flex;gap:8px;flex-wrap:wrap;justify-content:center;">
        <span class="badge badge-strong"><?php echo $_smarty_tpl->getValue('dist')['strong'];?>
 strong</span>
        <span class="badge badge-medium"><?php echo $_smarty_tpl->getValue('dist')['medium'];?>
 medium</span>
        <span class="badge badge-weak"><?php echo $_smarty_tpl->getValue('dist')['weak'];?>
 weak</span>
      </div>
    </div>

    <div class="chart-card">
      <h3>Password Strength Distribution</h3>
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
      <div class="legend">
        <div class="legend-item"><div class="legend-dot" style="background:var(--weak);"></div>Weak (&lt;40)</div>
        <div class="legend-item"><div class="legend-dot" style="background:var(--medium);"></div>Medium (40–69)</div>
        <div class="legend-item"><div class="legend-dot" style="background:var(--strong);"></div>Strong (70+)</div>
      </div>

      <!-- Category breakdown -->
      <div class="chart-divider">
        <h3 style="margin-bottom:16px;">By Category</h3>
        <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('catBars'), 'cb');
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('cb')->value) {
$foreach1DoElse = false;
?>
        <div class="progress-row">
          <div class="progress-row-header">
            <span><?php echo $_smarty_tpl->getValue('cb')['name'];?>
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

  <!-- Issues List -->
  <div class="weak-list-card">
    <h3>⚠️ Passwords to Fix</h3>
    <?php if ($_smarty_tpl->getSmarty()->getModifierCallback('count')($_smarty_tpl->getValue('issues')) == 0) {?>
      <p style="color:var(--muted);font-size:13px;text-align:center;padding:24px 0;">
        ✅ No issues found! Your vault looks healthy.
      </p>
    <?php } else { ?>
      <table class="cred-table">
        <thead>
          <tr><th>Site</th><th>Username</th><th>Issue</th><th>Score</th><th>Action</th></tr>
        </thead>
        <tbody>
          <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('issues'), 'issue');
$foreach2DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('issue')->value) {
$foreach2DoElse = false;
?>
          <tr>
            <td><?php echo htmlspecialchars((string)$_smarty_tpl->getValue('issue')['site_name'], ENT_QUOTES, 'UTF-8', true);?>
</td>
            <td><?php echo htmlspecialchars((string)$_smarty_tpl->getValue('issue')['username'], ENT_QUOTES, 'UTF-8', true);?>
</td>
            <td style="color:var(--danger);"><?php echo htmlspecialchars((string)$_smarty_tpl->getValue('issue')['issue_reason'], ENT_QUOTES, 'UTF-8', true);?>
</td>
            <td style="color:<?php echo $_smarty_tpl->getValue('issue')['color'];?>
;font-weight:600;"><?php echo $_smarty_tpl->getValue('issue')['strength_score'];?>
/100 — <?php echo $_smarty_tpl->getValue('issue')['label'];?>
</td>
            <td><a href="edit.php?id=<?php echo htmlspecialchars((string)$_smarty_tpl->getValue('issue')['id'], ENT_QUOTES, 'UTF-8', true);?>
" class="btn btn-secondary btn-sm">Fix →</a></td>
          </tr>
          <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
        </tbody>
      </table>
    <?php }?>
  </div>
</div>
<?php $_smarty_tpl->renderSubTemplate("file:foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
}
}
