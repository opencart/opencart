<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>

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
            <div class="form-group">
              <div class="row">
                <div class="col-sm-12">
                  <label class="control-label"><?php echo $text_filter_range; ?></label>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <input type="text" name="filter_qty_min" value="" class="form-control" placeholder="<?php echo $text_filter_range_from; ?>" id="filter_qty_min" />
                </div>
                <div class="col-sm-6">
                  <input type="text" name="filter_qty_max" value=""  class="form-control" placeholder="<?php echo $text_filter_range_to; ?>" id="filter_qty_max" />
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label"><?php echo $text_status; ?></label>
              <select name="filter_variant" class="form-control" id="filter_variant">
                <option value="1"><?php echo $text_yes; ?></option>
                <option value="0"><?php echo $text_no; ?></option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <table class="table">
        <thead>
          <tr>
            <th class="text-left"></th>
            <th class="text-left"><?php echo $text_column_itemId; ?></th>
            <th class="text-left"><?php echo $text_column_listing_title; ?></th>
            <th class="text-left"><?php echo $text_column_product_auto; ?></th>
            <th class="text-center"><?php echo $text_column_stock_available; ?></th>
            <th class="text-center"><?php echo $text_column_allocated; ?></th>
            <th class="text-center"><?php echo $text_column_ebay_stock; ?></th>
            <th class="text-center"><?php echo $text_column_variants; ?></th>
            <th class="text-center"><?php echo $text_column_action; ?></th>
          </tr>
        </thead>
        <tbody id="ebay-listings" style="display:none;">
          <tr id="fetching-ebay-items">
            <td class="text-center" colspan="9"><?php echo $text_text_unlinked_info; ?></td>
          </tr>
        </tbody>
      </table>
      <div class="buttons">
        <a class="btn btn-primary" id="check-unlinked-items"><?php echo $text_btn_check_unlinked; ?></a>
        <input type="hidden" name="unlinked_page" id="unlinked_page" value="1" />
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h1 class="panel-title"><i class="fa fa-link fa-lg"></i> <?php echo $text_linked_items; ?></h1>
    </div>
    <div class="panel-body">
      <p><?php echo $text_text_linked_desc; ?></p>
        <table class="table">
          <thead>
            <tr>
              <th class="text-left"><?php echo $text_column_product; ?></th>
              <th class="text-center"><?php echo $text_column_itemId; ?></th>
              <th class="text-center"><?php echo $text_column_allocated; ?></th>
              <th class="text-center"><?php echo $text_column_stock_available; ?></th>
              <th class="text-center"><?php echo $text_column_ebay_stock; ?></th>
              <th class="text-center"><?php echo $text_column_variants; ?></th>
              <th class="text-center"><?php echo $text_column_status; ?></th>
              <th class="text-center"><?php echo $text_column_action; ?></th>
            </tr>
          </thead>
          <tr>
            <td class="text-left" colspan="8" id="checking_linked_items">
              <a class="btn btn-primary" id="load-usage"><i class="fa fa-cog fa-lg fa-spin"></i> <?php echo $text_text_loading_items; ?></a>
            </td>
          </tr>
          <tbody style="display:none;" id="show_linked_items">
            <?php foreach ($linked_items as $id => $item) { ?>
              <input type="hidden" class="refreshClear" name="ebay_qty_<?php echo $id; ?>" value="" id="ebay_qty_<?php echo $id; ?>" />
              <input type="hidden" name="store_qty_<?php echo $id; ?>" value="<?php echo $item['qty']; ?>" id="store_qty_<?php echo $id; ?>" />
              <input type="hidden" name="item_id[]" id="item_id_<?php echo $id; ?>" value="<?php echo $id; ?>" class="item_id"  />
              <input type="hidden" name="product_id[]" id="product_id_<?php echo $id; ?>" value="<?php echo $item['product_id']; ?>" />
              <input type="hidden" name="options" id="options_<?php echo $id; ?>" value="<?php echo (int)$item['options']; ?>" />

              <tr id="row_<?php echo $id; ?>" class="refreshRow">
                <td class="text-left"><a href="<?php echo $item['link_edit']; ?>" target="_BLANK"><?php echo $item['name']; ?></a></td>
                <td class="text-center"><a href="<?php echo $item['link_ebay']; ?>" target="_BLANK"><?php echo $id; ?></a></td>
                <?php if ($item['options'] == 0) { ?>
                  <td class="text-center"><?php echo $item['allocated']; ?></td>
                  <td class="text-center"><?php echo $item['qty']; ?></td>
                  <td id="text_qty_<?php echo $id; ?>" class="text-center refreshClear"></td>
                  <td class="text-center" align="center"><img title="" alt="" src="view/image/delete.png" style="margin-top:3px;"></td>
                <?php } else { ?>
                  <td class="text-center">-</td>
                  <td class="text-center"><?php foreach ($item['options'] as $option) { echo $option['stock'] .' x ' . $option['combi'] . '<br />'; } ?></td>
                  <td id="text_qty_<?php echo $id; ?>" class="text-center refreshClear"></td>
                  <td class="text-center" align="center"><img title="" alt="" src="view/image/success.png" style="margin-top:3px;"></td>
                <?php } ?>
                <td class="text-center refreshClear" id="text_status_<?php echo $id; ?>"></td>
                <td class="text-center buttons refreshClear" id="text_buttons_<?php echo $id; ?>"></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
  function checkLinkedItems() {
      $.ajax({
          url: 'index.php?route=openbay/ebay/loadLinkedStatus&token=<?php echo $token; ?>',
          data: $('.item_id').serialize(),
          type: 'POST',
          dataType: 'json',
          success: function(json) {
            if (json.data == '') {
              $('#checking_linked_items').hide();
              $('.pagination').hide();
              $('#show_linked_items').html('<tr><td colspan="8" class="text-center"><?php echo $text_ajax_error_listings; ?></td></tr>').show();
            } else {
              $.each (json.data, function(key, val) {
                key                 = String(key);
                var product_id      = $('#product_id_'+key).val();
                var storeQty        = $('#store_qty_'+key).val();
                var htmlInj         = '';
  
                if (val.variants == 0) {
                  $('#text_qty_'+key).text(val.qty);
                  $('#ebay_qty_'+key).val(val.qty);
  
                  if (val.status == 1) {
                    if ($('#ebay_qty_'+key).val() == $('#store_qty_'+key).val()) {
                      $('#text_status_'+key).text('OK');
                      $('#row_'+key+' > td').css('background-color', '#E3FFC8');
                      $('#text_buttons_'+key).html('<a href="<?php echo $edit_url; ?>'+product_id+'" class="btn btn-primary"><span><?php echo $text_btn_edit; ?></span></a>');
                    } else {
                      $('#text_status_'+key).text('Stock error');
                      $('#row_'+key+' > td').css('background-color', '#FFD4D4');
                      $('#text_buttons_'+key).html('<a onclick="updateLink('+key+','+val.qty+','+product_id+', '+storeQty+');" class="btn btn-primary"><span><?php echo $text_btn_resync; ?></a>');
                    }
                  } else {
                    $('#text_status_'+key).text('Listing ended');
                    $('#row_'+key+' > td').css('background-color', '#FFD4D4');
                    $('#text_buttons_'+key).html('<a onclick="removeLink('+product_id+', '+key+');" class="btn btn-danger"><i class="fa fa-minus-circle"></i> <?php echo $text_btn_remove_link; ?></a>');
                  }
                } else {
                  $.each (val.variants, function(key1, val1) {
                    htmlInj += val1.qty+' x ';
                    $.each (val1.nv.NameValueList, function(key2, val2) {
                        htmlInj += val2.Value+' > ';
                    });
                    htmlInj += '<br />';
                  });
  
                  $('#text_qty_'+key).html(htmlInj);
  
                  if (val.status == 0) {
                    $('#text_status_'+key).text('Listing ended');
                    $('#row_'+key+' > td').css('background-color', '#FFD4D4');
                    $('#text_buttons_'+key).html('<a onclick="removeLink('+product_id+', '+key+');" class="btn btn-danger"><i class="fa fa-minus-circle"></i> <?php echo $text_btn_remove_link; ?></a>');
                  }
                }
              });
  
              $('#checking_linked_items').hide();
              $('#show_linked_items').show();
              $('#ebay-listings').show();
            }
          },
          failure: function() {
              $('#errorBox').text('<?php echo $text_ajax_load_error; ?>').fadeIn();
          },
          error: function() {
              $('#errorBox').text('<?php echo $text_ajax_load_error; ?>').fadeIn();
          }
      });
  }
  
  function removeLink(product_id, id) {
      $.ajax({
          type: 'GET',
          url: 'index.php?route=openbay/ebay/removeItemLink&token=<?php echo $token; ?>&product_id='+product_id,
          dataType: 'json',
          success: function(json) {
              $('#row_'+id).fadeOut('slow');
          },
          error: function (xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
  }
  
  function updateLink(itemid, qty, product_id, storeQty) {
      var r = confirm("<?php echo $text_alert_stock_local; ?>");
      varBtnOld = $('#text_buttons_'+itemid).html();
  
      $('#text_buttons_'+itemid).html('<p class="center"><img src="view/image/loading.gif" alt="Loading" /></p>');
  
      if (r == true) {
          $.ajax({
              type: 'GET',
              url: 'index.php?route=openbay/ebay/setProductStock&token=<?php echo $token; ?>&product_id='+product_id,
              dataType: 'json',
              success: function(json) {
                  if (json.error == false) {
                      $('#text_status_'+itemid).text('OK');
                      $('#text_buttons_'+itemid).html('<a href="<?php echo $edit_url; ?>'+product_id+'" class="button"><span><?php echo $text_btn_edit; ?></span></a>');
                      $('#row_'+itemid+' > td').css('background-color', '#E3FFC8');
                      $('#l_'+itemid+'_qtyinput').val(qty);
                      $('#l_'+itemid+'_qty').val(qty);
                      $('#text_qty_'+itemid).text(storeQty);
                      $('#text_buttons_'+itemid).empty();
                  }
  
                  if (json.error == true) {
                      $('#text_buttons_'+itemid).html(varBtnOld);
                      alert(json.msg);
                  }
              },
              failure: function() {
                  $('#text_buttons_'+itemid).html(varBtnOld);
                  alert('<?php echo $text_ajax_load_error; ?>');
              },
              error: function() {
                  $('#text_buttons_'+itemid).html(varBtnOld);
                  alert('<?php echo $text_ajax_load_error; ?>');
              }
          });
      }
  }
  
  function saveListingLink(id) {
      var product_id      = $('#l_'+id+'_pid').val();
      var qty             = $('#l_'+id+'_qtyinput').val();
      var ebayqty         = $('#l_'+id+'_qtyebayinput').val();
      var variants        = $('#l_'+id+'_variants').val();
  
      if (product_id === '') {
          alert('<?php echo $text_ajax_error_link; ?>');
          return false;
      }
  
      if (qty < 1) {
          alert('<?php echo $text_ajax_error_link_no_sk; ?>');
          return false;
      }
  
      $.ajax({
          url: 'index.php?route=openbay/ebay/saveItemLink&token=<?php echo $token; ?>&pid='+product_id+'&itemId='+id+'&qty='+qty+'&ebayqty='+ebayqty+'&variants='+variants,
          type: 'post',
          dataType: 'json',
          beforeSend: function() {
              $('#l_'+id+'_saveBtn').hide();
              $('#l_'+id+'_saveLoading').show();
          },
          success: function(json) {
              $('#row'+id).fadeOut('slow');
              $('#l_'+id+'_saveLoading').hide();
          },
          error: function (xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
  }
  
  function getProductStock(id, elementId) {
      $.ajax({
          type:'GET',
          dataType: 'json',
          url: 'index.php?route=openbay/ebay/getProductStock&token=<?php echo $token; ?>&pid='+id,
          success: function(data) {
              if (data.variant == 0) {
                  $('#'+elementId+'_qty').text(data.qty);
                  $('#'+elementId+'_qtyinput').val(data.qty);
                  $('#'+elementId+'_allocated').text(data.allocated);
                  $('#'+elementId+'_allocatedinput').val(data.allocated);
                  $('#'+elementId+'_subtractinput').val(data.subtract);
                  $('#'+elementId+'_saveBtn').show();
              } else {
                  var injHtml = '';
                  $.each (data.variant, function(key, val) {
                      injHtml += val.stock+' x '+val.combi+'<br />';
                  });
                  $('#'+elementId+'_qty').html(injHtml);
                  $('#'+elementId+'_saveBtn').show();
              }
          }
      });
  }
  
  $('#check-unlinked-items').bind('click', function() {
    var unlinked_page = $('#unlinked_page').val();
  
    $.ajax({
      url: 'index.php?route=openbay/ebay/loadUnlinked&token=<?php echo $token; ?>&page='+unlinked_page,
      type: 'POST',
      data: { 'filter_title' : $('#filter_title').val(), 'filter_qty_min' : $('#filter_qty_min').val(), 'filter_qty_max' : $('#filter_qty_max').val(), 'filter_variant' : $('#filter_variant').val() },
      dataType: 'json',
      beforeSend: function() {
        $('#fetching-ebay-items').hide();
        $('#check-unlinked-items').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
        $('.alert-warning').remove();
      },
      success: function(json) {
        if (json.data.items === null) {
          $('#ebay-listings').append('<tr><td colspan="7"><p><?php echo $text_ajax_error_listings; ?></p></td></tr>');
        } else {
          var htmlInj;
  
          $.each (json.data.items, function(key, val) {
            htmlInj = '';
            htmlInj += '<tr class="listing" id="row'+key+'">';
            htmlInj += '<td class="text-center">';
            if (val.img != '') {
              htmlInj += '<img src="'+val.img+'" />';
            }
            htmlInj += '</td>';
            htmlInj += '<td class="text-left">'+key+'<input type="hidden" id="l_'+key+'_val" val="'+key+'" /></td>';
            htmlInj += '<td class="text-left">'+val.name+'</td>';
            htmlInj += '<td class="text-left"><input type="text" class="form-control product-search" placeholder="<?php echo $text_column_product_auto; ?>" value="" id="l_'+key+'" /><input type="hidden" id="l_'+key+'_pid" /></td>';
  
            if (val.variants == 0) {
                htmlInj += '<td class="text-center"><span id="l_'+key+'_qty"></span><input type="hidden" id="l_'+key+'_qtyinput" /></td>';
                htmlInj += '<td class="text-center"><span id="l_'+key+'_allocated"></span><input type="hidden" id="l_'+key+'_allocatedinput" /><input type="hidden" id="l_'+key+'_subtractinput" /></td>';
                htmlInj += '<td class="text-center"><span id="l_'+key+'_qtyebay">'+val.qty+'</span><input type="hidden" id="l_'+key+'_qtyebayinput" value="'+val.qty+'" /></td>';
                htmlInj += '<input type="hidden" name="variants" id="l_'+key+'_variants" value="0" />';
                htmlInj += '<td class="text-center"><img title="" alt="" src="view/image/delete.png" style="margin-top:3px;"></td>';
            } else {
                htmlInj += '<td class="text-center"><span id="l_'+key+'_qty"></span></td>';
                htmlInj += '<td class="text-center">-</td>';
                htmlInj += '<td class="text-center">';
                $.each (val.variants, function(key1, val1) {
                    htmlInj += val1.qty+' x ';
                    $.each (val1.nv.NameValueList, function(key2, val2) {
                        htmlInj += val2.Value+' > ';
                    });
                    htmlInj += '<br />';
                });
                htmlInj += '</td>';
                htmlInj += '<input type="hidden" name="variants" id="l_'+key+'_variants" value="1" />';
                htmlInj += '<td class="text-center"><img title="Success" alt="Success" src="view/image/success.png" style="margin-top:3px;"></td>';
            }
            htmlInj += '<td class="text-center"><a class="button" style="display:none;" onclick="saveListingLink('+key+'); return false;" id="l_'+key+'_saveBtn"><span><?php echo $button_save; ?></span></a> <img src="view/image/loading.gif" class="displayNone" id="l_'+key+'_saveLoading" alt="Loading" /></td>';
            htmlInj += '</tr>';
  
            $('#ebay-listings').append(htmlInj);
          });
        }
  
        $('#ebay-listings').show();
  
        if (json.data.more_pages == 1) {
          $('#check-unlinked-items').empty().html('<?php echo $text_btn_check_unlinked; ?>').removeAttr('disabled');
        } else {
          $('#check-unlinked-items').hide();
        }

        if (json.data.break == 1) {
          $('#check-unlinked-items').before('<div class="alert alert-warning"><?php echo $error_limit_reached; ?></div>');
        }
  
        $('#unlinked_page').val(json.data.next_page);
      },
      error: function (xhr, ajaxOptions, thrownError) {
        $('#check-unlinked-items').empty().removeClass('btn-primary').addClass('btn-danger').html('<?php echo $text_failed; ?>').removeAttr('disabled');
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  });
  
    $('.product-search').autocomplete({
      source: function(request, response) {
        $.ajax({
          url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
          dataType: 'json',
          type: 'POST',
          data: 'filter_name=' +  encodeURIComponent(request.term),
          success: function(json) {
              response($.map(json, function(item) {
                  return {
                      label: item.name,
                      value: item.product_id
                  }
              }));
          },
          error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          }
        });
      },
      select: function(event, ui) {
        $(this).val(ui.item.label);
        // get the item id of the row
        var elementId = $(this).attr('id');
        getProductStock(ui.item.value, elementId);
        $('#'+elementId+'_pid').val(ui.item.value);
        return false;
      }
    });
  
  $(document).ready(function() {
      checkLinkedItems();
  });
//--></script>
<?php echo $footer; ?>