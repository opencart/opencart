<?php echo $header; ?>
<div class="container">
  <h1><?php echo $heading_step_1; ?></h1>
  <div class="row">
    <div class="col-sm-9">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <div class="terms"><?php echo $text_terms; ?></div>
        <div class="buttons">
          <div class="pull-right">
            <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
          </div>
        </div>
      </form>
    </div>
    <div class="col-sm-3">
      <ul class="list-group">
        <li class="list-group-item"><b><?php echo $text_license; ?></b></li>
        <li class="list-group-item"><?php echo $text_installation; ?></li>
        <li class="list-group-item"><?php echo $text_configuration; ?></li>
        <li class="list-group-item"><?php echo $text_finished; ?></li>
      </ul>
    </div>
  </div>
</div>
<?php echo $footer; ?>