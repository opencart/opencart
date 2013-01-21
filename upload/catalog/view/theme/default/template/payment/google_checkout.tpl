<form action="<?php echo $action; ?>" method="post">
  <input type="hidden" name="cart" value="<?php echo $cart; ?>">
  <input type="hidden" name="signature" value="<?php echo $signature; ?>">
  <div class="buttons">
    <div class="right">
      <input type="button" value="<?php echo $button_confirm; ?>" id="button-paypal" class="button" />
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
			}
		}
	});
});
//--></script> 