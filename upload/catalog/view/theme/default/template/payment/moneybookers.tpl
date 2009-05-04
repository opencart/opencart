<form action="<?php echo $action; ?>" method="post" id="checkout">
  <input type="hidden" name="recipient_description" value="<?php echo $recipient_desc; ?>" />
  <input type="hidden" name="logo_url" value="<?php echo $storelogourl; ?>" />
  <input type="hidden" name="transaction_id" value="<?php echo $trans_id; ?>" />
  <input type="hidden" name="return_url" value="<?php echo $return_url; ?>" />
  <input type="hidden" name="cancel_url" value="<?php echo $cancel_url; ?>" />
  <input type="hidden" name="status_url" value="<?php echo $mb_email; ?>" />
  <input type="hidden" name="language" value="<?php echo $language; ?>" />
  <input type="hidden" name="pay_to_email" value="<?php echo $mb_email; ?>" />  
  <input type="hidden" name="pay_from_email" value="<?php echo $cust_email; ?>" />
  <input type="hidden" name="amount" value="<?php echo $amount; ?>" />
  <input type="hidden" name="currency" value="<?php echo $currency; ?>" />
  <input type="hidden" name="detail1_description" value="<?php echo $detail1_desc; ?>" />
  <input type="hidden" name="detail1_text" value="<?php echo $detail1_text; ?>" />
  <input type="hidden" name="firstname" value="<?php echo $cust_firstname; ?>" />
  <input type="hidden" name="lastname" value="<?php echo $cust_lastname; ?>" />
  <input type="hidden" name="address" value="<?php echo $cust_address1; ?>" />
  <input type="hidden" name="address2" value="<?php echo $cust_address2; ?>" />
  <input type="hidden" name="postal_code" value="<?php echo $cust_postcode; ?>" />
  <input type="hidden" name="city" value="<?php echo $cust_city; ?>" />
  <input type="hidden" name="state" value="<?php echo $cust_zone; ?>" />
  <input type="hidden" name="country" value="<?php echo $cust_country; ?>" />
  <input type="hidden" name="confirmation_note" value="<?php echo $mb_note; ?>" />
</form>
<div class="buttons">
  <table>
    <tr>
      <td align="left"><a onclick="location='<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
      <td align="right"><a onclick="confirmSubmit();" class="button"><span><?php echo $button_continue; ?></span></a></td>
    </tr>
  </table>
</div>
<script type="text/javascript"><!--
function confirmSubmit() {
	$.ajax({
		type: 'GET',
		url: 'index.php?route=payment/moneybookers/confirm',
		success: function() {
			$('#checkout').submit();
		}
	});
}
//--></script>


