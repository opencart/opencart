<script src="//www.google.com/recaptcha/api.js" type="text/javascript"></script>
<fieldset>
  <legend><?php echo $text_captcha; ?></legend>
  <div class="form-group required">
    <?php if (substr($route, 0, 9) == 'checkout/') { ?>
    <label class="control-label" for="input-payment-captcha"><?php echo $entry_captcha; ?></label>
    <div id="input-payment-captcha" class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>
    <?php if ($error_captcha) { ?>
    <div class="text-danger"><?php echo $error_captcha; ?></div>
    <?php } ?>
    <?php } else { ?>
    <label class="col-sm-2 control-label"><?php echo $entry_captcha; ?></label>
    <div class="col-sm-10">
      <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>
      <?php if ($error_captcha) { ?>
      <div class="text-danger"><?php echo $error_captcha; ?></div>
      <?php } ?>
    </div>
    <?php } ?>
  </div>
</fieldset>
