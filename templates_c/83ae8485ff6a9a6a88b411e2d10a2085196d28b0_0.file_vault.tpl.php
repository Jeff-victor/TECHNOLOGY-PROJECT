<?php
/* Smarty version 5.8.0, created on 2026-05-22 13:04:26
  from 'file:vault.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_6a10545a4e91a1_60903044',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '83ae8485ff6a9a6a88b411e2d10a2085196d28b0' => 
    array (
      0 => 'vault.tpl',
      1 => 1779455060,
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
function content_6a10545a4e91a1_60903044 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\wamp64\\www\\passguard\\passguard\\templates';
$_smarty_tpl->renderSubTemplate("file:head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('page_title'=>"Vault"), (int) 0, $_smarty_current_dir);
$_smarty_tpl->renderSubTemplate("file:nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active_page'=>"vault"), (int) 0, $_smarty_current_dir);
?>
<div class="page-body">
  <div class="page-header">
    <h2>My Vault <span class="page-sub">— <?php echo $_smarty_tpl->getValue('total');?>
 credential<?php if ($_smarty_tpl->getValue('total') != 1) {?>s<?php }?></span></h2>
    <a href="add.php" class="btn btn-primary">+ Add Credential</a>
  </div>

  <!-- Search & Filter -->
  <form method="GET" class="search-row">
    <input type="search" name="search" placeholder=" Search credentials…"
           value="<?php echo htmlspecialchars((string)$_smarty_tpl->getValue('search'), ENT_QUOTES, 'UTF-8', true);?>
"/>
    <select name="category" onchange="this.form.submit()">
      <option value="">All categories</option>
      <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, array('Social','Work','Finance','Other'), 'cat');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('cat')->value) {
$foreach0DoElse = false;
?>
        <option <?php if ($_smarty_tpl->getValue('category') == $_smarty_tpl->getValue('cat')) {?>selected<?php }?>><?php echo $_smarty_tpl->getValue('cat');?>
</option>
      <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
    </select>
    <button type="submit" class="btn btn-secondary btn-sm">Search</button>
    <?php if ($_smarty_tpl->getValue('search') || $_smarty_tpl->getValue('category')) {?>
      <a href="vault.php" class="btn btn-secondary btn-sm">✕ Clear</a>
    <?php }?>
  </form>

  <!-- Credentials Table -->
  <div class="section-card">
    <table class="cred-table">
      <thead>
        <tr>
          <th>Site</th>
          <th>Username</th>
          <th>Password</th>
          <th>Strength</th>
          <th>Category</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($_smarty_tpl->getSmarty()->getModifierCallback('count')($_smarty_tpl->getValue('creds')) == 0) {?>
          <tr>
            <td colspan="6" style="text-align:center;padding:48px;color:var(--muted);">
              No credentials found.
              <a href="add.php" style="color:var(--accent);">Add your first one →</a>
            </td>
          </tr>
        <?php } else { ?>
          <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('creds'), 'c');
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('c')->value) {
$foreach1DoElse = false;
?>
          <tr>
            <td>
              <strong><?php echo htmlspecialchars((string)$_smarty_tpl->getValue('c')['site_name'], ENT_QUOTES, 'UTF-8', true);?>
</strong>
              <?php if ($_smarty_tpl->getValue('c')['site_url']) {?>
                <br><a href="<?php echo htmlspecialchars((string)$_smarty_tpl->getValue('c')['site_url'], ENT_QUOTES, 'UTF-8', true);?>
" target="_blank"
                       style="font-size:11px;color:var(--muted);"><?php echo htmlspecialchars((string)$_smarty_tpl->getValue('c')['site_url'], ENT_QUOTES, 'UTF-8', true);?>
</a>
              <?php }?>
            </td>
            <td><?php echo htmlspecialchars((string)$_smarty_tpl->getValue('c')['username'], ENT_QUOTES, 'UTF-8', true);?>
</td>
            <td>
              <span class="pw-cell" data-pw="<?php echo htmlspecialchars((string)$_smarty_tpl->getValue('c')['password'], ENT_QUOTES, 'UTF-8', true);?>
">••••••••</span>
              <button type="button" class="btn-icon" onclick="togglePwCell(this)" title="Show/hide">👁</button>
              <button type="button" class="btn-icon" onclick="copyPw(this)" title="Copy">📋</button>
            </td>
            <td>
              <span style="color:<?php echo $_smarty_tpl->getValue('c')['strength']['color'];?>
;font-weight:600;"><?php echo $_smarty_tpl->getValue('c')['strength']['label'];?>
</span>
              <br><span style="font-size:11px;color:var(--muted);"><?php echo $_smarty_tpl->getValue('c')['strength']['score'];?>
/100</span>
            </td>
            <td><?php echo htmlspecialchars((string)(($tmp = $_smarty_tpl->getValue('c')['category'] ?? null)===null||$tmp==='' ? '—' ?? null : $tmp), ENT_QUOTES, 'UTF-8', true);?>
</td>
            <td class="actions">
              <a href="edit.php?id=<?php echo htmlspecialchars((string)$_smarty_tpl->getValue('c')['id'], ENT_QUOTES, 'UTF-8', true);?>
" class="btn btn-secondary btn-sm">Edit</a>
              <a href="delete.php?id=<?php echo htmlspecialchars((string)$_smarty_tpl->getValue('c')['id'], ENT_QUOTES, 'UTF-8', true);?>
"
                 class="btn btn-sm"
                 style="background:var(--danger);color:#fff;border:none;"
                 onclick="return confirm('Delete this credential?')">Delete</a>
            </td>
          </tr>
          <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
        <?php }?>
      </tbody>
    </table>
  </div>
</div>

<?php echo '<script'; ?>
>
function togglePwCell(btn) {
  const cell    = btn.closest('td').querySelector('.pw-cell');
  const showing = cell.textContent !== '••••••••';
  cell.textContent = showing ? '••••••••' : cell.dataset.pw;
  btn.textContent  = showing ? '👁' : '🙈';
}
function copyPw(btn) {
  const pw = btn.closest('td').querySelector('.pw-cell').dataset.pw;
  navigator.clipboard.writeText(pw).then(() => {
    btn.textContent = '✅';
    setTimeout(() => { btn.textContent = '📋'; }, 1500);
  });
}
<?php echo '</script'; ?>
>

<?php $_smarty_tpl->renderSubTemplate("file:foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
}
}
