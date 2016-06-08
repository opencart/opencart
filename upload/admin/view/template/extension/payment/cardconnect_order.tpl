<h2><?php echo $text_payment_info; ?></h2>
<table class="table table-striped table-bordered">
  <tr>
    <td><?php echo $text_payment_method; ?></td>
    <td><?php echo $cardconnect_order['payment_method']; ?></td>
  </tr>
  <tr>
    <td><?php echo $text_reference; ?></td>
    <td><?php echo $cardconnect_order['retref']; ?></td>
  </tr>
  <tr>
    <td><?php echo $text_update; ?></td>
    <td><a class="button btn btn-primary btn-xs" id="button-inquire-all"><?php echo $button_inquire_all; ?></a> <span class="btn btn-primary btn-xs img_loading_inquire" style="display:none"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span></td>
  </tr>
  <tr>
    <td><?php echo $text_order_total; ?></td>
    <td><?php echo $cardconnect_order['total_formatted']; ?></td>
  </tr>
  <tr>
    <td><?php echo $text_total_captured; ?></td>
    <td id="cardconnect_total_captured"><?php echo $cardconnect_order['total_captured_formatted']; ?></td>
  </tr>
  <tr>
    <td><?php echo $text_capture_payment; ?></td>
    <td>
      <input type="text" style="width:80px" id="capture_amount" value="<?php echo $cardconnect_order['total']; ?>"/>
      <a class="button btn btn-primary btn-sm" id="button-capture"><?php echo $button_capture; ?></a> <span class="btn btn-primary btn-sm" id="img_loading_capture" style="display:none"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>
    </td>
  </tr>
  <tr>
    <td><?php echo $text_refund_payment; ?></td>
    <td>
      <input type="text" style="width:80px" id="refund_amount" <?php if ($cardconnect_order['total_captured'] < 1) { echo 'style="display:none"'; } ?> />
      <a class="button btn btn-primary btn-sm" id="button-refund" <?php if ($cardconnect_order['total_captured'] < 1) { echo 'style="display:none"'; } ?>><?php echo $button_refund; ?></a> <span class="btn btn-primary btn-sm" id="img_loading_refund" style="display:none"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>
     </td>
  </tr>
  <tr>
    <td><?php echo $text_void; ?></td>
    <td><a class="button btn btn-primary btn-xs" id="button-void-all"><?php echo $button_void_all; ?></a> <span class="btn btn-primary btn-xs" id="img_loading_void" style="display:none"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span></td>
  </tr>
  <tr>
    <td><?php echo $text_transactions; ?></td>
    <td><table class="table table-striped table-bordered" id="cardconnect_transactions">
        <thead>
          <tr>
            <td class="text-left"><strong><?php echo $text_column_type; ?></strong></td>
            <td class="text-left"><strong><?php echo $text_column_reference; ?></strong></td>
            <td class="text-left"><strong><?php echo $text_column_amount; ?></strong></td>
            <td class="text-left"><strong><?php echo $text_column_status; ?></strong></td>
            <td class="text-left"><strong><?php echo $text_column_date_modified; ?></strong></td>
            <td class="text-left"><strong><?php echo $text_column_date_added; ?></strong></td>
            <td class="text-left"><strong><?php echo $text_column_update; ?></strong></td>
            <td class="text-left"><strong><?php echo $text_column_void; ?></strong></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cardconnect_order['transactions'] as $transaction) { ?>
          <tr>
            <td class="text-left"><?php echo $transaction['type']; ?></td>
            <td class="text-left"><?php echo $transaction['retref']; ?></td>
            <td class="text-left"><?php echo $transaction['amount']; ?></td>
            <td class="text-left"><?php echo $transaction['status']; ?></td>
            <td class="text-left"><?php echo $transaction['date_modified']; ?></td>
            <td class="text-left"><?php echo $transaction['date_added']; ?></td>
            <td class="text-left"><a class="button btn btn-primary button-inquire btn-xs" data-inquire-retref="<?php echo $transaction['retref']; ?>"><?php echo $button_inquire; ?></a> <span class="btn btn-primary btn-xs img_loading_inquire" style="display:none"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span></td>
            <td class="text-left"><a class="button btn btn-primary button-void btn-xs" data-void-retref="<?php echo $transaction['retref']; ?>"><?php echo $button_void; ?></a> <span class="btn btn-primary btn-xs img_loading_void" style="display:none"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span></td>
          </tr>
          <?php } ?>
        </tbody>
      </table></td>
  </tr>
