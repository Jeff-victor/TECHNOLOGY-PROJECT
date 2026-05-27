{include file="head.tpl" page_title="Sign Up"}
<div class="auth-page">
  <div class="auth-card">
    <div class="auth-logo">
      <div class="auth-logo-mark">🔐</div>
      <h1>Pass<span>Guard</span></h1>
      <p>Create your secure vault</p>
    </div>

    {if $error}
      <div class="warning-box" style="color:var(--danger);border-color:var(--danger);">
        ⚠️ {$error|escape}
      </div>
    {/if}

    <form method="POST">
      <div class="field">
        <label for="username">Username</label>
        <input type="text" id="username" name="username"
               value="{$username_value|escape}"
               placeholder="Choose a username" required autofocus/>
      </div>
      <div class="field">
        <label for="password">Master Password</label>
        <div class="input-icon-wrap">
          <input type="password" id="password" name="password"
                 placeholder="••••••••" required oninput="liveStrength(this.value)"/>
          <span class="input-action" onclick="togglePw('password',this)">👁</span>
        </div>
        <div class="strength-bar">
          <div class="strength-fill" id="strength-fill"
               style="width:{if $score}{$score.score}{else}0{/if}%;
                      background:{if $score}{$score.color}{else}var(--muted){/if};"></div>
        </div>
        <span id="strength-label" style="font-size:11px;color:var(--muted);">
          {if $score}{$score.label|escape}{else}Enter a password{/if}
        </span>
      </div>
      <div class="field">
        <label for="confirm">Confirm Master Password</label>
        <div class="input-icon-wrap">
          <input type="password" id="confirm" name="confirm"
                 placeholder="••••••••" required/>
          <span class="input-action" onclick="togglePw('confirm',this)">👁</span>
        </div>
      </div>
      <div class="warning-box">
        ⚠️ Your master password cannot be recovered if lost. Store it somewhere safe.
      </div>
      <button type="submit" class="btn btn-primary btn-block">Create Account →</button>
    </form>

    <div class="auth-divider">Already have an account?</div>
    <a href="login.php" class="btn btn-secondary btn-block">Sign In</a>
  </div>
</div>
<script src="../js/strength.js"></script>
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
  document.getElementById('strength-label').textContent     = pw ? label : 'Enter a password';
}
</script>
{/literal}
{include file="foot.tpl"}
