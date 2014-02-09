<?php echo $header; ?>
<div class="container">
  <h1><?php echo $heading_step_1; ?></h1>
  <div class="row">
    <div class="col-sm-9">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <div class="terms"><?php echo $text_terms; ?></div>
        <div class="buttons">
          <div class="pull-right">
            <button type="submit" class="btn btn-primary">
				<?php echo $button_continue; ?> <span class="fa fa-chevron-right"></span>
			</button>
          </div>
        </div>
      </form>
    </div>
    <div class="col-sm-3">
      <ul class="nav nav-pills nav-stacked">
        <li class="active"><a href="index.php?route=step_1"><?php echo $text_license; ?></a></li>
        <li><a href="index.php?route=step_1"><?php echo $text_installation; ?></a></li>
        <li><a href="index.php?route=step_1"><?php echo $text_configuration; ?></a></li>
        <li><a href="index.php?route=step_1"><?php echo $text_finished; ?></a></li>
      </ul>
    </div>
  </div>
</div>
<?php echo $footer; ?>