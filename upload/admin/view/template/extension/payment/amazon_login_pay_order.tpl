<h2><?php echo $text_payment_info; ?></h2>
<div class="alert alert-success" id="amazon_login_pay-transaction-msg" style="display:none;"></div>
<table class="table table-striped table-bordered">
  <tr>
	<td><?php echo $text_order_ref; ?></td>
	<td><?php echo $amazon_login_pay_order['amazon_order_reference_id']; ?></td>
  </tr>
  <tr>
	<td><?php echo $text_order_total; ?></td>
	<td><?php echo $amazon_login_pay_order['total_formatted']; ?></td>
  </tr>
  <tr>
	<td><?php echo $text_total_captured; ?></td>
	<td id="amazon_login_pay-total-captured"><?php echo $amazon_login_pay_order['total_captured_formatted']; ?></td>
  </tr>
  <tr>
	<td><?php echo $text_capture_status; ?></td>
	<td id="capture_status">
	  <?php if ($amazon_login_pay_order['capture_status'] == 1) { ?>
		  <span class="capture-text"><?php echo $text_yes; ?></span>
	  <?php } else { ?>
		  <span class="capture-text"><?php echo $text_no; ?></span>&nbsp;&nbsp;
		  <?php if ($amazon_login_pay_order['cancel_status'] == 0) { ?>
			  <input type="text" width="10" id="capture-amount" value="<?php echo $amazon_login_pay_order['total']; ?>"/>
			  <a class="button btn btn-primary" id="button-capture"><?php echo $button_capture; ?></a>
			  <span class="btn btn-primary" id="loading-capture" style="display:none;"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>
		  <?php } ?>
	  <?php } ?>
	</td>
  </tr>
  <tr>
	<td><?php echo $text_cancel_status; ?></td>
	<td id="cancel_status">
	  <?php if ($amazon_login_pay_order['cancel_status'] == 1) { ?>
		  <span class="cancel_text"><?php echo $text_yes; ?></span>
	  <?php } else { ?>
		  <span class="cancel_text"><?php echo $text_no; ?></span>&nbsp;&nbsp;
		  <?php if ($amazon_login_pay_order['total_captured'] == 0 && $amazon_login_pay_order['refund_status'] != 1) { ?>
		  <a class="button btn btn-primary" id="button-cancel"><?php echo $button_cancel; ?></a>
		  <span class="btn btn-primary" id="loading-cancel" style="display:none;"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>
		  <?php } ?>
	  <?php } ?>
	</td>
  </tr>
  <tr>
	<td><?php echo $text_refund_status; ?></td>
	<td id="refund_status">
	  <?php if ($amazon_login_pay_order['refund_status'] == 1) { ?>
		  <span class="refund_text"><?php echo $text_yes; ?></span>
	  <?php } else { ?>
		  <span class="refund_text"><?php echo $text_no; ?></span>&nbsp;&nbsp;
		  <?php if ($amazon_login_pay_order['total_captured'] > 0 && $amazon_login_pay_order['cancel_status'] == 0) { ?>
			  <input type="text" width="10" id="refund-amount" />
			  <a class="button btn btn-primary" id="button-refund"><?php echo $button_refund; ?></a>
		  <?php } else { ?>
			  <input type="text" width="10" id="refund-amount" style="display:none;"/>
			  <a class="button btn btn-primary" id="button-refund" style="display:none;"><?php echo $button_refund; ?></a>
		  <?php } ?>
		  <span class="btn btn-primary" id="loading-refund" style="display:none;"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>
	  <?php } ?>
	</td>
  </tr>
  <tr>
	<td><?php echo $text_transactions; ?>:</td>
	<td>
	  <table class="table table-striped table-bordered" id="amazon_login_pay-transactions">
		<thead>
		  <tr>
			<td class="text-left"><strong><?php echo $text_column_date_added; ?></strong></td>
			<td class="text-left"><strong><?php echo $text_column_type; ?></strong></td>
			<td class="text-left"><strong><?php echo $text_column_status; ?></strong></td>
			<td class="text-left"><strong><?php echo $text_column_authorization_id; ?></strong></td>
			<td class="text-left"><strong><?php echo $text_column_capture_id; ?></strong></td>
			<td class="text-left"><strong><?php echo $text_column_refund_id; ?></strong></td>
			<td class="text-left"><strong><?php echo $text_column_amount; ?></strong></td>
		  </tr>
		</thead>
		<tbody>
		  <?php foreach ($amazon_login_pay_order['transactions'] as $transaction) { ?>
			  <tr>
				<td class="text-left"><?php echo $transaction['date_added']; ?></td>
				<td class="text-left"><?php echo $transaction['type']; ?></td>
				<td class="text-left"><?php echo $transaction['status']; ?></td>
				<td class="text-left"><?php echo $transaction['amazon_authorization_id']; ?></td>
				<td class="text-left"><?php echo $transaction['amazon_capture_id']; ?></td>
				<td class="text-left"><?php echo $transaction['amazon_refund_id']; ?></td>
				<td class="text-left"><?php echo $transaction['amount']; ?></td>
			  </tr>
		  <?php } ?>
		</tbody>
	  </table>
	</td>
  </tr>
