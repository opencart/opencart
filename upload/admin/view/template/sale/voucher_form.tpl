<?php echo $header; ?>
<div id="content">
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_code; ?></td>
          <td><input type="text" name="code" value="<?php echo $code; ?>" />
            <?php if ($error_code) { ?>
            <span class="error"><?php echo $error_code; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_from_name; ?></td>
          <td><input type="text" name="from_name" value="<?php echo $from_name; ?>" />
            <?php if ($error_from_name) { ?>
            <span class="error"><?php echo $error_from_name; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_from_email; ?></td>
          <td><input type="text" name="from_email" value="<?php echo $from_email; ?>" />
            <?php if ($error_from_email) { ?>
            <span class="error"><?php echo $error_from_email; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_to_name; ?></td>
          <td><input type="text" name="to_name" value="<?php echo $to_name; ?>" />
            <?php if ($error_to_name) { ?>
            <span class="error"><?php echo $error_to_name; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_to_email; ?></td>
          <td><input type="text" name="to_email" value="<?php echo $to_email; ?>" />
            <?php if ($error_to_email) { ?>
            <span class="error"><?php echo $error_to_email; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_message; ?></td>
          <td><textarea name="message" cols="40" rows="5"><?php echo $message; ?></textarea></td>
        </tr>
        <tr>
          <td><?php echo $entry_amount; ?></td>
          <td><input type="text" name="amount" value="<?php echo $amount; ?>" />
            <?php if ($error_amount) { ?>
            <span class="error"><?php echo $error_amount; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_type; ?></td>
          <td><select name="voucher_type_id">
              <option value="0"><?php echo $text_none; ?></option>
              <?php foreach ($voucher_types as $voucher_type) { ?>
              <?php if ($voucher_type['voucher_type_id'] == $voucher_type_id) { ?>
              <option value="<?php echo $voucher_type['voucher_type_id']; ?>" selected="selected"><?php echo $voucher_type['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $voucher_type['voucher_type_id']; ?>"><?php echo $voucher_type['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
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
    </form>
  </div>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});
	
	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script> 
<?php echo $footer; ?>