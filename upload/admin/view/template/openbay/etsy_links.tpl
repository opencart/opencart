<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default" id="btn-cancel"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-plus fa-lg"></i> <?php echo $text_new_link; ?></h3>
      </div>
      <div class="panel-body">
        <div class="alert alert-success" id="alert-link-save" style="display:none;"><i class="fa fa-check fa-lg" style="color:green"></i> <?php echo $text_link_saved; ?></div>
        <div class="alert alert-danger" id="alert-link-error" style="display:none;"></div>
        <div class="well">
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
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-link fa-lg"></i> <?php echo $text_current_links; ?></h3>
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th class="text-left"><?php echo $column_product; ?></th>
              <th class="text-center"><?php echo $column_item_id; ?></th>
              <th class="text-center"><?php echo $column_store_stock; ?></th>
              <th class="text-center"><?php echo $column_status; ?></th>
              <th class="text-center"><?php echo $column_action; ?></th>
            </tr>
          </thead>
          <tbody id="show-linked-items">
            <?php if (!empty($items)) { ?>
              <?php foreach ($items as $id => $item) { ?>
                <tr id="row-<?php echo $item['etsy_listing_id']; ?>">
                  <td class="text-left"><a href="<?php echo $item['link_edit']; ?>" target="_BLANK"><?php echo $item['name']; ?></a></td>
                  <td class="text-center"><a href="<?php echo $item['link_etsy']; ?>" target="_BLANK"><?php echo $item['etsy_item_id']; ?></a></td>
                  <td class="text-center"><?php echo $item['quantity']; ?></td>
                  <td class="text-center"><?php if ($item['status'] == 1) { ?>
                    <i class="fa fa-check" style="color: green;"></i>
                    <?php } else { ?>
                    <i class="fa fa-times" style="color: red;"></i>
                    <?php } ?></td>
                  <td class="text-center"><button class="btn btn-danger" id="row-delete-btn-<?php echo $item['etsy_listing_id']; ?>" onclick="deleteLink('<?php echo $item['etsy_listing_id']; ?>')"><i class="fa fa-times"></i></button></td>
                </tr>
              <?php } ?>
            <?php } else { ?>
            <tr>
              <td colspan="5" class="text-center"><?php echo $text_no_links; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        </div>
        <div class="pagination"><?php echo $pagination; ?></div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
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
        $('#alert-link-save').hide();
        $('#alert-link-error').hide().empty();
        $('#button-submit-link').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
      },
      success: function(json) {
        if (json.error == false) {
          $('#input-product-id').val('');
          $('#input-name').val('');
          $('#input-etsy-id').val('');
          $('#alert-link-save').show();
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
        $('#row-delete-btn-'+etsy_link_id).empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
      },
      success: function(json) {
        if (json.error == false) {
          $('#row-'+etsy_link_id).remove();
        } else {
          $('#row-delete-btn-'+etsy_link_id).empty().html('<i class="fa fa-times fa-lg" style="color:red;"></i>').removeAttr('disabled');
          $('#alert-link-error').html('<i class="fa fa-times fa-lg"></i> '+json.error).show();
        }
      },
      failure: function() {
        $('#row-delete-btn-'+etsy_link_id).empty().html('<i class="fa fa-times fa-lg"></i>').removeAttr('disabled');
      }
    });
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

  $(document).ready(function() {

  });
//--></script>
<?php echo $footer; ?>