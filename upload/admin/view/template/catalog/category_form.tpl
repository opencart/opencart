<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page"><span class="required">*</span> <?php echo $entry_name; ?><br />
    <?php foreach ($languages as $language) { ?>
    <input name="category_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo @$category_description[$language['language_id']]['name']; ?>" />
    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
    <?php if (@$error_name[$language['language_id']]) { ?>
    <span class="error"><?php echo $error_name[$language['language_id']]; ?></span><br />
    <?php } ?>
    <?php } ?>
    <br />
    <?php echo $entry_category; ?><br />
    <select name="parent_id">
      <option value="0"><?php echo $text_none; ?></option>
      <?php foreach ($categories as $category) { ?>
      <?php if ($category['category_id'] == $parent_id) { ?>
      <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
      <?php } else { ?>
      <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
      <?php } ?>
      <?php } ?>
    </select>
    <br />
    <br />
    <?php echo $entry_image; ?><br />
    <div id="image" class="preview"></div>
    <select name="image_id" id="image_id" class="image" onchange="$('#image').load('index.php?route=catalog/image/thumb&image_id=' + this.value);">
      <?php foreach ($images as $image) { ?>
      <?php if ($image['image_id'] == $image_id) { ?>
      <option value="<?php echo $image['image_id']; ?>" selected="selected"><?php echo $image['title']; ?></option>
      <?php } else { ?>
      <option value="<?php echo $image['image_id']; ?>"><?php echo $image['title']; ?></option>
      <?php } ?>
      <?php } ?>
    </select>
    <a onclick="openMyModal('index.php?route=catalog/image/upload');" style="text-decoration: underline;"><?php echo $text_upload; ?></a><br />
    <br />
    <?php echo $entry_sort_order; ?><br />
    <input name="sort_order" value="<?php echo $sort_order; ?>" size="1" />
  </div>
</form>
<script type="text/javascript"><!--
$('#image').load('index.php?route=catalog/image/thumb&image_id=' + $('#image_id').attr('value'));
//--></script>
<script type="text/javascript" src="view/javascript/jquery/modal/modal.js"></script>
<link rel="stylesheet" type="text/css" href="view/javascript/jquery/modal/modal.css" />
<script type="text/javascript"><!--
function openMyModal(source) {   
    modalWindow.windowId = 'myModal';   
    modalWindow.width    = 450;   
    modalWindow.height   = 350;   
    modalWindow.content  = '<iframe width="450" height="350" frameborder="0" scrolling="no" allowtransparency="true" src="' + source + '"></iframe>';   
    modalWindow.open();   
};  
//--></script>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>