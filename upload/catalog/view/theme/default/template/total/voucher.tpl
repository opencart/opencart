<div>
  <div class="cart-heading"><?php echo $heading_title; ?></div>
  <div class="cart-content" id="voucher"><?php echo $entry_voucher; ?>&nbsp;
    <input type="text" name="voucher" value="<?php echo $voucher; ?>" />
    &nbsp;<a id="button-voucher" class="button"><span><?php echo $button_voucher; ?></span></a></div>
</div>
<script type="text/javascript"><!--
$('#button-voucher').bind('click', function() {
	$.ajax({
		type: 'POST',
		url: 'index.php?route=total/voucher/calculate',
		data: $('#voucher :input'),
		dataType: 'json',		
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-voucher').attr('disabled', true);
			$('#button-voucher').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-voucher').attr('disabled', false);
			$('.wait').remove();
		},		
		success: function(json) {
			if (json['error']) {
				$('#basket').before('<div class="warning">' + json['error'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
			}
			
			if (json['redirect']) {
				location = json['redirect'];
			}
		}
	});
});
//--></script> 