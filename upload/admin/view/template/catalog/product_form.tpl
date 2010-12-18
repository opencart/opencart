<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/product.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <div id="tabs" class="htabs"><a tab="#tab_general"><?php echo $tab_general; ?></a><a tab="#tab_data"><?php echo $tab_data; ?></a><a tab="#tab_links"><?php echo $tab_links; ?></a><a tab="#tab_option"><?php echo $tab_option; ?></a><a tab="#tab_discount"><?php echo $tab_discount; ?></a><a tab="#tab_special"><?php echo $tab_special; ?></a><a tab="#tab_image"><?php echo $tab_image; ?></a></div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div id="tab_general">
        <div id="languages" class="htabs">
          <?php foreach ($languages as $language) { ?>
          <a tab="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
		  <?php } ?>
        </div> 
        <?php foreach ($languages as $language) { ?>
        <div id="language<?php echo $language['language_id']; ?>">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $entry_name; ?></td>
              <td><input type="text" name="product_description[<?php echo $language['language_id']; ?>][name]" size="100" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name'] : ''; ?>" />
                <?php if (isset($error_name[$language['language_id']])) { ?>
                <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_meta_keywords; ?></td>
              <td><textarea name="product_description[<?php echo $language['language_id']; ?>][meta_keywords]" cols="40" rows="5"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_keywords'] : ''; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_meta_description; ?></td>
              <td><textarea name="product_description[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="5"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_description'] : ''; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_description; ?></td>
              <td><textarea name="product_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['description'] : ''; ?></textarea></td>
            </tr>
			<tr>
              <td><?php echo $entry_tags; ?></td>
              <td><input type="text" name="product_tags[<?php echo $language['language_id']; ?>]" value="<?php echo isset($product_tags[$language['language_id']]) ? $product_tags[$language['language_id']] : ''; ?>" size="80"/></td>
            </tr>
          </table>
        </div>
		<?php } ?>
      </div>
      <div id="tab_data">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_model; ?></td>
            <td><input type="text" name="model" value="<?php echo $model; ?>" />
              <?php if ($error_model) { ?>
              <span class="error"><?php echo $error_model; ?></span>
              <?php } ?></td>
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
            <td><?php echo $entry_price; ?></td>
            <td><input type="text" name="price" value="<?php echo $price; ?>" /></td>
          </tr>
		  <tr>
            <td><?php echo $entry_cost; ?></td>
            <td><input type="text" name="cost" value="<?php echo $cost; ?>" /></td>
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
            <td><?php echo $entry_quantity; ?></td>
            <td><input type="text" name="quantity" value="<?php echo $quantity; ?>" size="2" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_minimum; ?></td>
            <td><input type="text" name="minimum" value="<?php echo $minimum; ?>" size="2" /></td>
          </tr>
		  <tr>
		    <td><?php echo $entry_subtract; ?></td>
		    <td><select name="subtract">
			  <?php if ($subtract) { ?>
			  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
			  <option value="0"><?php echo $text_no; ?></option>
			  <?php } else { ?>
			  <option value="1"><?php echo $text_yes; ?></option>
			  <option value="0" selected="selected"><?php echo $text_no; ?></option>
			  <?php } ?>
			</select></td>
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
            <td><input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
              <img src="<?php echo $preview; ?>" alt="" id="preview" class="image" onclick="image_upload('image', 'preview');" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_date_available; ?></td>
            <td><input type="text" name="date_available" value="<?php echo $date_available; ?>" size="12" class="date" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="2" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_dimension; ?></td>
            <td><input type="text" name="length" value="<?php echo $length; ?>" size="4" />
              <input type="text" name="width" value="<?php echo $width; ?>" size="4" />
              <input type="text" name="height" value="<?php echo $height; ?>" size="4" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_length; ?></td>
            <td><select name="length_class_id">
                <?php foreach ($length_classes as $length_class) { ?>
                <?php if ($length_class['length_class_id'] == $length_class_id) { ?>
                <option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
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
        </table>
      </div>
      <div id="tab_links">
        <table class="form">
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
            <td><?php echo $entry_store; ?></td>
            <td><div class="scrollbox">
                <?php $class = 'even'; ?>
                <div class="<?php echo $class; ?>">
                  <?php if (in_array(0, $product_store)) { ?>
                  <input type="checkbox" name="product_store[]" value="0" checked="checked" />
                  <?php echo $text_default; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="product_store[]" value="0" />
                  <?php echo $text_default; ?>
                  <?php } ?>
                </div>
                <?php foreach ($stores as $store) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
                  <?php if (in_array($store['store_id'], $product_store)) { ?>
                  <input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                  <?php echo $store['name']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" />
                  <?php echo $store['name']; ?>
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
                  <td style="padding: 0;"><select multiple="multiple" id="product" size="10" style="width: 350px;">
                    </select></td>
                  <td style="vertical-align: middle;"><input type="button" value="--&gt;" onclick="addRelated();" />
                    <br />
                    <input type="button" value="&lt;--" onclick="removeRelated();" /></td>
                  <td style="padding: 0;"><select multiple="multiple" id="related" size="10" style="width: 350px;">
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
      <div id="tab_option">
        <div style="width: 100%; display: inline-block; padding-bottom: 5px;">
          <div style="width: 200px; height: 300px; float: left;">
            <select id="option" size="20" style="width: 100%;">
              <?php $option_row = 0; ?>
              <?php $option_value_row = 0; ?>
              <?php foreach ($product_options as $product_option) { ?>
              <option value="option<?php echo $option_row; ?>"><?php echo $product_option['language'][$language_id]['name']; ?></option>
              <?php if ($product_option['product_option_value']) { ?>
              <?php foreach ($product_option['product_option_value'] as $product_option_value) { ?>
              <?php foreach ($languages as $language) { ?>
              <?php if ($language['language_id'] == $language_id) { ?>
              <option value="option<?php echo $option_row; ?>_<?php echo $option_value_row; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $product_option_value['language'][$language['language_id']]['name']; ?></option>
              <?php $option_value_row++; ?>
              <?php } ?>
              <?php } ?>
              <?php } ?>
              <?php } ?>
              <?php $option_row++; ?>
              <?php } ?>
            </select>
          </div>
          <div id="options" style="margin-left: 215px;">
            <div style="border-bottom: 1px solid #DDDDDD; text-align: right; padding-bottom: 10px; margin-bottom: 15px;"><a onclick="addOption();" class="button"><span><?php echo $button_add_option; ?></span></a></div>
            <?php $option_row = 0; ?>
            <?php $option_value_row = 0; ?>
            <?php foreach ($product_options as $product_option) { ?>
            <div id="option<?php echo $option_row; ?>" class="option">
              <table class="form">
                <tr>
                  <td><?php echo $entry_option; ?></td>
                  <td><?php foreach ($languages as $language) { ?>
					<?php if ($language['language_id'] == $language_id) { ?>
                    <input type="text" name="product_option[<?php echo $option_row; ?>][language][<?php echo $language['language_id']; ?>][name]" value="<?php echo $product_option['language'][$language['language_id']]['name']; ?>" onkeyup="$('#option option[value=\'option<?php echo $option_row; ?>\']').text(this.value);" />
                    <?php } else { ?>
                    <input type="text" name="product_option[<?php echo $option_row; ?>][language][<?php echo $language['language_id']; ?>][name]" value="<?php echo $product_option['language'][$language['language_id']]['name']; ?>" />
                    <?php } ?>
                    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
					<?php } ?></td>
                </tr>
                <tr>
                  <td><?php echo $entry_sort_order; ?></td>
                  <td><input type="text" name="product_option[<?php echo $option_row; ?>][sort_order]" value="<?php echo $product_option['sort_order']; ?>" size="2" /></td>
                </tr>
                <tr>
                  <td colspan="2"><a onclick="addOptionValue('<?php echo $option_row; ?>');" class="button"><span><?php echo $button_add_option_value; ?></span></a> <a onclick="removeOption('<?php echo $option_row; ?>');" class="button"><span><?php echo $button_remove; ?></span></a></td>
                </tr>
              </table>
            </div>
            <?php if ($product_option['product_option_value']) { ?>
            <?php foreach ($product_option['product_option_value'] as $product_option_value) { ?>
            <div id="option<?php echo $option_row; ?>_<?php echo $option_value_row; ?>" class="option">
              <table class="form">
                <tr>
                  <td><?php echo $entry_option_value; ?></td>
                  <td><?php foreach ($languages as $language) { ?>
                  <?php if ($language['language_id'] == $language_id) { ?>
                    <input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][language][<?php echo $language['language_id']; ?>][name]" value="<?php echo $product_option_value['language'][$language['language_id']]['name']; ?>" onkeyup="$('#option option[value=\'option<?php echo $option_row; ?>_<?php echo $option_value_row; ?>\']').text('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + this.value);" />
                    <?php } else { ?>
                    <input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][language][<?php echo $language['language_id']; ?>][name]" value="<?php echo $product_option_value['language'][$language['language_id']]['name']; ?>" />
                    <?php } ?>
                    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
					<?php } ?></td>
                </tr>
                <tr>
                  <td><?php echo $entry_quantity; ?></td>
                  <td><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][quantity]" value="<?php echo $product_option_value['quantity']; ?>" size="2" /></td>
                </tr>
                <tr>
                  <td><?php echo $entry_subtract; ?></td>
                  <td><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][subtract]">
                      <?php if ($product_option_value['subtract']) { ?>
                      <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                      <option value="0"><?php echo $text_no; ?></option>
                      <?php } else { ?>
                      <option value="1"><?php echo $text_yes; ?></option>
                      <option value="0" selected="selected"><?php echo $text_no; ?></option>
                      <?php } ?>
                    </select></td>
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
                  <td><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][sort_order]" value="<?php echo $product_option_value['sort_order']; ?>" size="2" /></td>
                </tr>
                <tr>
                  <td colspan="2"><a onclick="removeOptionValue('<?php echo $option_row; ?>_<?php echo $option_value_row; ?>');" class="button"><span><?php echo $button_remove; ?></span></a></td>
                </tr>
              </table>
            </div>
            <?php $option_value_row++; ?>
            <?php } ?>
            <?php } ?>
            <?php $option_row++; ?>
            <?php } ?>
          </div>
        </div>
      </div>
      <div id="tab_discount">
        <table id="discount" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_customer_group; ?></td>
              <td class="left"><?php echo $entry_quantity; ?></td>
              <td class="left"><?php echo $entry_priority; ?></td>
              <td class="left"><?php echo $entry_price; ?></td>
              <td class="left"><?php echo $entry_date_start; ?></td>
              <td class="left"><?php echo $entry_date_end; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $discount_row = 0; ?>
          <?php foreach ($product_discounts as $product_discount) { ?>
          <tbody id="discount_row<?php echo $discount_row; ?>">
            <tr>
              <td class="left"><select name="product_discount[<?php echo $discount_row; ?>][customer_group_id]">
                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <?php if ($customer_group['customer_group_id'] == $product_discount['customer_group_id']) { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td class="left"><input type="text" name="product_discount[<?php echo $discount_row; ?>][quantity]" value="<?php echo $product_discount['quantity']; ?>" size="2" /></td>
              <td class="left"><input type="text" name="product_discount[<?php echo $discount_row; ?>][priority]" value="<?php echo $product_discount['priority']; ?>" size="2" /></td>
              <td class="left"><input type="text" name="product_discount[<?php echo $discount_row; ?>][price]" value="<?php echo $product_discount['price']; ?>" /></td>
              <td class="left"><input type="text" name="product_discount[<?php echo $discount_row; ?>][date_start]" value="<?php echo $product_discount['date_start']; ?>" class="date" /></td>
              <td class="left"><input type="text" name="product_discount[<?php echo $discount_row; ?>][date_end]" value="<?php echo $product_discount['date_end']; ?>" class="date" /></td>
              <td class="left"><a onclick="$('#discount_row<?php echo $discount_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
            </tr>
          </tbody>
          <?php $discount_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="6"></td>
              <td class="left"><a onclick="addDiscount();" class="button"><span><?php echo $button_add_discount; ?></span></a></td>
            </tr>
          </tfoot>
        </table>
      </div>
      <div id="tab_special">
        <table id="special" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_customer_group; ?></td>
              <td class="left"><?php echo $entry_priority; ?></td>
              <td class="left"><?php echo $entry_price; ?></td>
              <td class="left"><?php echo $entry_date_start; ?></td>
              <td class="left"><?php echo $entry_date_end; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $special_row = 0; ?>
          <?php foreach ($product_specials as $product_special) { ?>
          <tbody id="special_row<?php echo $special_row; ?>">
            <tr>
              <td class="left"><select name="product_special[<?php echo $special_row; ?>][customer_group_id]">
                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <?php if ($customer_group['customer_group_id'] == $product_special['customer_group_id']) { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td class="left"><input type="text" name="product_special[<?php echo $special_row; ?>][priority]" value="<?php echo $product_special['priority']; ?>" size="2" /></td>
              <td class="left"><input type="text" name="product_special[<?php echo $special_row; ?>][price]" value="<?php echo $product_special['price']; ?>" /></td>
              <td class="left"><input type="text" name="product_special[<?php echo $special_row; ?>][date_start]" value="<?php echo $product_special['date_start']; ?>" class="date" /></td>
              <td class="left"><input type="text" name="product_special[<?php echo $special_row; ?>][date_end]" value="<?php echo $product_special['date_end']; ?>" class="date" /></td>
              <td class="left"><a onclick="$('#special_row<?php echo $special_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
            </tr>
          </tbody>
          <?php $special_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="5"></td>
              <td class="left"><a onclick="addSpecial();" class="button"><span><?php echo $button_add_special; ?></span></a></td>
            </tr>
          </tfoot>
        </table>
      </div>
      <div id="tab_image">
        <table id="images" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_image; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $image_row = 0; ?>
          <?php foreach ($product_images as $product_image) { ?>
          <tbody id="image_row<?php echo $image_row; ?>">
            <tr>
              <td class="left"><input type="hidden" name="product_image[<?php echo $image_row; ?>]" value="<?php echo $product_image['file']; ?>" id="image<?php echo $image_row; ?>"  />
                <img src="<?php echo $product_image['preview']; ?>" alt="" id="preview<?php echo $image_row; ?>" class="image" onclick="image_upload('image<?php echo $image_row; ?>', 'preview<?php echo $image_row; ?>');" /></td>
              <td class="left"><a onclick="$('#image_row<?php echo $image_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
            </tr>
          </tbody>
          <?php $image_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td></td>
              <td class="left"><a onclick="addImage();" class="button"><span><?php echo $button_add_image; ?></span></a></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
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

	<?php if (isset($this->request->get['product_id'])) {?>
	var product_id = '<?php echo $this->request->get['product_id'] ?>';
	<?php } else { ?>
	var product_id = 0;
	<?php } ?>

	$.ajax({
		url: 'index.php?route=catalog/product/category&token=<?php echo $token; ?>&category_id=' + $('#category').attr('value'),
		dataType: 'json',
		success: function(data) {
			for (i = 0; i < data.length; i++) {
				if (data[i]['product_id'] == product_id) { continue; }
	 			$('#product').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + ' (' + data[i]['model'] + ') </option>');
			}
		}
	});
}

function getRelated() {
	$('#related option').remove();
	
	$.ajax({
		url: 'index.php?route=catalog/product/related&token=<?php echo $token; ?>',
		type: 'POST',
		dataType: 'json',
		data: $('#product_related input'),
		success: function(data) {
			$('#product_related input').remove();
			
			for (i = 0; i < data.length; i++) {
	 			$('#related').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + ' (' + data[i]['model'] + ') </option>');
				
				$('#product_related').append('<input type="hidden" name="product_related[]" value="' + data[i]['product_id'] + '" />');
			} 
		}
	});
}

getProducts();
getRelated();
//--></script>
<script type="text/javascript"><!--
$('#option').bind('change', function() {
	$('.option').hide();
	
	$('#' + $('#option option:selected').attr('value')).show();
});

$('#option option:first').attr('selected', 'selected');

$('#option').trigger('change');
//--></script>
<script type="text/javascript"><!--							 
var option_row = <?php echo $option_row; ?>;

function addOption() {	
	html  = '<div id="option' + option_row + '" class="option">';
	html += '<table class="form">';
	html += '<tr>';
	html += '<td><?php echo $entry_option; ?></td>';
	html += '<td>';
	<?php foreach ($languages as $language) { ?>
	<?php if ($language['language_id'] == $language_id) { ?>
	html += '<input type="text" name="product_option[' + option_row + '][language][<?php echo $language['language_id']; ?>][name]" value="Option ' + option_row + '" onkeyup="$(\'#option option[value=\\\'option' + option_row + '\\\']\').text(this.value);" />&nbsp;<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
	<?php } else { ?>
	html += '<input type="text" name="product_option[' + option_row + '][language][<?php echo $language['language_id']; ?>][name]" value="Option ' + option_row + '" />&nbsp;<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
	<?php } ?>
	<?php } ?>
	html += '</td>';
	html += '</tr>';
	html += '<tr>';
	html += '<td><?php echo $entry_sort_order; ?></td>';
	html += '<td><input type="text" name="product_option[' + option_row + '][sort_order]" value="" size="2" /></td>';
	html += '</tr>';
	html += '<tr>';
	html += '<td colspan="2"><a onclick="addOptionValue(\'' + option_row + '\');" class="button"><span><?php echo $button_add_option_value; ?></span></a> <a onclick="removeOption(\'' + option_row + '\');" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '</tr>';
	html += '</table>';
	html += '</div>';
		 
	$('#options').append(html);
	
	$('#option').append('<option value="option' + option_row + '"><?php echo $text_option; ?> ' + option_row + '</option>');
	$('#option option[value=\'option' + option_row + '\']').attr('selected', 'selected');
	$('#option').trigger('change');

	option_row++;
}

function removeOption(option_row) {
	$('#option option[value=\'option' + option_row + '\']').remove();
	$('#option option[value^=\'option' + option_row + '_\']').remove();
	
	$('#options div[id=\'option' + option_row + '\']').remove();
	$('#options div[id^=\'option' + option_row + '_\']').remove();
}

var option_value_row = <?php echo $option_value_row; ?>;

function addOptionValue(option_id) {
	html  = '<div id="option' + option_id + '_' + option_value_row + '" class="option">';
	html += '<table class="form">';
	html += '<tr>';
	html += '<td><?php echo $entry_option_value; ?></td>';
	html += '<td>';
	<?php foreach ($languages as $language) { ?>
	<?php if ($language['language_id'] == $language_id) { ?>
	html += '<input type="text" name="product_option[' + option_id + '][product_option_value][' + option_value_row + '][language][<?php echo $language['language_id']; ?>][name]" value="Option Value ' + option_value_row + '" onkeyup="$(\'#option option[value=\\\'option' + option_id + '_' + option_value_row + '\\\']\').text(\'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\' + this.value);" />&nbsp;<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
	<?php } else { ?>
	html += '<input type="text" name="product_option[' + option_id + '][product_option_value][' + option_value_row + '][language][<?php echo $language['language_id']; ?>][name]" value="Option Value ' + option_value_row + '" />&nbsp;<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
	<?php } ?>	
	<?php } ?>
	html += '</td>';
	html += '</tr>';
	html += '<tr>';
	html += '<td><?php echo $entry_quantity; ?></td>';
	html += '<td><input type="text" name="product_option[' + option_id + '][product_option_value][' + option_value_row + '][quantity]" value="' + '" size="2" /></td>';	
	html += '</tr>';
	html += '<tr>';
	html += '<td><?php echo $entry_subtract; ?></td>';
	html += '<td><select name="product_option[' + option_id + '][product_option_value][' + option_value_row + '][subtract]">';
    html += '<option value="1"><?php echo $text_yes; ?></option>';
    html += '<option value="0"><?php echo $text_no; ?></option>';
    html += '</select></td>';
	html += '</tr>';
	html += '<tr>';
	html += '<td><?php echo $entry_price; ?></td>';
	html += '<td><input type="text" name="product_option[' + option_id + '][product_option_value][' + option_value_row + '][price]" value="" /></td>';
	html += '</tr>';
	html += '<tr>';	
	html += '<td><?php echo $entry_prefix; ?></td>';
	html += '<td><select name="product_option[' + option_id + '][product_option_value][' + option_value_row + '][prefix]">';
	html += '<option value="+"><?php echo $text_plus; ?></option>';
	html += '<option value="-"><?php echo $text_minus; ?></option>';
	html += '</select></td>';
	html += '</tr>';
	html += '<tr>';	
	html += '<td><?php echo $entry_sort_order; ?></td>';	
	html += '<td><input type="text" name="product_option[' + option_id + '][product_option_value][' + option_value_row + '][sort_order]" value="" size="2" /></td>';
	html += '</tr>';
	html += '<tr>';		
	html += '<td colspan="2"><a onclick="removeOptionValue(\'' + option_id + '_' + option_value_row + '\');" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '</tr>';
	html += '</table>';
	html += '</div>';
	
	$('#options').append(html);
	
	option = $('#option option[value^=\'option' + option_id + '_\']:last');
	
	if (option.size()) {
		option.after('<option value="option' + option_id + '_' + option_value_row + '">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $text_option_value; ?> ' + option_value_row + '</option>');
	} else {
		$('#option option[value=\'option' + option_id + '\']').after('<option value="option' + option_id + '_' + option_value_row + '">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $text_option_value; ?> ' + option_value_row + '</option>');
	}
	
	$('#option option[value=\'option' + option_id + '_' + option_value_row + '\']').attr('selected', 'selected');
	
	$('#option').trigger('change');
	
	option_value_row++;
}

function removeOptionValue(option_value_row) {
	$('#option option[value=\'option' + option_value_row + '\']').remove();
	
	$('#option' + option_value_row).remove();
}
//--></script>
<script type="text/javascript"><!--
var discount_row = <?php echo $discount_row; ?>;

function addDiscount() {
	html  = '<tbody id="discount_row' + discount_row + '">';
	html += '<tr>'; 
    html += '<td class="left"><select name="product_discount[' + discount_row + '][customer_group_id]" style="margin-top: 3px;">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
    <?php } ?>
    html += '</select></td>';		
    html += '<td class="left"><input type="text" name="product_discount[' + discount_row + '][quantity]" value="" size="2" /></td>';
    html += '<td class="left"><input type="text" name="product_discount[' + discount_row + '][priority]" value="" size="2" /></td>';
	html += '<td class="left"><input type="text" name="product_discount[' + discount_row + '][price]" value="" /></td>';
    html += '<td class="left"><input type="text" name="product_discount[' + discount_row + '][date_start]" value="" class="date" /></td>';
	html += '<td class="left"><input type="text" name="product_discount[' + discount_row + '][date_end]" value="" class="date" /></td>';
	html += '<td class="left"><a onclick="$(\'#discount_row' + discount_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '</tr>';	
    html += '</tbody>';
	
	$('#discount tfoot').before(html);
		
	$('#discount_row' + discount_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});
	
	discount_row++;
}
//--></script>
<script type="text/javascript"><!--
var special_row = <?php echo $special_row; ?>;

