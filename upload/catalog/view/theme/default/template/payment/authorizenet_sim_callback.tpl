<?php echo $header; ?>
<h1><?php echo $x_response_reason_text; ?></h1>
<?php if ($x_response_code == '1' || $response_code == $md5_hash) { ?>
<script type="text/javascript">
	setTimeout(function () {
       window.location.href = "<?php echo $confirm; ?>"; 
    }, 0000); 
</script>
<?php } elseif ($x_response_code == '1' || $response_code != $md5_hash){ ?>
<p>Hash matching failed, however payment authorization has been accepted, please contact the site administrator</p>
<div class="buttons">
  <div class="pull-right"><a href="<?php echo $confirm; ?>" class="btn btn-primary"><?php echo $button_confirm; ?></a></div>
</div>
<?php } elseif ($_REQUEST['x_response_code'] == '2') { ?>
<p>Your payment has failed, we sugguest speaking to your card company or bank to resolve this issue</p>
<div class="buttons">
  <div class="pull-right"><a href="<?php echo $confirm; ?>" class="btn btn-primary"><?php echo $button_confirm; ?></a></div>
</div>
<?php } else { ?>
<p>An error occurred while processing your payment. Please try again later.</p>
<div class="buttons">
  <div class="pull-right"><a href="<?php echo $confirm; ?>" class="btn btn-primary"><?php echo $button_confirm; ?></a></div>
</div>
<?php } ?>
<?php echo $footer; ?>