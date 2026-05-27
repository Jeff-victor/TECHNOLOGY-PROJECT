{include file="head.tpl" page_title="Login"}
<div class="auth-page">
  <div class="auth-card">
    <div class="auth-logo">
      <div class="auth-logo-mark">🔐</div>
      <h1>Pass<span>Guard</span></h1>
      <p>Your secure password vault</p>
    </div>

    {if $timeout}
      <div class="warning-box" style="color:var(--warn);border-color:var(--warn);">
        ⏱ Session expired after 15 minutes of inactivity.
      </div>
    {/if}

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
{literal}
<script>
function togglePw(id, btn) {
  const input = document.getElementById(id);
  input.type  = input.type === 'password' ? 'text' : 'password';
  btn.textContent = input.type === 'password' ? '👁' : '🙈';
}
</script>
{/literal}
{include file="foot.tpl"}
