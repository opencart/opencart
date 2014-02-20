<?php echo $header; ?>
<div class="container">
  <h1>Upgrade</h1>
  <div class="row">
    <div class="col-sm-9">
	  <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
      <?php } ?>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <fieldset>
          <h4>Follow these steps carefully!</h4>
          <ol>
            <li>Post any upgrade script errors problems in the forums</li>
            <li>After upgrade, clear any cookies in your browser to avoid getting token errors.</li>
            <li>Load the admin page & press Ctrl+F5 twice to force the browser to update the css changes.</li>
            <li>Goto Admin -> Users -> User Groups and Edit the Top Adminstrator group. Check All boxes.</li>
            <li>Goto Admin and Edit the main System Settings. Update all fields and click save, even if nothing changed.</li>
            <li>Load the store front & press Ctrl+F5 twice to force the browser to update the css changes.</li>
          </ol>
        </fieldset>
        <div class="buttons">
          <div class="pull-right">
            <button type="submit" class="btn btn-primary">
				Continue <span class="fa fa-chevron-right"></span>
			</button>
          </div>
        </div>
      </form>
    </div>
    <div class="col-sm-3">
      <ul class="nav nav-pills nav-stacked">
        <li class="active"><a href="index.php?route=upgrade">Upgrade</a></li>
        <li><a href="index.php?route=upgrade">Finished</a></li>
      </ul>
    </div>
  </div>
</div>
<?php echo $footer; ?>