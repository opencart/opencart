<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a><a tab="#tab_data"><?php echo $tab_data; ?></a><a tab="#tab_option"><?php echo $tab_option; ?></a><a tab="#tab_discount"><?php echo $tab_discount; ?></a><a tab="#tab_image"><?php echo $tab_image; ?></a><a tab="#tab_download"><?php echo $tab_download; ?></a><a tab="#tab_category"><?php echo $tab_category; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page">
    <?php foreach ($languages as $language) { ?>
    <span class="required">*</span> <?php echo $entry_name; ?><br />
    <input type="text" name="product_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo @$product_description[$language['language_id']]['name']; ?>" />
    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
    <?php if (@$error_name[$language['language_id']]) { ?>
    <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
    <?php } ?>
    <br />
    <span class="required">*</span> <?php echo $entry_description; ?><br />
    <textarea name="product_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo @$product_description[$language['language_id']]['description']; ?></textarea>
    <?php if (@$error_description[$language['language_id']]) { ?>
    <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
    <?php } ?>
    &nbsp;<br />
    <?php } ?>
  </div>
  <div id="tab_data" class="page"><span class="required">*</span> <?php echo $entry_model; ?><br />
    <input type="text" name="model" value="<?php echo $model; ?>" />
    <br />
    <?php if ($error_model) { ?>
    <span class="error"><?php echo $error_model; ?></span>
    <?php } ?>
    <br />
    <?php echo $entry_image; ?><br />
    <div id="preview_1" class="preview"></div>
    <select name="image_id" id="image_1" class="image" onchange="$('#preview_1').load('index.php?route=catalog/image/thumb&image_id=' + this.value);">
      <?php foreach ($images as $image) { ?>
      <?php if ($image['image_id'] == $image_id) { ?>
      <option value="<?php echo $image['image_id']; ?>" selected="selected"><?php echo $image['title']; ?></option>
      <?php } else { ?>
      <option value="<?php echo $image['image_id']; ?>"><?php echo $image['title']; ?></option>
      <?php } ?>
      <?php } ?>
    </select>
    <a onclick="openMyModal('index.php?route=catalog/image/upload');" style="text-decoration: underline;"><?php echo $text_upload; ?></a><br />
    <br />
    <?php echo $entry_manufacturer; ?><br />
    <select name="manufacturer_id">
      <option value="0" selected="selected"><?php echo $text_none; ?></option>
      <?php foreach ($manufacturers as $manufacturer) { ?>
      <?php if ($manufacturer['manufacturer_id'] == $manufacturer_id) { ?>
      <option value="<?php echo $manufacturer['manufacturer_id']; ?>" selected="selected"><?php echo $manufacturer['name']; ?></option>
      <?php } else { ?>
      <option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
      <?php } ?>
      <?php } ?>
    </select>
    <br />
    <br />
    <?php echo $entry_shipping; ?><br />
    <?php if ($shipping) { ?>
    <input type="radio" name="shipping" value="1" checked="checked" />
    <?php echo $text_yes; ?>
    <input type="radio" name="shipping" value="0" />
    <?php echo $text_no; ?>
    <?php } else { ?>
    <input type="radio" name="shipping" value="1" />
    <?php echo $text_yes; ?>
    <input type="radio" name="shipping" value="0" checked="checked" />
    <?php echo $text_no; ?>
    <?php } ?>
    <br />
    <br />
    <?php echo $entry_date_available; ?><br />
    <input type="text" name="date_available" value="<?php echo $date_available; ?>" size="12" id="date" />
    <br />
    <br />
    <?php echo $entry_quantity; ?><br />
    <input type="text" name="quantity" value="<?php echo $quantity; ?>" size="2" />
    <br />
    <br />
    <?php echo $entry_stock_status; ?><br />
    <select name="stock_status_id">
      <?php foreach ($stock_statuses as $stock_status) { ?>
      <?php if ($stock_status['stock_status_id'] == $stock_status_id) { ?>
      <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
      <?php } else { ?>
      <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
      <?php } ?>
      <?php } ?>
    </select>
    <br />
    <br />
    <?php echo $entry_status; ?><br />
    <select name="status">
      <?php if ($status) { ?>
      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
      <option value="0"><?php echo $text_disabled; ?></option>
      <?php } else { ?>
      <option value="1"><?php echo $text_enabled; ?></option>
      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
      <?php } ?>
    </select>
    <br />
    <br />
    <?php echo $entry_sort_order; ?> <br />
    <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" />
    <br />
    <br />
    <?php echo $entry_tax_class; ?><br />
    <select name="tax_class_id">
      <option value="0"><?php echo $text_none; ?></option>
      <?php foreach ($tax_classes as $tax_class) { ?>
      <?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
      <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
      <?php } else { ?>
      <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
      <?php } ?>
      <?php } ?>
    </select>
    <br />
    <br />
    <?php echo $entry_price; ?><br />
    <input type="text" name="price" value="<?php echo $price; ?>" />
    <br />
    <br />
    <?php echo $entry_weight_class; ?><br />
    <select name="weight_class_id">
      <?php foreach ($weight_classes as $weight_class) { ?>
      <?php if ($weight_class['weight_class_id'] == $weight_class_id) { ?>
      <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
      <?php } else { ?>
      <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
      <?php } ?>
      <?php } ?>
    </select>
    <br />
    <br />
    <?php echo $entry_weight; ?><br />
    <input name="weight" value="<?php echo $weight; ?>" />
  </div>
  <div id="tab_option" class="page">
    <?php $i = 0; ?>
    <?php $j = 0; ?>
    <div id="option">
      <?php foreach ($product_options as $product_option) { ?>
      <div id="option<?php echo $i; ?>">
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
        <div id="option_value<?php echo $j; ?>" class="option_value">
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
      <table class="green" id="discount<?php echo $k; ?>">
        <tr>
          <td><?php echo $entry_quantity; ?><br />
            <input type="text" name="product_discount[<?php echo $k; ?>][quantity]" value="<?php echo $product_discount['quantity']; ?>" size="2" /></td>
          <td><?php echo $entry_discount; ?><br />
            <input type="text" name="product_discount[<?php echo $k; ?>][discount]" value="<?php echo $product_discount['discount']; ?>" /></td>
          <td><a onclick="$('#discount<?php echo $k; ?>').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>
        </tr>
      </table>
      <?php $k++; ?>
      <?php } ?>
    </div>
    <a onclick="addDiscount();" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_add_discount; ?></span><span class="button_right"></span></a> </div>
  <div id="tab_image" class="page">
    <table width="100%">
      <tr>
        <td width="45%" valign="top"><?php echo $entry_images; ?>
          <div id="preview_2" class="preview"></div>
          <select id="image_2" class="image" onchange="$('#preview_2').load('index.php?route=catalog/image/thumb&image_id=' + this.value);">
            <?php foreach ($images as $image) { ?>
            <?php if ($image['image_id'] == $image_id) { ?>
            <option value="<?php echo $image['image_id']; ?>" selected="selected"><?php echo $image['title']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $image['image_id']; ?>"><?php echo $image['title']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
          <a onclick="openMyModal('index.php?route=catalog/image/upload');" style="text-decoration: underline;"><?php echo $text_upload; ?></a><br />
          <br />
          <a onclick="addImage();" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_add_image; ?></span><span class="button_right"></span></a></td>
        <td id="images" width="55%" valign="top"><?php $k = 0; ?>
          <?php foreach ($product_images as $product_image) { ?>
          <div id="image<?php echo $product_image; ?>" style="float: left; text-align: center; background: #E4F1C9; padding: 5px; margin-right: 10px; margin-bottom: 10px;">
            <div id="preview<?php echo $product_image; ?>" class="preview" style="margin-bottom: 5px;"></div>
            <a onclick="$('#image<?php echo $product_image; ?>').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a>
            <input type="hidden" name="product_image[]" value="<?php echo $product_image; ?>" />
          </div>
          <?php $k++; ?>
          <?php } ?></td>
      </tr>
    </table>
  </div>
  <div id="tab_download" class="page"><?php echo $entry_download; ?><br />
    <div class="scrollbox">
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
    </div>
  </div>
  <div id="tab_category" class="page"><?php echo $entry_category; ?><br />
    <div class="scrollbox">
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
    </div>
  </div>
</form>
<script type="text/javascript" src="view/javascript/fckeditor/fckeditor.js"></script>
<script type="text/javascript"><!--
var sBasePath = document.location.href.replace(/index\.php.*/, 'view/javascript/fckeditor/');
<?php foreach ($languages as $language) { ?>
var oFCKeditor<?php echo $language['language_id']; ?>          = new FCKeditor('description<?php echo $language['language_id']; ?>');
	oFCKeditor<?php echo $language['language_id']; ?>.BasePath = sBasePath;
	oFCKeditor<?php echo $language['language_id']; ?>.Value	   = document.getElementById('description<?php echo $language['language_id']; ?>').value;
	oFCKeditor<?php echo $language['language_id']; ?>.Width    = '100%';
	oFCKeditor<?php echo $language['language_id']; ?>.Height   = '300';
	oFCKeditor<?php echo $language['language_id']; ?>.ReplaceTextarea();
<?php } ?>
//--></script>
<script type="text/javascript"><!--
var option_row = <?php echo $i; ?>;

function addOption() {	
	html  = '<div id="option' + option_row + '" style="display: none;">';
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
	
	$('#option' + option_row).slideDown('slow');
	
	option_row++;
}

function removeOption(option_id) {
	$('#option' + option_id).slideUp('slow', function() {
		$('#option' + option_id).remove();											  
	});
}

var option_value_row = <?php echo $j; ?>;

function addOptionValue(option_id) {
	html  = '<div id="option_value' + option_value_row + '" class="option_value" style="display: none;">';
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
	
	$('#option_value' + option_value_row).slideDown('slow');
	
	option_value_row++;
}

function removeOptionValue(option_value_id) {
	$('#option_value' + option_value_id).slideUp('slow', function() {
		$('#option_value' + option_value_id).remove();														  
	});
}
//--></script>
<script type="text/javascript"><!--
var discount_row = <?php echo $k ?>;

function addDiscount() {
	row  = '<table class="green" id="discount' + discount_row + '">';
	row += '<tr>';   
    row += '<td><?php echo $entry_quantity; ?><br /><input type="text" name="product_discount[' + discount_row + '][quantity]" value="" size="2" /></td>';
    row += '<td><?php echo $entry_discount; ?><br /><input type="text" name="product_discount[' + discount_row + '][discount]" value="" /></td>';
    row += '<td><a onclick="$(\'#discount' + discount_row + '\').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>';
	row += '</tr>';
    row += '</table>';
	
	$('#discount').append(row);
	
	discount_row++;
}
//--></script>
<script type="text/javascript"><!--
function addImage() {
	image_id = $('#image_2').attr('value');
	
	$('#image' + image_id).remove();
	
    row  = '<div id="image' + image_id + '" style="float: left; text-align: center; background: #E4F1C9; padding: 5px; margin-right: 10px; margin-bottom: 10px;">';
	row += '<div id="preview' + image_id + '" class="preview"></div>';
	row += '<a onclick="$(\'#image' + image_id + '\').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a>';
    row += '<input type="hidden" name="product_image[]" value="' + image_id + '" />';
	row += '</div>';
	
	$('#images').append(row);
	
	$('#preview' + image_id).load('index.php?route=catalog/image/thumb&image_id=' + image_id);
}
//--></script>
<script type="text/javascript"><!--
$('#preview_1').load('index.php?route=catalog/image/thumb&image_id=' + $('#image_1').attr('value'));
$('#preview_2').load('index.php?route=catalog/image/thumb&image_id=' + $('#image_2').attr('value'));
<?php foreach ($product_images as $product_image) { ?>
$('#preview<?php echo $product_image; ?>').load('index.php?route=catalog/image/thumb&image_id=<?php echo $product_image; ?>');
<?php } ?>
//--></script>
<script type="text/javascript" src="view/javascript/jquery/modal/modal.js"></script>
<link rel="stylesheet" type="text/css" href="view/javascript/jquery/modal/modal.css" />
<script type="text/javascript"><!--
function openMyModal(source) {   
    modalWindow.windowId = 'myModal';   
    modalWindow.width    = 450;   
    modalWindow.height   = 350;   
    modalWindow.content  = '<iframe width="450" height="350" frameborder="0" scrolling="no" allowtransparency="true" src="' + source + '"></iframe>';   
    modalWindow.open();   
};  
//--></script>
<link rel="stylesheet" type="text/css" href="view/stylesheet/datepicker.css" />
<script type="text/javascript" src="view/javascript/jquery/ui/ui.core.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.min.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
