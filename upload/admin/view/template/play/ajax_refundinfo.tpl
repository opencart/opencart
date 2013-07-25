<div id="openbayInfo">
    <div class="attention" style="margin-top:15px;"><?php echo $lang_ajax_play_refund; ?></div>
    <table class="form">
        <tbody>
        <tr>
            <td><?php echo $lang_ajax_refund_reason; ?>:</td>
            <td>
                <select name="play_refund_reason" id="play_refund_reason" class="openbayData">
                    <?php foreach($refund_reason as $id => $reason){ ?>
                        <option value="<?php echo $id; ?>"
                        <?php if(isset($order_info['refund_reason']) && $order_info['refund_reason'] == $id){ echo ' selected'; } ?>
                        ><?php echo $reason; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php echo $lang_ajax_refund_message; ?>:</td>
            <td><input class="openbayData" id="play_refund_message" type="text" name="play_refund_message" value="<?php if(isset($order_info['refund_message'])){ echo $order_info['refund_message']; } ?>"></td>
        </tr>
        </tbody>
    </table>
    <input type="hidden" name="orderChannel" value="play" id="orderChannel" />
</div>

<script type="text/javascript">
    function verifyStatusChange(){
        var message = '';

        if($('#orderChannel').val()){
            message = $('#play_refund_message').val();

            if(message == ''){
                alert('<?php echo $lang_ajax_refund_entermsg; ?>');
                return false;
            }
            if(message.length >= 1000){
                alert('<?php echo $lang_ajax_refund_charmsg; ?>');
                return false;
            }
            if((message.indexOf('>') != -1) || (message.indexOf('<') != -1)) {
                alert('<?php echo $lang_ajax_refund_charmsg2; ?>');
                return false;
            }
        }
    }
</script>