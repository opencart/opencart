<form action="<?php echo $action; ?>" method="post">
  <?php if (!$test) { ?>
  <input type="hidden" name="merchant_id" value="<?php echo $merchant_id; ?>" />
  <input type="hidden" name="success_url" value="<?php echo $success_url; ?>" />
  <input type="hidden" name="cancel_url" value="<?php echo $cancel_url; ?>" />
  <input type="hidden" name="declined_url" value="<?php echo $declined_url; ?>" />
  <?php } else { ?>
  <input type="hidden" name="merchant_id" value="nochex_test" />
  <input type="hidden" name="test_transaction" value="100" />
  <input type="hidden" name="test_success_url" value="<?php echo $success_url; ?>" />
  <input type="hidden" name="test_cancel_url" value="<?php echo $cancel_url; ?>" />
  <input type="hidden" name="test_declined_url" value="<?php echo $declined_url; ?>" />
  <?php } ?>
  <input type="hidden" name="callback_url" value="<?php echo $callback_url; ?>" />
  <input type="hidden" name="amount" value="<?php echo $amount; ?>" />
  <input type="hidden" name="description" value="<?php echo $description; ?>" />
  <input type="hidden" name="order_id" value="<?php echo $order_id; ?>" />
  <input type="hidden" name="billing_fullname" value="<?php echo $billing_fullname; ?>" />
  <input type="hidden" name="billing_address" value="<?php echo $billing_address; ?>" />
  <input type="hidden" name="billing_postcode" value="<?php echo $billing_postcode; ?>" />
  <input type="hidden" name="delivery_fullname" value="<?php echo $delivery_fullname; ?>" />
  <input type="hidden" name="delivery_address" value="<?php echo $delivery_address; ?>" />
  <input type="hidden" name="delivery_postcode" value="<?php echo $delivery_postcode; ?>" />
  <input type="hidden" name="customer_phone_number" value="<?php echo $customer_phone_number; ?>" />
  <input type="hidden" name="email_address" value="<?php echo $email_address; ?>" />
  <input type="hidden" name="hide_billing_details" value="true" />
  <div class="buttons">
    <div class="pull-right">
      <input type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>