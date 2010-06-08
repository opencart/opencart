<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div class="htabs">
        <?php foreach ($languages as $language) { ?>
        <a tab="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
        <?php } ?>
      </div>
      <?php foreach ($languages as $language) { ?>
      <div id="language<?php echo $language['language_id']; ?>">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_name; ?></td>
            <td><input name="coupon_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($coupon_description[$language['language_id']]) ? $coupon_description[$language['language_id']]['name'] : ''; ?>" />
              <?php if (isset($error_name[$language['language_id']])) { ?>
              <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_description; ?></td>
            <td><textarea name="coupon_description[<?php echo $language['language_id']; ?>][description]" cols="40" rows="5"><?php echo isset($coupon_description[$language['language_id']]) ? $coupon_description[$language['language_id']]['description'] : ''; ?></textarea>
              <?php if (isset($error_description[$language['language_id']])) { ?>
              <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
              <?php } ?></td>
          </tr>
        </table>
      </div>
      <?php } ?>
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_code; ?></td>
          <td><input type="text" name="code" value="<?php echo $code; ?>" />
            <?php if ($error_code) { ?>
            <span class="error"><?php echo $error_code; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_type; ?></td>
          <td><select name="type">
              <?php if ($type == 'P') { ?>
              <option value="P" selected="selected"><?php echo $text_percent; ?></option>
              <?php } else { ?>
              <option value="P"><?php echo $text_percent; ?></option>
              <?php } ?>
              <?php if ($type == 'F') { ?>
              <option value="F" selected="selected"><?php echo $text_amount; ?></option>
              <?php } else { ?>
              <option value="F"><?php echo $text_amount; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_discount; ?></td>
          <td><input type="text" name="discount" value="<?php echo $discount; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_total; ?></td>
          <td><input type="text" name="total" value="<?php echo $total; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_logged; ?></td>
          <td><?php if ($logged) { ?>
            <input type="radio" name="logged" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="logged" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="logged" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="logged" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_shipping; ?></td>
          <td><?php if ($shipping) { ?>
            <input type="radio" name="shipping" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="shipping" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="shipping" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="shipping" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_product; ?></td>
          <td><table>
              <tr>
                <td style="padding: 0;" colspan="3"><select id="category" style="margin-bottom: 5px;" onchange="getProducts();">
                    <?php foreach ($categories as $category) { ?>
                    <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td style="padding: 0;"><select multiple="multiple" id="product" size="10" style="width: 200px;">
                  </select></td>
                <td style="vertical-align: middle;"><input type="button" value="--&gt;" onclick="addProduct();" />
                  <br />
                  <input type="button" value="&lt;--" onclick="removeProduct();" /></td>
                <td style="padding: 0;"><select multiple="multiple" id="coupon" size="10" style="width: 200px;">
                  </select></td>
              </tr>
            </table>
            <div id="coupon_product">
              <?php foreach ($coupon_product as $product_id) { ?>
              <input type="hidden" name="coupon_product[]" value="<?php echo $product_id; ?>" />
              <?php } ?>
            </div></td>
        </tr>
        <tr>
          <td><?php echo $entry_date_start; ?></td>
          <td><input type="text" name="date_start" value="<?php echo $date_start; ?>" size="12" id="date_start" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_date_end; ?></td>
          <td><input type="text" name="date_end" value="<?php echo $date_end; ?>" size="12" id="date_end" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_uses_total; ?></td>
          <td><input type="text" name="uses_total" value="<?php echo $uses_total; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_uses_customer; ?></td>
          <td><input type="text" name="uses_customer" value="<?php echo $uses_customer; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="status">
              <?php if ($status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
function addProduct() {
	$('#product :selected').each(function() {
		$(this).remove();
		
		$('#coupon option[value=\'' + $(this).attr('value') + '\']').remove();
		
		$('#coupon').append('<option value="' + $(this).attr('value') + '">' + $(this).text() + '</option>');
		
		$('#coupon_product input[value=\'' + $(this).attr('value') + '\']').remove();
		
		$('#coupon_product').append('<input type="hidden" name="coupon_product[]" value="' + $(this).attr('value') + '" />');
	});
}

function removeProduct() {
	$('#coupon :selected').each(function() {
		$(this).remove();
		
		$('#coupon_product input[value=\'' + $(this).attr('value') + '\']').remove();
	});
}

function getProducts() {
	$('#product option').remove();
	
	$.ajax({
		url: 'index.php?route=sale/coupon/category&token=<?php echo $token; ?>&category_id=' + $('#category').attr('value'),
		dataType: 'json',
		success: function(data) {
			for (i = 0; i < data.length; i++) {
	 			$('#product').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + '</option>');
			}
		}
	});
}

function getProduct() {
	$('#coupon option').remove();
	
	$.ajax({
		url: 'index.php?route=sale/coupon/product&token=<?php echo $token; ?>',
		type: 'POST',
		dataType: 'json',
		data: $('#coupon_product input'),
		success: function(data) {
			$('#coupon_product input').remove();
			
			for (i = 0; i < data.length; i++) {
	 			$('#coupon').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + '</option>');
				
				$('#coupon_product').append('<input type="hidden" name="coupon_product[]" value="' + data[i]['product_id'] + '" />');
			} 
		}
	});
}

getProducts();
getProduct();
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date_start').datepicker({dateFormat: 'yy-mm-dd'});
	
	$('#date_end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
$.tabs('.htabs a'); 
//--></script>
<?php echo $footer; ?>