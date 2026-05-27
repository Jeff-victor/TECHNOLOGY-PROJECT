<?php
/* Smarty version 5.8.0, created on 2026-05-27 09:14:18
  from 'file:add.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_6a16b5ea705640_21035658',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1ff668fbc3bd7cccecc2aa38d6f0702fd7b4332a' => 
    array (
      0 => 'add.tpl',
      1 => 1779453436,
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
function content_6a16b5ea705640_21035658 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\wamp64\\www\\passguard\\passguard\\templates';
$_smarty_tpl->renderSubTemplate("file:head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('page_title'=>"Add Credential"), (int) 0, $_smarty_current_dir);
$_smarty_tpl->renderSubTemplate("file:nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active_page'=>"vault"), (int) 0, $_smarty_current_dir);
?>
<div class="page-body">
  <div class="page-header">
    <h2>Add Credential</h2>
    <a href="vault.php" class="btn btn-secondary btn-sm">← Back to Vault</a>
  </div>

  <?php if ($_smarty_tpl->getValue('error')) {?>
    <div class="warning-box" style="color:var(--danger);border-color:var(--danger);max-width:560px;">
      ⚠️ <?php echo htmlspecialchars((string)$_smarty_tpl->getValue('error'), ENT_QUOTES, 'UTF-8', true);?>

    </div>
  <?php }?>

  <div class="form-card">
    <form method="POST">
      <div class="field">
        <label for="site_name">Site Name</label>
        <input type="text" id="site_name" name="site_name"
               value="<?php echo htmlspecialchars((string)$_smarty_tpl->getValue('post')['site_name'], ENT_QUOTES, 'UTF-8', true);?>
"
               placeholder="e.g. GitHub" required/>
      </div>
      <div class="field">
        <label for="site_url">Website URL</label>
        <input type="url" id="site_url" name="site_url"
               value="<?php echo htmlspecialchars((string)$_smarty_tpl->getValue('post')['site_url'], ENT_QUOTES, 'UTF-8', true);?>
"
               placeholder="https://github.com"/>
      </div>
      <div class="field">
        <label for="username">Username / Email</label>
        <input type="text" id="username" name="username"
               value="<?php echo htmlspecialchars((string)$_smarty_tpl->getValue('post')['username'], ENT_QUOTES, 'UTF-8', true);?>
"
               placeholder="Your login username or email" required/>
      </div>
      <div class="field">
        <label for="category">Category</label>
        <select id="category" name="category">
          <option value="">Select a category…</option>
          <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, array('Social','Work','Finance','Other'), 'cat');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('cat')->value) {
$foreach0DoElse = false;
?>
            <option <?php if ($_smarty_tpl->getValue('post')['category'] == $_smarty_tpl->getValue('cat')) {?>selected<?php }?>><?php echo $_smarty_tpl->getValue('cat');?>
</option>
          <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
        </select>
      </div>

      <!-- Password Generator -->
      <div class="generator-box">
        <h4> Password Generator</h4>
        <div class="gen-output" id="gen-output">X7#mPqL@2vNk!9sR</div>
        <div class="gen-options">
          <label class="checkbox-label"><input type="checkbox" id="gen-upper"   checked/> Uppercase</label>
          <label class="checkbox-label"><input type="checkbox" id="gen-lower"   checked/> Lowercase</label>
          <label class="checkbox-label"><input type="checkbox" id="gen-numbers" checked/> Numbers</label>
          <label class="checkbox-label"><input type="checkbox" id="gen-symbols" checked/> Symbols</label>
        </div>
        <div class="gen-length-row">
          <label>Length: <strong id="gen-length-display">16</strong></label>
          <input type="range" id="gen-length" min="8" max="64" value="16"/>
          <button type="button" class="btn btn-primary btn-sm">↻ Generate</button>
          <button type="button" class="btn btn-secondary btn-sm">Use this →</button>
        </div>
      </div>

      <div class="field">
        <label for="password">Password</label>
        <div class="input-icon-wrap">
          <input type="password" id="password" name="password"
                 placeholder="Or type your own password" required
                 oninput="liveStrength(this.value)"/>
          <span class="input-action" onclick="togglePw('password',this)">👁</span>
        </div>
        <div class="strength-bar">
          <div class="strength-fill" id="strength-fill" style="width:0%;"></div>
        </div>
        <div style="display:flex;justify-content:space-between;margin-top:4px;">
          <span id="strength-label" style="font-size:11px;color:var(--muted);">Enter a password</span>
          <span id="strength-score" style="font-size:11px;color:var(--muted);"></span>
        </div>
      </div>

      <div class="field">
        <label for="notes">Notes (optional)</label>
        <textarea id="notes" name="notes" rows="3" style="resize:vertical;"
                  placeholder="Any extra info…"><?php echo htmlspecialchars((string)$_smarty_tpl->getValue('post')['notes'], ENT_QUOTES, 'UTF-8', true);?>
</textarea>
      </div>

      <div style="display:flex;gap:12px;margin-top:8px;">
        <button type="submit" class="btn btn-primary" style="flex:1;">Save Credential</button>
        <a href="vault.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php echo '<script'; ?>
 src="../js/strength.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="../js/generator.js"><?php echo '</script'; ?>
>

<?php echo '<script'; ?>
>
function togglePw(id, btn) {
  const input = document.getElementById(id);
  input.type  = input.type === 'password' ? 'text' : 'password';
  btn.textContent = input.type === 'password' ? '👁' : '🙈';
}
function liveStrength(pw) {
  const { score, label, color } = scorePassword(pw);
  document.getElementById('strength-fill').style.width      = score + '%';
  document.getElementById('strength-fill').style.background = color;
  document.getElementById('strength-label').textContent     = pw ? label : 'Enter a password';
  document.getElementById('strength-score').textContent     = pw ? score + '/100' : '';
}
document.addEventListener('DOMContentLoaded', initGenerator);
<?php echo '</script'; ?>
>

<?php $_smarty_tpl->renderSubTemplate("file:foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
}
}
