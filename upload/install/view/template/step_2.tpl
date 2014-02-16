<?php echo $header; ?>
<div class="container">
  <ul class="wizard">         
    <li><span class="badge">1</span> <span class="hidden-xs"><?php echo $text_license; ?> <span class="fa fa-check"></span></span></li>
    <li class="active"><span class="badge">2</span> <span class="hidden-xs"><?php echo $text_installation; ?></span></li>
    <li><span class="badge">3</span> <span class="hidden-xs"><?php echo $text_configuration; ?></span></li>
    <li><span class="badge">4</span> <span class="hidden-xs"><?php echo $text_finished; ?></span></li>
  </ul>
  <h1><?php echo $heading_step_2; ?></h1>
  <hr>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
	<h4><?php echo $text_install_php; ?></h4>
	<fieldset>
	  <table class="table">
		<thead>
		  <tr>
			<td width="35%"><b><?php echo $text_setting; ?></b></td>
			<td width="25%"><b><?php echo $text_current; ?></b></td>
			<td width="25%"><b><?php echo $text_required; ?></b></td>
			<td width="15%" class="text-center"><b><?php echo $text_status; ?></b></td>
		  </tr>
		</thead>
		<tbody>
		  <tr>
			<td><?php echo $text_version; ?></td>
			<td><?php echo $php_version; ?></td>
			<td>5.3+</td>
			<td class="text-center"><?php if ($php_version >= '5.3') { ?>
			  <span class="text-success"><i class="fa fa-check-circle"></i></span>
			  <?php } else { ?>
			  <span class="text-danger"><i class="fa fa-minus-circle"></i></span>
			  <?php } ?></td>
		  </tr>
		  <tr>
			<td><?php echo $text_global; ?></td>
			<td><?php if ($register_globals) { ?>
			  <?php echo $text_on; ?>
			  <?php } else { ?>
			  <?php echo $text_off; ?>
			  <?php } ?></td>
			<td><?php echo $text_off; ?></td>
			<td class="text-center"><?php if (!$register_globals) { ?>
			  <span class="text-success"><i class="fa fa-check-circle"></i></span>
			  <?php } else { ?>
			  <span class="text-danger"><i class="fa fa-minus-circle"></i></span>
			  <?php } ?></td>
		  </tr>
		  <tr>
			<td><?php echo $text_magic; ?></td>
			<td><?php if ($magic_quotes_gpc) { ?>
			  <?php echo $text_on; ?>
			  <?php } else { ?>
			  <?php echo $text_off; ?>
			  <?php } ?></td>
			<td><?php echo $text_off; ?></td>
			<td class="text-center"><?php if (!$magic_quotes_gpc) { ?>
			  <span class="text-success"><i class="fa fa-check-circle"></i></span>
			  <?php } else { ?>
			  <span class="text-danger"><i class="fa fa-minus-circle"></i></span>
			  <?php } ?></td>
		  </tr>
		  <tr>
			<td><?php echo $text_upload; ?></td>
			<td><?php if ($file_uploads) { ?>
			  <?php echo $text_on; ?>
			  <?php } else { ?>
			  <?php echo $text_off; ?>
			  <?php } ?></td>
			<td><?php echo $text_on; ?></td>
			<td class="text-center"><?php if ($file_uploads) { ?>
			  <span class="text-success"><i class="fa fa-check-circle"></i></span>
			  <?php } else { ?>
			  <span class="text-danger"><i class="fa fa-minus-circle"></i></span>
			  <?php } ?></td>
		  </tr>
		  <tr>
			<td><?php echo $text_session; ?></td>
			<td><?php if ($session_auto_start) { ?>
			  <?php echo $text_on; ?>
			  <?php } else { ?>
			  <?php echo $text_off; ?>
			  <?php } ?></td>
			<td><?php echo $text_off; ?></td>
			<td class="text-center"><?php if (!$session_auto_start) { ?>
			  <span class="text-success"><i class="fa fa-check-circle"></i></span>
			  <?php } else { ?>
			  <span class="text-danger"><i class="fa fa-minus-circle"></i></span>
			  <?php } ?></td>
		  </tr>
		</tbody>
	  </table>
	</fieldset>
	<h4><?php echo $text_install_extension; ?></h4>
	<fieldset>
	  <table class="table">
		<thead>
		  <tr>
			<td width="35%"><b><?php echo $text_extension; ?></b></td>
			<td width="25%"><b><?php echo $text_current; ?></b></td>
			<td width="25%"><b><?php echo $text_required; ?></b></td>
			<td width="15%" class="text-center"><b><?php echo $text_status; ?></b></td>
		  </tr>
		</thead>
		<tbody>
		  <tr>
			<td><?php echo $text_gd; ?></td>
			<td><?php if ($gd) { ?>
			  <?php echo $text_on; ?>
			  <?php } else { ?>
			  <?php echo $text_off; ?>
			  <?php } ?></td>
			<td><?php echo $text_on; ?></td>
			<td class="text-center"><?php if ($gd) { ?>
			  <span class="text-success"><i class="fa fa-check-circle"></i></span>
			  <?php } else { ?>
			  <span class="text-danger"><i class="fa fa-minus-circle"></i></span>
			  <?php } ?></td>
		  </tr>
		  <tr>
			<td><?php echo $text_curl; ?></td>
			<td><?php if ($curl) { ?>
			  <?php echo $text_on; ?>
			  <?php } else { ?>
			  <?php echo $text_off; ?>
			  <?php } ?></td>
			<td><?php echo $text_on; ?></td>
			<td class="text-center"><?php if ($curl) { ?>
			  <span class="text-success"><i class="fa fa-check-circle"></i></span>
			  <?php } else { ?>
			  <span class="text-danger"><i class="fa fa-minus-circle"></i></span>
			  <?php } ?></td>
		  </tr>
		  <tr>
			<td><?php echo $text_mcrypt; ?></td>
			<td><?php if ($mcrypt_encrypt) { ?>
			  <?php echo $text_on; ?>
			  <?php } else { ?>
			  <?php echo $text_off; ?>
			  <?php } ?></td>
			<td><?php echo $text_on; ?></td>
			<td class="text-center"><?php if ($mcrypt_encrypt) { ?>
			  <span class="text-success"><i class="fa fa-check-circle"></i></span>
			  <?php } else { ?>
			  <span class="text-danger"><i class="fa fa-minus-circle"></i></span>
			  <?php } ?></td>
		  </tr>
		  <tr>
			<td><?php echo $text_zip; ?></td>
			<td><?php if ($zlib) { ?>
			  <?php echo $text_on; ?>
			  <?php } else { ?>
			  <?php echo $text_off; ?>
			  <?php } ?></td>
			<td><?php echo $text_on; ?></td>
			<td class="text-center"><?php if ($zlib) { ?>
			  <span class="text-success"><i class="fa fa-check-circle"></i></span>
			  <?php } else { ?>
			  <span class="text-danger"><i class="fa fa-minus-circle"></i></span>
			  <?php } ?></td>
		  </tr>
		</tbody>
	  </table>
	</fieldset>
	<h4><?php echo $text_install_file; ?></h4>
	<fieldset>
	  <table class="table">
		<thead>
		  <tr>
			<td><b><?php echo $text_file; ?></b></td>
			<td width="15%" class="text-center"><b><?php echo $text_status; ?></b></td>
		  </tr>
		</thead>
		<tbody>
		  <tr>
			<td><?php echo $config_catalog; ?></td>
			<td class="text-center"><?php if (!file_exists($config_catalog)) { ?>
			  <span class="text-danger"><i class="fa fa-minus-circle"></i> <?php echo $text_missing; ?></span>
			  <?php } elseif (!is_writable($config_catalog)) { ?>
			  <span class="text-danger"><i class="fa fa-minus-circle"></i> <?php echo $text_unwritable; ?></span>
			  <?php } else { ?>
			  <span class="text-success"><i class="fa fa-check-circle"></i> <?php echo $text_writable; ?></span>
			  <?php } ?></td>
		  </tr>
		  <tr>
			<td><?php echo $config_admin; ?></td>
			<td class="text-center"><?php if (!file_exists($config_admin)) { ?>
			  <span class="text-danger"><i class="fa fa-minus-circle"></i> <?php echo $text_missing; ?></span>
			  <?php } elseif (!is_writable($config_admin)) { ?>
			  <span class="text-danger"><i class="fa fa-minus-circle"></i> <?php echo $text_unwritable; ?></span>
			  <?php } else { ?>
			  <span class="text-success"><i class="fa fa-check-circle"></i> <?php echo $text_writable; ?></span>
			  <?php } ?></td>
		  </tr>
		</tbody>
	  </table>
	</fieldset>
	<h4><?php echo $text_install_directory; ?></h4>
	<fieldset>
	  <table class="table">
		<thead>
		  <tr>
			<td><b><?php echo $text_directory; ?></b></td>
			<td width="15%" class="text-center"><b><?php echo $text_status; ?></b></td>
		  </tr>
		</thead>
		<tbody>
		  <tr>
			<td><?php echo $cache . '/'; ?></td>
			<td class="text-center"><?php if (is_writable($cache)) { ?>
			  <span class="text-success"><i class="fa fa-check-circle"></i> <?php echo $text_writable; ?></span>
			  <?php } else { ?>
			  <span class="text-danger"><i class="fa fa-minus-circle"></i> <?php echo $text_unwritable; ?></span>
			  <?php } ?></td>
		  </tr>
		  <tr>
			<td><?php echo $logs . '/'; ?></td>
			<td class="text-center"><?php if (is_writable($logs)) { ?>
			  <span class="text-success"><i class="fa fa-check-circle"></i> <?php echo $text_writable; ?></span>
			  <?php } else { ?>
			  <span class="text-danger"><i class="fa fa-minus-circle"></i> <?php echo $text_unwritable; ?></span>
			  <?php } ?></td>
		  </tr>
		  <tr>
			<td><?php echo $download . '/'; ?></td>
			<td class="text-center"><?php if (is_writable($download)) { ?>
			  <span class="text-success"><i class="fa fa-check-circle"></i> <?php echo $text_writable; ?></span>
			  <?php } else { ?>
			  <span class="text-danger"><i class="fa fa-minus-circle"></i> <?php echo $text_unwritable; ?></span>
			  <?php } ?></td>
		  </tr>
		  <tr>
			<td><?php echo $image . '/'; ?></td>
			<td class="text-center"><?php if (is_writable($image)) { ?>
			  <span class="text-success"><i class="fa fa-check-circle"></i> <?php echo $text_writable; ?></span>
			  <?php } else { ?>
			  <span class="text-danger"><i class="fa fa-minus-circle"></i> <?php echo $text_unwritable; ?></span>
			  <?php } ?></td>
		  </tr>
		  <tr>
			<td><?php echo $image_cache . '/'; ?></td>
			<td class="text-center"><?php if (is_writable($image_cache)) { ?>
			  <span class="text-success"><i class="fa fa-check-circle"></i> <?php echo $text_writable; ?></span>
			  <?php } else { ?>
			  <span class="text-danger"><i class="fa fa-minus-circle"></i> <?php echo $text_unwritable; ?></span>
			  <?php } ?></td>
		  </tr>
		  <tr>
			<td><?php echo $image_data . '/'; ?></td>
			<td class="text-center"><?php if (is_writable($image_data)) { ?>
			  <span class="text-success"><i class="fa fa-check-circle"></i> <?php echo $text_writable; ?></span>
			  <?php } else { ?>
			  <span class="text-danger"><i class="fa fa-minus-circle"></i> <?php echo $text_unwritable; ?></span>
			  <?php } ?></td>
		  </tr>
		</tbody>
	  </table>
	</fieldset>
	<div class="buttons">
	  <hr>
	  <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><span class="fa fa-chevron-left"></span> <?php echo $button_back; ?></a></div>
	  <div class="pull-right">
		<button type="submit" class="btn btn-primary" data-loading-text="Checking...">
			<?php echo $button_continue; ?> <span class="fa fa-chevron-right"></span>
		</button>
	  </div>
	</div>
  </form>
</div>
<?php echo $footer; ?> 