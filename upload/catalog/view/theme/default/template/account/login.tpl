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
    <div class="row-fluid">
      <div class="span6">
        <div class="well">
          <h2><?php echo $text_new_customer; ?></h2>
          <p><strong><?php echo $text_register; ?></strong></p>
          <p><?php echo $text_register_account; ?></p>
          <a href="<?php echo $register; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a> </div>
      </div>
      <div class="span6">
        <div class="well">
          <h2><?php echo $text_returning_customer; ?></h2>
          <p><strong><?php echo $text_i_am_returning_customer; ?></strong></p>
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
            <div class="form-group">
              <label class="col-lg-3 control-label" for="input-email"><?php echo $entry_email; ?></label>
              <div class="col-lg-9">
                <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 control-label" for="input-password"><?php echo $entry_password; ?></label>
              <div class="col-lg-9">
                <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
                <br />
                <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></div>
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