<?php if ($checkout_method == 'iframe') { ?>
  <iframe name="hss_iframe" width="560px" height="540px" style="border:0px solid #DDDDDD; margin-left:210px;" scrolling="no" src="<?php echo HTTPS_SERVER.'index.php?route=payment/pp_pro_iframe/create'; ?>"></iframe>
<?php } else { ?>
  <?php if (!$error_connection) { ?>
  <form action="<?php echo $url; ?>" method="post" name="ppform" id="ppform">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="<?php echo $code; ?>">
  </form>
  <script type="text/javascript"><!--
  $('#ppform').submit();
  //--></script>
  <?php } else { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_connection ?></div>
  <?php } ?>
<?php } ?>
