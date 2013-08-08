<?php echo $header; ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php if ($success) { ?>
<div class="alert alert-success"><i class="icon-ok-sign"></i> <?php echo $success; ?></div>
<?php } ?>
<?php if ($error_warning) { ?>
<div class="alert alert-danger"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="row"><?php echo $column_left; ?>
  <div id="content" class="span9"><?php echo $content_top; ?>
    <h1><?php echo $heading_title; ?></h1>
    <?php echo $text_description; ?>
    <div class="row">
      <div class="span4">
        <div class="well">
          <h2><?php echo $text_new_affiliate; ?></h2>
          <p><?php echo $text_register_account; ?></p>
          <a class="btn btn-primary" href="<?php echo $register; ?>"><?php echo $button_continue; ?></a></div>
      </div>
      <div class="span5">
        <div class="well">
          <h2><?php echo $text_returning_affiliate; ?></h2>
          <p><strong><?php echo $text_i_am_returning_affiliate; ?></strong></p>
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
              <label class="col-lg-3 control-label" for="input-email"><?php echo $entry_email; ?></label>
              <div class="col-lg-9">
                <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 control-label" for="input-password"><?php echo $entry_password; ?></label>
              <div class="col-lg-9">
                <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" />
                <br />
                <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a> </div>
            </div>
            <input type="submit" value="<?php echo $button_login; ?>" class="btn btn-primary" />
            <?php if ($redirect) { ?>
            <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
            <?php } ?>
          </form>
        </div>
      </div>
    </div>
    <?php echo $content_bottom; ?></div>
  <?php echo $column_right; ?></div>
<?php echo $footer; ?>