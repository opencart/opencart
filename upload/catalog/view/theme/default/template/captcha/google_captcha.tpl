<div class="form-group required">
  <?php if (substr($route, 0, 9) == 'checkout/') { ?>
  <label class="control-label" for="input-payment-telephone">Telephone</label>
  <input type="text" name="telephone" value="" placeholder="Telephone" id="input-payment-telephone" class="form-control">
  <?php } else { ?>
  <div class="col-sm-offset-2 col-sm-10">
    <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>
    <?php if ($error_captcha) { ?>
    <div class="text-danger"><?php echo $error_captcha; ?></div>
    <?php } ?>
  </div>
  <?php } ?>
</div>
