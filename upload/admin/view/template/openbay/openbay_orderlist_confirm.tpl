<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/order.png" alt="" /> <?php echo $lang_confirm_title; ?></h1>
      <div class="buttons">
          <a href="<?php echo $link_cancel; ?>" class="button"><?php echo $lang_cancel; ?></a>
      </div>
    </div>
    <div class="content">
        <div class="attention"><?php echo $lang_confirm_change_text; ?>: <strong><?php echo $status_mapped[$this->request->post['change_order_status_id']]; ?></strong></div>
      <form action="<?php echo $link_complete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td class="center" width="60"><?php echo $column_order_id; ?></td>
              <td class="left" width="150"><?php echo $column_customer; ?></td>
              <td class="left" width="125"><?php echo $column_status; ?></td>
              <td class="left" width="60"><?php echo $lang_order_channel; ?></td>
                <td class="left"><?php echo $column_date_added; ?></td>
                <td class="left"><?php echo $lang_column_addtional; ?></td>
                <td class="left"><?php echo $lang_column_comments; ?></td>
                <td class="center">
                    <?php echo $lang_column_notify; ?>&nbsp;
                    <input type="checkbox" name="notify_all" id="notify_all" value="1" onchange="notifyAll();" />
                </td>
            </tr>
          </thead>
          <tbody>
          <input type="hidden" name="order_status_id" value="<?php echo $this->request->post['change_order_status_id']; ?>"/>
            <?php foreach ($orders as $order) { ?>
                <input type="hidden" name="order_id[]" value="<?php echo $order['order_id']; ?>"/>
                <input type="hidden" name="old_status[<?php echo $order['order_id']; ?>]" value="<?php echo $order['order_status_id']; ?>"/>
                <tr>
                    <td class="center"><?php echo $order['order_id']; ?></td>
                    <td class="left"><?php echo $order['customer']; ?></td>
                    <td class="left"><?php echo $order['status']; ?></td>
                    <td class="left">
                        <input type="hidden" name="channel[<?php echo $order['order_id']; ?>]" value="<?php echo $order['channel']; ?>"/>
                        <?php echo $order['channel']; ?>
                    </td>
                    <td class="left"><?php echo $order['date_added']; ?></td>
                    <td class="left">
<?php
                      if($order['channel'] == 'eBay'){
                        //shipping info
                        if($this->request->post['change_order_status_id'] == $this->config->get('EBAY_DEF_SHIPPED_ID')){
?>
                            <p>
                                <span style="display:block; width:80px; font-weight:bold;"><?php echo $lang_carrier; ?>:</span>
                                <select name="carrier[<?php echo $order['order_id']; ?>]">
                                    <?php foreach($market_options['ebay']['carriers'] as $carrier){ ?>
                                        <option <?php echo ($carrier['description'] == $order['shipping_method'] ? ' selected' : ''); ?>><?php echo $carrier['description']; ?></option>
                                    <?php } ?>
                                </select>
                            </p>
                            <p><span style="display:block; width:80px; font-weight:bold;"><?php echo $lang_tracking; ?>:</span><input type="text" name="tracking[<?php echo $order['order_id']; ?>]" value="" class="ebay_tracking_no"></p>
<?php
                        }
                      }

                      if($order['channel'] == 'Amazon'){
                        //shipping info
                        if($this->request->post['change_order_status_id'] == $this->config->get('openbay_amazon_order_status_shipped')){
?>
                        <p>
                            <span style="display:block; width:80px; font-weight:bold;"><?php echo $lang_carrier; ?>:</span>
                            <select name="carrier[<?php echo $order['order_id']; ?>]" class="amazon_carrier" id="amazon_carrier_<?php echo $order['order_id']; ?>">
                                <?php foreach($market_options['amazon']['carriers'] as $courier){ ?>
                                    <option><?php echo $courier; ?></option>
                                <?php } ?>
                                <option value="other"><?php echo $lang_other; ?></option>
                            </select>
                        </p>
                        <p>
                            <span style="display:block; width:80px; font-weight:bold;"><?php echo $lang_other; ?>:</span>
                            <input type="text" name="carrier_other[<?php echo $order['order_id']; ?>]" value="" id="amazon_carrier_<?php echo $order['order_id']; ?>_other">
                        </p>
                        <p>
                            <span style="display:block; width:80px; font-weight:bold;"><?php echo $lang_tracking; ?>:</span>
                            <input type="text" name="tracking[<?php echo $order['order_id']; ?>]" value="">
                        </p>
<?php
                        }
                      }
                      
                      if($order['channel'] == 'Amazonus'){
                        //shipping info
                        if($this->request->post['change_order_status_id'] == $this->config->get('openbay_amazonus_order_status_shipped')){
?>
                        <p>
                            <span style="display:block; width:80px; font-weight:bold;"><?php echo $lang_carrier; ?>:</span>
                            <select name="carrier[<?php echo $order['order_id']; ?>]" class="amazonus_carrier" id="amazonus_carrier_<?php echo $order['order_id']; ?>">
                                <?php foreach($market_options['amazonus']['carriers'] as $courier){ ?>
                                    <option><?php echo $courier; ?></option>
                                <?php } ?>
                                <option value="other"><?php echo $lang_other; ?></option>
                            </select>
                        </p>
                        <p>
                            <span style="display:block; width:80px; font-weight:bold;"><?php echo $lang_other; ?>:</span>
                            <input type="text" name="carrier_other[<?php echo $order['order_id']; ?>]" value="" id="amazonus_carrier_<?php echo $order['order_id']; ?>_other">
                        </p>
                        <p>
                            <span style="display:block; width:80px; font-weight:bold;"><?php echo $lang_tracking; ?>:</span>
                            <input type="text" name="tracking[<?php echo $order['order_id']; ?>]" value="">
                        </p>
<?php
                        }
                      }
                      
                      

                      if($order['channel'] == 'Play'){
                        //shipping info
                        if($this->request->post['change_order_status_id'] == $this->config->get('obp_play_shipped_id')){
?>
                        <p>
                            <span style="display:block; width:80px; font-weight:bold;"><?php echo $lang_carrier; ?>:</span>
                            <select name="carrier[<?php echo $order['order_id']; ?>]">
                                <?php foreach($market_options['play']['carriers'] as $id => $carrier){ ?>
                                    <option value="<?php echo $id; ?>"><?php echo $carrier; ?></option>
                                <?php } ?>
                            </select>
                        </p>

                        <span style="display:block; width:80px; font-weight:bold;"><?php echo $lang_tracking; ?>:</span>
                        <input type="text" name="tracking[<?php echo $order['order_id']; ?>]" value="none" class="play_tracking_no">

<?php
                        }

                        //refund info
                        if($this->request->post['change_order_status_id'] == $this->config->get('obp_play_refunded_id')){
?>
                        <span style="display:block; width:80px; font-weight:bold;"><?php echo $lang_refund_reason; ?>:</span>
                            <select name="refund_reason[<?php echo $order['order_id']; ?>]">
                                <?php foreach($refund_reason as $id => $reason){ ?>
                                    <option value="<?php echo $id; ?>" ><?php echo $reason; ?></option>
                                <?php } ?>
                            </select>

                        <span style="display:block; width:80px; font-weight:bold;"><?php echo $lang_refund_message; ?>:</span>
                            <input type="text" name="refund_message[<?php echo $order['order_id']; ?>]" value="" class="play_refund_reason">
<?php
                        }
                      }
?>
                    </td>
                    <td class="left"><textarea name="comments[<?php echo $order['order_id']; ?>]"></textarea></td>
                    <td class="center">
                        <input type="hidden" name="notify[<?php echo $order['order_id']; ?>]" value="0"/>
                        <input type="checkbox" name="notify[<?php echo $order['order_id']; ?>]" class="notify_checkbox" value="1"/>
                    </td>
                </tr>
            <?php } ?>
          </tbody>
        </table>

      </form>
        <div class="buttons right" style="margin-top:20px;">
            <a onclick="validate();" class="button"><?php echo $lang_update; ?></a>
        </div>
    </div>
  </div>
