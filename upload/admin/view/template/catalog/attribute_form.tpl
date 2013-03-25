<?php echo $header; ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php if ($error_warning) { ?>
<div class="alert alert-error"><?php echo $error_warning; ?></div>
<?php } ?>
<h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
  <div class="buttons"><a onclick="$('#form').submit();" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></a> <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
  <div class="control-group">
    <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_name; ?></label>
    <div class="controls">
      <?php foreach ($languages as $language) { ?>
      <input type="text" name="attribute_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($attribute_description[$language['language_id']]) ? $attribute_description[$language['language_id']]['name'] : ''; ?>" />
      <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
      <?php if (isset($error_name[$language['language_id']])) { ?>
      <span class="error"><?php echo $error_name[$language['language_id']]; ?></span><br />
      <?php } ?>
      <?php } ?>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="input-attribute-group"><?php echo $entry_attribute_group; ?></label>
    <div class="controls">
      <select name="attribute_group_id" id="input-attribute-group">
        <?php foreach ($attribute_groups as $attribute_group) { ?>
        <?php if ($attribute_group['attribute_group_id'] == $attribute_group_id) { ?>
        <option value="<?php echo $attribute_group['attribute_group_id']; ?>" selected="selected"><?php echo $attribute_group['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $attribute_group['attribute_group_id']; ?>"><?php echo $attribute_group['name']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
    <div class="controls">
      <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" id="input-sort-order" />
    </div>
  </div>
</form>
<?php echo $footer; ?>