<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>

    <div class="box">
        <div class="heading">
            <h1><?php echo $lang_heading_title; ?></h1>
            <div class="buttons">
                <a class="button" onclick="$('#settings-form').submit()" ><span><?php echo $save_text ?></span></a>
            </div>
        </div>

        <div class="content">

            <div id="tabs" class="htabs">
                <a href="#page-settings" id="tab-settings"><?php echo $settings_text ?></a>
                <a href="#page-product" id="tab-product"><?php echo $listing_text ?></a>
                <a href="#page-orders" id="tab-settings"><?php echo $orders_text ?></a>
                <a href="#page-subscription" id="tab-subscription"><?php echo $subscription_text ?></a>
                <a href="#page-itemLinks" id="tab-itemLinks"><?php echo $link_items_text ?></a>
            </div>

            <form method="POST" action="" id="settings-form">

                <div id="page-settings">
                    <table class="form">
                        <tr>
                            <td><?php echo $enabled_text ?></td>
                            <td>               
                                <select style="width:200px;" name="amazon_status" id="enabled">
                                    <?php if ($is_enabled) { ?>
                                        <option value="1" selected="selected"><?php echo $yes_text ?></option>
                                        <option value="0"><?php echo $no_text ?></option>
                                    <?php } else { ?>
                                        <option value="1"><?php echo $yes_text ?></option>
                                        <option value="0" selected="selected"><?php echo $no_text ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $token_text ?></td>
                            <td><input style="width:300px;" type="text" name="openbay_amazon_token" value="<?php echo $openbay_amazon_token ?>" /></td>
                        </tr>
                        <tr>
                            <td><?php echo $enc_string1_text ?></td>
                            <td><input style="width:300px;" type="text" name="openbay_amazon_enc_string1" value="<?php echo $openbay_amazon_enc_string1 ?>" /></td>
                        </tr>
                        <tr>
                            <td><?php echo $enc_string2_text ?></td>
                            <td><input style="width:300px;" type="text" name="openbay_amazon_enc_string2" value="<?php echo $openbay_amazon_enc_string2 ?>"/></td>
                        </tr>
                    </table>                    
                </div>
                
                <div id="page-product">
                    <table class="form">
                        <tr>
                            <td><?php echo $tax_percentage_text ?></td>
                            <td><input type="text" name="openbay_amazon_listing_tax_added" value="<?php echo $openbay_amazon_listing_tax_added ?>" style="width:50px;" />%</td>
                        </tr>
                        <!-- Marketplaces -->
                        <tr>
                            <td>Default marketplaces<span class="help"><?php echo $default_mp_text; ?></span></td>
                            <td>
                                <?php foreach ($marketplaces as $mp) { ?>
                                    <div style="text-align: center; float: left; margin-right: 20px;">
                                        <label for="default_marketplace_<?php echo $mp['code'] ?>"><?php echo $mp['name'] ?></label>
                                        <input id="default_marketplace_<?php echo $mp['code'] ?>" <?php  if (in_array($mp['id'], $default_listing_marketplace_ids)) { ?> checked="checked" <?php }  ?> type="checkbox" name="openbay_amazon_default_listing_marketplace_ids[]" value="<?php echo $mp['id'] ?>">
                                    </div>
                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                </div>

                <div id="page-orders">
                    <table class="form">
                        <tr>
                            <td colspan="2"><h2><?php echo $order_statuses_text ?></h2></td>
                        </tr>
                        <?php foreach ($amazon_order_statuses as $key => $amazon_order_status): ?>
                            <tr>
                                <td><?php echo $amazon_order_status['name'] ?></td>
                                <td>
                                    <select name="openbay_amazon_order_status_<?php echo $key ?>" style="width:200px;">
                                        <?php foreach ($order_statuses as $order_status): ?>
                                            <?php if ($amazon_order_status['order_status_id'] == $order_status['order_status_id']): ?>
                                                <option value="<?php echo $order_status['order_status_id'] ?>" selected="selected"><?php echo $order_status['name'] ?></option>
                                            <?php else: ?>
                                                <option value="<?php echo $order_status['order_status_id'] ?>"><?php echo $order_status['name'] ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="2">
                                <h2><?php echo $marketplaces_text ?></h2>
                            </td>
                        </tr>
                        <?php foreach ($marketplaces as $marketplace): ?>
                        <tr>
                            <td><label for="code_<?php echo $marketplace['code'] ?>"><?php echo $marketplace['name'] ?></label></td>
                            <td>
                                <?php if (in_array($marketplace['id'], $marketplace_ids)): ?>
                                    <input id="code_<?php echo $marketplace['code'] ?>" type="checkbox" name="openbay_amazon_orders_marketplace_ids[]" value="<?php echo $marketplace['id'] ?>" checked="checked" />
                                <?php else: ?>
                                    <input id="code_<?php echo $marketplace['code'] ?>" type="checkbox" name="openbay_amazon_orders_marketplace_ids[]" value="<?php echo $marketplace['id'] ?>" />
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="2">
                                <h2><?php echo $other_text ?></h2>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="openbay_amazon_order_tax"><?php echo $import_tax_text ?></label>
                            </td>
                            <td>
                                <input type="text" name="openbay_amazon_order_tax" value="<?php echo $openbay_amazon_order_tax ?>" style="width:50px;" />%
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="customer_group_input"><?php echo $customer_group_text ?></label><br />
                                <span class="help"><?php echo $customer_group_help_text ?></span>
                            </td>
                            <td>
                                <select name="openbay_amazon_order_customer_group" style="width:200px;">
                                    <?php foreach($customer_groups as $customer_group): ?>
                                        <?php if ($openbay_amazon_order_customer_group == $customer_group['customer_group_id']): ?>
                                        <option value="<?php echo $customer_group['customer_group_id']?>" selected="selected"><?php echo $customer_group['name'] ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo $customer_group['customer_group_id']?>"><?php echo $customer_group['name'] ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>

                <div id="page-subscription">
                </div>
                
                <div id="page-itemLinks">
                </div>

            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript">
    
    $(document).ready(function(){
        $('#tabs a').tabs(); 
        
        $('#tab-subscription').click(function(){
            $.ajax({
                url: '<?php echo html_entity_decode($subscription_url); ?>',
                data: {},
                dataType: 'html',
                success: function(data) {
                    $('#page-subscription').html(data);
                }
            });
        });
        
        var linksPageLoaded = false;
        $('#tab-itemLinks').click(function(){
            if(!linksPageLoaded) { 
                $.ajax({
                    url: '<?php echo html_entity_decode($itemLinks_url); ?>',
                    data: {},
                    dataType: 'html',
                    success: function(data) {
                        $('#page-itemLinks').html(data);
                        linksPageLoaded = true;
                    }
                });
            }
        });
    });
</script>
<?php echo $footer; ?>