<fieldset>
  <legend><?php echo $text_google_captcha; ?></legend>
  <div class="alert alert-info"><?php echo $help_google_captcha; ?></div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="input-google-captcha-public"><?php echo $entry_google_captcha_public; ?></label>
    <div class="col-sm-10">
      <input type="text" name="config_google_captcha_public" value="<?php echo $config_google_captcha_public; ?>" placeholder="<?php echo $entry_google_captcha_public; ?>" id="input-google-captcha-public" class="form-control" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="input-google-captcha-secret"><?php echo $entry_google_captcha_secret; ?></label>
    <div class="col-sm-10">
      <input type="text" name="config_google_captcha_secret" value="<?php echo $config_google_captcha_secret; ?>" placeholder="<?php echo $entry_google_captcha_secret; ?>" id="input-google-captcha-secret" class="form-control" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="input-google-captcha-status"><?php echo $entry_status; ?></label>
    <div class="col-sm-10">
      <select name="config_google_captcha_status" id="input-google-captcha-status" class="form-control">
        <?php if ($config_google_captcha_status) { ?>
        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
        <option value="0"><?php echo $text_disabled; ?></option>
        <?php } else { ?>
        <option value="1"><?php echo $text_enabled; ?></option>
        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
        <?php } ?>
      </select>
    </div>
  </div>
</fieldset>
