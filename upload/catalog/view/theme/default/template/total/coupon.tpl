<?php if ($coupon_status) { ?>
<h2><?php echo $heading_title; ?></h2>
<div id="coupon" class="content"><?php echo $entry_coupon; ?>&nbsp;
  <input type="text" name="coupon" value="<?php echo $coupon; ?>" />
  &nbsp;<a id="button-coupon" class="button"><span><?php echo $button_coupon; ?></span></a></div>
<script type="text/javascript"><!--
$('#button-coupon').bind('click', function() {
	$.ajax({
		type: 'POST',
		url: 'index.php?route=total/coupon/calculate',
		data: $('#coupon :input'),
		dataType: 'json',		
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-coupon').attr('disabled', 'disabled');
			$('#button-coupon').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-coupon').attr('disabled', '');
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
<?php } ?>