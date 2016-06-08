<fieldset>
  <legend><?php echo $text_transaction; ?></legend>
  <div id="paypal-transaction"></div>
</fieldset>

<fieldset>
  <legend><?php echo $text_payment; ?></legend>
  <table class="table table-bordered">
    <tr>
      <td><?php echo $text_capture_status; ?></td>
      <td id="capture-status"><?php echo $capture_status; ?></td>
    </tr>
    <tr>
      <td><?php echo $text_amount_authorised; ?></td>
      <td><?php echo $total; ?>
        <?php if ($capture_status != 'Complete') { ?>
        &nbsp;&nbsp;&nbsp;
        <button type="button" id="button-void" data-loading="<?php echo $text_loading; ?>" class="btn btn-danger"><?php echo $button_void; ?></button>
        <?php } ?></td>
    </tr>
    <tr>
      <td><?php echo $text_amount_captured; ?></td>
      <td id="paypal-captured"><?php echo $captured; ?></td>
    </tr>
    <tr>
      <td><?php echo $text_amount_refunded; ?></td>
      <td id="paypal-refund"><?php echo $refunded; ?></td>
    </tr>
  </table>
</fieldset>

<?php if ($capture_status != 'Complete') { ?>
	<fieldset id="capture-form">
		<legend><?php echo $tab_capture; ?></legend>
		<form id="paypal-capture" class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-capture-amount"><?php echo $entry_capture_amount; ?></label>
							<div class="col-sm-10">
								<input type="text" name="amount" value="<?php echo $capture_remaining; ?>" id="input-capture-amount" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-capture-complete"><?php echo $entry_capture_complete; ?></label>
							<div class="col-sm-10">
								<input type="checkbox" name="complete" value="1" id="input-capture-complete" class="form-control" />
							</div>
						</div>
						<div class="pull-right">
							<button type="button" id="button-capture" data-loading="<?php echo $text_loading; ?>" class="btn btn-primary"><?php echo $button_capture; ?></button>
						</div>
		</form>
	</fieldset>
<?php } ?>
<script type="text/javascript"><!--
	$('#paypal-transaction').load('index.php?route=extension/payment/pp_express/transaction&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

	$('#button-capture').on('click', function() {
		$.ajax({
			url: 'index.php?route=extension/payment/pp_express/capture&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
			type: 'post',
			dataType: 'json',
			data: 'amount=' + $('#input-capture-amount').val() + '&complete=' + ($('#paypal-capture-complete').prop('checked') == true ? 1 : 0),
			beforeSend: function() {
				$('#button-capture').button('loading');
			},
			complete: function() {
				$('#button-capture').button('reset');
			},
			success: function(json) {
				$('.alert').remove();

				if (json['error']) {
					$('#paypal-capture').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['success']) {
					$('#paypal-capture').prepend('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					$('#paypal-captured').text(json['captured']);
					$('#paypal-capture-amount').val(json['remaining']);

					if (json['capture_status']) {
						$('#capture-status').text(json['capture_status']);

						$('#button-void').remove();

						$('#capture-form').remove();
					}
				}

				$('#paypal-transaction').load('index.php?route=extension/payment/pp_express/transaction&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
			}
		});
	});

	$('#button-void').on('click', function() {
		if (confirm('<?php echo addslashes($text_confirm_void); ?>')) {
			$.ajax({
				url: 'index.php?route=extension/payment/pp_express/void&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
				dataType: 'json',
				beforeSend: function() {
					$('#button-void').button('loading');
				},
				complete: function() {
					$('#button-void').button('reset');
				},
				success: function(json) {
					$('.alert').remove();

					if (json['error']) {
						$('#paypal-capture').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}

					if (json['success']) {
						$('#capture-status').text(json['capture_status']);

						$('#button-void').remove();

						$('#capture-form').remove();
					}

					$('#paypal-transaction').load('index.php?route=extension/payment/pp_express/transaction&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
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
			success: function(json) {
				$('.alert').remove();

				if (json['error']) {
					$('#tab-pp-express').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['success']) {
					$('#paypal-transaction').load('index.php?route=extension/payment/pp_express/transaction&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
				}
			}
		});
	});
//--></script>