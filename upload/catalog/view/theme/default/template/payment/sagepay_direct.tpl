<b style="margin-bottom: 3px; display: block;"><?php echo $text_credit_card; ?></b>
<div id="sagepay" style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
  <table width="100%">
    <tr>
      <td><?php echo $entry_cc_owner; ?></td>
      <td><input type="text" name="cc_owner" value="" /></td>
    </tr>
    <tr>
      <td><?php echo $entry_cc_type; ?></td>
      <td><select name="cc_type">
          <?php foreach ($cards as $card) { ?>
          <option value="<?php echo $card['value']; ?>"><?php echo $card['text']; ?></option>
          <?php } ?>
        </select></td>
    </tr>
    <tr>
      <td><?php echo $entry_cc_number; ?></td>
      <td><input type="text" name="cc_number" value="" /></td>
    </tr>
    <tr>
      <td><?php echo $entry_cc_start_date; ?></td>
      <td><select name="cc_start_date_month">
          <?php foreach ($months as $month) { ?>
          <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
          <?php } ?>
        </select>
        /
        <select name="cc_start_date_year">
          <?php foreach ($year_valid as $year) { ?>
          <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
          <?php } ?>
        </select>
        <?php echo $text_start_date; ?></td>
    </tr>
    <tr>
      <td><?php echo $entry_cc_expire_date; ?></td>
      <td><select name="cc_expire_date_month">
          <?php foreach ($months as $month) { ?>
          <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
          <?php } ?>
        </select>
        /
        <select name="cc_expire_date_year">
          <?php foreach ($year_expire as $year) { ?>
          <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
          <?php } ?>
        </select></td>
    </tr>
    <tr>
      <td><?php echo $entry_cc_cvv2; ?></td>
      <td><input type="text" name="cc_cvv2" value="" size="3" /></td>
    </tr> 
    <tr>
      <td><?php echo $entry_cc_issue; ?></td>
      <td><input type="text" name="cc_issue" value="" size="1" />
        <?php echo $text_issue; ?></td>
    </tr>
  </table>
</div>
<div class="buttons">
  <table>
    <tr>
      <td align="left"><a onclick="location = '<?php echo str_replace('&', '&amp;', $back); ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
      <td align="right"><a onclick="confirmSubmit();" id="sagepay_button" class="button"><span><?php echo $button_confirm; ?></span></a></td>
    </tr>
  </table>
</div>
<script type="text/javascript"><!--
function confirmSubmit() {
	$.ajax({
		type: 'POST',
		url: 'index.php?route=payment/sagepay_direct/send',
		data: $('#sagepay :input'),
		dataType: 'json',		
		beforeSend: function() {
			$('#sagepay_button').attr('disabled', 'disabled');
			
			$('#sagepay').before('<div class="wait"><img src="catalog/view/theme/default/image/loading_1.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		success: function(data) {
			if (data.ACSURL) {
				$('#3dauth').remove();
				
				html  = '<form action="' + data.ACSURL + '" method="post" id="3dauth">';
				html += '<input type="hidden" name="MD" value="' + data.MD + '" />';
				html += '<input type="hidden" name="PaReq" value="' + data.PaReq + '" />';
				html += '<input type="hidden" name="TermUrl" value="' + data.TermUrl + '" />';
				html += '</form>';
				
				$('#sagepay').after(html);
				
				$('#3dauth').submit();
			}
			
			if (data.error) {
				alert(data.error);
				
				$('#sagepay_button').attr('disabled', '');
			}
			
			$('.wait').remove();
			
			if (data.success) {
				location = data.success;
			}
		}
	});
}
//--></script>
