{include file="head.tpl" page_title="Audit Log"}
{include file="admin_nav.tpl" active_page="audit"}
<div class="page-body">
  <div class="page-header">
    <h2>Audit Log <span class="page-sub">— {$totalRows} event{if $totalRows != 1}s{/if}</span></h2>
  </div>

  <!-- Quick stats -->
  <div class="dash-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:24px;">
    <div class="stat-card">
      <div class="stat-label">Total Events</div>
      <div class="stat-value">{$totalEvents}</div>
      <div class="stat-sub">all time</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Failed Logins</div>
      <div class="stat-value" style="color:var(--danger);">{$loginFails}</div>
      <div class="stat-sub">all time</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Today's Events</div>
      <div class="stat-value" style="color:var(--accent);">{$todayEvents}</div>
      <div class="stat-sub">{$today}</div>
    </div>
  </div>

  <!-- Filters -->
  <form method="GET" class="search-row" style="flex-wrap:wrap;">
    <input type="search" name="user" placeholder=" Filter by username…"
           value="{$filterUser|escape}" style="flex:1;min-width:160px;"/>
    <input type="search" name="ip" placeholder="Filter by IP…"
           value="{$filterIp|escape}" style="width:160px;"/>
    <select name="action" onchange="this.form.submit()" style="width:180px;">
      <option value="">All actions</option>
      {foreach $actions as $a}
        <option {if $filterAction == $a}selected{/if}>{$a|escape}</option>
      {/foreach}
    </select>
    <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
    {if $filterAction || $filterUser || $filterIp}
      <a href="admin_audit.php" class="btn btn-secondary btn-sm">✕ Clear</a>
    {/if}
  </form>

  <!-- Log table -->
  <div class="section-card">
    <table class="cred-table">
      <thead>
        <tr><th>Time</th><th>User</th><th>Action</th><th>Credential ID</th><th>IP Address</th></tr>
      </thead>
      <tbody>
        {if $logs|@count == 0}
          <tr>
            <td colspan="5" style="text-align:center;padding:48px;color:var(--muted);">
              No events found matching your filters.
            </td>
          </tr>
        {else}
          {foreach $logs as $log}
          <tr>
            <td style="font-size:12px;color:var(--muted);white-space:nowrap;">{$log.created_at|escape}</td>
            <td style="font-weight:600;">
              {$log.username|default:'Deleted user'|escape}
              {if $log.action == 'LOGIN_FAIL'}
                <span class="badge badge-weak" style="margin-left:4px;font-size:9px;">FAILED</span>
              {/if}
            </td>
            <td>
              <span style="color:{$log.actionColor};font-weight:500;">{$log.icon} {$log.action|escape}</span>
            </td>
            <td style="font-size:11px;color:var(--muted);">{if $log.credential_id}{$log.credential_id|escape}{else}—{/if}</td>
            <td style="font-size:11px;color:var(--muted);">{$log.ip_address|default:'—'|escape}</td>
          </tr>
          {/foreach}
        {/if}
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  {if $totalPages > 1}
  <div style="display:flex;justify-content:center;gap:8px;margin-top:20px;">
    {if $page > 1}
      <a href="{$prevUrl|escape}" class="btn btn-secondary btn-sm">← Prev</a>
    {/if}
    <span style="padding:7px 14px;font-size:12px;color:var(--muted);">
      Page {$page} of {$totalPages}
    </span>
    {if $page < $totalPages}
      <a href="{$nextUrl|escape}" class="btn btn-secondary btn-sm">Next →</a>
    {/if}
  </div>
  {/if}
</div>
{include file="foot.tpl"}
