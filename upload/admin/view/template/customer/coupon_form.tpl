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
    <span class="required">*</span> <?php echo $entry_name; ?><br />
    <input name="coupon_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo @$coupon_description[$language['language_id']]['name']; ?>" />
    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
    <?php if (@$error_name[$language['language_id']]) { ?>
    <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
    <?php } ?>
    <br />
    <span class="required">*</span> <?php echo $entry_description; ?><br />
    <textarea name="coupon_description[<?php echo $language['language_id']; ?>][description]" cols="40" rows="5"><?php echo @$coupon_description[$language['language_id']]['description']; ?></textarea>
    <br />
    <?php if (@$error_description[$language['language_id']]) { ?>
    <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
    <?php } ?>
    <br />
    <?php } ?>
  </div>
  <div id="tab_data" class="page"><span class="required">*</span> <?php echo $entry_code; ?><br />
    <input type="text" name="code" value="<?php echo $code; ?>" />
    <br />
    <?php if ($error_code) { ?>
    <span class="error"><?php echo $error_code; ?></span>
    <?php } ?>
    <br />
    <?php echo $entry_discount; ?><br />
    <input type="text" name="discount" value="<?php echo $discount; ?>" />
    <br />
    <br />
    <?php echo $entry_prefix; ?><br />
    <select name="prefix">
      <?php if ($prefix != '-') { ?>
      <option value="%" selected="selected"><?php echo $text_percent; ?></option>
      <option value="-"><?php echo $text_minus; ?></option>
      <?php } else { ?>
      <option value="%"><?php echo $text_percent; ?></option>
      <option value="-" selected="selected"><?php echo $text_minus; ?></option>
      <?php } ?>
    </select>
    <br />
    <br />
    <?php echo $entry_shipping; ?><br />
    <?php if ($shipping) { ?>
    <input type="radio" name="shipping" value="1" checked />
    <?php echo $text_yes; ?>
    <input type="radio" name="shipping" value="0" />
    <?php echo $text_no; ?>
    <?php } else { ?>
    <input type="radio" name="shipping" value="1" />
    <?php echo $text_yes; ?>
    <input type="radio" name="shipping" value="0" checked />
    <?php echo $text_no; ?>
    <?php } ?>
    <br />
    <br />
    <?php echo $entry_date_start; ?><br />
    <input type="text" name="date_start" value="<?php echo $date_start; ?>" size="12" id="date_start" />
    <br />
    <br />
    <?php echo $entry_date_end; ?><br />
    <input type="text" name="date_end" value="<?php echo $date_end; ?>" size="12" id="date_end" />
    <br />
    <br />
    <?php echo $entry_uses_total; ?><br />
    <input type="text" name="uses_total" value="<?php echo $uses_total; ?>" />
    <br />
    <br />
    <?php echo $entry_uses_customer; ?><br />
    <input type="text" name="uses_customer" value="<?php echo $uses_customer; ?>" />
    <br />
    <br />
    <?php echo $entry_status; ?><br />
    <select name="status">
      <?php if ($status) { ?>
      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
      <option value="0"><?php echo $text_disabled; ?></option>
      <?php } else { ?>
      <option value="1"><?php echo $text_enabled; ?></option>
      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
      <?php } ?>
    </select>
  </div>
</form>
<link rel="stylesheet" type="text/css" href="view/stylesheet/datepicker.css" />
<script type="text/javascript" src="view/javascript/jquery/ui/ui.core.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.min.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date_start').datepicker({dateFormat: 'yy-mm-dd'});
	
	$('#date_end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>