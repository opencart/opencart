<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/payment.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_merchant_id; ?></td>
          <td><input type="text" name="google_checkout_merchant_id" value="<?php echo $google_checkout_merchant_id; ?>" />
            <?php if ($error_merchant_id) { ?>
            <span class="error"><?php echo $error_merchant_id; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_merchant_key; ?></td>
          <td><input type="text" name="google_checkout_merchant_key" value="<?php echo $google_checkout_merchant_key; ?>" />
            <?php if ($error_merchant_key) { ?>
            <span class="error"><?php echo $error_merchant_key; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_test; ?></td>
          <td><?php if ($google_checkout_test) { ?>
            <input type="radio" name="google_checkout_test" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="google_checkout_test" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="google_checkout_test" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="google_checkout_test" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="google_checkout_status">
              <?php if ($google_checkout_status) { ?>
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
<?php echo $footer; ?>