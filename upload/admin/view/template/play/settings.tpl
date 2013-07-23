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
            <div class="buttons">
                <a onclick="validateForm(); return false;" class="button"><span><?php echo $lang_save; ?></span></a>
                <a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $lang_cancel; ?></span></a>
            </div>
        </div>
        <div class="content">
            <div id="tabs" class="htabs">
                <a href="#tab-connection"><?php echo $lang_tab_connection; ?></a>
                <a href="#tab-application"><?php echo $lang_tab_application; ?></a>
                <a href="#tab-defaults"><?php echo $lang_tab_defaults; ?></a>
            </div>

            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <div id="tab-connection">
                    <h2><?php echo $lang_legend_api; ?></h2>

                    <table class="form">
                        <tr>
                            <td><p><?php echo $lang_status; ?></p></td>
                            <td><p><select name="play_status" style="width:200px;">
                                <?php if ($play_status) { ?>
                                <option value="1" selected="selected"><?php echo $lang_enabled; ?></option>
                                <option value="0"><?php echo $lang_disabled; ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $lang_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $lang_disabled; ?></option>
                                <?php } ?>
                            </select></p></td>
                        </tr>

                        <tr>
                            <td><p><label for="obp_play_token"><?php echo $lang_obp_token; ?></p></td>
                            <td>
                                <p><img src="<?php echo HTTPS_SERVER; ?>view/image/lock.png" style="margin-bottom:-3px;" />&nbsp;<a href="https://playuk.openbaypro.com/account/register" target="_BLANK"><?php echo $lang_obp_token_register; ?></a></p>
                                <p><input type="text" name="obp_play_token" id="obp_play_token" style="width:300px;" maxlength="" value="<?php echo $obp_play_token;?>" class="credentials" /></p>
                            </td>
                        </tr>

                        <tr>
                            <td><p><label for="obp_play_secret"><?php echo $lang_obp_secret; ?></p></td>
                            <td><p><input type="text" name="obp_play_secret" id="obp_play_secret" style="width:300px;" maxlength="" value="<?php echo $obp_play_secret;?>" class="credentials" /></p></td>
                        </tr>

                        <tr>
                            <td><p><label for="obp_play_key"><?php echo $lang_obp_key; ?></p></td>
                            <td><p><input type="text" name="obp_play_key" id="obp_play_key" style="width:300px;" maxlength="" value="<?php echo $obp_play_key;?>" class="credentials" /></p></td>
                        </tr>

                        <tr>
                            <td><p><label for="obp_play_key2"><?php echo $lang_obp_key; ?> 2</p></td>
                            <td><p><input type="text" name="obp_play_key2" id="obp_play_key2" style="width:300px;" maxlength="" value="<?php echo $obp_play_key2;?>" class="credentials" /></p></td>
                        </tr>
                    </table>
                </div>

                <div id="tab-application">
                    <h2><?php echo $lang_legend_app_settings; ?></h2>
                    <table class="form">
                        <tr>
                            <td><?php echo $lang_app_logging; ?></td>
                            <td>
                                <select name="obp_play_logging" style="width:200px;">
                                    <?php if ($obp_play_logging) { ?>
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
                                <select name="obp_play_def_currency" style="width:200px;">
                                    <?php
                                        foreach($currency_list as $currency){
                                            echo '<option value="'.$currency['code'].'"';
                                            if($obp_play_def_currency == $currency['code']){ echo ' selected="selected"';}
                                            echo'>'.$currency['title'].'</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_app_cust_grp; ?></td>
                            <td>
                                <select name="obp_play_def_customer_grp" style="width:200px;">
                                    <?php
                                        foreach($customer_grp_list as $customer_grp){
                                            echo '<option value="'.$customer_grp['customer_group_id'].'"';
                                            if($obp_play_def_customer_grp == $customer_grp['customer_group_id']){ echo ' selected="selected"';}
                                            echo'>'.$customer_grp['name'].'</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </table>

                    <br /><br />

                    <h2><?php echo $lang_legend_default_import; ?></h2>
                    <table class="form">
                        <tr>
                            <td><label for="obp_play_import_id"><?php echo $lang_import_def_id; ?></td>
                            <td>
                                <select name="obp_play_import_id" style="width:200px;">
                                    <?php
                                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status");
                                    if (empty($obp_play_import_id)) { $obp_play_import_id = 1; }

                                    foreach($query->rows as $status){
                                        echo'<option value="'.$status['order_status_id'].'"';
                                        if($obp_play_import_id == $status['order_status_id']){echo ' selected=selected';}
                                        echo'>'.$status['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="obp_play_paid_id"><?php echo $lang_import_paid_id; ?></td>
                            <td>
                                <select name="obp_play_paid_id" style="width:200px;">
                                    <?php
                                    if (empty($obp_play_paid_id)) { $obp_play_paid_id = 2; }

                                    foreach($query->rows as $status){
                                        echo'<option value="'.$status['order_status_id'].'"';
                                        if($obp_play_paid_id == $status['order_status_id']){echo ' selected=selected';}
                                        echo'>'.$status['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="obp_play_shipped_id"><?php echo $lang_import_shipped_id; ?></td>
                            <td>
                                <select name="obp_play_shipped_id" style="width:200px;">
                                    <?php
                                    if (empty($obp_play_shipped_id)) { $obp_play_shipped_id = 3; }

                                    foreach($query->rows as $status){
                                        echo'<option value="'.$status['order_status_id'].'"';
                                        if($obp_play_shipped_id == $status['order_status_id']){echo ' selected=selected';}
                                        echo'>'.$status['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="obp_play_cancelled_id"><?php echo $lang_import_cancelled_id; ?></td>
                            <td>
                                <select name="obp_play_cancelled_id" style="width:200px;">
                                    <?php
                                    if (empty($obp_play_cancelled_id)) { $obp_play_cancelled_id = 7; }

                                    foreach($query->rows as $status){
                                        echo'<option value="'.$status['order_status_id'].'"';
                                        if($obp_play_cancelled_id == $status['order_status_id']){echo ' selected=selected';}
                                        echo'>'.$status['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="obp_play_refunded_id"><?php echo $lang_import_refund_id; ?></td>
                            <td>
                                <select name="obp_play_refunded_id" style="width:200px;">
                                    <?php
                                    if (empty($obp_play_refunded_id)) { $obp_play_refunded_id = 11; }

                                    foreach($query->rows as $status){
                                        echo'<option value="'.$status['order_status_id'].'"';
                                        if($obp_play_refunded_id == $status['order_status_id']){echo ' selected=selected';}
                                        echo'>'.$status['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="tab-defaults">
                    <table class="form">
                        <tr>
                            <td><?php echo $lang_default_ship_to; ?></td>
                            <td>
                                <select name="obp_play_def_shipto" style="width:200px;">
                                    <?php foreach($dispatch_to as $key => $type){ ?>
                                    <option value="<?php echo $key; ?>"
                                    <?php if($key == $obp_play_def_shipto){ echo ' selected';} ?>
                                    ><?php echo $type; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_default_ship_from; ?></td>
                            <td>
                                <select name="obp_play_def_shipfrom" style="width:200px;">
                                    <?php foreach($dispatch_from as $key => $type){ ?>
                                    <option value="<?php echo $key; ?>"
                                    <?php if($key == $obp_play_def_shipfrom){ echo ' selected';} ?>
                                    ><?php echo $type; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_default_item_condition; ?></td>
                            <td>
                                <select name="obp_play_def_itemcond" style="width:200px;">
                                    <?php foreach($item_conditions as $key => $type){ ?>
                                    <option value="<?php echo $key; ?>"
                                    <?php if($key == $obp_play_def_itemcond){ echo ' selected';} ?>
                                    ><?php echo $type; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_order_new_notify; ?></td>
                            <td>
                                <select name="obp_play_order_new_notify" style="width:200px;">
                                    <?php if ($obp_play_order_new_notify) { ?>
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
                            <td><?php echo $lang_order_new_notify_admin; ?></td>
                            <td>
                                <select name="obp_play_order_new_notify_admin" style="width:200px;">
                                    <?php if ($obp_play_order_new_notify_admin) { ?>
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
                            <td><?php echo $lang_order_update_notify; ?></td>
                            <td>
                                <select name="obp_play_order_update_notify" style="width:200px;">
                                    <?php if ($obp_play_order_update_notify) { ?>
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
                            <td><p><label for="obp_play_default_tax"><?php echo $lang_obp_tax; ?></p></td>
                            <td><p><input type="text" name="obp_play_default_tax" id="obp_play_default_tax" style="width:100px;" maxlength="" value="<?php echo $obp_play_default_tax;?>" class="credentials" /></p></td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">


    function validateForm() {
        $('#form').submit();
    }

    $('#tabs a').tabs();

    $(document).ready(function() {

    });
    //--></script>
<?php echo $footer; ?>