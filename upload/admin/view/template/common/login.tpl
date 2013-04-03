<?php echo $header; ?>
<div id="content">
  <div class="box" style="width: 400px; min-height: 300px; margin-top: 40px; margin-left: auto; margin-right: auto;">
    <div class="box-heading">
      <h1><?php echo $text_login; ?></h1>
    </div>
    <div class="content" style="min-height: 150px; overflow: hidden;">
    <?php if ($success) { ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
    <?php } ?>
    <?php if ($error_warning) { ?>
    <div class="alert alert-error"><?php echo $error_warning; ?></div>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
      <div class="control-group">
        <label class="control-label" for="input-username"><?php echo $entry_username; ?></label>
        <div class="controls">
          <input type="text" name="username" value="<?php echo $username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
        <div class="controls">
          <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" />
          <?php if ($forgotten) { ?>
          <span class="help-block"> <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></span>
          <?php } ?>
        </div>
      </div>
      <button type="submit" class="btn"><i class="icon-lock"></i> <?php echo $button_login; ?></button>
      <?php if ($redirect) { ?>
      <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
      <?php } ?>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#form').submit();
	}
});
//--></script> 
<?php echo $footer; ?>