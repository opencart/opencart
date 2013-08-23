<?php echo $header; ?>
<div id="content" class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <button type="submit" form="form-paypoint" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-danger"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
      <h1 class="panel-title"><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-paypoint" class="form-horizontal">
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-merchant"><?php echo $entry_merchant; ?></label>
          <div class="col-sm-10">
            <input type="text" name="paypoint_merchant" value="<?php echo $paypoint_merchant; ?>" placeholder="<?php echo $entry_merchant; ?>" id="input-merchant" class="form-control" />
            <?php if ($error_merchant) { ?>
            <div class="text-danger"><?php echo $error_merchant; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
          <div class="col-sm-10">
            <input type="text" name="paypoint_password" value="<?php echo $paypoint_password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
            <span class="help-block"><?php echo $help_password; ?></span> </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-test"><?php echo $entry_test; ?></label>
          <div class="col-sm-10">
            <select name="paypoint_test" id="input-test" class="form-control">
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
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-total"><?php echo $entry_total; ?></label>
          <div class="col-sm-10">
            <input type="text" name="paypoint_total" value="<?php echo $paypoint_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
            <span class="help-block"><?php echo $help_total; ?></span> </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
          <div class="col-sm-10">
            <select name="paypoint_order_status_id" id="input-order-status" class="form-control">
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
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
          <div class="col-sm-10">
            <select name="paypoint_geo_zone_id" id="input-geo-zone" class="form-control">
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
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
          <div class="col-sm-10">
            <select name="paypoint_status" id="input-status" class="form-control">
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
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
          <div class="col-sm-10">
            <input type="text" name="paypoint_sort_order" value="<?php echo $paypoint_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>