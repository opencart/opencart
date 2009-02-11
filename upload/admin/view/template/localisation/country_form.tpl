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
    <input type="text" name="name" value="<?php echo $name; ?>" />
    <br />
    <?php if ($error_name) { ?>
    <span class="error"><?php echo $error_name; ?></span>
    <?php } ?>
    <br />
    <?php echo $entry_iso_code_2; ?><br />
    <input type="text" name="iso_code_2" value="<?php echo $iso_code_2; ?>" />
    <br />
    <br />
    <?php echo $entry_iso_code_3; ?><br />
    <input type="text" name="iso_code_3" value="<?php echo $iso_code_3; ?>" />
    <br />
    <br />
    <?php echo $entry_address_format; ?><br />
    <textarea name="address_format" cols="40" rows="5"><?php echo $address_format; ?></textarea>
  </div>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>