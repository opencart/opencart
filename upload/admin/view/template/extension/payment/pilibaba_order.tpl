<h2><?php echo $text_payment_info; ?></h2>
<table class="table table-striped table-bordered">
  <tr>
    <td><?php echo $text_order_id; ?></td>
    <td><?php echo $pilibaba_order['order_id']; ?></td>
  </tr>
  <tr>
    <td><?php echo $text_amount; ?></td>
    <td><?php echo $pilibaba_order['amount']; ?></td>
  </tr>
  <tr>
    <td><?php echo $text_fee; ?></td>
    <td><?php echo $pilibaba_order['fee']; ?></td>
  </tr>
  <tr>
    <td><?php echo $text_date_added; ?></td>
    <td><?php echo $pilibaba_order['date_added']; ?></td>
  </tr>
  <tr>
    <td><?php echo $text_tracking; ?></td>
    <td>
      <input type="text" style="width:80px" id="tracking" maxlength="50" value="<?php echo $pilibaba_order['tracking']; ?>"/>
      <a class="button btn btn-primary btn-sm" id="button-tracking"><?php echo $button_tracking; ?></a> <span class="btn btn-primary btn-sm" id="img_loading_tracking" style="display:none"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>
    </td>
  </tr>
  <tr>
    <td><?php echo $text_barcode; ?></td>
    <td>
	  <a href="<?php echo $barcode; ?>" target="_blank" class="button btn btn-primary btn-sm"><?php echo $button_barcode; ?></a>
	  <span style="padding-left:5px"><?php echo $text_barcode_info; ?></span>
    </td>
  </tr>
</table>
<script type="text/javascript"><!--
	$('#button-tracking').click(function() {
		if (confirm('<?php echo $text_confirm; ?>')) {
			$.ajax({
				type: 'POST',
				dataType: 'json',
				data: {'order_id': <?php echo $order_id; ?>, 'tracking': $('#tracking').val()},
				url: 'index.php?route=extension/payment/pilibaba/tracking&token=<?php echo $token; ?>',
				beforeSend: function() {
					$('#button-tracking').hide();
					$('#img_loading_tracking').show();
					$('.pilibaba_message').remove();
				},
				success: function(json) {
					if (json['success']) {
						$('h2').after('<div class="alert alert-success pilibaba_message" style="display:none"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>').fadeIn();
					}

					if (json['error']) {
						$('h2').after('<div class="alert alert-danger pilibaba_message" style="display:none"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>').fadeIn();
					}

					$('#button-tracking').show();
					$('#img_loading_tracking').hide();
					$('.pilibaba_message').fadeIn();
				}
			});
		}
	});
//--></script>