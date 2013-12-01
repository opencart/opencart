<?php echo $header; ?>
<div class="container">
  <h1><?php echo $heading_step_4; ?></h1>
  <div class="alert alert-danger">Don't forget to delete your installation directory!</div>
  <div class="row">
    <div class="col-sm-9">
      <p>Congratulations! You have successfully installed OpenCart.</p>
      <div class="row">
        <div class="col-sm-6"><a href="../"><img src="view/image/screenshot_1.png" alt="" /></a><br />
          <a href="../">Go to your Online Shop</a></div>
        <div class="col-sm-6"><a href="../admin/"><img src="view/image/screenshot_2.png" alt="" /></a><br />
          <a href="../admin/">Login to your Administration</a></div>
      </div>
    </div>
    <div class="col-sm-3">
      <ul>
        <li><?php echo $text_license; ?></li>
        <li><?php echo $text_installation; ?></li>
        <li><?php echo $text_configuration; ?></li>
        <li><b><?php echo $text_finished; ?></b></li>
      </ul>
    </div>
  </div>
</div>
<?php echo $footer; ?>