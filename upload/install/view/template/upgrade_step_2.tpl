<?php echo $header; ?>
<div class="container">
  <ul class="wizard">         
    <li><span class="badge">1</span> <span class="hidden-xs"><?php echo $text_upgrade; ?> <span class="fa fa-check"></span></span></li>
    <li class="active"><span class="badge">2</span> <span class="hidden-xs"><?php echo $text_finished; ?> <span class="fa fa-check"></span></span></li>
  </ul>
  <h1><?php echo $heading_step_2; ?></h1>
  <hr>
  <h4 class="text-success"><?php echo $text_upgrade_finished; ?> <span class="fa fa-thumbs-o-up"></span></h4>
  <div class="row">
	<div class="col-sm-6">
		<div class="thumbnail">
			<img src="view/image/frontend.png" alt="" />
			<div class="caption">
				<br>
				<p><a href="../" class="btn btn-primary"><?php echo $text_shop; ?></a></p>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="thumbnail">
			<img src="view/image/backend.png" alt="" />
			<div class="caption">
				<br>
				<p><a href="../admin/" class="btn btn-primary"><?php echo $text_login; ?></a></p>
			</div>
		</div>
	</div>
  </div>
  <div class="alert alert-warning">
    <?php echo $text_forget; ?> <button class="btn btn-warning btn-sm pull-right" id="deleteUpgradeDirBtn" data-loading-text="<?php echo $button_deleting; ?>"><span class="fa fa-trash-o"></span> <?php echo $button_delete; ?></button><br><br>
  </div>
</div>
<?php echo $footer; ?>