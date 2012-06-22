<?php echo $header; ?>
<h1 style="background: url('view/image/configuration.png') no-repeat;">Upgrade</h1>
<div style="width: 100%; display: inline-block;">
  <div style="float: left; width: 569px;">
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <p><b>Follow these steps carefully!</b></p>
	  <ol>
	    <li>Post any upgrade script errors problems in the forums</li>
		<li>After upgrade, clear any cookies in your browser to avoid getting token errors.</li>
		<li>Load the admin page & press Ctrl+F5 twice to force the browser to update the css changes.</li>
		<li>Goto Admin->Users->User Groups and Edit the Top Adminstrator group. Check All boxes.</li>
		<li>Goto Admin and Edit the main System Settings. Update all fields and click save, even if nothing changed.</li>
		<li>Load the store front & press Ctrl+F5 twice to force the browser to update the css changes.</li>
	  </ol>
      <div style="text-align: right;"><a onclick="document.getElementById('form').submit()" class="button"><span class="button_left button_continue"></span><span class="button_middle">Upgrade</span><span class="button_right"></span></a></div>
    </form>
  </div>
  <div style="float: right; width: 205px; height: 400px; padding: 10px; color: #663300; border: 1px solid #FFE0CC; background: #FFF5CC;">
    <ul>
      <li><b>Upgrade</b></li>
      <li>Finished</li>
    </ul>
  </div>
</div>
<?php echo $footer; ?>

