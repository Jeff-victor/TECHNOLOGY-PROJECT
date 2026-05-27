{include file="head.tpl" page_title="Categories"}
{include file="admin_nav.tpl" active_page="categories"}
<div class="page-body">
  <div class="page-header">
    <h2>Category Management <span class="page-sub">— {$categories|@count} categor{if $categories|@count != 1}ies{else}y{/if}</span></h2>
  </div>

  {if $msg}
    <div class="warning-box" style="max-width:700px;color:{if $msgType == 'success'}var(--strong){else}var(--danger){/if};border-color:{if $msgType == 'success'}var(--strong){else}var(--danger){/if};">
      {if $msgType == 'success'}✅{else}⚠️{/if} {$msg|escape}
    </div>
  {/if}

  <!-- Add new category -->
  <div class="form-card" style="margin-bottom:24px;max-width:700px;">
    <h3 style="font-family:var(--font-head);font-size:15px;font-weight:700;margin-bottom:16px;"> Add New Category</h3>
    <form method="POST" style="display:flex;gap:12px;align-items:flex-end;">
      <input type="hidden" name="action" value="add"/>
      <div class="field" style="flex:1;margin-bottom:0;">
        <label for="new_cat_name">Category Name</label>
        <input type="text" id="new_cat_name" name="name"
               placeholder="e.g. Gaming, Streaming, Education…" required/>
      </div>
      <button type="submit" class="btn btn-primary" style="height:42px;">Add Category</button>
    </form>
  </div>

  <!-- Categories list -->
  <div class="section-card" style="max-width:700px;">
    <table class="cred-table">
      <thead>
        <tr><th>Category</th><th>Credentials Using</th><th>Actions</th></tr>
      </thead>
      <tbody>
        {foreach $categories as $cat}
        <tr>
          <td style="font-weight:600;font-size:15px;"> {$cat.name|escape}</td>
          <td>
            <span style="font-weight:600;">{$cat.usage_count}</span>
            <span style="color:var(--muted);font-size:12px;"> credential{if $cat.usage_count != 1}s{/if}</span>
          </td>
          <td>
            <div class="actions" style="flex-wrap:wrap;">
              <form method="POST" style="display:inline-flex;gap:6px;align-items:center;"
                    onsubmit="var n=this.querySelector('.rename-input').value; return n?true:(alert('Enter a name'),false);">
                <input type="hidden" name="action" value="rename"/>
                <input type="hidden" name="cat_id" value="{$cat.id}"/>
                <input type="text" name="name" class="rename-input" placeholder="New name…"
                       style="width:120px;padding:6px 10px;font-size:12px;"/>
                <button type="submit" class="btn btn-secondary btn-sm">Rename</button>
              </form>
              <form method="POST" style="display:inline;"
                    onsubmit="return confirm('Delete &quot;{$cat.name|escape}&quot;? {$cat.usage_count} credential(s) will become uncategorized.');">
                <input type="hidden" name="action" value="delete"/>
                <input type="hidden" name="cat_id" value="{$cat.id}"/>
                <button type="submit" class="btn btn-sm" style="background:var(--danger);color:#fff;border:none;"> Delete</button>
              </form>
            </div>
          </td>
        </tr>
        {/foreach}

        {if $categories|@count == 0}
          <tr>
            <td colspan="3" style="text-align:center;padding:48px;color:var(--muted);">
              No categories yet. Add your first one above.
            </td>
          </tr>
        {/if}

        {if $uncategorized > 0}
        <tr style="opacity:0.6;">
          <td style="font-weight:600;font-size:15px;color:var(--muted);"> Uncategorized</td>
          <td>
            <span style="font-weight:600;">{$uncategorized}</span>
            <span style="color:var(--muted);font-size:12px;"> credential{if $uncategorized != 1}s{/if}</span>
          </td>
          <td style="font-size:11px;color:var(--muted);">Default — cannot be removed</td>
        </tr>
        {/if}
      </tbody>
    </table>
  </div>
</div>
{include file="foot.tpl"}
