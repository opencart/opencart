<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
        <div class="warning"><?php echo $error_warning; ?></div>
    <?php } elseif ($message_success) { ?>
        <div class="success"><?php echo $message_success ?></div>
    <?php }?>
    <div class="box">
        <div class="heading">
            <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
            <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
        </div>
        <div class="content">

            <div id="tabs" class="htabs">
                <a href="#page-settings" id="tab-settings"><?php echo $text_settings ?></a>
                <a href="#page-help" id="tab-subscription"><?php echo $text_help ?></a>
                <a href="#page-log" id="tab-settings"><?php echo $text_log ?></a>
            </div>

            <div id="page-settings">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                    <div id="tab-general" class="page">
                        <table class="form">
                            <tr>
                                <td><span class="required">*</span> <?php echo $entry_merchant; ?></td>
                                <td>
                                    <input type="text" name="klarna_merchant" value="<?php echo $klarna_merchant; ?>" />
                                    <?php if ($error_merchant) { ?>
                                        <span class="error"><?php echo $error_merchant; ?></span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="required">*</span> <?php echo $entry_secret; ?></td>
                                <td>
                                    <input type="text" name="klarna_secret" value="<?php echo $klarna_secret; ?>" />
                                    <?php if ($error_secret) { ?>
                                        <span class="error"><?php echo $error_secret; ?></span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_server; ?></td>
                                <td>
                                    <select name="klarna_server">
                                        <?php if ($klarna_server == 'live') { ?>
                                            <option value="live" selected="selected"><?php echo $text_live; ?></option>
                                        <?php } else { ?>
                                            <option value="live"><?php echo $text_live; ?></option>
                                        <?php } ?>
                                        <?php if ($klarna_server == 'beta') { ?>
                                            <option value="beta" selected="selected"><?php echo $text_beta; ?></option>
                                        <?php } else { ?>
                                            <option value="beta"><?php echo $text_beta; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_pending_order_status; ?></td>
                                <td>
                                    <select name="klarna_pending_order_status_id">
                                        <?php foreach ($order_statuses as $order_status) { ?>
                                            <?php if ($order_status['order_status_id'] == $klarna_pending_order_status_id) { ?>
                                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_accepted_order_status; ?></td>
                                <td>
                                    <select name="klarna_accepted_order_status_id">
                                        <?php foreach ($order_statuses as $order_status) { ?>
                                            <?php if ($order_status['order_status_id'] == $klarna_accepted_order_status_id) { ?>
                                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><h3><?php echo $text_klarna_account ?></h3></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_minimum_amount; ?></td>
                                <td>
                                    <input type="text" name="klarna_acc_minimum_amount" value="<?php echo $klarna_acc_minimum_amount; ?>" />
                                    <?php if ($error_acc_minimum_amount) { ?>
                                        <span class="error"><?php echo $error_acc_minimum_amount; ?></span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_geo_zone; ?></td>
                                <td>
                                    <select name="klarna_acc_geo_zone_id">
                                        <option value="0"><?php echo $text_all_zones; ?></option>
                                        <?php foreach ($geo_zones as $geo_zone) { ?>
                                            <?php if ($geo_zone['geo_zone_id'] == $klarna_acc_geo_zone_id) { ?>
                                                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>            
                            <tr>
                                <td><?php echo $entry_status; ?></td>
                                <td>
                                    <select name="klarna_acc_status">
                                        <?php if ($klarna_acc_status) { ?>
                                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                            <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php } else { ?>
                                            <option value="1"><?php echo $text_enabled; ?></option>
                                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_sort_order; ?></td>
                                <td><input type="text" name="klarna_acc_sort_order" value="<?php echo $klarna_acc_sort_order; ?>" size="1" /></td>
                            </tr>
                            <tr>
                                <td colspan="2"><h3><?php echo $text_klarna_invoice  ?></h3></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_geo_zone; ?></td>
                                <td>
                                    <select name="klarna_inv_geo_zone_id">
                                        <option value="0"><?php echo $text_all_zones; ?></option>
                                        <?php foreach ($geo_zones as $geo_zone) { ?>
                                            <?php if ($geo_zone['geo_zone_id'] == $klarna_inv_geo_zone_id) { ?>
                                                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>            
                            <tr>
                                <td><?php echo $entry_status; ?></td>
                                <td>
                                    <select name="klarna_inv_status">
                                        <?php if ($klarna_inv_status) { ?>
                                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                            <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php } else { ?>
                                            <option value="1"><?php echo $text_enabled; ?></option>
                                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_sort_order; ?></td>
                                <td><input type="text" name="klarna_inv_sort_order" value="<?php echo $klarna_inv_sort_order; ?>" size="1" /></td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
                
            <div id="page-help">Help</div>
            <div id="page-log">
                <textarea style="width: 98%; min-height: 250px" readonly><?php echo $klarna_log ?></textarea>
                <a href="<?php echo $clear_log ?>" class="button" style="float: right; margin-top: 15px">Clear</a>
            </div>
            
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#tabs a').tabs();  
    });
</script>
<?php echo $footer; ?> 