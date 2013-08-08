<?php echo $header; ?>
<div class="container">
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
  <div class="panel">
    <div class="panel-heading">
      <h1 class="panel-title"><i class="icon-edit icon-large"></i> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <button type="submit" form="form-length-class" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
    </div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-length-class" class="form-horizontal">
      <div class="form-group required">
        <div class="col-lg-3 control-label"><?php echo $entry_title; ?></div>
        <div class="col-lg-9">
          <?php foreach ($languages as $language) { ?>
          <input type="text" name="length_class_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($length_class_description[$language['language_id']]) ? $length_class_description[$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" />
          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
          <?php if (isset($error_title[$language['language_id']])) { ?>
          <span class="error"><?php echo $error_title[$language['language_id']]; ?></span><br />
          <?php } ?>
          <?php } ?>
        </div>
      </div>
      <div class="form-group required">
        <div class="col-lg-3 control-label"><?php echo $entry_unit; ?></div>
        <div class="col-lg-9">
          <?php foreach ($languages as $language) { ?>
          <input type="text" name="length_class_description[<?php echo $language['language_id']; ?>][unit]" value="<?php echo isset($length_class_description[$language['language_id']]) ? $length_class_description[$language['language_id']]['unit'] : ''; ?>" placeholder="<?php echo $entry_unit; ?>" />
          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
          <?php if (isset($error_unit[$language['language_id']])) { ?>
          <span class="error"><?php echo $error_unit[$language['language_id']]; ?></span><br />
          <?php } ?>
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="input-value"><?php echo $entry_value; ?> <span class="help-block"><?php echo $help_value; ?></span></label>
        <div class="col-lg-9">
          <input type="text" name="value" value="<?php echo $value; ?>" placeholder="<?php echo $entry_value; ?>" id="input-value" />
        </div>
      </div>
    </form>
  </div>
</div>
<?php echo $footer; ?>