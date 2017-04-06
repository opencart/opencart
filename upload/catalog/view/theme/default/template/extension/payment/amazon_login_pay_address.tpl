<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div class="container"><?php echo $content_top; ?>
	<div style="text-align:center;">
		<h3><?php echo $heading_address; ?></h3>
		<?php if(isset($amazon_login_pay_test)){ ?>
		<label>Debug Error Code     :</label>
		<div id="errorCode"></div>
		<br>
		<label>Debug Error Message  :</label>
		<div id="errorMessage"></div>
		<?php } ?>
		<div style="margin: 0 auto; width: 400px; height: 228px;" id="addressBookWidgetDiv">
		</div>
		<div style="margin: 5px auto 0; width: 180px;">
			<div class="shipping-methods amazon-payments-box"></div>
		</div>
	</div>
	<div style="clear: both;"></div>
	<div class="buttons">
		<div class="pull-left">
			<a href="<?php echo $cart; ?>" class="btn btn-primary"><?php echo $text_cart; ?></a>
		</div>
		<div class="pull-right">
			<input class="btn btn-primary" id="continue-button" type="submit" value="<?php echo $text_continue; ?>" />
		</div>
	</div>
	<input type="hidden" name="addressSelected" value="0" />
	<?php echo $content_bottom; ?>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
		amazon.Login.setClientId('<?php echo $amazon_login_pay_client_id; ?>');

		$('#continue-button').click(function() {
			$('div.warning').remove();
			if ($('input[name="addressSelected"]').val() == '0') {
				$('#addressBookWidgetDiv').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i><?php echo $error_shipping_address; ?></div>');
			} else if ($('input[name="shipping_method"]:checked').length == 0) {
				$('#addressBookWidgetDiv').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i><?php echo $error_shipping; ?></div>');
			} else {
				$.ajax({
					url: 'index.php?route=extension/payment/amazon_login_pay/setshipping',
					type: 'post',
					data: $('input[name="shipping_method"]:checked'), dataType: 'json',
					success: function(json) {
						location = json['redirect'];
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		});

		new OffAmazonPayments.Widgets.AddressBook({
			sellerId: '<?php echo $amazon_login_pay_merchant_id; ?>',
			onOrderReferenceCreate: function(orderReference) {
				window.AmazonOrderReferenceId = orderReference.getAmazonOrderReferenceId();
			},
			onAddressSelect: function(orderReference) {
				$('input[name="addressSelected"]').val('1');
				$('div.warning').remove();
				$('div.shipping-methods').html('');

				$.get('<?php echo $shipping_quotes; ?>&AmazonOrderReferenceId=' + AmazonOrderReferenceId, {}, function(data) {
					$('.shipping-methods').html('');

					if (data.error) {
						$('#addressBookWidgetDiv').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>' + data.error + '</div>');
					} else if (data.quotes) {
						var html = '';

						$.each(data.quotes, function(code, shippingMethod) {
							html += '<p><strong>' + shippingMethod.title + '</strong></p>';

							$.each(shippingMethod.quote, function(i, quote) {
								html += '<div class="radio">';
								html += '<label>';

								if (data.selected == quote.code) {
									html += '<input type="radio" name="shipping_method" value="' + quote.code + '" id="' + quote.code + '" checked="checked" />';
								} else {
									html += '<input type="radio" name="shipping_method" value="' + quote.code + '" id="' + quote.code + '" />';
								}

								html += quote.title + ' - ';
								html += quote.text;
								html += '</label>';
								html += '</div>';
							});
						});
						$('.shipping-methods').html(html);

						if ($('input[name="shipping_method"]:checked').length == 0) {
							$('input[name="shipping_method"]:first').attr('checked', 'checked');
						}
					}
				}, 'json');
			},
			design: {
				designMode: 'responsive'},
			onError: function(error) {
				document.getElementById("errorCode").innerHTML = error.getErrorCode();
				document.getElementById("errorMessage").innerHTML = error.getErrorMessage();
			}
		}).bind("addressBookWidgetDiv");

	});
	//--></script>
<?php echo $footer; ?>
