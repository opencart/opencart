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
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="buttons"><a onclick="$('#form').submit();" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></a> <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
        <div class="control-group">
          <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_name; ?></label>
          <div class="controls">
            <input type="text" name="name" value="<?php echo $name; ?>" />
            <?php if ($error_name) { ?>
            <span class="error"><?php echo $error_name; ?></span>
            <?php  } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_access; ?></label>
          <div class="controls">
            <div class="scrollbox">
              <?php $class = 'odd'; ?>
              <?php foreach ($permissions as $permission) { ?>
              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
              <div class="<?php echo $class; ?>">
                <?php if (in_array($permission, $access)) { ?>
                <input type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" checked="checked" />
                <?php echo $permission; ?>
                <?php } else { ?>
                <input type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" />
                <?php echo $permission; ?>
                <?php } ?>
              </div>
              <?php } ?>
            </div>
            <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a> </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_modify; ?></label>
          <div class="controls">
            <div class="scrollbox">
              <?php $class = 'odd'; ?>
              <?php foreach ($permissions as $permission) { ?>
              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
              <div class="<?php echo $class; ?>">
                <?php if (in_array($permission, $modify)) { ?>
                <input type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>" checked="checked" />
                <?php echo $permission; ?>
                <?php } else { ?>
                <input type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>" />
                <?php echo $permission; ?>
                <?php } ?>
              </div>
              <?php } ?>
            </div>
            <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a> </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 