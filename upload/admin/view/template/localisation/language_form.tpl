<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page"> <span class="required">*</span> <?php echo $entry_name; ?><br />
    <input type="text" name="name" value="<?php echo $name; ?>" />
    <br />
    <?php if ($error_name) { ?>
    <span class="error"><?php echo $error_name; ?></span>
    <?php } ?>
    <br />
    <span class="required">*</span> <?php echo $entry_code; ?><br />
    <input type="text" name="code" value="<?php echo $code; ?>" />
    <br />
    <?php if ($error_code) { ?>
    <span class="error"><?php echo $error_code; ?></span>
    <?php } ?>
    <br />
    <span class="required">*</span> <?php echo $entry_locale; ?><br />
    <input type="text" name="locale" value="<?php echo $locale; ?>" />
    <br />
    <?php if ($error_locale) { ?>
    <span class="error"><?php echo $error_locale; ?></span>
    <?php } ?>
    <br />
    <span class="required">*</span> <?php echo $entry_image; ?><br />
    <input type="text" name="image" value="<?php echo $image; ?>" />
    <br />
    <?php if ($error_image) { ?>
    <span class="error"><?php echo $error_image; ?></span>
    <?php } ?>
    <br />
    <span class="required">*</span> <?php echo $entry_directory; ?><br />
    <input type="text" name="directory" value="<?php echo $directory; ?>" />
    <br />
    <?php if ($error_directory) { ?>
    <span class="error"><?php echo $error_directory; ?></span>
    <?php } ?>
    <br />
    <span class="required">*</span> <?php echo $entry_filename; ?><br />
    <input type="text" name="filename" value="<?php echo $filename; ?>" />
    <br />
    <?php if ($error_filename) { ?>
    <span class="error"><?php echo $error_filename; ?></span>
    <?php } ?>
    <br />
    <?php echo $entry_status; ?><br />
    <select name="status">
      <?php if ($status) { ?>
      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
      <option value="0"><?php echo $text_disabled; ?></option>
      <?php } else { ?>
      <option value="1"><?php echo $text_enabled; ?></option>
      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
      <?php } ?>
    </select>
    <br />
    <br />
    <?php echo $entry_sort_order; ?><br />
    <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" />
  </div>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
