<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-ebay-settings" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary" onclick="validateForm(); return false;"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-ebay-settings" class="form-horizontal">
        <input type="hidden" name="ebay_itm_link" value="<?php echo $ebay_itm_link; ?>" />

        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_api_info; ?></a></li>
          <li><a href="#tab-setup" data-toggle="tab"><?php echo $tab_setup; ?></a></li>
          <li><a href="#tab-defaults" data-toggle="tab"><?php echo $tab_defaults; ?></a></li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane active" id="tab-general">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="ebay-status"><?php echo $text_status; ?></label>
              <div class="col-sm-10">
                <select name="ebay_status" id="ebay-status" class="form-control">
                  <?php if ($ebay_status) { ?>
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
              <label class="col-sm-2 control-label" for="ebay-token"><?php echo $text_obp_token; ?></label>
              <div class="col-sm-10">
                <input type="text" name="ebay_token" value="<?php echo $ebay_token; ?>" placeholder="<?php echo $text_obp_token; ?>" id="ebay-token" class="form-control credentials" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="ebay-secret"><?php echo $text_obp_secret; ?></label>
              <div class="col-sm-10">
                <input type="text" name="ebay_secret" value="<?php echo $ebay_secret; ?>" placeholder="<?php echo $text_obp_secret; ?>" id="ebay-secret" class="form-control credentials" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="ebay-string1"><?php echo $text_obp_string1; ?></label>
              <div class="col-sm-10">
                <input type="text" name="ebay_string1" value="<?php echo $ebay_string1; ?>" placeholder="<?php echo $text_obp_string1; ?>" id="ebay-string1" class="form-control credentials" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="ebay-string2"><?php echo $text_obp_string2; ?></label>
              <div class="col-sm-10">
                <input type="text" name="ebay_string2" value="<?php echo $ebay_string2; ?>" placeholder="<?php echo $text_obp_string2; ?>" id="ebay-string2" class="form-control credentials" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_api_status; ?></label>
              <div class="col-sm-10">
                <h4><span id="api-status" class="label" style="display:none;"></span></h4>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_api_other; ?></label>
              <div class="col-sm-10">
                <p><a href="https://account.openbaypro.com/ebay/apiRegister/" target="_BLANK"><i class="fa fa-link"></i> <?php echo $text_obp_token_register; ?></a></p>
                <p><a href="https://account.openbaypro.com/ebay/apiRenew/" target="_BLANK"><i class="fa fa-link"></i> <?php echo $text_obp_token_renew; ?></a></p>
                <p><a href="http://account.openbaypro.com/ebay/apiUpdate/" target="_BLANK"><i class="fa fa-link"></i> <?php echo $text_obp_detail_update; ?></a></p>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-setup">
            <fieldset>
              <legend><?php echo $text_legend_app_settings; ?></legend>
              <p><?php echo $text_app_setting_msg; ?></p>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_enditems"><?php echo $text_app_end_ebay; ?></label>
                <div class="col-sm-10">
                  <select name="ebay_enditems" id="ebay_enditems" class="form-control">
                    <?php if ($ebay_enditems) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $text_app_end_ebay_help; ?></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_relistitems"><?php echo $text_app_relist_ebay; ?></label>
                <div class="col-sm-10">
                  <select name="ebay_relistitems" id="ebay_relistitems" class="form-control">
                    <?php if ($ebay_relistitems) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $text_app_relist_ebay_help; ?></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_disable_nostock"><?php echo $text_app_nostock_ebay; ?></label>
                <div class="col-sm-10">
                  <select name="ebay_disable_nostock" id="ebay_disable_nostock" class="form-control">
                    <?php if ($ebay_disable_nostock) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $text_app_nostock_ebay_help; ?></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_logging"><?php echo $text_app_logging; ?></label>
                <div class="col-sm-10">
                  <select name="ebay_logging" id="ebay_logging" class="form-control">
                    <?php if ($ebay_logging) { ?>
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
                <label class="col-sm-2 control-label" for="ebay_def_currency"><?php echo $text_app_currency; ?></label>
                <div class="col-sm-10">
                  <select name="ebay_def_currency" id="ebay_def_currency" class="form-control">
                    <?php foreach ($currency_list as $currency) { ?>
                    <?php echo '<option value="'.$currency['code'].'"'.($ebay_def_currency == $currency['code'] ? ' selected="selected"' : '').'>'.$currency['title'].'</option>'; ?>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $text_app_currency_msg; ?></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_def_customer_grp"><?php echo $text_app_cust_grp; ?></label>
                <div class="col-sm-10">
                  <select name="ebay_def_customer_grp" id="ebay_def_customer_grp" class="form-control">
                    <?php foreach ($customer_grp_list as $customer_grp) { ?>
                    <?php echo '<option value="'.$customer_grp['customer_group_id'].'"'.($ebay_def_customer_grp == $customer_grp['customer_group_id'] ? ' selected="selected"' : '').'>'.$customer_grp['name'].'</option>'; ?>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $text_app_cust_grp_help; ?></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_stock_allocate"><?php echo $text_app_stock_allocate; ?></label>
                <div class="col-sm-10">
                  <select name="ebay_stock_allocate" id="ebay_stock_allocate" class="form-control">
                    <?php if ($ebay_stock_allocate) { ?>
                      <option value="1" selected="selected"><?php echo $text_app_stock_2; ?></option>
                      <option value="0"><?php echo $text_app_stock_1; ?></option>
                    <?php } else { ?>
                      <option value="1"><?php echo $text_app_stock_2; ?></option>
                      <option value="0" selected="selected"><?php echo $text_app_stock_1; ?></option>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $text_app_stock_allocate_help; ?></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_created_hours"><?php echo $text_created_hours; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="ebay_created_hours" value="<?php echo $ebay_created_hours;?>" placeholder="<?php echo $text_obp_secret; ?>" id="ebay_created_hours" class="form-control" />
                  <span class="help-block"><?php echo $text_created_hours_help; ?></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_stock_allocate"><?php echo $text_create_date; ?></label>
                <div class="col-sm-10">
                  <select name="ebay_create_date" id="ebay_create_date" class="form-control">
                    <?php if ($ebay_create_date) { ?>
                    <option value="1" selected="selected"><?php echo $text_create_date_1; ?></option>
                    <option value="0"><?php echo $text_create_date_0; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_create_date_1; ?></option>
                    <option value="0" selected="selected"><?php echo $text_create_date_0; ?></option>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $text_create_date_help; ?></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_time_offset"><?php echo $text_timezone_offset; ?></label>
                <div class="col-sm-10">
                  <select name="ebay_time_offset" id="ebay_time_offset" class="form-control">
                    <option value="-12"<?php if ($ebay_time_offset == '-12'){ echo ' selected';} ?>>-12</option>
                    <option value="-11"<?php if ($ebay_time_offset == '-11'){ echo ' selected';} ?>>-11</option>
                    <option value="-10"<?php if ($ebay_time_offset == '-10'){ echo ' selected';} ?>>-10</option>
                    <option value="-9"<?php if ($ebay_time_offset == '-9'){ echo ' selected';} ?>>-9</option>
                    <option value="-8"<?php if ($ebay_time_offset == '-8'){ echo ' selected';} ?>>-8</option>
                    <option value="-7"<?php if ($ebay_time_offset == '-7'){ echo ' selected';} ?>>-7</option>
                    <option value="-6"<?php if ($ebay_time_offset == '-6'){ echo ' selected';} ?>>-6</option>
                    <option value="-5"<?php if ($ebay_time_offset == '-5'){ echo ' selected';} ?>>-5</option>
                    <option value="-4"<?php if ($ebay_time_offset == '-4'){ echo ' selected';} ?>>-4</option>
                    <option value="-3"<?php if ($ebay_time_offset == '-3'){ echo ' selected';} ?>>-3</option>
                    <option value="-2"<?php if ($ebay_time_offset == '-2'){ echo ' selected';} ?>>-2</option>
                    <option value="-1"<?php if ($ebay_time_offset == '-1'){ echo ' selected';} ?>>-1</option>
                    <option value="0"<?php if ($ebay_time_offset == '0'){ echo ' selected';} ?>>0</option>
                    <option value="+1"<?php if ($ebay_time_offset == '+1'){ echo ' selected';} ?>>+1</option>
                    <option value="+2"<?php if ($ebay_time_offset == '+2'){ echo ' selected';} ?>>+2</option>
                    <option value="+3"<?php if ($ebay_time_offset == '+3'){ echo ' selected';} ?>>+3</option>
                    <option value="+4"<?php if ($ebay_time_offset == '+4'){ echo ' selected';} ?>>+4</option>
                    <option value="+5"<?php if ($ebay_time_offset == '+5'){ echo ' selected';} ?>>+5</option>
                    <option value="+6"<?php if ($ebay_time_offset == '+6'){ echo ' selected';} ?>>+6</option>
                    <option value="+7"<?php if ($ebay_time_offset == '+7'){ echo ' selected';} ?>>+7</option>
                    <option value="+8"<?php if ($ebay_time_offset == '+8'){ echo ' selected';} ?>>+8</option>
                    <option value="+9"<?php if ($ebay_time_offset == '+9'){ echo ' selected';} ?>>+9</option>
                    <option value="+10"<?php if ($ebay_time_offset == '+10'){ echo ' selected';} ?>>+10</option>
                    <option value="+11"<?php if ($ebay_time_offset == '+11'){ echo ' selected';} ?>>+11</option>
                    <option value="+12"<?php if ($ebay_time_offset == '+12'){ echo ' selected';} ?>>+12</option>
                  </select>
                  <span class="help-block"><?php echo $text_timezone_offset_help; ?></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_default_addressformat"><?php echo $text_address_format; ?></label>
                <div class="col-sm-10">
                  <textarea name="ebay_default_addressformat" class="form-control" rows="3" id="ebay_default_addressformat"><?php echo $ebay_default_addressformat; ?></textarea>
                  <span class="help-block"><?php echo $text_address_format_help; ?></span>
                </div>
              </div>
            </fieldset>
            <fieldset>
              <legend><?php echo $text_legend_notify_settings; ?></legend>
              <p><?php echo $text_notify_setting_msg; ?></p>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_update_notify"><?php echo $text_ebay_update_notify; ?></label>
                <div class="col-sm-10">
                  <select name="ebay_update_notify" id="ebay_update_notify" class="form-control">
                    <?php if ($ebay_update_notify) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $text_ebay_update_notify_help; ?></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_confirm_notify"><?php echo $text_ebay_confirm_notify; ?></label>
                <div class="col-sm-10">
                  <select name="ebay_confirm_notify" id="ebay_confirm_notify" class="form-control">
                    <?php if ($ebay_confirm_notify) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $text_ebay_confirm_notify_help; ?></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_confirmadmin_notify"><?php echo $text_ebay_confirmadmin_notify; ?></label>
                <div class="col-sm-10">
                  <select name="ebay_confirmadmin_notify" id="ebay_confirmadmin_notify" class="form-control">
                    <?php if ($ebay_confirmadmin_notify) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $text_ebay_confirmadmin_notify_help; ?></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_email_brand_disable"><?php echo $text_openbaypro_brand_disable; ?></label>
                <div class="col-sm-10">
                  <select name="ebay_email_brand_disable" id="ebay_email_brand_disable" class="form-control">
                    <?php if ($ebay_email_brand_disable) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </fieldset>

            <fieldset>
              <legend><?php echo $text_legend_default_import; ?></legend>
              <p><?php echo $text_import_desc; ?></p>

              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $text_import_pending; ?></label>
                <div class="col-sm-10">
                  <input type="hidden" name="ebay_import_unpaid" value="0" />
                  <input type="checkbox" name="ebay_import_unpaid" value="1" <?php if ($ebay_import_unpaid == 1){ echo 'checked="checked"'; } ?> />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_status_import_id"><?php echo $text_import_def_id; ?></label>
                <div class="col-sm-10">
                  <select name="ebay_status_import_id" id="ebay_status_import_id" class="form-control">
                    <?php if (empty($ebay_status_import_id)) { $ebay_status_import_id = 1; } ?>

                    <?php foreach ($order_statuses as $status) { ?>
                    <?php echo'<option value="'.$status['order_status_id'].'"'.($ebay_status_import_id == $status['order_status_id'] ? ' selected=selected' :'').'>'.$status['name'].'</option>'; ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_status_paid_id"><?php echo $text_import_paid_id; ?></label>
                <div class="col-sm-10">
                  <select name="ebay_status_paid_id" id="ebay_status_paid_id" class="form-control">
                    <?php if (empty($ebay_status_paid_id)) { $ebay_status_paid_id = 2; } ?>

                    <?php foreach ($order_statuses as $status) { ?>
                    <?php echo'<option value="'.$status['order_status_id'].'"'.($ebay_status_paid_id == $status['order_status_id'] ? ' selected=selected' :'').'>'.$status['name'].'</option>'; ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_status_shipped_id"><?php echo $text_import_shipped_id; ?></label>
                <div class="col-sm-10">
                  <select name="ebay_status_shipped_id" id="ebay_status_shipped_id" class="form-control">
                    <?php if (empty($ebay_status_shipped_id)) { $ebay_status_shipped_id = 3; } ?>

                    <?php foreach ($order_statuses as $status) { ?>
                    <?php echo'<option value="'.$status['order_status_id'].'"'.($ebay_status_shipped_id == $status['order_status_id'] ? ' selected=selected' :'').'>'.$status['name'].'</option>'; ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_status_cancelled_id"><?php echo $text_import_cancelled_id; ?></label>
                <div class="col-sm-10">
                  <select name="ebay_status_cancelled_id" id="ebay_status_cancelled_id" class="form-control">
                    <?php if (empty($ebay_status_cancelled_id)) { $ebay_status_cancelled_id = 7; } ?>

                    <?php foreach ($order_statuses as $status) { ?>
                    <?php echo'<option value="'.$status['order_status_id'].'"'.($ebay_status_cancelled_id == $status['order_status_id'] ? ' selected=selected' :'').'>'.$status['name'].'</option>'; ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_status_partial_refund_id"><?php echo $text_import_part_refund_id; ?></label>
                <div class="col-sm-10">
                  <select name="ebay_status_partial_refund_id" id="ebay_status_partial_refund_id" class="form-control">
                    <?php if (empty($ebay_status_partial_refund_id)) { $ebay_status_partial_refund_id = 2; } ?>

                    <?php foreach ($order_statuses as $status) { ?>
                    <?php echo'<option value="'.$status['order_status_id'].'"'.($ebay_status_partial_refund_id == $status['order_status_id'] ? ' selected=selected' :'').'>'.$status['name'].'</option>'; ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_status_refunded_id"><?php echo $text_import_refund_id; ?></label>
                <div class="col-sm-10">
                  <select name="ebay_status_refunded_id" id="ebay_status_refunded_id" class="form-control">
                    <?php if (empty($ebay_status_refunded_id)) { $ebay_status_refunded_id = 11; } ?>

                    <?php foreach ($order_statuses as $status) { ?>
                    <?php echo'<option value="'.$status['order_status_id'].'"'.($ebay_status_refunded_id == $status['order_status_id'] ? ' selected=selected' :'').'>'.$status['name'].'</option>'; ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </fieldset>

            <fieldset>
              <legend><?php echo $text_developer; ?></legend>
              <p><?php echo $text_developer_desc; ?></p>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="button-clear-data"><?php echo $text_developer_empty; ?></label>
                <div class="col-sm-10">
                  <a class="btn btn-primary" id="button-clear-data"><?php echo $text_clear; ?></a>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="button-clear-data"><?php echo $text_developer_locks; ?></label>
                <div class="col-sm-10">
                  <a class="btn btn-primary" id="button-clear-locks"><?php echo $text_clear; ?></a>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="button-repair-links"><?php echo $text_developer_repairlinks; ?></label>
                <div class="col-sm-10">
                  <a class="btn btn-primary" id="button-repair-links"><?php echo $text_update; ?></a>
                </div>
              </div>
            </fieldset>
          </div>

          <div class="tab-pane" id="tab-defaults">
            <fieldset>
              <legend><?php echo $text_legend_listing; ?></legend>
              <p><?php echo $text_setting_desc; ?></p>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_duration"><?php echo $text_openbay_duration; ?></label>
                <div class="col-sm-10">
                  <select name="ebay_duration" id="ebay_duration" class="form-control">
                    <?php foreach ($durations as $key => $duration) { ?>
                    <?php echo'<option value="'.$key.'"'.($key == $ebay_duration ? ' selected=selected' : '').'>'.$duration.'</option>'; ?>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $text_openbay_duration_help; ?></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_duration"><?php echo $text_openbay_measurement; ?></label>
                <div class="col-sm-10">
                  <select name="ebay_measurement" id="ebay_measurement" class="form-control">
                    <?php foreach ($measurement_types as $key => $type) { ?>
                    <?php echo'<option value="'.$key.'"'.($key == $ebay_measurement ? ' selected=selected' : '').'>'.$type.'</option>'; ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </fieldset>
            <fieldset>
              <legend><?php echo $text_legend_payments; ?></legend>
              <p><?php echo $text_payments_desc; ?></p>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_payment_instruction"><?php echo $text_payment_instruction; ?></label>
                <div class="col-sm-10">
                  <textarea name="ebay_payment_instruction" class="form-control" rows="3" id="ebay_payment_instruction"><?php echo $ebay_payment_instruction; ?></textarea>
                  <span class="help-block"><?php echo $text_payment_instruction_help; ?></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="ebay_payment_paypal_address"><?php echo $text_payment_paypal_add; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="ebay_payment_paypal_address" value="<?php echo $ebay_payment_paypal_address;?>" placeholder="<?php echo $text_payment_paypal_add; ?>" id="ebay_payment_paypal_address" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $text_payment_types; ?></label>
                <div class="col-sm-10">
                  <?php foreach($payment_options as $payment){ ?>
                  <div class="checkbox">
                    <label>
                      <input type="hidden" name="ebay_payment_types[<?php echo $payment['ebay_name']; ?>]" value="0" />
                      <input type="checkbox" name="ebay_payment_types[<?php echo $payment['ebay_name']; ?>]" value="1" <?php echo (isset($ebay_payment_types[(string)$payment['ebay_name']]) && $ebay_payment_types[(string)$payment['ebay_name']] == 1 ? 'checked="checked"' : ''); ?> />
                      <?php echo $payment['local_name']; ?>
                    </label>
                  </div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $text_payment_immediate; ?></label>
                <div class="col-sm-10">
                  <select name="ebay_payment_immediate" id="ebay_payment_immediate" class="form-control">
                    <?php if ($ebay_payment_immediate) { ?>
                      <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                      <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                      <option value="1"><?php echo $text_yes; ?></option>
                      <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $text_payment_immediate_help; ?></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $text_tax_listing; ?></label>
                <div class="col-sm-10">
                  <select name="ebay_tax_listing" id="ebay_tax_listing" class="form-control">
                    <?php if ($ebay_tax_listing) { ?>
                      <option value="1" selected="selected"><?php echo $text_tax_use_listing; ?></option>
                      <option value="0"><?php echo $text_tax_use_value; ?></option>
                    <?php } else { ?>
                      <option value="1"><?php echo $text_tax_use_listing; ?></option>
                      <option value="0" selected="selected"><?php echo $text_tax_use_value; ?></option>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $text_tax_listing_help; ?></span>
                </div>
              </div>
              <div class="form-group" id="ebay_tax_listing_preset">
                <label class="col-sm-2 control-label" for="tax"><?php echo $text_tax; ?></label>
                <div class="col-sm-10">
                  <div class="input-group col-xs-2">
                    <input type="text" name="tax" value="<?php echo $tax;?>" id="tax" class="form-control" />
                    <span class="input-group-addon">%</span>
                  </div>
                  <span class="help-block"><?php echo $text_tax_help; ?></span>
                </div>
              </div>
            </fieldset>
          </div>
        </div>
      </form>
  </div>
</div>
<script type="text/javascript"><!--
    $('#button-clear-data').bind('click', function() {
      var pass = prompt("<?php echo $text_ajax_dev_enter_pw; ?>", "");

      if (pass != '') {
        $.ajax({
          url: 'index.php?route=openbay/ebay/devClear&token=<?php echo $token; ?>',
          type: 'post',
          dataType: 'json',
          data: 'pass=' + pass,
          beforeSend: function() {
            $('#button-clear-data').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>');
          },
          success: function(json) {
              setTimeout(function() {
                alert(json.msg);
                $('#button-clear-data').empty().html('<?php echo $text_clear; ?>');
              }, 500);
          },
          error: function (xhr, ajaxOptions, thrownError) {
            if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
          }
        });
      } else {
        alert('<?php echo $text_ajax_dev_enter_warn; ?>');
        $('#button-clear-data').empty().html('<?php echo $text_clear; ?>');
      }
    });

    $('#button-clear-locks').bind('click', function() {
      $.ajax({
        url: 'index.php?route=openbay/ebay/deleteAllLocks&token=<?php echo $token; ?>',
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
          $('#button-clear-locks').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>');
        },
        success: function(json) {
          setTimeout(function() {
            alert(json.msg);
            $('#button-clear-locks').empty().html('<?php echo $text_clear; ?>');
          }, 500);
        },
        error: function (xhr, ajaxOptions, thrownError) {
          if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
          $('#button-clear-locks').empty().html('<?php echo $text_clear; ?>');
        }
      });
    });

    $('#button-repair-links').bind('click', function() {
      $.ajax({
        url: 'index.php?route=openbay/ebay/repairLinks&token=<?php echo $token; ?>',
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
          $('#button-repair-links').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>');
        },
        success: function(json) {
          setTimeout(function() {
            alert(json.msg);
            $('#button-repair-links').empty().html('<?php echo $text_update; ?>');
          }, 500);
        },
        error: function (xhr, ajaxOptions, thrownError) {
          if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
          $('#button-repair-links').empty().html('<?php echo $text_update; ?>');
        }
      });
    });

    function validateForm() {
        $('#form-ebay-settings').submit();
    }

    function checkCredentials() {
        $.ajax({
            url: 'index.php?route=openbay/ebay/verifyCreds&token=<?php echo $token; ?>',
            type: 'POST',
            dataType: 'json',
            data: {token: $('#ebay-token').val(), secret: $('#ebay-secret').val(), string1: $('#ebay-string1').val(), string2: $('#ebay-string2').val()},
            beforeSend: function() {
              $('#api-status').removeClass('label-success').removeClass('label-danger').addClass('label-primary').html('<i class="fa fa-cog fa-lg fa-spin"></i> Checking details').show();
            },
            success: function(data) {
                if (data.error == false) {
                    $('#api-status').removeClass('label-primary').addClass('label-success').html('<i class="fa fa-check-square-o"></i> <?php echo $text_api_ok; ?>: ' + data.data.expire);
                } else {
                    $('#api-status').removeClass('label-primary').addClass('label-danger').html('<i class="fa fa-minus-square"></i> ' + data.msg);
                }
            },
            failure: function() {
              $('#api-status').removeClass('label-primary').addClass('label-danger').html('<i class="fa fa-minus-square"></i> <?php echo $text_api_connect_fail; ?>');
            },
            error: function() {
              $('#api-status').removeClass('label-primary').addClass('label-danger').html('<i class="fa fa-minus-square"></i> <?php echo $text_api_connect_error; ?>');
            }
        });
    }

    function changeTaxHandler(){
        if ($('#ebay_tax_listing').val() == 1){
            $('#ebay_tax_listing_preset').hide();
        }else{
            $('#ebay_tax_listing_preset').show();
        }
    }

    $('.credentials').change(function() {
      checkCredentials();
    });

    $('#ebay_tax_listing').change(function() {
      changeTaxHandler();
    });

    $(document).ready(function() {
      checkCredentials();
      changeTaxHandler();
    });
//--></script>
<?php echo $footer; ?>