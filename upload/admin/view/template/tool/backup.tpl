<?php echo $header; ?>

<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class=""></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $restore; ?>" method="post" enctype="multipart/form-data" id="restore">
        <div class="buttons"><a onclick="$('#restore').submit();" class="btn"><i class="icon-upload"></i> <?php echo $button_restore; ?></a> <a onclick="$('#backup').submit();" class="btn"><i class="icon-download"></i> <?php echo $button_backup; ?></a></div>
        <table class="form">
          <tr>
            <td><?php echo $entry_restore; ?></td>
            <td><input type="file" name="import" /></td>
          </tr>
        </table>
      </form>
      <form action="<?php echo $backup; ?>" method="post" enctype="multipart/form-data" id="backup">
        <div class="control-group">
          <label class="control-label"><?php echo $entry_backup; ?></label>
          <div class="controls">
            <?php foreach ($tables as $table) { ?>
            <label class="checkbox">
              <input type="checkbox" name="backup[]" value="<?php echo $table; ?>" checked="checked" />
              <?php echo $table; ?></label>
            <?php } ?>
            <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a> </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>