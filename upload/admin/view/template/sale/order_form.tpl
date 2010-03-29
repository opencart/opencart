<?php echo $header; ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/order.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="window.open('<?php echo $invoice; ?>');" class="button"><span><?php echo $button_invoice; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <div style="display: inline-block; width: 100%;">
      <div class="vtabs"><a tab="#tab_order"><?php echo $tab_order; ?></a><a tab="#tab_product"><?php echo $tab_product; ?></a><a tab="#tab_shipping"><?php echo $tab_shipping; ?></a><a tab="#tab_payment"><?php echo $tab_payment; ?></a><a tab="#tab_history"><?php echo $tab_history; ?></a></div>
      <div id="tab_order" class="vtabs_page">
        <table class="form">
          <tr>
            <td><?php echo $entry_order_id; ?></td>
            <td>#<?php echo $order_id; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_invoice_id; ?></td>
            <td id="invoice"><?php if ($invoice_id) { ?>
              <?php echo $invoice_id; ?>
              <?php } else { ?>
              <a id="generate_button" class="button"><span><?php echo $button_generate; ?></span></a>
              <?php } ?></td>
          </tr>
          <?php if ($customer) { ?>
          <tr>
            <td><?php echo $entry_customer; ?></td>
            <td><a href="<?php echo $customer; ?>"><?php echo $firstname; ?> <?php echo $lastname; ?></a></td>
          </tr>
          <?php } else { ?>
          <tr>
            <td><?php echo $entry_customer; ?></td>
            <td><?php echo $firstname; ?> <?php echo $lastname; ?></td>
          </tr>
          <?php } ?>
          <?php if ($customer_group) { ?>
          <tr>
            <td><?php echo $entry_customer_group; ?></td>
            <td><?php echo $customer_group; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $entry_email; ?></td>
            <td><?php echo $email; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_telephone; ?></td>
            <td><?php echo $telephone; ?></td>
          </tr>
          <?php if ($fax) { ?>
          <tr>
            <td><?php echo $entry_fax; ?></td>
            <td><?php echo $fax; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $entry_store_name; ?></td>
            <td><?php echo $store_name; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_store_url; ?></td>
            <td><a onclick="window.open('<?php echo $store_url; ?>');"><u><?php echo $store_url; ?></u></a></td>
          </tr>
          <tr>
            <td><?php echo $entry_date_added; ?></td>
            <td><?php echo $date_added; ?></td>
          </tr>
          <?php if ($shipping_method) { ?>
          <tr>
            <td><?php echo $entry_shipping_method; ?></td>
            <td><?php echo $shipping_method; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $entry_payment_method; ?></td>
            <td><?php echo $payment_method; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_total; ?></td>
            <td><?php echo $total; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_order_status; ?></td>
            <td id="order_status"><?php echo $order_status; ?></td>
          </tr>
          <?php if ($comment) { ?>
          <tr>
            <td><?php echo $entry_comment; ?></td>
            <td><?php echo $comment; ?></td>
          </tr>
          <?php } ?>
        </table>
      </div>
      <div id="tab_product" class="vtabs_page">
        <table id="product" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $column_product; ?></td>
              <td class="left"><?php echo $column_model; ?></td>
              <td class="right"><?php echo $column_quantity; ?></td>
              <td class="right"><?php echo $column_price; ?></td>
              <td class="right" width="1"><?php echo $column_total; ?></td>
            </tr>
          </thead>
          <?php foreach ($products as $product) { ?>
          <tbody>
            <tr>
              <td class="left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                <?php foreach ($product['option'] as $option) { ?>
                <br />
                &nbsp;<small> - <?php echo $option['name']; ?> <?php echo $option['value']; ?></small>
                <?php } ?></td>
              <td class="left"><?php echo $product['model']; ?></td>
              <td class="right"><?php echo $product['quantity']; ?></td>
              <td class="right"><?php echo $product['price']; ?></td>
              <td class="right"><?php echo $product['total']; ?></td>
            </tr>
          </tbody>
          <?php } ?>
          <?php foreach ($totals as $totals) { ?>
          <tbody>
            <tr>
              <td colspan="4" class="right"><?php echo $totals['title']; ?></td>
              <td class="right"><?php echo $totals['text']; ?></td>
            </tr>
          </tbody>
          <?php } ?>
        </table>
        <?php if ($downloads) { ?>
        <table class="list">
          <thead>
            <tr>
              <td class="left"><b><?php echo $column_download; ?></b></td>
              <td class="left"><b><?php echo $column_filename; ?></b></td>
              <td class="right"><b><?php echo $column_remaining; ?></b></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($downloads as $download) { ?>
            <tr>
              <td class="left"><?php echo $download['name']; ?></td>
              <td class="left"><?php echo $download['filename']; ?></td>
              <td class="right"><?php echo $download['remaining']; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <?php } ?>
      </div>
      <div id="tab_shipping" class="vtabs_page">
        <table class="form">
          <tr>
            <td><?php echo $entry_firstname; ?></td>
            <td><?php echo $shipping_firstname; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_lastname; ?></td>
            <td><?php echo $shipping_lastname; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_company; ?></td>
            <td><?php echo $shipping_company; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_address_1; ?></td>
            <td><?php echo $shipping_address_1; ?></td>
          </tr>
          <?php if ($shipping_address_2) { ?>
          <tr>
            <td><?php echo $entry_address_2; ?></td>
            <td><?php echo $shipping_address_2; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $entry_city; ?></td>
            <td><?php echo $shipping_city; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_postcode; ?></td>
            <td><?php echo $shipping_postcode; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_zone; ?></td>
            <td><?php echo $shipping_zone; ?></td>
          </tr>
          <?php if ($shipping_zone_code) { ?>
          <tr>
            <td><?php echo $entry_zone_code; ?></td>
            <td><?php echo $shipping_zone_code; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $entry_country; ?></td>
            <td><?php echo $shipping_country; ?></td>
          </tr>
        </table>
      </div>
      <div id="tab_payment" class="vtabs_page">
        <table class="form">
          <tr>
            <td><?php echo $entry_firstname; ?></td>
            <td><?php echo $payment_firstname; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_lastname; ?></td>
            <td><?php echo $payment_lastname; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_company; ?></td>
            <td><?php echo $payment_company; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_address_1; ?></td>
            <td><?php echo $payment_address_1; ?></td>
          </tr>
          <?php if ($payment_address_2) { ?>
          <tr>
            <td><?php echo $entry_address_2; ?></td>
            <td><?php echo $payment_address_2; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $entry_city; ?></td>
            <td><?php echo $payment_city; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_postcode; ?></td>
            <td><?php echo $payment_postcode; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_zone; ?></td>
            <td><?php echo $payment_zone; ?></td>
          </tr>
          <?php if ($payment_zone_code) { ?>
          <tr>
            <td><?php echo $entry_zone_code; ?></td>
            <td><?php echo $payment_zone_code; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $entry_country; ?></td>
            <td><?php echo $payment_country; ?></td>
          </tr>
        </table>
      </div>
      <div id="tab_history" class="vtabs_page">
        <?php foreach ($histories as $history) { ?>
        <table class="list">
          <thead>
            <tr>
              <td class="left" width="33.3%"><b><?php echo $column_date_added; ?></b></td>
              <td class="left" width="33.3%"><b><?php echo $column_status; ?></b></td>
              <td class="left" width="33.3%"><b><?php echo $column_notify; ?></b></td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="left"><?php echo $history['date_added']; ?></td>
              <td class="left"><?php echo $history['status']; ?></td>
              <td class="left"><?php echo $history['notify']; ?></td>
            </tr>
          </tbody>
          <?php if ($history['comment']) { ?>
          <thead>
            <tr>
              <td class="left" colspan="3"><b><?php echo $column_comment; ?></b></td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="left" colspan="3"><?php echo $history['comment']; ?></td>
            </tr>
          </tbody>
          <?php } ?>
        </table>
        <?php } ?>
        <table class="form">
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="order_status_id">
                <?php foreach ($order_statuses as $order_statuses) { ?>
                <?php if ($order_statuses['order_status_id'] == $order_status_id) { ?>
                <option value="<?php echo $order_statuses['order_status_id']; ?>" selected="selected"><?php echo $order_statuses['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_statuses['order_status_id']; ?>"><?php echo $order_statuses['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_notify; ?></td>
            <td><input type="checkbox" name="notify" value="1" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_comment; ?></td>
            <td><textarea name="comment" cols="40" rows="8" style="width: 99%"></textarea>
              <div style="margin-top: 10px; text-align: right;"><a onclick="history();" id="history_button" class="button"><span><?php echo $button_add_history; ?></span></a></div></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#generate_button').click(function() {
	$.ajax({
		url: 'index.php?route=sale/order/generate&order_id=<?php echo $order_id; ?>',
		dataType: 'json',
		beforeSend: function() {
			$('#generate_button').attr('disabled', 'disabled');
		},		
		complete: function() {
			$('#generate_button').attr('disabled', '');
		},		
		success: function(data) {
			if (data.invoice_id) {
				$('#generate_button').fadeOut('slow', function() {
					$('#invoice').html(data.invoice_id);
				});
			}
		}
	});
});


function history() {
	$.ajax({
		type: 'POST',
		url: 'index.php?route=sale/order/history&order_id=<?php echo $order_id; ?>',
		dataType: 'json',
		data: 'order_status_id=' + encodeURIComponent($('select[name=\'order_status_id\']').val()) + '&notify=' + encodeURIComponent($('input[name=\'notify\']').attr('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'comment\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#history_button').attr('disabled', 'disabled');
			$('#tab_history .form').before('<div class="attention"><img src="view/image/loading_1.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#history_button').attr('disabled', '');
			$('.attention').remove();
		},
		success: function(data) {
			if (data.error) {
				$('#tab_history .form').before('<div class="warning">' + data.error + '</div>');
			}
			
			if (data.success) {
				html  = '<div class="history" style="display: none;">';
				html += '  <table class="list">';
				html += '    <thead>';
				html += '      <tr>';
				html += '        <td class="left" width="33.3%"><b><?php echo $column_date_added; ?></b></td>';
				html += '        <td class="left" width="33.3%"><b><?php echo $column_status; ?></b></td>';
				html += '        <td class="left" width="33.3%"><b><?php echo $column_notify; ?></b></td>';
				html += '      </tr>';
				html += '    </thead>';
				html += '    <tbody>';
				html += '      <tr>';
				html += '        <td class="left">' + data.date_added + '</td>';
				html += '        <td class="left">' + data.order_status + '</td>';
				html += '        <td class="left">' + data.notify + '</td>';
				html += '      </tr>';
				html += '    </tbody>';
				
				if (data.comment) { 
					html += '    <thead>';
					html += '      <tr>';
					html += '        <td class="left" colspan="3"><b><?php echo $column_comment; ?></b></td>';
					html += '      </tr>';
					html += '    </thead>';
					html += '    <tbody>';
					html += '      <tr>';
					html += '        <td class="left" colspan="3">' + data.comment + '</td>';
					html += '      </tr>';
					html += '    </tbody>';	
				}
				
				html += '  </table>';	
				html += '</div>';	
				
				$('#order_status').html(data.status);
				
				$('#tab_history .form').before(html);
				
				$('#tab_history .history').slideDown();
				
				$('#tab_history .form').before('<div class="success">' + data.success + '</div>');

				$('textarea[name=\'comment\']').val('');
			}
		}
	});
}
//--></script>
<script type="text/javascript"><!--
$.tabs('.vtabs a'); 
//--></script>
<?php echo $footer; ?>