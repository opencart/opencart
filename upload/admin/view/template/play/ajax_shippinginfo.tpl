<div id="openbayInfo">
    <div class="attention" style="margin-top:15px;"><?php echo $lang_ajax_play_shipped; ?></div>
    <table class="form">
        <tbody>
        <tr>
            <td><?php echo $lang_ajax_courier; ?>:</td>
            <td>
                <select name="play_courier" id="play_courier" class="openbayData">
                    <?php foreach($carriers as $id => $carrier){ ?>
                        <option value="<?php echo $id; ?>"

                        <?php if(isset($order_info['carrier_id']) && $order_info['carrier_id'] == $id){ echo ' selected'; } ?>
                        ><?php echo $carrier; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php echo $lang_ajax_tracking; ?>:</td>
            <td><input class="openbayData" id="play_tracking_no" type="text" name="play_tracking_no" value="<?php if(isset($order_info['tracking_no'])){ echo $order_info['tracking_no']; } ?>"></td>
        </tr>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    function verifyStatusChange(){
        var message = '';

        if($('#orderChannel').val()){
            message = $('#play_tracking_no').val();

            if(message == ''){
                alert('<?php echo $lang_ajax_tracking_msg; ?>');
                return false;
            }
            if((message.indexOf('>') != -1) || (message.indexOf('<') != -1)) {
                alert('<?php echo $lang_ajax_tracking_msg2; ?>');
                return false;
            }
        }
    }
</script>