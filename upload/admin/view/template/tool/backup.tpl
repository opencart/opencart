<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="icon-ok-sign"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class=""></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $restore; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="control-group">
          <label class="control-label" for="input-import"><?php echo $entry_restore; ?></label>
          <div class="controls">
            <input type="file" name="import" id="input-import" />
          </div>
        </div>
        <button type="submit" class="btn"><i class="icon-upload"></i> <?php echo $button_restore; ?></button>
      </form>
      <form action="<?php echo $backup; ?>" method="post" enctype="multipart/form-data" id="backup">
        <div class="control-group">
          <div class="control-label"><?php echo $entry_backup; ?></div>
          <div class="controls">
            <div class="well well-small scrollbox">
              <?php foreach ($tables as $table) { ?>
              <label class="checkbox">
                <input type="checkbox" name="backup[]" value="<?php echo $table; ?>" checked="checked" />
                <?php echo $table; ?></label>
              <?php } ?>
            </div>
            <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a></div>
        </div>
        <button type="submit" class="btn"><i class="icon-download"></i> <?php echo $button_backup; ?></button>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>