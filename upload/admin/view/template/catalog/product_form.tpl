<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-editalt"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="buttons">
          <button type="submit" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
          <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
          <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
          <li><a href="#tab-links" data-toggle="tab"><?php echo $tab_links; ?></a></li>
          <li><a href="#tab-attribute" data-toggle="tab"><?php echo $tab_attribute; ?></a></li>
          <li><a href="#tab-option" data-toggle="tab"><?php echo $tab_option; ?></a></li>
          <li><a href="#tab-discount" data-toggle="tab"><?php echo $tab_discount; ?></a></li>
          <li><a href="#tab-special" data-toggle="tab"><?php echo $tab_special; ?></a></li>
          <li><a href="#tab-image" data-toggle="tab"><?php echo $tab_image; ?></a></li>
          <li><a href="#tab-reward" data-toggle="tab"><?php echo $tab_reward; ?></a></li>
          <li><a href="#tab-design" data-toggle="tab"><?php echo $tab_design; ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-general">
            <ul class="nav nav-tabs" id="language">
              <?php foreach ($languages as $language) { ?>
              <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
              <?php } ?>
            </ul>
            <div class="tab-content">
              <?php foreach ($languages as $language) { ?>
              <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                <div class="control-group">
                  <label class="control-label" for="input-name<?php echo $language['language_id']; ?>"><span class="required">*</span> <?php echo $entry_name; ?></label>
                  <div class="controls">
                    <input type="text" name="product_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="input-xxlarge" />
                    <?php if (isset($error_name[$language['language_id']])) { ?>
                    <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
                    <?php } ?>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                  <div class="controls">
                    <textarea name="product_description[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                  <div class="controls">
                    <textarea name="product_description[<?php echo $language['language_id']; ?>][meta_keyword]" cols="40" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                  <div class="controls">
                    <textarea name="product_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['description'] : ''; ?></textarea>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="input-tag<?php echo $language['language_id']; ?>"><?php echo $entry_tag; ?></label>
                  <div class="controls">
                    <input type="text" name="product_description[<?php echo $language['language_id']; ?>][tag]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['tag'] : ''; ?>" placeholder="<?php echo $entry_tag; ?>" id="input-tag<?php echo $language['language_id']; ?>" class="input-xxlarge" />
                    <a data-toggle="tooltip" title="<?php echo $help_tag; ?>"><i class="icon-info-sign"></i></a></div>
                </div>
              </div>
              <?php } ?>
            </div>
          </div>
          <div class="tab-pane" id="tab-data">
            <div class="control-group">
              <label class="control-label" for="input-model"><span class="required">*</span> <?php echo $entry_model; ?></label>
              <div class="controls">
                <input type="text" name="model" value="<?php echo $model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" />
                <?php if ($error_model) { ?>
                <span class="error"><?php echo $error_model; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-sku"><?php echo $entry_sku; ?></label>
              <div class="controls">
                <input type="text" name="sku" value="<?php echo $sku; ?>" placeholder="<?php echo $entry_sku; ?>" id="input-sku" />
                <a data-toggle="tooltip" title="<?php echo $help_sku; ?>"><i class="icon-info-sign"></i></a></div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-upc"><?php echo $entry_upc; ?></label>
              <div class="controls">
                <input type="text" name="upc" value="<?php echo $upc; ?>" placeholder="<?php echo $entry_upc; ?>" id="input-upc" />
                <a data-toggle="tooltip" title="<?php echo $help_upc; ?>"><i class="icon-info-sign"></i></a></div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-ean"><?php echo $entry_ean; ?></label>
              <div class="controls">
                <input type="text" name="ean" value="<?php echo $ean; ?>" placeholder="<?php echo $entry_ean; ?>" id="input-ean" />
                <a data-toggle="tooltip" title="<?php echo $help_ean; ?>"><i class="icon-info-sign"></i></a></div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-jan"><?php echo $entry_jan; ?></label>
              <div class="controls">
                <input type="text" name="jan" value="<?php echo $jan; ?>" placeholder="<?php echo $entry_jan; ?>" id="input-jan" />
                <a data-toggle="tooltip" title="<?php echo $help_jan; ?>"><i class="icon-info-sign"></i></a></div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-isbn"><?php echo $entry_isbn; ?></label>
              <div class="controls">
                <input type="text" name="isbn" value="<?php echo $isbn; ?>" placeholder="<?php echo $entry_isbn; ?>" id="input-isbn" />
                <a data-toggle="tooltip" title="<?php echo $help_isbn; ?>"><i class="icon-info-sign"></i></a></div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-mpn"><?php echo $entry_mpn; ?></label>
              <div class="controls">
                <input type="text" name="mpn" value="<?php echo $mpn; ?>" placeholder="<?php echo $entry_mpn; ?>" id="input-mpn" />
                <a data-toggle="tooltip" title="<?php echo $help_mpn; ?>"><i class="icon-info-sign"></i></a></div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-location"><?php echo $entry_location; ?></label>
              <div class="controls">
                <input type="text" name="location" value="<?php echo $location; ?>" placeholder="<?php echo $entry_location; ?>" id="input-location" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-price"><?php echo $entry_price; ?></label>
              <div class="controls">
                <input type="text" name="price" value="<?php echo $price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="input-small" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-tax-class"><?php echo $entry_tax_class; ?></label>
              <div class="controls">
                <select name="tax_class_id" id="input-tax-class">
                  <option value="0"><?php echo $text_none; ?></option>
                  <?php foreach ($tax_classes as $tax_class) { ?>
                  <?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
              <div class="controls">
                <input type="text" name="quantity" value="<?php echo $quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="input-mini" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-minimum"><?php echo $entry_minimum; ?></label>
              <div class="controls">
                <input type="text" name="minimum" value="<?php echo $minimum; ?>" placeholder="<?php echo $entry_minimum; ?>" id="input-minimum" class="input-mini" />
                <a data-toggle="tooltip" title="<?php echo $help_minimum; ?>"><i class="icon-info-sign"></i></a></div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-subtract"><?php echo $entry_subtract; ?></label>
              <div class="controls">
                <select name="subtract" id="input-subtract">
                  <?php if ($subtract) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <option value="0"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-stock-status"><?php echo $entry_stock_status; ?></label>
              <div class="controls">
                <select name="stock_status_id" id="input-stock-status">
                  <?php foreach ($stock_statuses as $stock_status) { ?>
                  <?php if ($stock_status['stock_status_id'] == $stock_status_id) { ?>
                  <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                <a data-toggle="tooltip" title="<?php echo $help_stock_status; ?>"><i class="icon-info-sign"></i></a></div>
            </div>
            <div class="control-group">
              <div class="control-label"><?php echo $entry_shipping; ?></div>
              <div class="controls inline">
                <label class="radio inline">
                  <?php if ($shipping) { ?>
                  <input type="radio" name="shipping" value="1" checked="checked" />
                  <?php echo $text_yes; ?>
                  <?php } else { ?>
                  <input type="radio" name="shipping" value="1" />
                  <?php echo $text_yes; ?>
                  <?php } ?>
                </label>
                <label class="radio inline">
                  <?php if (!$shipping) { ?>
                  <input type="radio" name="shipping" value="0" checked="checked" />
                  <?php echo $text_no; ?>
                  <?php } else { ?>
                  <input type="radio" name="shipping" value="0" />
                  <?php echo $text_no; ?>
                  <?php } ?>
                </label>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-keyword"><?php echo $entry_keyword; ?></label>
              <div class="controls">
                <input type="text" name="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" />
                <a data-toggle="tooltip" title="<?php echo $help_keyword; ?>"><i class="icon-info-sign"></i></a></div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-image"><?php echo $entry_image; ?></label>
              <div class="controls">
                <div class="image"><img src="<?php echo $thumb; ?>" alt="" id="thumb" class="img-polaroid" /><br />
                  <input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
                  <a onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a></div>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-available"><?php echo $entry_date_available; ?></label>
              <div class="controls">
                <input type="date" name="date_available" value="<?php echo $date_available; ?>" placeholder="<?php echo $entry_date_available; ?>" id="input-available" class="input-medium" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-length"><?php echo $entry_dimension; ?></label>
              <div class="controls">
                <input type="text" name="length" value="<?php echo $length; ?>" placeholder="<?php echo $entry_length; ?>" id="input-length" class="input-mini" />
                <input type="text" name="width" value="<?php echo $width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="input-mini" />
                <input type="text" name="height" value="<?php echo $height; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="input-mini" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-length-class"><?php echo $entry_length_class; ?></label>
              <div class="controls">
                <select name="length_class_id" id="input-length-class">
                  <?php foreach ($length_classes as $length_class) { ?>
                  <?php if ($length_class['length_class_id'] == $length_class_id) { ?>
                  <option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-weight"><?php echo $entry_weight; ?></label>
              <div class="controls">
                <input type="text" name="weight" value="<?php echo $weight; ?>" placeholder="<?php echo $entry_weight; ?>" id="input-weight" class="input-small" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-weight-class"><?php echo $entry_weight_class; ?></label>
              <div class="controls">
                <select name="weight_class_id" id="input-weight-class">
                  <?php foreach ($weight_classes as $weight_class) { ?>
                  <?php if ($weight_class['weight_class_id'] == $weight_class_id) { ?>
                  <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
              <div class="controls">
                <select name="status" id="input-status">
                  <?php if ($status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
              <div class="controls">
                <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="input-mini" />
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-links">
            <div class="control-group">
              <label class="control-label" for="input-manufacturer"><?php echo $entry_manufacturer; ?></label>
              <div class="controls">
                <input type="text" name="manufacturer" value="<?php echo $manufacturer ?>" placeholder="<?php echo $entry_manufacturer; ?>" id="input-manufacturer" />
                <input type="hidden" name="manufacturer_id" value="<?php echo $manufacturer_id; ?>" />
                <a data-toggle="tooltip" title="<?php echo $help_manufacturer; ?>"><i class="icon-info-sign"></i></a></div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-category"><?php echo $entry_category; ?></label>
              <div class="controls">
                <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" />
                <a data-toggle="tooltip" title="<?php echo $help_category; ?>"><i class="icon-info-sign"></i></a> <br />
                <div id="product-category" class="well well-small scrollbox">
                  <?php foreach ($product_categories as $product_category) { ?>
                  <div id="product-category<?php echo $product_category['category_id']; ?>"><i class="icon-minus-sign"></i> <?php echo $product_category['name']; ?>
                    <input type="hidden" name="product_category[]" value="<?php echo $product_category['category_id']; ?>" />
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-filter"><?php echo $entry_filter; ?></label>
              <div class="controls">
                <input type="text" name="filter" value="" placeholder="<?php echo $entry_filter; ?>" id="input-filter" />
                <a data-toggle="tooltip" title="<?php echo $help_filter; ?>"><i class="icon-info-sign"></i></a> <br />
                <div id="product-filter" class="well well-small scrollbox">
                  <?php foreach ($product_filters as $product_filter) { ?>
                  <div id="product-filter<?php echo $product_filter['filter_id']; ?>"><i class="icon-minus-sign"></i> <?php echo $product_filter['name']; ?>
                    <input type="hidden" name="product_filter[]" value="<?php echo $product_filter['filter_id']; ?>" />
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="control-group">
              <div class="control-label"><?php echo $entry_store; ?></div>
              <div class="controls">
                <label class="checkbox">
                  <?php if (in_array(0, $product_store)) { ?>
                  <input type="checkbox" name="product_store[]" value="0" checked="checked" />
                  <?php echo $text_default; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="product_store[]" value="0" />
                  <?php echo $text_default; ?>
                  <?php } ?>
                </label>
                <?php foreach ($stores as $store) { ?>
                <label class="checkbox">
                  <?php if (in_array($store['store_id'], $product_store)) { ?>
                  <input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                  <?php echo $store['name']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" />
                  <?php echo $store['name']; ?>
                  <?php } ?>
                </label>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-download"><?php echo $entry_download; ?></label>
              <div class="controls">
                <input type="text" name="download" value="" placeholder="<?php echo $entry_download; ?>" id="input-download" />
                <a data-toggle="tooltip" title="<?php echo $help_download; ?>"><i class="icon-info-sign"></i></a> <br />
                <div id="product-download" class="well well-small scrollbox">
                  <?php foreach ($product_downloads as $product_download) { ?>
                  <div id="product-download<?php echo $product_download['download_id']; ?>"><i class="icon-minus-sign"></i> <?php echo $product_download['name']; ?>
                    <input type="hidden" name="product_download[]" value="<?php echo $product_download['download_id']; ?>" />
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-related"><?php echo $entry_related; ?></label>
              <div class="controls">
                <input type="text" name="related" value="" placeholder="<?php echo $entry_related; ?>" id="input-related" />
                <a data-toggle="tooltip" title="<?php echo $help_related; ?>"><i class="icon-info-sign"></i></a> <br />
                <div id="product-related" class="well well-small scrollbox">
                  <?php foreach ($product_relateds as $product_related) { ?>
                  <div id="product-related<?php echo $product_related['product_id']; ?>"><i class="icon-minus-sign"></i> <?php echo $product_related['name']; ?>
                    <input type="hidden" name="product_related[]" value="<?php echo $product_related['product_id']; ?>" />
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-attribute">
            <table id="attribute" class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <td class="left"><?php echo $entry_attribute; ?></td>
                  <td class="left"><?php echo $entry_text; ?></td>
                  <td></td>
                </tr>
              </thead>
              <tbody>
                <?php $attribute_row = 0; ?>
                <?php foreach ($product_attributes as $product_attribute) { ?>
                <tr id="attribute-row<?php echo $attribute_row; ?>">
                  <td class="left"><input type="text" name="product_attribute[<?php echo $attribute_row; ?>][name]" value="<?php echo $product_attribute['name']; ?>" placeholder="<?php echo $entry_attribute; ?>" />
                    <input type="hidden" name="product_attribute[<?php echo $attribute_row; ?>][attribute_id]" value="<?php echo $product_attribute['attribute_id']; ?>" /></td>
                  <td class="left"><?php foreach ($languages as $language) { ?>
                    <textarea name="product_attribute[<?php echo $attribute_row; ?>][product_attribute_description][<?php echo $language['language_id']; ?>][text]" cols="40" rows="5" placeholder="<?php echo $entry_text; ?>"><?php echo isset($product_attribute['product_attribute_description'][$language['language_id']]) ? $product_attribute['product_attribute_description'][$language['language_id']]['text'] : ''; ?></textarea>
                    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                    <?php } ?></td>
                  <td class="left"><a onclick="$('#attribute-row<?php echo $attribute_row; ?>').remove();" class="btn"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></a></td>
                </tr>
                <?php $attribute_row++; ?>
                <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="2"></td>
                  <td class="left"><a onclick="addAttribute();" class="btn"><i class="icon-plus-sign"></i> <?php echo $button_add_attribute; ?></a></td>
                </tr>
              </tfoot>
            </table>
          </div>
          <div class="tab-pane" id="tab-option">
            <div class="control-group">
              <label class="control-label" for="input-option"><?php echo $entry_option; ?></label>
              <div class="controls">
                <input type="text" name="option" value="" placeholder="<?php echo $entry_option; ?>" id="input-option" />
              </div>
            </div>
            <div class="tabbable tabs-left">
              <ul class="nav nav-tabs" id="option">
                <?php $option_row = 0; ?>
                <?php foreach ($product_options as $product_option) { ?>
                <li><a href="#tab-option<?php echo $option_row; ?>" data-toggle="tab"><i class="icon-minus-sign" onclick="$('a[href=\'#tab-option<?php echo $option_row; ?>\']').parent().remove(); $('#tab-option<?php echo $option_row; ?>').remove(); $('#option a:first').tab('show');"></i> <?php echo $product_option['name']; ?></a></li>
                <?php $option_row++; ?>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php $option_row = 0; ?>
                <?php $option_value_row = 0; ?>
                <?php foreach ($product_options as $product_option) { ?>
                <div class="tab-pane" id="tab-option<?php echo $option_row; ?>">
                  <input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_id]" value="<?php echo $product_option['product_option_id']; ?>" />
                  <input type="hidden" name="product_option[<?php echo $option_row; ?>][name]" value="<?php echo $product_option['name']; ?>" />
                  <input type="hidden" name="product_option[<?php echo $option_row; ?>][option_id]" value="<?php echo $product_option['option_id']; ?>" />
                  <input type="hidden" name="product_option[<?php echo $option_row; ?>][type]" value="<?php echo $product_option['type']; ?>" />
                  <div class="control-group">
                    <label class="control-label" for="input-required<?php echo $option_row; ?>"><?php echo $entry_required; ?></label>
                    <div class="controls">
                      <select name="product_option[<?php echo $option_row; ?>][required]" id="input-required<?php echo $option_row; ?>">
                        <?php if ($product_option['required']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <?php if ($product_option['type'] == 'text') { ?>
                  <div class="control-group">
                    <label class="control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
                    <div class="controls">
                      <input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" id="input-value<?php echo $option_row; ?>" />
                    </div>
                  </div>
                  <?php } ?>
                  <?php if ($product_option['type'] == 'textarea') { ?>
                  <div class="control-group">
                    <label class="control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
                    <div class="controls">
                      <textarea name="product_option[<?php echo $option_row; ?>][value]" cols="40" rows="5" placeholder="<?php echo $entry_option_value; ?>" id="input-value<?php echo $option_row; ?>"><?php echo $product_option['value']; ?></textarea>
                    </div>
                  </div>
                  <?php } ?>
                  <?php if ($product_option['type'] == 'file') { ?>
                  <div class="control-group" style="display: none;">
                    <label class="control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
                    <div class="controls">
                      <input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" id="input-value<?php echo $option_row; ?>" />
                    </div>
                  </div>
                  <?php } ?>
                  <?php if ($product_option['type'] == 'date') { ?>
                  <div class="control-group">
                    <label class="control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
                    <div class="controls">
                      <input type="date" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" id="input-value<?php echo $option_row; ?>" class="input-medium" />
                    </div>
                  </div>
                  <?php } ?>
                  <?php if ($product_option['type'] == 'datetime') { ?>
                  <div class="control-group">
                    <label class="control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
                    <div class="controls">
                      <input type="datetime-local" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" id="input-value<?php echo $option_row; ?>" />
                    </div>
                  </div>
                  <?php } ?>
                  <?php if ($product_option['type'] == 'time') { ?>
                  <div class="control-group">
                    <label class="control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
                    <div class="controls">
                      <input type="time" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" id="input-value<?php echo $option_row; ?>" class="input-mini" />
                    </div>
                  </div>
                  <?php } ?>
                  <?php if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') { ?>
                  <table id="option-value<?php echo $option_row; ?>" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <td class="left"><?php echo $entry_option_value; ?></td>
                        <td class="right"><?php echo $entry_quantity; ?></td>
                        <td class="left"><?php echo $entry_subtract; ?></td>
                        <td class="right"><?php echo $entry_price; ?></td>
                        <td class="right"><?php echo $entry_option_points; ?></td>
                        <td class="right"><?php echo $entry_weight; ?></td>
                        <td></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($product_option['product_option_value'] as $product_option_value) { ?>
                      <tr id="option-value-row<?php echo $option_value_row; ?>">
                        <td class="left"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][option_value_id]">
                            <?php if (isset($option_values[$product_option['option_id']])) { ?>
                            <?php foreach ($option_values[$product_option['option_id']] as $option_value) { ?>
                            <?php if ($option_value['option_value_id'] == $product_option_value['option_value_id']) { ?>
                            <option value="<?php echo $option_value['option_value_id']; ?>" selected="selected"><?php echo $option_value['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                          </select>
                          <input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][product_option_value_id]" value="<?php echo $product_option_value['product_option_value_id']; ?>" /></td>
                        <td class="right"><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][quantity]" value="<?php echo $product_option_value['quantity']; ?>" placeholder="<?php echo $entry_quantity; ?>" class="input-mini" /></td>
                        <td class="left"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][subtract]" class="input-small">
                            <?php if ($product_option_value['subtract']) { ?>
                            <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                            <option value="0"><?php echo $text_no; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_yes; ?></option>
                            <option value="0" selected="selected"><?php echo $text_no; ?></option>
                            <?php } ?>
                          </select></td>
                        <td class="right"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price_prefix]" class="input-mini">
                            <?php if ($product_option_value['price_prefix'] == '+') { ?>
                            <option value="+" selected="selected">+</option>
                            <?php } else { ?>
                            <option value="+">+</option>
                            <?php } ?>
                            <?php if ($product_option_value['price_prefix'] == '-') { ?>
                            <option value="-" selected="selected">-</option>
                            <?php } else { ?>
                            <option value="-">-</option>
                            <?php } ?>
                          </select>
                          <input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price]" value="<?php echo $product_option_value['price']; ?>" placeholder="<?php echo $entry_price; ?>" class="input-small" /></td>
                        <td class="right"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points_prefix]" class="input-mini">
                            <?php if ($product_option_value['points_prefix'] == '+') { ?>
                            <option value="+" selected="selected">+</option>
                            <?php } else { ?>
                            <option value="+">+</option>
                            <?php } ?>
                            <?php if ($product_option_value['points_prefix'] == '-') { ?>
                            <option value="-" selected="selected">-</option>
                            <?php } else { ?>
                            <option value="-">-</option>
                            <?php } ?>
                          </select>
                          <input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points]" value="<?php echo $product_option_value['points']; ?>" placeholder="<?php echo $entry_points; ?>" class="input-small" /></td>
                        <td class="right"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight_prefix]" class="input-mini">
                            <?php if ($product_option_value['weight_prefix'] == '+') { ?>
                            <option value="+" selected="selected">+</option>
                            <?php } else { ?>
                            <option value="+">+</option>
                            <?php } ?>
                            <?php if ($product_option_value['weight_prefix'] == '-') { ?>
                            <option value="-" selected="selected">-</option>
                            <?php } else { ?>
                            <option value="-">-</option>
                            <?php } ?>
                          </select>
                          <input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight]" value="<?php echo $product_option_value['weight']; ?>" placeholder="<?php echo $entry_weight; ?>" class="input-small" /></td>
                        <td class="left"><a onclick="$('#option-value-row<?php echo $option_value_row; ?>').remove();" class="btn"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></a></td>
                      </tr>
                      <?php $option_value_row++; ?>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="6"></td>
                        <td class="left"><a onclick="addOptionValue('<?php echo $option_row; ?>');" class="btn"><i class="icon-plus-sign"></i> <?php echo $button_add_option_value; ?></a></td>
                      </tr>
                    </tfoot>
                  </table>
                  <select id="option-values<?php echo $option_row; ?>" style="display: none;">
                    <?php if (isset($option_values[$product_option['option_id']])) { ?>
                    <?php foreach ($option_values[$product_option['option_id']] as $option_value) { ?>
                    <option value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <?php } ?>
                </div>
                <?php $option_row++; ?>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-discount">
            <table id="discount" class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <td class="left"><?php echo $entry_customer_group; ?></td>
                  <td class="right"><?php echo $entry_quantity; ?></td>
                  <td class="right"><?php echo $entry_priority; ?></td>
                  <td class="right"><?php echo $entry_price; ?></td>
                  <td class="left"><?php echo $entry_date_start; ?></td>
                  <td class="left"><?php echo $entry_date_end; ?></td>
                  <td></td>
                </tr>
              </thead>
              <tbody>
                <?php $discount_row = 0; ?>
                <?php foreach ($product_discounts as $product_discount) { ?>
                <tr id="discount-row<?php echo $discount_row; ?>">
                  <td class="left"><select name="product_discount[<?php echo $discount_row; ?>][customer_group_id]">
                      <?php foreach ($customer_groups as $customer_group) { ?>
                      <?php if ($customer_group['customer_group_id'] == $product_discount['customer_group_id']) { ?>
                      <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select></td>
                  <td class="right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][quantity]" value="<?php echo $product_discount['quantity']; ?>" placeholder="<?php echo $entry_quantity; ?>" class="input-mini" /></td>
                  <td class="right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][priority]" value="<?php echo $product_discount['priority']; ?>" placeholder="<?php echo $entry_priority; ?>" class="input-mini" /></td>
                  <td class="right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][price]" value="<?php echo $product_discount['price']; ?>" placeholder="<?php echo $entry_price; ?>" class="input-small" /></td>
                  <td class="left"><input type="date" name="product_discount[<?php echo $discount_row; ?>][date_start]" value="<?php echo $product_discount['date_start']; ?>" placeholder="<?php echo $entry_date_start; ?>" class="input-medium" /></td>
                  <td class="left"><input type="date" name="product_discount[<?php echo $discount_row; ?>][date_end]" value="<?php echo $product_discount['date_end']; ?>" placeholder="<?php echo $entry_date_end; ?>" class="input-medium" /></td>
                  <td class="left"><a onclick="$('#discount-row<?php echo $discount_row; ?>').remove();" class="btn"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></a></td>
                </tr>
                <?php $discount_row++; ?>
                <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="6"></td>
                  <td class="left"><a onclick="addDiscount();" class="btn"><i class="icon-plus-sign"></i> <?php echo $button_add_discount; ?></a></td>
                </tr>
              </tfoot>
            </table>
          </div>
          <div class="tab-pane" id="tab-special">
            <table id="special" class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <td class="left"><?php echo $entry_customer_group; ?></td>
                  <td class="right"><?php echo $entry_priority; ?></td>
                  <td class="right"><?php echo $entry_price; ?></td>
                  <td class="left"><?php echo $entry_date_start; ?></td>
                  <td class="left"><?php echo $entry_date_end; ?></td>
                  <td></td>
                </tr>
              </thead>
              <tbody>
                <?php $special_row = 0; ?>
                <?php foreach ($product_specials as $product_special) { ?>
                <tr id="special-row<?php echo $special_row; ?>">
                  <td class="left"><select name="product_special[<?php echo $special_row; ?>][customer_group_id]">
                      <?php foreach ($customer_groups as $customer_group) { ?>
                      <?php if ($customer_group['customer_group_id'] == $product_special['customer_group_id']) { ?>
                      <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select></td>
                  <td class="right"><input type="text" name="product_special[<?php echo $special_row; ?>][priority]" value="<?php echo $product_special['priority']; ?>" placeholder="<?php echo $entry_quantity; ?>" class="input-mini" /></td>
                  <td class="right"><input type="text" name="product_special[<?php echo $special_row; ?>][price]" value="<?php echo $product_special['price']; ?>" placeholder="<?php echo $entry_price; ?>" class="input-small" /></td>
                  <td class="left"><input type="date" name="product_special[<?php echo $special_row; ?>][date_start]" value="<?php echo $product_special['date_start']; ?>" placeholder="<?php echo $entry_date_start; ?>" class="input-medium" /></td>
                  <td class="left"><input type="date" name="product_special[<?php echo $special_row; ?>][date_end]" value="<?php echo $product_special['date_end']; ?>" placeholder="<?php echo $entry_date_end; ?>" class="input-medium" /></td>
                  <td class="left"><a onclick="$('#special-row<?php echo $special_row; ?>').remove();" class="btn"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></a></td>
                </tr>
                <?php $special_row++; ?>
                <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="5"></td>
                  <td class="left"><a onclick="addSpecial();" class="btn"><i class="icon-plus-sign"></i> <?php echo $button_add_special; ?></a></td>
                </tr>
              </tfoot>
            </table>
          </div>
          <div class="tab-pane" id="tab-image">
            <table id="images" class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <td class="left"><?php echo $entry_image; ?></td>
                  <td class="right"><?php echo $entry_sort_order; ?></td>
                  <td></td>
                </tr>
              </thead>
              <tbody>
                <?php $image_row = 0; ?>
                <?php foreach ($product_images as $product_image) { ?>
                <tr id="image-row<?php echo $image_row; ?>">
                  <td class="left"><div class="image"><img src="<?php echo $product_image['thumb']; ?>" alt="" id="thumb<?php echo $image_row; ?>" class="img-polaroid" />
                      <input type="hidden" name="product_image[<?php echo $image_row; ?>][image]" value="<?php echo $product_image['image']; ?>" id="image<?php echo $image_row; ?>" />
                      <br />
                      <a onclick="image_upload('image<?php echo $image_row; ?>', 'thumb<?php echo $image_row; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $image_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
                  <td class="right"><input type="text" name="product_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $product_image['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="input-mini" /></td>
                  <td class="left"><a onclick="$('#image-row<?php echo $image_row; ?>').remove();" class="btn"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></a></td>
                </tr>
                <?php $image_row++; ?>
                <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="2"></td>
                  <td class="left"><a onclick="addImage();" class="btn"><i class="icon-plus-sign"></i> <?php echo $button_add_image; ?></a></td>
                </tr>
              </tfoot>
            </table>
          </div>
          <div class="tab-pane" id="tab-reward">
            <div class="control-group">
              <label class="control-label" for="input-points"><?php echo $entry_points; ?></label>
              <div class="controls">
                <input type="text" name="points" value="<?php echo $points; ?>" placeholder="<?php echo $entry_points; ?>" id="input-points" class="input-small" />
                <a data-toggle="tooltip" title="<?php echo $help_stock_status; ?>"><i class="icon-info-sign"></i></a></div>
            </div>
            <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <td class="left"><?php echo $entry_customer_group; ?></td>
                  <td class="right"><?php echo $entry_reward; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($customer_groups as $customer_group) { ?>
                <tr>
                  <td class="left"><?php echo $customer_group['name']; ?></td>
                  <td class="right"><input type="text" name="product_reward[<?php echo $customer_group['customer_group_id']; ?>][points]" value="<?php echo isset($product_reward[$customer_group['customer_group_id']]) ? $product_reward[$customer_group['customer_group_id']]['points'] : ''; ?>" /></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
          <div class="tab-pane" id="tab-design">
            <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <td class="left"><?php echo $entry_store; ?></td>
                  <td class="left"><?php echo $entry_layout; ?></td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="left"><?php echo $text_default; ?></td>
                  <td class="left"><select name="product_layout[0][layout_id]">
                      <option value=""></option>
                      <?php foreach ($layouts as $layout) { ?>
                      <?php if (isset($product_layout[0]) && $product_layout[0] == $layout['layout_id']) { ?>
                      <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select></td>
                </tr>
                <?php foreach ($stores as $store) { ?>
                <tr>
                  <td class="left"><?php echo $store['name']; ?></td>
                  <td class="left"><select name="product_layout[<?php echo $store['store_id']; ?>][layout_id]">
                      <option value=""></option>
                      <?php foreach ($layouts as $layout) { ?>
                      <?php if (isset($product_layout[$store['store_id']]) && $product_layout[$store['store_id']] == $layout['layout_id']) { ?>
                      <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('input-description<?php echo $language['language_id']; ?>', {
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
// Manufacturer
$('input[name=\'manufacturer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				json.unshift({
					'manufacturer_id': 0,
					'name': '<?php echo $text_none; ?>'
				});
				
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['manufacturer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'manufacturer\']').val(item['label']);
		$('input[name=\'manufacturer_id\']').val(item['value']);
	}	
});

// Category
$('input[name=\'category\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['category_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'category\']').val('');
		
		$('#product-category' + item['value']).remove();
		
		$('#product-category').append('<div id="product-category' + item['value'] + '"><i class="icon-minus-sign"></i> ' + item['label'] + '<input type="hidden" name="product_category[]" value="' + item['value'] + '" /></div>');	
	}
});

$('#product-category').delegate('.icon-minus-sign', 'click', function() {
	$(this).parent().remove();
});

// Filter
$('input[name=\'filter\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/filter/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['filter_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter\']').val('');
		
		$('#product-filter' + item['value']).remove();
		
		$('#product-filter').append('<div id="product-filter' + item['value'] + '"><i class="icon-minus-sign"></i> ' + item['label'] + '<input type="hidden" name="product_filter[]" value="' + item['value'] + '" /></div>');	
	}	
});

$('#product-filter').delegate('.icon-minus-sign', 'click', function() {
	$(this).parent().remove();
});

// Downloads
$('input[name=\'download\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/download/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['download_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'download\']').val('');
		
		$('#product-download' + item['value']).remove();
		
		$('#product-download').append('<div id="product-download' + item['value'] + '"><i class="icon-minus-sign"></i> ' + item['label'] + '<input type="hidden" name="product_download[]" value="' + item['value'] + '" /></div>');	
	}	
});

$('#product-download').delegate('.icon-minus-sign', 'click', function() {
	$(this).parent().remove();
});

// Related
$('input[name=\'related\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'related\']').val('');
		
		$('#product-related' + item['value']).remove();
		
		$('#product-related').append('<div id="product-related' + item['value'] + '"><i class="icon-minus-sign"></i> ' + item['label'] + '<input type="hidden" name="product_related[]" value="' + item['value'] + '" /></div>');	
	}	
});

$('#product-related').delegate('.icon-minus-sign', 'click', function() {
	$(this).parent().remove();
});
//--></script> 
<script type="text/javascript"><!--
var attribute_row = <?php echo $attribute_row; ?>;

function addAttribute() {
    html  = '<tr id="attribute-row' + attribute_row + '">';
	html += '  <td class="left"><input type="text" name="product_attribute[' + attribute_row + '][name]" value="" placeholder="<?php echo $entry_attribute; ?>" /><input type="hidden" name="product_attribute[' + attribute_row + '][attribute_id]" value="" /></td>';
	html += '  <td class="left">';
	<?php foreach ($languages as $language) { ?>
	html += '<textarea name="product_attribute[' + attribute_row + '][product_attribute_description][<?php echo $language['language_id']; ?>][text]" cols="40" rows="5" placeholder="<?php echo $entry_text; ?>"></textarea> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
    <?php } ?>
	html += '  </td>';
	html += '  <td class="left"><a onclick="$(\'#attribute-row' + attribute_row + '\').remove();" class="btn"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></a></td>';
    html += '</tr>';
	
	$('#attribute tbody').append(html);
	
	attributeautocomplete(attribute_row);
	
	attribute_row++;
}

function attributeautocomplete(attribute_row) {
	$('input[name=\'product_attribute[' + attribute_row + '][name]\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',			
				success: function(json) {
					response($.map(json, function(item) {
						return {
							category: item.attribute_group,
							label: item.name,
							value: item.attribute_id
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name=\'product_attribute[' + attribute_row + '][name]\']').val(item['label']);
			$('input[name=\'product_attribute[' + attribute_row + '][attribute_id]\']').val(item['value']);
		}
	});
}

$('#attribute tbody tr').each(function(index, element) {
	attributeautocomplete(index);
});
//--></script> 
<script type="text/javascript"><!--	
var option_row = <?php echo $option_row; ?>;

$('input[name=\'option\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/option/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						category: item['category'],
						label: item['name'],
						value: item['option_id'],
						type: item['type'],
						option_value: item['option_value']
					}
				}));
			}
		});
	},
	'select': function(item) {
		html  = '<div class="tab-pane" id="tab-option' + option_row + '">';
		html += '	<input type="hidden" name="product_option[' + option_row + '][product_option_id]" value="" />';
		html += '	<input type="hidden" name="product_option[' + option_row + '][name]" value="' + item['label'] + '" />';
		html += '	<input type="hidden" name="product_option[' + option_row + '][option_id]" value="' + item['value'] + '" />';
		html += '	<input type="hidden" name="product_option[' + option_row + '][type]" value="' + item['type'] + '" />';
		
		html += '	<div class="control-group">';
		html += '	  <label class="control-label" for="input-required' + option_row + '"><?php echo $entry_required; ?></label>';
		html += '	  <div class="controls"><select name="product_option[' + option_row + '][required]" id="input-required' + option_row + '">';
		html += '	      <option value="1"><?php echo $text_yes; ?></option>';
		html += '	      <option value="0"><?php echo $text_no; ?></option>';
		html += '	  </select></div>';
		html += '	</div>';
		
		if (item['type'] == 'text') {
			html += '	<div class="control-group">';
			html += '	  <label class="control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
			html += '	  <div class="controls"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" /></div>';
			html += '	</div>';
		}
		
		if (item['type'] == 'textarea') {
			html += '	<div class="control-group">';
			html += '	  <label class="control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
			html += '	  <div class="controls"><textarea name="product_option[' + option_row + '][value]" cols="40" rows="5" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '"></textarea></div>';
			html += '	</div>';			
		}
		 
		if (item['type'] == 'file') {
			html += '	<div class="control-group" style="display: none;">';
			html += '	  <label class="control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
			html += '	  <div class="controls"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" /></div>';
			html += '	</div>';
		}
						
		if (item['type'] == 'date') {
			html += '	<div class="control-group">';
			html += '	  <label class="control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
			html += '	  <div class="controls"><input type="date" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" class="input-medium" /></div>';
			html += '	</div>';
		}
		
		if (item['type'] == 'datetime') {
			html += '	<div class="control-group">';
			html += '	  <label class="control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
			html += '	  <div class="controls"><input type="datetime-local" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" /></div>';
			html += '	</div>';
		}
		
		if (item['type'] == 'time') {
			html += '	<div class="control-group">';
			html += '	  <label class="control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
			html += '	  <div class="controls"><input type="time" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" class="input-mini" /></div>';
			html += '	</div>';
		}
			
		if (item['type'] == 'select' || item['type'] == 'radio' || item['type'] == 'checkbox' || item['type'] == 'image') {
			html += '  <table id="option-value' + option_row + '" class="table table-striped table-bordered table-hover">';
			html += '  	 <thead>'; 
			html += '      <tr>';
			html += '        <td class="left"><?php echo $entry_option_value; ?></td>';
			html += '        <td class="right"><?php echo $entry_quantity; ?></td>';
			html += '        <td class="left"><?php echo $entry_subtract; ?></td>';
			html += '        <td class="right"><?php echo $entry_price; ?></td>';
			html += '        <td class="right"><?php echo $entry_option_points; ?></td>';
			html += '        <td class="right"><?php echo $entry_weight; ?></td>';
			html += '        <td></td>';
			html += '      </tr>';
			html += '  	 </thead>';
			html += '  	 <tbody>';
			html += '    </tbody>';
			html += '    <tfoot>';
			html += '      <tr>';
			html += '        <td colspan="6"></td>';
			html += '        <td class="left"><a onclick="addOptionValue(' + option_row + ');" class="btn"><i class="icon-plus-sign"></i> <?php echo $button_add_option_value; ?></a></td>';
			html += '      </tr>';
			html += '    </tfoot>';
			html += '  </table>';
            html += '  <select id="option-values' + option_row + '" style="display: none;">';
			
            for (i = 0; i < item['option_value'].length; i++) {
				html += '  <option value="' + item['option_value'][i]['option_value_id'] + '">' + item['option_value'][i]['name'] + '</option>';
            }

            html += '  </select>';	
			html += '</div>';	
		}
		
		$('#tab-option .tab-content').append(html);
		
		$('#option').append('<li><a href="#tab-option' + option_row + '" data-toggle="tab"><i class="icon-minus-sign" onclick="$(\'a[href=\\\'#tab-option' + option_row + '\\\']\').parent().remove(); $(\'#tab-option' + option_row + '\').remove(); $(\'#vtab-option a:first\').trigger(\'click\');"></i> ' + item['label'] + '</li>');
		
		$('#option a[href=\'#tab-option' + option_row + '\']').tab('show');
				
		option_row++;
	}	
});
//--></script> 
<script type="text/javascript"><!--		
var option_value_row = <?php echo $option_value_row; ?>;

function addOptionValue(option_row) {	
	html  = '<tr id="option-value-row' + option_value_row + '">';
	html += '  <td class="left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value_id]">';
	html += $('#option-values' + option_row).html();
	html += '  </select><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][product_option_value_id]" value="" /></td>';
	html += '  <td class="right"><input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][quantity]" value="" placeholder="<?php echo $entry_quantity; ?>" class="input-mini" /></td>'; 
	html += '  <td class="left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][subtract]" class="input-small">';
	html += '    <option value="1"><?php echo $text_yes; ?></option>';
	html += '    <option value="0"><?php echo $text_no; ?></option>';
	html += '  </select></td>';
	html += '  <td class="right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price_prefix]" class="input-mini">';
	html += '    <option value="+">+</option>';
	html += '    <option value="-">-</option>';
	html += '  </select>';
	html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="input-small" /></td>';
	html += '  <td class="right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points_prefix]" class="input-mini">';
	html += '    <option value="+">+</option>';
	html += '    <option value="-">-</option>';
	html += '  </select>';
	html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points]" value="" placeholder="<?php echo $entry_points; ?>" class="input-small" /></td>';	
	html += '  <td class="right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight_prefix]" class="input-mini">';
	html += '    <option value="+">+</option>';
	html += '    <option value="-">-</option>';
	html += '  </select>';
	html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight]" value="" placeholder="<?php echo $entry_weight; ?>" class="input-small" /></td>';
	html += '  <td class="left"><a onclick="$(\'#option-value-row' + option_value_row + '\').remove();" class="btn"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></a></td>';
	html += '</tr>';
	
	$('#option-value' + option_row + ' tbody').append(html);

	option_value_row++;
}
//--></script> 
<script type="text/javascript"><!--
var discount_row = <?php echo $discount_row; ?>;

function addDiscount() {
	html  = '<tr id="discount-row' + discount_row + '">'; 
    html += '  <td class="left"><select name="product_discount[' + discount_row + '][customer_group_id]">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
    <?php } ?>
    html += '  </select></td>';		
    html += '  <td class="right"><input type="text" name="product_discount[' + discount_row + '][quantity]" value="" placeholder="<?php echo $entry_quantity; ?>" class="input-mini" /></td>';
    html += '  <td class="right"><input type="text" name="product_discount[' + discount_row + '][priority]" value="" placeholder="<?php echo $entry_priority; ?>" class="input-mini" /></td>';
	html += '  <td class="right"><input type="text" name="product_discount[' + discount_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="input-small" /></td>';
    html += '  <td class="left"><input type="date" name="product_discount[' + discount_row + '][date_start]" value="" placeholder="<?php echo $entry_date_start; ?>" class="input-medium" /></td>';
	html += '  <td class="left"><input type="date" name="product_discount[' + discount_row + '][date_end]" value="" placeholder="<?php echo $entry_date_end; ?>" class="input-medium" /></td>';
	html += '  <td class="left"><a onclick="$(\'#discount-row' + discount_row + '\').remove();" class="btn"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></a></td>';
	html += '</tr>';	
	
	$('#discount tbody').append(html);
	
	discount_row++;
}
//--></script> 
<script type="text/javascript"><!--
var special_row = <?php echo $special_row; ?>;

function addSpecial() {
	html  = '<tr id="special-row' + special_row + '">'; 
    html += '  <td class="left"><select name="product_special[' + special_row + '][customer_group_id]">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
    <?php } ?>
    html += '  </select></td>';		
    html += '  <td class="right"><input type="text" name="product_special[' + special_row + '][priority]" value="" placeholder="<?php echo $entry_priority; ?>" class="input-mini" /></td>';
	html += '  <td class="right"><input type="text" name="product_special[' + special_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="input-small" /></td>';
    html += '  <td class="left"><input type="date" name="product_special[' + special_row + '][date_start]" value="" placeholder="<?php echo $entry_date_start; ?>" class="input-medium" /></td>';
	html += '  <td class="left"><input type="date" name="product_special[' + special_row + '][date_end]" value="" placeholder="<?php echo $entry_date_end; ?>" class="input-medium" /></td>';
	html += '  <td class="left"><a onclick="$(\'#special-row' + special_row + '\').remove();" class="btn"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></a></td>';
	html += '</tr>';
	
	$('#special tbody').append(html);
	
	special_row++;
}
//--></script> 
<script type="text/javascript"><!--
function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(text) {
						$('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script> 
<script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
	html  = '<tr id="image-row' + image_row + '">';
	html += '  <td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + image_row + '" class="img-polaroid" /><input type="hidden" name="product_image[' + image_row + '][image]" value="" id="image' + image_row + '" /><br /><a onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';
	html += '  <td class="right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="input-mini" /></td>';
	html += '  <td class="left"><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="btn"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></a></td>';
	html += '</tr>';
	
	$('#images tbody').append(html);
	
	image_row++;
}
//--></script> 
<script type="text/javascript"><!--
$('#language a:first').tab('show');
$('#option a:first').tab('show');
//--></script> 
<?php echo $footer; ?>