$(document).ready(function() {
	mastercard_pgs_error = function(error) { // We need it as a global variable!
		$('#button-confirm').button('reset');
		alert(error.cause + ": " + error.explanation);
	}

	var toggle_ajax_setup = function(status) {
		$.ajaxSetup({
			cache: status
		});
	}

	var hide_body_on_success = function() {
		if (location.hash.indexOf("#__hc-action-complete") === 0) {
			$('body').html('<div class="text-center text-muted" style="margin-top: 20px;"><i class="fa fa-circle-o-notch fa-spin"></i>&nbsp;<?php echo $text_loading; ?></div>');
		}
	}

	var init_mastercard_pgs = function(init_ajax_setup) {
		hide_body_on_success();

		toggle_ajax_setup(true);
		
		var script = document.createElement('script');

		script.type="text/javascript";

		script.setAttribute('data-complete', '<?php echo $complete; ?>');
		script.setAttribute('data-cancel', '<?php echo $cancel; ?>');
		script.setAttribute('data-error', 'mastercard_pgs_error');
		
		$("head").append(script);

		script.onload = function() {
			// Do something when script loads
		}

		script.src="<?php echo $checkout_script; ?>";

		toggle_ajax_setup(init_ajax_setup);
	}

	init_mastercard_pgs($.ajaxSetup().cache);
});