</table>
<script type="text/javascript"><!--
	$('#button-inquire-all').click(function() {
		$('.button-inquire').trigger('click');
	});

	$('#button-void-all').click(function() {
		$('.button-void').trigger('click');
	});

	$('#button-capture').click(function() {
		if (confirm('<?php echo $text_confirm_capture; ?>')) {
			$.ajax({
				type: 'POST',
				dataType: 'json',
				data: {'order_id': <?php echo $order_id; ?>, 'amount': $('#capture_amount').val()},
				url: 'index.php?route=extension/payment/cardconnect/capture&token=<?php echo $token; ?>',
				beforeSend: function() {
					$('#button-capture, #capture_amount').hide();
					$('#img_loading_capture').show();
					$('.cardconnect_message').remove();
				},
				success: function(json) {
					if (json['success']) {
						html = '';
						html += '<tr>';
						html += '<td class="text-left">Payment</td>';
						html += '<td class="text-left">' + json['retref'] + '</td>';
						html += '<td class="text-left">' + json['amount'] + '</td>';
						html += '<td class="text-left">' + json['status'] + '</td>';
						html += '<td class="text-left">' + json['date_modified'] + '</td>';
						html += '<td class="text-left">' + json['date_added'] + '</td>';
						html += '<td class="text-left">' + '<a class="button btn btn-primary button-inquire btn-xs" data-inquire-retref="' + json['retref'] + '"><?php echo $button_inquire; ?></a> <span class="btn btn-primary btn-xs img_loading_inquire" style="display:none"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>' + '</td>';
						html += '<td class="text-left">' + '<a class="button btn btn-primary button-void btn-xs" data-void-retref="' + json['retref'] + '"><?php echo $button_void; ?></a> <span class="btn btn-primary btn-xs img_loading_void" style="display:none"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>' + '</td>';
						html += '</tr>';

						$('#cardconnect_transactions').append(html);
						$('#cardconnect_total_captured').text(json['total_captured']);

						$('h2').after('<div class="alert alert-success cardconnect_message" style="display:none"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>').fadeIn();

						$('#refund_amount, #button-refund').show();
					}

					if (json['error']) {
						$('h2').after('<div class="alert alert-danger cardconnect_message" style="display:none"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>').fadeIn();
					}

					$('#capture_amount').val(0.00).show();
					$('#button-capture').show();
					$('#refund_amount').val(0.00);

					$('.cardconnect_message').fadeIn();
					$('#img_loading_capture').hide();
				}
			});
		}
	});

	$('#button-refund').click(function() {
		if (confirm('<?php echo $text_confirm_refund; ?>')) {
			$.ajax({
				type: 'POST',
				dataType: 'json',
				data: {'order_id': <?php echo $order_id; ?>, 'amount': $('#refund_amount').val()},
				url: 'index.php?route=extension/payment/cardconnect/refund&token=<?php echo $token; ?>',
				beforeSend: function() {
					$('#button-refund, #refund_amount').hide();
					$('#img_loading_refund').show();
					$('.cardconnect_message').remove();
				},
				success: function(json) {
					if (json['success']) {
						html = '';
						html += '<tr>';
						html += '<td class="text-left">Refund</td>';
						html += '<td class="text-left">' + json['retref'] + '</td>';
						html += '<td class="text-left">' + json['amount'] + '</td>';
						html += '<td class="text-left">' + json['status'] + '</td>';
						html += '<td class="text-left">' + json['date_modified'] + '</td>';
						html += '<td class="text-left">' + json['date_added'] + '</td>';
						html += '<td class="text-left">' + '<a class="button btn btn-primary button-inquire btn-xs" data-inquire-retref="' + json['retref'] + '"><?php echo $button_inquire; ?></a> <span class="btn btn-primary btn-xs img_loading_inquire" style="display:none"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>' + '</td>';
						html += '<td class="text-left">' + '<a class="button btn btn-primary button-void btn-xs" data-void-retref="' + json['retref'] + '"><?php echo $button_void; ?></a> <span class="btn btn-primary btn-xs img_loading_void" style="display:none"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>' + '</td>';
						html += '</tr>';

						$('#cardconnect_transactions').append(html);
						$('#cardconnect_total_captured').text(json['total_captured']);

						$('h2').after('<div class="alert alert-success cardconnect_message" style="display:none"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>').fadeIn();
					}

					if (json['error']) {
						$('h2').after('<div class="alert alert-danger cardconnect_message" style="display:none"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>').fadeIn();
					}

					$('#refund_amount').val(0.00).show();
					$('#button-refund').show();
					$('#capture_amount').val(0.00);

					$('.cardconnect_message').fadeIn();
					$('#img_loading_refund').hide();
				}
			});
		}
	});

	$(document).on('click', '.button-inquire', function(e) {
		var button = $(e.target);

		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: {'order_id': <?php echo $order_id; ?>, 'retref': $(this).data('inquire-retref')},
			url: 'index.php?route=extension/payment/cardconnect/inquire&token=<?php echo $token; ?>',
			beforeSend: function() {
				button.hide();
				button.next().show();
				$('.cardconnect_message').remove();
			},
			success: function(json) {
				if (json['success']) {
					$('*[data-inquire-retref="' + button.data('inquire-retref') + '"]').parent().prev().prev().prev().text(json['status']);

					$('*[data-inquire-retref="' + button.data('inquire-retref') + '"]').parent().prev().prev().text(json['date_modified']);

					$('h2').after('<div class="alert alert-success cardconnect_message" style="display:none"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
				}

				if (json['error']) {
					$('h2').after('<div class="alert alert-danger cardconnect_message" style="display:none"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
				}

				$('.button-inquire').show();

				$('.cardconnect_message').fadeIn();
				$('.img_loading_inquire').hide();
			}
		});
	});

	$(document).on('click', '.button-void', function(e) {
		var button = $(e.target);

		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: {'order_id': <?php echo $order_id; ?>, 'retref': $(this).data('void-retref')},
			url: 'index.php?route=extension/payment/cardconnect/void&token=<?php echo $token; ?>',
			beforeSend: function() {
				button.hide();
				button.next().show();
				$('.cardconnect_message').remove();
			},
			success: function(json) {
				if (json['success']) {
					$('*[data-void-retref="' + button.data('void-retref') + '"]').parent().prev().prev().prev().prev().text(json['status']);

					$('*[data-void-retref="' + button.data('void-retref') + '"]').parent().prev().prev().prev().text(json['date_modified']);

					$('h2').after('<div class="alert alert-success cardconnect_message" style="display:none"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
				}

				if (json['error']) {
					$('h2').after('<div class="alert alert-danger cardconnect_message" style="display:none"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
				}

				$('.button-void').show();

				$('.cardconnect_message').fadeIn();
				$('.img_loading_void').hide();
			}
		});
	});
//--></script>