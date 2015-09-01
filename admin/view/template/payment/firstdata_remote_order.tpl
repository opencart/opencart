<h2><?php echo $text_payment_info; ?></h2>
<div class="alert alert-success" id="firstdata_transaction_msg" style="display: none;"></div>
<table class="table table-striped table-bordered">
  <tr>
    <td><?php echo $text_order_ref; ?></td>
    <td><?php echo $firstdata_order['order_ref']; ?></td>
  </tr>
  <tr>
    <td><?php echo $text_order_total; ?></td>
    <td><?php echo $firstdata_order['total_formatted']; ?></td>
  </tr>
  <tr>
    <td><?php echo $text_total_captured; ?></td>
    <td id="firstdata_total_captured"><?php echo $firstdata_order['total_captured_formatted']; ?></td>
  </tr>
  <tr>
    <td><?php echo $text_capture_status; ?></td>
    <td id="capture_status"><?php if ($firstdata_order['capture_status'] == 1 ) { ?>
      <span class="capture_text"><?php echo $text_yes; ?></span>
      <?php } else { ?>
      <span class="capture_text"><?php echo $text_no; ?></span>&nbsp;&nbsp;
      <?php if ($firstdata_order['void_status'] == 0) { ?>
      <a class="btn btn-primary" id="button-capture"><?php echo $button_capture; ?></a> <span class="btn btn-primary" id="img_loading_capture" style="display:none;"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>
      <?php } ?>
      <?php } ?></td>
  </tr>
  <tr>
    <td><?php echo $text_void_status; ?></td>
    <td id="void_status"><?php if ($firstdata_order['void_status'] == 1) { ?>
      <span class="void_text"><?php echo $text_yes; ?></span>
      <?php } else { ?>
      <span class="void_text"><?php echo $text_no; ?></span>&nbsp;&nbsp;
      <?php if ($firstdata_order['capture_status'] == 0 ) { ?>
      <a class="btn btn-primary" id="button-void"><?php echo $button_void; ?></a> <span class="btn btn-primary" id="img_loading_void" style="display:none;"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>
      <?php } ?>
      <?php } ?></td>
  </tr>
  <tr>
    <td><?php echo $text_refund_status; ?></td>
    <td id="refund_status"><?php if ($firstdata_order['refund_status'] == 1) { ?>
      <span class="refund_text"><?php echo $text_yes; ?></span>
      <?php } else { ?>
      <span class="refund_text"><?php echo $text_no; ?></span>&nbsp;&nbsp; <a class="btn btn-primary" id="button-refund" <?php if ($firstdata_order['capture_status'] == 0 || $firstdata_order['void_status'] == 1) { echo 'style="display:none;"'; } ?>><?php echo $button_refund; ?></a> <span class="btn btn-primary" id="img_loading_refund" style="display:none;"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>
      <?php } ?></td>
  </tr>
  <tr>
    <td><?php echo $text_transactions; ?>:</td>
    <td><table class="table table-striped table-bordered" id="firstdata_transactions">
        <thead>
          <tr>
            <td class="text-left"><strong><?php echo $text_column_date_added; ?></strong></td>
            <td class="text-left"><strong><?php echo $text_column_type; ?></strong></td>
            <td class="text-left"><strong><?php echo $text_column_amount; ?></strong></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach($firstdata_order['transactions'] as $transaction) { ?>
          <tr>
            <td class="text-left"><?php echo $transaction['date_added']; ?></td>
            <td class="text-left"><?php echo $transaction['type']; ?></td>
            <td class="text-left"><?php echo $transaction['amount']; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table></td>
  </tr>
</table>
<script type="text/javascript"><!--
$('#button-void').bind('click', function () {
	if (confirm('<?php echo $text_confirm_void; ?>')) {
		$.ajax({
			type:'post',
			dataType: 'json',
			data: 'order_id=<?php echo $order_id; ?>',
			url: 'index.php?route=payment/firstdata_remote/void&token=<?php echo $token; ?>',
			beforeSend: function() {
				$('#button-void').hide();
				$('#img_loading_void').show();
				$('#firstdata_transaction_msg').hide();
			},
			success: function(data) {
				if (data['error'] == false) {
					html = '';
					html += '<tr>';
					html += '<td class="text-left">'+data.data.date_added+'</td>';
					html += '<td class="text-left">void</td>';
					html += '<td class="text-left">0.00</td>';
					html += '</tr>';
			
					$('. void_text').text('<?php echo $text_yes; ?>');
					
					$('#firstdata_transactions').append(html);
					
					$('#button-capture').hide();
					
					if (data.msg != '') {
						$('#firstdata_transaction_msg').empty().html('<i class="fa fa-check-circle"></i> ' + data['msg']).fadeIn();
					}
				}
			
				if (data['error'] == true) {
					alert(data['msg']);
					
					$('#button-void').show();
				}
			
				$('#img_loading_void').hide();
			}
		});
	}
});

$('#button-capture').bind('click', function () {
	if (confirm('<?php echo $text_confirm_capture; ?>')) {
		$.ajax({
		type:'POST',
		dataType: 'json',
		data: {'order_id': <?php echo $order_id; ?> },
		url: 'index.php?route=payment/firstdata_remote/capture&token=<?php echo $token; ?>',
		beforeSend: function() {
		$('#button-capture').hide();
		$('#img_loading_capture').show();
		$('#firstdata_transaction_msg').hide();
		},
		success: function(data) {
		if (data.error == false) {
		html = '';
		html += '<tr>';
		html += '<td class="text-left">'+data.data.date_added+'</td>';
		html += '<td class="text-left">payment</td>';
		html += '<td class="text-left">'+data.data.amount+'</td>';
		html += '</tr>';
		
		$('#firstdata_transactions').append(html);
		$('#firstdata_total_captured').text(data.data.total_formatted);
		
		if (data.data.capture_status == 1) {
		$('#button-void').hide();
		$('#button-refund').show();
		$(' . capture_text').text('<?php echo $text_yes; ?>');
		} else {
		$('#button-capture').show();
		}
		
		if (data.msg != '') {
		$('#firstdata_transaction_msg').empty().html('<i class="fa fa-check-circle"></i> '+data.msg).fadeIn();
		}
		}
		if (data.error == true) {
		alert(data.msg);
		$('#button-capture').show();
		}
		
		$('#img_loading_capture').hide();
		}
		});
	}
});

$('#button-refund').bind('click', function () {
	if (confirm('<?php echo $text_confirm_refund; ?>')) {
		$.ajax({
		type:'POST',
		dataType: 'json',
		data: {'order_id': <?php echo $order_id; ?> },
		url: 'index.php?route=payment/firstdata_remote/refund&token=<?php echo $token; ?>',
		beforeSend: function() {
		$('#button-refund').hide();
		$('#img_loading_refund').show();
		$('#firstdata_transaction_msg').hide();
		},
		success: function(data) {
		if (data.error == false) {
		html = '';
		html += '<tr>';
		html += '<td class="text-left">'+data.data.date_added+'</td>';
		html += '<td class="text-left">refund</td>';
		html += '<td class="text-left">'+data.data.amount+'</td>';
		html += '</tr>';
		
		$('#firstdata_transactions').append(html);
		$('#firstdata_total_captured').text(data.data.total_captured);
		
		if (data.data.refund_status == 1) {
		$(' . refund_text').text('<?php echo $text_yes; ?>');
		} else {
		$('#button-refund').show();
		}
		
		if (data.msg != '') {
		$('#firstdata_transaction_msg').empty().html('<i class="fa fa-check-circle"></i> '+data.msg).fadeIn();
		}
		}
		if (data.error == true) {
		alert(data.msg);
		$('#button-refund').show();
		}
		
		$('#img_loading_refund').hide();
		}
		});
	}
});
//--></script>