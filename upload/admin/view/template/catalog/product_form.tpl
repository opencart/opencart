<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a><a tab="#tab_data"><?php echo $tab_data; ?></a><a tab="#tab_option"><?php echo $tab_option; ?></a><a tab="#tab_discount"><?php echo $tab_discount; ?></a><a tab="#tab_special"><?php echo $tab_special; ?></a><a tab="#tab_image"><?php echo $tab_image; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page">
    <table class="form">
      <?php foreach ($languages as $language) { ?>
      <tr>
        <td width="180"><span class="required">*</span> <?php echo $entry_name; ?></td>
        <td><input type="text" name="product_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name'] : ''; ?>" />
          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
          <?php if (isset($error_name[$language['language_id']])) { ?>
          <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_meta_description; ?></td>
        <td><textarea name="product_description[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="5"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_description; ?></td>
        <td><textarea name="product_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['description'] : ''; ?></textarea>
          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" /></td>
      </tr>
      <?php } ?>
    </table>
  </div>
  <div id="tab_data" class="page">
    <table class="form">
      <tr>
        <td width="180"><span class="required">*</span> <?php echo $entry_model; ?></td>
        <td><input type="text" name="model" value="<?php echo $model; ?>" />
          <br />
          <?php if ($error_model) { ?>
          <span class="error"><?php echo $error_model; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_sku; ?></td>
        <td><input type="text" name="sku" value="<?php echo $sku; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_location; ?></td>
        <td><input type="text" name="location" value="<?php echo $location; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_keyword; ?></td>
        <td><input type="text" name="keyword" value="<?php echo $keyword; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_image; ?></td>
        <td><input type="file" name="image" /></td>
      </tr>
      <tr>
        <td></td>
        <td><img src="<?php echo $preview; ?>" alt="" style="margin: 4px 0px; border: 1px solid #EEEEEE;" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_manufacturer; ?></td>
        <td><select name="manufacturer_id">
            <option value="0" selected="selected"><?php echo $text_none; ?></option>
            <?php foreach ($manufacturers as $manufacturer) { ?>
            <?php if ($manufacturer['manufacturer_id'] == $manufacturer_id) { ?>
            <option value="<?php echo $manufacturer['manufacturer_id']; ?>" selected="selected"><?php echo $manufacturer['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
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
        <td><?php echo $entry_date_available; ?></td>
        <td><input type="text" name="date_available" value="<?php echo $date_available; ?>" size="12" class="date" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_quantity; ?></td>
        <td><input type="text" name="quantity" value="<?php echo $quantity; ?>" size="2" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_stock_status; ?></td>
        <td><select name="stock_status_id">
            <?php foreach ($stock_statuses as $stock_status) { ?>
            <?php if ($stock_status['stock_status_id'] == $stock_status_id) { ?>
            <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
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
      <tr>
        <td><?php echo $entry_tax_class; ?></td>
        <td><select name="tax_class_id">
            <option value="0"><?php echo $text_none; ?></option>
            <?php foreach ($tax_classes as $tax_class) { ?>
            <?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
            <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_price; ?></td>
        <td><input type="text" name="price" value="<?php echo $price; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_dimension; ?></td>
        <td><input type="text" name="length" value="<?php echo $length; ?>" size="4" />
          <input type="text" name="width" value="<?php echo $width; ?>" size="4" />
          <input type="text" name="height" value="<?php echo $height; ?>" size="4" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_measurement; ?></td>
        <td><select name="measurement_class_id">
            <?php foreach ($measurement_classes as $measurement_class) { ?>
            <?php if ($measurement_class['measurement_class_id'] == $measurement_class_id) { ?>
            <option value="<?php echo $measurement_class['measurement_class_id']; ?>" selected="selected"><?php echo $measurement_class['title']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $measurement_class['measurement_class_id']; ?>"><?php echo $measurement_class['title']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_weight; ?></td>
        <td><input type="text" name="weight" value="<?php echo $weight; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_weight_class; ?></td>
        <td><select name="weight_class_id">
            <?php foreach ($weight_classes as $weight_class) { ?>
            <?php if ($weight_class['weight_class_id'] == $weight_class_id) { ?>
            <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_category; ?></td>
        <td><div class="scrollbox">
            <?php $class = 'odd'; ?>
            <?php foreach ($categories as $category) { ?>
            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
            <div class="<?php echo $class; ?>">
              <?php if (in_array($category['category_id'], $product_category)) { ?>
              <input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
              <?php echo $category['name']; ?>
              <?php } else { ?>
              <input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" />
              <?php echo $category['name']; ?>
              <?php } ?>
            </div>
            <?php } ?>
          </div></td>
      </tr>
      <tr>
        <td><?php echo $entry_download; ?></td>
        <td><div class="scrollbox">
            <?php $class = 'odd'; ?>
            <?php foreach ($downloads as $download) { ?>
            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
            <div class="<?php echo $class; ?>">
              <?php if (in_array($download['download_id'], $product_download)) { ?>
              <input type="checkbox" name="product_download[]" value="<?php echo $download['download_id']; ?>" checked="checked" />
              <?php echo $download['name']; ?>
              <?php } else { ?>
              <input type="checkbox" name="product_download[]" value="<?php echo $download['download_id']; ?>" />
              <?php echo $download['name']; ?>
              <?php } ?>
            </div>
            <?php } ?>
          </div></td>
      </tr>
      <tr>
        <td><?php echo $entry_related; ?></td>
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
              <td style="vertical-align: middle;"><input type="button" value="--&gt;" onclick="addRelated();" />
                <br />
                <input type="button" value="&lt;--" onclick="removeRelated();" /></td>
              <td style="padding: 0;"><select multiple="multiple" id="related" size="10" style="width: 200px;">
                </select></td>
            </tr>
          </table>
          <div id="product_related">
            <?php foreach ($product_related as $related_id) { ?>
            <input type="hidden" name="product_related[]" value="<?php echo $related_id; ?>" />
            <?php } ?>
          </div></td>
      </tr>
    </table>
  </div>
  <div id="tab_option" class="page">
    <?php $option_row = 0; ?>
    <?php $option_value_row = 0; ?>
    <?php foreach ($product_options as $product_option) { ?>
    <div id="option_row<?php echo $option_row; ?>">
      <div class="green">
        <table class="form">
          <tr>
            <td width="180"><?php echo $entry_option; ?></td>
            <td><?php foreach ($languages as $language) { ?>
              <input type="text" name="product_option[<?php echo $option_row; ?>][language][<?php echo $language['language_id']; ?>][name]" value="<?php echo $product_option['language'][$language['language_id']]['name']; ?>" />
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
              <?php } ?></td>
            <td rowspan="2"><a onclick="$('#option_row<?php echo $option_row; ?>').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="product_option[<?php echo $option_row; ?>][sort_order]" value="<?php echo $product_option['sort_order']; ?>" size="5" /></td>
          </tr>
        </table>
      </div>
      <?php foreach ($product_option['product_option_value'] as $product_option_value) { ?>
      <div id="option_value_row<?php echo $option_value_row; ?>">
        <div class="green">
          <table class="form">
            <tr>
              <td width="180"><?php echo $entry_option_value; ?></td>
              <td><?php foreach ($languages as $language) { ?>
                <input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][language][<?php echo $language['language_id']; ?>][name]" value="<?php echo $product_option_value['language'][$language['language_id']]['name']; ?>" />
                <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                <?php } ?></td>
              <td rowspan="6"><a onclick="$('#option_value_row<?php echo $option_value_row; ?>').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>
            </tr>
            <tr>
              <td><?php echo $entry_quantity; ?></td>
              <td><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][quantity]" value="<?php echo $product_option_value['quantity']; ?>" size="2" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_subtract; ?></td>
              <td><?php if ($product_option_value['subtract']) { ?>
                <input type="radio" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][subtract]" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][subtract]" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][subtract]" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][subtract]" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_price; ?></td>
              <td><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price]" value="<?php echo $product_option_value['price']; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_prefix; ?></td>
              <td><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][prefix]">
                  <?php  if ($product_option_value['prefix'] != '-') { ?>
                  <option value="+" selected="selected"><?php echo $text_plus; ?></option>
                  <option value="-"><?php echo $text_minus; ?></option>
                  <?php } else { ?>
                  <option value="+"><?php echo $text_plus; ?></option>
                  <option value="-" selected="selected"><?php echo $text_minus; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_sort_order; ?></td>
              <td><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][sort_order]" value="<?php echo $product_option_value['sort_order']; ?>" size="5" style="margin-top: 3px;" /></td>
            </tr>
          </table>
        </div>
      </div>
      <?php $option_value_row++; ?>
      <?php } ?>
      <a id="add_option_value<?php echo $option_row; ?>" onclick="addOptionValue('<?php echo $option_row; ?>');" style="margin-bottom: 15px;" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_add_option_value; ?></span><span class="button_right"></span></a> </div>
    <?php $option_row++; ?>
    <?php } ?>
    <a id="add_option" onclick="addOption();" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_add_option; ?></span><span class="button_right"></span></a></div>
  <div id="tab_discount" class="page">
    <div id="discount">
      <?php $discount_row = 0; ?>
      <?php foreach ($product_discounts as $product_discount) { ?>
      <table width="100%" class="green" id="discount_row<?php echo $discount_row; ?>">
        <tr>
          <td><?php echo $entry_customer_group; ?><br />
            <select name="product_discount[<?php echo $discount_row; ?>][customer_group_id]" style="margin-top: 3px;">
              <?php foreach ($customer_groups as $customer_group) { ?>
              <?php if ($customer_group['customer_group_id'] == $product_discount['customer_group_id']) { ?>
              <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
          <td><?php echo $entry_quantity; ?><br />
            <input type="text" name="product_discount[<?php echo $discount_row; ?>][quantity]" value="<?php echo $product_discount['quantity']; ?>" size="2" style="margin-top: 3px;" /></td>
          <td><?php echo $entry_priority; ?><br />
            <input type="text" name="product_discount[<?php echo $discount_row; ?>][priority]" value="<?php echo $product_discount['priority']; ?>" size="2" style="margin-top: 3px;" /></td>
          <td><?php echo $entry_price; ?><br />
            <input type="text" name="product_discount[<?php echo $discount_row; ?>][price]" value="<?php echo $product_discount['price']; ?>" style="margin-top: 3px;" /></td>
          <td><a onclick="$('#discount_row<?php echo $discount_row; ?>').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>
        </tr>
        <tr>
          <td><?php echo $entry_date_start; ?><br />
            <input type="text" name="product_discount[<?php echo $discount_row; ?>][date_start]" value="<?php echo $product_discount['date_start']; ?>" class="date" style="margin-top: 3px;" /></td>
          <td colspan="3"><?php echo $entry_date_end; ?><br />
            <input type="text" name="product_discount[<?php echo $discount_row; ?>][date_end]" value="<?php echo $product_discount['date_end']; ?>" class="date" style="margin-top: 3px;" /></td>
        </tr>
      </table>
      <?php $discount_row++; ?>
      <?php } ?>
    </div>
    <a onclick="addDiscount();" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_add_discount; ?></span><span class="button_right"></span></a></div>
  <div id="tab_special" class="page">
    <div id="special">
      <?php $special_row = 0; ?>
      <?php foreach ($product_specials as $product_special) { ?>
      <table width="100%" class="green" id="special_row<?php echo $special_row; ?>">
        <tr>
          <td><?php echo $entry_customer_group; ?><br />
            <select name="product_special[<?php echo $special_row; ?>][customer_group_id]" style="margin-top: 3px;">
              <?php foreach ($customer_groups as $customer_group) { ?>
              <?php if ($customer_group['customer_group_id'] == $product_special['customer_group_id']) { ?>
              <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
          <td><?php echo $entry_priority; ?><br />
            <input type="text" name="product_special[<?php echo $special_row; ?>][priority]" value="<?php echo $product_special['priority']; ?>" size="2" style="margin-top: 3px;" /></td>
          <td><?php echo $entry_price; ?><br />
            <input type="text" name="product_special[<?php echo $special_row; ?>][price]" value="<?php echo $product_special['price']; ?>" style="margin-top: 3px;" /></td>
          <td rowspan="2"><a onclick="$('#special_row<?php echo $special_row; ?>').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>
        </tr>
        <tr>
          <td><?php echo $entry_date_start; ?><br />
            <input type="text" name="product_special[<?php echo $special_row; ?>][date_start]" value="<?php echo $product_special['date_start']; ?>" class="date" style="margin-top: 3px;" /></td>
          <td colspan="2"><?php echo $entry_date_end; ?><br />
            <input type="text" name="product_special[<?php echo $special_row; ?>][date_end]" value="<?php echo $product_special['date_end']; ?>" class="date" style="margin-top: 3px;" /></td>
        </tr>
      </table>
      <?php $special_row++; ?>
      <?php } ?>
    </div>
    <a onclick="addSpecial();" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_add_special; ?></span><span class="button_right"></span></a></div>
  <div id="tab_image" class="page">
    <div id="images">
      <?php $image_row = 0; ?>
      <?php foreach ($product_images as $product_image) { ?>
      <table width="100%" id="image_row<?php echo $image_row; ?>" class="green">
        <tr>
          <td><img src="<?php echo $product_image['preview']; ?>" alt="" /></td>
          <td><div style="margin-bottom: 4px;"><?php echo $entry_image; ?></div>
            <input type="file" name="product_image[<?php echo $image_row; ?>]"  />
            <input type="hidden" name="product_image[<?php echo $image_row; ?>]" value="<?php echo $product_image['file']; ?>"  /></td>
          <td><a onclick="$('#image_row<?php echo $image_row; ?>').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>
        </tr>
      </table>
      <?php $image_row++; ?>
      <?php } ?>
    </div>
    <a onclick="addImage();" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_add_image; ?></span><span class="button_right"></span></a> </div>
</form>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('description<?php echo $language['language_id']; ?>');
<?php } ?>
//--></script>
<script type="text/javascript"><!--
function addRelated() {
	$('#product :selected').each(function() {
		$(this).remove();
		
		$('#related option[value=\'' + $(this).attr('value') + '\']').remove();
		
		$('#related').append('<option value="' + $(this).attr('value') + '">' + $(this).text() + '</option>');
		
		$('#product_related input[value=\'' + $(this).attr('value') + '\']').remove();
		
		$('#product_related').append('<input type="hidden" name="product_related[]" value="' + $(this).attr('value') + '" />');
	});
}

function removeRelated() {
	$('#related :selected').each(function() {
		$(this).remove();
		
		$('#product_related input[value=\'' + $(this).attr('value') + '\']').remove();
	});
}

function getProducts() {
	$('#product option').remove();
	
	$.ajax({
		url: 'index.php?route=catalog/product/category&category_id=' + $('#category').attr('value'),
		dataType: 'json',
		success: function(data) {
			for (i = 0; i < data.length; i++) {
	 			$('#product').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + '</option>');
			}
		}
	});
}

function getRelated() {
	$('#related option').remove();
	
	$.ajax({
		url: 'index.php?route=catalog/product/related',
		type: 'POST',
		dataType: 'json',
		data: $('#product_related input'),
		success: function(data) {
			$('#product_related input').remove();
			
			for (i = 0; i < data.length; i++) {
	 			$('#related').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + '</option>');
				
				$('#product_related').append('<input type="hidden" name="product_related[]" value="' + data[i]['product_id'] + '" />');
			} 
		}
	});
}

getProducts();
getRelated();
//--></script>
<script type="text/javascript"><!--
var option_row = <?php echo $option_row; ?>;

function addOption() {	
	html  = '<div id="option_row' + option_row + '">';
	html += '<div class="green">';
	html += '<table class="form">';
	html += '<tr>';
	html += '<td width="180"><?php echo $entry_option; ?></td>';
	html += '<td>';
	<?php foreach ($languages as $language) { ?>
	html += '<input type="text" name="product_option[' + option_row + '][language][<?php echo $language['language_id']; ?>][name]" value="" />&nbsp;';
	html += '<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
	<?php } ?>
	html += '</td>';
	html += '<td rowspan="2"><a onclick="$(\'#option_row' + option_row + '\').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>';
	html += '</tr>';
	html += '<tr>';
	html += '<td><?php echo $entry_sort_order; ?></td>';
	html += '<td><input type="text" name="product_option[' + option_row + '][sort_order]" value="" size="5" /></td>';
	html += '</tr>';
	html += '</table>';
	html += '</div>';
	html += '<a id="add_option_value' + option_row + '" onclick="addOptionValue(\'' + option_row + '\');" style="margin-bottom: 15px;" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_add_option_value; ?></span><span class="button_right"></span></a>';
	html += '</div>';
	 
	$('#add_option').before(html);
	
	option_row++;
}

var option_value_row = <?php echo $option_value_row; ?>;

function addOptionValue(option_id) {
	html  = '<div id="option_value_row' + option_value_row + '">';
	html += '<div class="green">';
	html += '<table class="form">';
	html += '<tr>';
	html += '<td width="180"><?php echo $entry_option_value; ?></td>';
	html += '<td>';
	<?php foreach ($languages as $language) { ?>
	html += '<input type="text" name="product_option[' + option_id + '][product_option_value][' + option_value_row + '][language][<?php echo $language['language_id']; ?>][name]" value="" />&nbsp;';
	html += '<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
	<?php } ?>
	html += '</td>';
	html += '<td rowspan="6"><a onclick="$(\'#option_value_row' + option_value_row + '\').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>';
	html += '</tr>';
	html += '<tr>';
	html += '<td><?php echo $entry_quantity; ?></td>';
	html += '<td><input type="text" name="product_option[' + option_id + '][product_option_value][' + option_value_row + '][quantity]" value="" size="2" /></td>';
	html += '</tr>';
	html += '<tr>';
	html += '<td><?php echo $entry_subtract; ?></td>';
	html += '<td>';
	html += '<input type="radio" name="product_option[' + option_id + '][product_option_value][' + option_value_row + '][subtract]" value="1" />&nbsp;';
	html += '<?php echo $text_yes; ?>&nbsp;';
	html += '<input type="radio" name="product_option[' + option_id + '][product_option_value][' + option_value_row + '][subtract]" value="0" checked="checked" />&nbsp;';
	html += '<?php echo $text_no; ?>';
	html += '</td>';
	html += '</tr>';
	html += '<tr>';
	html += '<td><?php echo $entry_price; ?></td>';
	html += '<td><input type="text" name="product_option[' + option_id + '][product_option_value][' + option_value_row + '][price]" value="" /></td>';
	html += '</tr>';
	html += '<tr>';
	html += '<td><?php echo $entry_prefix; ?></td>';
	html += '<td><select name="product_option[' + option_id + '][product_option_value][' + option_value_row + '][prefix]">';
	html += '<option value="+"><?php echo $text_plus; ?></option>';
	html += '<option value="-" selected="selected"><?php echo $text_minus; ?></option>';
	html += '</select></td>';
	html += '</tr>';
	html += '<tr>';
	html += '<td><?php echo $entry_sort_order; ?></td>';
	html += '<td><input type="text" name="product_option[' + option_id + '][product_option_value][' + option_value_row + '][sort_order]" value="" size="5" /></td>';
	html += '</tr>';
	html += '</table>';
	html += '</div>';
	html += '</div>';

	$('#add_option_value' + option_id).before(html);
	
	option_value_row++;
}
//--></script>
<script type="text/javascript"><!--
var discount_row = <?php echo $discount_row; ?>;

function addDiscount() {
	html  = '<table class="green" id="discount_row' + discount_row + '">';
	html += '<tr>'; 
    html += '<td><?php echo $entry_customer_group; ?><br /><select name="product_discount[' + discount_row + '][customer_group_id]" style="margin-top: 3px;">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
    <?php } ?>
    html += '</select></td>';		
    html += '<td><?php echo $entry_quantity; ?><br /><input type="text" name="product_discount[' + discount_row + '][quantity]" value="" size="2" style="margin-top: 3px;" /></td>';
    html += '<td><?php echo $entry_priority; ?><br /><input type="text" name="product_discount[' + discount_row + '][priority]" value="" size="2" style="margin-top: 3px;" /></td>';
	html += '<td><?php echo $entry_price; ?><br /><input type="text" name="product_discount[' + discount_row + '][price]" value="" style="margin-top: 3px;" /></td>';
	html += '<td rowspan="2"><a onclick="$(\'#discount_row' + discount_row + '\').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>';
	html += '</tr>';
	html += '<tr>'; 
    html += '<td><?php echo $entry_date_start; ?><br /><input type="text" name="product_discount[' + discount_row + '][date_start]" value="" class="date" style="margin-top: 3px;" /></td>';
	html += '<td colspan="3"><?php echo $entry_date_end; ?><br /><input type="text" name="product_discount[' + discount_row + '][date_end]" value="" class="date" style="margin-top: 3px;" /></td>';
	html += '</tr>';	
    html += '</table>';
	
	$('#discount').append(html);
	
	$('#discount .date').datepicker({dateFormat: 'yy-mm-dd'});
	
	discount_row++;
}
//--></script>
<script type="text/javascript"><!--
var special_row = <?php echo $special_row; ?>;

function addSpecial() {
	html  = '<table class="green" id="special_row' + special_row + '">';
	html += '<tr>'; 
    html += '<td><?php echo $entry_customer_group; ?><br /><select name="product_special[' + special_row + '][customer_group_id]" style="margin-top: 3px;">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
    <?php } ?>
    html += '</select></td>';		
    html += '<td><?php echo $entry_priority; ?><br /><input type="text" name="product_special[' + special_row + '][priority]" value="" size="2" style="margin-top: 3px;" /></td>';
	html += '<td><?php echo $entry_price; ?><br /><input type="text" name="product_special[' + special_row + '][price]" value="" style="margin-top: 3px;" /></td>';
	html += '<td rowspan="2"><a onclick="$(\'#special_row' + special_row + '\').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>';
	html += '</tr>';
	html += '<tr>'; 
    html += '<td><?php echo $entry_date_start; ?><br /><input type="text" name="product_special[' + special_row + '][date_start]" value="" class="date" style="margin-top: 3px;" /></td>';
	html += '<td colspan="2"><?php echo $entry_date_end; ?><br /><input type="text" name="product_special[' + special_row + '][date_end]" value="" class="date" style="margin-top: 3px;" /></td>';
	html += '</tr>';
    html += '</table>';
	
	$('#special').append(html);
	
	$('#special .date').datepicker({dateFormat: 'yy-mm-dd'});
	
	special_row++;
}
//--></script>
<script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
    html  = '<div id="image_row' + image_row + '" class="green">';
	html += '<table width="100%">';
	html += '<tr>';
	html += '<td><img src="<?php echo $no_image; ?>" alt="" style="margin: 4px 0px; border: 1px solid #EEEEEE;" /></td>';
	html += '<td><div style="margin-bottom: 4px;"><?php echo $entry_image; ?></div><input type="file" name="product_image[' + image_row  + ']" /></td>';
	html += '<td><a onclick="$(\'#image_row' + image_row  + '\').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>';
	html += '</tr>';
	html += '</table>';
	html += '</div>';
	
	$('#images').append(html);
	
	image_row++;
}
//--></script>
<link rel="stylesheet" type="text/css" href="view/stylesheet/datepicker.css" />
<script type="text/javascript" src="view/javascript/jquery/ui/ui.core.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.min.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
<?php echo $footer; ?>