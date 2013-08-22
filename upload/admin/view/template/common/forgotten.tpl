<?php echo $header; ?>
<div id="content" class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h1 class="panel-title"><i class="icon-repeat"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <p><?php echo $text_email; ?></p>
        <div class="form-group">
          <label class="col-sm-3 control-label" for="input-email"><?php echo $entry_email; ?></label>
          <div class="col-sm-9">
            <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control" />
          </div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_reset; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-default"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>