</div>

<script>
    function notifyAll(){
        var valChecked = $('#notify_all').prop('checked');

        if(valChecked == true){
            $('.notify_checkbox').prop('checked', true);
        }else{
            $('.notify_checkbox').prop('checked', false);
        }
    }

    function validate(){
        var element_id;
        var error = false;
        var errorPlayRefund = false;
        var errorPlayTracking = false;
        var errorAmazonCarrier = false;
        var errorAmazonusCarrier = false;

        $.each($('.play_refund_reason'), function(k,v){
            if($(this).val() == ''){
                error = true;
                errorPlayRefund = true;
                $(this).css('border-color','#FF0000');
            }
        });

        $.each($('.play_tracking_no'), function(k,v){
            if($(this).val() == ''){
                error = true;
                errorPlayTracking = true;
                $(this).css('border-color','#FF0000');
            }
        });

        $.each($('.amazon_carrier'), function(k,v){
            if($(this).val() == 'other'){
                element_id = $(this).attr("id");

                if($('#'+element_id+'_other').val() == ''){
                    error = true;
                    errorAmazonCarrier = true;
                    $('#'+element_id+'_other').css('border-color','#FF0000');
                }
            }
        });
        
        $.each($('.amazonus_carrier'), function(k,v){
            if($(this).val() == 'other'){
                element_id = $(this).attr("id");

                if($('#'+element_id+'_other').val() == ''){
                    error = true;
                    errorAmazonusCarrier = true;
                    $('#'+element_id+'_other').css('border-color','#FF0000');
                }
            }
        });

        if(errorPlayRefund == true){
            alert('<?php echo $lang_e_ajax_1; ?>');
        }

        if(errorPlayTracking == true){
            alert('<?php echo $lang_e_ajax_2; ?>');
        }

        if(errorAmazonCarrier == true){
            alert('<?php echo $lang_e_ajax_3; ?>');
        }
        
        if(errorAmazonusCarrier == true){
            alert('<?php echo $lang_e_ajax_3; ?>');
        }

        if(error == false){
            $('#form').submit();
        }else{
            return false;
        }
    }
</script>

<?php echo $footer; ?>