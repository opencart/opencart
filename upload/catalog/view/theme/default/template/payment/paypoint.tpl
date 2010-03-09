<form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" id="checkout">
  <input type="hidden" name="merchant" value="<?php echo $merchant; ?>" />
  <input type="hidden" name="trans_id" value="<?php echo $trans_id; ?>" />
  <input type="hidden" name="amount" value="<?php echo $amount; ?>" />
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
  <input type="hidden" name="order_id" value="<?php echo $order_id; ?>" />
  <input type="hidden" name="options" value="<?php echo $options; ?>" />
</form>
<div class="buttons">
  <table>
    <tr>
      <td align="left"><a onclick="location = '<?php echo str_replace('&', '&amp;', $back); ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
      <td align="right"><a onclick="$('#checkout').submit();" class="button"><span><?php echo $button_confirm; ?></span></a></td>
    </tr>
  </table>
</div>
