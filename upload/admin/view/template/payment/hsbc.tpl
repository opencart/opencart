<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/payment.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_client; ?></td>
          <td><input type="text" name="hsbc_client" value="<?php echo $hsbc_client; ?>" />
            <?php if ($error_client) { ?>
            <span class="error"><?php echo $error_client; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_user; ?></td>
          <td><input type="text" name="hsbc_user" value="<?php echo $hsbc_user; ?>" />
            <?php if ($error_user) { ?>
            <span class="error"><?php echo $error_user; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_password; ?></td>
          <td><input type="text" name="hsbc_password" value="<?php echo $hsbc_password; ?>" />
            <?php if ($error_password) { ?>
            <span class="error"><?php echo $error_password; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_gateway; ?></td>
          <td><input type="text" name="hsbc_gateway" value="<?php echo $hsbc_gateway; ?>" />
            <?php if ($error_gateway) { ?>
            <span class="error"><?php echo $error_gateway; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_pas; ?></td>
          <td><input type="text" name="hsbc_pas" value="<?php echo $hsbc_pas; ?>" />
            <?php if ($error_pas) { ?>
            <span class="error"><?php echo $error_pas; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_avs; ?></td>
          <td><?php if ($hsbc_avs) { ?>
            <input type="radio" name="hsbc_avs" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="hsbc_avs" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="hsbc_avs" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="hsbc_avs" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_test; ?></td>
          <td><select name="hsbc_test">
              <?php if ($hsbc_test == 'off') { ?>
              <option value="off" selected="selected"><?php echo $text_off; ?></option>
              <?php } else { ?>
              <option value="off"><?php echo $text_off; ?></option>
              <?php } ?>
              <?php if ($hsbc_test == 'approved') { ?>
              <option value="approved" selected="selected"><?php echo $text_approved; ?></option>
              <?php } else { ?>
              <option value="approved"><?php echo $text_approved; ?></option>
              <?php } ?>
              <?php if ($hsbc_test == 'declined') { ?>
              <option value="declined" selected="selected"><?php echo $text_declined; ?></option>
              <?php } else { ?>
              <option value="declined"><?php echo $text_declined; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_order_status; ?></td>
          <td><select name="hsbc_order_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $hsbc_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_geo_zone; ?></td>
          <td><select name="hsbc_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $hsbc_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="hsbc_status">
              <?php if ($hsbc_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="hsbc_sort_order" value="<?php echo $hsbc_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>