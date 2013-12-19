<html>
  <head>
    <link rel="stylesheet" type="text/css" href="<?php echo $stylesheet ?>" />
  </head>
  <body>
    <?php if (!$error_connection) { ?>
      <form action="<?php echo $url; ?>" method="post" name="ppform" id="ppform">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="<?php echo $code; ?>">
        <p style="text-align:center;">
          <span style="font-family:arial; font-size:12px; font-weight:bold;"><?php echo $text_secure_connection ?></span>
        </p>
        <img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
      </form>
      <script type="text/javascript"><!--
        document.forms["ppform"].submit();
      //--></script>
    <?php } else { ?>
      <div class="warning"><?php echo $error_connection ?></div>
    <?php } ?>
  </body>
</html>