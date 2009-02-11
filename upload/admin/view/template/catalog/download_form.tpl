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
    <input name="download_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo @$download_description[$language['language_id']]['name']; ?>" />
    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
    <?php if (@$error_name[$language['language_id']]) { ?>
    <span class="error"><?php echo $error_name[$language['language_id']]; ?></span><br />
    <?php } ?>
    <?php } ?>
    <br />
    <?php echo $entry_filename; ?><br />
    <input type="file" name="download" value="" />
    <br />
    <?php if ($error_file) { ?>
    <span class="error"><?php echo $error_file; ?></span>
    <?php } ?>
    <br />
    <?php echo $entry_mask; ?><br />
    <input type="input" name="mask" value="<?php echo $mask; ?>" />
    <br />
    <?php if ($error_mask) { ?>
    <span class="error"><?php echo $error_mask; ?></span>
    <?php } ?>
    <br />
    <?php echo $entry_remaining; ?><br />
    <input type="input" name="remaining" value="<?php echo $remaining; ?>" size="6" />
  </div>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>