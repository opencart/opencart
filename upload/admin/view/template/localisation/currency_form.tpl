<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page"> <span class="required">*</span> <?php echo $entry_title; ?><br />
    <input type="text" name="title" value="<?php echo $title; ?>" />
    <br />
    <?php if ($error_title) { ?>
    <span class="error"><?php echo $error_title; ?></span>
    <?php } ?>
    <br />
    <span class="required">*</span> <?php echo $entry_code; ?><br />
    <input type="text" name="code" value="<?php echo $code; ?>" />
    <br />
    <?php if ($error_code) { ?>
    <span class="error"><?php echo $error_code; ?></span>
    <?php } ?>
    <br />
    <?php echo $entry_symbol_left; ?><br />
    <input type="text" name="symbol_left" value="<?php echo $symbol_left; ?>" />
    <br />
    <br />
    <?php echo $entry_symbol_right; ?><br />
    <input type="text" name="symbol_right" value="<?php echo $symbol_right; ?>" />
    <br />
    <br />
    <?php echo $entry_decimal_place; ?><br />
    <input type="text" name="decimal_place" value="<?php echo $decimal_place; ?>" />
    <br />
    <br />
    <?php echo $entry_value; ?><br />
    <input type="text" name="value" value="<?php echo $value; ?>" />
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
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
