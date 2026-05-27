{include file="head.tpl" page_title="Manage Users"}
{include file="admin_nav.tpl" active_page="users"}
<div class="page-body">
  <div class="page-header">
    <h2>User Management <span class="page-sub">— {$users|@count} user{if $users|@count != 1}s{/if}</span></h2>
  </div>

  {if $msg}
    <div class="warning-box" style="max-width:800px;color:{if $msgType == 'success'}var(--strong){else}var(--danger){/if};border-color:{if $msgType == 'success'}var(--strong){else}var(--danger){/if};">
      {if $msgType == 'success'}✅{else}⚠️{/if} {$msg|escape}
    </div>
  {/if}

  <form method="GET" class="search-row" style="max-width:500px;">
    <input type="search" name="search" placeholder=" Search users…" value="{$search|escape}"/>
    <button type="submit" class="btn btn-secondary btn-sm">Search</button>
    {if $search}
      <a href="admin_users.php" class="btn btn-secondary btn-sm">✕ Clear</a>
    {/if}
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
        {foreach $users as $u}
        <tr{if !$u.is_active} style="opacity:0.5;"{/if}>
          <td>
            <strong>{$u.username|escape}</strong>
            {if $u.id == $currentId}<span class="tag" style="margin-left:6px;">You</span>{/if}
          </td>
          <td>
            {if $u.is_admin}
              <span class="badge" style="background:#3d8bff20;color:var(--accent2);">Admin</span>
            {else}
              <span class="tag">User</span>
            {/if}
          </td>
          <td>
            {if $u.is_active}
              <span class="badge badge-strong">Active</span>
            {else}
              <span class="badge badge-weak">Disabled</span>
            {/if}
          </td>
          <td>{$u.cred_count}</td>
          <td style="color:{$u.color};font-weight:600;">{if $u.cred_count > 0}{$u.avg_score}/100{else}—{/if}</td>
          <td style="font-size:12px;color:var(--muted);">{$u.created_at|default:'—'|escape}</td>
          <td style="font-size:12px;color:var(--muted);">{if $u.last_login}{$u.last_login|escape}{else}Never{/if}</td>
          <td>
            <div class="actions" style="flex-wrap:wrap;">
              {if $u.id != $currentId}
                <form method="POST" style="display:inline;">
                  <input type="hidden" name="user_id" value="{$u.id}"/>
                  <input type="hidden" name="action" value="toggle"/>
                  <button type="submit" class="btn btn-secondary btn-sm">
                    {if $u.is_active} Disable{else} Enable{/if}
                  </button>
                </form>
                <form method="POST" style="display:inline;">
                  <input type="hidden" name="user_id" value="{$u.id}"/>
                  <input type="hidden" name="action" value="{if $u.is_admin}demote{else}promote{/if}"/>
                  <button type="submit" class="btn btn-secondary btn-sm">
                    {if $u.is_admin}⬇ Demote{else} Promote{/if}
                  </button>
                </form>
                <form method="POST" style="display:inline;"
                      onsubmit="return confirm('Delete user {$u.username|escape} and ALL their data? This cannot be undone.')">
                  <input type="hidden" name="user_id" value="{$u.id}"/>
                  <input type="hidden" name="action" value="delete"/>
                  <button type="submit" class="btn btn-sm" style="background:var(--danger);color:#fff;border:none;"> Delete</button>
                </form>
              {else}
                <span style="font-size:11px;color:var(--muted);">—</span>
              {/if}
            </div>
          </td>
        </tr>
        {/foreach}
      </tbody>
    </table>
  </div>
</div>
{include file="foot.tpl"}
