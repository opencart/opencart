<?php echo $header; ?>
<div id="content">

    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>

    <?php if ($error != '') { ?>
    <div class="warning"><?php echo $error; ?></div>
    <?php } ?>

    <?php if ($attention != '') { ?>
    <div class="attention"><?php echo $attention; ?></div>
    <?php } ?>

    <div class="box">

        <div class="heading">
            <h1><img src="view/image/payment.png" alt=""/> <?php echo $heading_title; ?></h1>
            <div class="buttons"><a href="<?php echo $cancel; ?>" class="button"><?php echo $btn_cancel; ?></a></div>
        </div>

        <div class="content">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="form">
                    <input type="hidden" name="amount_original" value="<?php echo $amount_original; ?>"/>
                    <input type="hidden" name="currency_code" value="<?php echo $currency_code; ?>"/>
                    <tr>
                        <td><?php echo $entry_transaction_id; ?>:</td>
                        <td><input type="text" name="transaction_id" value="<?php echo $transaction_id; ?>" /></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_full_refund; ?>:</td>
                        <td>
                            <input type="hidden" name="refund_full" value="0"/>
                            <input type="checkbox" name="refund_full" id="refund_full" value="1" <?php echo ($refund_available == '' ? 'checked="checked"' : ''); ?> onchange="refundAmount();"/>
                        </td>
                    </tr>
                    <tr <?php echo ($refund_available == '' ? 'style="display:none;"' : ''); ?> id="partial_amount_row">
                        <td><?php echo $entry_amount; ?>:</td>
                        <td><input type="text" name="amount" value="<?php echo ($refund_available != '' ? $refund_available : ''); ?>" /></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_message; ?>:</td>
                        <td><textarea name="refund_message" id="paypal_refund_message" cols="40" rows="5"></textarea></td>
                    </tr>
                </table>
                <a style="float:right;" onclick="$('#form').submit();" class="button"><?php echo $btn_refund; ?></a>
            </form>
        </div>
    </div>
</div>
<?php echo $footer; ?>
<script type="text/javascript"><!--
    function refundAmount(){
        var valChecked = $('#refund_full').prop('checked');

        if(valChecked == true){
            $('#partial_amount_row').hide();
        }else{
            $('#partial_amount_row').show();
        }
    }
//--></script>