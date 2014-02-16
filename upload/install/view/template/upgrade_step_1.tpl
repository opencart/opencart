<?php echo $header; ?>
<div class="container">
  <ul class="wizard">         
    <li class="active"><span class="badge">1</span> <span class="hidden-xs"><?php echo $text_upgrade; ?></span></li>
    <li><span class="badge">2</span> <span class="hidden-xs"><?php echo $text_finished; ?></span></li>
  </ul>
  <h1><?php echo $heading_step_1; ?></h1>
  <hr>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
	<button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="startUpgradeForm">
	<fieldset>
	  <h4><?php echo $text_upgrade_tasks; ?></h4>
	  <?php echo $text_upgrade_steps; ?>
	</fieldset>
	<div class="buttons">
	  <hr>
	  <div class="pull-right">
		<button type="submit" class="btn btn-primary" id="startUpgradeBtn" data-loading-text="<?php echo $button_checking; ?>">
			<?php echo $button_continue; ?> <span class="fa fa-chevron-right"></span>
		</button>
	  </div>
	</div>
  </form>

</div>
<?php echo $footer; ?>