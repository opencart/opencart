<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"> <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a> </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="alert alert-info" id="form-loading"> <i class="fa fa-cog fa-lg fa-spin"></i> <?php echo $text_loading; ?> </div>
    <div class="alert alert-danger" id="form-error" style="display:none;">
      <div class="row">
        <div class="col-sm-8"><?php echo $text_error_loading; ?></div>
        <div class="col-sm-4 text-right"><a onclick="load();" class="btn btn-primary"><i class="fa fa-refresh"></i> <?php echo $button_retry; ?></a></div>
      </div>
    </div>
    <div class="content displayNone" id="form-main">
      <div class="alert alert-success" id="form-success" style="display:none;"><?php echo $text_saved; ?></div>
      <div class="alert alert-danger" id="error_box" style="display:none;"></div>
      <form method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
        <input type="hidden" name="itemId" value="" id="itemId" />
        <div class="form-group">
          <label class="col-sm-2 control-label" for="title"><?php echo $entry_title; ?></label>
          <div class="col-sm-10">
            <input type="text" name="title" value="" id="title" class="form-control" />
          </div>
        </div>
        <div class="form-group stdMatrix">
          <input type="hidden" name="qty_local" value="0" id="qty_local" />
          <input type="hidden" name="qty_ebay" value="0" id="qty_ebay" />
          <input type="hidden" name="variant" value="0" />
          <label class="col-sm-2 control-label" for="price"><?php echo $entry_price; ?></label>
          <div class="col-sm-2">
            <input type="text" name="price" value="" id="price" class="form-control" />
          </div>
        </div>
        <div class="form-group stdMatrix">
          <label class="col-sm-2 control-label" for="qty-instock"><?php echo $entry_stock_store; ?></label>
          <div class="col-sm-2">
            <input type="text" name="qty_instock" id="qty-instock" class="form-control" disabled="disabled" />
            <span class="help-block"><?php echo $help_stock_store; ?></span> </div>
        </div>
        <div class="form-group stdMatrix">
          <label class="col-sm-2 control-label" for="qty-listed"><?php echo $entry_stock_listed; ?></label>
          <div class="col-sm-2">
            <input type="text" name="qty_listed" id="qty-listed" class="form-control" disabled="disabled" />
            <span class="help-block"><?php echo $help_stock_listed; ?></span> </div>
        </div>
        <div class="form-group stdMatrix">
          <label class="col-sm-2 control-label"><?php echo $entry_stock_reserve; ?></label>
          <div class="col-sm-2">
            <input type="text" name="qty_reserve" value="0" id="qty_reserve" class="form-control" onkeyup="updateReserveMessage();" />
            <span class="help-block"><?php echo $help_stock_reserve; ?></span> </div>
        </div>
        <div class="form-group" id="variantMatrix">
          <label class="col-sm-2 control-label"><?php echo $entry_stock_matrix_active; ?></label>
          <div class="col-sm-10">
            <table class="table">
              <thead>
                <tr>
                  <td class="text-center"><?php echo $column_sku; ?></td>
                  <td class="text-center"><?php echo $column_stock_total; ?></td>
                  <td class="text-center"><?php echo $column_stock_listed; ?></td>
                  <td class="text-center"><?php echo $column_stock_reserve; ?></td>
                  <td class="text-left"><?php echo $column_combination; ?></td>
                  <td class="text-center"><?php echo $column_price; ?></td>
                  <td class="text-center"><?php echo $column_status; ?></td>
                </tr>
              </thead>
              <tbody id="matrix-active">
              <input type="hidden" name="variant" value="1" />
              <input type="hidden" name="optGroupArray" value="" id="optGroupArray" />
              <input type="hidden" name="optGroupRelArray" value="" id="optGroupRelArray" />
                </tbody>
              
            </table>
          </div>
        </div>
        <div class="form-group" id="variantMatrixInactive" style="display:none;">
          <label class="col-sm-2 control-label"><?php echo $entry_stock_matrix_inactive; ?></label>
          <div class="col-sm-10">
            <table class="table">
              <thead>
                <tr>
                  <th class="text-center"><?php echo $column_sku; ?></th>
                  <th class="text-center"><?php echo $column_stock_total; ?></th>
                  <th class="text-center"><?php echo $column_stock_reserve; ?></th>
                  <th class="text-left"><?php echo $column_combination; ?></th>
                  <th class="text-center"><?php echo $column_price; ?></th>
                  <th class="text-center"><?php echo $column_add; ?></th>
                </tr>
              </thead>
              <tbody id="matrix-inactive">
              </tbody>
            </table>
          </div>
        </div>
      </form>
      <div class="well">
        <div class="row">
          <div class="col-sm-12 text-right"> <a onclick="endItem();" id="btn-end-item" class="btn btn-danger"><i class="fa fa-minus-circle"></i> <?php echo $button_end; ?></a> <a onclick="removeLink();" class="btn btn-danger"><i class="fa fa-minus-circle"></i> <?php echo $button_remove; ?></a> <a href="<?php echo $view_link; ?>" class="btn btn-primary" target="_BLANK"><?php echo $button_listing; ?></a> <a id="button-save" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $button_save; ?></a> </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
    function updateReserveMessage(){
        var reserve = parseInt($('#qty_reserve').val());
        var local   = parseInt($('#qty_local').val());

        if (reserve > local){
            alert('<?php echo $error_reserve; ?>');
            $('#qty_reserve').val(local);
        }
    }

    function load(){
        $.ajax({
            url: 'index.php?route=openbay/ebay/editLoad&token=<?php echo $token; ?>&product_id=<?php echo $product_id; ?>',
            type: 'GET',
            dataType: 'json',
            beforeSend: function(){
                $('#form-loading').fadeIn('slow');
                $('#form-main').hide();
                $('#form-error').hide();
            },
            success: function(data) {
                if (data.error == false){
                    if (data.data.listing.status == 0){
                        $('#form').hide();
                        $('#btn-end-item').hide();
                        $('#error_box').html('<p><?php echo $error_ended; ?></p>').fadeIn('slow');
                    }else{
                        $('#title').val(data.data.listing.title);
                        $('#itemId').val(data.data.listing.itemId);

                        if (data.data.variant.variant == 1){
                            $('.stdMatrix').remove();
                            $('#optGroupArray').val(data.data.variant.data.grp_info.optGroupArray);
                            $('#optGroupRelArray').val(data.data.variant.data.grp_info.optGroupRelArray);

                            var i = 0;
                            var html = '';

                            $.each(data.data.variant.data.options, function( k, v ) {
                                html = '';

                                $('#matrix-active').append('<input type="hidden" name="opt['+i+'][sku]" value="'+v.ebay.SKU+'" />');

                                html +='<tr>';
                                html +='<input type="hidden" name="varPriceExCount" class="varPriceExCount" value="'+i+'" />';
                                html +='<td class="text-center">'+v.local.var+'</td>';
                                html +='<td class="text-center">'+v.local.stock+'</td>';
                                html +='<td class="text-center">'+v.ebay.Quantity+'</td>';
                                html +='<td class="text-center"><input type="text" name="opt['+i+'][reserve]" value="'+v.local.reserve+'" class="text-center form-control" /></td>';
                                html +='<td class="text-left">'+v.local.combi+'</td>';
                                html +='<td class="text-left"><input type="text" name="opt['+i+'][price]" value="'+v.ebay.StartPrice+'" value="0" class="text-center form-control" /></td>';
                                html +='<td class="text-left"><input type="hidden" name="opt['+i+'][active]" value="0" /><input type="checkbox" name="opt['+i+'][active]" value="1" checked="checked" /></td>';
                                html +='</tr>';

                                $('#matrix-active').append(html);

                                i++;
                            });

                            if (data.data.variant.data.optionsinactive != false){
                                $('#variantMatrixInactive').show();

                                $.each(data.data.variant.data.optionsinactive, function( k, v ) {
                                    $('#matrix-active').append('<input type="hidden" name="opt['+i+'][sku]" value="'+v.local.var+'" />');
                                    html = '';

                                    html +='<tr>';
                                    html +='<input type="hidden" name="varPriceExCount" class="varPriceExCount" value="'+i+'" />';
                                    html +='<td class="text-center">'+ v.local.var+ '</td>';
                                    html +='<td class="text-center">'+ v.local.stock+ '</td>';
                                    html +='<td class="text-center"><input type="text" name="opt['+i+'][reserve]" value="'+v.local.reserve+'" class="text-center form-control"/></td>';
                                    html +='<td class="text-left">'+v.local.combi+'</td>';
                                    html +='<td class="text-left"><input type="text" name="opt['+i+'][price]" value="'+ v.local.price+'" value="0" class="text-center form-control" /></td>';
                                    html +='<td class="text-left"><input type="hidden" name="opt['+i+'][active]" value="0" /><input type="checkbox" name="opt['+i+'][active]" value="1" /></td>';
                                    html +='</tr>';

                                    $('#matrix-inactive').append(html);

                                    i++;
                                });
                            }

                        }else{
                            $('#variantMatrix').remove();

                            $('#price').val(data.data.listing.price);
                            $('#qty-instock').val(data.data.stock.quantity);
                            $('#qty_local').val(data.data.stock.quantity);
                            $('#qty-listed').val(data.data.listing.qty);
                            $('#qty_ebay').val(data.data.listing.qty);
                            $('#qty_reserve').val(data.data.reserve);
                        }
                    }
                }

                $('#form-main').fadeIn('slow');
            },
            complete: function() {
              $('#form-loading').hide();
            },
            failure: function(){
                $('#form-error').fadeIn('slow');
            },
            error: function(){
                $('#form-error').fadeIn('slow');
            }
        });
    }

    $('#button-save').bind('click', function() {
        $.ajax({
            type: 'POST',
            url: 'index.php?route=openbay/ebay/editSave&token=<?php echo $token; ?>',
            dataType: 'json',
            data: $("#form").serialize(),
            beforeSend: function(){
              $('#button-save').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
                $('#error_box').empty().hide();
                $('#form-success').hide();
            },
            success: function(data) {
                $('#reviewButtonLoading').hide();
                $('#reviewButton').show();

                if (data.Errors){
                    if (data.Errors.ShortMessage){
                        $('#error_box').append('<p class="m3">'+data.Errors.LongMessage+'</p>');
                    }else{
                        $.each(data.Errors, function(key,val){
                            $('#error_box').append('<p class="m3">'+val.LongMessage+'</p>');
                        });
                    }
                    $('#error_box').fadeIn('slow');
                }

                if (data.Ack !== 'Failure'){
                    $('#form-success').fadeIn('slow');
                }

                $('#form').hide();
            },
            complete: function() {
              $('#button-save').empty().html('<i class="fa fa-save"></i> <?php echo $button_save; ?>').removeAttr('disabled');
            },
            error: function (xhr, ajaxOptions, thrownError) {
              if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
            }
        });
    });

    function removeLink() {
        var pass = confirm("<?php echo $text_confirm; ?>");

        if (pass == true) {
            var id = $('#itemId').val();

            if (id !== ''){
                $.ajax({
                    type: 'GET',
                    url: 'index.php?route=openbay/ebay/removeItemLink&token=<?php echo $token; ?>&product_id=<?php echo $product_id; ?>',
                    dataType: 'json',
                    success: function() {
                        alert('<?php echo $text_alert_removed; ?>');
                        window.location = 'index.php?route=extension/openbay/itemList&token=<?php echo $token; ?>';
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                      if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
                    }
                });
            }
        }
    }

    function endItem() {
        var pass = confirm("<?php echo $text_confirm; ?>");

        if (pass == true) {
            var id = $('#itemId').val();

            if (id !== ''){
                $.ajax({
                    type: 'GET',
                    url: 'index.php?route=openbay/ebay/endItem&token=<?php echo $token; ?>&id='+id,
                    dataType: 'json',
                    success: function(data) {
                        if (data.error == true){
                            alert(data.msg);
                        }else{
                            alert('<?php echo $text_alert_ended; ?>');
                            window.location = 'index.php?route=extension/openbay/itemList&token=<?php echo $token; ?>';
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                      if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
                    }
                });
            }
        }
    }

    $(document).ready(function() {
        load();
    });
//--></script> 
<?php echo $footer; ?>