<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/order.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div style="display: inline-block; width: 100%;">
        <div class="vtabs"><a tab="#tab_contact">Customer Details</a><a tab="#tab_shipping">Shipping Address</a><a tab="#tab_payment">Payment Address</a><a tab="#tab_product">Products</a><a tab="#tab_order">Order Details</a></div>
        <div id="tab_contact" class="vtabs_page">
          <div style="background: #E7EFEF; border: 1px solid #C6D7D7; text-align: left; padding: 5px; margin-bottom: 15px; float: left;">
            <table>
              <tr>
                <td><input type="text" id="customer" value="" style="width: 300px;" /></td>
                <td><input type="button" value="Search" onclick="getCustomers();" /></td>
              </tr>
              <tr>
                <td><select id="customer_id" style="width: 307px;">
                    <option value="0"><?php echo $text_none; ?></option>
                  </select></td>
                <td><input type="button" value="Apply" onclick="setCustomer();" /></td>
              </tr>
            </table>
            <input type="hidden" name="customer_id" value="" />
          </div>
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
              <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
                <?php if ($error_firstname) { ?>
                <span class="error"><?php echo $error_firstname; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
              <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" />
                <?php if ($error_lastname) { ?>
                <span class="error"><?php echo $error_lastname; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_email; ?></td>
              <td><input type="text" name="email" value="<?php echo $email; ?>" />
                <?php if ($error_email) { ?>
                <span class="error"><?php echo $error_email; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
              <td><input type="text" name="telephone" value="<?php echo $telephone; ?>" />
                <?php if ($error_telephone) { ?>
                <span class="error"><?php echo $error_telephone; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_fax; ?></td>
              <td><input type="text" name="fax" value="<?php echo $fax; ?>" /></td>
            </tr>
          </table>
        </div>
        <div id="tab_shipping" class="vtabs_page">
          <div style="background: #E7EFEF; border: 1px solid #C6D7D7; text-align: left; padding: 5px; margin-bottom: 15px;">
            <table>
              <tr>
                <td>Use Address
                  <select id="shipping_address_id">
                    <option value="0"><?php echo $text_none; ?></option>
                  </select></td>
                <td><a onclick="addCustomer();" class="button"><span>Apply</span></a></td>
              </tr>
            </table>
          </div>
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
              <td><input type="text" name="shipping_firstname" value="<?php echo $shipping_firstname; ?>" /></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
              <td><input type="text" name="shipping_lastname" value="<?php echo $shipping_lastname; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_company; ?></td>
              <td><input type="text" name="shipping_company" value="<?php echo $shipping_company; ?>" /></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
              <td><input type="text" name="shipping_address_1" value="<?php echo $shipping_address_1; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_address_2; ?></td>
              <td><input type="text" name="shipping_address_2" value="<?php echo $shipping_address_2; ?>" /></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_city; ?></td>
              <td><input type="text" name="shipping_city" value="<?php echo $shipping_city; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_postcode; ?></td>
              <td><input type="text" name="shipping_postcode" value="<?php echo $shipping_postcode; ?>" /></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_country; ?></td>
              <td><select name="shipping_country_id" id="shipping_country_id" onchange="$('select[name=\'shipping_zone_id\']').load('index.php?route=sale/order/zone&country_id=' + this.value + '&zone_id=<?php echo $shipping_zone_id; ?>');">
                  <option value="FALSE"><?php echo $text_select; ?></option>
                  <?php foreach ($countries as $country) { ?>
                  <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                  <?php } ?>
                </select>
                <?php if ($error_shipping_country) { ?>
                <span class="error"><?php echo $error_shipping_country; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
              <td><select name="shipping_zone_id">
                </select>
                <?php if ($error_shipping_zone) { ?>
                <span class="error"><?php echo $error_shipping_zone; ?></span>
                <?php } ?></td>
            </tr>
          </table>
        </div>
        <div id="tab_payment" class="vtabs_page">
          <div style="background: #E7EFEF; border: 1px solid #C6D7D7; text-align: left; padding: 5px; margin-bottom: 15px;">
            <table>
              <tr>
                <td>Use Address
                  <select id="payment_address_id">
                    <option value="0"><?php echo $text_none; ?></option>
                  </select></td>
                <td><a onclick="addCustomer();" class="button"><span>Apply</span></a></td>
              </tr>
            </table>
          </div>
          <table class="form">
            <tr>
              <td><select>
                  <option>Same as billing address</option>
                </select></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
              <td><input type="text" name="payment_firstname" value="<?php echo $payment_firstname; ?>" /></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
              <td><input type="text" name="payment_lastname" value="<?php echo $payment_lastname; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_company; ?></td>
              <td><input type="text" name="payment_company" value="<?php echo $payment_company; ?>" /></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
              <td><input type="text" name="payment_address_1" value="<?php echo $payment_address_1; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_address_2; ?></td>
              <td><input type="text" name="payment_address_2" value="<?php echo $payment_address_2; ?>" /></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_city; ?></td>
              <td><input type="text" name="payment_city" value="<?php echo $payment_city; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_postcode; ?></td>
              <td><input type="text" name="payment_postcode" value="<?php echo $payment_postcode; ?>" /></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_country; ?></td>
              <td><select name="payment_country_id" id="payment_country_id" onchange="$('select[name=\'payment_zone_id\']').load('index.php?route=sale/order/zone&country_id=' + this.value + '&zone_id=<?php echo $payment_zone_id; ?>');">
                  <option value="FALSE"><?php echo $text_select; ?></option>
                  <?php foreach ($countries as $country) { ?>
                  <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                  <?php } ?>
                </select>
                <?php if ($error_payment_country) { ?>
                <span class="error"><?php echo $error_payment_country; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
              <td><select name="payment_zone_id">
                </select>
                <?php if ($error_payment_zone) { ?>
                <span class="error"><?php echo $error_payment_zone; ?></span>
                <?php } ?></td>
            </tr>
          </table>
        </div>
        <div id="tab_product" class="vtabs_page">
          <div style="background: #E7EFEF; border: 1px solid #C6D7D7; text-align: left; padding: 5px; margin-bottom: 15px; float: left;">
            <table>
              <tr>
                <td><input type="text" id="product" value="" style="width: 300px;" />
                  <input type="button" value="Search" onclick="getProducts();" /></td>
              </tr>
              <tr>
                <td><select id="product_id" style="width: 307px;">
                    <option value="0"><?php echo $text_none; ?></option>
                  </select>&nbsp;<input type="button" value="Apply" onclick="addProduct();" /></td>
              </tr>
            </table>
          </div>
          <table id="product" class="list">
            <thead>
              <tr>
                <td class="left">Product Name:</td>
                <td class="left">Model:</td>
                <td class="right">Quantity:</td>
                <td class="right">Unit Price:</td>
                <td class="right">Tax (%):</td>
                <td class="right">Total:</td>
                <td></td>
              </tr>
            </thead>
            <?php $product_row = 0; ?>
            <?php foreach ($products as $product) { ?>
            <tbody id="product_row<?php echo $product_row; ?>">
              <tr>
                <td class="left"><input type="hidden" name="product[<?php echo $product_row; ?>][product_id]" value="<?php echo $product['product_id']; ?>" />
                  <input type="text" name="product[<?php echo $product_row; ?>][name]" value="<?php echo $product['name']; ?>" /></td>
                <td class="left"><input type="text" name="product[<?php echo $product_row; ?>][model]" value="<?php echo $product['model']; ?>" onblur="getProduct('<?php echo $product_row; ?>');" /></td>
                <td class="right"><input type="text" name="product[<?php echo $product_row; ?>][quantity]" value="<?php echo $product['quantity']; ?>" size="3" /></td>
                <td class="right"><input type="text" name="product[<?php echo $product_row; ?>][price]" value="<?php echo $product['price']; ?>" /></td>
                <td class="right"><input type="text" name="product[<?php echo $product_row; ?>][price]" value="<?php echo $product['price']; ?>" /></td>
                <td class="right"></td>
                <td class="left"><a onclick="$('#product_row<?php echo $product_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
              </tr>
              <tr>
                <td class="left"><input type="hidden" name="product[<?php echo $product_row; ?>][product_id]" value="<?php echo $product['product_id']; ?>" />
                  <input type="text" name="product[<?php echo $product_row; ?>][name]" value="<?php echo $product['name']; ?>" /></td>
                <td class="left"><input type="text" name="product[<?php echo $product_row; ?>][model]" value="<?php echo $product['model']; ?>" /></td>
                <td class="right"><input type="text" name="product[<?php echo $product_row; ?>][quantity]" value="<?php echo $product['quantity']; ?>" size="3" /></td>
                <td class="right"><input type="text" name="product[<?php echo $product_row; ?>][price]" value="<?php echo $product['price']; ?>" /></td>
                <td class="right"><input type="text" name="product[<?php echo $product_row; ?>][price]" value="<?php echo $product['price']; ?>" /></td>
                <td class="right"></td>
                <td class="left"><a onclick="$('#product_row<?php echo $product_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
              </tr>
            </tbody>
            <?php $product_row++; ?>
            <?php } ?>
            <tbody class="no_result">
              <tr>
                <td class="center" colspan="7">No results!</td>
              </tr>
            </tbody>
            <tfoot>
            </tfoot>
          </table>
        </div>
        <div id="tab_order" class="vtabs_page">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $entry_shipping_method; ?></td>
              <td><input type="text" name="shipping_method" value="<?php echo $shipping_method; ?>" /></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_payment_method; ?></td>
              <td><input type="text" name="payment_method" value="<?php echo $payment_method; ?>" /></td>
            </tr>
            <tr>
              <td style="text-align: right;" colspan="2"><a onclick="addTotal();" class="button"><span>Calculte Methods</span></a></td>
            </tr>
          </table>
          <table class="form">
            <tr>
              <td><?php echo $entry_coupon; ?></td>
              <td><input type="text" name="coupon" value="<?php echo $coupon; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="order_status_id">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td>Send E-Mail Notifcation:</td>
              <td><select name="order_status_id">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_comment; ?></td>
              <td><textarea name="comment" cols="40" rows="8" style="width: 99%"><?php echo $comment; ?></textarea></td>
            </tr>
          </table>
          <table id="total" class="list">
            <thead>
              <tr>
                <td class="left">Total:</td>
                <td class="left">Value:</td>
                <td></td>
              </tr>
            </thead>
            <?php $total_row = 0; ?>
            <?php foreach ($totals as $total) { ?>
            <tbody id="total_row<?php echo $total_row; ?>">
              <tr>
                <td class="left"><input type="text" name="total[<?php echo $total_row; ?>][title]" value="<?php echo $total['title']; ?>" /></td>
                <td class="left"><input type="text" name="total[<?php echo $total_row; ?>][value]" value="<?php echo $total['value']; ?>" onblur="getProduct('<?php echo $product_row; ?>');" /></td>
                <td class="left"><a onclick="$('#total_row<?php echo $total_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
              </tr>
            </tbody>
            <?php $product_row++; ?>
            <?php } ?>
            <tfoot>
              <tr>
                <td colspan="2"></td>
                <td class="left"><a onclick="addTotal();" class="button"><span>Add Total</span></a></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
function getCustomers() {
	$('#customer_id option').remove();
	
	$.ajax({
		url: 'index.php?route=sale/order/customers&keyword=' + encodeURIComponent($('#customer').attr('value')),
		dataType: 'json',
		success: function(data) {
			$('#customer_id').append('<option value="0"><?php echo $text_none; ?></option>');
			
			for (i = 0; i < data.length; i++) {
	 			$('#customer_id').append('<option value="' + data[i]['customer_id'] + '">' + data[i]['name'] + '</option>');
			}
		}
	});
}

function setCustomer() {
	$.ajax({
		url: 'index.php?route=sale/order/customer&customer_id=' + $('#customer_id').attr('value'),
		dataType: 'json',
		success: function(data) {
			if (data.length) {
				$('input[name=\'customer_id\']').attr('value', $('#customer_id').attr('value'));
				$('input[name=\'firstname\']').attr('value', data.firstname);
				$('input[name=\'lastname\']').attr('value', data.lastname);
				$('input[name=\'email\']').attr('value', data.email);
				$('input[name=\'telephone\']').attr('value', data.telephone);
				$('input[name=\'fax\']').attr('value', data.fax);
			} else {
				$('input[name=\'customer_id\']').attr('value', '');
				$('input[name=\'firstname\']').attr('value', '');
				$('input[name=\'lastname\']').attr('value', '');
				$('input[name=\'email\']').attr('value', '');
				$('input[name=\'telephone\']').attr('value', '');
				$('input[name=\'fax\']').attr('value', '');				
			}
		}
	});	
}

function getProducts() {
	$('#product_id option').remove();
	
	$.ajax({
		url: 'index.php?route=sale/order/products&keyword=' + encodeURIComponent($('#product').attr('value')),
		dataType: 'json',
		success: function(data) {
			$('#product_id').append('<option value="0"><?php echo $text_none; ?></option>');
			
			for (i = 0; i < data.length; i++) {
	 			$('#product_id').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + '</option>');
			}
			
			$('#product .no_result').remove();
		}
	});
}

var product_row = <?php echo $product_row; ?>;

function addProduct() {
	$.ajax({
		type: 'GET',
		url: 'index.php?route=sale/order/product&product_id=' + $('#product_id').attr('value'),
		dataType: 'json',
		success: function(data) {
			alert('hi');
			if (data.product_id) {
				html  = '<tbody id="product_row' + product_row + '">';
				html += '  <tr>';
				html += '    <td class="left"><input type="text" name="product[' + product_row + '][name]" value="' + data.name + '" /></td>';
				html += '    <td class="left"><input type="text" name="product[' + product_row + '][model]" value="' + data.name + '" /></td>';
				html += '    <td class="right"><input type="text" name="product[' + product_row + '][quantity]" value="1" size="3" /></td>';
				html += '    <td class="right"><input type="text" name="product[' + product_row + '][price]" value="' + data.price + '" /></td>';
				html += '    <td class="right"><input type="text" name="product[' + product_row + '][tax]" value="" /></td>';
				html += '    <td class="right"></td>';
				html += '    <td class="left"><a onclick="$(\'#product_row' + product_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a><input type="hidden" name="product[' + product_row + '][product_id]" value="' + $('#product_id').attr('value') + '" /></td>';
				html += '  </tr>';
				html += '</tbody>';
						
				$('#product > tfoot').before(html);
	
				product_row++;
			}
		}
	});	
}

function getAddress(type, address_id) {
	$.ajax({
		url: 'index.php?route=sale/order/address&address_id=' + $('#address_id').attr('value'),
		dataType: 'json',
		success: function(data) {
			if (data) {
				$('input[name=\'shipping_firstname\']').attr('value', data.firstname);
				$('input[name=\'shipping_lastname\']').attr('value', data.lastname);
				$('input[name=\'shipping_company\']\']').attr('value', data.company);
				$('input[name=\'shipping_address_1\']\']').attr('value', data.address_1);
				$('input[name=\'shipping_address_2\']\']').attr('value', data.address_2);
				$('input[name=\'shipping_city\']\']').attr('value', data.city);
				$('input[name=\'shipping_postcode\']\']').attr('value', data.postcode);
				$('input[name=\'shipping_country_id\']\']').attr('value', data.country_id);
				$('input[name=\'shipping_zone_id\']\']').attr('value', data.zone_id);
				
				//$('select[name=\'shipping_zone_id\']').load('index.php?route=sale/order/zone&country_id=<?php echo $shipping_country_id; ?>&zone_id=<?php echo $shipping_zone_id; ?>');
				
				//$('#shipping_country_id').attr('value', '<?php echo $shipping_country_id; ?>');
			}
		}
	});	
}
//--></script>
<script type="text/javascript"><!--
$('select[name=\'shipping_zone_id\']').load('index.php?route=sale/order/zone&country_id=<?php echo $shipping_country_id; ?>&zone_id=<?php echo $shipping_zone_id; ?>');

$('#shipping_country_id').attr('value', '<?php echo $shipping_country_id; ?>');

$('select[name=\'payment_zone_id\']').load('index.php?route=sale/order/zone&country_id=<?php echo $payment_country_id; ?>&zone_id=<?php echo $payment_zone_id; ?>');

$('#payment_country_id').attr('value', '<?php echo $payment_country_id; ?>');
//--></script>
<script type="text/javascript"><!--
$.tabs('.vtabs a'); 
//--></script>
<?php echo $footer; ?>