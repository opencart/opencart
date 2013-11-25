<?php echo $header; ?>
<h1>Upgrade</h1>
<div id="column-right">
  <ul>
    <li><b>Upgrade</b></li>
    <li>Finished</li>
  </ul>
</div>
<div id="content">
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
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
      <div class="right">
        <input type="submit" value="Continue" class="button" />
      </div>
    </div>
  </form>
</div>
<?php echo $footer; ?> 