<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a><a tab="#tab_data"><?php echo $tab_data; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page">
    <?php foreach ($languages as $language) { ?>
    <span class="required">*</span> <?php echo $entry_title; ?><br />
    <input name="information_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo @$information_description[$language['language_id']]['title']; ?>" />
    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
    <?php if (@$error_title[$language['language_id']]) { ?>
    <span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
    <?php } ?>
    <br />
    <span class="required">*</span> <?php echo $entry_description; ?><br />
    <textarea name="information_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo @$information_description[$language['language_id']]['description']; ?></textarea>
    <?php if (@$error_description[$language['language_id']]) { ?>
    <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
    <?php } ?>
    &nbsp;<br />
    <?php } ?>
  </div>
  <div id="tab_data" class="page"><?php echo $entry_sort_order; ?><br />
    <input name="sort_order" value="<?php echo $sort_order; ?>" size="1" />
  </div>
</form>
<script type="text/javascript" src="view/javascript/fckeditor/fckeditor.js"></script>
<script type="text/javascript"><!--
var sBasePath           = document.location.href.replace(/index\.php.*/, 'view/javascript/fckeditor/');
<?php foreach ($languages as $language) { ?>
var oFCKeditor          = new FCKeditor('description<?php echo $language['language_id']; ?>');
	oFCKeditor.BasePath = sBasePath;
	oFCKeditor.Value	= document.getElementById('description<?php echo $language['language_id']; ?>').value;
	oFCKeditor.Width    = '100%';
	oFCKeditor.Height   = '300';
	oFCKeditor.ReplaceTextarea();
<?php } ?>	  
//--></script>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>