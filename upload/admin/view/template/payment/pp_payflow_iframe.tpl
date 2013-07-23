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
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content"> 
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <table class="form">
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_vendor; ?><br /><span class="help"><?php echo $help_vendor ?></span></td>
                    <td><input type="text" name="pp_payflow_iframe_vendor" value="<?php echo $pp_payflow_iframe_vendor; ?>" />
                        <?php if ($error_vendor) { ?>
                            <span class="error"><?php echo $error_vendor; ?></span>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_user; ?><br /><span class="help"><?php echo $help_user ?></span></td>
                    <td><input type="text" name="pp_payflow_iframe_user" value="<?php echo $pp_payflow_iframe_user; ?>" />
                        <?php if ($error_user) { ?>
                            <span class="error"><?php echo $error_user; ?></span>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_password; ?><br /><span class="help"><?php echo $help_password ?></span></td>
                    <td><input type="text" name="pp_payflow_iframe_password" value="<?php echo $pp_payflow_iframe_password; ?>" />
                        <?php if ($error_password) { ?>
                            <span class="error"><?php echo $error_password; ?></span>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_partner; ?><br /><span class="help"><?php echo $help_partner ?></span></td>
                    <td><input type="text" name="pp_payflow_iframe_partner" value="<?php echo $pp_payflow_iframe_partner; ?>" />
                        <?php if ($error_partner) { ?>
                            <span class="error"><?php echo $error_partner; ?></span>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_transaction_method; ?></td>
                    <td>
                        <select name="pp_payflow_iframe_transaction_method">
                            <?php if ($pp_payflow_iframe_transaction_method == 'authorization') { ?>
                                <option value="sale"><?php echo $text_sale; ?></option>
                                <option value="authorization" selected="selected"><?php echo $text_authorization; ?></option>
                            <?php } else { ?>
                                <option value="sale" selected="selected"><?php echo $text_sale; ?></option>
                                <option value="authorization"><?php echo $text_authorization; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_test; ?></td>
                    <td>
                        <?php if ($pp_payflow_iframe_test) { ?>
                            <input type="radio" name="pp_payflow_iframe_test" value="1" checked="checked" />
                            <?php echo $text_yes; ?>
                            <input type="radio" name="pp_payflow_iframe_test" value="0" />
                            <?php echo $text_no; ?>
                        <?php } else { ?>
                            <input type="radio" name="pp_payflow_iframe_test" value="1" />
                            <?php echo $text_yes; ?>
                            <input type="radio" name="pp_payflow_iframe_test" value="0" checked="checked" />
                            <?php echo $text_no; ?>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_debug; ?><br /><span class="help"><?php echo $help_debug ?></span></td>
                    <td>
                        <select name="pp_payflow_iframe_debug">
                            <?php if ($pp_payflow_iframe_debug) { ?>
                                <option value="1" selected="selected"><?php echo $text_yes ?></option>
                                <option value="0"><?php echo $text_no ?></option>
                            <?php } else { ?>
                                <option value="1"><?php echo $text_yes ?></option>
                                <option value="0" selected="selected"><?php echo $text_no ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_checkout_method ?><br /><span class="help"><?php echo $help_checkout_method ?></span></td>
                    <td>
                        <select name="pp_payflow_iframe_checkout_method">
                            <?php if ($pp_payflow_iframe_checkout_method == 'iframe'): ?>
                                        
                                <option value="iframe" selected="selected"><?php echo $text_iframe ?></option>
                                <option value="redirect"><?php echo $text_redirect ?></option>
                                        
                            <?php else: ?>
                                        
                                <option value="iframe"><?php echo $text_iframe ?></option>
                                <option value="redirect" selected="selected"><?php echo $text_redirect ?></option>
                                        
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_order_status; ?></td>
                    <td>
                        <select name="pp_payflow_iframe_order_status_id">
                            <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $pp_payflow_iframe_order_status_id) { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_total; ?></td>
                    <td><input type="text" name="pp_payflow_iframe_total" value="<?php echo $pp_payflow_iframe_total; ?>" /></td>
                </tr>
                <tr>
                    <td><?php echo $entry_geo_zone; ?></td>
                    <td>
                        <select name="pp_payflow_iframe_geo_zone_id">
                            <option value="0"><?php echo $text_all_zones; ?></option>
                            <?php foreach ($geo_zones as $geo_zone) { ?>
                                <?php if ($geo_zone['geo_zone_id'] == $pp_payflow_iframe_geo_zone_id) { ?>
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
                        <select name="pp_payflow_iframe_status">
                            <?php if ($pp_payflow_iframe_status) { ?>
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
                    <td><input type="text" name="pp_payflow_iframe_sort_order" value="<?php echo $pp_payflow_iframe_sort_order; ?>" size="1" /></td>
                </tr>
                <tr>
                    <td><?php echo $entry_cancel_url; ?></td>
                    <td><?php echo $cancel_url ?></td>
                </tr>
                <tr>
                    <td><?php echo $entry_error_url; ?></td>
                    <td><?php echo $error_url ?></td>
                </tr>
                <tr>
                    <td><?php echo $entry_return_url; ?></td>
                    <td><?php echo $return_url ?></td>
                </tr>
                <tr>
                    <td><?php echo $entry_post_url; ?></td>
                    <td><?php echo $post_url ?></td>
                </tr>
            </table>
        </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>