<form action="<?php echo $action; ?>" method="post" id="payment">
  <input type="hidden" name="VPSProtocol" value="2.23" />
  <input type="hidden" name="TxType" value="<?php echo $transaction; ?>" />
  <input type="hidden" name="Vendor" value="<?php echo $vendor; ?>" />
  <input type="hidden" name="Crypt" value="<?php echo $crypt; ?>" />
  <div class="buttons">
    <div class="right"><a onclick="$('#payment').submit();" class="button"><span><?php echo $button_confirm; ?></span></a></div>
  </div>
</form>
