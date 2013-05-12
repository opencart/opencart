<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="buttons"><button type="submit" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></button> <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
        <div class="control-group">
          <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
          <div class="controls">
            <input type="text" name="moneybookers_email" value="<?php echo $moneybookers_email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" />
            <?php if ($error_email) { ?>
            <span class="error"><?php echo $error_email; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-secret"><?php echo $entry_secret; ?></label>
          <div class="controls">
            <input type="text" name="moneybookers_secret" value="<?php echo $moneybookers_secret; ?>" placeholder="<?php echo $entry_secret; ?>" id="input-secret" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-total"><?php echo $entry_total; ?></label>
          <div class="controls">
            <input type="text" name="moneybookers_total" value="<?php echo $moneybookers_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" />

            
            <a data-toggle="tooltip" title="<?php echo $help_total; ?>"><i class="icon-info-sign"></i></a>
            
            </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
          <div class="controls">
            <select name="moneybookers_order_status_id" id="input-order-status">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $moneybookers_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-pending-status"><?php echo $entry_pending_status; ?></label>
          <div class="controls">
            <select name="moneybookers_pending_status_id" id="input-pending-status">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $moneybookers_pending_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-canceled-status"><?php echo $entry_canceled_status; ?></label>
          <div class="controls">
            <select name="moneybookers_canceled_status_id" id="input-canceled-status">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $moneybookers_canceled_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-failed-status"><?php echo $entry_failed_status; ?></label>
          <div class="controls">
            <select name="moneybookers_failed_status_id" id="input-failed-status">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $moneybookers_failed_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-chargeback-status"><?php echo $entry_chargeback_status; ?></label>
          <div class="controls">
            <select name="moneybookers_chargeback_status_id" id="input-chargeback-status">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $moneybookers_chargeback_status_id) { ?>
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
            <select name="moneybookers_geo_zone_id" id="input-geo-zone">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $moneybookers_geo_zone_id) { ?>
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
            <select name="moneybookers_status" id="input-status">
              <?php if ($moneybookers_status) { ?>
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
            <input type="text" name="moneybookers_sort_order" value="<?php echo $moneybookers_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="input-small" />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 