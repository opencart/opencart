<div class="row">
  <div class="col-sm-6">
    <h2>{{ text_new_customer }}</h2>
    <p>{{ text_checkout }}</p>
    <div class="radio">
      <label>
        <?php if ($account == 'register') { ?>
        <input type="radio" name="account" value="register" checked="checked" />
        <?php } else { ?>
        <input type="radio" name="account" value="register" />
        <?php } ?>
        {{ text_register }}</label>
    </div>
    <?php if ($checkout_guest) { ?>
    <div class="radio">
      <label>
        <?php if ($account == 'guest') { ?>
        <input type="radio" name="account" value="guest" checked="checked" />
        <?php } else { ?>
        <input type="radio" name="account" value="guest" />
        <?php } ?>
        {{ text_guest }}</label>
    </div>
    <?php } ?>
    <p>{{ text_register_account }}</p>
    <input type="button" value="{{ button_continue }}" id="button-account" data-loading-text="{{ text_loading }}" class="btn btn-primary" />
  </div>
  <div class="col-sm-6">
    <h2>{{ text_returning_customer }}</h2>
    <p>{{ text_i_am_returning_customer }}</p>
    <div class="form-group">
      <label class="control-label" for="input-email">{{ entry_email }}</label>
      <input type="text" name="email" value="" placeholder="{{ entry_email }}" id="input-email" class="form-control" />
    </div>
    <div class="form-group">
      <label class="control-label" for="input-password">{{ entry_password }}</label>
      <input type="password" name="password" value="" placeholder="{{ entry_password }}" id="input-password" class="form-control" />
      <a href="<?php echo $forgotten; ?>">{{ text_forgotten }}</a></div>
    <input type="button" value="{{ button_login }}" id="button-login" data-loading-text="{{ text_loading }}" class="btn btn-primary" />
  </div>
</div>
