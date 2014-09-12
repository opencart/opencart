<h2><?php echo $text_payment_info; ?></h2>
<table class="table table-striped table-bordered">
  <tr>
    <td><?php echo $text_capture_status; ?></td>
    <td id="capture_status"><?php echo $paypal_order['capture_status']; ?></td>
  </tr>
  <tr>
    <td><?php echo $text_amount_auth; ?></td>
    <td><?php echo $paypal_order['total']; ?>
      <?php if ($paypal_order['capture_status'] != 'Complete') { ?>
      &nbsp;&nbsp; <a onclick="doVoid();" class="button paypal_capture btn btn-primary" id="button-void"><?php echo $button_void; ?></a> <span class="btn btn-primary" id="img_loading_void" style="display:none;"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>
      <?php } ?></td>
  </tr>
  <tr>
    <td><?php echo $text_amount_captured; ?></td>
    <td id="paypal_captured"><?php echo $paypal_order['captured']; ?></td>
  </tr>
  <tr>
    <td><?php echo $text_amount_refunded; ?></td>
    <td id="paypal_refunded"><?php echo $paypal_order['refunded']; ?></td>
  </tr>
  <?php if ($paypal_order['capture_status'] != 'Complete') { ?>
  <tr class="paypal_capture">
    <td><?php echo $text_capture_amount; ?></td>
    <td><p>
        <input type="checkbox" name="paypal_capture_complete" id="paypal_capture_complete" value="1"/>
        <?php echo $text_complete_capture; ?></p>
      <p>
        <input type="text" size="10" id="paypal_capture_amount" value="<?php echo $paypal_order['remaining']; ?>"/>
        <a class="btn btn-primary" onclick="capture();" id="button_capture"><?php echo $button_capture; ?></a> <span class="btn btn-primary" id="img_loading_capture" style="display:none;"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span> </p></td>
  </tr>
  <?php } ?>
  <tr>
    <td><?php echo $text_transactions; ?>:</td>
    <td><table class="table table-striped table-bordered" id="paypal_transactions">
        <thead>
          <tr>
            <td class="text-left"><strong><?php echo $column_trans_id; ?></strong></td>
            <td class="text-left"><strong><?php echo $column_amount; ?></strong></td>
            <td class="text-left"><strong><?php echo $column_type; ?></strong></td>
            <td class="text-left"><strong><?php echo $column_status; ?></strong></td>
            <td class="text-left"><strong><?php echo $column_pend_reason; ?></strong></td>
            <td class="text-left"><strong><?php echo $column_date_added; ?></strong></td>
            <td class="text-left"><strong><?php echo $column_action; ?></strong></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach($paypal_order['transactions'] as $transaction) { ?>
          <tr>
            <td class="text-left"><?php echo $transaction['transaction_id']; ?></td>
            <td class="text-left"><?php echo $transaction['amount']; ?></td>
            <td class="text-left"><?php echo $transaction['payment_type']; ?></td>
            <td class="text-left"><?php echo $transaction['payment_status']; ?></td>
            <td class="text-left"><?php echo $transaction['pending_reason']; ?></td>
            <td class="text-left"><?php echo $transaction['date_added']; ?></td>
            <td class="text-left"><?php if ($transaction['transaction_id']) { ?>
              <a href="<?php echo $view_link .'&transaction_id='.$transaction['transaction_id']; ?>"><?php echo $text_view; ?></a>
              <?php if ($transaction['payment_type'] == 'instant' && ($transaction['payment_status'] == 'Completed'|| $transaction['payment_status'] == 'Partially-Refunded')) { ?>
              &nbsp;<a href="<?php echo $refund_link .'&transaction_id='.$transaction['transaction_id']; ?>"><?php echo $text_refund; ?></a>
              <?php } ?>
              <?php } else { ?>
              <a onclick="resendTransaction(this); return false;" href="<?php echo $resend_link . '&paypal_order_transaction_id=' . $transaction['paypal_order_transaction_id']; ?>"><?php echo $text_resend; ?></a>
              <?php } ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table></td>
  </tr>
</table>
<script type="text/javascript"><!--
    function capture() {
        var amt = $('#paypal_capture_amount').val();

        if (amt == '' || amt == 0) {
            alert('<?php echo $error_capture_amt; ?>');
            return false;
        } else {
            var captureComplete;
            var voidTransaction = false;

            if ($('#paypal_capture_complete').prop('checked') == true) {
                captureComplete = 1;
            } else {
                captureComplete = 0;
            }

            $.ajax({
                type:'POST',
                dataType: 'json',
                data: {'amount':amt, 'order_id':<?php echo $order_id; ?>, 'complete': captureComplete},
                url: 'index.php?route=payment/pp_express/capture&token=<?php echo $token; ?>',
                beforeSend: function() {
                    $('#button_capture').hide();
                    $('#img_loading_capture').show();
                },
                success: function(data) {
                    if (data.error == false) {
                        html = '';

                        html += '<tr>';
                            html += '<td class="text-left">'+data.data.transaction_id+'</td>';
                            html += '<td class="text-left">'+data.data.amount+'</td>';
                            html += '<td class="text-left">'+data.data.payment_type+'</td>';
                            html += '<td class="text-left">'+data.data.payment_status+'</td>';
                            html += '<td class="text-left">'+data.data.pending_reason+'</td>';
                            html += '<td class="text-left">'+data.data.date_added+'</td>';
                            html += '<td class="text-left">';
                                html += '<a href="<?php echo $view_link; ?>&transaction_id='+data.data.transaction_id+'"><?php echo $text_view; ?></a>';
                                html += '&nbsp;<a href="<?php echo $refund_link; ?>&transaction_id='+data.data.transaction_id+'"><?php echo $text_refund; ?></a>';
                            html += '</td>';
                        html += '</tr>';

                        $('#paypal_captured').text(data.data.captured);
                        $('#paypal_capture_amount').val(data.data.remaining);
                        $('#paypal_transactions').append(html);

                        if (data.data.void != '') {
                            html += '<tr>';
                                html += '<td class="text-left">'+data.data.void.transaction_id+'</td>';
                                html += '<td class="text-left">'+data.data.void.amount+'</td>';
                                html += '<td class="text-left">'+data.data.void.payment_type+'</td>';
                                html += '<td class="text-left">'+data.data.void.payment_status+'</td>';
                                html += '<td class="text-left">'+data.data.void.pending_reason+'</td>';
                                html += '<td class="text-left">'+data.data.void.date_added+'</td>';
                                html += '<td class="text-left"></td>';
                            html += '</tr>';

                            $('#paypal_transactions').append(html);
                        }

                        if (data.data.status == 1) {
                            $('#capture_status').text('<?php echo $text_complete; ?>');
                            $('.paypal_capture').hide();
                        }
                    }
                    if (data.error == true) {
                        alert(data.msg);

                        if (data.failed_transaction) {
                            html = '';
                            html += '<tr>';
                            html += '<td class="text-left"></td>';
                            html += '<td class="text-left">' + data.failed_transaction.amount + '</td>';
                            html += '<td class="text-left"></td>';
                            html += '<td class="text-left"></td>';
                            html += '<td class="text-left"></td>';
                            html += '<td class="text-left">' + data.failed_transaction.date_added + '</td>';
                            html += '<td class="text-left"><a onclick="resendTransaction(this); return false;" href="<?php echo $resend_link ?>&paypal_order_transaction_id=' + data.failed_transaction.paypal_order_transaction_id + '"><?php echo $text_resend ?></a></td>';
                            html += '/<tr>';

                            $('#paypal_transactions').append(html);
                        }
                    }

                    $('#button_capture').show();
                    $('#img_loading_capture').hide();
                }
            });
        }
    }

    function doVoid() {
        if (confirm('<?php echo $text_confirm_void; ?>')) {
            $.ajax({
                type:'POST',
                dataType: 'json',
                data: {'order_id':<?php echo $order_id; ?> },
                url: 'index.php?route=payment/pp_express/void&token=<?php echo $token; ?>',
                beforeSend: function() {
                    $('#button-void').hide();
                    $('#img_loading_void').show();
                },
                success: function(data) {
                    if (data.error == false) {
                        html = '';
                        html += '<tr>';
                            html += '<td class="text-left"></td>';
                            html += '<td class="text-left"></td>';
                            html += '<td class="text-left"></td>';
                            html += '<td class="text-left">'+data.data.payment_status+'</td>';
                            html += '<td class="text-left"></td>';
                            html += '<td class="text-left">'+data.data.date_added+'</td>';
                            html += '<td class="text-left"></td>';
                        html += '</tr>';

                        $('#paypal_transactions').append(html);
                        $('#capture_status').text('<?php echo $text_complete; ?>');
                        $('.paypal_capture_live').hide();
                    }
                    if (data.error == true) {
                        alert(data.msg);
                    }
                    $('#button-void').show();
                    $('#img_loading_void').hide();
                }
            });
        }
    }

    function resendTransaction(element) {
        $.ajax({
            type:'GET',
            dataType: 'json',
            url: $(element).attr('href'),

            beforeSend: function() {
                $(element).hide();
                $(element).after('<span class="btn btn-primary loading"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>');
            },

            success: function(data) {
                $(element).show();
                $('.loading').remove();

                if (data.error) {
                    alert(data.error);
                }

                if (data.success) {
                    location.reload();
                }
            }
        });
    }
//--></script>