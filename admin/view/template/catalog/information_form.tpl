<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page">
    <table class="form">
      <?php foreach ($languages as $language) { ?>
      <tr>
        <td width="25%"><span class="required">*</span> <?php echo $entry_title; ?></td>
        <td><input name="information_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo @$information_description[$language['language_id']]['title']; ?>" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
          <br />
          <?php if (@$error_title[$language['language_id']]) { ?>
          <span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_description; ?></td>
        <td><textarea name="information_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo @$information_description[$language['language_id']]['description']; ?></textarea> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" />
          <?php if (@$error_description[$language['language_id']]) { ?>
          <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
          <?php } ?></td>
      </tr>
      <?php } ?>
      <tr>
        <td><?php echo $entry_sort_order; ?></td>
        <td><input name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
      </tr>
    </table>
  </div>
</form>
<script type="text/javascript" src="view/javascript/fckeditor/fckeditor.js"></script>
<script type="text/javascript"><!--
var sBasePath           = document.location.href.replace(/index\.php.*/, 'view/javascript/fckeditor/');
<?php foreach ($languages as $language) { ?>
var oFCKeditor          = new FCKeditor('description<?php echo $language['language_id']; ?>');
	oFCKeditor.BasePath = sBasePath;
	oFCKeditor.Value	= document.getElementById('description<?php echo $language['language_id']; ?>').value;
	oFCKeditor.Width    = '520';
	oFCKeditor.Height   = '300';
	oFCKeditor.ReplaceTextarea();
<?php } ?>	  
//--></script>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