</table>
<script type="text/javascript"><!--
  $("#button-cancel").click(function () {
      if (confirm('<?php echo $text_confirm_cancel; ?>')) {
        $.ajax({
          type: 'POST',
          dataType: 'json',
          data: {'order_id': '<?php echo $order_id; ?>'},
          url: 'index.php?route=extension/payment/amazon_login_pay/cancel&token=<?php echo $token; ?>',
          beforeSend: function () {
            $('#button-cancel').hide();
            $('#loading-cancel').show();
            $('#amazon_login_pay-transaction-msg').hide();
          },
          success: function (data) {
            if (data.error == false) {
              var html = '';
              html += '<tr>';
              html += '<td class="text-left">' + data.data.date_added + '</td>';
              html += '<td class="text-left">' + data.data.type + '</td>';
              html += '<td class="text-left">' + data.data.status + '</td>';
              html += '<td class="text-left"></td>';
              html += '<td class="text-left"></td>';
              html += '<td class="text-left"></td>';
              html += '<td class="text-left">' + data.data.amount + '</td>';
              html += '</tr>';

              $('.cancel_text').text('<?php echo $text_yes; ?>');
              $('#amazon_login_pay-transactions').append(html);
              $('#button-capture').hide();
              $('#capture-amount').hide();

              if (data.msg != '') {
                $('#amazon_login_pay-transaction-msg').empty().html('<i class="fa fa-check-circle"></i> ' + data.msg).fadeIn();
              }
            }
            if (data.error == true) {
              alert(data.msg);
              $('#button-cancel').show();
            }

            $('#loading-cancel').hide();
          }
        });
      }
    });
    $("#button-capture").click(function () {
      if (confirm('<?php echo $text_confirm_capture; ?>')) {
        $.ajax({
          type: 'POST',
          dataType: 'json',
          data: {'order_id': '<?php echo $order_id; ?>', 'amount': $('#capture-amount').val()},
          url: 'index.php?route=extension/payment/amazon_login_pay/capture&token=<?php echo $token; ?>',
          beforeSend: function () {
            $('#button-capture').hide();
            $('#capture-amount').hide();
            $('#loading-capture').show();
            $('#amazon_login_pay-transaction-msg').hide();
          },
          success: function (data) {
            if (data.error == false) {
              var html = '';
              html += '<tr>';
              html += '<td class="text-left">' + data.data.date_added + '</td>';
              html += '<td class="text-left">' + data.data.type + '</td>';
              html += '<td class="text-left">' + data.data.status + '</td>';
              html += '<td class="text-left">' + data.data.amazon_authorization_id + '</td>';
              html += '<td class="text-left">' + data.data.amazon_capture_id + '</td>';
              html += '<td class="text-left"></td>';
              html += '<td class="text-left">' + data.data.amount + '</td>';
              html += '</tr>';

              $('#amazon_login_pay-transactions').append(html);
              $('#amazon_login_pay-total-captured').text(data.data.total);

              if (data.data.capture_status == 1) {
                $('#button-cancel').hide();
                $('.capture-text').text('<?php echo $text_yes; ?>');
              } else {
                $('#button-capture, #capture-amount').show();
                $('#capture-amount').val('0.00');
              }

              if (data.msg != '') {
                $('#amazon_login_pay-transaction-msg').empty().html('<i class="fa fa-check-circle"></i> ' + data.msg).fadeIn();
              }

              $('#button-refund').show();
              $('#refund-amount').val(0.00).show();
            }
            if (data.error == true) {
              alert(data.msg);
              $('#button-capture').show();
              $('#capture-amount').show();
            }

            $('#loading-capture').hide();
          }
        });
      }
    });
    $("#button-refund").click(function () {
      if (confirm('<?php echo $text_confirm_refund; ?>')) {
        $.ajax({
          type: 'POST',
          dataType: 'json',
          data: {'order_id': '<?php echo $order_id; ?>', 'amount': $('#refund-amount').val()},
          url: 'index.php?route=extension/payment/amazon_login_pay/refund&token=<?php echo $token; ?>',
          beforeSend: function () {
            $('#button-refund').hide();
            $('#refund-amount').hide();
            $('#loading-refund').show();
            $('#amazon_login_pay-transaction-msg').hide();
          },
          success: function (data) {
            var html = '';
            if (data.data != undefined) {
              $.each(data.data, function (index, value) {
                html += '<tr>';
                html += '<td class="text-left">' + value['date_added'] + '</td>';
                html += '<td class="text-left">' + value['type'] + '</td>';
                html += '<td class="text-left">' + value['status'] + '</td>';
                html += '<td class="text-left">' + value['amazon_authorization_id'] + '</td>';
                html += '<td class="text-left">' + value['amazon_capture_id'] + '</td>';
                html += '<td class="text-left">' + value['amazon_refund_id'] + '</td>';
                html += '<td class="text-left">' + value['amount'] + '</td>';
                html += '</tr>';
              });
              $('#amazon_login_pay-transactions').append(html);
            }
            $('#amazon_login_pay-total-captured').text(data.total_captured);

            if (data.refund_status == 1) {
              $('.refund_text').text('<?php echo $text_yes; ?>');
            } else {
              $('#button-refund').show();
              $('#refund-amount').val(0.00).show();
            }
            if (data.msg != '' && data.msg != undefined) {
              $('#amazon_login_pay-transaction-msg').empty().html('<i class="fa fa-check-circle"></i> ' + data.msg).fadeIn();
            }
            if (data.error == true) {
              var msg = '';
              $.each(data.error_msg, function (index, value) {
                msg += value + "\n";
              });
              alert(msg);
              $('#button-refund').show();
            }

            $('#loading-refund').hide();
          }
        });
      }
    });
//--></script>