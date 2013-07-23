<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <div class="box">
        <div class="heading">
            <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_refund; ?></h1>
            <div class="buttons"><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
        </div>
        <div class="content"> 
            <table class="form">
                <tr>
                    <td><?php echo $entry_transaction_reference ?></td>
                    <td><?php echo $transaction_reference ?></td>
                </tr>
                <tr>
                    <td><?php echo $entry_transaction_amount ?></td>
                    <td><?php echo $transaction_amount ?></td>
                </tr>
                <tr>
                    <td><?php echo $entry_refund_amount ?></td>
                    <td>
                        <input type="test" value="0.00" name="amount" />
                        <a class="button" onclick="refund()" id="button-refund"><?php echo $button_refund ?></a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
    
function refund(){
    var amount = $('input[name="amount"]').val();
    
    $.ajax({
        type:'POST',
        dataType: 'json',
        data: {'transaction_reference': '<?php echo $transaction_reference; ?>', 'amount' : amount },
        url: 'index.php?route=payment/pp_payflow_iframe/do_refund&token=<?php echo $token; ?>',
        
        beforeSend: function(){
            $('#button-refund').after('<img src="view/image/loading.gif" class="loading" />');
            $('#button-refund').hide();
        },
        
        success: function(data){
            if(!data.error){
                alert(data.success);
                $('input[name="amount"]').val('0.00');
            }

            if(data.error){
                alert(data.error);
            }
            
            $('#button-refund').show();
            $('.loading').remove();
        }
    });
}
    
//--></script>
<?php echo $footer; ?>