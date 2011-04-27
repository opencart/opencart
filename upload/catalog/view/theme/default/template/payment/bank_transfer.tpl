<h2><?php echo $text_instruction; ?></h2>
<p><?php echo $text_description; ?></p>
<p><?php echo $bank; ?></p>
<p><?php echo $text_payment; ?></p>
<div class="buttons">
  <div class="right"><a id="button-confirm" class="button"><span><?php echo $button_confirm; ?></span></a></div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({ 
		type: 'GET',
		url: 'index.php?route=payment/bank_transfer/confirm',
		success: function() {
			location = '<?php echo $continue; ?>';
		}		
	});
});
//--></script> 
