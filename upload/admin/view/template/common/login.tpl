<?php echo $header; ?>
<div class="container">
  <div class="row">
    <div class="col-lg-offset-4 col-lg-4">
      <div class="panel">
        <div class="panel-heading">
          <h1 class="panel-title"><i class="icon-lock icon-large"></i> <?php echo $text_login; ?></h1>
        </div>
        <?php if ($success) { ?>
        <div class="alert alert-success"><i class="icon-ok-sign"></i> <?php echo $success; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="input-username"><?php echo $entry_username; ?></label>
            <div class="input-group"><span class="input-group-addon"><i class="icon-user"></i></span>
              <input type="text" name="username" value="<?php echo $username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label for="input-password"><?php echo $entry_password; ?></label>
            <div class="input-group"><span class="input-group-addon"><i class="icon-lock"></i></span>
              <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
            </div>
            <?php if ($forgotten) { ?>
            <span class="help-block"><a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></span>
            <?php } ?>
          </div>
          <button type="submit" class="btn btn-primary"><i class="icon-key"></i> <?php echo $button_login; ?></button>
          <?php if ($redirect) { ?>
          <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
          <?php } ?>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>