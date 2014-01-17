<?php echo $header; ?>
<div class="container">
  <h1><?php echo $heading_step_4; ?></h1>
  <div class="alert alert-danger"><?php echo $text_forget; ?></div>
  <div class="row">
    <div class="col-sm-9">
      <p><?php echo $text_congratulation; ?></p>
      <div class="row">
        <div class="col-sm-6"><a href="../"><img src="view/image/screenshot-1.png" alt="" class="img-thumbnail" /></a><br />
          <a href="../"><?php echo $text_shop; ?></a></div>
        <div class="col-sm-6"><a href="../admin/"><img src="view/image/screenshot-2.png" alt="" class="img-thumbnail" /></a><br />
          <a href="../admin/"><?php echo $text_login; ?></a></div>
      </div>
    </div>
    <div class="col-sm-3">
      <ul class="list-group">
        <li class="list-group-item"><?php echo $text_license; ?></li>
        <li class="list-group-item"><?php echo $text_installation; ?></li>
        <li class="list-group-item"><?php echo $text_configuration; ?></li>
        <li class="list-group-item"><b><?php echo $text_finished; ?></b></li>
      </ul>
    </div>
  </div>
</div>
<?php echo $footer; ?>