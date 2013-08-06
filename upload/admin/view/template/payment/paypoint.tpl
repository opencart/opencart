<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel">
    <div class="panel-heading">
      <h1 class="panel-title"><i class="icon-edit icon-large"></i> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <button type="submit" form="form-paypoint" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
    </div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-paypoint" class="form-horizontal">
      <div class="control-group required">
        <label class="control-label" for="input-merchant"><?php echo $entry_merchant; ?></label>
        <div class="controls">
          <input type="text" name="paypoint_merchant" value="<?php echo $paypoint_merchant; ?>" placeholder="<?php echo $entry_merchant; ?>" id="input-merchant" />
          <?php if ($error_merchant) { ?>
          <span class="error"><?php echo $error_merchant; ?></span>
          <?php } ?>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="input-password"><?php echo $entry_password; ?> <span class="help-block"><?php echo $help_password; ?></span></label>
        <div class="controls">
          <input type="text" name="paypoint_password" value="<?php echo $paypoint_password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="input-test"><?php echo $entry_test; ?></label>
        <div class="controls">
          <select name="paypoint_test" id="input-test">
            <?php if ($paypoint_test == 'live') { ?>
            <option value="live" selected="selected"><?php echo $text_live; ?></option>
            <?php } else { ?>
            <option value="live"><?php echo $text_live; ?></option>
            <?php } ?>
            <?php if ($paypoint_test == 'successful') { ?>
            <option value="successful" selected="selected"><?php echo $text_successful; ?></option>
            <?php } else { ?>
            <option value="successful"><?php echo $text_successful; ?></option>
            <?php } ?>
            <?php if ($paypoint_test == 'fail') { ?>
            <option value="fail" selected="selected"><?php echo $text_fail; ?></option>
            <?php } else { ?>
            <option value="fail"><?php echo $text_fail; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="input-total"><?php echo $entry_total; ?> <span class="help-block"><?php echo $help_total; ?></span></label>
        <div class="controls">
          <input type="text" name="paypoint_total" value="<?php echo $paypoint_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
        <div class="controls">
          <select name="paypoint_order_status_id" id="input-order-status">
            <?php foreach ($order_statuses as $order_status) { ?>
            <?php if ($order_status['order_status_id'] == $paypoint_order_status_id) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
        <div class="controls">
          <select name="paypoint_geo_zone_id" id="input-geo-zone">
            <option value="0"><?php echo $text_all_zones; ?></option>
            <?php foreach ($geo_zones as $geo_zone) { ?>
            <?php if ($geo_zone['geo_zone_id'] == $paypoint_geo_zone_id) { ?>
            <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
        <div class="controls">
          <select name="paypoint_status" id="input-status">
            <?php if ($paypoint_status) { ?>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <option value="0"><?php echo $text_disabled; ?></option>
            <?php } else { ?>
            <option value="1"><?php echo $text_enabled; ?></option>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
        <div class="controls">
          <input type="text" name="paypoint_sort_order" value="<?php echo $paypoint_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="input-mini" />
        </div>
      </div>
    </form>
  </div>
</div>
<?php echo $footer; ?>