<div id="openbayInfo">
    <div class="attention mTop15"><?php echo $lang_ajax_amazonuseu_shipped; ?></div>
    <table class="form">
        <tbody>
        <tr>
            <td><?php echo $lang_ajax_courier; ?>:</td>
            <td>
                <select name="courier_id" id="courier_id" class="openbayData">
                    <option></option>
                    <?php foreach($couriers as $courier){ ?>
                        <option <?php if(!$order_info['courier_other'] && $order_info['courier_id'] == $courier) echo "selected"; ?>><?php echo $courier; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php echo $lang_ajax_courier_other; ?>:</td>
            <td><input class="openbayData" id="courier_other" type="text" name="courier_other" value="<?php if($order_info['courier_other']){ echo $order_info['courier_id']; } ?>"></td>
        </tr>
        <tr>
            <td>Tracking #:</td>
            <td><input class="openbayData" id="tracking_no" type="text" name="tracking_no" value="<?php if(isset($order_info['tracking_no'])){ echo $order_info['tracking_no']; } ?>"></td>
        </tr>
        </tbody>
    </table>
    
    <input type="hidden" name="orderChannel" value="Amazon US" id="orderChannel" />
</div>

<script type="text/javascript">
    function verifyStatusChange() {
        var courier = '';
        var courier_other = '';
        var tracking = '';
        
        
        if($('#orderChannel').val()){
            courier = $('#courier_id').val();
            courier_other = $('#courier_other').val();
            tracking = $('#tracking_no').val();

            if(courier != '' && courier_other != '') {
                alert('<?php echo $lang_ajax_tracking_msg4; ?>');
                return false;
            }

            if(tracking != '' && courier == '' && courier_other == ''){
                alert('<?php echo $lang_ajax_tracking_msg3; ?>');
                return false;
            }
            
            if((tracking.indexOf('>') != -1) || (tracking.indexOf('<') != -1)) {
                alert('<?php echo $lang_ajax_tracking_msg2; ?>');
                return false;
            }
        }
    }
</script>