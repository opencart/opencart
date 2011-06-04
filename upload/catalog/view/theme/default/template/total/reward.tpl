<div>
  <div class="cart-heading"><?php echo $heading_title; ?></div>
  <div class="cart-content" id="reward"><?php echo $entry_reward; ?>&nbsp;
  <input type="text" name="reward" value="<?php echo $reward; ?>" />
  &nbsp;<a id="button-reward" class="button"><span><?php echo $button_reward; ?></a></div>
</div>
<script type="text/javascript"><!--
$('#button-reward').bind('click', function() {
	$.ajax({
		type: 'POST',
		url: 'index.php?route=total/reward/calculate',
		data: $('#reward :input'),
		dataType: 'json',		
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-reward').attr('disabled', true);
			$('#button-reward').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-reward').attr('disabled', false);
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
