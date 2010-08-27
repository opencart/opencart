<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="top">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center">
      <h1><?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="middle">
    <?php if ($success) { ?>
    <div class="success"><?php echo $success; ?></div>
    <?php } ?>
    <?php if ($error) { ?>
    <div class="warning"><?php echo $error; ?></div>
    <?php } ?>
    <div style="margin-bottom: 10px; display: inline-block; width: 100%;">
      <div style="float: left; display: inline-block; width: 49%;"><b style="margin-bottom: 2px; display: block;"><?php echo $text_i_am_new_customer; ?></b>
        <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; min-height: 210px;">
          <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="account">
            <p><?php echo $text_checkout; ?></p>
            <label for="register" style="cursor: pointer;">
              <?php if ($account == 'register') { ?>
              <input type="radio" name="account" value="register" id="register" checked="checked" />
              <?php } else { ?>
              <input type="radio" name="account" value="register" id="register" />
              <?php } ?>
              <b><?php echo $text_register; ?></b></label>
            <br />
            <?php if ($guest_checkout) { ?>
            <label for="guest" style="cursor: pointer;">
              <?php if ($account == 'guest') { ?>
              <input type="radio" name="account" value="guest" id="guest" checked="checked" />
              <?php } else { ?>
              <input type="radio" name="account" value="guest" id="guest" />
              <?php } ?>
              <b><?php echo $text_guest; ?></b></label>
            <br />
            <?php } ?>
            <br />
            <p><?php echo $text_create_account; ?></p>
            <div style="text-align: right;"><a onclick="$('#account').submit();" class="button"><span><?php echo $button_continue; ?></span></a></div>
          </form>
        </div>
      </div>
      <div style="float: right; display: inline-block; width: 49%;"><b style="margin-bottom: 2px; display: block;"><?php echo $text_returning_customer; ?></b>
        <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; min-height: 210px;">
          <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="login">
            <?php echo $text_i_am_returning_customer; ?><br />
            <br />
            <b><?php echo $entry_email; ?></b><br />
            <input type="text" name="email" />
            <br />
            <br />
            <b><?php echo $entry_password; ?></b><br />
            <input type="password" name="password" />
            <br />
            <a href="<?php echo str_replace('&', '&amp;', $forgotten); ?>"><?php echo $text_forgotten_password; ?></a><br />
            <div style="text-align: right;"><a onclick="$('#login').submit();" class="button"><span><?php echo $button_login; ?></span></a></div>
            <?php if ($redirect) { ?>
            <input type="hidden" name="redirect" value="<?php echo str_replace('&', '&amp;', $redirect); ?>" />
            <?php } ?>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>

</div>
<script type="text/javascript"><!--
$('#login input').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#login').submit();
	}
});
//--></script>
<?php echo $footer; ?> 