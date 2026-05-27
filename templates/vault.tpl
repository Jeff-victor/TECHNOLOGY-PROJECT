{include file="head.tpl" page_title="Vault"}
{include file="nav.tpl" active_page="vault"}
<div class="page-body">
  <div class="page-header">
    <h2>My Vault <span class="page-sub">— {$total} credential{if $total != 1}s{/if}</span></h2>
    <a href="add.php" class="btn btn-primary">+ Add Credential</a>
  </div>

  <!-- Search & Filter -->
  <form method="GET" class="search-row">
    <input type="search" name="search" placeholder=" Search credentials…"
           value="{$search|escape}"/>
    <select name="category" onchange="this.form.submit()">
      <option value="">All categories</option>
      {foreach ['Social','Work','Finance','Other'] as $cat}
        <option {if $category == $cat}selected{/if}>{$cat}</option>
      {/foreach}
    </select>
    <button type="submit" class="btn btn-secondary btn-sm">Search</button>
    {if $search || $category}
      <a href="vault.php" class="btn btn-secondary btn-sm">✕ Clear</a>
    {/if}
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
        {if $creds|@count == 0}
          <tr>
            <td colspan="6" style="text-align:center;padding:48px;color:var(--muted);">
              No credentials found.
              <a href="add.php" style="color:var(--accent);">Add your first one →</a>
            </td>
          </tr>
        {else}
          {foreach $creds as $c}
          <tr>
            <td>
              <strong>{$c.site_name|escape}</strong>
              {if $c.site_url}
                <br><a href="{$c.site_url|escape}" target="_blank"
                       style="font-size:11px;color:var(--muted);">{$c.site_url|escape}</a>
              {/if}
            </td>
            <td>{$c.username|escape}</td>
            <td>
              <span class="pw-cell" data-pw="{$c.password|escape}">••••••••</span>
              <button type="button" class="btn-icon" onclick="togglePwCell(this)" title="Show/hide">👁</button>
              <button type="button" class="btn-icon" onclick="copyPw(this)" title="Copy">📋</button>
            </td>
            <td>
              <span style="color:{$c.strength.color};font-weight:600;">{$c.strength.label}</span>
              <br><span style="font-size:11px;color:var(--muted);">{$c.strength.score}/100</span>
            </td>
            <td>{$c.category|default:'—'|escape}</td>
            <td class="actions">
              <a href="edit.php?id={$c.id|escape}" class="btn btn-secondary btn-sm">Edit</a>
              <a href="delete.php?id={$c.id|escape}"
                 class="btn btn-sm"
                 style="background:var(--danger);color:#fff;border:none;"
                 onclick="return confirm('Delete this credential?')">Delete</a>
            </td>
          </tr>
          {/foreach}
        {/if}
      </tbody>
    </table>
  </div>
</div>
{literal}
<script>
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
</script>
{/literal}
{include file="foot.tpl"}
