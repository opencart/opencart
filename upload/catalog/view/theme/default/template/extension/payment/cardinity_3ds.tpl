<?php if ($success) { ?>
<form id="cardinity-3ds-form" name="ThreeDForm" method="POST" action="<?php echo $url; ?>">
	<input type="hidden" name="PaReq" value="<?php echo $PaReq; ?>" />
	<input type="hidden" name="TermUrl" value="<?php echo $TermUrl; ?>" />
	<input type="hidden" name="MD" value="<?php echo $MD; ?>" />
</form>
<script type="text/javascript"><!--
	$('#cardinity-3ds-form').submit();
//--></script>
<?php } ?>
<?php if ($redirect) { ?>
<script type="text/javascript"><!--
	location = '<?php echo $redirect; ?>';
//--></script>
<?php } ?>
