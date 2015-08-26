<fieldset>
  <legend><?php echo $text_captcha; ?></legend>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>
      <?php if ($error_captcha) { ?>
      <div class="text-danger"><?php echo $error_captcha; ?></div>
      <?php } ?>
    </div>
  </div>
</fieldset>
