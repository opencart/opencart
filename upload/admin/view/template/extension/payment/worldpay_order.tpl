<h2><?php echo $text_payment_info; ?></h2>
<div class="alert alert-success" id="worldpay_transaction_msg" style="display:none;"></div>
<table class="table table-striped table-bordered">
  <tr>
	<td><?php echo $text_order_ref; ?></td>
	<td><?php echo $worldpay_order['order_code']; ?></td>
  </tr>
  <tr>
	<td><?php echo $text_order_total; ?></td>
	<td><?php echo $worldpay_order['total_formatted']; ?></td>
  </tr>
  <tr>
	<td><?php echo $text_total_released; ?></td>
	<td id="worldpay_total_released"><?php echo $worldpay_order['total_released_formatted']; ?></td>
  </tr>
  <tr>
	<td><?php echo $text_refund_status; ?></td>
	<td id="refund_status">
	  <?php if ($worldpay_order['refund_status'] == 1) { ?>
		  <span class="refund_text"><?php echo $text_yes; ?></span>
	  <?php } else { ?>
		  <span class="refund_text"><?php echo $text_no; ?></span>&nbsp;&nbsp;

		  <?php if ($worldpay_order['total_released'] > 0) { ?>
			  <input type="text" width="10" id="refund_amount" />
			  <a class="button btn btn-primary" id="btn_refund"><?php echo $btn_refund; ?></a>
			  <span class="btn btn-primary" id="img_loading_refund" style="display:none;"><i class="fa fa-cog fa-spin fa-lg"></i></span>
		  <?php } ?>
	  <?php } ?>
	</td>
  </tr>
  <tr>
	<td><?php echo $text_transactions; ?>:</td>
	<td>
	  <table class="table table-striped table-bordered" id="worldpay_transactions">
		<thead>
		  <tr>
			<td class="text-left"><strong><?php echo $text_column_date_added; ?></strong></td>
			<td class="text-left"><strong><?php echo $text_column_type; ?></strong></td>
			<td class="text-left"><strong><?php echo $text_column_amount; ?></strong></td>
		  </tr>
		</thead>
		<tbody>
		  <?php foreach ($worldpay_order['transactions'] as $transaction) { ?>
			  <tr>
				<td class="text-left"><?php echo $transaction['date_added']; ?></td>
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
    $("#btn_refund").click(function () {
      if (confirm('<?php echo $text_confirm_refund ?>')) {
        $.ajax({
          type: 'POST',
          dataType: 'json',
          data: {'order_id': <?php echo $order_id; ?>, 'amount': $('#refund_amount').val()},
          url: 'index.php?route=payment/worldpay/refund&token=<?php echo $token; ?>',
          beforeSend: function () {
            $('#btn_refund').hide();
            $('#refund_amount').hide();
            $('#img_loading_refund').show();
            $('#worldpay_transaction_msg').hide();
          },
          success: function (data) {
            if (data.error == false) {
              html = '';
              html += '<tr>';
              html += '<td class="text-left">' + data.data.created + '</td>';
              html += '<td class="text-left">refund</td>';
              html += '<td class="text-left">' + data.data.amount + '</td>';
              html += '</tr>';

              $('#worldpay_transactions').append(html);
              $('#worldpay_total_released').text(data.data.total_released);

              if (data.data.refund_status == 1) {
                $('.refund_text').text('<?php echo $text_yes; ?>');
              } else {
                $('#btn_refund').show();
                $('#refund_amount').val(0.00).show();
              }

              if (data.msg != '') {
                $('#worldpay_transaction_msg').empty().html('<i class="fa fa-check-circle"></i> ' + data.msg).fadeIn();
              }
            }
            if (data.error == true) {
              alert(data.msg);
              $('#btn_refund').show();
            }

            $('#img_loading_refund').hide();
          }
        });
      }
    });
//--></script>