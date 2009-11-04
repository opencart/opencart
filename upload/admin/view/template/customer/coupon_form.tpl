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
        <td width="25%"><span class="required">*</span> <?php echo $entry_name; ?></td>
        <td><input name="coupon_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($coupon_description[$language['language_id']]) ? $coupon_description[$language['language_id']]['name'] : ''; ?>" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
          <br />
          <?php if (isset($error_name[$language['language_id']])) { ?>
          <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_description; ?></td>
        <td><textarea name="coupon_description[<?php echo $language['language_id']; ?>][description]" cols="40" rows="5"><?php echo isset($coupon_description[$language['language_id']]) ? $coupon_description[$language['language_id']]['description'] : ''; ?></textarea> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" />
          <br />
          <?php if (isset($error_description[$language['language_id']])) { ?>
          <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
          <?php } ?></td>
      </tr>
      <?php } ?>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_code; ?></td>
        <td><input type="text" name="code" value="<?php echo $code; ?>" />
          <br />
          <?php if ($error_code) { ?>
          <span class="error"><?php echo $error_code; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_type; ?></td>
        <td><select name="type">
            <?php if ($type == 'P') { ?>
            <option value="P" selected="selected"><?php echo $text_percent; ?></option>
            <?php } else { ?>
            <option value="P"><?php echo $text_percent; ?></option>
            <?php } ?>
            <?php if ($type == 'F') { ?>
            <option value="F" selected="selected"><?php echo $text_amount; ?></option>
            <?php } else { ?>
            <option value="F"><?php echo $text_amount; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_discount; ?></td>
        <td><input type="text" name="discount" value="<?php echo $discount; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_total; ?></td>
        <td><input type="text" name="total" value="<?php echo $total; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_shipping; ?></td>
        <td><?php if ($shipping) { ?>
          <input type="radio" name="shipping" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="shipping" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="shipping" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="shipping" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_product; ?></td>
        <td><div class="scrollbox">
            <?php $class = 'odd'; ?>
            <?php foreach ($products as $product) { ?>
            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
            <div class="<?php echo $class; ?>">
              <?php if (in_array($product['product_id'], $coupon_product)) { ?>
              <input type="checkbox" name="coupon_product[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
              <?php echo $product['name']; ?>
              <?php } else { ?>
              <input type="checkbox" name="coupon_product[]" value="<?php echo $product['product_id']; ?>" />
              <?php echo $product['name']; ?>
              <?php } ?>
            </div>
            <?php } ?>
          </div></td>
      </tr>
      <tr>
        <td><?php echo $entry_date_start; ?></td>
        <td><input type="text" name="date_start" value="<?php echo $date_start; ?>" size="12" id="date_start" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_date_end; ?></td>
        <td><input type="text" name="date_end" value="<?php echo $date_end; ?>" size="12" id="date_end" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_uses_total; ?></td>
        <td><input type="text" name="uses_total" value="<?php echo $uses_total; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_uses_customer; ?></td>
        <td><input type="text" name="uses_customer" value="<?php echo $uses_customer; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_status; ?></td>
        <td><select name="status">
            <?php if ($status) { ?>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <option value="0"><?php echo $text_disabled; ?></option>
            <?php } else { ?>
            <option value="1"><?php echo $text_enabled; ?></option>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <?php } ?>
          </select></td>
      </tr>
    </table>
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
