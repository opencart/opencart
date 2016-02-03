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
	var captureComplete;
	var voidTransaction = false;

	$.ajax({
		url: 'index.php?route=payment/pp_express/capture&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: 'amount=' + $('#paypal-capture-amount').val() + '&order_id=<?php echo $order_id; ?>&complete=' + $('#paypal_capture_complete').prop('checked'),
		beforeSend: function() {
			$('#button-capture').button('loading');
		},
		complete: function() {
			$('#button-capture').button('reset');
		},			
		success: function(json) {
			if (!json['error']) {
				html  = '<tr>';
				html += '  <td class="text-left">' + json['transaction_id'] + '</td>';
				html += '  <td class="text-left">' + json['amount'] + '</td>';
				html += '  <td class="text-left">' + json['payment_type'] + '</td>';
				html += '  <td class="text-left">' + json['payment_status'] + '</td>';
				html += '  <td class="text-left">' + json['pending_reason'] + '</td>';
				html += '  <td class="text-left">' + json['date_added'] + '</td>';
				html += '  <td class="text-left"><a href="' + json['view'] + '" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-default"><i class="fa fa-eye"></i></a>&nbsp;<a href="' + json['refund'] + '" data-toggle="tooltip" title="<?php echo $button_refund; ?>" class="btn btn-danger"><i class="fa fa-reply"></i></a></td>';
				html += '</tr>';

				$('#paypal_captured').text(data.data.captured);
				$('#paypal_capture_amount').val(data.data.remaining);
				$('#paypal_transactions').append(html);

				if (json['void'] != '') {
					html += '<tr>';
					html += '  <td class="text-left">' + json['void']['transaction_id'] + '</td>';
					html += '  <td class="text-left">' + json['void']['amount'] + '</td>';
					html += '  <td class="text-left">' + json['void']['payment_type'] + '</td>';
					html += '  <td class="text-left">' + json['void']['payment_status'] + '</td>';
					html += '  <td class="text-left">' + json['void']['pending_reason'] + '</td>';
					html += '  <td class="text-left">' + json['void']['date_added'] + '</td>';
					html += '  <td class="text-left"></td>';
					html += '</tr>';

					$('#paypal-transaction tbody').append(html);
				}

				if (json['status']) {
					$('#capture_status').text('<?php echo $text_complete; ?>');
					$('.paypal_capture').hide();
				}
			}
			
			if (json['error']) {
				alert(data.msg);

				if (data.failed_transaction) {
					html = '';
					html += '<tr>';
					html += '<td class="text-left"></td>';
					html += '<td class="text-left">' + json['failed_transaction']['amount'] + '</td>';
					html += '<td class="text-left"></td>';
					html += '<td class="text-left"></td>';
					html += '<td class="text-left"></td>';
					html += '<td class="text-left">' + json['failed_transaction']['date_added'] + '</td>';
					html += '<td class="text-left"><a onclick="resendTransaction(this); return false;" href="<?php echo $resend_link ?>&paypal_order_transaction_id=' + data.failed_transaction.paypal_order_transaction_id + '"><?php echo $text_resend ?></a></td>';
					html += '/<tr>';

					$('#paypal_transactions').append(html);
				}
			}
		}
	});
});

$('#button-void').on('click', function() {
	if (confirm('<?php echo addslashes($text_confirm_void); ?>')) {
		$.ajax({
			url: 'index.php?route=payment/pp_express/void&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: {
				'order_id': '<?php echo $order_id; ?>'
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

$('#paypal-transaction').delegate('button', 'click', function() {
	var element = this;
	
	$.ajax({
		url: $(element).attr('href'),
		dataType: 'json',
		beforeSend: function() {
			$(element).button('loading');
		},
		complete: function() {
			$(element).button('reset');
		},		
		success: function(data) {
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