<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
    <div class="alert alert-info">
      <p><?php echo $text_link_desc1; ?></p>
      <p><?php echo $text_link_desc2; ?></p>
      <p><?php echo $text_link_desc3; ?></p>
      <p><?php echo $text_link_desc4; ?></p>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h1 class="panel-title"><i class="fa fa-unlink fa-lg"></i> <?php echo $text_unlinked_items; ?></h1>
      </div>
      <div class="panel-body">
        <p><?php echo $text_text_unlinked_desc; ?></p>
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="filter_title"><?php echo $text_filter_title; ?></label>
                <input type="text" name="filter_title" value="" placeholder="<?php echo $text_filter_title; ?>" id="filter_title" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label"><?php echo $text_filter_range; ?></label>
                <div class="row">
                  <div class="col-sm-6">
                    <input type="text" name="filter_qty_min" value="" class="form-control" placeholder="<?php echo $text_filter_range_from; ?>" id="filter-qty-min" />
                  </div>
                  <div class="col-sm-6">
                    <input type="text" name="filter_qty_max" value=""  class="form-control" placeholder="<?php echo $text_filter_range_to; ?>" id="filter-qty-max" />
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label"><?php echo $text_filter_var; ?></label>
                <select name="filter_variant" class="form-control" id="filter-variant">
                  <option value="1"><?php echo $text_yes; ?></option>
                  <option value="0"><?php echo $text_no; ?></option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th class="text-left"></th>
              <th class="text-left"><?php echo $column_item_id; ?></th>
              <th class="text-left"><?php echo $column_listing_title; ?></th>
              <th class="text-left"><?php echo $column_product_auto; ?></th>
              <th class="text-center"><?php echo $column_stock_available; ?></th>
              <th class="text-center"><?php echo $column_allocated; ?></th>
              <th class="text-center"><?php echo $column_ebay_stock; ?></th>
              <th class="text-center"><?php echo $column_variants; ?></th>
              <th class="text-center"><?php echo $column_action; ?></th>
            </tr>
          </thead>
          <tbody id="ebay-listings">
            <tr id="fetching-ebay-items">
              <td class="text-center" colspan="9"><?php echo $text_text_unlinked_info; ?></td>
            </tr>
          </tbody>
        </table>
        <div class="buttons"> <a class="btn btn-primary" id="check-unlinked-items"><?php echo $button_check_unlinked; ?></a>
          <input type="hidden" name="unlinked_page" id="unlinked-page" value="1" />
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h1 class="panel-title"><i class="fa fa-link fa-lg"></i> <?php echo $text_linked_items; ?></h1>
      </div>
      <div class="panel-body">
        <p><?php echo $text_text_linked_desc; ?></p>
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th class="text-left"><?php echo $column_product; ?></th>
              <th class="text-center"><?php echo $column_item_id; ?></th>
              <th class="text-center"><?php echo $column_allocated; ?></th>
              <th class="text-center"><?php echo $column_stock_available; ?></th>
              <th class="text-center"><?php echo $column_stock_reserve; ?></th>
              <th class="text-center"><?php echo $column_ebay_stock; ?></th>
              <th class="text-center"><?php echo $column_variants; ?></th>
              <th class="text-center"><?php echo $column_status; ?></th>
              <th class="text-center"><?php echo $column_action; ?></th>
            </tr>
          </thead>
          <tr>
            <td class="text-left" colspan="8" id="checking-linked-items"><a class="btn btn-primary" id="load-usage"><i class="fa fa-cog fa-lg fa-spin"></i> <?php echo $text_text_loading_items; ?></a></td>
          </tr>
          <tbody style="display:none;" id="show-linked-items">
            <?php foreach ($linked_items as $id => $item) { ?>
          <input type="hidden" name="ebay_qty_<?php echo $id; ?>" value="" id="ebay-qty-<?php echo $id; ?>" />
          <input type="hidden" name="store_qty_<?php echo $id; ?>" value="<?php echo $item['qty']; ?>" id="store-qty-<?php echo $id; ?>" />
          <input type="hidden" name="reserve_qty_<?php echo $id; ?>" value="<?php echo $item['reserve']; ?>" id="reserve-qty-<?php echo $id; ?>" />
          <input type="hidden" name="item_id[]" id="item-id-<?php echo $id; ?>" value="<?php echo $id; ?>" class="item-id"  />
          <input type="hidden" name="product_id[]" id="product-id-<?php echo $id; ?>" value="<?php echo $item['product_id']; ?>" />
          <input type="hidden" name="options" id="options-<?php echo $id; ?>" value="<?php echo (int)$item['options']; ?>" />
          <tr id="row-<?php echo $id; ?>">
            <td class="text-left"><a href="<?php echo $item['link_edit']; ?>" target="_BLANK"><?php echo $item['name']; ?></a></td>
            <td class="text-center"><a href="<?php echo $item['link_ebay']; ?>" target="_BLANK"><?php echo $id; ?></a></td>
            <?php if ($item['options'] == 0) { ?>
            <td class="text-center"><?php echo $item['allocated']; ?></td>
            <td class="text-center"><?php echo $item['qty']; ?></td>
            <td class="text-center"><?php echo $item['reserve']; ?></td>
            <td id="text-qty-<?php echo $id; ?>" class="text-center"></td>
            <td class="text-center"><i class="fa fa-times-circle text-danger"></i></td>
            <?php } else { ?>
            <td class="text-center">-</td>
            <td class="text-center"><?php foreach ($item['options'] as $option) { echo $option['stock'] .' x ' . $option['combi'] . '<br />'; } ?></td>
            <td id="text-qty-<?php echo $id; ?>" class="text-center"></td>
            <td class="text-center" align="center"><i class="fa fa-check-circle text-success"></i></td>
            <?php } ?>
            <td class="text-center" id="text-status-<?php echo $id; ?>"></td>
            <td class="text-center" id="text-buttons-<?php echo $id; ?>"></td>
          </tr>
          <?php } ?>
            </tbody>

        </table>
        <div class="pagination"><?php echo $pagination; ?></div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
  function checkLinkedItems() {
      $.ajax({
          url: 'index.php?route=openbay/ebay/loadLinkedStatus&token=<?php echo $token; ?>',
          data: $('.item-id').serialize(),
          type: 'POST',
          dataType: 'json',
          success: function(json) {
            if (json.data == '') {
              $('#checking-linked-items').hide();
              $('.pagination').hide();
              $('#show-linked-items').html('<tr><td colspan="8" class="text-center"><?php echo $error_no_listings; ?></td></tr>').show();
            } else {
              $.each (json.data, function(key, val) {
                key                 = String(key);
                var product_id      = $('#product-id-'+key).val();
                var store_qty       = $('#store-qty-'+key).val();
                var reserve_qty     = $('#reserve-qty-'+key).val();
                var html_inj        = '';

                if (val.variants == 0) {
                  $('#text-qty-'+key).text(val.qty);
                  $('#ebay-qty-'+key).val(val.qty);

                  if (val.status == 1) {
                    if (val.qty == store_qty || val.qty == reserve_qty) {
                      $('#text-status-'+key).text('OK');
                      $('#row-'+key+' > td').css('background-color', '#E3FFC8');
                      $('#text-buttons-'+key).html('<a href="<?php echo $edit_url; ?>'+product_id+'" class="btn btn-primary"><span><?php echo $button_edit; ?></span></a>');
                    } else {
                      $('#text-status-'+key).text('<?php echo $text_stock_error; ?>');
                      $('#row-'+key+' > td').css('background-color', '#FFD4D4');
                      $('#text-buttons-'+key).html('<a onclick="updateLink('+key+','+val.qty+','+product_id+', '+store_qty+', '+reserve_qty+');" class="btn btn-primary"><span><?php echo $button_resync; ?></a>');
                    }
                  } else {
                    $('#text-status-'+key).text('<?php echo $text_listing_ended; ?>');
                    $('#row-'+key+' > td').css('background-color', '#FFD4D4');
                    $('#text-buttons-'+key).html('<a onclick="removeLink('+product_id+', '+key+');" class="btn btn-danger"><i class="fa fa-minus-circle fa-lg"></i> <?php echo $button_remove_link; ?></a>');
                  }
                } else {
                  $.each (val.variants, function(key1, val1) {
                    html_inj += val1.qty+' x ';
                    $.each (val1.nv.NameValueList, function(key2, val2) {
                        html_inj += val2.Value+' > ';
                    });
                    html_inj += '<br />';
                  });

                  $('#text-qty-'+key).html(html_inj);

                  if (val.status == 0) {
                    $('#text-status-'+key).text('<?php echo $text_listing_ended; ?>');
                    $('#row-'+key+' > td').css('background-color', '#FFD4D4');
                    $('#text-buttons-'+key).html('<a onclick="removeLink('+product_id+', '+key+');" class="btn btn-danger"><i class="fa fa-minus-circle fa-lg"></i> <?php echo $button_remove_link; ?></a>');
                  }
                }
              });

              $('#checking-linked-items').hide();
              $('#show-linked-items').show();
            }
          },
          failure: function() {
              $('#errorBox').text('<?php echo $error_ajax_load; ?>').fadeIn();
          },
          error: function() {
              $('#errorBox').text('<?php echo $error_ajax_load; ?>').fadeIn();
          }
      });
  }

  function removeLink(product_id, id) {
      $.ajax({
          type: 'GET',
          url: 'index.php?route=openbay/ebay/removeItemLink&token=<?php echo $token; ?>&product_id='+product_id,
          dataType: 'json',
          success: function(json) {
              $('#row-'+id).fadeOut('slow');
          },
          error: function (xhr, ajaxOptions, thrownError) {
          if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
        }
      });
  }

  function updateLink(item_id, qty, product_id, store_qty, reserve_qty) {
      var r = confirm("<?php echo $text_alert_stock_local; ?>");
      var button_old = $('#text-buttons-'+item_id).html();

      $('#text-buttons-'+item_id).html('<p class="text-center"><i class="fa fa-cog fa-lg fa-spin"></i></p>');

      if (r == true) {
          $.ajax({
              type: 'GET',
              url: 'index.php?route=openbay/ebay/setProductStock&token=<?php echo $token; ?>&product_id='+product_id,
              dataType: 'json',
              success: function(json) {
                  if (json.error == false) {
                      $('#text-status-'+item_id).text('OK');
                      $('#text-buttons-'+item_id).empty().html('<a href="<?php echo $edit_url; ?>'+product_id+'" class="btn btn-primary"><?php echo $button_edit; ?></a>');
                      $('#row-'+item_id+' > td').css('background-color', '#E3FFC8');
                      $('#l-'+item_id+'-qty-input').val(qty);
                      $('#l-'+item_id+'-qty').val(qty);
                      if (reserve_qty > 0) {
                        $('#text-qty-'+item_id).text(reserve_qty);
                      } else {
                        $('#text-qty-'+item_id).text(store_qty);
                      }
                      $('#reserve-qty-'+item_id).text(reserve_qty);
                  } else {
                      $('#text-buttons-'+item_id).html(button_old);
                      alert(json.msg);
                  }
              },
              failure: function() {
                  $('#text-buttons-'+item_id).html(button_old);
                  alert('<?php echo $error_ajax_load; ?>');
              },
              error: function() {
                  $('#text-buttons-'+item_id).html(button_old);
                  alert('<?php echo $error_ajax_load; ?>');
              }
          });
      }
  }

  function saveListingLink(id) {
      var product_id      = $('#l-'+id+'-pid').val();
      var qty             = $('#l-'+id+'-qty-input').val();
      var ebayqty         = $('#l-'+id+'-qtyebayinput').val();
      var variants        = $('#l-'+id+'-variants').val();

      if (product_id === '') {
          alert('<?php echo $error_link_value; ?>');
          return false;
      }

      if (qty < 1) {
          alert('<?php echo $error_link_no_stock; ?>');
          return false;
      }

      $.ajax({
          url: 'index.php?route=openbay/ebay/saveItemLink&token=<?php echo $token; ?>&pid='+product_id+'&itemId='+id+'&qty='+qty+'&ebayqty='+ebayqty+'&variants='+variants,
          type: 'post',
          dataType: 'json',
          beforeSend: function() {
            $('#l-'+id+'-save-button').html('<i class="fa fa-cog fa-lg fa-spin"></i>');
          },
          success: function(json) {
            $('#row'+id).fadeOut('slow');
            $('#l-'+id+'-save-button').hide();
          },
          error: function (xhr, ajaxOptions, thrownError) {
            if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
          }
      });
  }

  function getProductStock(id, element_id) {
      $.ajax({
          type:'GET',
          dataType: 'json',
          url: 'index.php?route=openbay/ebay/getProductStock&token=<?php echo $token; ?>&pid='+id,
          success: function(data) {
              if (data.variant == 0) {
                  $('#'+element_id+'-qty').text(data.qty);
                  $('#'+element_id+'-qty-input').val(data.qty);
                  $('#'+element_id+'-allocated').text(data.allocated);
                  $('#'+element_id+'-allocatedinput').val(data.allocated);
                  $('#'+element_id+'-subtractinput').val(data.subtract);
                  $('#'+element_id+'-save-button').show();
              } else {
                  var injHtml = '';
                  $.each (data.variant, function(key, val) {
                      injHtml += val.stock+' x '+val.combi+'<br />';
                  });
                  $('#'+element_id+'-qty').html(injHtml);
                  $('#'+element_id+'-save-button').show();
              }
          }
      });
  }

  $('#check-unlinked-items').bind('click', function() {
    var unlinked_page = $('#unlinked-page').val();

    $.ajax({
      url: 'index.php?route=openbay/ebay/loadUnlinked&token=<?php echo $token; ?>&page='+unlinked_page,
      type: 'POST',
      data: { 'filter_title' : $('#filter_title').val(), 'filter_qty_min' : $('#filter-qty-min').val(), 'filter_qty_max' : $('#filter-qty-max').val(), 'filter_variant' : $('#filter-variant').val() },
      dataType: 'json',
      beforeSend: function() {
        $('#fetching-ebay-items').hide();
        $('#check-unlinked-items').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
        $('.alert-warning').remove();
      },
      success: function(json) {
        if (json.data.items === null) {
          $('#ebay-listings').append('<tr><td colspan="7"><p><?php echo $error_no_listings; ?></p></td></tr>');
        } else {
          var html_inj;

          $.each (json.data.items, function(key, val) {
            html_inj = '';
            html_inj += '<tr class="listing" id="row'+key+'">';
            html_inj += '<td class="text-center">';
            if (val.img != '') {
              html_inj += '<img src="'+val.img+'" />';
            }
            html_inj += '</td>';
            html_inj += '<td class="text-left">'+key+'<input type="hidden" id="l-'+key+'_val" val="'+key+'" /></td>';
            html_inj += '<td class="text-left">'+val.name+'</td>';
            html_inj += '<td class="text-left"><input type="text" class="product-search form-control" placeholder="<?php echo $column_product_auto; ?>" id="l-'+key+'" /><input type="hidden" id="l-'+key+'-pid" /></td>';

            if (val.variants == 0) {
                html_inj += '<td class="text-center"><span id="l-'+key+'-qty"></span><input type="hidden" id="l-'+key+'-qtyinput" /></td>';
                html_inj += '<td class="text-center"><span id="l-'+key+'-allocated"></span><input type="hidden" id="l-'+key+'-allocatedinput" /><input type="hidden" id="l-'+key+'-subtractinput" /></td>';
                html_inj += '<td class="text-center"><span id="l-'+key+'-qtyebay">'+val.qty+'</span><input type="hidden" id="l-'+key+'-qtyebayinput" value="'+val.qty+'" /></td>';
                html_inj += '<input type="hidden" name="variants" id="l-'+key+'-variants" value="0" />';
                html_inj += '<td class="text-center"><img title="" alt="" src="view/image/delete.png" style="margin-top:3px;"></td>';
            } else {
                html_inj += '<td class="text-center"><span id="l-'+key+'-qty"></span></td>';
                html_inj += '<td class="text-center">-</td>';
                html_inj += '<td class="text-center">';
                $.each (val.variants, function(key1, val1) {
                    html_inj += val1.qty+' x ';
                    $.each (val1.nv.NameValueList, function(key2, val2) {
                        html_inj += val2.Value+' > ';
                    });
                    html_inj += '<br />';
                });
                html_inj += '</td>';
                html_inj += '<input type="hidden" name="variants" id="l-'+key+'-variants" value="1" />';
                html_inj += '<td class="text-center"><img title="Success" alt="Success" src="view/image/success.png" style="margin-top:3px;"></td>';
            }
            html_inj += '<td class="text-center"><a class="btn btn-primary" style="display:none;" onclick="saveListingLink('+key+'); return false;" id="l-'+key+'-save-button"><span><?php echo $button_save; ?></span></a></td>';
            html_inj += '</tr>';

            $('#ebay-listings').append(html_inj);
          });
        }

        $('#ebay-listings').show();

        if (json.data.more_pages == 1) {
          $('#check-unlinked-items').empty().html('<?php echo $button_check_unlinked; ?>').removeAttr('disabled');
        } else {
          $('#check-unlinked-items').hide();
        }

        if (json.data.break == 1) {
          $('#check-unlinked-items').before('<div class="alert alert-warning"><?php echo $text_limit_reached; ?></div>');
        }

        $('#unlinked-page').val(json.data.next_page);
      },
      error: function (xhr, ajaxOptions, thrownError) {
        $('#check-unlinked-items').empty().removeClass('btn-primary').addClass('btn-danger').html('<?php echo $text_failed; ?>').removeAttr('disabled');
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  });

  $(document).on('keydown', '.product-search', function() {
    var element_id = $(this).attr('id');

    $(this).autocomplete({
      source: function(request, response) {
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
      select: function(item) {
        $('#'+element_id).val(item['label']);
        getProductStock(item['value'], element_id);
        $('#'+element_id+'-pid').val(item['value']);
        return false;
      }
    });
  });

  $(document).ready(function() {
      checkLinkedItems();
  });
//--></script>
<?php echo $footer; ?>