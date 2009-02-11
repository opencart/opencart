<form action="<?php echo $action; ?>" method="post" id="checkout">
  <input type="hidden" name="VPSProtocol" value="2.22" />
  <input type="hidden" name="TxType" value="PAYMENT" />
  <input type="hidden" name="Vendor" value="<?php echo $vendor; ?>" />
  <input type="hidden" name="crypt" value="<?php echo $crypt; ?>" />
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
