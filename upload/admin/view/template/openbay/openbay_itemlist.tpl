<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

    <?php if ($link_amazon_eu_bulk || $link_amazon_us_bulk || $link_ebay_bulk) { ?>
      <div class="well" id="bulk-buttons">
        <div class="row">
          <div class="col-sm-12 text-right">
            <?php if ($link_amazon_eu_bulk) { ?>
            <a class="btn btn-primary" href="<?php echo $link_amazon_eu_bulk; ?>"><i class="fa fa-cloud-upload fa-lg"></i> <?php echo $button_amazon_eu_bulk; ?></a>
            <?php } ?>
            <?php if ($link_amazon_us_bulk) { ?>
            <a class="btn btn-primary" href="<?php echo $link_amazon_us_bulk; ?>"><i class="fa fa-cloud-upload fa-lg"></i> <?php echo $button_amazon_us_bulk ?></a>
            <?php } ?>
            <?php  if ($link_ebay_bulk) { ?>
            <a class="btn btn-primary" id="button-ebay-bulk"><i class="fa fa-cloud-upload fa-lg"></i> <?php echo $button_ebay_bulk; ?></a>
            <?php } ?>
          </div>
        </div>
      </div>
    <?php } ?>
    <div class="well">
      <div class="row">
        <div class="col-sm-4">
          <div class="form-group">
            <label class="control-label" for="filter_name"><?php echo $entry_title; ?></label>
            <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_title; ?>" id="filter_name" class="form-control" />
          </div>
          <div class="form-group">
            <label class="control-label" for="filter_model"><?php echo $entry_model; ?></label>
            <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $entry_model; ?>" id="filter_model" class="form-control" />
          </div>
          <div class="form-group">
            <label class="control-label" for="filter_manufacturer"><?php echo $entry_manufacturer; ?></label>
            <select name="filter_manufacturer" id="filter_manufacturer" class="form-control">
              <option value=""></option>
              <?php foreach($manufacturer_list as $man) { ?>
              <option value="<?php echo $man['manufacturer_id']; ?>"<?php echo ($filter_manufacturer == $man["manufacturer_id"] ? " selected" : ""); ?>><?php echo $man['name']; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            <label class="control-label" for="filter_marketplace"><?php echo $entry_status_marketplace; ?></label>
            <select name="filter_marketplace" id="filter_marketplace" class="form-control">
              <option value="all" <?php echo (!isset($filter_marketplace) || $filter_marketplace == 'all' ? ' selected' : ''); ?>><?php echo $text_status_all; ?></option>
              <?php if ($marketplace_statuses['ebay']) { ?>
              <option value="ebay_active" <?php echo ($filter_marketplace == 'ebay_active' ? ' selected' : ''); ?>><?php echo $text_status_ebay_active; ?></option>
              <option value="ebay_inactive" <?php echo ($filter_marketplace == 'ebay_inactive' ? ' selected' : ''); ?>><?php echo $text_status_ebay_inactive; ?></option>
              <?php } ?>
              <?php if ($marketplace_statuses['amazon']) { ?>
              <option value="amazon_saved" <?php echo ($filter_marketplace == 'amazon_saved' ? ' selected' : ''); ?>><?php echo $text_status_amazoneu_saved; ?></option>
              <option value="amazon_uploaded" <?php echo ($filter_marketplace == 'amazon_uploaded' ? ' selected' : ''); ?>><?php echo $text_status_amazoneu_processing; ?></option>
              <option value="amazon_ok" <?php echo ($filter_marketplace == 'amazon_ok' ? ' selected' : ''); ?>><?php echo $text_status_amazoneu_active; ?></option>
              <option value="amazon_unlisted" <?php echo ($filter_marketplace == 'amazon_unlisted' ? ' selected' : ''); ?>><?php echo $text_status_amazoneu_notlisted; ?></option>
              <option value="amazon_error" <?php echo ($filter_marketplace == 'amazon_error' ? ' selected' : ''); ?>><?php echo $text_status_amazoneu_failed; ?></option>
              <option value="amazon_linked" <?php echo ($filter_marketplace == 'amazon_linked' ? ' selected' : ''); ?>><?php echo $text_status_amazoneu_linked; ?></option>
              <option value="amazon_not_linked" <?php echo ($filter_marketplace == 'amazon_not_linked' ? ' selected' : ''); ?>><?php echo $text_status_amazoneu_notlinked; ?></option>
              <?php } ?>
              <?php if ($marketplace_statuses['amazonus']) { ?>
              <option value="amazonus_saved" <?php echo ($filter_marketplace == 'amazonus_saved' ? ' selected' : ''); ?>><?php echo $text_status_amazonus_saved; ?></option>
              <option value="amazonus_uploaded" <?php echo ($filter_marketplace == 'amazonus_uploaded' ? ' selected' : ''); ?>><?php echo $text_status_amazonus_processing; ?></option>
              <option value="amazonus_ok" <?php echo ($filter_marketplace == 'amazonus_ok' ? ' selected' : ''); ?>><?php echo $text_status_amazonus_active; ?></option>
              <option value="amazonus_unlisted" <?php echo ($filter_marketplace == 'amazonus_unlisted' ? ' selected' : ''); ?>><?php echo $text_status_amazonus_notlisted; ?></option>
              <option value="amazonus_error" <?php echo ($filter_marketplace == 'amazonus_error' ? ' selected' : ''); ?>><?php echo $text_status_amazonus_failed; ?></option>
              <option value="amazonus_linked" <?php echo ($filter_marketplace == 'amazonus_linked' ? ' selected' : ''); ?>><?php echo $text_status_amazonus_linked; ?></option>
              <option value="amazonus_not_linked" <?php echo ($filter_marketplace == 'amazonus_not_linked' ? ' selected' : ''); ?>><?php echo $text_status_amazonus_notlinked; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label class="control-label"><?php echo $entry_status; ?></label>
            <select name="filter_status" class="form-control">
              <option value="*"></option>
              <?php if ($filter_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <?php } ?>
              <?php if (($filter_status !== null) && !$filter_status) { ?>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label class="control-label" for="filter_category"><?php echo $entry_category; ?></label>
            <select name="filter_category" id="filter_category" class="form-control">
              <option value=""></option>
              <option value="none"><?php echo $text_category_missing; ?></option>
              <?php foreach($category_list as $cat) { ?>
              <option value="<?php echo $cat['category_id']; ?>"<?php echo ($filter_category == $cat["category_id"] ? " selected" : ""); ?>><?php echo $cat['name']; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            <div class="row">
              <div class="col-sm-12">
                <label class="control-label"><?php echo $entry_stock_range; ?></label>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" class="form-control" placeholder="<?php echo $text_min; ?>" id="input-quantity" />
              </div>
              <div class="col-sm-6">
                <input type="text" name="filter_quantity_to" value="<?php echo $filter_quantity_to; ?>"  class="form-control" placeholder="<?php echo $text_max; ?>" id="input-quantity-to" />
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-sm-12">
                <label class="control-label"><?php echo $entry_populated; ?></label>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-3">
                <label class="control-label"><?php echo $entry_sku; ?></label>
              </div>
              <div class="col-sm-3">
                <input type="checkbox" name="filter_sku" id="filter_sku" value="1" <?php if ($filter_sku == 1) { echo 'checked="checked" ';} ?>/>
              </div>
              <div class="col-sm-3">
                <label class="control-label"><?php echo $entry_description; ?></label>
              </div>
              <div class="col-sm-3">
                <input type="checkbox" name="filter_desc" id="filter_desc" value="1" <?php if ($filter_desc == 1) { echo 'checked="checked" ';} ?>/>
              </div>
            </div>
          </div>
          <a onclick="filter();" class="btn btn-primary pull-right" data-toggle="tooltip" title="<?php echo $button_filter; ?>"><i class="fa fa-filter"></i></a>
        </div>
      </div>
    </div>
    <form method="post" id="form">
        <table class="table">
          <thead>
            <tr>
              <td width="1" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="text-center"><?php echo $column_image; ?></td>
              <td class="text-left"><?php if ($sort == 'pd.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?></td>
              <td class="text-left"><?php if ($sort == 'p.model') { ?>
                <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                <?php } ?></td>
              <td class="text-left"><?php if ($sort == 'p.price') { ?>
                <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                <?php } ?></td>
              <td class="text-right"><?php if ($sort == 'p.quantity') { ?>
                <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
                <?php } ?></td>
              <td class="text-left"><?php if ($sort == 'p.status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?></td>
                <td width="230"><?php echo $text_markets; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($products) { ?>
              <?php foreach ($products as $product) { ?>
                <tr>
                  <td class="text-center">
                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                  </td>
                  <td class="text-center">
                    <?php if ($product['image']) { ?>
                      <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail" />
                      <?php } else { ?>
                      <span class="img-thumbnail"><i class="fa fa-camera fa-5x"></i></span>
                      <?php } ?>
                  </td>
                  <td class="text-left"><a href="<?php echo $product['edit']; ?>"><?php echo $product['name']; ?></a></td>
                  <td class="text-left"><?php echo $product['model']; ?></td>
                  <td class="text-left">
                    <?php if ($product['special']) { ?>
                      <span style="text-decoration: line-through;"><?php echo $product['price']; ?></span><br/>
                      <div class="text-danger"><?php echo $product['special']; ?></div>
                    <?php } else { ?>
                      <?php echo $product['price']; ?>
                    <?php } ?>
                  </td>
                  <td class="text-right">
                    <?php if ($product['has_option'] == 0) { ?>
                      <?php if ($product['quantity'] <= 0) { ?>
                        <span class="label label-danger"><?php echo $product['quantity']; ?></span>
                      <?php } elseif ($product['quantity'] <= 5) { ?>
                        <span class="label label-warning"><?php echo $product['quantity']; ?></span>
                      <?php } else { ?>
                        <span class="label label-success"><?php echo $product['quantity']; ?></span>
                      <?php } ?>
                    <?php } else { ?>
                      <span class="label label-info"><?php echo $product['vCount']; ?> <?php echo $text_variations; ?></span><br />
                      <span class="label label-info"><?php echo $product['vsCount']; ?> <?php echo $text_variations_stock; ?></span>
                    <?php } ?>
                  </td>
                  <td class="text-left"><?php echo $product['status']; ?></td>
                  <td>
                    <?php foreach ($product['markets'] as $market) { ?>
                      <?php if ($market['status'] == 1) { ?>
                        <a href="<?php echo $market['href']; ?>" data-toggle="tooltip" title="<?php echo $market['text']; ?>" class="btn btn-block btn-sm btn-success"><?php echo $market['name']; ?></a>
                      <?php } elseif ($market['status'] == 2) { ?>
                        <a href="<?php echo $market['href']; ?>" data-toggle="tooltip" title="<?php echo $market['text']; ?>" class="btn btn-block btn-sm btn-danger"><?php echo $market['name']; ?></a>
                      <?php } elseif ($market['status'] == 3) { ?>
                        <?php if ($market['href'] != '') { ?>
                          <a href="<?php echo $market['href']; ?>" data-toggle="tooltip" title="<?php echo $market['text']; ?>" class="btn btn-block btn-sm btn-info"><?php echo $market['name']; ?></a>
                        <?php } else { ?>
                          <a disable="disable" data-toggle="tooltip" title="<?php echo $market['text']; ?>" class="btn btn-info btn-block btn-sm"><?php echo $market['name']; ?></a>
                        <?php } ?>
                      <?php } else { ?>
                        <a href="<?php echo $market['href']; ?>" data-toggle="tooltip" title="<?php echo $market['text']; ?>" class="btn btn-block btn-sm btn-default"><?php echo $market['name']; ?></a>
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
      </form>
    <div class="row">
      <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
      <div class="col-sm-6 text-right"><?php echo $results; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
  function filter() {
      url = 'index.php?route=extension/openbay/itemList&token=<?php echo $token; ?>';

      var filter_name = $('input[name=\'filter_name\']').val();

      if (filter_name) {
        url += '&filter_name=' + encodeURIComponent(filter_name);
      }

      var filter_model = $('input[name=\'filter_model\']').val();

      if (filter_model) {
        url += '&filter_model=' + encodeURIComponent(filter_model);
      }

      var filter_price = $('input[name=\'filter_price\']').val();

      if (filter_price) {
          url += '&filter_price=' + encodeURIComponent(filter_price);
      }

      var filter_price_to = $('input[name=\'filter_price_to\']').val();

      if (filter_price) {
          url += '&filter_price_to=' + encodeURIComponent(filter_price_to);
      }

      var filter_quantity = $('input[name=\'filter_quantity\']').val();

      if (filter_quantity) {
          url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
      }

      var filter_quantity_to = $('input[name=\'filter_quantity_to\']').val();

      if (filter_quantity_to) {
          url += '&filter_quantity_to=' + encodeURIComponent(filter_quantity_to);
      }

      var filter_status = $('select[name=\'filter_status\']').find(":selected").val();

      if (filter_status != '*') {
          url += '&filter_status=' + encodeURIComponent(filter_status);
      }

      var filter_sku = $('input[name=\'filter_sku\']:checked').val();

      if (filter_sku) {
          url += '&filter_sku=' + encodeURIComponent(filter_sku);
      }

      var filter_desc = $('input[name=\'filter_desc\']:checked').val();

      if (filter_desc) {
          url += '&filter_desc=' + encodeURIComponent(filter_desc);
      }

      var filter_category = $('select[name=\'filter_category\']').find(":selected").val();

      if (filter_category) {
          url += '&filter_category=' + encodeURIComponent(filter_category);
      }

      var filter_manufacturer = $('select[name=\'filter_manufacturer\']').find(":selected").val();

      if (filter_manufacturer) {
          url += '&filter_manufacturer=' + encodeURIComponent(filter_manufacturer);
      }

      var filter_marketplace = $('select[name=\'filter_marketplace\']').find(":selected").val();

      if (filter_marketplace) {
          url += '&filter_marketplace=' + encodeURIComponent(filter_marketplace);
      }

      location = url;
  }

  $('#button-ebay-bulk').bind('click', function() {
    var request_data = $('input[name="selected[]"]:checked').serialize();

    if (request_data != '') {
      $('#form').attr('action', 'index.php?route=openbay/ebay/createBulk&token=<?php echo $token; ?>').submit();
    } else {
      $('#bulk-buttons').before('<div class="alert alert-danger"><?php echo $error_select_items; ?></div>');
    }
  });
//--></script>
<?php echo $footer; ?>