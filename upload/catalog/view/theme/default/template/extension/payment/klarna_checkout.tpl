<?php echo $header; ?>
<div class="container">
  <div class="row">
    <div id="content" class="col-sm-9"><?php echo $content_top; ?>
	  <div class="klarna-checkout-main"></div>
      <?php echo $content_bottom; ?>
	</div>
	<div class="klarna-checkout-sidebar col-sm-3"></div>
    <?php echo $column_right; ?>
  </div>
</div>

<style>
@-webkit-keyframes spin {
  to {
    -webkit-transform: rotate(1turn);
  }
}

@keyframes spin {
  to {
    -webkit-transform: rotate(1turn);
    transform: rotate(1turn);
  }
}
.sidebar-overlay {
    position: absolute;
    width: 100%;
    height: 100%;
    background-color: rgba(255,255,255,0.7);
    left: 0;
    top: 0;
    z-index: 1;
}

.sidebar-loader {
	position: absolute;
	top: 25%;
	left: 50%;
     -webkit-animation-name: spin;
    animation-name: spin;
    -webkit-animation-iteration-count: infinite;
    animation-iteration-count: infinite;
    -webkit-animation-duration: .8s;
    animation-duration: .8s;
    -webkit-animation-timing-function: linear;
    animation-timing-function: linear;
    background-position: center;
    background-repeat: no-repeat;
    display: block;
    height: 20px;
    margin: -10px 0 0 -10px;
    background-image: url(https://checkout.klarnacdn.net/160128-eb49c06/img/gray-loader-20x20-1-A2Z1Y.png);
    width: 20px;
}
</style>

<script type="text/javascript"><!--
$('.klarna-checkout-main').load('index.php?route=extension/payment/klarna_checkout/main', {response: 'template'}, function(data) {
	window._klarnaCheckout(function(api) {
		addSidebarOverlay();
		api.suspend();

		$('.klarna-checkout-sidebar').load('index.php?route=extension/payment/klarna_checkout/sidebar', function() {
			api.resume();
			removeSidebarOverlay();
		});

		api.on({
			shipping_address_change: function(data) {
				addSidebarOverlay();
				api.suspend();

				$.post('index.php?route=extension/payment/klarna_checkout/shippingAddress', data, function(data) {
					$('.klarna-checkout-sidebar').load('index.php?route=extension/payment/klarna_checkout/sidebar');

					$.get('index.php?route=extension/payment/klarna_checkout/cartTotal', function(total) {
						setTimeout(function() {
							$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + total + '</span>');
						}, 100);

						$('#cart > ul').load('index.php?route=common/cart/info ul li');
					});

					api.resume();
					removeSidebarOverlay();
				});
			}
		});
	});



	$.get('index.php?route=extension/payment/klarna_checkout/cartTotal', function(total) {
		setTimeout(function() {
			$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + total + '</span>');
		}, 100);

		$('#cart > ul').load('index.php?route=common/cart/info ul li');
	});
});

function addSidebarOverlay() {
	$('.klarna-checkout-sidebar').prepend('<div class="sidebar-overlay"><span class="sidebar-loader"></span></div>');
}

function removeSidebarOverlay() {
	$('.sidebar-overlay').remove();
}
//--></script>
<?php echo $footer; ?>