<h3><?php echo $text_payment_info; ?></h3>
<div class="kc-alert" style="display: none;"><i class="fa fa-check-circle"></i></div>
<div id="table-action"></div>

<script type="text/javascript"><!--
function getTransaction() {
	$.ajax({
		url: 'index.php?route=extension/payment/klarna_checkout/getTransaction&token=<?php echo $token; ?>',
		dataType: 'html',
		data: {
			order_id: '<?php echo $order_id; ?>'
		},
		beforeSend: function() {
			$('#button-filter').button('loading');

			$('#table-action').html('<i class="klarna_checkout-loading fa fa-spinner fa-spin fa-5x" style="text-align: center; margin: 0 auto; width: 100%; font-size: 5em;"></i>');
		},
		complete: function() {
			$('#button-filter').button('reset');
		},
		success: function(html) {
			$('#table-action').html(html);
		}
	});
}

getTransaction();
//--></script>