<?php if ($auto_submit) { ?>
  <!DOCTYPE html>
  <html>
  <head></head>
  <body>
<?php } ?>

<form action="<?php echo $url; ?>" id="pilibaba-form" method="post">
  <input type="hidden" name="version" value="<?php echo $version; ?>">
  <input type="hidden" name="merchantNo" value="<?php echo $merchantNo; ?>">
  <input type="hidden" name="currencyType" value="<?php echo $currencyType; ?>">
  <input type="hidden" name="orderNo" value="<?php echo $orderNo; ?>">
  <input type="hidden" name="orderAmount" value="<?php echo $orderAmount; ?>">
  <input type="hidden" name="orderTime" value="<?php echo $orderTime; ?>">
  <input type="hidden" name="pageUrl" value="<?php echo $pageUrl; ?>">
  <input type="hidden" name="serverUrl" value="<?php echo $serverUrl; ?>">
  <input type="hidden" name="redirectUrl" value="<?php echo $redirectUrl; ?>">
  <input type="hidden" name="notifyType" value="<?php echo $notifyType; ?>">
  <input type="hidden" name="shipper" value="<?php echo $shipper; ?>">
  <input type="hidden" name="tax" value="<?php echo $tax; ?>">
  <input type="hidden" name="signType" value="<?php echo $signType; ?>">
  <input type="hidden" name="signMsg" value="<?php echo $signMsg; ?>">
  <input type="hidden" name="goodsList" value="<?php echo $goodsList; ?>">
</form>

<?php if (!$auto_submit) { ?>
  <div class="buttons">
    <div class="pull-right">
      <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
    </div>
  </div>

  <script type="text/javascript"><!--
  $('#button-confirm').bind('click', function() {
	$('#button-confirm').button('loading');

    $('#pilibaba-form').submit();
  });
//--></script>
<?php } ?>

<?php if ($auto_submit) { ?>
  <?php echo $text_redirecting; ?>

  <script>
  document.getElementById('pilibaba-form').submit();
  </script>

  </body>
  </html>
<?php } ?>