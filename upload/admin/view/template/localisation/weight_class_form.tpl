<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="buttons"><button type="submit" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></button> <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
        <div class="control-group">
          <div class="control-label"><span class="required">*</span> <?php echo $entry_title; ?></div>
          <div class="controls">
            <?php foreach ($languages as $language) { ?>
            <input type="text" name="weight_class_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($weight_class_description[$language['language_id']]) ? $weight_class_description[$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" />
            <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
            <?php if (isset($error_title[$language['language_id']])) { ?>
            <span class="error"><?php echo $error_title[$language['language_id']]; ?></span><br />
            <?php } ?>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <div class="control-label"><span class="required">*</span> <?php echo $entry_unit; ?></div>
          <div class="controls">
            <?php foreach ($languages as $language) { ?>
            <input type="text" name="weight_class_description[<?php echo $language['language_id']; ?>][unit]" value="<?php echo isset($weight_class_description[$language['language_id']]) ? $weight_class_description[$language['language_id']]['unit'] : ''; ?>" placeholder="<?php echo $entry_unit; ?>" />
            <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
            <?php if (isset($error_unit[$language['language_id']])) { ?>
            <span class="error"><?php echo $error_unit[$language['language_id']]; ?></span><br />
            <?php } ?>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-value"><?php echo $entry_value; ?></label>
          <div class="controls">
            <input type="text" name="value" value="<?php echo $value; ?>" placeholder="<?php echo $entry_value; ?>" id="input-value" />
            
            <a data-toggle="tooltip" title="<?php echo $help_value; ?>"><i class="icon-info-sign"></i></a>
            
            </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>