<?php
/* Smarty version 5.8.0, created on 2026-05-22 13:09:07
  from 'file:admin_users.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_6a105573760ef6_35661423',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2c5f24556bd04f17517480befc08df399e1e60aa' => 
    array (
      0 => 'admin_users.tpl',
      1 => 1779455343,
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
function content_6a105573760ef6_35661423 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\wamp64\\www\\passguard\\passguard\\templates';
$_smarty_tpl->renderSubTemplate("file:head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('page_title'=>"Manage Users"), (int) 0, $_smarty_current_dir);
$_smarty_tpl->renderSubTemplate("file:admin_nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active_page'=>"users"), (int) 0, $_smarty_current_dir);
?>
<div class="page-body">
  <div class="page-header">
    <h2>User Management <span class="page-sub">— <?php echo $_smarty_tpl->getSmarty()->getModifierCallback('count')($_smarty_tpl->getValue('users'));?>
 user<?php if ($_smarty_tpl->getSmarty()->getModifierCallback('count')($_smarty_tpl->getValue('users')) != 1) {?>s<?php }?></span></h2>
  </div>

  <?php if ($_smarty_tpl->getValue('msg')) {?>
    <div class="warning-box" style="max-width:800px;color:<?php if ($_smarty_tpl->getValue('msgType') == 'success') {?>var(--strong)<?php } else { ?>var(--danger)<?php }?>;border-color:<?php if ($_smarty_tpl->getValue('msgType') == 'success') {?>var(--strong)<?php } else { ?>var(--danger)<?php }?>;">
      <?php if ($_smarty_tpl->getValue('msgType') == 'success') {?>✅<?php } else { ?>⚠️<?php }?> <?php echo htmlspecialchars((string)$_smarty_tpl->getValue('msg'), ENT_QUOTES, 'UTF-8', true);?>

    </div>
  <?php }?>

  <form method="GET" class="search-row" style="max-width:500px;">
    <input type="search" name="search" placeholder=" Search users…" value="<?php echo htmlspecialchars((string)$_smarty_tpl->getValue('search'), ENT_QUOTES, 'UTF-8', true);?>
"/>
    <button type="submit" class="btn btn-secondary btn-sm">Search</button>
    <?php if ($_smarty_tpl->getValue('search')) {?>
      <a href="admin_users.php" class="btn btn-secondary btn-sm">✕ Clear</a>
    <?php }?>
  </form>

  <div class="section-card">
    <table class="cred-table">
      <thead>
        <tr>
          <th>User</th><th>Role</th><th>Status</th><th>Credentials</th>
          <th>Avg Score</th><th>Registered</th><th>Last Login</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('users'), 'u');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('u')->value) {
$foreach0DoElse = false;
?>
        <tr<?php if (!$_smarty_tpl->getValue('u')['is_active']) {?> style="opacity:0.5;"<?php }?>>
          <td>
            <strong><?php echo htmlspecialchars((string)$_smarty_tpl->getValue('u')['username'], ENT_QUOTES, 'UTF-8', true);?>
</strong>
            <?php if ($_smarty_tpl->getValue('u')['id'] == $_smarty_tpl->getValue('currentId')) {?><span class="tag" style="margin-left:6px;">You</span><?php }?>
          </td>
          <td>
            <?php if ($_smarty_tpl->getValue('u')['is_admin']) {?>
              <span class="badge" style="background:#3d8bff20;color:var(--accent2);">Admin</span>
            <?php } else { ?>
              <span class="tag">User</span>
            <?php }?>
          </td>
          <td>
            <?php if ($_smarty_tpl->getValue('u')['is_active']) {?>
              <span class="badge badge-strong">Active</span>
            <?php } else { ?>
              <span class="badge badge-weak">Disabled</span>
            <?php }?>
          </td>
          <td><?php echo $_smarty_tpl->getValue('u')['cred_count'];?>
</td>
          <td style="color:<?php echo $_smarty_tpl->getValue('u')['color'];?>
;font-weight:600;"><?php if ($_smarty_tpl->getValue('u')['cred_count'] > 0) {
echo $_smarty_tpl->getValue('u')['avg_score'];?>
/100<?php } else { ?>—<?php }?></td>
          <td style="font-size:12px;color:var(--muted);"><?php echo htmlspecialchars((string)(($tmp = $_smarty_tpl->getValue('u')['created_at'] ?? null)===null||$tmp==='' ? '—' ?? null : $tmp), ENT_QUOTES, 'UTF-8', true);?>
</td>
          <td style="font-size:12px;color:var(--muted);"><?php if ($_smarty_tpl->getValue('u')['last_login']) {
echo htmlspecialchars((string)$_smarty_tpl->getValue('u')['last_login'], ENT_QUOTES, 'UTF-8', true);
} else { ?>Never<?php }?></td>
          <td>
            <div class="actions" style="flex-wrap:wrap;">
              <?php if ($_smarty_tpl->getValue('u')['id'] != $_smarty_tpl->getValue('currentId')) {?>
                <form method="POST" style="display:inline;">
                  <input type="hidden" name="user_id" value="<?php echo $_smarty_tpl->getValue('u')['id'];?>
"/>
                  <input type="hidden" name="action" value="toggle"/>
                  <button type="submit" class="btn btn-secondary btn-sm">
                    <?php if ($_smarty_tpl->getValue('u')['is_active']) {?> Disable<?php } else { ?> Enable<?php }?>
                  </button>
                </form>
                <form method="POST" style="display:inline;">
                  <input type="hidden" name="user_id" value="<?php echo $_smarty_tpl->getValue('u')['id'];?>
"/>
                  <input type="hidden" name="action" value="<?php if ($_smarty_tpl->getValue('u')['is_admin']) {?>demote<?php } else { ?>promote<?php }?>"/>
                  <button type="submit" class="btn btn-secondary btn-sm">
                    <?php if ($_smarty_tpl->getValue('u')['is_admin']) {?>⬇ Demote<?php } else { ?> Promote<?php }?>
                  </button>
                </form>
                <form method="POST" style="display:inline;"
                      onsubmit="return confirm('Delete user <?php echo htmlspecialchars((string)$_smarty_tpl->getValue('u')['username'], ENT_QUOTES, 'UTF-8', true);?>
 and ALL their data? This cannot be undone.')">
                  <input type="hidden" name="user_id" value="<?php echo $_smarty_tpl->getValue('u')['id'];?>
"/>
                  <input type="hidden" name="action" value="delete"/>
                  <button type="submit" class="btn btn-sm" style="background:var(--danger);color:#fff;border:none;"> Delete</button>
                </form>
              <?php } else { ?>
                <span style="font-size:11px;color:var(--muted);">—</span>
              <?php }?>
            </div>
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
