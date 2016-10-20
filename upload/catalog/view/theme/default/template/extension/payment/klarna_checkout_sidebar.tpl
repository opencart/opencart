<style>

.table-klarna {
    width: 100%;
}

.table-klarna tr {
    border-bottom: 1px solid #ddd;
}

.table-klarna td {
    padding: 10px;
}

</style>

<?php if ($shipping_required) { ?>
<div id="klarna-shipping-method">
  <h3><?php echo $text_choose_shipping_method; ?></h3>
  <?php if ($shipping_methods) { ?>
  <p><?php echo $text_shipping_method; ?></p>
  <?php foreach ($shipping_methods as $shipping_method) { ?>
  <p><strong><?php echo $shipping_method['title']; ?></strong></p>
  <?php if (!$shipping_method['error']) { ?>
  <?php foreach ($shipping_method['quote'] as $quote) { ?>
  <div class="radio">
	<label>
	  <?php if ($quote['code'] == $code || !$code) { ?>
	  <?php $code = $quote['code']; ?>
	  <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" checked="checked" />
	  <?php } else { ?>
	  <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" />
	  <?php } ?>
	  <?php echo $quote['title']; ?> - <?php echo $quote['text']; ?></label>
  </div>
  <?php } ?>
  <?php } else { ?>
  <div class="alert alert-danger"><?php echo $shipping_method['error']; ?></div>
  <?php } ?>
  <?php } ?>
  <input type="hidden" name="comment" value="">
  <?php } else { ?>
  <?php echo $text_no_shipping; ?>
  <?php } ?>
</div>
<?php } ?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Order Summary</h3>
    </div>
    <?php if ($products || $vouchers) { ?>
        <div style="overflow: auto;">
        	<table class="table-klarna">
        	  <?php foreach ($products as $product) { ?>
        	  <tr>
        		<td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
        		  <?php if ($product['option']) { ?>
        		  <?php foreach ($product['option'] as $option) { ?>
        		  <br />
        		  - <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small>
        		  <?php } ?>
        		  <?php } ?>
        		  <?php if ($product['recurring']) { ?>
        		  <br />
        		  - <small><?php echo $text_recurring; ?> <?php echo $product['recurring']; ?></small>
        		  <?php } ?></td>
        		<td class="text-right">x <?php echo $product['quantity']; ?></td>
        		<td class="text-right"><?php echo $product['total']; ?></td>
        		<td class="text-center"><button type="button" onclick="kc.cartRemove('<?php echo $product['cart_id']; ?>');" title="<?php echo $button_remove; ?>" class="btn-link"><i class="fa fa-times"></i></button></td>
        	  </tr>
        	  <?php } ?>
        	  <?php foreach ($vouchers as $voucher) { ?>
        	  <tr>
        		<td class="text-left"><?php echo $voucher['description']; ?></td>
        		<td class="text-right">x&nbsp;1</td>
        		<td class="text-right"><?php echo $voucher['amount']; ?></td>
        		<td class="text-center"><button type="button" onclick="kc.voucherRemove('<?php echo $voucher['key']; ?>');" title="<?php echo $button_remove; ?>" class="btn-link"><i class="fa fa-times"></i></button></td>
        	  </tr>
        	  <?php } ?>
        	</table>
        </div>
		<div>
		  <table class="table-klarna">
			<?php foreach ($totals as $total) { ?>
			<tr>
			  <td class="text-right"><strong><?php echo $total['title']; ?></strong></td>
			  <td class="text-right"><?php echo $total['text']; ?></td>
			</tr>
			<?php } ?>
		  </table>
		</div>
	  <?php } else { ?>
		<p class="text-center"><?php echo $text_empty; ?></p>
	  <?php } ?>
</div>

<script type="text/javascript"><!--
$('#klarna-shipping-method input[type=\'radio\'], #confirm-shipping input[type=\'radio\']').change(function() {
	window._klarnaCheckout(function(api) {
		addSidebarOverlay();
		api.suspend();
	});


    $.ajax({
        url: 'index.php?route=checkout/shipping_method/save',
        type: 'post',
        data: $('#klarna-shipping-method input[type=\'radio\']:checked, #klarna-shipping-method input[type=\'hidden\']'),
        dataType: 'json',
        success: function(json) {
            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
                console.log(json['error']);
            } else {
				$.post('index.php?route=extension/payment/klarna_checkout/main', {response: 'json'}, function() {
					$('.klarna-checkout-sidebar').load('index.php?route=extension/payment/klarna_checkout/sidebar', function() {
						window._klarnaCheckout(function(api) {
							api.resume();
							removeSidebarOverlay();
						});
					});

					$.get('index.php?route=extension/payment/klarna_checkout/cartTotal', function(total) {
						setTimeout(function() {
							$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + total + '</span>');
						}, 100);

						$('#cart > ul').load('index.php?route=common/cart/info ul li');
					});
				});
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

var kc = {
	'cartRemove': function(key) {
		window._klarnaCheckout(function(api) {
			addSidebarOverlay();
			api.suspend();
		});

		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			complete: function() {
				window._klarnaCheckout(function(api) {
					api.resume();
					removeSidebarOverlay();
				});
			},
			success: function(json) {
				$.post('index.php?route=extension/payment/klarna_checkout/main', {response: 'json'}, function(data) {
					if (data['redirect']) {
						location = data['redirect'];
					} else {
						$('.klarna-checkout-sidebar').load('index.php?route=extension/payment/klarna_checkout/sidebar', function() {
							window._klarnaCheckout(function(api) {
								api.resume();
								removeSidebarOverlay();
							});
						});

						// Need to set timeout otherwise it wont update the total
						setTimeout(function() {
							$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
						}, 100);

						$('#cart > ul').load('index.php?route=common/cart/info ul li');
					}
				});
			},
	        error: function(xhr, ajaxOptions, thrownError) {
	            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	        }
		});
	},
	'voucherRemove': function(key) {
		window._klarnaCheckout(function(api) {
			addSidebarOverlay();
			api.suspend();
		});

		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			complete: function() {
				window._klarnaCheckout(function(api) {
					api.resume();
					removeSidebarOverlay();
				});
			},
			success: function(json) {
				$.post('index.php?route=extension/payment/klarna_checkout/main', {response: 'json'}, function() {
					if (data['redirect']) {
						location = data['redirect'];
					} else {
						$('.klarna-checkout-sidebar').load('index.php?route=extension/payment/klarna_checkout/sidebar', function() {
							window._klarnaCheckout(function(api) {
								api.resume();
								removeSidebarOverlay();
							});
						});

						// Need to set timeout otherwise it wont update the total
						setTimeout(function() {
							$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
						}, 100);

						$('#cart > ul').load('index.php?route=common/cart/info ul li');
					}
				});
			},
	        error: function(xhr, ajaxOptions, thrownError) {
	            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	        }
		});
	}
};
//--></script>
