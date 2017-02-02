<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-mastercard-hosted-checkout" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $rehook_events; ?>" data-toggle="tooltip" title="<?php echo $button_rehook_events; ?>" class="btn btn-warning"><i class="fa fa-refresh"></i></a>
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
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>&nbsp;<?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i>&nbsp;<?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i>&nbsp;<?php echo $text_notification_ssl; ?></div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i>&nbsp;<?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-mastercard-hosted-checkout" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li><a href="#tab-transaction" data-toggle="tab"><i class="fa fa-list"></i>&nbsp;<?php echo $tab_transaction; ?></a></li>
            <li><a href="#tab-setting" data-toggle="tab"><i class="fa fa-gear"></i>&nbsp;<?php echo $tab_setting; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane" id="tab-setting">
              <fieldset>
                <legend><?php echo $text_general_settings; ?></legend>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-merchant"><span data-toggle="tooltip" title="<?php echo $help_merchant; ?>"><?php echo $entry_merchant; ?></span></label>
                  <div class="col-sm-10">
                    <input type="text" name="mastercard_pgs_merchant" value="<?php echo $mastercard_pgs_merchant; ?>" placeholder="<?php echo $entry_merchant; ?>" id="input-merchant" class="form-control" />
                    <?php if ($error_merchant) { ?>
                    <div class="text-danger"><?php echo $error_merchant; ?></div>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-integration-password"><span data-toggle="tooltip" title="<?php echo $help_integration_password; ?>"><?php echo $entry_integration_password; ?></span></label>
                  <div class="col-sm-10">
                    <div class="input-group">
                      <input type="password" name="mastercard_pgs_integration_password" value="<?php echo $mastercard_pgs_integration_password; ?>" placeholder="<?php echo $entry_integration_password; ?>" id="input-integration-password" class="form-control" />
                      <a class="input-group-addon btn show_hide_credential" data-toggle="tooltip" title="<?php echo $text_show_hide; ?>"><i class="fa fa-eye"></i></a>
                    </div>
                    <?php if ($error_integration_password) { ?>
                    <div class="text-danger"><?php echo $error_integration_password; ?></div>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-notification-secret"><span data-toggle="tooltip" title="<?php echo $help_notification_secret; ?>"><?php echo $entry_notification_secret; ?></span></label>
                  <div class="col-sm-10">
                    <div class="input-group">
                      <input type="password" name="mastercard_pgs_notification_secret" value="<?php echo $mastercard_pgs_notification_secret; ?>" placeholder="<?php echo $entry_notification_secret; ?>" id="input-notification-secret" class="form-control" />
                      <a class="input-group-addon btn show_hide_credential" data-toggle="tooltip" title="<?php echo $text_show_hide; ?>"><i class="fa fa-eye"></i></a>
                    </div>
                    <?php if ($error_notification_secret) { ?>
                    <div class="text-danger"><?php echo $error_notification_secret; ?></div>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_display_name; ?>"><?php echo $entry_display_name; ?></span></label>
                  <div class="col-sm-10">
                    <?php foreach ($languages as $language) : ?>
                      <div class="input-group margin-bottom">
                        <div class="input-group-addon" data-toggle="tooltip" title="<?php echo $language['name']; ?>"><img src="<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" /></div>
                        <input type="text" name="mastercard_pgs_display_name[<?php echo $language['language_id']; ?>]" value="<?php echo !empty($mastercard_pgs_display_name[$language['language_id']]) ? $mastercard_pgs_display_name[$language['language_id']] : $default_display_name; ?>" placeholder="<?php echo $entry_display_name; ?>" class="form-control" />
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="select-tokenize"><span data-toggle="tooltip" title="<?php echo $help_tokenize; ?>"><?php echo $entry_tokenize; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_tokenize" id="select-tokenize" class="form-control">
                      <?php if ($mastercard_pgs_tokenize) { ?>
                      <option value="0"><?php echo $text_disabled; ?></option>
                      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                      <?php } else { ?>
                      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                      <option value="1"><?php echo $text_enabled; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="select-checkout"><span data-toggle="tooltip" title="<?php echo $help_checkout; ?>"><?php echo $entry_checkout; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_checkout" id="select-checkout" class="form-control">
                      <?php if ($mastercard_pgs_checkout == 'pay') { ?>
                      <option value="authorize"><?php echo $text_authorize; ?></option>
                      <option value="pay" selected="selected"><?php echo $text_pay; ?></option>
                      <?php } else { ?>
                      <option value="authorize" selected="selected"><?php echo $text_authorize; ?></option>
                      <option value="pay"><?php echo $text_pay; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="select-debug-log"><span data-toggle="tooltip" title="<?php echo $help_debug_log; ?>"><?php echo $entry_debug_log; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_debug_log" id="select-debug-log" class="form-control">
                      <?php if ($mastercard_pgs_debug_log) { ?>
                      <option value="0"><?php echo $text_disabled; ?></option>
                      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                      <?php } else { ?>
                      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                      <option value="1"><?php echo $text_enabled; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="select-gateway"><span data-toggle="tooltip" title="<?php echo $help_gateway; ?>"><?php echo $entry_gateway; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_gateway" id="select-gateway" class="form-control margin-bottom">
                      <?php foreach ($gateways as $gateway) { ?>
                      <?php if ($gateway['code'] == $mastercard_pgs_gateway) { ?>
                      <option value="<?php echo $gateway['code']; ?>" selected="selected"><?php echo $gateway['text']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $gateway['code']; ?>"><?php echo $gateway['text']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                    <div id="gateway_other">
                      <input type="text" name="mastercard_pgs_gateway_other" value="<?php echo $mastercard_pgs_gateway_other; ?>" placeholder="<?php echo $entry_gateway_other; ?>" class="form-control" />
                      <?php if ($error_gateway_other) { ?>
                        <div class="text-danger"><?php echo $error_gateway_other; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="select-onclick"><?php echo $entry_onclick; ?></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_onclick" id="select-onclick" class="form-control">
                      <?php if ($mastercard_pgs_onclick == 'hosted_payment_page') { ?>
                      <option value="lightbox"><?php echo $text_lightbox; ?></option>
                      <option value="hosted_payment_page" selected="selected"><?php echo $text_hosted_payment_page; ?></option>
                      <?php } else { ?>
                      <option value="lightbox" selected="selected"><?php echo $text_lightbox; ?></option>
                      <option value="hosted_payment_page"><?php echo $text_hosted_payment_page; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-google-analytics-property-id"><span data-toggle="tooltip" title="<?php echo $help_google_analytics_property_id; ?>"><?php echo $entry_google_analytics_property_id; ?></span></label>
                  <div class="col-sm-10">
                    <input type="text" name="mastercard_pgs_google_analytics_property_id" value="<?php echo $mastercard_pgs_google_analytics_property_id; ?>" placeholder="<?php echo $entry_google_analytics_property_id; ?>" id="input-google-analytics-property-id" class="form-control" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
                  <div class="col-sm-10">
                    <input type="text" name="mastercard_pgs_total" value="<?php echo $mastercard_pgs_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="select-geo-zone"><?php echo $entry_geo_zone; ?></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_geo_zone_id" id="select-geo-zone" class="form-control">
                      <option value="0"><?php echo $text_all_zones; ?></option>
                      <?php foreach ($geo_zones as $geo_zone) { ?>
                      <?php if ($geo_zone['geo_zone_id'] == $mastercard_pgs_geo_zone_id) { ?>
                      <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="select-status"><?php echo $entry_status; ?></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_status" id="select-status" class="form-control">
                      <?php if ($mastercard_pgs_status) { ?>
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
                    <input type="text" name="mastercard_pgs_sort_order" value="<?php echo $mastercard_pgs_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <legend><?php echo $text_transaction_statuses; ?></legend>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-approved-authorization-order-status"><span data-toggle="tooltip" title="<?php echo $help_approved_authorization_order_status; ?>"><?php echo $entry_approved_authorization_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_approved_authorization_order_status_id" id="input-approved-authorization-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_approved_authorization_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-approved-capture-order-status"><span data-toggle="tooltip" title="<?php echo $help_approved_capture_order_status; ?>"><?php echo $entry_approved_capture_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_approved_capture_order_status_id" id="input-approved-capture-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_approved_capture_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-approved-payment-order-status"><span data-toggle="tooltip" title="<?php echo $help_approved_payment_order_status; ?>"><?php echo $entry_approved_payment_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_approved_payment_order_status_id" id="input-approved-payment-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_approved_payment_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-approved-refund-order-status"><span data-toggle="tooltip" title="<?php echo $help_approved_refund_order_status; ?>"><?php echo $entry_approved_refund_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_approved_refund_order_status_id" id="input-approved-refund-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_approved_refund_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-approved-void-order-status"><span data-toggle="tooltip" title="<?php echo $help_approved_void_order_status; ?>"><?php echo $entry_approved_void_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_approved_void_order_status_id" id="input-approved-void-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_approved_void_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-approved-verification-order-status"><span data-toggle="tooltip" title="<?php echo $help_approved_verification_order_status; ?>"><?php echo $entry_approved_verification_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_approved_verification_order_status_id" id="input-approved-verification-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_approved_verification_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-unspecified_failure-order-status"><span data-toggle="tooltip" title="<?php echo $help_unspecified_failure_order_status; ?>"><?php echo $entry_unspecified_failure_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_unspecified_failure_order_status_id" id="input-unspecified_failure-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_unspecified_failure_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-declined-order-status"><span data-toggle="tooltip" title="<?php echo $help_declined_order_status; ?>"><?php echo $entry_declined_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_declined_order_status_id" id="input-declined-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_declined_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-timed_out-order-status"><span data-toggle="tooltip" title="<?php echo $help_timed_out_order_status; ?>"><?php echo $entry_timed_out_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_timed_out_order_status_id" id="input-timed_out-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_timed_out_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-expired_card-order-status"><span data-toggle="tooltip" title="<?php echo $help_expired_card_order_status; ?>"><?php echo $entry_expired_card_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_expired_card_order_status_id" id="input-expired_card-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_expired_card_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-insufficient_funds-order-status"><span data-toggle="tooltip" title="<?php echo $help_insufficient_funds_order_status; ?>"><?php echo $entry_insufficient_funds_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_insufficient_funds_order_status_id" id="input-insufficient_funds-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_insufficient_funds_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-acquirer_system_error-order-status"><span data-toggle="tooltip" title="<?php echo $help_acquirer_system_error_order_status; ?>"><?php echo $entry_acquirer_system_error_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_acquirer_system_error_order_status_id" id="input-acquirer_system_error-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_acquirer_system_error_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-system_error-order-status"><span data-toggle="tooltip" title="<?php echo $help_system_error_order_status; ?>"><?php echo $entry_system_error_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_system_error_order_status_id" id="input-system_error-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_system_error_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-not_supported-order-status"><span data-toggle="tooltip" title="<?php echo $help_not_supported_order_status; ?>"><?php echo $entry_not_supported_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_not_supported_order_status_id" id="input-not_supported-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_not_supported_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-declined_do_not_contact-order-status"><span data-toggle="tooltip" title="<?php echo $help_declined_do_not_contact_order_status; ?>"><?php echo $entry_declined_do_not_contact_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_declined_do_not_contact_order_status_id" id="input-declined_do_not_contact-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_declined_do_not_contact_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-aborted-order-status"><span data-toggle="tooltip" title="<?php echo $help_aborted_order_status; ?>"><?php echo $entry_aborted_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_aborted_order_status_id" id="input-aborted-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_aborted_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-blocked-order-status"><span data-toggle="tooltip" title="<?php echo $help_blocked_order_status; ?>"><?php echo $entry_blocked_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_blocked_order_status_id" id="input-blocked-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_blocked_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-cancelled-order-status"><span data-toggle="tooltip" title="<?php echo $help_cancelled_order_status; ?>"><?php echo $entry_cancelled_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_cancelled_order_status_id" id="input-cancelled-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_cancelled_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-deferred_transaction_received-order-status"><span data-toggle="tooltip" title="<?php echo $help_deferred_transaction_received_order_status; ?>"><?php echo $entry_deferred_transaction_received_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_deferred_transaction_received_order_status_id" id="input-deferred_transaction_received-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_deferred_transaction_received_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-referred-order-status"><span data-toggle="tooltip" title="<?php echo $help_referred_order_status; ?>"><?php echo $entry_referred_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_referred_order_status_id" id="input-referred-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_referred_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-authentication_failed-order-status"><span data-toggle="tooltip" title="<?php echo $help_authentication_failed_order_status; ?>"><?php echo $entry_authentication_failed_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_authentication_failed_order_status_id" id="input-authentication_failed-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_authentication_failed_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-invalid_csc-order-status"><span data-toggle="tooltip" title="<?php echo $help_invalid_csc_order_status; ?>"><?php echo $entry_invalid_csc_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_invalid_csc_order_status_id" id="input-invalid_csc-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_invalid_csc_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-lock_failure-order-status"><span data-toggle="tooltip" title="<?php echo $help_lock_failure_order_status; ?>"><?php echo $entry_lock_failure_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_lock_failure_order_status_id" id="input-lock_failure-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_lock_failure_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-submitted-order-status"><span data-toggle="tooltip" title="<?php echo $help_submitted_order_status; ?>"><?php echo $entry_submitted_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_submitted_order_status_id" id="input-submitted-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_submitted_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-not_enrolled_3d_secure-order-status"><span data-toggle="tooltip" title="<?php echo $help_not_enrolled_3d_secure_order_status; ?>"><?php echo $entry_not_enrolled_3d_secure_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_not_enrolled_3d_secure_order_status_id" id="input-not_enrolled_3d_secure-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_not_enrolled_3d_secure_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-pending-order-status"><span data-toggle="tooltip" title="<?php echo $help_pending_order_status; ?>"><?php echo $entry_pending_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_pending_order_status_id" id="input-pending-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_pending_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-exceeded_retry_limit-order-status"><span data-toggle="tooltip" title="<?php echo $help_exceeded_retry_limit_order_status; ?>"><?php echo $entry_exceeded_retry_limit_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_exceeded_retry_limit_order_status_id" id="input-exceeded_retry_limit-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_exceeded_retry_limit_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-duplicate_batch-order-status"><span data-toggle="tooltip" title="<?php echo $help_duplicate_batch_order_status; ?>"><?php echo $entry_duplicate_batch_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_duplicate_batch_order_status_id" id="input-duplicate_batch-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_duplicate_batch_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-declined_avs-order-status"><span data-toggle="tooltip" title="<?php echo $help_declined_avs_order_status; ?>"><?php echo $entry_declined_avs_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_declined_avs_order_status_id" id="input-declined_avs-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_declined_avs_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-declined_csc-order-status"><span data-toggle="tooltip" title="<?php echo $help_declined_csc_order_status; ?>"><?php echo $entry_declined_csc_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_declined_csc_order_status_id" id="input-declined_csc-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_declined_csc_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-declined_avs_csc-order-status"><span data-toggle="tooltip" title="<?php echo $help_declined_avs_csc_order_status; ?>"><?php echo $entry_declined_avs_csc_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_declined_avs_csc_order_status_id" id="input-declined_avs_csc-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_declined_avs_csc_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-declined_payment_plan-order-status"><span data-toggle="tooltip" title="<?php echo $help_declined_payment_plan_order_status; ?>"><?php echo $entry_declined_payment_plan_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_declined_payment_plan_order_status_id" id="input-declined_payment_plan-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_declined_payment_plan_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-approved-pending_settlement-order-status"><span data-toggle="tooltip" title="<?php echo $help_approved_pending_settlement_order_status; ?>"><?php echo $entry_approved_pending_settlement_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_approved_pending_settlement_order_status_id" id="input-approved-pending_settlement-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_approved_pending_settlement_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-partially_approved-order-status"><span data-toggle="tooltip" title="<?php echo $help_partially_approved_order_status; ?>"><?php echo $entry_partially_approved_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_partially_approved_order_status_id" id="input-partially_approved-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_partially_approved_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-unknown-order-status"><span data-toggle="tooltip" title="<?php echo $help_unknown_order_status; ?>"><?php echo $entry_unknown_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_unknown_order_status_id" id="input-unknown-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_unknown_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-risk-rejected-order-status"><span data-toggle="tooltip" title="<?php echo $help_risk_rejected_order_status; ?>"><?php echo $entry_risk_rejected_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_risk_rejected_order_status_id" id="input-risk-rejected-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_risk_rejected_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-risk-review-pending-order-status"><span data-toggle="tooltip" title="<?php echo $help_risk_review_pending_order_status; ?>"><?php echo $entry_risk_review_pending_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_risk_review_pending_order_status_id" id="input-risk-review-pending-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_risk_review_pending_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-risk-review-rejected-order-status"><span data-toggle="tooltip" title="<?php echo $help_risk_review_rejected_order_status; ?>"><?php echo $entry_risk_review_rejected_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mastercard_pgs_risk_review_rejected_order_status_id" id="input-risk-review-rejected-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $mastercard_pgs_risk_review_rejected_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="tab-pane" id="tab-transaction">
              <div class="text-right margin-bottom">
                <a class="btn btn-warning" data-toggle="tooltip" data-original-title="<?php echo $text_refresh; ?>" id="refresh_transactions"><i class="fa fa-refresh"></i>&nbsp;<?php echo $button_refresh; ?></a>
              </div>
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th class="text-left hidden-xs"><?php echo $column_merchant; ?></th>
                      <th class="text-left"><?php echo $column_order_id; ?></th>
                      <th class="text-left hidden-xs"><?php echo $column_type; ?></th>
                      <th class="text-left hidden-xs"><?php echo $column_amount; ?></th>
                      <th class="text-left hidden-xs"><?php echo $column_risk; ?></th>
                      <th class="text-left hidden-xs hidden-sm"><?php echo $column_ip; ?></th>
                      <th class="text-left"><?php echo $column_date_created; ?></th>
                      <th class="text-right"><?php echo $column_action; ?></th>
                    </tr>
                  </thead>
                  <tbody id="transactions">
                  </tbody>
                </table>
                <div id="transactions_pagination"></div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<style type="text/css">
  .margin-bottom {
    margin-bottom: 10px;
  }
</style>
<script type="text/javascript">
$(document).ready(function() {
  var list_transactions = function(page) {
    $.ajax({
      url : '<?php echo $url_list_transactions; ?>'.replace('{PAGE}', page ? page : 1),
      dataType : 'json',
      beforeSend : function() {
        $('#refresh_transactions').button('loading');
        $('#transactions_pagination').empty();
        $('#transactions').html('<tr><td colspan="9" class="text-center"><i class="fa fa-circle-o-notch fa-spin"></i>&nbsp;<?php echo $text_loading; ?></td></tr>');
      },
      success : function(data) {
        var html = '';

        if (data.transactions.length) {
          for (var i in data.transactions) {
            var row = data.transactions[i];

            html += '<tr>';
            html += '<td class="text-left hidden-xs">' + row.merchant + '</td>';
            html += '<td class="text-left"><a target="_blank" href="' + row.url_order + '">' + row.order_id + '</td>';
            html += '<td class="text-left hidden-xs">' + row.type + '</td>';
            html += '<td class="text-left hidden-xs">' + row.amount + '</td>';
            html += '<td class="text-left hidden-xs">' + row.risk + '</td>';
            html += '<td class="text-left hidden-xs hidden-sm">' + row.ip + '</td>';
            html += '<td class="text-left">' + row.date_created + '</td>';
            html += '<td class="text-right"><a class="btn btn-info" href="' + row.url_info + '" data-toggle="tooltip" data-original-title="<?php echo $text_view; ?>"><i class="fa fa-eye"></i></a></td>';
            html += '</tr>';
          }
        } else {
          html += '<tr>';
          html += '<td class="text-center" colspan="9"><?php echo $text_no_transactions; ?></td>';
          html += '</tr>';
        }

        $('#transactions').html(html);
        
        $('#transactions_pagination').html(data.pagination).find('a[href]').each(function(index,element) {
          $(this).click(function(e) {
            e.preventDefault();
            list_transactions(isNaN($(this).attr('href')) ? 1 : $(this).attr('href'));
          })
        });
      },
      complete : function() {
        $('#refresh_transactions').button('reset');
      }
    });
  }

  $('#refresh_transactions').click(function(e) {
    e.preventDefault();
    list_transactions();
  }).trigger('click');

  $('.nav-tabs a[href="#<?php echo $tab ?>"]').tab('show');

  $('.text-danger').each(function() {
    $(this).closest('.form-group').addClass('has-error');
  });

  $('#select-gateway').change(function() {
    if ($(this).val() == 'other') {
      $('#gateway_other').show();
    } else {
      $('#gateway_other').hide();
      $(this).closest('.form-group').removeClass('has-error');
    }
  }).trigger('change');

  $('.show_hide_credential').click(function(e) {
    e.preventDefault();
    var input = $(this).closest('.input-group').find('input');

    if ($(input).attr('type') == 'text') {
      $(input).attr('type', 'password');
    } else {
      $(input).attr('type', 'text');
    }
  });
});
</script>
<?php echo $footer; ?> 