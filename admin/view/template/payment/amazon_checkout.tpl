<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-amazon-checkout" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_amazon_join; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-amazon-checkout" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-merchant-id"><?php echo $entry_merchant_id; ?></label>
            <div class="col-sm-10">
              <input type="text" name="amazon_checkout_merchant_id" value="<?php echo $amazon_checkout_merchant_id; ?>" placeholder="<?php echo $entry_merchant_id; ?>" id="input-merchant-id" class="form-control" />
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-access-key"><?php echo $entry_access_key; ?></label>
            <div class="col-sm-10">
              <input type="text" name="amazon_checkout_access_key" value="<?php echo $amazon_checkout_access_key; ?>" placeholder="<?php echo $entry_access_key; ?>" id="input-access-key" class="form-control" />
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-access-secret"><?php echo $entry_access_secret; ?></label>
            <div class="col-sm-10">
              <input type="text" name="amazon_checkout_access_secret" value="<?php echo $amazon_checkout_access_secret; ?>" placeholder="<?php echo $entry_merchant_id; ?>" id="input-access-secret" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-mode"><?php echo $entry_checkout_mode; ?></label>
            <div class="col-sm-10">
              <select name="amazon_checkout_mode" id="input-mode" class="form-control">
                <?php if ($amazon_checkout_mode == 'sandbox') { ?>
                <option value="sandbox" selected="selected"><?php echo $text_sandbox; ?></option>
                <?php } else { ?>
                <option value="sandbox"><?php echo $text_sandbox; ?></option>
                <?php } ?>
                <?php if ($amazon_checkout_mode == 'live') { ?>
                <option value="live" selected="selected"><?php echo $text_live; ?></option>
                <?php } else { ?>
                <option value="live"><?php echo $text_live; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-marketplace"><?php echo $entry_marketplace; ?></label>
            <div class="col-sm-10">
              <select name="amazon_checkout_marketplace" id="input-marketplace" class="form-control">
                <?php if ($amazon_checkout_marketplace == 'uk') { ?>
                <option value="uk" selected="selected"><?php echo $text_uk; ?></option>
                <?php } else { ?>
                <option value="uk"><?php echo $text_uk; ?></option>
                <?php } ?>
                <?php if ($amazon_checkout_marketplace == 'de') { ?>
                <option value="de" selected="selected"><?php echo $text_germany; ?></option>
                <?php } else { ?>
                <option value="de"><?php echo $text_germany; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
            <div class="col-sm-10">
              <select name="amazon_checkout_order_status_id" id="input-order-status" class="form-control">
                <?php foreach($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $amazon_checkout_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-ready-status"><?php echo $entry_ready_status; ?></label>
            <div class="col-sm-10">
              <select name="amazon_checkout_ready_status_id" id="input-ready-status" class="form-control">
                <?php foreach($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $amazon_checkout_ready_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-shipped-status"><?php echo $entry_shipped_status; ?></label>
            <div class="col-sm-10">
              <select name="amazon_checkout_shipped_status_id" id="input-shipped-status" class="form-control">
                <?php foreach($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $amazon_checkout_shipped_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-canceled-status"><?php echo $entry_canceled_status; ?></label>
            <div class="col-sm-10">
              <select name="amazon_checkout_canceled_status_id" id="input-canceled-status" class="form-control">
                <?php foreach($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $amazon_checkout_canceled_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-cron-job-token"><span data-toggle="tooltip" title="<?php echo $help_cron_job_token; ?>"><?php echo $entry_cron_job_token; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="amazon_checkout_cron_job_token" value="<?php echo $amazon_checkout_cron_job_token; ?>" id="input-cron-job-token" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-cron-job-url"><?php echo $entry_cron_job_url; ?></label>
            <div class="col-sm-10">
              <div class="input-group"><span class="input-group-addon"><i class="fa fa-link"></i></span>
                <input type="text" readonly value="<?php echo $cron_job_url; ?>" id="input-cron-job-url" class="form-control" />
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-cron-job-last-run"><?php echo $entry_cron_job_last_run; ?></label>
            <div class="col-sm-10">
              <input type="text" readonly value="<?php echo $cron_job_last_run; ?>" id="input-cron-job-last-run" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-ip"><span data-toggle="tooltip" data-html="true" title="<?php echo htmlspecialchars($help_ip); ?>"><?php echo $entry_ip_allowed; ?></span></label>
            <div class="col-sm-10">
              <div class="input-group">
                <input type="text" value="" placeholder="<?php echo $entry_ip; ?>" id="input-ip" class="form-control" />
                <span class="input-group-btn">
                <button type="button" id="button-ip-add" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_ip_add; ?></button>
                </span> </div>
              <div id="ip-allowed" class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($amazon_checkout_ip_allowed as $ip) { ?>
                <div><i class="fa fa-minus-circle"></i> <?php echo $ip; ?>
                  <input type="hidden" name="amazon_checkout_ip_allowed[]" value="<?php echo $ip; ?>" />
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-total"><?php echo $entry_total; ?></label>
            <div class="col-sm-10">
              <input type="text" name="amazon_checkout_total" value="<?php echo $amazon_checkout_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <select name="amazon_checkout_geo_zone" id="input-geo-zone" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($amazon_checkout_geo_zone == $geo_zone['geo_zone_id']) { ?>
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
              <select name="amazon_checkout_status" id="input-status" class="form-control">
                <?php if ($amazon_checkout_status) { ?>
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
              <input type="text" name="amazon_checkout_sort_order" value="<?php echo $amazon_checkout_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
          <fieldset>
            <legend><?php echo $text_button_settings; ?></legend>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-button-colour"><?php echo $entry_colour ?></label>
              <div class="col-sm-10">
                <select name="amazon_checkout_button_colour" id="input-button-colour" class="form-control">
                  <?php foreach ($button_colours as $value => $text) { ?>
                  <?php if ($value == $amazon_checkout_button_colour) { ?>
                  <option selected="selected" value="<?php echo $value; ?>"><?php echo $text; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $value; ?>"><?php echo $text; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-button-background"><?php echo $entry_background; ?></label>
              <div class="col-sm-10">
                <select name="amazon_checkout_button_background" id="input-button-background" class="form-control">
                  <?php foreach ($button_backgrounds as $value => $text) { ?>
                  <?php if ($value == $amazon_checkout_button_background) { ?>
                  <option selected="selected" value="<?php echo $value; ?>"><?php echo $text; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $value; ?>"><?php echo $text; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-button-size"><?php echo $entry_size; ?></label>
              <div class="col-sm-10">
                <select name="amazon_checkout_button_size" id="input-button-size" class="form-control">
                  <?php foreach ($button_sizes as $value => $text) { ?>
                  <?php if ($value == $amazon_checkout_button_size) { ?>
                  <option selected="selected" value="<?php echo $value; ?>"><?php echo $text; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $value; ?>"><?php echo $text; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-ip-add').on('click', function() {
    var ip = $.trim($('#input-ip').val());

    if (ip) {
        $('#ip-allowed').append('<div><i class="fa fa-minus-circle"></i> ' + ip + '<input type="hidden" name="amazon_checkout_ip_allowed[]" value="' + ip + '" /></div>');
    }

	$('#input-ip').val('');
});

$('#ip-allowed').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

$('input[name=\'amazon_checkout_cron_job_token\']').on('keyup', function() {
    $('#input-cron-job-url').val('<?php echo $store; ?>index.php?route=payment/amazon_checkout/cron&token=' + $(this).val());
});
//--></script></div>
<?php echo $footer; ?>