<h2><?php echo $text_payment_info; ?></h2>
<div class="alert alert-success" id="eway-transaction-msg" style="display:none;"></div>
<table class="table table-striped table-bordered">
  <tr>
    <td><?php echo $text_order_total; ?></td>
    <td  colspan="2"><?php echo $eway_order['total_formatted']; ?></td>
  </tr>
  <tr>
    <td><?php echo $text_total_captured; ?></td>
    <td id="eway-total-captured"><?php echo $eway_order['total_captured_formatted']; ?></td>
  <td>
      <?php if (($eway_order['void_status'] == 0) && ($eway_order['capture_status'] == 0) ) { ?>
      <input type="text" name="eway_capture_amount" placeholder="<?php echo $eway_order['uncaptured']; ?>" id="eway-capture-amount" class="" />
        <a class="button btn btn-primary" id="btn-capture"><?php echo $btn_capture; ?></a>
        <span class="btn btn-primary" id="img-loading-capture" style="display:none;"><i class="fa fa-cog fa-spin fa-lg"></i></span>
        <?php } ?>
  </td>
  </tr>
  <tr>
    <td><?php echo $text_total_refunded; ?></td>
    <td id="eway-total-refunded"><?php echo $eway_order['total_refunded_formatted']; ?></td>
    <td>
      <?php if ($eway_order['refund_status'] == 0 && $eway_order['capture_status'] == 1) { ?>
        <input type="text" name="eway_refund_amount" placeholder="<?php echo $eway_order['unrefunded']; ?>" id="eway-refund-amount" class="" />
        <a class="button btn btn-primary" id="btn-refund"><?php echo $btn_refund; ?></a>
        <span class="btn btn-primary" id="img-loading-refund" style="display:none;"><i class="fa fa-cog fa-spin fa-lg"></i></span>
      <?php } ?>
    </td>
  </tr>
  <tr>
    <td><?php echo $text_transactions; ?>:</td>
    <td colspan="2">
      <table class="table table-striped table-bordered" id="eway-transactions">
        <thead>
          <tr>
            <td class="text-left"><strong><?php echo $text_column_transactionid; ?></strong></td>
            <td class="text-left"><strong><?php echo $text_column_created; ?></strong></td>
            <td class="text-left"><strong><?php echo $text_column_type; ?></strong></td>
            <td class="text-left"><strong><?php echo $text_column_amount; ?></strong></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach($eway_order['transactions'] as $transaction) { ?>
            <tr>
              <td class="text-left"><?php echo $transaction['transaction_id']; ?></td>
              <td class="text-left"><?php echo $transaction['created']; ?></td>
              <td class="text-left"><?php echo $transaction['type']; ?></td>
              <td class="text-left"><?php echo $transaction['amount']; ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </td>
  </tr>
</table>

<script type="text/javascript"><!--
	$("#btn-refund").bind('click', function () {
		if ($('#eway-refund-amount').val() != '' && confirm('<?php echo $text_confirm_refund; ?>')) {
			$.ajax({
				type:'POST',
				dataType: 'json',
				data: {
					'order_id': <?php echo $order_id; ?>,
					'refund_amount': $("#eway-refund-amount").val()
				},
				url: 'index.php?route=extension/payment/eway/refund&token=<?php echo $token; ?>',
				beforeSend: function(xhr, opts) {
					$('#btn-refund').hide();
					$('#img-loading-refund').show();
					$('#eway-transaction-msg').hide();
					$('#eway-refund-amount').hide();
				},
				success: function(data) {
					if (data.error == false) {
						html = '';
						html += '<tr>';
						html += '<td class="text-left">'+data.data.transactionid+'</td>';
						html += '<td class="text-left">'+data.data.created+'</td>';
						html += '<td class="text-left">refund</td>';
						html += '<td class="text-left">'+data.data.amount+'</td>';
						html += '</tr>';
						$('#eway-transactions tr:last').after(html);

						$('#eway-total-refunded').text(data.data.total_refunded_formatted);

						if (data.data.refund_status != 1) {
							$('#btn-refund').show();
							$('#eway-refund-amount').show();
							$('#eway-refund-amount').val('');
							$('#eway-refund-amount').attr('placeholder',data.data.remaining);
						}

						if (data.message != '') {
							$('#eway-transaction-msg').empty().html('<i class="fa fa-check-circle"></i> '+data.message).fadeIn();
						}
					}
					if (data.error == true) {
						alert(data.message);
						$('#btn-refund').show();
						$('#eway-refund-amount').show();
					}

					$('#img-loading-refund').hide();
				}
			});
		}
	});
//-->
</script>
<script type="text/javascript"><!--
	$("#btn-capture").bind('click', function () {
		if ($('#eway-capture-amount').val() != '' && confirm('<?php echo $text_confirm_capture; ?>')) {
			$.ajax({
				type:'POST',
				dataType: 'json',
				data: {
					'order_id': <?php echo $order_id; ?>,
					'capture_amount': $("#eway-capture-amount").val()
				},
				url: 'index.php?route=extension/payment/eway/capture&token=<?php echo $token; ?>',
				beforeSend: function(xhr, opts) {
					$('#btn-capture').hide();
					$('#img-loading-capture').show();
					$('#eway-transaction-msg').hide();
					$('#eway-capture-amount').hide();
				},
				success: function(data) {
					if (data.error == false) {
						html = '';
						html += '<tr>';
						html += '<td class="text-left">'+data.data.transactionid+'</td>';
						html += '<td class="text-left">'+data.data.created+'</td>';
						html += '<td class="text-left">payment</td>';
						html += '<td class="text-left">'+data.data.amount+'</td>';
						html += '</tr>';
						$('#eway-transactions tr:last').after(html);

						$('#eway-total-captured').text(data.data.total_captured_formatted);

						if (data.data.capture_status != 1) {
							$('#btn-capture').show();
							$('#eway-capture-amount').show();
							$("#eway-capture-amount").val('');
							$("#eway-capture-amount").attr('placeholder',data.data.remaining);
						}

						if (data.message != '') {
							$('#eway-transaction-msg').empty().html('<i class="fa fa-check-circle"></i> '+data.message).fadeIn();
						}
					}
					if (data.error == true) {
						alert(data.message);
						$('#btn-capture').show();
						$('#eway-capture-amount').show();
					}

					$('#img-loading-capture').hide();
				}
			});
		}
	});
//-->
</script>