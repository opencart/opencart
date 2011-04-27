<?php if (isset($error)) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<?php if ($testmode) { ?>
<div class="warning"><?php echo $this->language->get('text_testmode'); ?></div>
<?php } ?>
<form action="<?php echo $action; ?>" method="post" id="payment">
  <?php foreach ($fields as $key => $value) { ?>
  <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>" />
  <?php } ?>
</form>
<div class="buttons">
  <div class="right"><a id="button-confirm" class="button"><span><?php echo $button_confirm; ?></span></a></div>  
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({
		type: 'GET',
		url: 'index.php?route=payment/pp_standard/confirm',
		success: function() {
			$('#payment').submit();
		}
	});
});
//--></script>