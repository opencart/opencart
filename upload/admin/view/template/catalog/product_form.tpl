<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a><a tab="#tab_data"><?php echo $tab_data; ?></a><a tab="#tab_option"><?php echo $tab_option; ?></a><a tab="#tab_discount"><?php echo $tab_discount; ?></a><a tab="#tab_image"><?php echo $tab_image; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page">
    <table class="form">
      <?php foreach ($languages as $language) { ?>
      <tr>
        <td width="180"><span class="required">*</span> <?php echo $entry_name; ?></td>
        <td><input type="text" name="product_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo @$product_description[$language['language_id']]['name']; ?>" />
          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
          <?php if (@$error_name[$language['language_id']]) { ?>
          <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_meta_description; ?></td>
        <td><textarea name="product_description[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="5"><?php echo @$product_description[$language['language_id']]['meta_description']; ?></textarea>
          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" /><br />
          <?php if (@$error_meta_description[$language['language_id']]) { ?>
          <span class="error"><?php echo $error_meta_description[$language['language_id']]; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_description; ?></td>
        <td><textarea name="product_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo @$product_description[$language['language_id']]['description']; ?></textarea>
          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" />
          <?php if (@$error_description[$language['language_id']]) { ?>
          <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
          <?php } ?></td>
      </tr>
      <?php } ?>
    </table>
  </div>
  <div id="tab_data" class="page">
    <table class="form">
      <tr>
        <td width="25%"><span class="required">*</span> <?php echo $entry_model; ?></td>
        <td><input type="text" name="model" value="<?php echo $model; ?>" />
          <br />
          <?php if ($error_model) { ?>
          <span class="error"><?php echo $error_model; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_image; ?></td>
        <td><input type="file" id="upload" />
          <input type="hidden" name="image" value="<?php echo $image; ?>" id="image" /></td>
      </tr>
      <tr>
        <td></td>
        <td><img src="<?php echo $preview; ?>" alt="" id="preview" style="margin: 4px 0px; border: 1px solid #EEEEEE;" /></td>
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
        <td><input type="text" name="date_available" value="<?php echo $date_available; ?>" size="12" id="date" /></td>
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
        <td><?php echo $entry_sort_order; ?></td>
        <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
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
        <td><?php echo $entry_weight; ?></td>
        <td><input name="weight" value="<?php echo $weight; ?>" /></td>
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
    </table>
  </div>
  <div id="tab_option" class="page">
    <?php $i = 0; ?>
    <?php $j = 0; ?>
    <div id="option">
      <?php foreach ($product_options as $product_option) { ?>
      <div id="option_row<?php echo $i; ?>">
        <div class="option">
          <table>
            <tr>
              <td colspan="3"><?php echo $entry_option; ?><br />
                <?php foreach ($languages as $language) { ?>
                <input type="text" name="product_option[<?php echo $i; ?>][language][<?php echo $language['language_id']; ?>][name]" value="<?php echo $product_option['language'][$language['language_id']]['name']; ?>" />
                <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                <?php } ?></td>
              <td valign="top"><?php echo $entry_sort_order; ?><br />
                <input type="text" name="product_option[<?php echo $i; ?>][sort_order]" value="<?php echo $product_option['sort_order']; ?>" size="5" /></td>
              <td align="right"><a onclick="removeOption('<?php echo $i; ?>');" class="remove"><?php echo $button_remove; ?></a></td>
            </tr>
          </table>
        </div>
        <?php foreach ($product_option['product_option_value'] as $product_option_value) { ?>
        <div id="option_value_row<?php echo $j; ?>" class="option_value">
          <table>
            <tr>
              <td><?php echo $entry_option_value; ?><br />
                <?php foreach ($languages as $language) { ?>
                <input type="text" name="product_option[<?php echo $i; ?>][product_option_value][<?php echo $j; ?>][language][<?php echo $language['language_id']; ?>][name]" value="<?php echo $product_option_value['language'][$language['language_id']]['name']; ?>" />
                <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                <?php } ?></td>
              <td><?php echo $entry_price; ?><br />
                <input type="text" name="product_option[<?php echo $i; ?>][product_option_value][<?php echo $j; ?>][price]" value="<?php echo $product_option_value['price']; ?>" /></td>
              <td><?php echo $entry_prefix; ?><br />
                <select name="product_option[<?php echo $i; ?>][product_option_value][<?php echo $j; ?>][prefix]">
                  <?php  if ($product_option_value['prefix'] != '-') { ?>
                  <option value="+" selected="selected"><?php echo $text_plus; ?></option>
                  <option value="-"><?php echo $text_minus; ?></option>
                  <?php } else { ?>
                  <option value="+"><?php echo $text_plus; ?></option>
                  <option value="-" selected="selected"><?php echo $text_minus; ?></option>
                  <?php } ?>
                </select></td>
              <td><?php echo $entry_sort_order; ?><br />
                <input type="text" name="product_option[<?php echo $i; ?>][product_option_value][<?php echo $j; ?>][sort_order]" value="<?php echo $product_option_value['sort_order']; ?>" size="5" /></td>
              <td align="right"><a onclick="removeOptionValue('<?php echo $j; ?>');" class="remove"><?php echo $button_remove; ?></a></td>
            </tr>
          </table>
        </div>
        <?php $j++; ?>
        <?php } ?>
        <div class="option_add" id="add_option_value<?php echo $i; ?>"><a onclick="addOptionValue('<?php echo $i; ?>')" class="add"><?php echo $button_add_option_value; ?></a></div>
      </div>
      <?php $i++; ?>
      <?php } ?>
      <div class="option_add" id="add_option"><a onclick="addOption();" class="add"><?php echo $button_add_option; ?></a></div>
    </div>
  </div>
  <div id="tab_discount" class="page">
    <div id="discount">
      <?php $k = 0; ?>
      <?php foreach ($product_discounts as $product_discount) { ?>
      <table width="100%" class="green" id="discount_row<?php echo $k; ?>">
        <tr>
          <td><?php echo $entry_quantity; ?><br />
            <input type="text" name="product_discount[<?php echo $k; ?>][quantity]" value="<?php echo $product_discount['quantity']; ?>" size="2" /></td>
          <td><?php echo $entry_discount; ?><br />
            <input type="text" name="product_discount[<?php echo $k; ?>][discount]" value="<?php echo $product_discount['discount']; ?>" /></td>
          <td><a onclick="$('#discount_row<?php echo $k; ?>').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>
        </tr>
      </table>
      <?php $k++; ?>
      <?php } ?>
    </div>
    <a onclick="addDiscount();" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_add_discount; ?></span><span class="button_right"></span></a></div>
  <div id="tab_image" class="page">
    <div id="images">
      <?php $l = 0; ?>
      <?php foreach ($product_images as $product_image) { ?>
      <table width="100%" id="image_row<?php echo $l; ?>" class="green">
        <tr>
          <td><img src="<?php echo $product_image['image']; ?>" alt="" id="preview<?php echo $l; ?>" /></td>
          <td><div style="margin-bottom: 4px;"><?php echo $entry_image; ?></div>
            <input type="file" id="upload<?php echo $l; ?>" />
            <input type="hidden" name="product_image[]" value="<?php echo $product_image['file']; ?>" id="image<?php echo $l; ?>" /></td>
          <td><a onclick="$('#image_row<?php echo $l; ?>').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>
        </tr>
      </table>
      <?php $l++; ?>
      <?php } ?>
    </div>
    <a onclick="addImage();" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_add_image; ?></span><span class="button_right"></span></a> </div>
</form>
<script type="text/javascript" src="view/javascript/fckeditor/fckeditor.js"></script>
<script type="text/javascript"><!--
var sBasePath = document.location.href.replace(/index\.php.*/, 'view/javascript/fckeditor/');
<?php foreach ($languages as $language) { ?>
var oFCKeditor<?php echo $language['language_id']; ?>          = new FCKeditor('description<?php echo $language['language_id']; ?>');
	oFCKeditor<?php echo $language['language_id']; ?>.BasePath = sBasePath;
	oFCKeditor<?php echo $language['language_id']; ?>.Value	   = document.getElementById('description<?php echo $language['language_id']; ?>').value;
	oFCKeditor<?php echo $language['language_id']; ?>.Width    = '520';
	oFCKeditor<?php echo $language['language_id']; ?>.Height   = '300';
	oFCKeditor<?php echo $language['language_id']; ?>.ReplaceTextarea();
<?php } ?>
//--></script>
<script type="text/javascript"><!--
var option_row = <?php echo $i; ?>;

function addOption() {	
	html  = '<div id="option_row' + option_row + '" style="display: none;">';
	html += '<div class="option">';
	html += '<table>';
	html += '<tr>';
	html += '<td colspan="3"><?php echo $entry_option; ?><br />';
	<?php foreach ($languages as $language) { ?>
	html += '<input type="text" name="product_option[' + option_row + '][language][<?php echo $language['language_id']; ?>][name]" value="" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
	<?php } ?>
	html += '</td>';
	html += '<td  valign="top"><?php echo $entry_sort_order; ?><br /><input type="text" name="product_option[' + option_row + '][sort_order]" value="" size="5" /></td>';	
	html += '<td align="right"><a onclick="removeOption(\'' + option_row + '\')" class="remove"><?php echo $button_remove; ?></a></td>';
	html += '</tr>';
	html += '</table>';
	html += '</div>';
	html += '<div class="option_add" id="add_option_value' + option_row + '"><a onclick="addOptionValue(\'' + option_row + '\')" class="add"><?php echo $button_add_option_value; ?></a></div>';
	html += '</div>';

	$('#add_option').before(html);
	
	$('#option_row' + option_row).slideDown('slow');
	
	option_row++;
}

function removeOption(option_id) {
	$('#option_row' + option_id).slideUp('slow', function() {
		$('#option_row' + option_id).remove();											  
	});
}

var option_value_row = <?php echo $j; ?>;

function addOptionValue(option_id) {
	html  = '<div id="option_value_row' + option_value_row + '" class="option_value" style="display: none;">';
	html += '<table>';
	html += '<tr>';
	html += '<td><?php echo $entry_option_value; ?><br />';
	<?php foreach ($languages as $language) { ?>
	html += '<input type="text" name="product_option[' + option_id + '][product_option_value][' + option_value_row + '][language][<?php echo $language['language_id']; ?>][name]" value="" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
	<?php } ?>
	html += '</td>';
	html += '<td><?php echo $entry_price; ?><br /><input type="text" name="product_option[' + option_id + '][product_option_value][' + option_value_row + '][price]" value="" /></td>';
	html += '<td><?php echo $entry_prefix; ?><br /><select name="product_option[' + option_id + '][product_option_value][' + option_value_row + '][prefix]">';
    html += '<option value="+"><?php echo $text_plus; ?></option>';
    html += '<option value="-"><?php echo $text_minus; ?></option>';
    html += '</select></td>';
	html += '<td><?php echo $entry_sort_order; ?><br /><input type="text" name="product_option[' + option_id + '][product_option_value][' + option_value_row + '][sort_order]" value="" size="5" /></td>';
	html += '<td align="right"><a onclick="removeOptionValue(\'' + option_value_row + '\')" class="remove"><?php echo $button_remove; ?></a></td>';
	html += '</tr>';
	html += '</table>';
	html += '<div>';

	$('#add_option_value' + option_id).before(html);
	
	$('#option_value_row' + option_value_row).slideDown('slow');
	
	option_value_row++;
}

function removeOptionValue(option_value_id) {
	$('#option_value_row' + option_value_id).slideUp('slow', function() {
		$('#option_value_row' + option_value_id).remove();														  
	});
}
//--></script>
<script type="text/javascript"><!--
var discount_row = <?php echo $k ?>;

function addDiscount() {
	html  = '<table class="green" id="discount_row' + discount_row + '">';
	html += '<tr>';   
    html += '<td><?php echo $entry_quantity; ?><br /><input type="text" name="product_discount[' + discount_row + '][quantity]" value="" size="2" /></td>';
    html += '<td><?php echo $entry_discount; ?><br /><input type="text" name="product_discount[' + discount_row + '][discount]" value="" /></td>';
    html += '<td><a onclick="$(\'#discount_row' + discount_row + '\').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>';
	html += '</tr>';
    html += '</table>';
	
	$('#discount').append(html);
	
	discount_row++;
}
//--></script>
<script type="text/javascript"><!--
var image_row = <?php echo $l; ?>;

function addImage() {
    html  = '<div id="image_row' + image_row + '" class="green">';
	html += '<table width="100%">';
	html += '<tr>';
	html += '<td><img src="<?php echo $no_image; ?>" alt="" id="preview' + image_row + '" style="margin: 4px 0px; border: 1px solid #EEEEEE;" /></td>';
	html += '<td><div style="margin-bottom: 4px;"><?php echo $entry_image; ?></div><input type="file" id="upload' + image_row + '" /><input type="hidden" name="product_image[]" value="" id="image' + image_row + '" /></td>';
	html += '<td><a onclick="$(\'#image_row' + image_row  + '\').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>';
	html += '</tr>';
	html += '</table>';
	html += '</div>';
	
	$('#images').append(html);
	
	setUploader('#upload' + image_row, '#preview' + image_row, '#image' + image_row);
	
	image_row++;
}
//--></script>
<script type="text/javascript" src="view/javascript/jquery/jquery.ocupload-1.1.2.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() { 
	setUploader('#upload', '#preview', '#image');
	
	$.tabs('.tabs a'); 
});	

function setUploader(upload, preview, image) {
	$(upload).upload({
		name:    'image',
		method:  'post',
		enctype: 'multipart/form-data',
		action:  'index.php?route=catalog/image',
		autoSubmit: false,
		onSubmit: function() {
			$(upload).after('<img src="view/image/loading.gif" id="loading" />');
		},
		onComplete: function(data) {
			var json = eval('(' + data + ')');

			if (json.error) {
				alert(json.error);
			} else {
				$(preview).attr('src', json.src);

				$(image).attr('value', json.file);
			}
			$('#loading').remove();
		},
		onSelect: function() {}
	});	
}
//--></script>
<link rel="stylesheet" type="text/css" href="view/stylesheet/datepicker.css" />
<script type="text/javascript" src="view/javascript/jquery/ui/ui.core.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.min.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
