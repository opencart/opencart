<form action="<?php echo $action; ?>" method="get" id="payment">
  <input type="hidden" name="mid" value="<?php echo $mid; ?>" />
  <input type="hidden" name="amt" value="<?php echo $amt; ?>" />
  <input type="hidden" name="amt_editable" value="N" />
  <input type="hidden" name="popup" value="false" />
  <input type="hidden" name="currency" value="<?php echo $currency; ?>" />
  <input type="hidden" name="ref" value="<?php echo $ref; ?>" />
  <input type="hidden" name="pmt_sender_email" value="<?php echo $pmt_sender_email; ?>" />
  <input type="hidden" name="pmt_contact_firstname" value="<?php echo $pmt_contact_firstname; ?>" />
  <input type="hidden" name="pmt_contact_surname" value="<?php echo $pmt_contact_surname; ?>" />
  <input type="hidden" name="pmt_contact_phone" value="<?php echo $pmt_contact_phone; ?>" />
  <input type="hidden" name="pmt_country" value="<?php echo $pmt_country; ?>" />
  <input type="hidden" name="regindi_address1" value="<?php echo $regindi_address1; ?>" />
  <input type="hidden" name="regindi_address2" value="<?php echo $regindi_address2; ?>" />
  <input type="hidden" name="regindi_sub" value="<?php echo $regindi_sub; ?>" />
  <input type="hidden" name="regindi_state" value="<?php echo $regindi_state; ?>" />
  <input type="hidden" name="regindi_pcode" value="<?php echo $regindi_pcode; ?>" />
  <input type="hidden" name="return" value="<?php echo $return; ?>" />
  <div class="buttons">
    <div class="right"><a onclick="$('#payment').submit();" class="button"><span><?php echo $button_confirm; ?></span></a></div>
  </div>
</form>
