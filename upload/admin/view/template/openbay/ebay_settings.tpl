<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>

  <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
  <?php } ?>

  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <button type="submit" form="form-ebay-settings" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn" onclick="validateForm(); return false;"><i class="fa fa-check-circle"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn"><i class="fa fa-reply"></i></a>
      </div>
      <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $lang_heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-ebay-settings" class="form-horizontal">
        <input type="hidden" name="ebay_itm_link" value="<?php echo $ebay_itm_link; ?>" />

        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $lang_tab_token; ?></a></li>
          <li><a href="#tab-setup" data-toggle="tab"><?php echo $lang_tab_setup; ?></a></li>
          <li><a href="#tab-defaults" data-toggle="tab"><?php echo $lang_tab_defaults; ?></a></li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane active" id="tab-general">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="ebay_status"><?php echo $lang_status; ?></label>
              <div class="col-sm-10">
                <select name="ebay_status" id="ebay_status" class="form-control ftpsetting">
                  <?php if ($ebay_status) { ?>
                    <option value="1" selected="selected"><?php echo $lang_enabled; ?></option>
                    <option value="0"><?php echo $lang_disabled; ?></option>
                  <?php } else { ?>
                    <option value="1"><?php echo $lang_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $lang_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="ebay_token"><?php echo $lang_obp_token; ?></label>
              <div class="col-sm-10">
                <input type="text" name="ebay_token" value="<?php echo $lang_obp_token; ?>" placeholder="<?php echo $field_ftp_user; ?>" id="ebay_token" class="form-control credentials" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="ebay_token"><?php echo $lang_obp_secret; ?></label>
              <div class="col-sm-10">
                <input type="text" name="ebay_secret" value="<?php echo $ebay_secret; ?>" placeholder="<?php echo $lang_obp_secret; ?>" id="ebay_secret" class="form-control credentials" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="ebay_string1"><?php echo $lang_obp_string1; ?></label>
              <div class="col-sm-10">
                <input type="text" name="ebay_string1" value="<?php echo $ebay_string1; ?>" placeholder="<?php echo $lang_obp_string1; ?>" id="ebay_string1" class="form-control credentials" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="ebay_string2"><?php echo $lang_obp_string2; ?></label>
              <div class="col-sm-10">
                <input type="text" name="ebay_string2" value="<?php echo $ebay_string2; ?>" placeholder="<?php echo $lang_obp_string2; ?>" id="ebay_string2" class="form-control credentials" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $lang_api_status; ?></label>
              <div class="col-sm-10">
                <h4><span id="api-status" class="label" style="display:none;"></span></h4>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $lang_api_other; ?></label>
              <div class="col-sm-10">
                <h5><a href="https://account.openbaypro.com/ebay/apiRegister/" target="_BLANK"><span class="label label-default"><i class="fa fa-link"></i> <?php echo $lang_obp_token_register; ?></span></a></h5>
                <h5><a href="https://account.openbaypro.com/ebay/apiRenew/" target="_BLANK"><span class="label label-default"><i class="fa fa-link"></i> <?php echo $lang_obp_token_renew; ?></span></a></h5>
                <h5><a href="http://account.openbaypro.com/ebay/apiUpdate/" target="_BLANK"><span class="label label-default"><i class="fa fa-link"></i> <?php echo $lang_obp_detail_update; ?></span></a></h5>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-setup">
                    <h2><?php echo $lang_legend_app_settings; ?></h2>
                    <p><?php echo $lang_app_setting_msg; ?></p>
                    <table class="form">
                        <tr>
                            <td><?php echo $lang_app_end_ebay; ?></td>
                            <td>
                                <select name="ebay_enditems" style="width:200px;">
                                    <?php if ($ebay_enditems) { ?>
                                        <option value="1" selected="selected"><?php echo $lang_yes; ?></option>
                                        <option value="0"><?php echo $lang_no; ?></option>
                                    <?php } else { ?>
                                        <option value="1"><?php echo $lang_yes; ?></option>
                                        <option value="0" selected="selected"><?php echo $lang_no; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_app_relist_ebay; ?></td>
                            <td>
                                <select name="ebay_relistitems" style="width:200px;">
                                    <?php if ($ebay_relistitems) { ?>
                                        <option value="1" selected="selected"><?php echo $lang_yes; ?></option>
                                        <option value="0"><?php echo $lang_no; ?></option>
                                    <?php } else { ?>
                                        <option value="1"><?php echo $lang_yes; ?></option>
                                        <option value="0" selected="selected"><?php echo $lang_no; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_app_nostock_ebay; ?></td>
                            <td>
                                <select name="ebay_disable_nostock" style="width:200px;">
                                    <?php if ($ebay_disable_nostock) { ?>
                                        <option value="1" selected="selected"><?php echo $lang_yes; ?></option>
                                        <option value="0"><?php echo $lang_no; ?></option>
                                    <?php } else { ?>
                                        <option value="1"><?php echo $lang_yes; ?></option>
                                        <option value="0" selected="selected"><?php echo $lang_no; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_app_logging; ?></td>
                            <td>
                                <select name="ebay_logging" style="width:200px;">
                                    <?php if ($ebay_logging) { ?>
                                        <option value="1" selected="selected"><?php echo $lang_yes; ?></option>
                                        <option value="0"><?php echo $lang_no; ?></option>
                                    <?php } else { ?>
                                        <option value="1"><?php echo $lang_yes; ?></option>
                                        <option value="0" selected="selected"><?php echo $lang_no; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo $lang_app_currency; ?>
                                <span class="help"><?php echo $lang_app_currency_msg; ?></span>
                            </td>
                            <td>
                                <select name="ebay_def_currency" style="width:200px;">
                                    <?php
                                    foreach($currency_list as $currency){
                                        echo '<option value="'.$currency['code'].'"';
                                            if($ebay_def_currency == $currency['code']){ echo ' selected="selected"';}
                                        echo'>'.$currency['title'].'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_app_cust_grp; ?></td>
                            <td>
                                <select name="ebay_def_customer_grp" style="width:200px;">
                                    <?php
                                    foreach($customer_grp_list as $customer_grp){
                                        echo '<option value="'.$customer_grp['customer_group_id'].'"';
                                            if($ebay_def_customer_grp == $customer_grp['customer_group_id']){ echo ' selected="selected"';}
                                        echo'>'.$customer_grp['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_app_stock_allocate; ?></td>
                            <td>
                                <select name="ebay_stock_allocate" style="width:200px;">
                                    <?php if ($ebay_stock_allocate) { ?>
                                        <option value="1" selected="selected"><?php echo $lang_app_stock_2; ?></option>
                                        <option value="0"><?php echo $lang_app_stock_1; ?></option>
                                    <?php } else { ?>
                                        <option value="1"><?php echo $lang_app_stock_2; ?></option>
                                        <option value="0" selected="selected"><?php echo $lang_app_stock_1; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="ebay_created_hours"><?php echo $lang_created_hours; ?></td>
                            <td><input type="text" name="ebay_created_hours" id="ebay_created_hours" style="width:30px;" maxlength="" value="<?php echo $ebay_created_hours;?>" class="credentials" /></td>
                        </tr>

                        <tr>
                            <td><?php echo $lang_create_date; ?></td>
                            <td>
                                <select name="ebay_create_date" style="width:200px;">
                                    <?php if ($ebay_create_date) { ?>
                                        <option value="1" selected="selected"><?php echo $lang_create_date_1; ?></option>
                                        <option value="0"><?php echo $lang_create_date_0; ?></option>
                                    <?php } else { ?>
                                        <option value="1"><?php echo $lang_create_date_1; ?></option>
                                        <option value="0" selected="selected"><?php echo $lang_create_date_0; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><?php echo $lang_timezone_offset; ?></td>
                            <td>
                                <select name="ebay_time_offset" style="width:200px;">
                                    <option value="-12"<?php if($ebay_time_offset == '-12'){ echo ' selected';} ?>>-12</option>
                                    <option value="-11"<?php if($ebay_time_offset == '-11'){ echo ' selected';} ?>>-11</option>
                                    <option value="-10"<?php if($ebay_time_offset == '-10'){ echo ' selected';} ?>>-10</option>
                                    <option value="-9"<?php if($ebay_time_offset == '-9'){ echo ' selected';} ?>>-9</option>
                                    <option value="-8"<?php if($ebay_time_offset == '-8'){ echo ' selected';} ?>>-8</option>
                                    <option value="-7"<?php if($ebay_time_offset == '-7'){ echo ' selected';} ?>>-7</option>
                                    <option value="-6"<?php if($ebay_time_offset == '-6'){ echo ' selected';} ?>>-6</option>
                                    <option value="-5"<?php if($ebay_time_offset == '-5'){ echo ' selected';} ?>>-5</option>
                                    <option value="-4"<?php if($ebay_time_offset == '-4'){ echo ' selected';} ?>>-4</option>
                                    <option value="-3"<?php if($ebay_time_offset == '-3'){ echo ' selected';} ?>>-3</option>
                                    <option value="-2"<?php if($ebay_time_offset == '-2'){ echo ' selected';} ?>>-2</option>
                                    <option value="-1"<?php if($ebay_time_offset == '-1'){ echo ' selected';} ?>>-1</option>
                                    <option value="0"<?php if($ebay_time_offset == '0'){ echo ' selected';} ?>>0</option>
                                    <option value="+1"<?php if($ebay_time_offset == '+1'){ echo ' selected';} ?>>+1</option>
                                    <option value="+2"<?php if($ebay_time_offset == '+2'){ echo ' selected';} ?>>+2</option>
                                    <option value="+3"<?php if($ebay_time_offset == '+3'){ echo ' selected';} ?>>+3</option>
                                    <option value="+4"<?php if($ebay_time_offset == '+4'){ echo ' selected';} ?>>+4</option>
                                    <option value="+5"<?php if($ebay_time_offset == '+5'){ echo ' selected';} ?>>+5</option>
                                    <option value="+6"<?php if($ebay_time_offset == '+6'){ echo ' selected';} ?>>+6</option>
                                    <option value="+7"<?php if($ebay_time_offset == '+7'){ echo ' selected';} ?>>+7</option>
                                    <option value="+8"<?php if($ebay_time_offset == '+8'){ echo ' selected';} ?>>+8</option>
                                    <option value="+9"<?php if($ebay_time_offset == '+9'){ echo ' selected';} ?>>+9</option>
                                    <option value="+10"<?php if($ebay_time_offset == '+10'){ echo ' selected';} ?>>+10</option>
                                    <option value="+11"<?php if($ebay_time_offset == '+11'){ echo ' selected';} ?>>+11</option>
                                    <option value="+12"<?php if($ebay_time_offset == '+12'){ echo ' selected';} ?>>+12</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><?php echo $lang_address_format; ?></td>
                            <td><textarea name="ebay_default_addressformat" style="height:150px; width:200px;"><?php echo $ebay_default_addressformat; ?></textarea></td>
                        </tr>
                    </table>
                    <br /><br />
                    <h2><?php echo $lang_legend_notify_settings; ?></h2>
                    <p><?php echo $lang_notify_setting_msg; ?></p>
                    <table class="form">
                        <tr>
                            <td><?php echo $lang_ebay_update_notify; ?></td>
                            <td>
                                <select name="ebay_update_notify" style="width:200px;">
                                    <?php if ($ebay_update_notify) { ?>
                                        <option value="1" selected="selected"><?php echo $lang_yes; ?></option>
                                        <option value="0"><?php echo $lang_no; ?></option>
                                    <?php } else { ?>
                                        <option value="1"><?php echo $lang_yes; ?></option>
                                        <option value="0" selected="selected"><?php echo $lang_no; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_ebay_confirm_notify; ?></td>
                            <td>
                                <select name="ebay_confirm_notify" style="width:200px;">
                                    <?php if ($ebay_confirm_notify) { ?>
                                        <option value="1" selected="selected"><?php echo $lang_yes; ?></option>
                                        <option value="0"><?php echo $lang_no; ?></option>
                                    <?php } else { ?>
                                        <option value="1"><?php echo $lang_yes; ?></option>
                                        <option value="0" selected="selected"><?php echo $lang_no; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_ebay_confirmadmin_notify; ?></td>
                            <td>
                                <select name="ebay_confirmadmin_notify" style="width:200px;">
                                    <?php if ($ebay_confirmadmin_notify) { ?>
                                        <option value="1" selected="selected"><?php echo $lang_yes; ?></option>
                                        <option value="0"><?php echo $lang_no; ?></option>
                                    <?php } else { ?>
                                        <option value="1"><?php echo $lang_yes; ?></option>
                                        <option value="0" selected="selected"><?php echo $lang_no; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_openbaypro_brand_disable; ?></td>
                            <td>
                                <select name="ebay_email_brand_disable" style="width:200px;">
                                    <?php if ($ebay_email_brand_disable) { ?>
                                        <option value="1" selected="selected"><?php echo $lang_yes; ?></option>
                                        <option value="0"><?php echo $lang_no; ?></option>
                                    <?php } else { ?>
                                        <option value="1"><?php echo $lang_yes; ?></option>
                                        <option value="0" selected="selected"><?php echo $lang_no; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <br /><br />

                    <h2><?php echo $lang_legend_default_import; ?></h2>
                    <table class="form">
                        <tr>
                            <td><label for="ebay_import_unpaid"><?php echo $lang_import_pending; ?></td>
                            <td>
                                <input type="hidden" name="ebay_import_unpaid" value="0" />
                                <input type="checkbox" name="ebay_import_unpaid" value="1" <?php if($ebay_import_unpaid == 1){ echo 'checked="checked"'; } ?> />
                            </td>
                        </tr>
                        <tr>
                            <td><label for="ebay_status_import_id"><?php echo $lang_import_def_id; ?></td>
                            <td>
                                <select name="ebay_status_import_id" style="width:200px;">
                                    <?php
                                    if (empty($ebay_status_import_id)) { $ebay_status_import_id = 1; }

                                    foreach($order_statuses as $status){
                                        echo'<option value="'.$status['order_status_id'].'"';
                                        if($ebay_status_import_id == $status['order_status_id']){echo ' selected=selected';}
                                        echo'>'.$status['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="ebay_status_paid_id"><?php echo $lang_import_paid_id; ?></td>
                            <td>
                                <select name="ebay_status_paid_id" style="width:200px;">
                                    <?php
                                    if (empty($ebay_status_paid_id)) { $ebay_status_paid_id = 2; }

                                    foreach($order_statuses as $status){
                                        echo'<option value="'.$status['order_status_id'].'"';
                                            if($ebay_status_paid_id == $status['order_status_id']){echo ' selected=selected';}
                                        echo'>'.$status['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="ebay_status_shipped_id"><?php echo $lang_import_shipped_id; ?></td>
                            <td>
                                <select name="ebay_status_shipped_id" style="width:200px;">
                                    <?php
                                    if (empty($ebay_status_shipped_id)) { $ebay_status_shipped_id = 3; }

                                    foreach($order_statuses as $status){
                                        echo'<option value="'.$status['order_status_id'].'"';
                                        if($ebay_status_shipped_id == $status['order_status_id']){echo ' selected=selected';}
                                        echo'>'.$status['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="ebay_status_cancelled_id"><?php echo $lang_import_cancelled_id; ?></td>
                            <td>
                                <select name="ebay_status_cancelled_id" style="width:200px;">
                                    <?php
                                    if (empty($ebay_status_cancelled_id)) { $ebay_status_cancelled_id = 7; }

                                    foreach($order_statuses as $status){
                                        echo'<option value="'.$status['order_status_id'].'"';
                                        if($ebay_status_cancelled_id == $status['order_status_id']){echo ' selected=selected';}
                                        echo'>'.$status['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="ebay_status_partial_refund_id"><?php echo $lang_import_part_refund_id; ?></td>
                            <td>
                                <select name="ebay_status_partial_refund_id" style="width:200px;">
                                    <?php
                                    if (empty($ebay_status_partial_refund_id)) { $ebay_status_partial_refund_id = 2; }

                                    foreach($order_statuses as $status){
                                        echo'<option value="'.$status['order_status_id'].'"';
                                        if($ebay_status_partial_refund_id == $status['order_status_id']){echo ' selected=selected';}
                                        echo'>'.$status['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="ebay_status_refunded_id"><?php echo $lang_import_refund_id; ?></td>
                            <td>
                                <select name="ebay_status_refunded_id" style="width:200px;">
                                    <?php
                                    if (empty($ebay_status_refunded_id)) { $ebay_status_refunded_id = 11; }

                                    foreach($order_statuses as $status){
                                        echo'<option value="'.$status['order_status_id'].'"';
                                        if($ebay_status_refunded_id == $status['order_status_id']){echo ' selected=selected';}
                                        echo'>'.$status['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </table>

                    <br /><br />
                    <h2><?php echo $lang_developer; ?></h2>
                    <p><?php echo $lang_developer_desc; ?></p>
                    <table class="form">
                      <tr>
                        <td><label><?php echo $lang_developer_empty; ?></td>
                        <td>
                          <a onclick="devClearData();" class="button" id="devClearData"><span><?php echo $lang_clear; ?></span></a>
                          <img src="<?php echo HTTPS_SERVER; ?>view/image/loading.gif" id="imageDevClearData" class="displayNone"/>
                        </td>
                      </tr>
                      <tr>
                        <td><label><?php echo $lang_developer_locks; ?></td>
                        <td>
                          <a onclick="removeLocks();" class="button" id="removeLocks"><span><?php echo $lang_clear; ?></span></a>
                          <img src="<?php echo HTTPS_SERVER; ?>view/image/loading.gif" id="imageRemoveLocks" class="displayNone"/>
                        </td>
                      </tr>
                      <tr>
                        <td><label><?php echo $lang_developer_repairlinks; ?></td>
                        <td>
                          <a onclick="repairLinks();" class="button" id="repairLinks"><span><?php echo $lang_update; ?></span></a>
                          <img src="<?php echo HTTPS_SERVER; ?>view/image/loading.gif" id="imageRepairLinks" class="displayNone"/>
                        </td>
                      </tr>
                    </table>
                </div>
          <div class="tab-pane" id="tab-defaults">
            <p><?php echo $lang_setting_desc; ?></p>

            <div class="form-group">
              <label class="col-sm-2 control-label" for="ebay_duration"><?php echo $lang_openbay_duration; ?></label>
              <div class="col-sm-10">
                <select name="ebay_duration" id="ebay_duration" class="form-control">
                  <?php foreach ($durations as $key => $duration) { ?>
                    <?php echo'<option value="'.$key.'"'.($key == $ebay_duration ? ' selected=selected' : '').'>'.$duration.'</option>'; ?>
                  <?php } ?>
                </select>
                <span class="help-block"><?php echo $lang_openbay_duration_help; ?></span>
              </div>
            </div>
            <h5><?php echo $lang_legend_payments; ?></h5>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="ebay_payment_instruction"><?php echo $lang_payment_instruction; ?></label>
              <div class="col-sm-10">
                <textarea name="ebay_payment_instruction" class="form-control" rows="3" id="ebay_payment_instruction"><?php echo $ebay_payment_instruction; ?></textarea>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="ebay_payment_paypal_address"><?php echo $lang_payment_paypal_add; ?></label>
              <div class="col-sm-10">
                <input type="text" name="ebay_payment_paypal_address" value="<?php echo $ebay_payment_paypal_address;?>" placeholder="<?php echo $lang_payment_paypal_add; ?>" id="ebay_payment_paypal_address" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $lang_payment_types; ?></label>
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
              <label class="col-sm-2 control-label"><?php echo $lang_payment_imediate; ?></label>
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
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $lang_tax_listing; ?></label>
              <div class="col-sm-10">
                <select name="ebay_tax_listing" id="ebay_tax_listing" class="form-control">
                  <?php if ($ebay_tax_listing) { ?>
                    <option value="1" selected="selected"><?php echo $lang_tax_use_listing; ?></option>
                    <option value="0"><?php echo $lang_tax_use_value; ?></option>
                  <?php } else { ?>
                    <option value="1"><?php echo $lang_tax_use_listing; ?></option>
                    <option value="0" selected="selected"><?php echo $lang_tax_use_value; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group" id="ebay_tax_listing_preset">
              <label class="col-sm-2 control-label" for="tax"><?php echo $lang_tax; ?></label>
              <div class="col-sm-10">
                <div class="input-group">
                  <input type="text" name="tax" value="<?php echo $tax;?>" id="tax" class="form-control" />
                  <span class="input-group-addon">%</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
    function devClearData() {
        var pass = prompt("<?php echo $lang_ajax_dev_enter_pw; ?>", "");

        if (pass != '') {
            $.ajax({
                url: 'index.php?route=openbay/ebay/devClear&token=<?php echo $token; ?>',
                type: 'post',
                dataType: 'json',
                data: 'pass=' + pass,
                beforeSend: function() {
                    $('#devClearData').hide();
                    $('#imageDevClearData').show();
                },
                success: function(json) {
                    setTimeout(function() {
                        alert(json.msg);
                        $('#imageDevClearData').hide();
                        $('#devClearData').show();
                    }, 500);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        } else {
            alert('<?php echo $lang_ajax_dev_enter_warn; ?>');
            $('#imageDevClearData').hide();
            $('#devClearData').show();
        }
    }

    function repairLinks() {
      $.ajax({
        url: 'index.php?route=openbay/ebay/repairLinks&token=<?php echo $token; ?>',
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
          $('#repairLinks').hide();
          $('#imageRepairLinks').show();
        },
        success: function(json) {
          setTimeout(function() {
            alert(json.msg);
            $('#imageRepairLinks').hide();
            $('#repairLinks').show();
          }, 500);
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }

    function removeLocks() {
      $.ajax({
        url: 'index.php?route=openbay/ebay/deleteAllLocks&token=<?php echo $token; ?>',
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
          $('#removeLocks').hide();
          $('#imageRemoveLocks').show();
        },
        success: function(json) {
          setTimeout(function() {
            alert(json.msg);
            $('#removeLocks').show();
            $('#imageRemoveLocks').hide();
          }, 500);
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }

    function validateForm() {
        $('#form-ebay-settings').submit();
    }

    function checkCredentials() {
        $.ajax({
            url: 'index.php?route=openbay/ebay/verifyCreds&token=<?php echo $token; ?>',
            type: 'POST',
            dataType: 'json',
            data: {token: $('#ebay_token').val(), secret: $('#ebay_secret').val(), string1: $('#ebay_string1').val(), string2: $('#ebay_string2').val()},
            beforeSend: function() {
              $('#api-status').removeClass('label-success').removeClass('label-danger').addClass('label-primary').html('<i class="fa fa-refresh fa-spin"></i> Checking details').show();
            },
            success: function(data) {
                if (data.error == false) {
                    $('#api-status').removeClass('label-primary').addClass('label-success').html('<i class="fa fa-check-square-o"></i> <?php echo $lang_api_ok; ?>: ' + data.data.expire);
                } else {
                    $('#api-status').removeClass('label-primary').addClass('label-danger').html('<i class="fa fa-minus-square"></i> ' + data.msg);
                }
            },
            failure: function() {
              $('#api-status').removeClass('label-primary').addClass('label-danger').html('<i class="fa fa-minus-square"></i> <?php echo $lang_api_connect_fail; ?>');
            },
            error: function() {
              $('#api-status').removeClass('label-primary').addClass('label-danger').html('<i class="fa fa-minus-square"></i> <?php echo $lang_api_connect_error; ?>');
            }
        });
    }

    function changeTaxHandler(){
        if($('#ebay_tax_listing').val() == 1){
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