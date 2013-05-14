<?php echo $header; ?>
<div id="content">
  <div class="box" style="width: 370px; min-height: 200px; margin-top: 40px; margin-bottom: 80px; margin-left: auto; margin-right: auto;">
    <div class="box-heading">
      <h1><i class="icon-lock icon-large"></i><?php echo $text_login; ?></h1>
    </div>
    <div class="box-content">
      <?php if ($success) { ?>
      <div class="alert alert-success"><i class="icon-ok-sign"></i> <?php echo $success; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
      <?php } ?>
      <?php if ($error_warning) { ?>
      <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
      <?php } ?>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <div class="control-group">
          <label class="control-label" for="input-username"><?php echo $entry_username; ?></label>
          <div class="controls">
            <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
              <input type="text" name="username" value="<?php echo $username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="input-xlarge" />
            </div>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
          <div class="controls">
            <div class="input-prepend"><span class="add-on"><i class="icon-lock"></i></span>
              <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="input-xlarge" />
            </div>
            <?php if ($forgotten) { ?>
            <span class="help-block"><a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></span>
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
</div>
<?php echo $footer; ?>