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
        
       <div id="htabs" class="htabs"><a href="#tab-settings"><?php echo $tab_settings; ?></a><a href="#tab-order-status"><?php echo $tab_order_status; ?></a></div>
          
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
          
            <div id="tab-settings">
            
                <table class="form">
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_user; ?></td>
                        <td><input type="text" name="pp_pro_iframe_user" value="<?php echo $pp_pro_iframe_user; ?>" />
                            <?php if ($error_user) { ?>
                                <span class="error"><?php echo $error_user; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_password; ?></td>
                        <td><input type="text" name="pp_pro_iframe_password" value="<?php echo $pp_pro_iframe_password; ?>" />
                            <?php if ($error_password) { ?>
                                <span class="error"><?php echo $error_password; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_sig; ?></td>
                        <td><input type="text" name="pp_pro_iframe_sig" value="<?php echo $pp_pro_iframe_sig; ?>" />
                            <?php if ($error_sig) { ?>
                                <span class="error"><?php echo $error_sig; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_transaction_method; ?></td>
                        <td>
                            <select name="pp_pro_iframe_transaction_method">
                                <?php if ($pp_pro_iframe_transaction_method == 'authorization') { ?>
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
                            <?php if ($pp_pro_iframe_test) { ?>
                                <input type="radio" name="pp_pro_iframe_test" value="1" checked="checked" />
                                <?php echo $text_yes; ?>
                                <input type="radio" name="pp_pro_iframe_test" value="0" />
                                <?php echo $text_no; ?>
                            <?php } else { ?>
                                <input type="radio" name="pp_pro_iframe_test" value="1" />
                                <?php echo $text_yes; ?>
                                <input type="radio" name="pp_pro_iframe_test" value="0" checked="checked" />
                                <?php echo $text_no; ?>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_debug; ?><br /><span class="help"><?php echo $help_debug ?></span></td>
                        <td>
                            <select name="pp_pro_iframe_debug">
                                <?php if ($pp_pro_iframe_debug) { ?>
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
                            <select name="pp_pro_iframe_checkout_method">
                                <?php if ($pp_pro_iframe_checkout_method == 'iframe'): ?>
                                
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
                        <td><?php echo $entry_total; ?></td>
                        <td><input type="text" name="pp_pro_iframe_total" value="<?php echo $pp_pro_iframe_total; ?>" /></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_geo_zone; ?></td>
                        <td>
                            <select name="pp_pro_iframe_geo_zone_id">
                                <option value="0"><?php echo $text_all_zones; ?></option>
                                <?php foreach ($geo_zones as $geo_zone) { ?>
                                    <?php if ($geo_zone['geo_zone_id'] == $pp_pro_iframe_geo_zone_id) { ?>
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
                            <select name="pp_pro_iframe_status">
                                <?php if ($pp_pro_iframe_status) { ?>
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
                        <td><input type="text" name="pp_pro_iframe_sort_order" value="<?php echo $pp_pro_iframe_sort_order; ?>" size="1" /></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_ipn_url; ?></td>
                        <td><?php echo $ipn_url ?></td>
                    </tr>
                </table>
            </div>
            <div id="tab-order-status">

                <table class="form">
                    <tr>
                        <td><?php echo $entry_canceled_reversal_status; ?></td>
                        <td>
                            <select name="pp_pro_iframe_canceled_reversal_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $pp_pro_iframe_canceled_reversal_status_id) { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_completed_status; ?></td>
                        <td>
                            <select name="pp_pro_iframe_completed_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $pp_pro_iframe_completed_status_id) { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_denied_status; ?></td>
                        <td>
                            <select name="pp_pro_iframe_denied_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $pp_pro_iframe_denied_status_id) { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_expired_status; ?></td>
                        <td>
                            <select name="pp_pro_iframe_expired_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $pp_pro_iframe_expired_status_id) { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_failed_status; ?></td>
                        <td>
                            <select name="pp_pro_iframe_failed_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $pp_pro_iframe_failed_status_id) { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_pending_status; ?></td>
                        <td>
                            <select name="pp_pro_iframe_pending_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $pp_pro_iframe_pending_status_id) { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_processed_status; ?></td>
                        <td>
                            <select name="pp_pro_iframe_processed_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $pp_pro_iframe_processed_status_id) { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_refunded_status; ?></td>
                        <td>
                            <select name="pp_pro_iframe_refunded_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $pp_pro_iframe_refunded_status_id) { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_reversed_status; ?></td>
                        <td>
                            <select name="pp_pro_iframe_reversed_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $pp_pro_iframe_reversed_status_id) { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_voided_status; ?></td>
                        <td>
                            <select name="pp_pro_iframe_voided_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $pp_pro_iframe_voided_status_id) { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
    $('#htabs a').tabs();
//--></script>
<?php echo $footer; ?>