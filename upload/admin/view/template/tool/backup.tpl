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
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="icon-ok-sign"></i> <?php echo $success; ?>
    <button type="button" form="form-backup" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h1 class="panel-title"><i class="icon-exchange"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $restore; ?>" method="post" enctype="multipart/form-data" id="form-backup" class="form-horizontal">
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-import"><?php echo $entry_restore; ?></label>
          <div class="col-sm-10">
            <input type="file" name="import" id="input-import" />
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <button type="submit" class="btn btn-default"><i class="icon-upload"></i> <?php echo $button_restore; ?></button>
          </div>
        </div>
      </form>
      <form action="<?php echo $backup; ?>" method="post" enctype="multipart/form-data" id="backup" class="form-horizontal">
        <div class="form-group">
          <label class="col-sm-2 control-label"><?php echo $entry_backup; ?></label>
          <div class="col-sm-10">
            <div class="well">
              <?php foreach ($tables as $table) { ?>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="backup[]" value="<?php echo $table; ?>" checked="checked" />
                  <?php echo $table; ?></label>
              </div>
              <?php } ?>
            </div>
            <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a></div>
        </div>
        <button type="submit" class="btn btn-default"><i class="icon-download"></i> <?php echo $button_backup; ?></button>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>