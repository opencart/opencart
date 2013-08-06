<?php echo $header; ?>
<div id="content">
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
  <div class="panel">
    <div class="panel-heading">
      <h1 class="panel-title"><i class="icon-warning-sign icon-large"></i> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $clear; ?>" class="btn"><i class="icon-eraser"></i> <?php echo $button_clear; ?></a></div>
    </div>
    <textarea wrap="off" style="width: 98%; height: 300px; overflow: scroll;" readonly="readonly"><?php echo $log; ?></textarea>
  </div>
</div>
<?php echo $footer; ?>