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
              <?php echo $invoice_id; ?> <span class="help"><?php echo $invoice_date; ?></span>
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
            <td><?php echo $entry_ip; ?></td>
            <td><?php echo $ip; ?></td>
          </tr>
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
            <td class="grand_total"><?php echo $total; ?></td>
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
              <td></td>
              <td class="left"><?php echo $column_product; ?></td>
              <td class="left"><?php echo $column_model; ?></td>
              <td class="right"><?php echo $column_quantity; ?></td>
              <td class="right"><?php echo $column_price; ?></td>
              <td class="right"><?php echo $column_total; ?></td>
            </tr>
          </thead>
          <?php foreach ($products as $product) { ?>
          <tbody id="product_<?php echo $product['order_product_id']; ?>">
            <tr>
              <td class="left" style="width:3px;"><span class="remove" onclick="removeProduct('<?php echo $product['order_product_id']; ?>');">&nbsp;</span></td>
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
          <tbody id="totals">
            <?php foreach ($totals as $key => $tot) { ?>
            <tr<?php if($key != (count($totals)-1) && $key != 0) echo ' class="taxes"'; // TU ?>>
              <td></td>
              <td colspan="4" class="right"><?php echo $tot['title']; ?></td>
			  <?php if ($key == (count($totals)-1)) { ?>
              <td class="right grand_total"><?php echo $tot['text']; ?></td>
              <?php } elseif ($key == 0) { ?>
			  <td class="right subtotal"><?php echo $tot['text']; ?></td>
              <?php } else { ?>
              <td class="right"><?php echo $tot['text']; ?></td>
              <?php } ?>
            </tr>
            <?php } ?>
          </tbody>
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
        <table class="list">
          <thead>
            <tr>
              <td class="left" colspan="3"><?php echo $column_add_product; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="left"><?php echo $entry_category; ?></td>
			  <td class="left" colspan="2">
			    <select id="category" style="width: 450px;" onchange="getProducts();">
			      <?php foreach ($categories as $category) { ?>
			      <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
			      <?php } ?>
			    </select>
			  </td>
			</tr>
            <tr>
		      <td class="left"><?php echo $entry_product; ?></td>
			  <td class="left" colspan="2"><select id="products" style="width: 450px;" onchange="getOptions();"></select></td>
			</tr>
			<tr>
			  <td class="left"><?php echo $entry_option; ?></td>
			  <td class="left"><select multiple="multiple" id="option" size="5" style="width: 450px;"></select></td>
			  <td style="vertical-align: middle;"><span class="add" onclick="addProduct();">&nbsp;</span></td>
			</tr>
			<!--<tr> // TU
		      <td class="left"><?php echo $entry_tax; ?></td>
			  <td class="left" colspan="2"><input id="add_tax" name="add_tax" type="text" value="0" size="5"/>%</td>
			</tr>-->
			<tr>
		      <td class="left"><?php echo $entry_quantity; ?></td>
			  <td class="left" colspan="2"><input id="add_quantity" name="add_quantity" type="text" value="1" size="5"/></td>
			</tr>
  		  </tbody>
		</table>
      </div>
      <div id="tab_shipping" class="vtabs_page">
	  	<form id="shipping_address">
        <table class="form">
          <tr>
            <td><?php echo $entry_firstname; ?></td>
            <td><input type="text" name="shipping_firstname" value="<?php echo $shipping_firstname; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_lastname; ?></td>
            <td><input type="text" name="shipping_lastname" value="<?php echo $shipping_lastname; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_company; ?></td>
            <td><input type="text" name="shipping_company" value="<?php echo $shipping_company; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_address_1; ?></td>
            <td><input type="text" name="shipping_address_1" value="<?php echo $shipping_address_1; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_address_2; ?></td>
            <td><input type="text" name="shipping_address_2" value="<?php echo $shipping_address_2; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_city; ?></td>
            <td><input type="text" name="shipping_city" value="<?php echo $shipping_city; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_postcode; ?></td>
            <td><input type="text" name="shipping_postcode" value="<?php echo $shipping_postcode; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_country; ?></td>
            <td><select name="shipping_country_id" id="shipping_country" onchange="$('#shipping_zone').load('index.php?route=sale/order/zone&token=<?php echo $token; ?>&country_id=' + this.value + '&zone_id=<?php echo $shipping_zone_id; ?>&type=shipping_zone');">
                <?php foreach ($countries as $country) { ?>
                <?php if ($country['country_id'] == $shipping_country_id) { ?>
                <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
              <input type="hidden" name="shipping_country" value="<?php echo $shipping_country; ?>" />
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_zone; ?></td>
            <td id="shipping_zone"></td>
          </tr>
          <tr>
		  	<td></td>
		  	<td><div style="margin-top: 10px; text-align: right;"><a onclick="address('shipping');" id="update_shipping_address" class="button"><span><?php echo $button_update_address; ?></span></a></div></td>
          </tr>
        </table>
        </form>
      </div>
      <div id="tab_payment" class="vtabs_page">
	  	<form id="payment_address">
        <table class="form">
          <tr>
            <td><?php echo $entry_firstname; ?></td>
            <td><input type="text" name="payment_firstname" value="<?php echo $payment_firstname; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_lastname; ?></td>
            <td><input type="text" name="payment_lastname" value="<?php echo $payment_lastname; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_company; ?></td>
            <td><input type="text" name="payment_company" value="<?php echo $payment_company; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_address_1; ?></td>
            <td><input type="text" name="payment_address_1" value="<?php echo $payment_address_1; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_address_2; ?></td>
            <td><input type="text" name="payment_address_2" value="<?php echo $payment_address_2; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_city; ?></td>
            <td><input type="text" name="payment_city" value="<?php echo $payment_city; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_postcode; ?></td>
            <td><input type="text" name="payment_postcode" value="<?php echo $payment_postcode; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_country; ?></td>
            <td><select name="payment_country_id" id="payment_country" onchange="$('#payment_zone').load('index.php?route=sale/order/zone&token=<?php echo $token; ?>&country_id=' + this.value + '&zone_id=<?php echo $payment_zone_id; ?>&type=payment_zone');">
                <?php foreach ($countries as $country) { ?>
                <?php if ($country['country_id'] == $payment_country_id) { ?>
                <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
              <input type="hidden" name="payment_country" value="<?php echo $payment_country; ?>" />
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_zone; ?></td>
            <td id="payment_zone"></td>
          </tr>
          <tr>
		  	<td></td>
		  	<td><div style="margin-top: 10px; text-align: right;"><a onclick="address('payment');" id="update_shipping_address" class="button"><span><?php echo $button_update_address; ?></span></a></div></td>
          </tr>
        </table>
        </form>
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
				<option value="0"><?php echo $text_none; ?></option>
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
            <td><?php echo $entry_append; ?></td>
            <td><input type="checkbox" name="append" value="1" checked="checked" /></td>
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

function address(type) {
	$.ajax({
		type: 'POST',
		url: 'index.php?route=sale/order/address&type='+type+'&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		dataType: 'json',
		data: $("#"+type+"_address").serialize(),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#tab_'+type+' form').before('<div class="attention"><img src="view/image/loading_1.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('.attention').remove();
		},
        error: function() {
			alert('failed');
		},
		success: function(data) {
			$('#tab_'+type+' form').before('<div class="success">' + data.success + '</div>');
		}
	});

	return false;
}

function removeProduct(id) {
	$.ajax({
		url: 'index.php?route=sale/order/removeProduct&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&order_product_id=' + id,
		dataType: 'json',
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#product').before('<div class="attention"><img src="view/image/loading_1.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('.attention').remove();
		},
        error: function() {
			alert('failed');
		},
		success: function(data) {
			$('#product_' + id).remove();
			$('#tab_product #product').before('<div class="success">' + data.success + '</div>');
			$('.grand_total').html(data.product_data['formatted_grand_total']);
			$('.subtotal').html(data.product_data['formatted_order_total']);

			// TU START
			$('.taxes').remove();
			for(var i in data.taxes_data) {
				$('#totals .grand_total').parent().before('<tr class="taxes"><td></td><td colspan="4" class="right">' + data.taxes_data[i]['title'] + '</td><td class="right">' + data.taxes_data[i]['text'] + '</td></tr>');
			} // TU END
		}
	});
}

function addProduct() {

	options = '';
	$('#option option:selected').each(function(i, opt) {
		options += $(opt).val() + '|';
	});

	$.ajax({
		type: 'POST',
		url: 'index.php?route=sale/order/addProduct&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		dataType: 'json',
		data: 'product_id=' + encodeURIComponent($('#products').val()) + '&option=' + options + '&quantity=' + encodeURIComponent($('input[name=\'add_quantity\']').val()) /*+ '&tax=' + encodeURIComponent($('input[name=\'add_tax\']').val()) // TU */,
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#product').before('<div class="attention"><img src="view/image/loading_1.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('.attention').remove();
		},
		error: function() {
			alert('failed');
		},
		success: function(data) {
			if (data.error) {
				$('#product').before('<div class="warning">' + data.error + '</div>');
			}

			if (data.success) {
				html  = '<tbody id="product_' + data.product_data['order_product_id'] + '">';
				html += '<tr>';
				html += '<td class="left" style="width:3px;">';
				html += '<span onclick="removeProduct(' + data.product_data['order_product_id'] + ');" class="remove">&nbsp;</span>';
				html += '</td>';
				html += '<td class="left">';
				html += '<a href="' + data.product_data['href'] +'">' + data.product_data['name'] + '</a>';
				for (k=0; k<data.product_data['options'].length; k++) {
					html += '<br/> &nbsp;<small> - ' + data.product_data['options'][k]['name'] + ' ' + data.product_data['options'][k]['value'] + '</small>';
				}
				html += '</td>';
				html += '<td class="left">'  + data.product_data['model'] + '</td>';
				html += '<td class="right">' + data.product_data['quantity'] + '</td>';
				html += '<td class="right">' + data.product_data['formatted_price'] + '</td>';
				html += '<td class="right">' + data.product_data['formatted_total'] + '</td>';
				html += '</tr>';
				html += '</tbody>';

				$('.grand_total').html(data.product_data['formatted_grand_total']);
				$('.subtotal').html(data.product_data['formatted_order_total']);

				// TU START
				$('.taxes').remove();
				for(var i in data.taxes_data) {
					$('#totals .grand_total').parent().before('<tr class="taxes"><td></td><td colspan="4" class="right">' + data.taxes_data[i]['title'] + '</td><td class="right">' + data.taxes_data[i]['text'] + '</td></tr>');
				} // TU END

				$('#totals').before(html);

				$('#tab_product #product').slideDown();

				$('#tab_product #product').before('<div class="success">' + data.success + '</div>');
			}
		}
	});
}
//--></script>
<script type="text/javascript"><!--
function getProducts() {
	$('#products option').remove();

	$.ajax({
		url: 'index.php?route=sale/order/category&token=<?php echo $token; ?>&category_id=' + $('#category').attr('value') + '&order_id=<?php echo $order_id; ?>',
		dataType: 'json',
		beforeSend: function() {
			$('#loading').remove();
			$('#products').after('&nbsp;<img id="loading" src="view/image/loading_1.gif" alt="" />');
		},
		success: function(data) {
			$('#loading').remove();
			for (i = 0; i < data.length; i++) {
	 			$('#products').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + ' [' + data[i]['model'] + '] - [' + data[i]['price'] + '] </option>');
			}
			getOptions();
		}
	});
}

function getOptions() {
	$('#option optgroup').remove();
	$('#option option').remove();

	$.ajax({
		url: 'index.php?route=sale/order/product&token=<?php echo $token; ?>&product_id=' + $('#products').attr('value') + '&order_id=<?php echo $order_id; ?>',
		dataType: 'json',
		beforeSend: function() {
			$('#loading').remove();
			$('#option').after('&nbsp;<img id="loading" src="view/image/loading_1.gif" alt="" />');
		},
		success: function(data) {
			$('#loading').remove();
			for (i = 0; i < data.length; i++) {
				$('#option').append('<optgroup id="optgroup_'+i+'" label="' + data[i]['language'][<?php echo $language_id; ?>]['name'] + '"></optgroup>');
				for (j = 0; j < data[i]['product_option_value'].length; j++) {
	 				$('#optgroup_'+i).append('<option value="' + data[i]['product_option_value'][j]['product_option_value_id'] + '">' + data[i]['product_option_value'][j]['language'][<?php echo $language_id; ?>]['name'] + ' [' + data[i]['product_option_value'][j]['prefix'] + data[i]['product_option_value'][j]['price'] + ']' +'</option>');
				}
			}
		}
	});
}

getProducts();
//--></script>
<script type="text/javascript"><!--
$('#generate_button').click(function() {
	$.ajax({
		url: 'index.php?route=sale/order/generate&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
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
		url: 'index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		dataType: 'json',
		data: 'order_status_id=' + encodeURIComponent($('select[name=\'order_status_id\']').val()) + '&notify=' + encodeURIComponent($('input[name=\'notify\']').attr('checked') ? 1 : 0) + '&append=' + encodeURIComponent($('input[name=\'append\']').attr('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'comment\']').val()),
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

			if (data.success && $('input[name=\'append\']').attr('checked')) {
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
<script type="text/javascript"><!--
$('#shipping_zone').load('index.php?route=sale/order/zone&token=<?php echo $token; ?>&country_id=<?php echo $shipping_country_id; ?>&zone_id=<?php echo $shipping_zone_id; ?>&type=shipping_zone');
$('#payment_zone').load('index.php?route=sale/order/zone&token=<?php echo $token; ?>&country_id=<?php echo $payment_country_id; ?>&zone_id=<?php echo $payment_zone_id; ?>&type=payment_zone');
//--></script>
<?php echo $footer; ?>