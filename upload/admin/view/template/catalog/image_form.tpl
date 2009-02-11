<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page"><span class="required">*</span> <?php echo $entry_title; ?><br />
    <?php foreach ($languages as $language) { ?>
    <input name="image_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo @$image_description[$language['language_id']]['title']; ?>" />
    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
    <?php if (@$error_title[$language['language_id']]) { ?>
    <span class="error"><?php echo $error_title[$language['language_id']]; ?></span><br />
    <?php } ?>
    <?php } ?>
    <br />
    <?php echo $entry_filename; ?><br />
    <input type="file" name="image" value="" />
    <br />
    <?php if ($error_file) { ?>
    <span class="error"><?php echo $error_file; ?></span>
    <?php } ?>
  </div>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>