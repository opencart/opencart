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
    <input name="weight_class[<?php echo $language['language_id']; ?>][title]" value="<?php echo @$weight_class[$language['language_id']]['title']; ?>" />
    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
    <?php if (@$error_title[$language['language_id']]) { ?>
    <span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
    <?php } ?>
    <br />
    <span class="required">*</span> <?php echo $entry_unit; ?><br />
    <input name="weight_class[<?php echo $language['language_id']; ?>][unit]" value="<?php echo @$weight_class[$language['language_id']]['unit']; ?>" />
    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
    <?php if (@$error_unit[$language['language_id']]) { ?>
    <span class="error"><?php echo $error_unit[$language['language_id']]; ?></span>
    <?php } ?>
    <br />
    <?php } ?>
  </div>
  <div id="tab_data" class="page">
    <?php foreach ($weight_tos as $weight_to) { ?>
    <?php echo $weight_to['title']; ?>:<br />
    <input type="text" name="weight_rule[<?php echo $weight_to['weight_class_id']; ?>]" value="<?php echo @$weight_rule[$weight_to['weight_class_id']]['rule']; ?>" />
    <br />
    <br />
    <?php } ?>
  </div>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>