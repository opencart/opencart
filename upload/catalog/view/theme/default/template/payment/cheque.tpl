<h2><?php echo $text_instruction; ?></h2>
<div class="content">
  <p><b><?php echo $text_payable; ?></b></p>
  <p><?php echo $payable; ?></p>
  <b><?php echo $text_address; ?></b><br />
  <p><?php echo $address; ?></p>
  <p><?php echo $text_payment; ?></p>
</div>
<div class="buttons">
  <div class="right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="button" />
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({ 
		type: 'get',
		url: 'index.php?route=payment/cheque/confirm',
		success: function() {
			location = '<?php echo $continue; ?>';
		}		
	});
});
//--></script> 
