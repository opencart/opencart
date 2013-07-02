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
      <h1><i class="icon-edit icon-large"></i> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <button type="submit" form="form-marketing" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
    </div>
    <div class="box-content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-marketing" class="form-horizontal">
        <div class="control-group required">
          <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
          <div class="controls">
            <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" />
            <?php if ($error_name) { ?>
            <span class="error"><?php echo $error_name; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-description"><?php echo $entry_description; ?></label>
          <div class="controls">
            <textarea name="description" cols="40" rows="5" placeholder="<?php echo $entry_description; ?>" id="input-description"><?php echo $description; ?></textarea>
          </div>
        </div>
        <div class="control-group required">
          <label class="control-label" for="input-code"><?php echo $entry_code; ?> <span class="help-block"><?php echo $help_code; ?></span></label>
          <div class="controls">
            <input type="text" name="code" value="<?php echo $code; ?>" placeholder="<?php echo $entry_code; ?>" id="input-code" />
            <?php if ($error_code) { ?>
            <span class="error"><?php echo $error_code; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group required">
          <label class="control-label" for="input-example"><?php echo $entry_example; ?> <span class="help-block"><?php echo $help_example; ?></span></label>
          <div class="controls">
            <textarea cols="40" rows="5" id="input-example">http://www.opencart.com?tracking=1234567</textarea>
          </div>
        </div>        
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#input-code').on('keydown', function() {
	$('#input-example').val();
});

$('#input-code').trigger('keydown');
//--></script>
<?php echo $footer; ?>