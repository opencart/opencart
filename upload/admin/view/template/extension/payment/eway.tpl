<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-eway" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-eway" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-paymode"><?php echo $entry_paymode; ?></label>
            <div class="col-sm-10">
              <select name="eway_paymode" id="input-test" class="form-control">
				<?php if ($eway_paymode == 'iframe') { ?>
					<option value="iframe" selected="selected"><?php echo $text_iframe; ?></option>
					<option value="transparent"><?php echo $text_transparent; ?></option>
				<?php } else { ?>
					<option value="iframe"><?php echo $text_iframe; ?></option>
					<option value="transparent" selected="selected"><?php echo $text_transparent; ?></option>
				<?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-test"><span data-toggle="tooltip" title="<?php echo $help_testmode; ?>"><?php echo $entry_test; ?></span></label>
            <div class="col-sm-10">
              <select name="eway_test" id="input-test" class="form-control">
				<?php if ($eway_test) { ?>
					<option value="1" selected="selected"><?php echo $text_yes; ?></option>
					<option value="0"><?php echo $text_no; ?></option>
				<?php } else { ?>
					<option value="1"><?php echo $text_yes; ?></option>
					<option value="0" selected="selected"><?php echo $text_no; ?></option>
				<?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-username"><span data-toggle="tooltip" title="<?php echo $help_username; ?>"><?php echo $entry_username; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="eway_username" value="<?php echo $eway_username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control"/>
			  <?php if ($error_username) { ?>
				  <div class="text-danger"><?php echo $error_username; ?></div>
			  <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-password"><span data-toggle="tooltip" title="<?php echo $help_password; ?>"><?php echo $entry_password; ?></span></label>
            <div class="col-sm-10">
              <input type="password" name="eway_password" value="<?php echo $eway_password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control"/>
			  <?php if ($error_password) { ?>
				  <div class="text-danger"><?php echo $error_password; ?></div>
			  <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="eway_status" id="input-status" class="form-control">
				<?php if ($eway_status) { ?>
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
            <label class="col-sm-2 control-label" for="input-transaction-method"><span data-toggle="tooltip" title="<?php echo $help_transaction_method; ?>"><?php echo $entry_transaction_method; ?></span></label>
            <div class="col-sm-10">
              <select name="eway_transaction_method" id="input-transaction-method" class="form-control">
				<?php if ($eway_transaction_method == 'auth') { ?>
					<option value="payment"><?php echo $text_sale; ?></option>
					<option value="auth" selected="selected"><?php echo $text_authorisation; ?></option>
				<?php } else { ?>
					<option value="payment" selected="selected"><?php echo $text_sale; ?></option>
					<option value="auth"><?php echo $text_authorisation; ?></option>
				<?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="eway_payment_type"><?php echo $entry_payment_type; ?></label>
            <div class="col-sm-10">
              <p>
                <input type="hidden" name="eway_payment_type[visa]" value="0" />
                <input type='checkbox' name='eway_payment_type[visa]' id="eway_payment_type" value='1' <?php echo (isset($eway_payment_type['visa']) && $eway_payment_type['visa'] == 1 ? 'checked="checked"' : '') ?> /> CC - Visa
              </p>
              <p>
                <input type="hidden" name="eway_payment_type[mastercard]" value="0" />
                <input type='checkbox' name='eway_payment_type[mastercard]' value='1' <?php echo (isset($eway_payment_type['mastercard']) && $eway_payment_type['mastercard'] == 1 ? 'checked="checked"' : '') ?> /> CC - MasterCard
              </p>
              <p>
                <input type="hidden" name="eway_payment_type[amex]" value="0" />
                <input type='checkbox' name='eway_payment_type[amex]' value='1' <?php echo (isset($eway_payment_type['amex']) && $eway_payment_type['amex'] == 1 ? 'checked="checked"' : '') ?> /> CC - Amex
              </p>
              <p>
                <input type="hidden" name="eway_payment_type[diners]" value="0" />
                <input type='checkbox' name='eway_payment_type[diners]' value='1' <?php echo (isset($eway_payment_type['diners']) && $eway_payment_type['diners'] == 1 ? 'checked="checked"' : '') ?> /> CC - Diners Club
              </p>
              <p>
                <input type="hidden" name="eway_payment_type[jcb]" value="0" />
                <input type='checkbox' name='eway_payment_type[jcb]' value='1' <?php echo (isset($eway_payment_type['jcb']) && $eway_payment_type['jcb'] == 1 ? 'checked="checked"' : '') ?> /> CC - JCB
              </p>
              <p>
                <input type="hidden" name="eway_payment_type[paypal]" value="0" />
                <input type='checkbox' name='eway_payment_type[paypal]' value='1' <?php echo (isset($eway_payment_type['paypal']) && $eway_payment_type['paypal'] == 1 ? 'checked="checked"' : '') ?> /> PayPal
              </p>
              <p>
                <input type="hidden" name="eway_payment_type[masterpass]" value="0" />
                <input type='checkbox' name='eway_payment_type[masterpass]' value='1' <?php echo (isset($eway_payment_type['masterpass']) && $eway_payment_type['masterpass'] == 1 ? 'checked="checked"' : '') ?> /> MasterPass
              </p>
			  <?php if ($error_payment_type) { ?>
				  <div class="text-danger"><?php echo $error_payment_type; ?></div>
			  <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <select name="eway_standard_geo_zone_id" id="input-geo-zone" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
				<?php foreach ($geo_zones as $geo_zone) { ?><?php if ($geo_zone['geo_zone_id'] == $eway_standard_geo_zone_id) { ?>
						<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
					<?php } else { ?>
						<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
					<?php } ?><?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
            <div class="col-sm-10">
              <select name="eway_order_status_id" id="input-order-status" class="form-control">
				<?php foreach ($order_statuses as $order_status) { ?><?php if ($order_status['order_status_id'] == $eway_order_status_id) { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					<?php } else { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					<?php } ?><?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-status-refund"><?php echo $entry_order_status_refund; ?></label>
            <div class="col-sm-10">
              <select name="eway_order_status_refunded_id" id="input-order-status-refund" class="form-control">
				<?php foreach ($order_statuses as $order_status) { ?><?php if ($order_status['order_status_id'] == $eway_order_status_refunded_id) { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					<?php } else { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					<?php } ?><?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-status-auth"><?php echo $entry_order_status_auth; ?></label>
            <div class="col-sm-10">
              <select name="eway_order_status_auth_id" id="input-order-status-auth" class="form-control">
				<?php foreach ($order_statuses as $order_status) { ?><?php if ($order_status['order_status_id'] == $eway_order_status_auth_id) { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					<?php } else { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					<?php } ?><?php } ?>
			  </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-status-fraud"><?php echo $entry_order_status_fraud; ?></label>
            <div class="col-sm-10">
              <select name="eway_order_status_fraud_id" id="input-order-status-fraud" class="form-control">
				<?php foreach ($order_statuses as $order_status) { ?><?php if ($order_status['order_status_id'] == $eway_order_status_fraud_id) { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					<?php } else { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					<?php } ?><?php } ?>
			  </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="eway_sort_order" value="<?php echo $eway_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control"/>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>