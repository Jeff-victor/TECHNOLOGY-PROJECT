<?php
/* Smarty version 5.8.0, created on 2026-05-22 13:26:05
  from 'file:login.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_6a10596d20f3f7_64934158',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bff0d9a3102ebf760185e5e176ec98110efb6811' => 
    array (
      0 => 'login.tpl',
      1 => 1779441368,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:head.tpl' => 1,
    'file:foot.tpl' => 1,
  ),
))) {
function content_6a10596d20f3f7_64934158 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\wamp64\\www\\passguard\\passguard\\templates';
$_smarty_tpl->renderSubTemplate("file:head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('page_title'=>"Login"), (int) 0, $_smarty_current_dir);
?>
<div class="auth-page">
  <div class="auth-card">
    <div class="auth-logo">
      <div class="auth-logo-mark">🔐</div>
      <h1>Pass<span>Guard</span></h1>
      <p>Your secure password vault</p>
    </div>

    <?php if ($_smarty_tpl->getValue('timeout')) {?>
      <div class="warning-box" style="color:var(--warn);border-color:var(--warn);">
        ⏱ Session expired after 15 minutes of inactivity.
      </div>
    <?php }?>

    <?php if ($_smarty_tpl->getValue('error')) {?>
      <div class="warning-box" style="color:var(--danger);border-color:var(--danger);">
        ⚠️ <?php echo htmlspecialchars((string)$_smarty_tpl->getValue('error'), ENT_QUOTES, 'UTF-8', true);?>

      </div>
    <?php }?>

    <form method="POST">
      <div class="field">
        <label for="username">Username</label>
        <input type="text" id="username" name="username"
               value="<?php echo htmlspecialchars((string)$_smarty_tpl->getValue('username_value'), ENT_QUOTES, 'UTF-8', true);?>
"
               placeholder="Enter your username" required autofocus/>
      </div>
      <div class="field">
        <label for="password">Master Password</label>
        <div class="input-icon-wrap">
          <input type="password" id="password" name="password"
                 placeholder="••••••••••••" required/>
          <span class="input-action" onclick="togglePw('password',this)">👁</span>
        </div>
      </div>
      <button type="submit" class="btn btn-primary btn-block" style="margin-top:4px;">
        Unlock Vault →
      </button>
    </form>

    <div class="auth-divider">Don't have an account?</div>
    <a href="signup.php" class="btn btn-secondary btn-block">Create Account</a>
  </div>
</div>

<?php echo '<script'; ?>
>
function togglePw(id, btn) {
  const input = document.getElementById(id);
  input.type  = input.type === 'password' ? 'text' : 'password';
  btn.textContent = input.type === 'password' ? '👁' : '🙈';
}
<?php echo '</script'; ?>
>

<?php $_smarty_tpl->renderSubTemplate("file:foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
}
}
