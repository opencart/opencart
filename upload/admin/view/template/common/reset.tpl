<?php echo $header; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><i class="fa fa-repeat"></i> <?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="container-fluid">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
      <p><?php echo $text_password; ?></p>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
        <div class="col-sm-10">
          <input type="password" name="password" value="<?php echo $password; ?>" id="input-password" class="form-control" />
          <?php if ($error_password) { ?>
          <div class="text-danger"><?php echo $error_password; ?></div>
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
        <div class="col-sm-10">
          <input type="password" name="confirm" value="<?php echo $confirm; ?>" id="input-confirm" class="form-control" />
          <?php if ($error_confirm) { ?>
          <div class="text-danger"><?php echo $error_confirm; ?></div>
          <?php } ?>
        </div>
      </div>
    </form>
  </div>
</div>
<?php echo $footer; ?>