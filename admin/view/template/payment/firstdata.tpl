<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-firstdata" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-firstdata" class="form-horizontal">
          <ul class="nav nav-tabs" id="tabs">
            <li class="active"><a href="#tab-account" data-toggle="tab"><?php echo $tab_account; ?></a></li>
            <li><a href="#tab-order-status" data-toggle="tab"><?php echo $tab_order_status; ?></a></li>
            <li><a href="#tab-payment" data-toggle="tab"><?php echo $tab_payment; ?></a></li>
            <li><a href="#tab-advanced" data-toggle="tab"><?php echo $tab_advanced; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-account">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-merchant-id"><?php echo $entry_merchant_id; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="firstdata_merchant_id" value="<?php echo $firstdata_merchant_id; ?>" placeholder="<?php echo $entry_merchant_id; ?>" id="input-merchant-id" class="form-control"/>
                  <?php if ($error_merchant_id) { ?>
                  <div class="text-danger"><?php echo $error_merchant_id; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-secret"><?php echo $entry_secret; ?></label>
                <div class="col-sm-10">
                  <input type="password" name="firstdata_secret" value="<?php echo $firstdata_secret; ?>" placeholder="<?php echo $entry_secret; ?>" id="input-secret" class="form-control"/>
                  <?php if ($error_secret) { ?>
                  <div class="text-danger"><?php echo $error_secret; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-live-demo"><?php echo $entry_live_demo; ?></label>
                <div class="col-sm-10">
                  <select name="firstdata_live_demo" id="input-live-demo" class="form-control">
                    <?php if ($firstdata_live_demo) { ?>
                    <option value="1" selected="selected"><?php echo $text_live; ?></option>
                    <option value="0"><?php echo $text_demo; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_live; ?></option>
                    <option value="0" selected="selected"><?php echo $text_demo; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
                <div class="col-sm-10">
                  <select name="firstdata_geo_zone_id" id="input-geo-zone" class="form-control">
                    <option value="0"><?php echo $text_all_zones; ?></option>
                    <?php foreach ($geo_zones as $geo_zone) { ?>
                    <?php if ($geo_zone['geo_zone_id'] == $firstdata_geo_zone_id) { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-debug"><span data-toggle="tooltip" title="<?php echo $help_debug; ?>"><?php echo $entry_debug; ?></span></label>
                <div class="col-sm-10">
                  <select name="firstdata_debug" id="input-debug" class="form-control">
                    <?php if ($firstdata_debug) { ?>
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
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="firstdata_status" id="input-status" class="form-control">
                    <?php if ($firstdata_status) { ?>
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
                <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="firstdata_total" value="<?php echo $firstdata_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="firstdata_sort_order" value="<?php echo $firstdata_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control"/>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-order-status">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-status-success-settled"><?php echo $entry_status_success_settled; ?></label>
                <div class="col-sm-10">
                  <select name="firstdata_order_status_success_settled_id" id="input-order-status-success-settled" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $firstdata_order_status_success_settled_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-status-success-unsettled"><?php echo $entry_status_success_unsettled; ?></label>
                <div class="col-sm-10">
                  <select name="firstdata_order_status_success_unsettled_id" id="input-order-status-success-unsettled" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $firstdata_order_status_success_unsettled_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-status-decline"><?php echo $entry_status_decline; ?></label>
                <div class="col-sm-10">
                  <select name="firstdata_order_status_decline_id" id="input-order-status-decline" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $firstdata_order_status_decline_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-status-void"><?php echo $entry_status_void; ?></label>
                <div class="col-sm-10">
                  <select name="firstdata_order_status_void_id" id="input-order-status-void" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $firstdata_order_status_void_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-payment">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-auto-settle"><span data-toggle="tooltip" title="<?php echo $help_settle; ?>"><?php echo $entry_auto_settle; ?></span></label>
                <div class="col-sm-10">
                  <select name="firstdata_auto_settle" id="input-auto-settle" class="form-control">
                    <option value="0"
                <?php echo ($firstdata_auto_settle == 0 ? ' selected' : ''); ?>><?php echo $text_settle_delayed; ?></option>
                    <option value="1"
                <?php echo ($firstdata_auto_settle == 1 ? ' selected' : ''); ?>><?php echo $text_settle_auto; ?></option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-card-store"><?php echo $entry_enable_card_store; ?></label>
                <div class="col-sm-10">
                  <select name="firstdata_card_storage" id="input-card-store" class="form-control">
                    <?php if ($firstdata_card_storage) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-advanced">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-live-url"><?php echo $entry_live_url; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="firstdata_live_url" value="<?php echo $firstdata_live_url; ?>" placeholder="<?php echo $entry_live_url; ?>" id="input-live-url" class="form-control"/>
                  <?php if ($error_live_url) { ?>
                  <div class="text-danger"><?php echo $error_live_url; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-demo-url"><?php echo $entry_demo_url; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="firstdata_demo_url" value="<?php echo $firstdata_demo_url; ?>" placeholder="<?php echo $entry_demo_url; ?>" id="input-demo-url" class="form-control"/>
                  <?php if ($error_demo_url) { ?>
                  <div class="text-danger"><?php echo $error_demo_url; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><span data-toggle="tooltip" title="<?php echo $help_notification; ?>"><?php echo $text_notification_url; ?></span></label>
                <div class="col-sm-10">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-link"></i></span>
                    <input type="text" value="<?php echo $notify_url; ?>" class="form-control"/>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#tabs a:first').tab('show');
//--></script> </div>
<?php echo $footer; ?>