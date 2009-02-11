<form method="POST" action="https://checkout.google.com/api/checkout/v2/checkoutForm/Merchant/1234567890" accept-charset="utf-8">
  <?php forech ($products as $product) { ?>
  <input type="hidden" name="item_name_1" value="Peanut Butter"/>
  <input type="hidden" name="item_description_1" value="Chunky peanut butter."/>
  <input type="hidden" name="item_quantity_1" value="1"/>
  <input type="hidden" name="item_price_1" value="3.99"/>
  <input type="hidden" name="item_currency_1" value="USD"/>
  <?php } ?>
  <input type="hidden" name="ship_method_name_1" value="UPS Ground"/>
  <input type="hidden" name="ship_method_price_1" value="10.99"/>
  <input type="hidden" name="ship_method_currency_1" value="USD"/>
  <input type="hidden" name="tax_rate" value="0.0875"/>
  <input type="hidden" name="tax_us_state" value="NY"/>
  <input type="hidden" name="_charset_"/>
  <input type="image" name="Google Checkout" alt="Fast checkout through Google" src="http://checkout.google.com/buttons/checkout.gif?merchant_id=1234567890&w=180&h=46&style=white&variant=text&loc=en_US" height="46" width="180" />
</form>
<form action="<?php echo $action; ?>" method="post" id="checkout">
  <input type="hidden" name="cmd" value="_xclick" />
  <input type="hidden" name="business" value="<?php echo $business; ?>" />
  <input type="hidden" name="item_name" value="<?php echo $item_name; ?>" />
  <input type="hidden" name="currency_code" value="<?php echo $currency_code; ?>" />
  <input type="hidden" name="amount" value="<?php echo $amount; ?>" />
  <input type="hidden" name="first_name" value="<?php echo $first_name; ?>" />
  <input type="hidden" name="last_name" value="<?php echo $last_name; ?>" />
  <input type="hidden" name="address1" value="<?php echo $address1; ?>" />
  <input type="hidden" name="address2" value="<?php echo $address2; ?>" />
  <input type="hidden" name="city" value="<?php echo $city; ?>" />
  <input type="hidden" name="zip" value="<?php echo $zip; ?>" />
  <input type="hidden" name="country" value="<?php echo $country; ?>" />
  <input type="hidden" name="address_override" value="0" />
  <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>" />
  <input type="hidden" name="email" value="<?php echo $email; ?>" />
  <input type="hidden" name="invoice" value="<?php echo $invoice; ?>" />
  <input type="hidden" name="lc" value="<?php echo $lc; ?>" />
  <input type="hidden" name="return" value="<?php echo $return; ?>" />
  <input type="hidden" name="rm" value="2" />
  <input type="hidden" name="no_note" value="1" />
  <input type="hidden" name="cancel_return" value="<?php echo $cancel_return; ?>" />
  <input type="hidden" name="paymentaction" value="authorization" />
  <div class="buttons">
    <table>
      <tr>
        <td align="left"><a onclick="location='<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
        <td align="right"><a onclick="confirmSubmit();" class="button"><span><?php echo $button_continue; ?></span></a></td>
      </tr>
    </table>
  </div>
</form>
<script type="text/javascript"><!--
function confirmSubmit() {
	$.ajax({
		type: 'POST',
		url: 'index.php?route=payment/paypal/confirm',
		dataType: 'text',
		success: function() {
			$('#checkout').submit();
		}
	});
}
//--></script>
