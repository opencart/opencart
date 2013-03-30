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
        <div class="control-group">
          <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_name; ?></label>
          <div class="controls">
            <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" />
            <?php if ($error_name) { ?>
            <span class="error"><?php echo $error_name; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_code; ?></label>
          <div class="controls">
            <input type="text" name="code" value="<?php echo $code; ?>" placeholder="<?php echo $entry_code; ?>" />
            <?php if ($error_code) { ?>
            <span class="error"><?php echo $error_code; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_locale; ?></label>
          <div class="controls">
            <input type="text" name="locale" value="<?php echo $locale; ?>" placeholder="<?php echo $entry_locale; ?>" />
            <?php if ($error_locale) { ?>
            <span class="error"><?php echo $error_locale; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_image; ?></label>
          <div class="controls">
            <input type="text" name="image" value="<?php echo $image; ?>" placeholder="<?php echo $entry_image; ?>" />
            <?php if ($error_image) { ?>
            <span class="error"><?php echo $error_image; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_directory; ?></label>
          <div class="controls">
            <input type="text" name="directory" value="<?php echo $directory; ?>" placeholder="<?php echo $entry_directory; ?>" />
            <?php if ($error_directory) { ?>
            <span class="error"><?php echo $error_directory; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_filename; ?></label>
          <div class="controls">
            <input type="text" name="filename" value="<?php echo $filename; ?>" placeholder="<?php echo $entry_filename; ?>" />
            <?php if ($error_filename) { ?>
            <span class="error"><?php echo $error_filename; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_status; ?></label>
          <div class="controls">
            <select name="status">
              <?php if ($status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_sort_order; ?></label>
          <div class="controls">
            <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="input-mini" />
          </div>
        </div>
        <div class="buttons"><a onclick="$('#form').submit();" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></a> <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>