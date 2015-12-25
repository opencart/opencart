<?php echo $header; ?>
<div class="container">
  <header>
    <div class="row">
      <div class="col-sm-12"><img src="view/image/logo.png" alt="OpenCart" title="OpenCart" /></div>
    </div>
  </header>
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
          <p><b>Follow these steps carefully!</b></p>
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
          <div class="text-right">
            <input type="submit" value="Continue" class="button" />
          </div>
        </div>
      </form>
    </div>
    <div class="col-sm-3">
      <ul class="list-group">
        <li class="list-group-item"><b>Upgrade</b></li>
        <li class="list-group-item">Finished</li>
      </ul>
    </div>
  </div>
</div>
<?php echo $footer; ?>