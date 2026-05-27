<nav class="topbar">
  <a href="vault.php" class="topbar-logo">Pass<span>Guard</span></a>
  <div class="topbar-nav">
    <a href="vault.php" class="{if $active_page == 'vault'}active{/if}"> Vault</a>
    <a href="dashboard.php" class="{if $active_page == 'dashboard'}active{/if}"> Dashboard</a>
    {if $is_admin}
      <a href="admin_dashboard.php" class="{if $active_page == 'admin'}active{/if}"> Admin</a>
    {/if}
    <a href="logout.php" class="danger">🔒 Lock</a>
  </div>
</nav>
