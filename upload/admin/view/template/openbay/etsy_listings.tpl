<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="well">
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label class="control-label" for="input-keywords"><?php echo $entry_keywords; ?></label>
            <input type="text" name="keywords" value="<?php echo isset($filter['keywords']) ? $filter['keywords'] : ''; ?>" placeholder="<?php echo $entry_keywords; ?>" id="input-keywords" class="form-control" />
            <span class="help-block"><?php echo $help_keywords; ?></span> </div>
          <div class="form-group">
            <label class="control-label" for="input-limit"><?php echo $entry_limit; ?></label>
            <select name="limit" id="input-limit" class="form-control">
              <option value="1"<?php if ($filter['limit'] == 1) { echo ' selected'; } ?>>1</option>
              <option value="10"<?php if ($filter['limit'] == 10) { echo ' selected'; } ?>>10</option>
              <option value="50"<?php if ($filter['limit'] == 50) { echo ' selected'; } ?>>50</option>
            </select>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
            <select name="status" id="input-status" class="form-control">
              <option value="active"<?php if ($filter['status'] == 'active') { echo ' selected'; } ?>>Active</option>
              <option value="inactive"<?php if ($filter['status'] == 'inactive') { echo ' selected'; } ?>>Inactive</option>
              <option value="draft"<?php if ($filter['status'] == 'draft') { echo ' selected'; } ?>>Draft</option>
              <option value="expired"<?php if ($filter['status'] == 'expired') { echo ' selected'; } ?>>Expired</option>
            </select>
          </div>
          <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
        </div>
      </div>
    </div>
    <div class="alert alert-danger" id="alert-error" style="display:none;"></div>
    <div class="well" style="display:none;" id="link-well">
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
            <input type="hidden" name="add_link_product_id" value="" id="input-product-id"/>
            <input type="text" name="add_link_product" value="" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label class="control-label" for="input-etsy-id"><?php echo $entry_etsy_id; ?></label>
            <input type="text" name="add_link_etsy_id" value="" placeholder="<?php echo $entry_etsy_id; ?>" id="input-etsy-id" class="form-control" />
          </div>
          <a onclick="addLink();" class="btn btn-primary pull-right" id="button-submit-link"><i class="fa fa-check"></i> <?php echo $button_save; ?></a> </div>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left"><?php echo $column_listing_id; ?></td>
            <td class="text-left"><?php echo $column_title; ?></td>
            <td class="text-center"><?php echo $column_listing_qty; ?></td>
            <td class="text-center"><?php echo $column_store_qty; ?></td>
            <td class="text-center"><?php echo $column_link_status; ?></td>
            <td class="text-center"><?php echo $column_status; ?></td>
            <td class="text-right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($listings) { ?>
          <?php foreach ($listings as $listing) { ?>
          <tr>
            <td class="text-left"><?php echo $listing['listing']['listing_id']; ?></td>
            <td class="text-left"><?php echo $listing['listing']['title']; ?></td>
            <td class="text-center"><?php echo $listing['listing']['quantity']; ?></td>
            <?php if (!empty($listing['link'])) { ?>
            <td class="text-center"><?php echo $listing['link']['quantity']; ?></td>
            <td class="text-center"><i class="fa fa-check" style="color: green;"></i></td>
            <td class="text-center"><?php
                    if ($listing['link']['quantity'] != $listing['listing']['quantity']) {
                      echo $text_status_stock;
                    } else {
                      echo $text_status_ok;
                    }
                   ?></td>
            <?php } else { ?>
            <td class="text-center"><i class="fa fa-minus"></i></td>
            <td class="text-center"><i class="fa fa-times" style="color: red;"></i></td>
            <td class="text-center"><?php echo $text_status_nolink; ?></td>
            <?php } ?>
              </td>
            <td class="text-right"><?php if (in_array('activate_item', $listing['actions'])) { ?>
              <button data-toggle="tooltip" title="<?php echo $text_activate; ?>" class="btn btn-primary" onclick="activateListing('<?php echo $listing['listing']['listing_id']; ?>');" id="btn-activate-<?php echo $listing['listing']['listing_id']; ?>"><i class="fa fa-plus"></i></button>
              <?php } ?>
              <?php if (in_array('add_link', $listing['actions'])) { ?>
              <button data-toggle="tooltip" title="<?php echo $text_add_link; ?>" class="btn btn-primary" onclick="showLinkOption('<?php echo $listing['listing']['listing_id']; ?>');"><i class="fa fa-link"></i></button>
              <?php } ?>
              <?php if (in_array('delete_link', $listing['actions'])) { ?>
              <button data-toggle="tooltip" title="<?php echo $text_delete_link; ?>" class="btn btn-danger" id="btn-delete-<?php echo $listing['link']['etsy_listing_id']; ?>" onclick="deleteLink('<?php echo $listing['link']['etsy_listing_id']; ?>');"><i class="fa fa-unlink"></i></button>
              <?php } ?>
              <?php if (in_array('end_item', $listing['actions'])) { ?>
              <button data-toggle="tooltip" title="<?php echo $text_delete; ?>" class="btn btn-danger" onclick="endListing('<?php echo $listing['listing']['listing_id']; ?>');" id="btn-end-<?php echo $listing['link']['etsy_listing_id']; ?>"><i class="fa fa-times"></i></button>
              <button data-toggle="tooltip" title="<?php echo $text_deactivate; ?>" class="btn btn-danger" onclick="deactivateListing('<?php echo $listing['listing']['listing_id']; ?>');" id="btn-deactivate-<?php echo $listing['listing']['listing_id']; ?>"><i class="fa fa-ban"></i></button>
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <div class="row">
      <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
      <div class="col-sm-6 text-right"><?php echo $results; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var url = 'index.php?route=openbay/etsy_product/listings&token=<?php echo $token; ?>&page=<?php echo $filter["page"]; ?>';

var filter_keywords = $('input[name=\'keywords\']').val();

if (filter_keywords) {
  url += '&keywords=' + encodeURIComponent(filter_keywords);
}

var filter_status = $('input[name=\'status\']').val();

if (filter_status) {
  url += '&status=' + encodeURIComponent(filter_status);
}

var filter_limit = $('input[name=\'limit\']').val();

if (filter_limit) {
  url += '&limit=' + encodeURIComponent(filter_limit);
}

$('#button-filter').on('click', function() {
  var url = 'index.php?route=openbay/etsy_product/listings&token=<?php echo $token; ?>';

  var status = $('select[name=\'status\']').val();

  url += '&status=' + encodeURIComponent(status);

  var limit = $('select[name=\'limit\']').val();

  url += '&limit=' + encodeURIComponent(limit);

  var keywords = $('input[name=\'keywords\']').val();

  if (keywords != '') {
    url += '&keywords=' + encodeURIComponent(keywords);
  }

  location = url;
});

function showLinkOption(id) {
  $('#input-etsy-id').val(id);
  $('#link-well').slideDown();
}

function addLink() {
  var product_id = $('#input-product-id').val();
  var etsy_id = $('#input-etsy-id').val();

  if (product_id == '') {
    alert('<?php echo $error_product_id; ?>');
    return false;
  }

  if (etsy_id == '') {
    alert('<?php echo $error_etsy_id; ?>');
    return false;
  }

  $.ajax({
    url: 'index.php?route=openbay/etsy_product/addLink&token=<?php echo $token; ?>',
    dataType: 'json',
    method: 'POST',
    data: { 'product_id' : product_id, 'etsy_id' : etsy_id },
    beforeSend: function() {
      $('#alert-link-error').hide().empty();
      $('#button-submit-link').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
    },
    success: function(json) {
      if (json.error == false) {
        url += '&link_added=1';
        location = url;
      } else {
        $('#alert-link-error').html('<i class="fa fa-times fa-lg" style="color:red;"></i> '+json.error).show();
      }

      $('#button-submit-link').empty().html('<i class="fa fa-check"></i> <?php echo $button_save; ?>').removeAttr('disabled');
    },
    failure: function() {
      $('#button-submit').empty().html('<i class="fa fa-check"></i> <?php echo $button_save; ?>').removeAttr('disabled');
    }
  });
}

function deleteLink(etsy_link_id) {
  $.ajax({
    url: 'index.php?route=openbay/etsy_product/deleteLink&token=<?php echo $token; ?>',
    dataType: 'json',
    method: 'POST',
    data: { 'etsy_link_id' : etsy_link_id },
    beforeSend: function() {
      $('#btn-delete-'+etsy_link_id).empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
    },
    success: function(json) {
      if (json.error == false) {
        url += '&link_deleted=1';
        location = url;
      } else {
        $('#btn-delete-'+etsy_link_id).empty().html('<i class="fa fa-times fa-lg" style="color:red;"></i>').removeAttr('disabled');
        $('#alert-error').html('<i class="fa fa-times fa-lg"></i> '+json.error).show();
      }
    },
    failure: function() {
      $('#btn-delete-'+etsy_link_id).empty().html('<i class="fa fa-times fa-lg"></i>').removeAttr('disabled');
    }
  });
}

function endListing(etsy_item_id) {
  var pass = confirm("<?php echo $text_confirm_end; ?>");

  if (pass == true) {
    $.ajax({
      url: 'index.php?route=openbay/etsy_product/endlisting&token=<?php echo $token; ?>',
      dataType: 'json',
      method: 'POST',
      data: { 'etsy_item_id' : etsy_item_id },
      beforeSend: function() {
        $('#btn-end-'+etsy_item_id).empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
      },
      success: function(json) {
        if (json.error == false) {
          url += '&item_ended=1';
          location = url;
        } else {
          $('#btn-end-'+etsy_item_id).empty().html('<i class="fa fa-times fa-lg" style="color:red;"></i>').removeAttr('disabled');
          $('#alert-error').html('<i class="fa fa-times fa-lg"></i> '+json.error).show();
        }
      },
      failure: function() {
        $('#btn-end-'+etsy_item_id).empty().html('<i class="fa fa-times fa-lg"></i>').removeAttr('disabled');
      }
    });
  }
}

function deactivateListing(etsy_item_id) {
  var pass = confirm("<?php echo $text_confirm_deactivate; ?>");

  if (pass == true) {
    $.ajax({
      url: 'index.php?route=openbay/etsy_product/deactivatelisting&token=<?php echo $token; ?>',
      dataType: 'json',
      method: 'POST',
      data: { 'etsy_item_id' : etsy_item_id },
      beforeSend: function() {
        $('#btn-deactivate-'+etsy_item_id).empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
      },
      success: function(json) {
        if (json.error == false) {
          url += '&item_deactivated=1';
          location = url;
        } else {
          $('#btn-deactivate-'+etsy_item_id).empty().html('<i class="fa fa-times fa-lg" style="color:red;"></i>').removeAttr('disabled');
          $('#alert-error').html('<i class="fa fa-times fa-lg"></i> '+json.error).show();
        }
      },
      failure: function() {
        $('#btn-deactivate-'+etsy_item_id).empty().html('<i class="fa fa-times fa-lg"></i>').removeAttr('disabled');
      }
    });
  }
}

function activateListing(etsy_item_id) {
  var pass = confirm("<?php echo $text_confirm_activate; ?>");

  if (pass == true) {
    $.ajax({
      url: 'index.php?route=openbay/etsy_product/activatelisting&token=<?php echo $token; ?>',
      dataType: 'json',
      method: 'POST',
      data: { 'etsy_item_id' : etsy_item_id },
      beforeSend: function() {
        $('#btn-activate-'+etsy_item_id).empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
      },
      success: function(json) {
        if (json.error == false) {
          url += '&item_activated=1';
          location = url;
        } else {
          $('#btn-activate-'+etsy_item_id).empty().html('<i class="fa fa-times fa-lg" style="color:red;"></i>').removeAttr('disabled');
          $('#alert-error').html('<i class="fa fa-times fa-lg"></i> '+json.error).show();
        }
      },
      failure: function() {
        $('#btn-activate-'+etsy_item_id).empty().html('<i class="fa fa-times fa-lg"></i>').removeAttr('disabled');
      }
    });
  }
}

$('input[name=\'add_link_product\']').autocomplete({
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
    $('input[name=\'add_link_product\']').val(item['label']);
    $('#input-product-id').val(item['value']);
  }
});
//--></script> 
<?php echo $footer; ?>