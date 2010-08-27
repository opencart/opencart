<?php echo $header; ?>
<div class="box" style="width: 325px; min-height: 300px; margin-top: 40px; margin-left: auto; margin-right: auto;">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/lockscreen.png');"><?php echo $text_login; ?></h1>
  </div>
  <div class="content" style="min-height: 150px;">
    <?php if ($error_warning) { ?>
    <div class="warning" style="padding: 3px;"><?php echo $error_warning; ?></div>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table style="width: 100%;">
        <tr>
          <td style="text-align: center;" rowspan="4"><img src="view/image/login.png" alt="<?php echo $text_login; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_username; ?><br />
            <input type="text" name="username" value="<?php echo $username; ?>" style="margin-top: 4px;" />
            <br />
            <br />
            <?php echo $entry_password; ?><br />
            <input type="password" name="password" value="<?php echo $password; ?>" style="margin-top: 4px;" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td style="text-align: right;"><a onclick="$('#form').submit(); return false;" href="#" class="button"><span><?php echo $button_login; ?></span></a></td>
        </tr>
      </table>
      <?php if ($redirect) { ?>
      <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
      <?php } ?>
    </form>
    <script type="text/javascript"><!--
	$('#form input').keydown(function(e) {
		if (e.keyCode == 13) {
			$('#form').submit();
		}
	});
	//--></script> 
  </div>
</div>
<?php echo $footer; ?> 