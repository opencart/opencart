<form action="<?php echo $action; ?>" method="POST" class="form-horizontal" id="firstdata_form_redirect">
  <fieldset id="payment">
    <input type="hidden" name="txntype" value="<?php echo $txntype; ?>" />
    <input type="hidden" name="timezone" value="GMT" />
    <input type="hidden" name="txndatetime" value="<?php echo $timestamp; ?>" />
    <input type="hidden" name="hash" value="<?php echo $hash; ?>" />
    <input type="hidden" name="storename" value="<?php echo $merchant_id; ?>" />
    <input type="hidden" name="mode" value="payonly" />
    <input type="hidden" name="chargetotal" value="<?php echo $amount; ?>" />
    <input type="hidden" name="currency" value="<?php echo $currency; ?>" />
    <input type="hidden" name="oid" value="<?php echo $order_id; ?>" />
    <input type="hidden" name="mobileMode" value="<?php echo $mobile; ?>" />
    <input type="hidden" name="responseSuccessURL" value="<?php echo $url_success; ?>" />
    <input type="hidden" name="responseFailURL" value="<?php echo $url_fail; ?>" />
    <input type="hidden" name="transactionNotificationURL" value="<?php echo $url_notify; ?>" />
    <input type="hidden" name="sname" value="<?php echo $sname; ?>" />
    <input type="hidden" name="saddr1" value="<?php echo $saddr1; ?>" />
    <input type="hidden" name="saddr2" value="<?php echo $saddr2; ?>" />
    <input type="hidden" name="scity" value="<?php echo $scity; ?>" />
    <input type="hidden" name="sstate" value="<?php echo $sstate; ?>" />
    <input type="hidden" name="scountry" value="<?php echo $scountry; ?>" />
    <input type="hidden" name="szip" value="<?php echo $szip; ?>" />
    <input type="hidden" name="bcompany" value="<?php echo $bcompany; ?>" />
    <input type="hidden" name="bname" value="<?php echo $bname; ?>" />
    <input type="hidden" name="baddr1" value="<?php echo $baddr1; ?>" />
    <input type="hidden" name="baddr2" value="<?php echo $baddr2; ?>" />
    <input type="hidden" name="bcity" value="<?php echo $bcity; ?>" />
    <input type="hidden" name="bstate" value="<?php echo $bstate; ?>" />
    <input type="hidden" name="bcountry" value="<?php echo $bcountry; ?>" />
    <input type="hidden" name="bzip" value="<?php echo $bzip; ?>" />
	<input type="hidden" name="email" value="<?php echo $email; ?>" />
    <input type="hidden" name="invoicenumber" value="<?php echo $version; ?>" />

    <?php if ($card_storage == 1) { ?>
      <?php $i = 1; if (!empty($stored_cards)) { ?>
        <?php foreach ($stored_cards as $card) { ?>
          <p><input type="radio" name="hosteddataid" value="<?php echo $card['token']; ?>" <?php echo ($i == 1 ? ' checked="checked"' : ''); ?> /> <?php echo $card['digits'] . ' - ' . $card['expire_month'] . '/' . $card['expire_year']; ?></p>
          <?php $i++; ?>
        <?php } ?>
        <p><input type="radio" name="hosteddataid" value="<?php echo $new_hosted_id; ?>" <?php echo $i == 1 ? ' checked="checked"' : ''; ?> /> Use a new card</p>
      <?php } else { ?>
        <input type="hidden" name="hosteddataid" value="<?php echo $new_hosted_id; ?>" />
      <?php } ?>
    <?php } ?>
  </fieldset>
</form>

<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" />
  </div>
</div>

<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
  $('#firstdata_form_redirect').submit();
});
//--></script>