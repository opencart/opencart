<?php echo $header; ?>
<div class="container">
  <header>
    <div class="row">
      <div class="col-sm-6">
        <h1 class="pull-left">2<small>/2</small></h1>
        <h3><?php echo $heading_title; ?><br>
          <small><?php echo $text_success; ?></small></h3>
      </div>
      <div class="col-sm-6">
        <div id="logo" class="pull-right hidden-xs"><img src="view/image/logo.png" alt="OpenCart" title="OpenCart" /></div>
      </div>
    </div>
  </header>
  <div class="row">
    <div class="col-sm-9">
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
      <div class="visit">
        <div class="row">
          <div class="col-sm-5 col-sm-offset-1 text-center">
            <p><i class="fa fa-shopping-cart fa-5x"></i></p>
            <a href="../" class="btn btn-secondary"><?php echo $text_catalog; ?></a></div>
          <div class="col-sm-5 text-center">
            <p><i class="fa fa-cog fa-5x white"></i></p>
            <a href="../admin/" class="btn btn-secondary"><?php echo $text_admin; ?></a></div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <h2>Log</h2>
          <textarea class="form-control">
          
          </textarea>
        </div>
      </div>
    </div>
    <div class="col-sm-3"><?php echo $column_left; ?></div>
  </div>
</div>
<?php echo $footer; ?>