<?php
/* Smarty version 5.8.0, created on 2026-05-22 13:06:56
  from 'file:admin_audit.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_6a1054f00f7735_89590792',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8f6e4129e66c89120415e5ee42108d8cea8fb688' => 
    array (
      0 => 'admin_audit.tpl',
      1 => 1779455210,
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
function content_6a1054f00f7735_89590792 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\wamp64\\www\\passguard\\passguard\\templates';
$_smarty_tpl->renderSubTemplate("file:head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('page_title'=>"Audit Log"), (int) 0, $_smarty_current_dir);
$_smarty_tpl->renderSubTemplate("file:admin_nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active_page'=>"audit"), (int) 0, $_smarty_current_dir);
?>
<div class="page-body">
  <div class="page-header">
    <h2>Audit Log <span class="page-sub">— <?php echo $_smarty_tpl->getValue('totalRows');?>
 event<?php if ($_smarty_tpl->getValue('totalRows') != 1) {?>s<?php }?></span></h2>
  </div>

  <!-- Quick stats -->
  <div class="dash-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:24px;">
    <div class="stat-card">
      <div class="stat-label">Total Events</div>
      <div class="stat-value"><?php echo $_smarty_tpl->getValue('totalEvents');?>
</div>
      <div class="stat-sub">all time</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Failed Logins</div>
      <div class="stat-value" style="color:var(--danger);"><?php echo $_smarty_tpl->getValue('loginFails');?>
</div>
      <div class="stat-sub">all time</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Today's Events</div>
      <div class="stat-value" style="color:var(--accent);"><?php echo $_smarty_tpl->getValue('todayEvents');?>
</div>
      <div class="stat-sub"><?php echo $_smarty_tpl->getValue('today');?>
</div>
    </div>
  </div>

  <!-- Filters -->
  <form method="GET" class="search-row" style="flex-wrap:wrap;">
    <input type="search" name="user" placeholder=" Filter by username…"
           value="<?php echo htmlspecialchars((string)$_smarty_tpl->getValue('filterUser'), ENT_QUOTES, 'UTF-8', true);?>
" style="flex:1;min-width:160px;"/>
    <input type="search" name="ip" placeholder="Filter by IP…"
           value="<?php echo htmlspecialchars((string)$_smarty_tpl->getValue('filterIp'), ENT_QUOTES, 'UTF-8', true);?>
" style="width:160px;"/>
    <select name="action" onchange="this.form.submit()" style="width:180px;">
      <option value="">All actions</option>
      <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('actions'), 'a');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('a')->value) {
$foreach0DoElse = false;
?>
        <option <?php if ($_smarty_tpl->getValue('filterAction') == $_smarty_tpl->getValue('a')) {?>selected<?php }?>><?php echo htmlspecialchars((string)$_smarty_tpl->getValue('a'), ENT_QUOTES, 'UTF-8', true);?>
</option>
      <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
    </select>
    <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
    <?php if ($_smarty_tpl->getValue('filterAction') || $_smarty_tpl->getValue('filterUser') || $_smarty_tpl->getValue('filterIp')) {?>
      <a href="admin_audit.php" class="btn btn-secondary btn-sm">✕ Clear</a>
    <?php }?>
  </form>

  <!-- Log table -->
  <div class="section-card">
    <table class="cred-table">
      <thead>
        <tr><th>Time</th><th>User</th><th>Action</th><th>Credential ID</th><th>IP Address</th></tr>
      </thead>
      <tbody>
        <?php if ($_smarty_tpl->getSmarty()->getModifierCallback('count')($_smarty_tpl->getValue('logs')) == 0) {?>
          <tr>
            <td colspan="5" style="text-align:center;padding:48px;color:var(--muted);">
              No events found matching your filters.
            </td>
          </tr>
        <?php } else { ?>
          <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('logs'), 'log');
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('log')->value) {
$foreach1DoElse = false;
?>
          <tr>
            <td style="font-size:12px;color:var(--muted);white-space:nowrap;"><?php echo htmlspecialchars((string)$_smarty_tpl->getValue('log')['created_at'], ENT_QUOTES, 'UTF-8', true);?>
</td>
            <td style="font-weight:600;">
              <?php echo htmlspecialchars((string)(($tmp = $_smarty_tpl->getValue('log')['username'] ?? null)===null||$tmp==='' ? 'Deleted user' ?? null : $tmp), ENT_QUOTES, 'UTF-8', true);?>

              <?php if ($_smarty_tpl->getValue('log')['action'] == 'LOGIN_FAIL') {?>
                <span class="badge badge-weak" style="margin-left:4px;font-size:9px;">FAILED</span>
              <?php }?>
            </td>
            <td>
              <span style="color:<?php echo $_smarty_tpl->getValue('log')['actionColor'];?>
;font-weight:500;"><?php echo $_smarty_tpl->getValue('log')['icon'];?>
 <?php echo htmlspecialchars((string)$_smarty_tpl->getValue('log')['action'], ENT_QUOTES, 'UTF-8', true);?>
</span>
            </td>
            <td style="font-size:11px;color:var(--muted);"><?php if ($_smarty_tpl->getValue('log')['credential_id']) {
echo htmlspecialchars((string)$_smarty_tpl->getValue('log')['credential_id'], ENT_QUOTES, 'UTF-8', true);
} else { ?>—<?php }?></td>
            <td style="font-size:11px;color:var(--muted);"><?php echo htmlspecialchars((string)(($tmp = $_smarty_tpl->getValue('log')['ip_address'] ?? null)===null||$tmp==='' ? '—' ?? null : $tmp), ENT_QUOTES, 'UTF-8', true);?>
</td>
          </tr>
          <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
        <?php }?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <?php if ($_smarty_tpl->getValue('totalPages') > 1) {?>
  <div style="display:flex;justify-content:center;gap:8px;margin-top:20px;">
    <?php if ($_smarty_tpl->getValue('page') > 1) {?>
      <a href="<?php echo htmlspecialchars((string)$_smarty_tpl->getValue('prevUrl'), ENT_QUOTES, 'UTF-8', true);?>
" class="btn btn-secondary btn-sm">← Prev</a>
    <?php }?>
    <span style="padding:7px 14px;font-size:12px;color:var(--muted);">
      Page <?php echo $_smarty_tpl->getValue('page');?>
 of <?php echo $_smarty_tpl->getValue('totalPages');?>

    </span>
    <?php if ($_smarty_tpl->getValue('page') < $_smarty_tpl->getValue('totalPages')) {?>
      <a href="<?php echo htmlspecialchars((string)$_smarty_tpl->getValue('nextUrl'), ENT_QUOTES, 'UTF-8', true);?>
" class="btn btn-secondary btn-sm">Next →</a>
    <?php }?>
  </div>
  <?php }?>
</div>
<?php $_smarty_tpl->renderSubTemplate("file:foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
}
}
