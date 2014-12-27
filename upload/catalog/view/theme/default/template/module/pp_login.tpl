<div class="panel panel-default">
  <div class="panel-body" style="text-align: right;"> <span id="pp_login_container"></span> 
    <script src="https://www.paypalobjects.com/js/external/api.js"></script> 
    <script>
paypal.use(['login'], function(login) {
	login.render ({
		'appid': '<?php echo $client_id; ?>',
		'authend': '<?php echo $sandbox; ?>',
		'scopes': '<?php echo $scopes; ?>',
		'containerid': 'pp_login_container',
		'locale': '<?php echo $locale; ?>',
		'theme': '<?php echo $button_colour; ?>',
		'returnurl': '<?php echo $return_url; ?>'
	});
});
    </script> 
  </div>
</div>
