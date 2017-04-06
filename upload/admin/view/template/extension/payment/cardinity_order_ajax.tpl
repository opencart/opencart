<?php if ($payment) { ?>
<table class="table table-bordered">
  <tr>
	<td><?php echo $column_refund_history; ?></td>
	<td>
		<table class="table table-bordered">
		  <thead>
			<tr>
			  <td class="text-left"><?php echo $column_date; ?></td>
			  <td class="text-left"><?php echo $column_amount; ?></td>
			  <td class="text-left"><?php echo $column_status; ?></td>
			  <td class="text-left"><?php echo $column_description; ?></td>
			</tr>
		  </thead>
		  <tbody>
			<?php if ($refunds) { ?>
			<?php foreach ($refunds as $refund) { ?>
			<tr>
			  <td><?php echo $refund['date_added']; ?></td>
			  <td><?php echo $refund['amount']; ?></td>
			  <td><?php echo $refund['status']; ?></td>
			  <td><?php echo $refund['description']; ?></td>
			</tr>
			<?php } ?>
			<?php } else { ?>
			<tr>
			  <td class="text-center" colspan="3"><?php echo $text_no_refund; ?></td>
			</tr>
			<?php } ?>
		  </tbody>
		</table>
	</td>
  </tr>
  <tr>
	<td><?php echo $column_action; ?></td>
	<td>
		<table class="table table-bordered">
		  <thead>
			<tr>
			  <td class="text-left"><?php echo $column_refund; ?></td>
			</tr>
		  </thead>
		  <tbody>
			<tr>
			  <td>
				  <?php if ($refund_action) { ?>
				  <label class="control-label">Amount:</label>
				  <input id="refund-amount" text="text" name="refund_amount" value="<?php echo $max_refund_amount; ?>" />
				  <label class="control-label">Description:</label>
				  <input id="refund-description" text="text" name="refund_description" value="" />
				  <a class="btn btn-primary button-command" data-type="refund"><?php echo $button_refund; ?></a>
				  <?php } else { ?>
				  <?php echo $text_na; ?>
				  <?php } ?>
			  </td>
			</tr>
		  </tbody>
		</table>
	</td>
  </tr>
</table>
<?php } else { ?>
Unable to find transaction for this order.
<?php } ?>

<script type="text/javascript"><!--
$('.button-command').on('click', function() {
	var confirm_text = '';

	<?php if ($symbol_left) { ?>
	confirm_text = '<?php echo $text_confirm_refund; ?> ' + '<?php echo $symbol_left; ?>' + $('#refund-amount').val();
	<?php } elseif($symbol_right) { ?>
	confirm_text = '<?php echo $text_confirm_refund; ?> ' + $('#refund-amount').val() + '<?php echo $symbol_right; ?>';
	<?php } ?>

	if (confirm(confirm_text)) {
		$.ajax({
			url: 'index.php?route=extension/payment/cardinity/refund&token=<?php echo $token; ?>',
			type: 'post',
			data: {
				payment_id: '<?php echo $payment_id; ?>',
				amount: $('#refund-amount').val(),
				description: $('#refund-description').val()
			},
			dataType: 'json',
			beforeSend: function() {
				$('#button-settle').button('loading');

				$('.alert').hide();

				$('.alert').removeClass('alert-success alert-danger');
			},
			complete: function() {
				$('#button-settle').button('reset');
			},
			success: function(json) {
				if (json.error) {
					$('.alert').show();

					$('.alert').addClass('alert-danger');

					$('.alert').html('<i class="fa fa-check-circle"></i> ' + json.error);
				}

				if (json.success) {
					$('.alert').show();

					$('.alert').addClass('alert-success');

					$('.alert').html('<i class="fa fa-exclamation-circle"></i> ' + json.success);
				}

				getPayment('<?php echo $payment_id; ?>');
			}
		});
	}
});
//--></script>