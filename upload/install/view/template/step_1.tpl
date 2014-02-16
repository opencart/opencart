<?php echo $header; ?>
<div class="container">
  <ul class="wizard">         
    <li class="active"><span class="badge">1</span> <span class="hidden-xs"><?php echo $text_license; ?></span></li>
    <li><span class="badge">2</span> <span class="hidden-xs"><?php echo $text_installation; ?></span></li>
    <li><span class="badge">3</span> <span class="hidden-xs"><?php echo $text_configuration; ?></span></li>
    <li><span class="badge">4</span> <span class="hidden-xs"><?php echo $text_finished; ?></span></li>
  </ul>
  <h1><?php echo $heading_step_1; ?></h1>
  <hr>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <div class="terms"><?php echo $text_terms; ?></div>
    <div class="buttons">
	  <hr>
      <div class="pull-right">
        <button type="submit" class="btn btn-primary" data-loading-text="Loading..."><?php echo $button_continue; ?> <span class="fa fa-chevron-right"></span></button>
      </div>
    </div>
  </form>
</div>
<?php echo $footer; ?>