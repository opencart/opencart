<?php echo $header; ?>
<div id="content" class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <button type="submit" form="form-customer-ban-ip" class="btn btn-primary"><i class="fa fa-check"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-danger"><i class="fa fa-times"></i> <?php echo $button_cancel; ?></a></div>
      <h1 class="panel-title"><i class="fa fa-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
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
</div>
<?php echo $footer; ?>