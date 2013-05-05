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
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="buttons">
          <button type="submit" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
          <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
        <div class="control-group required">
          <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
          <div class="controls">
            <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" />
            <?php if ($error_name) { ?>
            <span class="error"><?php echo $error_name; ?></span>
            <?php  } ?>
          </div>
        </div>
        <div class="control-group">
          <div class="control-label"><?php echo $entry_access; ?></div>
          <div class="controls">
            <div class="well well-small scrollbox">
              <?php foreach ($permissions as $permission) { ?>
              <label class="checkbox">
                <?php if (in_array($permission, $access)) { ?>
                <input type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" checked="checked" />
                <?php echo $permission; ?>
                <?php } else { ?>
                <input type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" />
                <?php echo $permission; ?>
                <?php } ?>
              </label>
              <?php } ?>
            </div>
            <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a></div>
        </div>
        <div class="control-group">
          <div class="control-label"><?php echo $entry_modify; ?></div>
          <div class="controls">
            <div class="well well-small scrollbox">
              <?php foreach ($permissions as $permission) { ?>
              <label class="checkbox">
                <?php if (in_array($permission, $modify)) { ?>
                <input type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>" checked="checked" />
                <?php echo $permission; ?>
                <?php } else { ?>
                <input type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>" />
                <?php echo $permission; ?>
                <?php } ?>
              </label>
              <?php } ?>
            </div>
            <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a></div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 