<?php echo $header; ?>
<div id="content" class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="icon-ok-sign"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($alert_filesize){ ?>
  <div class="alert alert-danger"><i class="icon-warning-sign"></i> <?php echo $alert_filesize ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right"><a href="<?php echo $clear; ?>" class="btn btn-danger"><i class="icon-eraser"></i> <?php echo $button_clear; ?></a></div>
      <h1 class="panel-title"><i class="icon-warning-sign"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <textarea wrap="off" rows="15" readonly="readonly" class="form-control"><?php echo $log; ?></textarea>
    </div>
  </div>
</div>
<?php echo $footer; ?>