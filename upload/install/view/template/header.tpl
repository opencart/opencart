<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<script type="text/javascript" src="view/javascript/jquery-1.11.0.min.js"></script>
<link href="view/stylesheet/bootstrap.min.css" rel="stylesheet" />
<script src="view/javascript/bootstrap-3.1.1.min.js" type="text/javascript"></script>
<script src="view/javascript/bootbox-4.2.0.min.js" type="text/javascript"></script>
<link href="view/stylesheet/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
<script>
$(document).ready(function() {
	$('#startInstallationForm').submit(function() {
		$('#startInstallationBtn').button('loading');
		setTimeout(function() { 
			$('#startInstallationBtn').html("<?php echo $text_installing; ?>"); 
			bootbox.dialog({
				message: "<?php echo $text_install_progress; ?><br><br><div class=\"progress progress-striped active\"><div class=\"progress-bar\"  role=\"progressbar\" aria-valuenow=\"100\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: 100%\"></span></div></div>",
				title: "<?php echo $text_installing; ?>",
				closeButton: false
			});
		}, 2000);
	});
	
	$('#startUpgradeForm').submit(function() {
		$('#startUpgradeBtn').button('loading');
		setTimeout(function() { 
			$('#startUpgradeBtn').html("<?php echo $text_upgrading; ?>"); 
			bootbox.dialog({
				message: "<?php echo $text_upgrade_progress; ?><br><br><div class=\"progress progress-striped active\"><div class=\"progress-bar\"  role=\"progressbar\" aria-valuenow=\"100\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: 100%\"></span></div></div>",
				title: "<?php echo $text_upgrading; ?>",
				closeButton: false
			});
		}, 2000);
	});
	
	$('#deleteInstallDirBtn').click(function() {
		$(this).button('loading');
		$.ajax({
			url: "index.php?route=step_4/delete",
			type: 'GET',
			success: function(res) {
				$('#deleteInstallDirBtn').html(res);
			}
		});
	});
	
	$('#deleteUpgradeDirBtn').click(function() {
		$(this).button('loading');
		$.ajax({
			url: "index.php?route=upgrade/delete",
			type: 'GET',
			success: function(res) {
				$('#deleteUpgradeDirBtn').html(res);
			}
		});
	});
});
</script>
</head>
<body>
<header>
  <div class="container">
    <div id="logo"><img src="view/image/logo.png" alt="OpenCart" title="OpenCart" /> <noscript><span class="alert alert-danger alert-js pull-right"><span class="fa fa-warning"></span> <?php echo $error_javascript; ?></span></noscript></div>
  </div>
</header>
