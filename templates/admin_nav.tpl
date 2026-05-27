<nav class="topbar">
  <a href="admin_dashboard.php" class="topbar-logo">Pass<span>Guard</span> <span style="font-size:11px;color:var(--danger);margin-left:6px;">ADMIN</span></a>
  <div class="topbar-nav">
    <a href="admin_dashboard.php" class="{if $active_page == 'dashboard'}active{/if}"> Overview</a>
    <a href="admin_users.php" class="{if $active_page == 'users'}active{/if}"> Users</a>
    <a href="admin_audit.php" class="{if $active_page == 'audit'}active{/if}"> Audit Log</a>
    <a href="admin_categories.php" class="{if $active_page == 'categories'}active{/if}"> Categories</a>
    <a href="vault.php"> Vault</a>
    <a href="logout.php" class="danger"> Lock</a>
  </div>
</nav>
