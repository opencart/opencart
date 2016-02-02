<h2><?php echo $text_payment; ?></h2>
<table class="table table-striped table-bordered">
  <tr>
    <td><?php echo $text_capture_status; ?></td>
    <td id="capture-status"><?php echo $capture_status; ?></td>
  </tr>
  <tr>
    <td><?php echo $text_amount_authorised; ?></td>
    <td><?php echo $total; ?>
      <?php if ($capture_status != 'Complete') { ?>
      &nbsp;&nbsp;
      <button type="button" id="button-void" class="btn btn-danger"><?php echo $button_void; ?></button>
      <?php } ?></td>
  </tr>
  <tr>
    <td><?php echo $text_amount_captured; ?></td>
    <td id="paypal-captured"><?php echo $captured; ?></td>
  </tr>
  <tr>
    <td><?php echo $text_amount_refunded; ?></td>
    <td id="paypal-refunded"><?php echo $refunded; ?></td>
  </tr>
  <?php if ($capture_status != 'Complete') { ?>
  <tr class="paypal-capture">
    <td><?php echo $text_capture_amount; ?></td>
    <td><label>
        <input type="checkbox" name="paypal_capture_complete" id="paypal-capture-complete" value="1" class="form-control" />
        <?php echo $text_complete_capture; ?></label>
      <div class="input-group"><span class="input-group-addon"><i class="fa fa-link"></i></span>
        <input type="text" value="<?php echo $remaining; ?>" size="10" id="paypal-capture-amount" class="form-control" />
        <button type="button" id="button-capture" class="btn btn-primary"><?php echo $button_capture; ?></button>
      </div></td>
  </tr>
  <?php } ?>
</table>
<h2><?php echo $text_transactions; ?></h2>
<table class="table table-striped table-bordered" id="paypal-transaction">
  <thead>
    <tr>
      <td class="text-left"><?php echo $column_transaction; ?></td>
      <td class="text-left"><?php echo $column_amount; ?></td>
      <td class="text-left"><?php echo $column_type; ?></td>
      <td class="text-left"><?php echo $column_status; ?></td>
      <td class="text-left"><?php echo $column_pending_reason; ?></td>
      <td class="text-left"><?php echo $column_date_added; ?></td>
      <td class="text-left"><?php echo $column_action; ?></td>
    </tr>
  </thead>
  <tbody>
    <?php foreach($transactions as $transaction) { ?>
    <tr>
      <td class="text-left"><?php echo $transaction['transaction_id']; ?></td>
      <td class="text-left"><?php echo $transaction['amount']; ?></td>
      <td class="text-left"><?php echo $transaction['payment_type']; ?></td>
      <td class="text-left"><?php echo $transaction['payment_status']; ?></td>
      <td class="text-left"><?php echo $transaction['pending_reason']; ?></td>
      <td class="text-left"><?php echo $transaction['date_added']; ?></td>
      <td class="text-left"><?php if ($transaction['transaction_id']) { ?>
        <a href="<?php echo $transaction['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-default"><i class="fa fa-eye"></i></a>
        <?php if ($transaction['payment_type'] == 'instant' && ($transaction['payment_status'] == 'Completed' || $transaction['payment_status'] == 'Partially-Refunded')) { ?>
        &nbsp;<a href="<?php echo $transaction['refund']; ?>" data-toggle="tooltip" title="<?php echo $button_refund; ?>" class="btn btn-danger"><i class="fa fa-reply"></i></a>
        <?php } ?>
        <?php } else { ?>
        &nbsp;
        <button type="button" value="<?php echo $transaction['resend']; ?>" data-toggle="tooltip" title="<?php echo $button_resend; ?>" class="btn btn-info"><i class="fa fa-refresh"></i></button>
        <?php } ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<script type="text/javascript"><!--
$('#button-capture').on('click', function() {
	var amt = $('#paypal-capture-amount').val();

	if (amt == '' || amt == 0) {
		alert('<?php echo $error_capture_amount; ?>');
		
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
			url: 'index.php?route=payment/pp_express/capture&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: {
				'amount': amt, 
				'order_id': <?php echo $order_id; ?>,
				'complete': captureComplete
			},
			beforeSend: function() {
				$('#button_capture').hide();
				$('#img_loading_capture').show();
			},
			success: function(json) {
				if (!json['error']) {
					html = '';

					html += '<tr>';
					html += '  <td class="text-left">'+data.data.transaction_id+'</td>';
					html += '  <td class="text-left">'+data.data.amount+'</td>';
					html += '  <td class="text-left">'+data.data.payment_type+'</td>';
					html += '  <td class="text-left">'+data.data.payment_status+'</td>';
					html += '  <td class="text-left">'+data.data.pending_reason+'</td>';
					html += '  <td class="text-left">'+data.data.date_added+'</td>';
					html += '  <td class="text-left"><a href="<?php echo $view_link; ?>&transaction_id='+data.data.transaction_id+'"><?php echo $text_view; ?></a>&nbsp;<a href="<?php echo $refund_link; ?>&transaction_id='+data.data.transaction_id+'"><?php echo $text_refund; ?></a></td>';
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
});

$('#button-void').on('click', function() {
	if (confirm('<?php echo addslashes($text_confirm_void); ?>')) {
		$.ajax({
			url: 'index.php?route=payment/pp_express/void&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: {
				'order_id': <?php echo $order_id; ?> 
			},
			beforeSend: function() {
				$('#button-void').button('loading');
			},
			complete: function() {
				$('#button-void').button('reset');
			},			
			success: function(json) {
				if (!json['error']) {
					html  = '<tr>';
					html += '  <td class="text-left"></td>';
					html += '  <td class="text-left"></td>';
					html += '  <td class="text-left"></td>';
					html += '  <td class="text-left">' + json['payment_status'] + '</td>';
					html += '  <td class="text-left"></td>';
					html += '  <td class="text-left">' + json['date_added'] + '</td>';
					html += '  <td class="text-left"></td>';
					html += '</tr>';
					
					$('#paypal-transaction').append(html);
					
					$('#capture-status').text('<?php echo $text_complete; ?>');
					
					$('.paypal_capture_live').hide();
				} else {
					alert(json['msg']);
				}
			}
		});
	}
});

$('#button-resend').on('click', function() {
//function resendTransaction(element) {
	
	$.ajax({
		url: $(element).attr('href'),
		type: 'get',
		dataType: 'json',
		beforeSend: function() {
			$(element).hide();
			$(element).after('<span class="btn btn-primary loading"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>');
		},

		success: function(data) {
			$(element).show();
			$('.loading').remove();

			if (json['error']) {
				alert(json['error']);
			}

			if (json['success']) {
				location.reload();
			}
		}
	});
});
//--></script>