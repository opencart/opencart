<?php echo $header; ?>
<div class="container">
  <header>
    <div class="row">
      <div class="col-sm-6">
        <h1 class="pull-left">1<small>/2</small></h1>
        <h3><?php echo $heading_title; ?><br>
          <small><?php echo $text_upgrade; ?></small></h3>
      </div>
      <div class="col-sm-6">
        <div id="logo" class="pull-right hidden-xs"><img src="view/image/logo.png" alt="OpenCart" title="OpenCart" /></div>
      </div>
    </div>
  </header>
  <div class="row">
    <div class="col-sm-9">
      <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
      <?php } ?>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <fieldset>
          <p><b><?php echo $text_steps; ?></b></p>
          <ol>
            <li><?php echo $text_error; ?></li>
            <li><?php echo $text_clear; ?></li>
            <li><?php echo $text_admin; ?></li>
            <li><?php echo $text_user; ?></li>
            <li><?php echo $text_setting; ?></li>
            <li><?php echo $text_store; ?></li>
          </ol>
        </fieldset>
        <div class="buttons">
          <div class="text-right">
            <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
          </div>
        </div>
      </form>
    </div>
    <div class="col-sm-3"><?php echo $column_left; ?></div>
  </div>
</div>
<?php echo $footer; ?>