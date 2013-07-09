<?php if ($error_warning) { ?>
<div class="alert alert-warning"><?php echo $error_warning; ?></div>
<?php } ?>

<?php if ($payment_methods) { ?>
<p><?php echo $text_payment_method; ?></p>

<div class="payment-method-options">
  <?php foreach ($payment_methods as $payment_method) { ?>

  <label class="radio" for="<?php echo $payment_method['code']; ?>">
      <?php if ($payment_method['code'] == $code || !$code) { ?>
      <?php $code = $payment_method['code']; ?>
      <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" checked="checked" />
      <?php } else { ?>
      <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" />
      <?php } ?>
    
    <?php echo $payment_method['title']; ?>
  </label>

  <?php } ?>
</div>

<?php } ?>

<strong><?php echo $text_comments; ?></strong>
<textarea name="comment" rows="8" style="width: 98%;"><?php echo $comment; ?></textarea>
<br />
<br />
<?php if ($text_agree) { ?>
<div class="buttons">
  <div class="right"><?php echo $text_agree; ?>
    <?php if ($agree) { ?>
    <input type="checkbox" name="agree" value="1" checked="checked" />
    <?php } else { ?>
    <input type="checkbox" name="agree" value="1" />
    <?php } ?>
    <input type="button" value="<?php echo $button_continue; ?>" id="button-payment-method" class="btn" />
  </div>
  <div class="clearfix"></div>
</div>
<?php } else { ?>
<div class="buttons">
  <div class="right">
    <input type="button" value="<?php echo $button_continue; ?>" id="button-payment-method" class="btn" />
  </div>
  <div class="clearfix"></div>
</div>
<?php } ?>
<script type="text/javascript"><!--
$(document).ready(function() {
    $('.colorbox').colorbox({
        maxWidth: 640,
        width: "85%",
        height: 480
    });
});
//--></script>