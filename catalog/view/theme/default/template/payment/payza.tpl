<form action="<?php echo $action; ?>" method="post">
  <input type="hidden" name="ap_merchant" value="<?php echo $ap_merchant; ?>" />
  <input type="hidden" name="ap_amount" value="<?php echo $ap_amount; ?>" />
  <input type="hidden" name="ap_currency" value="<?php echo $ap_currency; ?>" />
  <input type="hidden" name="ap_purchasetype" value="<?php echo $ap_purchasetype; ?>" />
  <input type="hidden" name="ap_itemname" value="<?php echo $ap_itemname; ?>" />
  <input type="hidden" name="ap_itemcode" value="<?php echo $ap_itemcode; ?>" />
  <input type="hidden" name="ap_returnurl" value="<?php echo $ap_returnurl; ?>" />
  <input type="hidden" name="ap_cancelurl" value="<?php echo $ap_cancelurl; ?>" />
  <div class="buttons">
    <div class="right">
      <input type="submit" value="<?php echo $button_confirm; ?>" class="button" />
    </div>
  </div>
</form>
