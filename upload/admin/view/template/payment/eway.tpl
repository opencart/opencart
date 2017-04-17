<?php if (isset($header)){ echo $header; } ?>
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
    <div class="left"></div>
    <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/payment.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
  <div class="content">
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">

        <tr id="ewaytestrow">
          <td width="25%"><?php echo $entry_test; ?></td>
          <td width="30%">
            <input type="radio" name="eway_test" value="1"<?php if ($eway_test) { echo ' checked="checked"'; } ?> /> <?php echo $text_yes; ?>
            <input type="radio" name="eway_test" value="0"<?php if (! $eway_test) { echo ' checked="checked"'; } ?> /> <?php echo $text_no; ?>
          </td>
          <td><?php echo $help_testmode; ?></td>
        </tr>

        <tr>
          <td><?php echo $entry_username; ?></td>
          <td>
              <input type="text" name="eway_username" id="eway_username" value="<?php echo $eway_username; ?>" style="width:200px" />
              <br />
              <?php if ($error_username) { ?>
              <span class="error"><?php echo $error_username; ?></span>
              <?php } ?>
            </td>
            <td><?php echo $help_username; ?></td>
        </tr>

        <tr>
          <td><?php echo $entry_password; ?></td>
          <td>
              <input type="text" name="eway_password" id="eway_password" value="<?php echo $eway_password; ?>" style="width:200px" />
              <br />
              <?php if ($error_password) { ?>
              <span class="error"><?php echo $error_password; ?></span>
              <?php } ?>
            </td>
            <td><?php echo $help_password; ?></td>
        </tr>

    	 <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="eway_status">
              <?php if ($eway_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
            <td><?php echo $help_ewaystatus; ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_geo_zone; ?></td>
          <td><select name="eway_standard_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $eway_standard_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
    	 <tr>
          <td><?php echo $entry_order_status; ?></td>
          <td><select name="eway_order_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $eway_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
            <td><?php echo $help_setorderstatus; ?></td>
        </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="eway_sort_order" value="<?php echo $eway_sort_order; ?>" size="1" /></td>
            <td><?php echo $help_sort_order; ?></td>
          </tr>

      </table>
  </form>
    </div>
  </div>
</div>

<?php if (isset($footer)){ echo $footer; } ?>