<fieldset>
  <legend><?php echo $text_google_analytics; ?></legend>
  <div class="alert alert-info"><?php echo $help_google_analytics; ?></div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="input-google-analytics"><?php echo $entry_google_analytics; ?></label>
    <div class="col-sm-10">
      <textarea name="config_google_analytics" rows="5" placeholder="<?php echo $entry_google_analytics; ?>" id="input-google-analytics" class="form-control"><?php echo $config_google_analytics; ?></textarea>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="input-google-analytics-status"><?php echo $entry_status; ?></label>
    <div class="col-sm-10">
      <select name="config_google_analytics_status" id="input-google-analytics-status" class="form-control">
        <?php if ($config_google_analytics_status) { ?>
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
