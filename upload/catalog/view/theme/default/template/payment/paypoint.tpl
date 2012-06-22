<form action="https://www.secpay.com/java-bin/ValCard" method="post">
  <input type="hidden" name="merchant" value="<?php echo $merchant; ?>" />
  <input type="hidden" name="trans_id" value="<?php echo $trans_id; ?>" />
  <input type="hidden" name="amount" value="<?php echo $amount; ?>" />
  <?php if ($digest) { ?>
  <input type="hidden" name="digest" value="<?php echo $digest; ?>" />
  <?php } ?>  
  <input type="hidden" name="bill_name" value="<?php echo $bill_name; ?>" />
  <input type="hidden" name="bill_addr_1" value="<?php echo $bill_addr_1; ?>" />
  <input type="hidden" name="bill_addr_2" value="<?php echo $bill_addr_2; ?>" />
  <input type="hidden" name="bill_city" value="<?php echo $bill_city; ?>" />
  <input type="hidden" name="bill_state" value="<?php echo $bill_state; ?>" />
  <input type="hidden" name="bill_post_code" value="<?php echo $bill_post_code; ?>" />
  <input type="hidden" name="bill_country" value="<?php echo $bill_country; ?>" />
  <input type="hidden" name="bill_tel" value="<?php echo $bill_tel; ?>" />
  <input type="hidden" name="bill_email" value="<?php echo $bill_email; ?>" />
  <input type="hidden" name="ship_name" value="<?php echo $ship_name; ?>" />
  <input type="hidden" name="ship_addr_1" value="<?php echo $ship_addr_1; ?>" />
  <input type="hidden" name="ship_addr_2" value="<?php echo $ship_addr_2; ?>" />
  <input type="hidden" name="ship_city" value="<?php echo $ship_city; ?>" />
  <input type="hidden" name="ship_state" value="<?php echo $ship_state; ?>" />
  <input type="hidden" name="ship_post_code" value="<?php echo $ship_post_code; ?>" />
  <input type="hidden" name="ship_country" value="<?php echo $ship_country; ?>" />
  <input type="hidden" name="currency" value="<?php echo $currency; ?>" />
  <input type="hidden" name="callback" value="<?php echo $callback; ?>" />
  <input type="hidden" name="options" value="<?php echo $options; ?>" />
  <div class="buttons">
    <div class="right">
      <input type="submit" value="<?php echo $button_confirm; ?>" class="button" />
    </div>
  </div>
</form>
