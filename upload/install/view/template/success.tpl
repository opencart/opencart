<?php echo $header; ?>
<div class="container">
  <h1>Upgrade - Finished</h1>
  <div class="alert alert-danger">Don't forget to delete your installation directory!</div>
  <div class="row">
    <div class="col-sm-9">
      <h4>Congratulations! You have successfully upgraded OpenCart.</h4>
      <div class="row">
        <div class="col-sm-6">
			<div class="thumbnail">
				<img src="view/image/screenshot-1.png" alt="" />
				<div class="caption">
					<br>
					<p><a href="../" class="btn btn-primary">Goto your Online Shop</a></p>
				</div>
			</div>
		</div>
        <div class="col-sm-6">
			<div class="thumbnail">
				<img src="view/image/screenshot-2.png" alt="" />
				<div class="caption">
					<br>
					<p><a href="../admin/" class="btn btn-primary">Login to your Administration</a></p>
				</div>
			</div>
		</div>
      </div>
    </div>
    <div class="col-sm-3">
      <ul class="nav nav-pills nav-stacked">
        <li><a href="index.php?route=upgrade/success">Upgrade <span class="fa fa-check"></span></a></li>
        <li class="active"><a href="index.php?route=upgrade/success">Finished</a></li>
      </ul>
    </div>
  </div>
</div>
<?php echo $footer; ?>