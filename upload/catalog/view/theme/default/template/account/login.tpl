<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle">
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <?php if ($error) { ?>
  <div class="warning"><?php echo $error; ?></div>
  <?php } ?>
  <div style="float: left; display: inline-block; width: 274px;"><b style="margin-bottom: 3px; display: block;"><?php echo $text_i_am_new_customer; ?></b>
    <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; min-height: 175px;"><?php echo $text_new_customer; ?><br />
      <br />
      <?php echo $text_create_account; ?><br />
      <br />
      <div style="text-align: right;"><a onclick="location='<?php echo $continue; ?>'" class="button"><span><?php echo $button_continue; ?></span></a></div>
    </div>
  </div>
  <div style="float: right; display: inline-block; width: 274px;"><b style="margin-bottom: 3px; display: block;"><?php echo $text_returning_customer; ?></b>
    <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; min-height: 175px;">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="login">
        <?php echo $text_i_am_returning_customer; ?><br />
        <br />
        <b><?php echo $entry_email; ?></b><br />
        <input type="text" name="email" />
        <br />
        <br />
        <b><?php echo $entry_password; ?></b><br />
        <input type="password" name="password" />
        <br />
        <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten_password; ?></a><br />
        <div style="text-align: right;"><a onclick="$('#login').submit();" class="button"><span><?php echo $button_login; ?></span></a></div>
        <?php if ($redirect) { ?>
        <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
        <?php } ?>
      </form>
    </div>
  </div>
</div>
<div class="bottom"></div>
