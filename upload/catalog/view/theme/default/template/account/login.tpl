<?php echo $header; ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php if ($success) { ?>
<div class="alert alert-success"><?php echo $success; ?></div>
<?php } ?>
<?php if ($error_warning) { ?>
<div class="alert alert-error"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="row"><?php echo $column_left; ?>
  <div id="content" class="span9"><?php echo $content_top; ?>
    <div class="login-content row">
      <div class="span4">
        <div class="well">
          <h2><?php echo $text_new_customer; ?></h2>
          <div class="content">
            <p> <strong> <?php echo $text_register; ?> </strong> </p>
            <p> <?php echo $text_register_account; ?> </p>
            <a href="<?php echo $register; ?>" class="btn btn-primary"> <?php echo $button_continue; ?> </a> </div>
        </div>
      </div>
      <div class="span5">
        <div class="well">
          <h2><?php echo $text_returning_customer; ?></h2>
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
            <fieldset>
              <p><?php echo $text_i_am_returning_customer; ?></p>
              <label for="email"><?php echo $entry_email; ?></label>
              <input type="text" name="email" value="<?php echo $email; ?>" />
            </fieldset>
            <fieldset>
              <label for="password"><?php echo $entry_password; ?></label>
              <input type="password" name="password" value="<?php echo $password; ?>" />
              <label><a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></label>
            </fieldset>
            <fieldset>
              <input type="submit" value="<?php echo $button_login; ?>" class="btn btn-primary" />
              <?php if ($redirect) { ?>
              <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
              <?php } ?>
            </fieldset>
          </form>
        </div>
      </div>
    </div>
    <?php echo $content_bottom; ?></div>
  <?php echo $column_right; ?></div>
<script type="text/javascript"><!--
$('#login input').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#login').submit();
	}
});
//--></script> 
<?php echo $footer; ?>