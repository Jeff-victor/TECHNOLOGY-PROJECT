<?php
/* Smarty version 5.8.0, created on 2026-05-22 13:07:30
  from 'file:admin_categories.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_6a10551285b489_66319178',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'df00240cd30d9e6deba3397981a1b3a7608230fa' => 
    array (
      0 => 'admin_categories.tpl',
      1 => 1779455245,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:head.tpl' => 1,
    'file:admin_nav.tpl' => 1,
    'file:foot.tpl' => 1,
  ),
))) {
function content_6a10551285b489_66319178 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\wamp64\\www\\passguard\\passguard\\templates';
$_smarty_tpl->renderSubTemplate("file:head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('page_title'=>"Categories"), (int) 0, $_smarty_current_dir);
$_smarty_tpl->renderSubTemplate("file:admin_nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active_page'=>"categories"), (int) 0, $_smarty_current_dir);
?>
<div class="page-body">
  <div class="page-header">
    <h2>Category Management <span class="page-sub">— <?php echo $_smarty_tpl->getSmarty()->getModifierCallback('count')($_smarty_tpl->getValue('categories'));?>
 categor<?php if ($_smarty_tpl->getSmarty()->getModifierCallback('count')($_smarty_tpl->getValue('categories')) != 1) {?>ies<?php } else { ?>y<?php }?></span></h2>
  </div>

  <?php if ($_smarty_tpl->getValue('msg')) {?>
    <div class="warning-box" style="max-width:700px;color:<?php if ($_smarty_tpl->getValue('msgType') == 'success') {?>var(--strong)<?php } else { ?>var(--danger)<?php }?>;border-color:<?php if ($_smarty_tpl->getValue('msgType') == 'success') {?>var(--strong)<?php } else { ?>var(--danger)<?php }?>;">
      <?php if ($_smarty_tpl->getValue('msgType') == 'success') {?>✅<?php } else { ?>⚠️<?php }?> <?php echo htmlspecialchars((string)$_smarty_tpl->getValue('msg'), ENT_QUOTES, 'UTF-8', true);?>

    </div>
  <?php }?>

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
        <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('categories'), 'cat');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('cat')->value) {
$foreach0DoElse = false;
?>
        <tr>
          <td style="font-weight:600;font-size:15px;"> <?php echo htmlspecialchars((string)$_smarty_tpl->getValue('cat')['name'], ENT_QUOTES, 'UTF-8', true);?>
</td>
          <td>
            <span style="font-weight:600;"><?php echo $_smarty_tpl->getValue('cat')['usage_count'];?>
</span>
            <span style="color:var(--muted);font-size:12px;"> credential<?php if ($_smarty_tpl->getValue('cat')['usage_count'] != 1) {?>s<?php }?></span>
          </td>
          <td>
            <div class="actions" style="flex-wrap:wrap;">
              <form method="POST" style="display:inline-flex;gap:6px;align-items:center;"
                    onsubmit="var n=this.querySelector('.rename-input').value; return n?true:(alert('Enter a name'),false);">
                <input type="hidden" name="action" value="rename"/>
                <input type="hidden" name="cat_id" value="<?php echo $_smarty_tpl->getValue('cat')['id'];?>
"/>
                <input type="text" name="name" class="rename-input" placeholder="New name…"
                       style="width:120px;padding:6px 10px;font-size:12px;"/>
                <button type="submit" class="btn btn-secondary btn-sm">Rename</button>
              </form>
              <form method="POST" style="display:inline;"
                    onsubmit="return confirm('Delete &quot;<?php echo htmlspecialchars((string)$_smarty_tpl->getValue('cat')['name'], ENT_QUOTES, 'UTF-8', true);?>
&quot;? <?php echo $_smarty_tpl->getValue('cat')['usage_count'];?>
 credential(s) will become uncategorized.');">
                <input type="hidden" name="action" value="delete"/>
                <input type="hidden" name="cat_id" value="<?php echo $_smarty_tpl->getValue('cat')['id'];?>
"/>
                <button type="submit" class="btn btn-sm" style="background:var(--danger);color:#fff;border:none;"> Delete</button>
              </form>
            </div>
          </td>
        </tr>
        <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>

        <?php if ($_smarty_tpl->getSmarty()->getModifierCallback('count')($_smarty_tpl->getValue('categories')) == 0) {?>
          <tr>
            <td colspan="3" style="text-align:center;padding:48px;color:var(--muted);">
              No categories yet. Add your first one above.
            </td>
          </tr>
        <?php }?>

        <?php if ($_smarty_tpl->getValue('uncategorized') > 0) {?>
        <tr style="opacity:0.6;">
          <td style="font-weight:600;font-size:15px;color:var(--muted);"> Uncategorized</td>
          <td>
            <span style="font-weight:600;"><?php echo $_smarty_tpl->getValue('uncategorized');?>
</span>
            <span style="color:var(--muted);font-size:12px;"> credential<?php if ($_smarty_tpl->getValue('uncategorized') != 1) {?>s<?php }?></span>
          </td>
          <td style="font-size:11px;color:var(--muted);">Default — cannot be removed</td>
        </tr>
        <?php }?>
      </tbody>
    </table>
  </div>
</div>
<?php $_smarty_tpl->renderSubTemplate("file:foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
}
}
