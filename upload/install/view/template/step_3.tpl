<?php echo $header; ?>
<div class="container">
  <ul class="wizard">         
    <li><span class="badge">1</span> <span class="hidden-xs"><?php echo $text_license; ?> <span class="fa fa-check"></span></span></li>
    <li><span class="badge">2</span> <span class="hidden-xs"><?php echo $text_installation; ?> <span class="fa fa-check"></span></span></li>
    <li class="active"><span class="badge">3</span> <span class="hidden-xs"><?php echo $text_configuration; ?></span></li>
    <li><span class="badge">4</span> <span class="hidden-xs"><?php echo $text_finished; ?></span></li>
  </ul>
  <h1><?php echo $heading_step_3; ?></h1>
  <hr>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <form action="<?php echo $action; ?>" method="post" id="startInstallationForm" enctype="multipart/form-data" class="form-horizontal">
	<h4><?php echo $text_db_connection; ?></h4>
	<fieldset>
	  <div class="form-group">
		<label class="col-sm-2 control-label" for="input-db-driver"><?php echo $entry_db_driver; ?></label>
		<div class="col-sm-10">
		  <select name="db_driver" id="input-db-driver" class="form-control">
			<?php if ($mysqli) { ?>
			<?php if ($db_driver == 'mysqli') { ?>
			<option value="mysqli" selected="selected"><?php echo $text_mysqli; ?></option>
			<?php } else { ?>
			<option value="mysqli"><?php echo $text_mysqli; ?></option>
			<?php } ?>
			<?php } ?>
			<?php if ($mysql) { ?>
			<?php if ($db_driver == 'mysql') { ?>
			<option value="mysql" selected="selected"><?php echo $text_mysql; ?></option>
			<?php } else { ?>
			<option value="mysql"><?php echo $text_mysql; ?></option>
			<?php } ?>
			<?php } ?>
			<?php if ($pdo) { ?>
			<?php if ($db_driver == 'mpdo') { ?>
			<option value="mpdo" selected="selected"><?php echo $text_mpdo; ?></option>
			<?php } else { ?>
			<option value="mpdo"><?php echo $text_mpdo; ?></option>
			<?php } ?>
			<?php } ?>
		  </select>
		</div>
	  </div>
	  <div class="form-group required <?php if ($error_db_hostname) { echo "has-error"; } ?>">
		<label class="col-sm-2 control-label" for="input-db-hostname"><?php echo $entry_db_hostname; ?></label>
		<div class="col-sm-10">
		  <input type="text" name="db_hostname" value="<?php echo $db_hostname; ?>" id="input-db-hostname" class="form-control" />
		  <?php if ($error_db_hostname) { ?>
		  <div class="text-danger"><span class="fa fa-warning"></span> <?php echo $error_db_hostname; ?></div>
		  <?php } ?>
		</div>
	  </div>
	  <div class="form-group required <?php if ($error_db_username) { echo "has-error"; } ?>">
		<label class="col-sm-2 control-label" for="input-db-username"><?php echo $entry_db_username; ?></label>
		<div class="col-sm-10">
		  <input type="text" name="db_username" value="<?php echo $db_username; ?>" id="input-db-username" class="form-control" />
		  <?php if ($error_db_username) { ?>
		  <div class="text-danger"><span class="fa fa-warning"></span> <?php echo $error_db_username; ?></div>
		  <?php } ?>
		</div>
	  </div>
	  <div class="form-group">
		<label class="col-sm-2 control-label" for="input-db-password"><?php echo $entry_db_password; ?></label>
		<div class="col-sm-10">
		  <input type="password" name="db_password" value="<?php echo $db_password; ?>" id="input-db-password" class="form-control" />
		</div>
	  </div>
	  <div class="form-group <?php if ($error_db_database) { echo "has-error"; } ?>">
		<label class="col-sm-2 control-label" for="input-db-database"><?php echo $entry_db_database; ?></label>
		<div class="col-sm-10">
		  <input type="text" name="db_database" value="<?php echo $db_database; ?>" id="input-db-database" class="form-control" />
		  <?php if ($error_db_database) { ?>
		  <div class="text-danger"><span class="fa fa-warning"></span> <?php echo $error_db_database; ?></div>
		  <?php } ?>
		</div>
	  </div>
	  <div class="form-group">
		<label class="col-sm-2 control-label" for="input-db-prefix"><?php echo $entry_db_prefix; ?></label>
		<div class="col-sm-10">
		  <input type="text" name="db_prefix" value="<?php echo $db_prefix; ?>" id="input-db-prefix" class="form-control" />
		</div>
	  </div>
	</fieldset>
	<h4><?php echo $text_db_administration; ?></h4>
	<fieldset>
	  <div class="form-group required <?php if ($error_username) { echo "has-error"; } ?>">
		<label class="col-sm-2 control-label" for="input-username"><?php echo $entry_username; ?></label>
		<div class="col-sm-10">
		  <input type="text" name="username" value="<?php echo $username; ?>" id="input-username" class="form-control" />
		  <?php if ($error_username) { ?>
		  <div class="text-danger"><span class="fa fa-warning"></span> <?php echo $error_username; ?></div>
		  <?php } ?>
		</div>
	  </div>
	  <div class="form-group required <?php if ($error_password) { echo "has-error"; } ?>">
		<label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
		<div class="col-sm-10">
		  <input type="password" name="password" value="<?php echo $password; ?>" id="input-password" class="form-control" />
		  <?php if ($error_password) { ?>
		  <div class="text-danger"><span class="fa fa-warning"></span> <?php echo $error_password; ?></div>
		  <?php } ?>
		</div>
	  </div>
	  <div class="form-group required <?php if ($error_email) { echo "has-error"; } ?>">
		<label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
		<div class="col-sm-10">
		  <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control" />
		  <?php if ($error_email) { ?>
		  <div class="text-danger"><span class="fa fa-warning"></span> <?php echo $error_email; ?></div>
		  <?php } ?>
		</div>
	  </div>
	</fieldset>
	<div class="buttons">
	  <hr>
	  <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><span class="fa fa-chevron-left"></span> <?php echo $button_back; ?></a></div>
	  <div class="pull-right">
		<button type="submit" class="btn btn-primary" id="startInstallationBtn" data-loading-text="Checking...">
			<?php echo $button_continue; ?> <span class="fa fa-chevron-right"></span>
		</button>
	  </div>
	</div>
  </form>
</div>
<?php echo $footer; ?>