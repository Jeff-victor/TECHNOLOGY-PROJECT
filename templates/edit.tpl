{include file="head.tpl" page_title="Edit Credential"}
{include file="nav.tpl" active_page="vault"}
<div class="page-body">
  <div class="page-header">
    <h2>Edit Credential</h2>
    <a href="vault.php" class="btn btn-secondary btn-sm">← Back to Vault</a>
  </div>

  {if $error}
    <div class="warning-box" style="color:var(--danger);border-color:var(--danger);max-width:560px;">
      ⚠️ {$error|escape}
    </div>
  {/if}

  <div class="form-card">
    <form method="POST">
      <input type="hidden" name="id" value="{$cred_id|escape}"/>

      <div class="field">
        <label for="site_name">Site Name</label>
        <input type="text" id="site_name" name="site_name"
               value="{$v.site_name|escape}" required/>
      </div>
      <div class="field">
        <label for="site_url">Website URL</label>
        <input type="url" id="site_url" name="site_url"
               value="{$v.site_url|escape}"/>
      </div>
      <div class="field">
        <label for="username">Username / Email</label>
        <input type="text" id="username" name="username"
               value="{$v.username|escape}" required/>
      </div>
      <div class="field">
        <label for="category">Category</label>
        <select id="category" name="category">
          <option value="">Select a category…</option>
          {foreach ['Social','Work','Finance','Other'] as $cat}
            <option {if $v.category == $cat}selected{/if}>{$cat}</option>
          {/foreach}
        </select>
      </div>

      <!-- Password Generator -->
      <div class="generator-box">
        <h4> Password Generator</h4>
        <div class="gen-output" id="gen-output">Click Generate →</div>
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
        <label for="password">New Password <span style="color:var(--muted);font-size:11px;">(leave blank to keep current)</span></label>
        <div class="input-icon-wrap">
          <input type="password" id="password" name="password"
                 placeholder="Leave blank to keep current password"
                 oninput="liveStrength(this.value)"/>
          <span class="input-action" onclick="togglePw('password',this)">👁</span>
        </div>
        <div class="strength-bar">
          <div class="strength-fill" id="strength-fill" style="width:0%;"></div>
        </div>
        <div style="display:flex;justify-content:space-between;margin-top:4px;">
          <span id="strength-label" style="font-size:11px;color:var(--muted);">Leave blank to keep current</span>
          <span id="strength-score" style="font-size:11px;color:var(--muted);"></span>
        </div>
      </div>

      <div class="field">
        <label for="notes">Notes (optional)</label>
        <textarea id="notes" name="notes" rows="3"
                  style="resize:vertical;">{$v.notes|escape}</textarea>
      </div>

      <div style="display:flex;gap:12px;margin-top:8px;">
        <button type="submit" class="btn btn-primary" style="flex:1;">Save Changes</button>
        <a href="vault.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
<script src="../js/strength.js"></script>
<script src="../js/generator.js"></script>
{literal}
<script>
function togglePw(id, btn) {
  const input = document.getElementById(id);
  input.type  = input.type === 'password' ? 'text' : 'password';
  btn.textContent = input.type === 'password' ? '👁' : '🙈';
}
function liveStrength(pw) {
  const { score, label, color } = scorePassword(pw);
  document.getElementById('strength-fill').style.width      = score + '%';
  document.getElementById('strength-fill').style.background = color;
  document.getElementById('strength-label').textContent     = pw ? label : 'Leave blank to keep current';
  document.getElementById('strength-score').textContent     = pw ? score + '/100' : '';
}
document.addEventListener('DOMContentLoaded', initGenerator);
</script>
{/literal}
{include file="foot.tpl"}
