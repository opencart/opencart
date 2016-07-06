{% if checkout_method == 'iframe') { ?>
  <iframe name="hss_iframe" width="560px" height="540px" style="border:0px solid #DDDDDD; margin-left:210px;" scrolling="no" src="<?php echo HTTPS_SERVER.'index.php?route=extension/payment/pp_pro_iframe/create'; ?>"></iframe>
{% else %}
  {% if !$error_connection %}
  <form action="{{ url }}" method="post" name="ppform" id="ppform">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="{{ code }}">
  </form>
  <script type="text/javascript"><!--
  $('#ppform').submit();
  //--></script>
  {% else %}
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{ error_connection }}</div>
  <?php } ?>
<?php } ?>
