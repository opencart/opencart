<?php echo $header; ?>
<style type="text/css">
    
    table.sub-form > tbody > tr > td:first-child {
        width: 200px;
    }
    
    .hidden {
        display: none;
    }
    
    span.help {
        display: inline;
    }
    
</style>
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
                <a href="#page-log" id="tab-settings"><?php echo $text_log ?></a>
            </div>

            <div id="page-settings">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                    <div id="tab-general" class="page">
                        <table class="form">
                            <tr>
                                <td colspan="2">
                                    <a onclick="window.open('https://merchants.klarna.com/signup?locale=en&partner_id=d5c87110cebc383a826364769047042e777da5e8&utm_campaign=Platform&utm_medium=Partners&utm_source=Opencart');" ><img src="view/image/payment/klarna_banner.gif" /></a>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_pending_order_status; ?></td>
                                <td>
                                    <select name="klarna_account_pending_order_status_id">
                                        <?php foreach ($order_statuses as $order_status) { ?>
                                            <?php if ($order_status['order_status_id'] == $klarna_account_pending_order_status_id) { ?>
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
                                    <select name="klarna_account_accepted_order_status_id">
                                        <?php foreach ($order_statuses as $order_status) { ?>
                                            <?php if ($order_status['order_status_id'] == $klarna_account_accepted_order_status_id) { ?>
                                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                                
                            <?php foreach ($country_names as $iso_3 => $country) { ?>
                                    
                                <tr>
                                    <td style="text-align: center">
                                        <?php if ($klarna_account_country[$iso_3]['status'] == 1) { ?>
                                            <input type="checkbox" checked="checked" class="input_country" id="input_coutry_<?php echo $iso_3 ?>" name="klarna_account_country[<?php echo $iso_3 ?>][status]" value="1" />
                                        <?php } else { ?>
                                            <input type="checkbox" class="input_country" id="input_coutry_<?php echo $iso_3 ?>" name="klarna_account_country[<?php echo $iso_3 ?>][status]" value="1" />
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <label for="input_coutry_<?php echo $iso_3 ?>" ><?php echo $country ?></label>
                                    </td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td>
                                        <?php if ($klarna_account_country[$iso_3]['status'] == 1) { ?>
                                        <div class="country_options">
                                        <?php } else { ?>
                                        <div class="country_options hidden">
                                        <?php } ?>
                                            <table class="form sub-form">
                                                <tr>
                                                    <td><label for="input_merchant_<?php echo $iso_3 ?>"><?php echo $entry_merchant ?></label></td>
                                                    <td><input type="text" id="input_merchant_<?php echo $iso_3 ?>" name="klarna_account_country[<?php echo $iso_3 ?>][merchant]" value="<?php echo $klarna_account_country[$iso_3]['merchant'] ?>" /></td>
                                                </tr>
                                                <tr>
                                                    <td><label for="input_secret_<?php echo $iso_3 ?>"><?php echo $entry_secret ?></label></td>
                                                    <td><input type="text" id="input_secret_<?php echo $iso_3 ?>" name="klarna_account_country[<?php echo $iso_3 ?>][secret]" value="<?php echo $klarna_account_country[$iso_3]['secret'] ?>" /></td>
                                                </tr>
                                                <tr>
                                                    <td><label for="select_server_<?php echo $iso_3 ?>"><?php echo $entry_server; ?></label></td>
                                                    <td>
                                                        <select id="select_server_<?php echo $iso_3 ?>" name="klarna_account_country[<?php echo $iso_3 ?>][server]">
                                                            <?php if ($klarna_account_country[$iso_3]['server'] == 'live') { ?>
                                                                <option value="live" selected="selected"><?php echo $text_live; ?></option>
                                                            <?php } else { ?>
                                                                <option value="live"><?php echo $text_live; ?></option>
                                                            <?php } ?>
                                                            <?php if ($klarna_account_country[$iso_3]['server'] == 'beta') { ?>
                                                                <option value="beta" selected="selected"><?php echo $text_beta; ?></option>
                                                            <?php } else { ?>
                                                                <option value="beta"><?php echo $text_beta; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><label for="input_minimum_<?php echo $iso_3?>"><?php echo $entry_minimum_amount ?></label></td>
                                                    <td><input id="input_minimum_<?php echo $iso_3?>"type="text" name="klarna_account_country[<?php echo $iso_3 ?>][minimum]" value="<?php echo $klarna_account_country[$iso_3]['minimum'] ?>" /></td>
                                                </tr>
                                                <tr>
                                                    <td><label for="input_sort_order_<?php echo $iso_3?>"><?php echo $entry_sort_order ?></label></td>
                                                    <td><input id="input_sort_order_<?php echo $iso_3?>" type="text" name="klarna_account_country[<?php echo $iso_3 ?>][sort_order]" value="<?php echo $klarna_account_country[$iso_3]['sort_order'] ?>" /></td>
                                                </tr>
                                                <tr>
                                                    <td><label for="select_geo_zone_<?php echo $iso_3?>"><?php echo $entry_geo_zone ?></label></td>
                                                    <td>
                                                        <select id="select_geo_zone_<?php echo $iso_3 ?>" name="klarna_account_country[<?php echo $iso_3 ?>][geo_zone_id]">
                                                            <?php if ($klarna_account_country[$iso_3]['geo_zone_id'] == 0) { ?>
                                                                <option value="0" selected="selected"><?php echo $text_all_zones ?></option>
                                                            <?php } else {?>
                                                                <option value="0"><?php echo $text_all_zones ?></option>
                                                            <?php } ?>
                                                            <?php foreach ($geo_zones as $geo_zone) { ?>
                                                                <?php if ($geo_zone['geo_zone_id'] == $klarna_account_country[$iso_3]['geo_zone_id']) {  ?>
                                                                    <option value="<?php echo $geo_zone['geo_zone_id'] ?>" selected="selected"><?php echo $geo_zone['name'] ?></option>
                                                                <?php } else { ?>
                                                                    <option value="<?php echo $geo_zone['geo_zone_id'] ?>"><?php echo $geo_zone['name'] ?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                </tr>

                            <?php } ?>
                            
                        </table>
                    </div>
                </form>
            </div>
                
            <div id="page-log">
                <textarea style="width: 98%; min-height: 250px" readonly><?php echo $klarna_account_log ?></textarea>
                <a href="<?php echo $clear_log ?>" class="button" style="float: right; margin-top: 15px"><?php echo $text_clear ?></a>
            </div>
            
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#tabs a').tabs();  
    });
    
    $('.input_country').change(function(){
        var target = $(this).closest('tr').next().find('.country_options');

        if ($(this).is(':checked')) {
            target.slideDown(300);
        } else {
            target.slideUp(220);
        }
    });
</script>
<?php echo $footer; ?> 