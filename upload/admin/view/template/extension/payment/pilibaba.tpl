<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-pilibaba" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <?php if ($success) { ?>
      <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-pilibaba" class="form-horizontal">
		  <ul class="nav nav-tabs">
		    <?php if ($show_register) { ?>
		      <li class="active" id="li-tab-register"><a href="#tab-register" data-toggle="tab"><?php echo $tab_register; ?></a></li>
		      <li id="li-tab-settings"><a href="#tab-settings" data-toggle="tab"><?php echo $tab_settings; ?></a></li>
			<?php } else { ?>
		      <li class="active" id="li-tab-settings"><a href="#tab-settings" data-toggle="tab"><?php echo $tab_settings; ?></a></li>
			<?php } ?>
		  </ul>
		  <div class="tab-content">
		    <div class="tab-pane <?php if ($show_register) { echo 'active'; } ?>" id="tab-register">
		      <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-pilibaba-email-address"><span data-toggle="tooltip" title="<?php echo $help_email_address; ?>"><?php echo $entry_email_address; ?></span></label>
                <div class="col-sm-10">
			    <input type="email" name="pilibaba_email_address" value="" placeholder="<?php echo $entry_email_address; ?>" id="input-pilibaba-email-address" class="form-control" />
		        </div>
		      </div>
		      <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-pilibaba-password"><span data-toggle="tooltip" title="<?php echo $help_password; ?>"><?php echo $entry_password; ?></span></label>
                <div class="col-sm-10">
			    <input type="password" name="pilibaba_password" value="" placeholder="<?php echo $entry_password; ?>" id="input-pilibaba-password" class="form-control" />
		        </div>
		      </div>
		      <div class="form-group required">
		        <label class="col-sm-2 control-label" for="input-pilibaba-currency"><span data-toggle="tooltip" title="<?php echo $help_currency; ?>"><?php echo $entry_currency; ?></span></label>
			    <div class="col-sm-10">
			      <select name="pilibaba_currency" id="input-pilibaba-currency" class="form-control">
			      	<option value="">Select Currency</option>
			        <?php foreach ($currencies as $currency) { ?>
			        <option value="<?php echo $currency; ?>"><?php echo $currency; ?></option>
			        <?php } ?>
			      </select>
			    </div>
		      </div>
		      <div class="form-group required">
		        <label class="col-sm-2 control-label" for="input-pilibaba-warehouse"><span data-toggle="tooltip" title="<?php echo $help_warehouse; ?>"><?php echo $entry_warehouse; ?></span></label>
			    <div class="col-sm-10">
			      <select name="pilibaba_warehouse" id="input-pilibaba-warehouse" class="form-control">
			      	<option value="">Select Warehouse</option>
			        <?php foreach ($warehouses as $warehouse) { ?>
			        <option value="<?php echo $warehouse['id']; ?>"><?php echo $warehouse['country'] . ' ' . $warehouse['city'] . ' ' . $warehouse['firstName'] . ' ' . $warehouse['lastName']; ?></option>
			        <?php } ?>
			        <option value="other"><?php echo $text_other; ?></option>
			      </select>
			    </div>
		      </div>
		      <div class="form-group" style="display:none">
		        <label class="col-sm-2 control-label" for="input-pilibaba-country"><span data-toggle="tooltip" title="<?php echo $help_country; ?>"><?php echo $entry_country; ?></span></label>
			    <div class="col-sm-10">
			      <select name="pilibaba_country" id="input-pilibaba-country" class="form-control">
			      	<option value="">Select Country</option>
			        <?php foreach ($countries as $country) { ?>
			        <option value="<?php echo $country['iso_code_3']; ?>"><?php echo $country['name']; ?></option>
			        <?php } ?>
			      </select>
			    </div>
		      </div>
			  <a class="button btn btn-primary col-sm-offset-2" id="button-register"><?php echo $button_register; ?></a> <span class="btn btn-primary col-sm-offset-2" id="img_loading_register" style="display:none"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>
			</div>
		    <div class="tab-pane <?php if (!$show_register) { echo 'active'; } ?>" id="tab-settings">
		      <?php if ($error_weight) { ?>
                <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_weight; ?></div>
			  <?php } ?>

		      <?php if ($notice_email) { ?>
				<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $notice_email; ?></div>
			  <?php } ?>

		      <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-pilibaba-merchant-number"><span data-toggle="tooltip" title="<?php echo $help_merchant_number; ?>"><?php echo $entry_merchant_number; ?></span></label>
                <div class="col-sm-10">
			    <input type="text" name="pilibaba_merchant_number" value="<?php echo $pilibaba_merchant_number; ?>" placeholder="<?php echo $entry_merchant_number; ?>" id="input-pilibaba-merchant-number" class="form-control" />
			    <?php if ($error_pilibaba_merchant_number) { ?>
                  <div class="text-danger"><?php echo $error_pilibaba_merchant_number; ?></div>
                <?php } ?>
		        </div>
		      </div>
		      <div class="form-group required">
		        <label class="col-sm-2 control-label" for="input-pilibaba-secret-key"><span data-toggle="tooltip" title="<?php echo $help_secret_key; ?>"><?php echo $entry_secret_key; ?></span></label>
			    <div class="col-sm-10">
			    <input type="text" name="pilibaba_secret_key" value="<?php echo $pilibaba_secret_key; ?>" placeholder="<?php echo $entry_secret_key; ?>" id="input-pilibaba-secret-key" class="form-control" />
			    <?php if ($error_pilibaba_secret_key) { ?>
                  <div class="text-danger"><?php echo $error_pilibaba_secret_key; ?></div>
                <?php } ?>
			    </div>
		      </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-pilibaba-environment"><?php echo $entry_environment; ?></label>
                <div class="col-sm-10">
                  <select name="pilibaba_environment" id="input-pilibaba-environment" class="form-control">
                    <?php if ($pilibaba_environment == 'live') { ?>
                    <option value="live" selected="selected"><?php echo $text_live; ?></option>
                    <?php } else { ?>
                    <option value="live"><?php echo $text_live; ?></option>
                    <?php } ?>
                    <?php if ($pilibaba_environment == 'test') { ?>
                    <option value="test" selected="selected"><?php echo $text_test; ?></option>
                    <?php } else { ?>
                    <option value="test"><?php echo $text_test; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
		      <div class="form-group">
		        <label class="col-sm-2 control-label" for="input-pilibaba-shipping-fee"><span data-toggle="tooltip" title="<?php echo $help_shipping_fee; ?>"><?php echo $entry_shipping_fee; ?></span></label>
			    <div class="col-sm-10">
			      <input type="text" name="pilibaba_shipping_fee" value="<?php echo $pilibaba_shipping_fee; ?>" placeholder="<?php echo $entry_shipping_fee; ?> (e.g. 4.99)" id="input-pilibaba-shipping-fee" class="form-control" />
			      <?php if ($error_pilibaba_shipping_fee) { ?>
                    <div class="text-danger"><?php echo $error_pilibaba_shipping_fee; ?></div>
                  <?php } ?>
			    </div>
		      </div>
		      <div class="form-group">
                <label class="col-sm-2 control-label" for="input-pilibaba-order-status"><span data-toggle="tooltip" title="<?php echo $help_order_status; ?>"><?php echo $entry_order_status; ?></span></label>
                <div class="col-sm-10">
                  <select name="pilibaba_order_status_id" id="input-pilibaba-order-status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $pilibaba_order_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
		      <div class="form-group">
		        <label class="col-sm-2 control-label" for="input-pilibaba-status"><?php echo $entry_status; ?></label>
		        <div class="col-sm-10">
		          <select name="pilibaba_status" id="input-pilibaba-status" class="form-control">
                    <?php if ($pilibaba_status) { ?>
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
		        <label class="col-sm-2 control-label" for="input-pilibaba-logging"><span data-toggle="tooltip" title="<?php echo $help_logging; ?>"><?php echo $entry_logging; ?></span></label>
			    <div class="col-sm-10">
		          <select name="pilibaba_logging" id="input-pilibaba-logging" class="form-control">
                    <?php if ($pilibaba_logging) { ?>
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
		        <label class="col-sm-2 control-label" for="input-pilibaba-sort-order"><?php echo $entry_sort_order; ?></label>
		        <div class="col-sm-10">
			      <input type="text" name="pilibaba_sort_order" value="<?php echo $pilibaba_sort_order ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-pilibaba-sort-order" class="form-control" />
			    </div>
		      </div>
		      <div class="form-group" style="display:none">
		        <label class="col-sm-2 control-label" for="input-pilibaba-email-address"></label>
		        <div class="col-sm-10">
			      <input type="text" name="pilibaba_email_address" value="<?php echo $pilibaba_email_address ?>" placeholder="" id="input-pilibaba-email-address" class="form-control" />
			    </div>
		      </div>
		    </div>
		  </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript"><!--
	$('#input-pilibaba-warehouse').change(function() {
		var value = $(this).val();

		if (value == 'other') {
			$('#input-pilibaba-country').parent().parent().show();
		} else {
			$('#input-pilibaba-country').parent().parent().hide();
		}
	});

	$('#button-register').click(function() {
		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: {'email_address': $('#input-pilibaba-email-address').val(), 'password': $('#input-pilibaba-password').val(), 'currency': $('#input-pilibaba-currency').val(), 'warehouse': $('#input-pilibaba-warehouse').val(), 'country': $('#input-pilibaba-country').val(), 'environment': $('#input-pilibaba-environment').val()},
			url: 'index.php?route=extension/payment/pilibaba/register&token=<?php echo $token; ?>',
			beforeSend: function() {
				$('#button-register').hide();
				$('#img_loading_register').show();
				$('.pilibaba_message').remove();
			},
			success: function(json) {
				if (json['redirect']) {
					location = json['redirect'].replace('&amp;', '&');
				}

				if (json['error']) {
					$('#tab-register').prepend('<div class="alert alert-danger pilibaba_message" style="display:none"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>').fadeIn();
				}

				$('#button-register').show();
				$('#img_loading_register').hide();
				$('.pilibaba_message').fadeIn();
			}
		});
	});
//--></script>

<style>
@media (min-width: 768px) {
	#button-register, #img_loading_register {
		position: relative;
		left: 5px;
	}
}
</style>

<?php echo $footer; ?>