<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h1 class="panel-title"><i class="fa fa-repeat fa-lg"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <p><?php echo $text_password; ?></p>
        <div class="form-group">
        <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
        <div class="col-sm-10">
          <input type="password" name="password" value="<?php echo $password; ?>" id="input-password" class="form-control" />
          <?php if ($error_password) { ?>
          <span class="text-danger"><?php echo $error_password; ?></span>
          <?php } ?>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
          <div class="col-sm-10">
            <input type="password" name="confirm" value="<?php echo $confirm; ?>" id="input-confirm" class="form-control" />
            <?php if ($error_confirm) { ?>
            <span class="text-danger"><?php echo $error_confirm; ?></span>
            <?php } ?>
          </div>
        </div>
        <button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn"><i class="fa fa-check-circle"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn"><i class="fa fa-reply"></i></a>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>