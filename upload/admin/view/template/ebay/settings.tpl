<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>

    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>

    <div class="box" style="margin-bottom:130px;">

        <div class="heading">
            <h1><?php echo $lang_heading_title; ?></h1>
            <div class="buttons"><a onclick="validateForm();
                    return false;" class="button"><span><?php echo $lang_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $lang_cancel; ?></span></a></div>
        </div>
        <div class="content">
            <div id="tabs" class="htabs">
                <a href="#tab-general"><?php echo $lang_tab_token; ?></a>
                <a href="#tab-setup"><?php echo $lang_tab_setup; ?></a>
                <a href="#tab-defaults"><?php echo $lang_tab_defaults; ?></a>
            </div>

            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <input type="hidden" name="openbaypro_ebay_itm_link" value="<?php echo $openbaypro_ebay_itm_link; ?>" />
                <div id="tab-general">
                    <h2><?php echo $lang_legend_api; ?></h2>

                    <table class="form">
                        <tr>
                            <td><p><?php echo $lang_status; ?></p></td>
                            <td><p><select name="openbay_status" style="width:200px;">
                                        <?php if ($openbay_status) { ?>
                                        <option value="1" selected="selected"><?php echo $lang_enabled; ?></option>
                                        <option value="0"><?php echo $lang_disabled; ?></option>
                                        <?php } else { ?>
                                        <option value="1"><?php echo $lang_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $lang_disabled; ?></option>
                                        <?php } ?>
                                    </select></p></td>
                        </tr>

                        <tr>
                            <td><p><label for="openbaypro_token"><?php echo $lang_obp_token; ?></p></td>
                            <td>
                                <p><img src="<?php echo HTTPS_SERVER; ?>view/image/lock.png" style="margin-bottom:-3px;" />&nbsp;<a href="https://uk.openbaypro.com/account/live/register.php" target="_BLANK"><?php echo $lang_obp_token_register; ?></a></p>
                                <p><img src="<?php echo HTTPS_SERVER; ?>view/image/lock-open.png" style="margin-bottom:-3px;" />&nbsp;<a href="https://uk.openbaypro.com/account/live/renew.php" target="_BLANK"><?php echo $lang_obp_token_renew; ?></a></p>
                                <p><img src="<?php echo HTTPS_SERVER; ?>view/image/lock-open.png" style="margin-bottom:-3px;" />&nbsp;<a href="https://uk.openbaypro.com/account/live/update.php" target="_BLANK"><?php echo $lang_obp_detail_update; ?></a></p>
                                <p><input type="text" name="openbaypro_token" id="openbaypro_token" style="width:300px;" maxlength="" value="<?php echo $openbaypro_token;?>" class="credentials" /></p>
                            </td>
                        </tr>

                        <tr>
                            <td><p><label for="openbaypro_secret"><?php echo $lang_obp_secret; ?></p></td>
                            <td><p><input type="text" name="openbaypro_secret" id="openbaypro_secret" style="width:300px;" maxlength="" value="<?php echo $openbaypro_secret;?>" class="credentials" /></p></td>
                        </tr>

                        <tr>
                            <td><p><label for="openbaypro_string1"><?php echo $lang_obp_string1; ?></p></td>
                            <td><p><input type="text" name="openbaypro_string1" id="openbaypro_string1" style="width:300px;" maxlength="" value="<?php echo $openbaypro_string1;?>" class="credentials" /></p></td>
                        </tr>

                        <tr>
                            <td><p><label for="openbaypro_string2"><?php echo $lang_obp_string2; ?></p></td>
                            <td><p><input type="text" name="openbaypro_string2" id="openbaypro_string2" style="width:300px;" maxlength="" value="<?php echo $openbaypro_string2;?>" class="credentials" /></p></td>
                        </tr>

                        <tr>
                            <td><p><?php echo $lang_api_status; ?></p></td>
                            <td id="apiConnection" style="padding:5px;">
                                <img style="display:inline; position:relative; top:3px;" src="view/image/loading.gif" alt="" title="" /> <?php echo $lang_api_checking; ?>
                            </td>
                        </tr>

                    </table>
                </div>

                <div id="tab-setup">
                    <h2><?php echo $lang_legend_app_settings; ?></h2>
                    <p><?php echo $lang_app_setting_msg; ?></p>
                    <table class="form">
                        <tr>
                            <td><?php echo $lang_app_end_ebay; ?></td>
                            <td>
                                <select name="openbaypro_enditems" style="width:200px;">
                                    <?php if ($openbaypro_enditems) { ?>
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
                                <select name="openbaypro_relistitems" style="width:200px;">
                                    <?php if ($openbaypro_relistitems) { ?>
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
                                <select name="openbaypro_logging" style="width:200px;">
                                    <?php if ($openbaypro_logging) { ?>
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
                                <select name="openbay_def_currency" style="width:200px;">
                                    <?php
                                    foreach($currency_list as $currency){
                                    echo '<option value="'.$currency['code'].'"';
                                    if($openbay_def_currency == $currency['code']){ echo ' selected="selected"';}
                                    echo'>'.$currency['title'].'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_app_cust_grp; ?></td>
                            <td>
                                <select name="openbay_def_customer_grp" style="width:200px;">
                                    <?php
                                    foreach($customer_grp_list as $customer_grp){
                                    echo '<option value="'.$customer_grp['customer_group_id'].'"';
                                    if($openbay_def_customer_grp == $customer_grp['customer_group_id']){ echo ' selected="selected"';}
                                    echo'>'.$customer_grp['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_app_stock_allocate; ?></td>
                            <td>
                                <select name="openbaypro_stock_allocate" style="width:200px;">
                                    <?php if ($openbaypro_stock_allocate) { ?>
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
                            <td><label for="openbaypro_created_hours"><?php echo $lang_created_hours; ?></td>
                            <td><input type="text" name="openbaypro_created_hours" id="openbaypro_created_hours" style="width:30px;" maxlength="" value="<?php echo $openbaypro_created_hours;?>" class="credentials" /></td>
                        </tr>

                        <tr>
                            <td><?php echo $lang_create_date; ?></td>
                            <td>
                                <select name="openbaypro_create_date" style="width:200px;">
                                    <?php if ($openbaypro_create_date) { ?>
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
                                <select name="openbaypro_time_offset" style="width:200px;">
                                    <option value="-12"<?php if($openbaypro_time_offset == '-12'){ echo ' selected';} ?>>-12</option>
                                    <option value="-11"<?php if($openbaypro_time_offset == '-11'){ echo ' selected';} ?>>-11</option>
                                    <option value="-10"<?php if($openbaypro_time_offset == '-10'){ echo ' selected';} ?>>-10</option>
                                    <option value="-9"<?php if($openbaypro_time_offset == '-9'){ echo ' selected';} ?>>-9</option>
                                    <option value="-8"<?php if($openbaypro_time_offset == '-8'){ echo ' selected';} ?>>-8</option>
                                    <option value="-7"<?php if($openbaypro_time_offset == '-7'){ echo ' selected';} ?>>-7</option>
                                    <option value="-6"<?php if($openbaypro_time_offset == '-6'){ echo ' selected';} ?>>-6</option>
                                    <option value="-5"<?php if($openbaypro_time_offset == '-5'){ echo ' selected';} ?>>-5</option>
                                    <option value="-4"<?php if($openbaypro_time_offset == '-4'){ echo ' selected';} ?>>-4</option>
                                    <option value="-3"<?php if($openbaypro_time_offset == '-3'){ echo ' selected';} ?>>-3</option>
                                    <option value="-2"<?php if($openbaypro_time_offset == '-2'){ echo ' selected';} ?>>-2</option>
                                    <option value="-1"<?php if($openbaypro_time_offset == '-1'){ echo ' selected';} ?>>-1</option>
                                    <option value="0"<?php if($openbaypro_time_offset == '0'){ echo ' selected';} ?>>0</option>
                                    <option value="+1"<?php if($openbaypro_time_offset == '+1'){ echo ' selected';} ?>>+1</option>
                                    <option value="+2"<?php if($openbaypro_time_offset == '+2'){ echo ' selected';} ?>>+2</option>
                                    <option value="+3"<?php if($openbaypro_time_offset == '+3'){ echo ' selected';} ?>>+3</option>
                                    <option value="+4"<?php if($openbaypro_time_offset == '+4'){ echo ' selected';} ?>>+4</option>
                                    <option value="+5"<?php if($openbaypro_time_offset == '+5'){ echo ' selected';} ?>>+5</option>
                                    <option value="+6"<?php if($openbaypro_time_offset == '+6'){ echo ' selected';} ?>>+6</option>
                                    <option value="+7"<?php if($openbaypro_time_offset == '+7'){ echo ' selected';} ?>>+7</option>
                                    <option value="+8"<?php if($openbaypro_time_offset == '+8'){ echo ' selected';} ?>>+8</option>
                                    <option value="+9"<?php if($openbaypro_time_offset == '+9'){ echo ' selected';} ?>>+9</option>
                                    <option value="+10"<?php if($openbaypro_time_offset == '+10'){ echo ' selected';} ?>>+10</option>
                                    <option value="+11"<?php if($openbaypro_time_offset == '+11'){ echo ' selected';} ?>>+11</option>
                                    <option value="+12"<?php if($openbaypro_time_offset == '+12'){ echo ' selected';} ?>>+12</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><?php echo $lang_address_format; ?></td>
                            <td><textarea name="openbay_default_addressformat" style="height:150px; width:200px;"><?php echo $openbay_default_addressformat; ?></textarea></td>
                        </tr>
                    </table>
                    <br /><br />
                    <h2><?php echo $lang_legend_notify_settings; ?></h2>
                    <p><?php echo $lang_notify_setting_msg; ?></p>
                    <table class="form">
                        <tr>
                            <td><?php echo $lang_openbaypro_update_notify; ?></td>
                            <td>
                                <select name="openbaypro_update_notify" style="width:200px;">
                                    <?php if ($openbaypro_update_notify) { ?>
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
                            <td><?php echo $lang_openbaypro_confirm_notify; ?></td>
                            <td>
                                <select name="openbaypro_confirm_notify" style="width:200px;">
                                    <?php if ($openbaypro_confirm_notify) { ?>
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
                            <td><?php echo $lang_openbaypro_confirmadmin_notify; ?></td>
                            <td>
                                <select name="openbaypro_confirmadmin_notify" style="width:200px;">
                                    <?php if ($openbaypro_confirmadmin_notify) { ?>
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
                                <select name="openbaypro_email_brand_disable" style="width:200px;">
                                    <?php if ($openbaypro_email_brand_disable) { ?>
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

                    <h2><?php echo $lang_legend_stock_rep; ?></h2>
                    <p><?php echo $lang_desc_stock_rep; ?></p>
                    <table class="form">
                        <tr>
                            <td><?php echo $lang_stock_reports; ?></td>
                            <td>
                                <select name="openbaypro_stock_report" style="width:200px;">
                                    <?php if ($openbaypro_stock_report) { ?>
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
                            <td><?php echo $lang_stock_summary; ?></td>
                            <td>
                                <select name="openbaypro_stock_report_summary" style="width:200px;">
                                    <?php if ($openbaypro_stock_report_summary) { ?>
                                    <option value="1" selected="selected"><?php echo $lang_yes; ?></option>
                                    <option value="0"><?php echo $lang_no; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $lang_yes; ?></option>
                                    <option value="0" selected="selected"><?php echo $lang_no; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                    </table><br /><br />
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
                            <td><label for="EBAY_DEF_IMPORT_ID"><?php echo $lang_import_def_id; ?></td>
                            <td>
                                <select name="EBAY_DEF_IMPORT_ID" style="width:200px;">
                                    <?php
                                    if (empty($EBAY_DEF_IMPORT_ID)) { $EBAY_DEF_IMPORT_ID = 1; }

                                    foreach($order_statuses as $status){
                                        echo'<option value="'.$status['order_status_id'].'"';
                                        if($EBAY_DEF_IMPORT_ID == $status['order_status_id']){echo ' selected=selected';}
                                        echo'>'.$status['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="EBAY_DEF_PAID_ID"><?php echo $lang_import_paid_id; ?></td>
                            <td>
                                <select name="EBAY_DEF_PAID_ID" style="width:200px;">
                                    <?php
                                    if (empty($EBAY_DEF_PAID_ID)) { $EBAY_DEF_PAID_ID = 2; }

                                    foreach($order_statuses as $status){
                                        echo'<option value="'.$status['order_status_id'].'"';
                                        if($EBAY_DEF_PAID_ID == $status['order_status_id']){echo ' selected=selected';}
                                        echo'>'.$status['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="EBAY_DEF_SHIPPED_ID"><?php echo $lang_import_shipped_id; ?></td>
                            <td>
                                <select name="EBAY_DEF_SHIPPED_ID" style="width:200px;">
                                    <?php
                                    if (empty($EBAY_DEF_SHIPPED_ID)) { $EBAY_DEF_SHIPPED_ID = 3; }

                                    foreach($order_statuses as $status){
                                        echo'<option value="'.$status['order_status_id'].'"';
                                        if($EBAY_DEF_SHIPPED_ID == $status['order_status_id']){echo ' selected=selected';}
                                        echo'>'.$status['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="EBAY_DEF_CANCELLED_ID"><?php echo $lang_import_cancelled_id; ?></td>
                            <td>
                                <select name="EBAY_DEF_CANCELLED_ID" style="width:200px;">
                                    <?php
                                    if (empty($EBAY_DEF_CANCELLED_ID)) { $EBAY_DEF_CANCELLED_ID = 7; }

                                    foreach($order_statuses as $status){
                                        echo'<option value="'.$status['order_status_id'].'"';
                                        if($EBAY_DEF_CANCELLED_ID == $status['order_status_id']){echo ' selected=selected';}
                                        echo'>'.$status['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="EBAY_DEF_PARTIAL_REFUND_ID"><?php echo $lang_import_part_refund_id; ?></td>
                            <td>
                                <select name="EBAY_DEF_PARTIAL_REFUND_ID" style="width:200px;">
                                    <?php
                                    if (empty($EBAY_DEF_PARTIAL_REFUND_ID)) { $EBAY_DEF_PARTIAL_REFUND_ID = 2; }

                                    foreach($order_statuses as $status){
                                        echo'<option value="'.$status['order_status_id'].'"';
                                        if($EBAY_DEF_PARTIAL_REFUND_ID == $status['order_status_id']){echo ' selected=selected';}
                                        echo'>'.$status['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="EBAY_DEF_REFUNDED_ID"><?php echo $lang_import_refund_id; ?></td>
                            <td>
                                <select name="EBAY_DEF_REFUNDED_ID" style="width:200px;">
                                    <?php
                                    if (empty($EBAY_DEF_REFUNDED_ID)) { $EBAY_DEF_REFUNDED_ID = 11; }

                                    foreach($order_statuses as $status){
                                        echo'<option value="'.$status['order_status_id'].'"';
                                        if($EBAY_DEF_REFUNDED_ID == $status['order_status_id']){echo ' selected=selected';}
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
                            <td><label><?php echo $lang_developer_repairlinks; ?></td>
                            <td>
                                <a onclick="repairLinks();" class="button" id="repairLinks"><span><?php echo $lang_update; ?></span></a>
                                <img src="<?php echo HTTPS_SERVER; ?>view/image/loading.gif" id="imageRepairLinks" class="displayNone"/>
                            </td>
                        </tr>

                    </table>
                </div>

                <div id="tab-defaults">
                    <p><?php echo $lang_setting_desc; ?></p>

                    <table class="form">
                        <tr>
                            <td><label><?php echo $lang_openbay_duration; ?></td>
                            <td>
                                <select name="openbaypro_duration" style="width:200px;">
                                    <?php
                                    foreach($durations as $key => $duration){
                                        echo'<option value="'.$key.'"';
                                        if($key == $openbaypro_duration){echo ' selected=selected';}
                                        echo'>'.$duration.'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

                    </table>

                    <br /><br />
                    <h2><?php echo $lang_legend_payments; ?></h2>

                    <table class="form">
                        <tr>
                            <td><label for="field_payment_instruction"><?php echo $lang_payment_instruction; ?></td>
                            <td><textarea name="field_payment_instruction" id="field_payment_instruction" maxlength="" style="width:400px; height:70px;"><?php echo $field_payment_instruction;?></textarea></td>
                        </tr>

                        <tr class="row3">
                            <td><label for="field_payment_paypal_address"><?php echo $lang_payment_paypal_add; ?></td>
                            <td><input type="text" name="field_payment_paypal_address" id="field_payment_paypal_address" style="width:300px" maxlength="" value="<?php echo $field_payment_paypal_address;?>" /></td>
                        </tr>

                        <?php foreach($payment_options as $payment){ ?>
                        <tr class="row4">
                            <td><label><?php echo $payment['local_name']; ?></td>
                            <td>
                                <input type="hidden" name="ebay_payment_types[<?php echo $payment['ebay_name']; ?>]" value="0" />
                                <input type="checkbox" name="ebay_payment_types[<?php echo $payment['ebay_name']; ?>]" value="1" <?php if(isset($ebay_payment_types[(string)$payment['ebay_name']]) && $ebay_payment_types[(string)$payment['ebay_name']] == 1) { echo 'checked="checked"'; } ?> />
                            </td>
                        </tr>
                        <?php } ?>

                        <tr class="row4">
                            <td><label><?php echo $lang_payment_imediate; ?></td>
                            <td>
                                <input type="hidden" name="payment_immediate" value="0" />
                                <input type="checkbox" name="payment_immediate" value="1" <?php if ($payment_immediate) { echo 'checked="checked"'; } ?> />
                            </td>
                        </tr>

                        <tr class="row6">
                            <td><label><?php echo $lang_tax_listing; ?></td>
                            <td>
                                <select name="ebay_tax_listing" id="ebay_tax_listing" style="width:200px;">
                                    <?php if ($ebay_tax_listing) { ?>
                                    <option value="1" selected="selected"><?php echo $lang_tax_use_listing; ?></option>
                                    <option value="0"><?php echo $lang_tax_use_value; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $lang_tax_use_listing; ?></option>
                                    <option value="0" selected="selected"><?php echo $lang_tax_use_value; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>

                        <tr class="row6" id="ebay_tax_listing_preset">
                            <td><label><?php echo $lang_tax; ?></td>
                            <td><input type="text" name="tax" id="tax" style="width:30px;" maxlength="" value="<?php echo $tax;?>" /> %</td>
                        </tr>

                    </table>
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
                url: 'index.php?route=openbay/openbay/devClear&token=<?php echo $token; ?>',
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
                url: 'index.php?route=openbay/openbay/repairLinks&token=<?php echo $token; ?>',
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
                }
            });
    }

    function validateForm() {
        $('#form').submit();
    }

    function checkCredentials() {
        $.ajax({
            url: 'index.php?route=openbay/openbay/verifyCreds&token=<?php echo $token; ?>',
            type: 'POST',
            dataType: 'json',
            data: {token: $('#openbaypro_token').val(), secret: $('#openbaypro_secret').val(), string1: $('#openbaypro_string1').val(), string2: $('#openbaypro_string2').val()},
            success: function(data) {
                if (data.error == false) {
                    $('#apiConnection').html('<img class="apiConnectionImg" src="view/image/success.png" /><?php echo $lang_api_ok; ?>: ' + data.data.expire);
                } else {
                    $('#apiConnection').html('<img class="apiConnectionImg" src="view/image/delete.png" />' + data.msg);
                }
            },
            failure: function() {
                $('#apiConnection').html('<img class="apiConnectionImg" src="view/image/delete.png" />&nbsp;&nbsp;<?php echo $lang_api_connect_fail; ?>');
            },
            error: function() {
                $('#apiConnection').html('<img class="apiConnectionImg" src="view/image/delete.png" />&nbsp;&nbsp;<?php echo $lang_api_connect_error; ?>');
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

    $('#tabs a').tabs();

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