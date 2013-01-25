<form action="<?php echo $action; ?>" method="post" id="google-checkout">
  <input type="hidden" name="cart" value="<?php echo $cart; ?>">
  <input type="hidden" name="signature" value="<?php echo $signature; ?>">
  <div class="buttons">
    <div class="right">
       <img alt="Fast checkout through Google" src="http://sandbox.google.com/checkout/buttons/checkout.gif?merchant_id=<?php echo $merchant; ?>&w=180&h=46&style=white&variant=text&loc=en_US" height="46" width="180" id="button-paypal" />
    </div>
  </div>
</form>
<script type="text/javascript"><!--
$('#button-paypal').bind('click', function() {
	$.ajax({
		url: 'index.php?route=payment/google_checkout/send',
		type: 'post',
		data: $('#payment :input'),
		dataType: 'json',		
		beforeSend: function() {
			$('#button-paypal').attr('disabled', true);
			$('#button-paypal').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-paypal').attr('disabled', false); 
			$('.wait').remove();
		},				
		success: function(json) {
			if (json['error']) {
				alert(json['error']);
			}
			
			if (json['cart']) {
				$('input[name=\'cart\']').attr('value', json['cart']);
				$('input[name=\'signature\']').attr('value', json['signature']);
				
				$('#google-checkout').submit();
				
			}
		}
	});
});
//--></script> 