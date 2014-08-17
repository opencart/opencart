<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-customer-ban-ip" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-customer-ban-ip" class="form-horizontal">
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-ip"><?php echo $entry_ip; ?></label>
        <div class="col-sm-10">
          <input type="text" name="ip" value="<?php echo $ip; ?>" id="input-ip" class="form-control" />
          <?php if ($error_ip) { ?>
          <div class="text-danger"><?php echo $error_ip; ?></div>
          <?php } ?>
        </div>
      </div>
    </form>
  </div>
</div>
<?php echo $footer; ?>