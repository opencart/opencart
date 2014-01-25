<?php echo $header; ?>
<div class="container">
  <h1><?php echo $heading_step_4; ?></h1>
  <div class="alert alert-danger"><?php echo $text_forget; ?></div>
  <div class="row">
    <div class="col-sm-9">
      <h4><?php echo $text_congratulation; ?></h4>
      <div class="row">
        <div class="col-sm-6">
			<div class="thumbnail">
				<img src="view/image/screenshot-1.png" alt="" />
				<div class="caption">
					<br>
					<p><a href="../" class="btn btn-primary"><?php echo $text_shop; ?></a></p>
				</div>
			</div>
		</div>
        <div class="col-sm-6">
			<div class="thumbnail">
				<img src="view/image/screenshot-2.png" alt="" />
				<div class="caption">
					<br>
					<p><a href="../admin/" class="btn btn-primary"><?php echo $text_login; ?></a></p>
				</div>
			</div>
		</div>
      </div>
    </div>
    <div class="col-sm-3">
      <ul class="nav nav-pills nav-stacked">
        <li><a href="index.php?route=step_4"><?php echo $text_license; ?> <span class="fa fa-check"></span></a></li>
        <li><a href="index.php?route=step_4"><?php echo $text_installation; ?> <span class="fa fa-check"></span></a></li>
        <li><a href="index.php?route=step_4"><?php echo $text_configuration; ?> <span class="fa fa-check"></span></a></li>
        <li class="active"><a href="index.php?route=step_4"><?php echo $text_finished; ?></a></li>
      </ul>
    </div>
  </div>
</div>
<?php echo $footer; ?>