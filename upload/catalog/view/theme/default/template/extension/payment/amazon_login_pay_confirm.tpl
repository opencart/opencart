<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div class="container"><?php echo $content_top; ?>
	<form class="payment-form" method="POST" action="<?php echo $process_order; ?>">
		<div style="text-align:center;">
			<h3><?php echo $heading_confirm; ?></h3>
			<?php if(isset($amazon_login_pay_test)){ ?>
			<label>Debug Error Code     :</label>
			<div id="errorCode_address"></div>
			<br>
			<label>Debug Error Message  :</label>
			<div id="errorMessage_address"></div>
			<?php } ?>
			<div style="display: inline-block; width: 400px; height: 185px;" id="readOnlyAddressBookWidgetDiv"></div>
			<br>
			<?php if(isset($amazon_login_pay_test)){ ?>
			<label>Debug Error Code     :</label>
			<div id="errorCode_wallet"></div>
			<br>
			<label>Debug Error Message  :</label>
			<div id="errorMessage_wallet"></div>
			<?php } ?>
			<div style="display: inline-block; width: 400px; height: 185px;" id="readOnlyWalletWidgetDiv"></div>
		</div>
		<div style="clear: both;"></div>
	</form>
	<div class="checkout-product" style="margin-top: 15px">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<td class="text-left"><?php echo $column_name; ?></td>
					<td class="text-left"><?php echo $column_model; ?></td>
					<td class="text-right"><?php echo $column_quantity; ?></td>
					<td class="text-right"><?php echo $column_price; ?></td>
					<td class="text-right"><?php echo $column_total; ?></td>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($products as $product) { ?>
					<tr>
						<td class="text-left"><?php echo $product['name']; ?>
							<?php foreach ($product['option'] as $option) { ?>
								<br />
								&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
							<?php } ?></td>
						<td class="text-left"><?php echo $product['model']; ?></td>
						<td class="text-right"><?php echo $product['quantity']; ?></td>
						<td class="text-right"><?php echo $product['price']; ?></td>
						<td class="text-right"><?php echo $product['total']; ?></td>
					</tr>
				<?php } ?>
			</tbody>
			<tfoot>
				<?php foreach ($totals as $total) { ?>
					<tr>
						<td colspan="4" class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>
						<td class="text-right"><?php echo $total['text']; ?></td>
					</tr>
				<?php } ?>
			</tfoot>
		</table>
	</div>
	<div class="buttons">
		<div class="pull-left">
			<a href="<?php echo $back; ?>" class="btn btn-primary"><?php echo $text_back; ?></a>
		</div>
		<div class="pull-right">
			<input class="btn btn-primary" id="confirm-button" type="submit" value="<?php echo $text_confirm; ?>" />
		</div>
	</div>
	<?php echo $content_bottom; ?>
</div>
<script>
	$(document).ready(function() {
		amazon.Login.setClientId('<?php echo $amazon_login_pay_client_id; ?>');
		new OffAmazonPayments.Widgets.AddressBook({
			sellerId: '<?php echo $amazon_login_pay_merchant_id; ?>',
			amazonOrderReferenceId: '<?php echo $AmazonOrderReferenceId; ?>',
			displayMode: "Read",
			design: {
				designMode: 'responsive'
			},
			onError: function(error) {
				document.getElementById("errorCode_address").innerHTML = error.getErrorCode();
				document.getElementById("errorMessage_address").innerHTML = error.getErrorMessage();
			}
		}).bind("readOnlyAddressBookWidgetDiv");


		new OffAmazonPayments.Widgets.Wallet({
			sellerId: '<?php echo $amazon_login_pay_merchant_id; ?>',
			amazonOrderReferenceId: '<?php echo $AmazonOrderReferenceId; ?>',
			displayMode: "Read",
			design: {
				designMode: 'responsive'
			},
			onError: function(error) {
				document.getElementById("errorCode_wallet").innerHTML = error.getErrorCode();
				document.getElementById("errorMessage_wallet").innerHTML = error.getErrorMessage();
			}
		}).bind("readOnlyWalletWidgetDiv");



		$('#confirm-button').click(function() {
			if ($(this).attr('disabled') != 'disabled') {
				$('.payment-form').submit();
			}

			$(this).attr('disabled', 'disabled');
		});
	});
//--></script>
<?php echo $footer; ?>