function addSpecial() {
	html  = '<tbody id="special_row' + special_row + '">';
	html += '<tr>'; 
    html += '<td class="left"><select name="product_special[' + special_row + '][customer_group_id]">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
    <?php } ?>
    html += '</select></td>';		
    html += '<td class="left"><input type="text" name="product_special[' + special_row + '][priority]" value="" size="2" /></td>';
	html += '<td class="left"><input type="text" name="product_special[' + special_row + '][price]" value="" /></td>';
    html += '<td class="left"><input type="text" name="product_special[' + special_row + '][date_start]" value="" class="date" /></td>';
	html += '<td class="left"><input type="text" name="product_special[' + special_row + '][date_end]" value="" class="date" /></td>';
	html += '<td class="left"><a onclick="$(\'#special_row' + special_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '</tr>';
    html += '</tbody>';
	
	$('#special tfoot').before(html);
 
	$('#special_row' + special_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});
	
	special_row++;
}
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.draggable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.resizable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.dialog.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/external/bgiframe/jquery.bgiframe.js"></script>
<script type="text/javascript"><!--
function image_upload(field, preview) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>',
					type: 'POST',
					data: 'image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(data) {
						$('#' + preview).replaceWith('<img src="' + data + '" alt="" id="' + preview + '" class="image" onclick="image_upload(\'' + field + '\', \'' + preview + '\');" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 700,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script>
<script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
    html  = '<tbody id="image_row' + image_row + '">';
	html += '<tr>';
	html += '<td class="left"><input type="hidden" name="product_image[' + image_row + ']" value="" id="image' + image_row + '" /><img src="<?php echo $no_image; ?>" alt="" id="preview' + image_row + '" class="image" onclick="image_upload(\'image' + image_row + '\', \'preview' + image_row + '\');" /></td>';
	html += '<td class="left"><a onclick="$(\'#image_row' + image_row  + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '</tr>';
	html += '</tbody>';
	
	$('#images tfoot').before(html);
	
	image_row++;
}
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
$.tabs('#tabs a'); 
$.tabs('#languages a'); 
//--></script>
<?php echo $footer; ?>