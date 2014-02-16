$(document).ready(function() {
	$('#startInstallationForm').submit(function() {
		$('#startInstallationBtn').button('loading');
		setTimeout(function() { 
			$('#startInstallationBtn').html("Installing..."); 
			bootbox.dialog({
				message: "Please wait while OpenCart is being installed. Don't close this page during this process.<br><br><div class=\"progress progress-striped active\"><div class=\"progress-bar\"  role=\"progressbar\" aria-valuenow=\"100\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: 100%\"></span></div></div>",
				title: "Installing...",
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
});