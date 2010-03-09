<form action="<?php echo $action; ?>" method="post" id="checkout">
  <input type="hidden" name="receiver" value="<?php echo $email; ?>" />
  <input type="hidden" name="receiverid" value="<?php echo $receiverid; ?>" />
  <input type="hidden" name="account_id" value="<?php echo $account_id; ?>" />
  <input type="hidden" name="prod_name" value="Order ID: <?php echo $order_id; ?>" />
  <input type="hidden" name="prod_code" value="<?php echo $order_id; ?>" />
  <input type="hidden" name="prod_price" value="<?php echo $amount; ?>" />
  <input type="hidden" name="ship_price" value="<?php echo $ship_price; ?>" />
  <input type="hidden" name="tax" value="<?php echo $tax; ?>" />
  <input type="hidden" name="notifyurl" value="<?php echo $callback; ?>" />
  <?php if ($method == 'std') { ?>
  <input type="hidden" name="successurl" value="<?php echo $success; ?>" />
  <input type="hidden" name="cancelurl" value="<?php echo $cancel; ?>" />
  <?php } elseif ($method == 'ipn') { ?>
  <input type="hidden" name="successurl" value="<?php echo $success; ?>" />
  <input type="hidden" name="cancelurl" value="<?php echo $cancel; ?>" />
  <?php } ?>
</form>
<div class="buttons">
  <table>
    <tr>
      <td align="left"><a onclick="location = '<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
      <td align="right"><a onclick="confirmSubmit();" class="button"><span><?php echo $button_confirm; ?></span></a></td>
    </tr>
  </table>
</div>
<script type="text/javascript"><!--
function confirmSubmit() {
	$.ajax({
		type: 'GET',
		url: 'index.php?route=payment/asiapay/confirm',
		success: function() {
			$('#checkout').submit();
		}
	});
}
//--></script>
