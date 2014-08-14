<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-amazon-checkout" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-check-circle"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><i class="fa fa-credit-card"></i> <?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="container-fluid">
    <?php foreach($errors as $error) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="alert alert-info"><?php echo $text_amazon_join; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-amazon-checkout" class="form-horizontal">
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="amazon_checkout_merchant_id"><?php echo $text_merchant_id; ?></label>
        <div class="col-sm-10">
          <input type="text" name="amazon_checkout_merchant_id" value="<?php echo $amazon_checkout_merchant_id; ?>" placeholder="<?php echo $text_merchant_id; ?>" id="amazon_checkout_merchant_id" class="form-control" />
        </div>
      </div>
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="amazon_checkout_access_key"><?php echo $text_access_key; ?></label>
        <div class="col-sm-10">
          <input type="text" name="amazon_checkout_access_key" value="<?php echo $amazon_checkout_access_key; ?>" placeholder="<?php echo $text_access_key; ?>" id="amazon_checkout_access_key" class="form-control" />
        </div>
      </div>
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="amazon_checkout_access_secret"><?php echo $text_access_secret; ?></label>
        <div class="col-sm-10">
          <input type="text" name="amazon_checkout_access_secret" value="<?php echo $amazon_checkout_access_secret; ?>" placeholder="<?php echo $text_merchant_id; ?>" id="amazon_checkout_access_secret" class="form-control" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="amazon_checkout_status"><?php echo $text_status; ?></label>
        <div class="col-sm-10">
          <select name="amazon_checkout_status" id="amazon_checkout_status" class="form-control">
            <?php if ($amazon_checkout_status == 1) { ?>
            <option value="1" selected="selected"><?php echo $text_status_enabled; ?></option>
            <?php } else { ?>
            <option value="1"><?php echo $text_status_enabled; ?></option>
            <?php } ?>
            <?php if ($amazon_checkout_status == 0) { ?>
            <option value="0" selected="selected"><?php echo $text_status_disabled; ?></option>
            <?php } else { ?>
            <option value="0"><?php echo $text_status_disabled; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="amazon_checkout_mode"><?php echo $text_checkout_mode; ?></label>
        <div class="col-sm-10">
          <select name="amazon_checkout_mode" id="amazon_checkout_mode" class="form-control">
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
        <label class="col-sm-2 control-label" for="amazon_checkout_marketplace"><?php echo $text_marketplace; ?></label>
        <div class="col-sm-10">
          <select name="amazon_checkout_marketplace" id="amazon_checkout_marketplace" class="form-control">
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
        <label class="col-sm-2 control-label" for="amazon_checkout_geo_zone"><?php echo $text_geo_zone; ?></label>
        <div class="col-sm-10">
          <select name="amazon_checkout_geo_zone" id="amazon_checkout_geo_zone" class="form-control">
            <?php if ($amazon_checkout_geo_zone == 0) { ?>
            <option value="0" selected="selected"><?php echo $text_all_geo_zones; ?></option>
            <?php } else { ?>
            <option value="0"><?php echo $text_all_geo_zones; ?></option>
            <?php } ?>
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
        <label class="col-sm-2 control-label" for="amazon_checkout_order_default_status"><?php echo $text_default_order_status; ?></label>
        <div class="col-sm-10">
          <select name="amazon_checkout_order_default_status" id="amazon_checkout_order_default_status" class="form-control">
            <?php foreach($order_statuses as $order_status) { ?>
            <?php if ($order_status['order_status_id'] == $amazon_checkout_order_default_status) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="amazon_checkout_order_ready_status"><?php echo $text_ready_order_status; ?></label>
        <div class="col-sm-10">
          <select name="amazon_checkout_order_ready_status" id="amazon_checkout_order_ready_status" class="form-control">
            <?php foreach($order_statuses as $order_status) { ?>
            <?php if ($order_status['order_status_id'] == $amazon_checkout_order_ready_status) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="amazon_checkout_order_shipped_status"><?php echo $text_shipped_order_status; ?></label>
        <div class="col-sm-10">
          <select name="amazon_checkout_order_shipped_status" id="amazon_checkout_order_shipped_status" class="form-control">
            <?php foreach($order_statuses as $order_status) { ?>
            <?php if ($order_status['order_status_id'] == $amazon_checkout_order_shipped_status) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="amazon_checkout_order_canceled_status"><?php echo $text_canceled_order_status; ?></label>
        <div class="col-sm-10">
          <select name="amazon_checkout_order_canceled_status" id="amazon_checkout_order_canceled_status" class="form-control">
            <?php foreach($order_statuses as $order_status) { ?>
            <?php if ($order_status['order_status_id'] == $amazon_checkout_order_canceled_status) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="amazon_checkout_minimum_total"><?php echo $text_minimum_total; ?></label>
        <div class="col-sm-10">
          <input type="text" name="amazon_checkout_minimum_total" value="<?php echo $amazon_checkout_minimum_total; ?>" placeholder="<?php echo $text_minimum_total; ?>" id="amazon_checkout_minimum_total" class="form-control" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="amazon_checkout_sort_order"><?php echo $text_sort_order; ?></label>
        <div class="col-sm-10">
          <input type="text" name="amazon_checkout_sort_order" value="<?php echo $amazon_checkout_sort_order; ?>" placeholder="<?php echo $text_sort_order; ?>" id="amazon_checkout_sort_order" class="form-control" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="amazon_checkout_cron_job_token"><span data-toggle="tooltip" title="<?php echo $help_cron_job_token; ?>"><?php echo $text_cron_job_token; ?></span></label>
        <div class="col-sm-10">
          <input type="text" name="amazon_checkout_cron_job_token" value="<?php echo $amazon_checkout_cron_job_token; ?>" id="amazon_checkout_cron_job_token" class="form-control" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label"><?php echo $text_cron_job_url; ?></label>
        <div class="col-sm-10">
          <input type="text" readonly="readonly" id="cron-job-url" value="<?php echo $cron_job_url; ?>" class="form-control" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label"><?php echo $text_last_cron_job_run; ?></label>
        <div class="col-sm-10">
          <input type="text" readonly="readonly" value="<?php echo $last_cron_job_run; ?>" class="form-control" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $text_help_ip; ?>"><?php echo $text_allowed_ips; ?></span></label>
        <div class="col-sm-10">
          <input type="text" name="allowed-ip" placeholder="<?php echo $text_ip; ?>" />
          <button type="button" id="add-ip" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $text_add; ?></button>
          <div id="allowed-ips" class="well well-sm" style="height: 150px; overflow: auto;">
            <?php $count = 0; ?>
            <?php foreach ($amazon_checkout_allowed_ips as $ip) { ?>
            <div id="allowed-ip<?php echo $count++; ?>"><i class="fa fa-minus-circle"></i> <?php echo $ip; ?>
              <input type="hidden" name="amazon_checkout_allowed_ips[]" value="<?php echo $ip; ?>" />
            </div>
            <?php } ?>
          </div>
        </div>
      </div>
      <legend><?php echo $text_button_settings; ?></legend>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="amazon_checkout_button_colour"><?php echo $text_colour ?></label>
        <div class="col-sm-10">
          <select name="amazon_checkout_button_colour" id="amazon_checkout_button_colour" class="form-control">
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
        <label class="col-sm-2 control-label" for="amazon_checkout_button_background"><?php echo $text_background; ?></label>
        <div class="col-sm-10">
          <select name="amazon_checkout_button_background" id="amazon_checkout_button_background" class="form-control">
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
        <label class="col-sm-2 control-label" for="amazon_checkout_button_size"><?php echo $text_size; ?></label>
        <div class="col-sm-10">
          <select name="amazon_checkout_button_size" id="amazon_checkout_button_size" class="form-control">
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
    </form>
  </div>
</div>
<script type="text/javascript"><!--
var count = <?php echo $count; ?>;
    
$('#add-ip').click(function(){
    var ip = $.trim($('input[name="allowed-ip"]').val());
    $('input[name="allowed-ip"]').val('');
    
    if (ip != '') {
        var html = '';
        html += '<div id="allowed-ip' + count++ + '"><i class="fa fa-minus-circle"></i> ' + ip;
        html += '<input type="hidden" name="amazon_checkout_allowed_ips[]" value="' + ip + '" />';
        html += '</div>';

        $('#allowed-ips').append(html);
    }
});

$('#allowed-ips img').click(function(){
    $(this).parent().remove();
    
    $('#allowed-ips div:odd').attr('class', 'odd');
	$('#allowed-ips div:even').attr('class', 'even');
});

$('#allowed-ips').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

$('input[name="amazon_checkout_cron_job_token"]').keyup(function(){
    $('#cron-job-url').val('<?php echo HTTPS_CATALOG; ?>index.php?route=payment/amazon_checkout/cron&token=' + $(this).val());
});
//--></script> 
<?php echo $footer; ?> 