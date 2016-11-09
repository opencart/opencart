<?php if ($redirect) { ?>
<script type="text/javascript"><!--
location = '<?php echo $redirect; ?>';
//--></script>
<?php } elseif ($klarna_checkout) { ?>
<?php echo $klarna_checkout; ?>
<?php } ?>