<?php
/* Smarty version 5.8.0, created on 2026-05-22 13:04:41
  from 'file:admin_nav.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_6a105469465201_26656807',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '82dec796514f47efe6f49173d0a70f005c699b06' => 
    array (
      0 => 'admin_nav.tpl',
      1 => 1779454956,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6a105469465201_26656807 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\wamp64\\www\\passguard\\passguard\\templates';
?><nav class="topbar">
  <a href="admin_dashboard.php" class="topbar-logo">Pass<span>Guard</span> <span style="font-size:11px;color:var(--danger);margin-left:6px;">ADMIN</span></a>
  <div class="topbar-nav">
    <a href="admin_dashboard.php" class="<?php if ($_smarty_tpl->getValue('active_page') == 'dashboard') {?>active<?php }?>"> Overview</a>
    <a href="admin_users.php" class="<?php if ($_smarty_tpl->getValue('active_page') == 'users') {?>active<?php }?>"> Users</a>
    <a href="admin_audit.php" class="<?php if ($_smarty_tpl->getValue('active_page') == 'audit') {?>active<?php }?>"> Audit Log</a>
    <a href="admin_categories.php" class="<?php if ($_smarty_tpl->getValue('active_page') == 'categories') {?>active<?php }?>"> Categories</a>
    <a href="vault.php"> Vault</a>
    <a href="logout.php" class="danger"> Lock</a>
  </div>
</nav>
<?php }
}
