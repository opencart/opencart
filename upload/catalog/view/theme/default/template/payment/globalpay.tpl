<form action="<?php echo $action; ?>" method="POST" class="form-horizontal" id="globalpay_form_redirect">
  <input type=hidden name="MERCHANT_ID" value="<?php echo $merchant_id; ?>" />
  <input type=hidden name="ORDER_ID" value="<?php echo $order_id; ?>" />
  <input type=hidden name="CURRENCY" value="<?php echo $currency; ?>" >
  <input type=hidden name="AMOUNT" value="<?php echo $amount; ?>" >
  <input type=hidden name="TIMESTAMP" value="<?php echo $timestamp; ?>" />
  <input type=hidden name="SHA1HASH" value="<?php echo $hash; ?>">
  <input type=hidden name="AUTO_SETTLE_FLAG" value="<?php echo $settle; ?>">
  <input type=hidden name="RETURN_TSS" value="<?php echo $tss; ?>">
  <input type=hidden name="BILLING_CODE" value="<?php echo $billing_code; ?>">
  <input type=hidden name="BILLING_CO" value="<?php echo $payment_country; ?>">
  <input type=hidden name="SHIPPING_CODE" value="<?php echo $shipping_code; ?>">
  <input type=hidden name="SHIPPING_CO" value="<?php echo $shipping_country; ?>">
  <input type=hidden name="MERCHANT_RESPONSE_URL" value="<?php echo $response_url; ?>">
  <input type=hidden name="COMMENT1" value="OpenCart">
  <?php if ($card_select == true) { ?>
  <fieldset id="payment">
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-cc-type"><span data-toggle="tooltip" title="<?php echo $help_select_card; ?>"><?php echo $entry_cc_type; ?></span></label>
      <div class="col-sm-10">
        <select name="ACCOUNT" class="form-control" id="input-cc-type">
          <?php foreach ($cards as $card) { ?>
          <option value="<?php echo $card['account']; ?>"><?php echo $card['type']; ?></option>
          <?php } ?>
        </select></div>
    </div>
  </fieldset>
  <?php } ?>
</form>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" />
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
  $('#globalpay_form_redirect').submit();
});
//--></script>