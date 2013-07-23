<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/product.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"></div>
    </div>
    <div class="content">
        <table class="list">
            <thead>
                <tr>
                    <td colspan="6" class="left">Filter results</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-weight:bold;" class="left">Title</td>
                    <td class="left"><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" size="35" /></td>
                    <td style="font-weight:bold;" class="left">Stock range</td>
                    <td class="left">
                        <input type="text" name="filter_quantity" size="8" value="<?php echo $filter_quantity; ?>" style="text-align: left;" /> to
                        <input type="text" name="filter_quantity_to" size="8" value="<?php echo $filter_quantity_to; ?>" style="text-align: left;" />
                    </td>
                    <td style="font-weight:bold;" class="left">Status</td>
                    <td class="left">

                        <select name="filter_status">
                            <option value="*"></option>
                            <?php if ($filter_status) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <?php } ?>
                            <?php if (!is_null($filter_status) && !$filter_status) { ?>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select>

                    </td>
                </tr>
                <tr>
                    <td style="font-weight:bold;" class="left">Marketplace status</td>
                    <td class="left">

                        <select name="filter_marketplace">
                            <option value="all" <?php echo (!isset($filter_marketplace) || $filter_marketplace == 'all' ? ' selected' : ''); ?>>All</option>
                            <?php if($marketplace_statuses['ebay']) : ?>
                            <option value="ebay_active" <?php echo ($filter_marketplace == 'ebay_active' ? ' selected' : ''); ?>>eBay active</option>
                            <option value="ebay_inactive" <?php echo ($filter_marketplace == 'ebay_inactive' ? ' selected' : ''); ?>>eBay not active</option>
                            <?php endif; ?>
                            <?php if($marketplace_statuses['amazon']) : ?>
                            <option value="amazon_saved" <?php echo ($filter_marketplace == 'amazon_saved' ? ' selected' : ''); ?>>Amazon EU saved</option>
                            <option value="amazon_uploaded" <?php echo ($filter_marketplace == 'amazon_uploaded' ? ' selected' : ''); ?>>Amazon EU processing</option>
                            <option value="amazon_ok" <?php echo ($filter_marketplace == 'amazon_ok' ? ' selected' : ''); ?>>Amazon EU active</option>
                            <option value="amazon_error" <?php echo ($filter_marketplace == 'amazon_error' ? ' selected' : ''); ?>>Amazon EU failed</option>
                            <option value="amazon_unlisted" <?php echo ($filter_marketplace == 'amazon_unlisted' ? ' selected' : ''); ?>>Amazon EU not listed</option>
                            <option value="amazon_linked" <?php echo ($filter_marketplace == 'amazon_linked' ? ' selected' : ''); ?>>Amazon EU linked</option>
                            <option value="amazon_not_linked" <?php echo ($filter_marketplace == 'amazon_not_linked' ? ' selected' : ''); ?>>Amazon EU not linked</option>
                            <?php endif; ?>
                            <?php if($marketplace_statuses['amazonus']) : ?>
                            <option value="amazonus_saved" <?php echo ($filter_marketplace == 'amazonus_saved' ? ' selected' : ''); ?>>Amazon US saved</option>
                            <option value="amazonus_uploaded" <?php echo ($filter_marketplace == 'amazonus_uploaded' ? ' selected' : ''); ?>>Amazon US processing</option>
                            <option value="amazonus_ok" <?php echo ($filter_marketplace == 'amazonus_ok' ? ' selected' : ''); ?>>Amazon US active</option>
                            <option value="amazonus_error" <?php echo ($filter_marketplace == 'amazonus_error' ? ' selected' : ''); ?>>Amazon US failed</option>
                            <option value="amazonus_unlisted" <?php echo ($filter_marketplace == 'amazonus_unlisted' ? ' selected' : ''); ?>>Amazon US not listed</option>
                            <option value="amazonus_linked" <?php echo ($filter_marketplace == 'amazonus_linked' ? ' selected' : ''); ?>>Amazon US linked</option>
                            <option value="amazonus_not_linked" <?php echo ($filter_marketplace == 'amazonus_not_linked' ? ' selected' : ''); ?>>Amazon US not linked</option>
                            <?php endif; ?>
                            <?php if($marketplace_statuses['play']) : ?>
                            <option value="play_active" <?php echo ($filter_marketplace == 'play_active' ? ' selected' : ''); ?>>Play.com active</option>
                            <option value="play_pending" <?php echo ($filter_marketplace == 'play_pending' ? ' selected' : ''); ?>>Play.com pending new</option>
                            <option value="play_processing" <?php echo ($filter_marketplace == 'play_processing' ? ' selected' : ''); ?>>Play.com processing</option>
                            <option value="play_updating" <?php echo ($filter_marketplace == 'play_updating' ? ' selected' : ''); ?>>Play.com pending edit</option>
                            <option value="play_stock_updating" <?php echo ($filter_marketplace == 'play_stock_updating' ? ' selected' : ''); ?>>Play.com stock updating</option>
                            <option value="play_errors" <?php echo ($filter_marketplace == 'play_errors' ? ' selected' : ''); ?>>Play.com errors</option>
                            <option value="play_failures" <?php echo ($filter_marketplace == 'play_failures' ? ' selected' : ''); ?>>Play.com failures</option>
                            <option value="play_inactive" <?php echo ($filter_marketplace == 'play_inactive' ? ' selected' : ''); ?>>Play.com not active</option>
                            <option value="play_pending_delete" <?php echo ($filter_marketplace == 'play_pending_delete' ? ' selected' : ''); ?>>Play.com pending delete</option>
                            <?php endif; ?>
                        </select>

                    </td>
                    <td style="font-weight:bold;" class="left">Price range</td>
                    <td class="left">
                        <input type="text" name="filter_price" value="<?php echo $filter_price; ?>" size="8"/> to
                        <input type="text" name="filter_price_to" value="<?php echo $filter_price_to; ?>" size="8"/>
                    </td>
                    <td style="font-weight:bold;" class="left">Model</td>
                    <td class="left"><input type="text" name="filter_model" value="<?php echo $filter_model; ?>" /></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;" class="left">Category</td>
                    <td class="left">

                        <select name="filter_category">
                            <option value=""></option>
                            <option value="none">Missing category</option>
                            <?php foreach($category_list as $cat) { ?>
                                <option value="<?php echo $cat['category_id']; ?>"<?php echo ($filter_category == $cat["category_id"] ? " selected" : ""); ?>><?php echo $cat['name']; ?></option>
                            <?php } ?>
                        </select>

                    </td>
                    <td style="font-weight:bold;" class="left">Manufacturer</td>
                    <td class="left">

                        <select name="filter_manufacturer">
                            <option value=""></option>
                            <?php foreach($manufacturer_list as $man) { ?>
                                <option value="<?php echo $man['manufacturer_id']; ?>"<?php echo ($filter_manufacturer == $man["manufacturer_id"] ? " selected" : ""); ?>><?php echo $man['name']; ?></option>
                            <?php } ?>
                        </select>

                    </td>
                    <td style="font-weight:bold;" class="left">Populated</td>
                    <td class="left">
                        <label for="filter_sku">SKU</label>
                        <input type="checkbox" name="filter_sku" id="filter_sku" value="1" <?php if($filter_sku == 1){ echo 'checked="checked" ';} ?>/>
                        <label for="filter_desc">Description</label>
                        <input type="checkbox" name="filter_desc" id="filter_desc" value="1" <?php if($filter_desc == 1){ echo 'checked="checked" ';} ?>/>
                    </td>
                </tr>
            <tr>
                <td colspan="6" class="right"><a onclick="filter();" class="button"><?php echo $button_filter; ?></a></td>
            </tr>
            </tbody>
        </table>


        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="center"><?php echo $column_image; ?></td>
              <td class="left"><?php if ($sort == 'pd.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.model') { ?>
                <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.price') { ?>
                <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'p.quantity') { ?>
                <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?></td>
                <td width="230"><?php echo $lang_markets; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($products) { ?>
            <?php foreach ($products as $product) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($product['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                <?php } ?></td>
              <td class="center"><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" /></td>
              <td class="left"><a href="<?php echo $product['edit']; ?>"><?php echo $product['name']; ?></a></td>
              <td class="left"><?php echo $product['model']; ?></td>
              <td class="left"><?php if ($product['special']) { ?>
                <span style="text-decoration: line-through;"><?php echo $product['price']; ?></span><br/>
                <span style="color: #b00;"><?php echo $product['special']; ?></span>
                <?php } else { ?>
                <?php echo $product['price']; ?>
                <?php } ?></td>
              <td class="right">
                <?php if($product['has_option'] == 0){ ?>
                <?php if ($product['quantity'] <= 0) { ?>
                    <span style="color: #FF0000;"><?php echo $product['quantity']; ?></span>
                <?php } elseif ($product['quantity'] <= 5) { ?>
                    <span style="color: #FFA500;"><?php echo $product['quantity']; ?></span>
                <?php } else { ?>
                    <span style="color: #008000;"><?php echo $product['quantity']; ?></span>
                <?php } }else{ ?>
                    <span style="color: #000000;"><?php echo $product['vCount']; ?> variations</span><br />
                    <span style="color: #000000;"><?php echo $product['vsCount']; ?> stock</span>
                <?php } ?>
              </td>
              <td class="left"><?php echo $product['status']; ?></td>
                <td>
                    <?php foreach ($product['markets'] as $market) { ?>
                    <div style="display:none;" id="tooltip<?php echo $product['product_id']; ?>"></div>
                    <?php if($market['href'] != ''){ ?>
                    <a href="<?php echo $market['href']; ?>" title="<?php echo $market['text']; ?>"><img width="45" height="45" style="margin-right:8px;" src="<?php echo $market['img']; ?>" onMouseOut="hideTooltip('tooltip<?php echo $product['product_id']; ?>')" onMouseOver="showTooltip('tooltip<?php echo $product['product_id']; ?>', '<?php echo $market['name']; ?>', '<?php echo $market['text']; ?>')" /></a>
                    <?php }else{ ?>
                    <img width="45" height="45" style="margin-right:8px;" src="<?php echo $market['img']; ?>" onMouseOut="hideTooltip('tooltip<?php echo $product['product_id']; ?>')" onMouseOver="showTooltip('tooltip<?php echo $product['product_id']; ?>', '<?php echo $market['name']; ?>', '<?php echo $market['text']; ?>')"/>
                    <?php } ?>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=extension/openbay/itemList&token=<?php echo $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_model = $('input[name=\'filter_model\']').attr('value');
	
	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}

    var filter_price = $('input[name=\'filter_price\']').attr('value');

    if (filter_price) {
        url += '&filter_price=' + encodeURIComponent(filter_price);
    }

    var filter_price_to = $('input[name=\'filter_price_to\']').attr('value');

    if (filter_price) {
        url += '&filter_price_to=' + encodeURIComponent(filter_price_to);
    }

    var filter_quantity = $('input[name=\'filter_quantity\']').attr('value');

    if (filter_quantity) {
        url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
    }

    var filter_quantity_to = $('input[name=\'filter_quantity_to\']').attr('value');

    if (filter_quantity_to) {
        url += '&filter_quantity_to=' + encodeURIComponent(filter_quantity_to);
    }

    var filter_status = $('select[name=\'filter_status\']').attr('value');

    if (filter_status != '*') {
        url += '&filter_status=' + encodeURIComponent(filter_status);
    }

    var filter_sku = $('input[name=\'filter_sku\']:checked').attr('value');

    if (filter_sku) {
        url += '&filter_sku=' + encodeURIComponent(filter_sku);
    }

    var filter_desc = $('input[name=\'filter_desc\']:checked').attr('value');

    if (filter_desc) {
        url += '&filter_desc=' + encodeURIComponent(filter_desc);
    }

    var filter_category = $('select[name=\'filter_category\']').attr('value');

    if (filter_category) {
        url += '&filter_category=' + encodeURIComponent(filter_category);
    }

    var filter_manufacturer = $('select[name=\'filter_manufacturer\']').attr('value');

    if (filter_manufacturer) {
        url += '&filter_manufacturer=' + encodeURIComponent(filter_manufacturer);
    }

    var filter_marketplace = $('select[name=\'filter_marketplace\']').attr('value');

    if (filter_marketplace) {
        url += '&filter_marketplace=' + encodeURIComponent(filter_marketplace);
    }

    location = url;
}
//--></script>
<script type="text/javascript">
    <?php  if ($this->config->get('openbay_status') == '1') { ?>
        $('.buttons').prepend('<a onclick="bulkUpload();" class="button"><span><?php echo $lang_bulk_btn; ?></span></a>');

        function bulkUpload(){
            $('#form').attr('action', 'index.php?route=openbay/openbay/createBulk&token=<?php echo $this->request->get['token']; ?>');
            $('#form').submit();
        }
    <?php } ?>
</script>
<?php echo $footer; ?>