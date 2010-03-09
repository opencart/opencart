<form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" id="checkout">
  <input type="hidden" name="ap_merchant" value="<?php echo $ap_merchant; ?>" />
  <input type="hidden" name="ap_amount" value="<?php echo $ap_amount; ?>" />
  <input type="hidden" name="ap_currency" value="<?php echo $ap_currency; ?>" />
  <input type="hidden" name="ap_purchasetype" value="<?php echo $ap_purchasetype; ?>" />
  <input type="hidden" name="ap_itemname" value="<?php echo $ap_itemname; ?>" />
  <input type="hidden" name="ap_itemcode" value="<?php echo $ap_itemcode; ?>" />
  <input type="hidden" name="ap_returnurl" value="<?php echo $ap_returnurl; ?>" />
  <input type="hidden" name="ap_cancelurl" value="<?php echo $ap_cancelurl; ?>" />
</form>
<div class="buttons">
  <table>
    <tr>
      <td align="left"><a onclick="location = '<?php echo str_replace('&', '&amp;', $back); ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
      <td align="right"><a onclick="$('#checkout').submit();" class="button"><span><?php echo $button_confirm; ?></span></a></td>
    </tr>
  </table>
</div>
