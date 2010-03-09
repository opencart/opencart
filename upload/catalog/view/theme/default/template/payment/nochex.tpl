<form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" id="checkout">
  <?php if (!$this->config->get('nochex_test')) { ?>
  <input type = "hidden" name = "merchant_id"         value = "<?php echo $merchant_id; ?>">
  <input type = "hidden" name = "success_url"         value = "<?php echo $success_url; ?>">
  <input type = "hidden" name = "cancel_url"          value = "<?php echo $cancel_url; ?>">
  <input type = "hidden" name = "declined_url"        value = "<?php echo $declined_url; ?>">
  <?php }else{ ?>
  <?php
        // Test card number: 1234567890123456
        // Test CVS number: 123
        // Name on card: Anything allowed for testing purposes
        // Valid to date: Anytime in the future
        ?>
  <input type="hidden" name = "merchant_id"           value = "nochex_test">
  <input type="hidden" name = "test_transaction"      value = "100">
  <input type="hidden" name = "test_success_url"      value = "<?php echo $success_url; ?>">
  <input type="hidden" name = "test_cancel_url"       value = "<?php echo $cancel_url; ?>">
  <input type="hidden" name = "test_declined_url"     value = "<?php echo $declined_url; ?>">
  <?php } ?>
  <input type = "hidden" name = "amount"                  value = "<?php echo $amount; ?>">
  <input type = "hidden" name = "description"             value = "<?php echo $description; ?>">
  <input type = "hidden" name = "order_id"                value = "<?php echo $order_id; ?>">
  <input type = "hidden" name = "billing_fullname"        value = "<?php echo $billing_fullname; ?>">
  <input type = "hidden" name = "billing_address"         value = "<?php echo $billing_address; ?>">
  <input type = "hidden" name = "billing_postcode"        value = "<?php echo $billing_postcode; ?>">
  <input type = "hidden" name = "delivery_fullname"       value = "<?php echo $delivery_fullname; ?>">
  <input type = "hidden" name = "delivery_address"        value = "<?php echo $delivery_address; ?>">
  <input type = "hidden" name = "delivery_postcode"       value = "<?php echo $delivery_postcode; ?>">
  <input type = "hidden" name = "customer_phone_number"   value = "<?php echo $customer_phone_number; ?>">
  <input type =" hidden" name = "email_address"           value = "<?php echo $email_address; ?>">
  <?php // Comment out the line below if you want to allow the customer to change address details (not recommended)?>
  <input type = "hidden" name = "hide_billing_details"    value = "<?php echo $hide_billing_details; ?>">
</form>
<div class="buttons">
  <table>
    <tr>
      <td align="left"><a onclick="location = '<?php echo str_replace('&', '&amp;', $back); ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
      <td align="right"><a onclick="confirmSubmit();" class="button"><span><?php echo $button_confirm; ?></span></a></td>
    </tr>
  </table>
</div>
<script type="text/javascript"><!--
function confirmSubmit() {
	$.ajax({
		type: 'GET',
		url: 'index.php?route=payment/nochex/confirm',
		success: function() {
			$('#checkout').submit();
		}
	});
}
//--></script>
