<?php
/* Smarty version 5.8.0, created on 2026-05-22 13:03:55
  from 'file:nav.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_6a10543bb532b9_58559979',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '02c44c249b494167b912f0a91b7c84df16777795' => 
    array (
      0 => 'nav.tpl',
      1 => 1779455015,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6a10543bb532b9_58559979 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\wamp64\\www\\passguard\\passguard\\templates';
?><nav class="topbar">
  <a href="vault.php" class="topbar-logo">Pass<span>Guard</span></a>
  <div class="topbar-nav">
    <a href="vault.php" class="<?php if ($_smarty_tpl->getValue('active_page') == 'vault') {?>active<?php }?>"> Vault</a>
    <a href="dashboard.php" class="<?php if ($_smarty_tpl->getValue('active_page') == 'dashboard') {?>active<?php }?>"> Dashboard</a>
    <?php if ($_smarty_tpl->getValue('is_admin')) {?>
      <a href="admin_dashboard.php" class="<?php if ($_smarty_tpl->getValue('active_page') == 'admin') {?>active<?php }?>"> Admin</a>
    <?php }?>
    <a href="logout.php" class="danger">🔒 Lock</a>
  </div>
</nav>
<?php }
}
