<h2><?php echo $text_payment; ?></h2>
<table class="table table-bordered">
  <tr>
    <td><?php echo $text_capture_status; ?></td>
    <td id="capture-status"><?php echo $capture_status; ?></td>
  </tr>
  <tr>
    <td><?php echo $text_amount_authorised; ?></td>
    <td><?php echo $total; ?>
      <?php if ($capture_status != 'Complete') { ?>
      &nbsp;&nbsp;&nbsp;
      <button type="button" id="button-void" class="btn btn-danger"><?php echo $button_void; ?></button>
      <?php } ?></td>
  </tr>
  <tr>
    <td><?php echo $text_amount_captured; ?></td>
    <td id="paypal-captured"><?php echo $captured; ?></td>
  </tr>
  <tr>
    <td><?php echo $text_amount_refunded; ?></td>
    <td id="paypal-refunded"><?php echo $refunded; ?></td>
  </tr>
  <?php if ($capture_status != 'Complete') { ?>
  <tr class="paypal-capture">
    <td><?php echo $text_capture_amount; ?></td>
    <td><label>
        <input type="checkbox" name="paypal_capture_complete" id="paypal-capture-complete" value="1" class="form-control" />
        <?php echo $text_complete_capture; ?></label>
      <div class="input-group"><span class="input-group-addon"><i class="fa fa-link"></i></span>
        <input type="text" value="<?php echo $remaining; ?>" size="10" id="paypal-capture-amount" class="form-control" />
        <button type="button" id="button-capture" class="btn btn-primary"><?php echo $button_capture; ?></button>
      </div></td>
  </tr>
  <?php } ?>
</table>
<h2><?php echo $text_transactions; ?></h2>
<div id="paypal-transaction"></div>
<script type="text/javascript"><!--
$('#paypal-transaction').load('index.php?route=payment/pp_express/transaction&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

$('#button-capture').on('click', function() {
	$.ajax({
		url: 'index.php?route=payment/pp_express/capture&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'amount=' + $('#paypal-capture-amount').val() + '&complete=' + $('#paypal-capture-complete').prop('checked'),
		beforeSend: function() {
			$('#button-capture').button('loading');
		},
		complete: function() {
			$('#button-capture').button('reset');
		},			
		success: function(json) {
			if (json['error']) {
				alert(json['error']);
			}
			
			if (!json['success']) {
				$('#paypal-captured').text(json['captured']);
				$('#paypal-capture-amount').val(json['remaining']);

				if (json['status']) {
					$('#capture_status').text('<?php echo $text_complete; ?>');
					$('.paypal_capture').hide();
				}
			}				
			
			$('#paypal-transaction').load('index.php?route=payment/pp_express/transaction&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
		}
	});
});

$('#button-void').on('click', function() {
	if (confirm('<?php echo addslashes($text_confirm_void); ?>')) {
		$.ajax({
			url: 'index.php?route=payment/pp_express/void&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
			dataType: 'json',
			beforeSend: function() {
				$('#button-void').button('loading');
			},
			complete: function() {
				$('#button-void').button('reset');
			},			
			success: function(json) {
				if (!json['error']) {					
					$('#capture-status').text('<?php echo $text_complete; ?>');
					
					$('.paypal_capture_live').hide();
				} else {
					alert(json['error']);
				}				
				
				$('#paypal-transaction').load('index.php?route=payment/pp_express/transaction&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
			}
		});
	}
});

$('#paypal-transaction').delegate('button', 'click', function() {
	var element = this;
	
	$.ajax({
		url: $(element).attr('href'),
		dataType: 'json',
		beforeSend: function() {
			$(element).button('loading');
		},
		complete: function() {
			$(element).button('reset');
		},		
		success: function(data) {
			if (json['error']) {
				alert(json['error']);
			}

			if (json['success']) {
				location.reload();
				
				$('#paypal-transaction').load('index.php?route=payment/pp_express/transaction&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
			}
		}
	});
});
//--></script>