<div class="panel panel-default">
  <div class="panel-body" style="text-align: right;">
    <span id="pp_login_container"></span>
    <script src="https://www.paypalobjects.com/js/external/api.js"></script>
    <script>
    paypal.use( ["login"], function(login) {
      login.render ({
        "appid"       : "<?php echo $pp_login_client_id; ?>",
        "authend"     : "<?php echo $pp_login_sandbox; ?>",
        "scopes"      : "<?php echo $pp_login_scopes; ?>",
        "containerid" : "pp_login_container",
        "locale"      : "<?php echo $pp_login_locale; ?>",
        "theme"       : "<?php echo $pp_login_button_colour; ?>",
        "returnurl"   : "<?php echo $pp_login_return_url; ?>"
      });
    });
    </script>
  </div>
</div>
