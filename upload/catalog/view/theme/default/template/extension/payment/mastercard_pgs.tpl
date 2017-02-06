<?php if ($error_session) { ?>
	<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>&nbsp;<?php echo $error_session; ?></div>
<?php } else { ?>
	<div class="alert alert-danger" id="mastercard_pgs_error" style="display:none;"></div>
	<?php if ($warning_test_mode) { ?>
		<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i>&nbsp;<?php echo $warning_test_mode; ?></div>
	<?php } ?>
	<?php if ($cards) : ?>
	<div class="row">
		<div class="col-xs-12">
			<p><?php echo $text_select_card; ?></p>
			<select class="form-control" name="mastercard_pgs_token">
				<option value=""><?php echo $text_new_card; ?></option>
				<optgroup label="<?php echo $text_existing_card; ?>">
					<?php foreach ($cards as $card) : ?>
						<option value="<?php echo $card['token']; ?>"><?php echo $card['text']; ?></option>
					<?php endforeach; ?>
				</optgroup>
			</select>
		</div>
	</div>
	<?php endif; ?>
	<div class="buttons">
	  <div class="pull-right">
	    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" data-loading-text="<?php echo $text_loading; ?>" />
	  </div>
	</div>
	<script type="text/javascript">
		$('#mastercard_pgs_error').hide().html('');

		$('#button-confirm').on('click', function() {
			$(this).button('loading');

			if ($('select[name="mastercard_pgs_token"]').length && $('select[name="mastercard_pgs_token"]').val() !== '') {
				$.ajax({
					url: 'index.php?route=extension/payment/mastercard_pgs/checkout',
					data: {
						token: $('select[name="mastercard_pgs_token"]').val()
					},
					dataType: 'json',
					type: 'POST',
					beforeSend: function() {
						$('#mastercard_pgs_error').hide().html('');
					},
					success: function(json) {
						if (json.error) {
							$('#mastercard_pgs_error').html('<i class="fa fa-exclamation-circle"></i>&nbsp;' + json.error).show();
						} else if (json.redirect) {
							location = json.redirect;
						}
					},
			        error: function(xhr, ajaxOptions, thrownError) {
			        	$('#button-confirm').button('reset');
			        	alert(thrownError + " (" + xhr.statusText + "): " + xhr.responseText);
			        },
			        complete: function() {
			        	$('#button-confirm').button('reset');
			        }
				});
			} else {
				if (typeof Checkout == 'undefined') {
					$('#button-confirm').button('reset');
					alert("<?php echo $error_event_missing; ?>");
				} else {
					Checkout.configure(<?php echo $configuration_json; ?>);

					<?php if ($onclick == 'lightbox') { ?>
						Checkout.showLightbox();
					<?php } else { ?>
						Checkout.showPaymentPage();
					<?php } ?>
				}
			}
		});
	</script>
<?php } ?>