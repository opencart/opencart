<div class="row">
  <div class="span6">
    <h2><?php echo $text_new_customer; ?></h2>
    <p><?php echo $text_checkout; ?></p>
    <label class="radio" for="register">
      <?php if ($account == 'register') { ?>
      <input type="radio" name="account" value="register" id="register" checked="checked" />
      <?php } else { ?>
      <input type="radio" name="account" value="register" id="register" />
      <?php } ?>
      <?php echo $text_register; ?></label>
    <?php if ($guest_checkout) { ?>
    <label class="radio" for="guest">
      <?php if ($account == 'guest') { ?>
      <input type="radio" name="account" value="guest" id="guest" checked="checked" />
      <?php } else { ?>
      <input type="radio" name="account" value="guest" id="guest" />
      <?php } ?>
      <?php echo $text_guest; ?></label>
    <?php } ?>
    <p><?php echo $text_register_account; ?></p>
    <input type="button" value="<?php echo $button_continue; ?>" id="button-account" class="btn" />
  </div>
  <div id="login" class="span6">
    <h2><?php echo $text_returning_customer; ?></h2>
    <p><?php echo $text_i_am_returning_customer; ?></p>
    <label for="email"><?php echo $entry_email; ?></label>
    <input type="text" name="email" value="" />
    <label for="password"><?php echo $entry_password; ?></label>
    <input type="password" name="password" value="" />
    <br />
    <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a><br />
    <br />
    <input type="button" value="<?php echo $button_login; ?>" id="button-login" class="btn" />
  </div>
</div>
