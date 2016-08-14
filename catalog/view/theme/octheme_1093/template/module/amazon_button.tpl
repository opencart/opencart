<div class="panel panel-default">
  <div class="panel-body" style="text-align: <?php echo $align; ?>;"><span id="AmazonCheckoutWidgetModule<?php echo $module; ?>"></span></div>
</div>
<script type="text/javascript"><!--
new CBA.Widgets.InlineCheckoutWidget({
	merchantId: '<?php echo $merchant_id; ?>',
	buttonSettings: {
		color: '<?php echo $button_colour; ?>',
		background: '<?php echo $button_background; ?>',
		size: '<?php echo $button_size; ?>',
	},
	onAuthorize: function(widget) {
		var redirect = '<?php echo html_entity_decode($amazon_checkout); ?>';

		if (redirect.indexOf('?') == -1) {
			window.location = redirect + '?contract_id=' + widget.getPurchaseContractId();
		} else {
			window.location = redirect + '&contract_id=' + widget.getPurchaseContractId();
		}
	}
}).render('AmazonCheckoutWidgetModule<?php echo $module; ?>');
//--></script>