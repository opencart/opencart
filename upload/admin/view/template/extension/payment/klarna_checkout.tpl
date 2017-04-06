<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-klarna-checkout" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <?php if ($error_tax_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_tax_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-klarna-checkout" class="form-horizontal">
          <ul class="nav nav-tabs" id="tabs">
            <li class="active"><a href="#tab-setting" data-toggle="tab"><?php echo $tab_setting; ?></a></li>
			<li><a href="#tab-order-status" data-toggle="tab"><?php echo $tab_order_status; ?></a></li>
			<li><a href="#tab-account" data-toggle="tab"><?php echo $tab_account; ?></a></li>
			<li><a href="#tab-settlement" data-toggle="tab"><?php echo $tab_settlement; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-setting">
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_version; ?></label>
                <div class="col-sm-10" style="padding-top: 9px;">
                  <span><?php echo $text_version; ?></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-debug"><span data-toggle="tooltip" title="<?php echo $help_debug; ?>"><?php echo $entry_debug; ?></span></label>
                <div class="col-sm-10">
                  <select name="klarna_checkout_debug" id="input-debug" class="form-control">
                    <?php if ($klarna_checkout_debug) { ?>
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
                <label class="col-sm-2 control-label" for="input-colour-button"><?php echo $entry_colour_button; ?></label>
                <div class="col-sm-10">
                  <input type="color" name="klarna_checkout_colour_button" value="<?php echo $klarna_checkout_colour_button; ?>" id="input-colour-button" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-colour-button-text"><?php echo $entry_colour_button_text; ?></label>
                <div class="col-sm-10">
                  <input type="color" name="klarna_checkout_colour_button_text" value="<?php echo $klarna_checkout_colour_button_text; ?>" id="input-colour-button-text" />
                </div>
              </div>
			  <div class="form-group">
			    <label class="col-sm-2 control-label" for="input-colour-checkbox"><?php echo $entry_colour_checkbox; ?></label>
			    <div class="col-sm-10">
				  <input type="color" name="klarna_checkout_colour_checkbox" value="<?php echo $klarna_checkout_colour_checkbox; ?>" id="input-colour-checkbox" />
			    </div>
			  </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-colour-checkbox-checkmark"><?php echo $entry_colour_checkbox_checkmark; ?></label>
                <div class="col-sm-10">
                  <input type="color" name="klarna_checkout_colour_checkbox_checkmark" value="<?php echo $klarna_checkout_colour_checkbox_checkmark; ?>" id="input-colour-checkbox-checkmark" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-colour-header"><?php echo $entry_colour_header; ?></label>
                <div class="col-sm-10">
                  <input type="color" name="klarna_checkout_colour_header" value="<?php echo $klarna_checkout_colour_header; ?>" id="input-colour-header" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-colour-link"><?php echo $entry_colour_link; ?></label>
                <div class="col-sm-10">
                  <input type="color" name="klarna_checkout_colour_link" value="<?php echo $klarna_checkout_colour_link; ?>" id="input-colour-link" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-separate-shipping-address"><span data-toggle="tooltip" title="<?php echo $help_separate_shipping_address; ?>"><?php echo $entry_separate_shipping_address; ?></span></label>
                <div class="col-sm-10">
                  <select name="klarna_checkout_separate_shipping_address" id="input-separate-shipping-address" class="form-control">
                    <?php if ($klarna_checkout_separate_shipping_address) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-dob-mandatory"><span data-toggle="tooltip" title="<?php echo $help_dob_mandatory; ?>"><?php echo $entry_dob_mandatory; ?></span></label>
                <div class="col-sm-10">
                  <select name="klarna_checkout_dob_mandatory" id="input-dob-mandatory" class="form-control">
                    <?php if ($klarna_checkout_dob_mandatory) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-title-mandatory"><span data-toggle="tooltip" title="<?php echo $help_title_mandatory; ?>"><?php echo $entry_title_mandatory; ?></span></label>
                <div class="col-sm-10">
                  <select name="klarna_checkout_title_mandatory" id="input-title-mandatory" class="form-control">
                    <?php if ($klarna_checkout_title_mandatory) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-additional-text-box"><span data-toggle="tooltip" title="<?php echo $help_additional_text_box; ?>"><?php echo $entry_additional_text_box; ?></span></label>
                <div class="col-sm-10">
                  <select name="klarna_checkout_additional_text_box" id="input-additional-text-box" class="form-control">
                    <?php if ($klarna_checkout_additional_text_box) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
			  <div class="form-group">
			    <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
			    <div class="col-sm-10">
			  	  <input type="text" name="klarna_checkout_total" value="<?php echo $klarna_checkout_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-terms"><?php echo $entry_terms; ?></label>
				<div class="col-sm-10">
				  <select name="klarna_checkout_terms" id="input-terms" class="form-control">
					<?php foreach ($informations as $information) { ?>
					<?php if ($information['information_id'] == $klarna_checkout_terms) { ?>
					<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
					<?php } ?>
					<?php } ?>
				  </select>
				</div>
			  </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="klarna_checkout_status" id="input-status" class="form-control">
                    <?php if ($klarna_checkout_status) { ?>
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
			<div class="tab-pane" id="tab-order-status">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-status-authorised"><?php echo $entry_order_status_authorised; ?></label>
                <div class="col-sm-10">
                  <select name="klarna_checkout_order_status_authorised_id" id="input-order-status-authorised" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $klarna_checkout_order_status_authorised_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-status-part-captured"><?php echo $entry_order_status_part_captured; ?></label>
                <div class="col-sm-10">
                  <select name="klarna_checkout_order_status_part_captured_id" id="input-order-status-part-captured" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $klarna_checkout_order_status_part_captured_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-status-captured"><?php echo $entry_order_status_captured; ?></label>
                <div class="col-sm-10">
                  <select name="klarna_checkout_order_status_captured_id" id="input-order-status-captured" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $klarna_checkout_order_status_captured_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-status-cancelled"><?php echo $entry_order_status_cancelled; ?></label>
                <div class="col-sm-10">
                  <select name="klarna_checkout_order_status_cancelled_id" id="input-order-status-cancelled" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $klarna_checkout_order_status_cancelled_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-status-refund"><?php echo $entry_order_status_refund; ?></label>
                <div class="col-sm-10">
                  <select name="klarna_checkout_order_status_refund_id" id="input-order-status-refund" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $klarna_checkout_order_status_refund_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-status-fraud-rejected"><?php echo $entry_order_status_fraud_rejected; ?></label>
                <div class="col-sm-10">
                  <select name="klarna_checkout_order_status_fraud_rejected_id" id="input-order-status-fraud-rejected" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $klarna_checkout_order_status_fraud_rejected_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-status-fraud-pending"><?php echo $entry_order_status_fraud_pending; ?></label>
                <div class="col-sm-10">
                  <select name="klarna_checkout_order_status_fraud_pending_id" id="input-order-status-fraud-pending" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $klarna_checkout_order_status_fraud_pending_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-status-fraud-accepted"><?php echo $entry_order_status_fraud_accepted; ?></label>
                <div class="col-sm-10">
                  <select name="klarna_checkout_order_status_fraud_accepted_id" id="input-order-status-fraud-accepted" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $klarna_checkout_order_status_fraud_accepted_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
			</div>
			<div class="tab-pane" id="tab-account">
			  <?php if ($error_account_warning) { ?>
			  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_account_warning; ?></div>
			  <?php } ?>
			  <table id="account" class="table table-striped table-bordered table-hover">
				<thead>
				  <tr>
					<td class="text-left required"><?php echo $entry_merchant_id; ?></td>
					<td class="text-left required"><?php echo $entry_secret; ?></td>
					<td class="text-left required"><?php echo $entry_environment; ?></td>
					<td class="text-left required"><?php echo $entry_country; ?></td>
					<td class="text-left required"><span data-toggle="tooltip" title="<?php echo $help_shipping; ?>"><?php echo $entry_shipping; ?></span></td>
					<td class="text-left required"><?php echo $entry_currency; ?></td>
					<td class="text-left required"><span data-toggle="tooltip" title="<?php echo $help_locale; ?>"><?php echo $entry_locale; ?></span></td>
					<td class="text-left required"><span data-toggle="tooltip" title="<?php echo $help_api; ?>"><?php echo $entry_api; ?></span></td>
					<td></td>
				  </tr>
				</thead>
				<tbody>
				  <?php $account_row = 0; ?>
				  <?php foreach ($klarna_checkout_account as $key => $account) { ?>
				  <tr id="account-row<?php echo $account_row; ?>">
					<td class="text-left">
					  <input type="text" name="klarna_checkout_account[<?php echo $account_row; ?>][merchant_id]" placeholder="<?php echo $entry_merchant_id; ?>" class="form-control" value="<?php echo $account['merchant_id']; ?>" />
					  <?php if (isset($error_account[$key]['merchant_id'])) { ?>
					  <div class="text-danger"><?php echo $error_account[$key]['merchant_id']; ?></div>
					  <?php } ?>
					</td>
					<td class="text-left">
					  <input type="text" name="klarna_checkout_account[<?php echo $account_row; ?>][secret]" placeholder="<?php echo $entry_merchant_id; ?>" class="form-control" value="<?php echo $account['secret']; ?>" />
					  <?php if (isset($error_account[$key]['secret'])) { ?>
					  <div class="text-danger"><?php echo $error_account[$key]['secret']; ?></div>
					  <?php } ?>
					</td>
					<td class="text-left">
					  <select name="klarna_checkout_account[<?php echo $account_row; ?>][environment]" class="form-control">
						<?php if ($account['environment'] == 'live') { ?>
						<option value="test"><?php echo $text_test; ?></option>
						<option value="live" selected="selected"><?php echo $text_live; ?></option>
						<?php } else { ?>
						<option value="test" selected="selected"><?php echo $text_test; ?></option>
						<option value="live"><?php echo $text_live; ?></option>
						<?php } ?>
					  </select>
					</td>
					<td class="text-left">
					  <select name="klarna_checkout_account[<?php echo $account_row; ?>][country]" class="form-control">
						<?php foreach ($countries as $country) { ?>
						<?php if ($country['country_id'] == $account['country']) { ?>
						<option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
						<?php } ?>
						<?php } ?>
					  </select>
					</td>
					<td class="text-left">
					  <select name="klarna_checkout_account[<?php echo $account_row; ?>][shipping]" class="form-control">
						<?php foreach ($geo_zones as $geo_zone) { ?>
						<?php if ($geo_zone['geo_zone_id'] == $account['shipping']) { ?>
						<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
						<?php } ?>
						<?php } ?>
					  </select>
					</td>
					<td class="text-left">
					  <select name="klarna_checkout_account[<?php echo $account_row; ?>][currency]" class="form-control">
						<?php foreach ($currencies as $currency) { ?>
						<?php if ($currency['code'] == $account['currency']) { ?>
						<option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
						<?php } ?>
						<?php } ?>
					  </select>
					</td>
					<td class="text-left">
					  <input type="text" name="klarna_checkout_account[<?php echo $account_row; ?>][locale]" placeholder="<?php echo $entry_locale; ?>" class="form-control" value="<?php echo $account['locale']; ?>" />
					  <?php if (isset($error_account[$key]['locale'])) { ?>
					  <div class="text-danger"><?php echo $error_account[$key]['locale']; ?></div>
					  <?php } ?>
					</td>
					<td class="text-left">
					  <select name="klarna_checkout_account[<?php echo $account_row; ?>][api]" class="form-control klarna-checkout-api">
						<?php foreach ($api_locations as $api_location) { ?>
						<?php if ($api_location['code'] == $account['api']) { ?>
						<option value="<?php echo $api_location['code']; ?>" selected="selected"><?php echo $api_location['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $api_location['code']; ?>"><?php echo $api_location['name']; ?></option>
						<?php } ?>
						<?php } ?>
					  </select>
					</td>
					<td class="text-left"><button type="button" onclick="$('#account-row<?php echo $account_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_account_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
				  </tr>
				  <?php $account_row++; ?>
				  <?php } ?>
				</tbody>
				<tfoot>
				  <tr>
					<td colspan="8"></td>
					<td class="text-left"><button type="button" onclick="addAccount();" data-toggle="tooltip" title="<?php echo $button_account_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
				  </tr>
				</tfoot>
			  </table>
			</div>
			<div class="tab-pane" id="tab-settlement">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sftp-username"><span data-toggle="tooltip" title="<?php echo $help_sftp_username; ?>"><?php echo $entry_sftp_username; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="klarna_checkout_sftp_username" placeholder="<?php echo $entry_sftp_username; ?>" class="form-control" value="<?php echo $klarna_checkout_sftp_username; ?>" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sftp-password"><span data-toggle="tooltip" title="<?php echo $help_sftp_password; ?>"><?php echo $entry_sftp_password; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="klarna_checkout_sftp_password" placeholder="<?php echo $entry_sftp_password; ?>" class="form-control" value="<?php echo $klarna_checkout_sftp_password; ?>" />
                </div>
              </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-settlement-order-status"><span data-toggle="tooltip" title="<?php echo $help_settlement_order_status; ?>"><?php echo $entry_settlement_order_status; ?></span></label>
				<div class="col-sm-10">
				  <select name="klarna_checkout_settlement_order_status_id" id="input-settlement-order-status" class="form-control">
					<?php foreach ($order_statuses as $order_status) { ?>
					<?php if ($order_status['order_status_id'] == $klarna_checkout_settlement_order_status_id) { ?>
					<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					<?php } ?>
					<?php } ?>
				  </select>
				</div>
			  </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_process_settlement; ?></label>
                <div class="col-sm-10">
                  <button type="button" id="button-process-settlement" title="<?php echo $button_process_settlement; ?>" class="btn btn-primary"><?php echo $button_process_settlement; ?></button>
                </div>
              </div>
			  <div class="settlement-alerts"></div>
			</div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('.klarna-checkout-api').on('change', function() {
	if ($(this).val() === 'EU') {
		alert('<?php echo addslashes($help_api); ?>');
	}
});

$(document).on('click', '#button-process-settlement', function() {
	if (confirm('<?php echo $text_confirm_settlement; ?>')) {
		$.ajax({
			url: 'index.php?route=extension/payment/klarna_checkout/downloadSettlementFiles&token=<?php echo $token; ?>',
			type: 'post',
			data: {
				username: $('input[name=klarna_checkout_sftp_username]').val(),
				password: $('input[name=klarna_checkout_sftp_password]').val(),
				order_status_id: $('select[name=klarna_checkout_settlement_order_status_id]').val()
			},
			dataType: 'json',
			beforeSend: function() {
				$('#button-process-settlement').button('loading');

				$('.settlement-alerts').empty();
			},
			complete: function() {
				$('#button-process-settlement').button('reset');
			},
			success: function(json) {
				if (json['error']) {
					$.each(json['error'], function(index, value) {
						$('.settlement-alerts').append('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + value + '</div>');
					});
				}

				if (json['orders']) {
					$('.settlement-alerts').append('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $text_downloading_settlement; ?></div>');

					if (json['orders'].length > 0) {
						$('.settlement-alerts').append('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $text_processing_orders; ?></div>');

						$.each(json['orders'], function(index, value) {
							$('.settlement-alerts').append('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $text_processing_order; ?> ' + value + '</div>');

							$.ajax({
								url: '<?php echo $store_url; ?>index.php?route=api/order/history&token=' + token + '&order_id=' + encodeURIComponent(value),
								type: 'post',
								dataType: 'json',
								data: 'order_status_id=' + encodeURIComponent($('select[name=klarna_checkout_settlement_order_status_id]').val()) + '&notify=0&override=1&comment',
								beforeSend: function() {
									//$('#button-history').button('loading');
								},
								complete: function() {
									//$('#button-history').button('reset');
								},
								success: function(json) {
									if (json['error']) {
										$('.settlement-alerts').append('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
									}

									if (json['success']) {
										$('.settlement-alerts').append('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
									}
								},
								error: function(xhr, ajaxOptions, thrownError) {
									alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
								}
							});
						});
					}

					$('.settlement-alerts').append('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $text_no_files; ?></div>');
				}
			}
		});
	}
});

$('#tabs a:first').tab('show');

$('.date').datetimepicker({
	pickTime: false
});

<?php if (empty($klarna_checkout_account)) { ?>
$(document).ready(function() {
	addAccount();
});
<?php } ?>

var account_row = <?php echo $account_row; ?>;

function addAccount() {
	html  = '<tr id="account-row' + account_row + '">';
    html += '  <td class="text-left"><input type="text" name="klarna_checkout_account[' + account_row + '][merchant_id]" placeholder="<?php echo $entry_merchant_id; ?>" class="form-control" value="" /></td>';
    html += '  <td class="text-left"><input type="text" name="klarna_checkout_account[' + account_row + '][secret]" placeholder="<?php echo $entry_secret; ?>" class="form-control" value="" /></td>';
	html += '  <td class="text-left"><select name="klarna_checkout_account[' + account_row + '][environment]" class="form-control">';
	html += '    <option value="live"><?php echo $text_live; ?></option>';
	html += '    <option value="test" selected="selected"><?php echo $text_test; ?></option>';
	html += '  </select>';
	html += '  <td class="text-left"><select name="klarna_checkout_account[' + account_row + '][country]" class="form-control">';
	<?php foreach ($countries as $country) { ?>
	html += '    <option value="<?php echo $country['country_id']; ?>"><?php echo addslashes($country['name']); ?></option>';
	<?php } ?>
	html += '  </select></td>';

	html += '  <td class="text-left"><select name="klarna_checkout_account[' + account_row + '][shipping]" class="form-control">';
	<?php foreach ($geo_zones as $geo_zone) { ?>
	html += '    <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo addslashes($geo_zone['name']); ?></option>';
	<?php } ?>
	html += '  </select></td>';

	html += '  <td class="text-left"><select name="klarna_checkout_account[' + account_row + '][currency]" class="form-control">';
	<?php foreach ($currencies as $currency) { ?>
	html += '    <option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>';
	<?php } ?>
	html += '  </select></td>';
    html += '  <td class="text-left"><input type="text" name="klarna_checkout_account[' + account_row + '][locale]" placeholder="<?php echo $entry_locale; ?>" class="form-control" value="" /></td>';
	html += '  <td class="text-left"><select name="klarna_checkout_account[' + account_row + '][api]" class="form-control">';
	<?php foreach ($api_locations as $api_location) { ?>
	html += '    <option value="<?php echo $api_location['code']; ?>"><?php echo $api_location['name']; ?></option>';
	<?php } ?>
	html += '  </select></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#account-row' + account_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_account_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';

	$('#account tbody').append(html);

	account_row++;
}

$(document).delegate('#button-ip-add', 'click', function() {
    $.ajax({
        url: 'index.php?route=user/api/addip&token=<?php echo $token; ?>&api_id=<?php echo $api_id; ?>',
        type: 'post',
        data: 'ip=<?php echo $api_ip; ?>',
        dataType: 'json',
        beforeSend: function() {
            $('#button-ip-add').button('loading');
        },
        complete: function() {
            $('#button-ip-add').button('reset');
        },
        success: function(json) {
            $('.api-alert').remove();

            if (json['error']) {
                $('#content > .container-fluid').prepend('<div class="alert alert-danger api-alert"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            }

            if (json['success']) {
                $('#content > .container-fluid').prepend('<div class="alert alert-success api-alert"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

var token = '';

// Login to the API
$.ajax({
    url: '<?php echo $store_url; ?>index.php?route=api/login',
    type: 'post',
    dataType: 'json',
    data: 'key=<?php echo $api_key; ?>',
    crossDomain: true,
    success: function(json) {
        $('.api-alert').remove();

        if (json['error']) {
            if (json['error']['key']) {
                $('#content > .container-fluid').prepend('<div class="alert alert-danger api-alert"><i class="fa fa-exclamation-circle"></i> ' + json['error']['key'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            }

            if (json['error']['ip']) {
                $('#content > .container-fluid').prepend('<div class="alert alert-danger api-alert"><i class="fa fa-exclamation-circle"></i> ' + json['error']['ip'] + ' <button type="button" id="button-ip-add" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger btn-xs pull-right"><i class="fa fa-plus"></i> <?php echo $button_ip_add; ?></button></div>');
            }
        }

        if (json['token']) {
            token = json['token'];
        }
    },
    error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
});
//--></script>
<?php echo $footer; ?>