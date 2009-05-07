<b style="margin-bottom: 3px; display: block;"><?php echo $text_credit_card; ?></b>
<div id="paypal" style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
  <table width="100%">
    <tr>
      <td><?php echo $entry_credit_card_type; ?></td>
      <td><select name="credit_card_type">
          <?php foreach ($cards as $card) { ?>
          <option value="<?php echo $card['value']; ?>"><?php echo $card['text']; ?></option>
          <?php } ?>
        </select></td>
    </tr>
    <tr>
      <td><?php echo $entry_credit_card_number; ?></td>
      <td><input type="text" name="credit_card_number" value="" /></td>
    </tr>
    <tr>
      <td><?php echo $entry_start_date; ?></td>
      <td><select name="start_date_month">
          <?php foreach ($months as $month) { ?>
          <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
          <?php } ?>
        </select>
        /
        <select name="start_date_year">
          <?php foreach ($year_valid as $year) { ?>
          <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
          <?php } ?>
        </select>
        <?php echo $text_start_date; ?></td>
    </tr>
    <tr>
      <td><?php echo $entry_expire_date; ?></td>
      <td><select name="expire_date_month">
          <?php foreach ($months as $month) { ?>
          <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
          <?php } ?>
        </select>
        /
        <select name="expire_date_year">
          <?php foreach ($year_expire as $year) { ?>
          <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
          <?php } ?>
        </select></td>
    </tr>
    <tr>
      <td><?php echo $entry_cvv2_number; ?></td>
      <td><input type="text" name="cvv2_number" value="" size="3" /></td>
    </tr>
    <tr>
      <td><?php echo $entry_issue_number; ?></td>
      <td><input type="text" name="issue_number" value="" size="1" />
        <?php echo $text_issue_number; ?></td>
    </tr>
  </table>
</div>
<div class="buttons">
  <table>
    <tr>
      <td align="left"><a onclick="location='<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
      <td align="right"><a onclick="confirmSubmit();" class="button"><span><?php echo $button_confirm; ?></span></a></td>
    </tr>
  </table>
</div>
<script type="text/javascript"><!--
function confirmSubmit() {
	$.ajax({
		type: 'POST',
		url: 'index.php?route=payment/paypal_direct/send',
		data: $('#paypal :input'),
		dataType: 'json',
		success: function(data) {
			if (data.error) {
				alert(data.error);
			}
			
			if (data.success) {
				location = 'index.php?route=checkout/success';
			}
		}
	});
}
//--></script>
