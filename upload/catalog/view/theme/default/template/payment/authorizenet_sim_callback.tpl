<h1><?php echo $x_response_reason_text; ?></h1>
<?php if ($x_response_code == '1') { ?>
<p>Your payment was processed successfully. Here is your receipt:</p>
<pre>
<?php echo $exact_ctr; ?></pre>
<?php if(!empty($exact_issname)) { ?>
<p>Issuer: <?php echo $exact_issname; ?><br/>
  Confirmation Number: <?php echo $exact_issconf; ?></p>
<?php } ?>
<div class="buttons">
  <div class="pull-right"><a href="<?php echo $confirm; ?>" class="btn btn-primary"><?php echo $button_confirm; ?></a></div>
</div>
<?php } elseif ($_REQUEST['x_response_code'] == '2') { ?>
<p>Your payment failed.  Here is your receipt.</p>
<pre>
<?php echo $exact_ctr; ?></pre>
<div class="buttons">
  <div class="buttons">
    <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
  </div>
</div>
<?php } else { ?>
<p>An error occurred while processing your payment. Please try again later.</p>
<div class="buttons">
  <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
</div>
<?php } ?>
