<?php echo $header; ?>

<div id="content">
    <div class="box">
        <div class="heading">
            <h1><?php echo $lang_page_title; ?></h1>
            <div class="buttons">
                <a href="<?php echo $view_link; ?>" class="button" target="_BLANK"><span><?php echo $lang_view; ?></span></a>
                <a onclick="endItem();" class="button" id="btn_end_item"><span><?php echo $lang_end; ?></span></a>
                <a onclick="removeLink();" class="button"><span><?php echo $lang_remove; ?></span></a>
                <a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $lang_cancel; ?></span></a>
            </div>
        </div>

        <div class="content" id="loadingForm">
            <p class="m3"><img src="view/image/loading.gif" alt="Loading" /> <?php echo $lang_loading; ?></p>
        </div>

        <div class="content displayNone" id="errorForm">
            <div class="warning">
                <p class="m3"><?php echo $lang_error_loading; ?> <a onclick="load();" class="button"><span><?php echo $lang_retry; ?></span></a></p>
            </div>
        </div>

        <div class="content displayNone" id="mainForm">
            <div class="success displayNone mBottom5" id="successForm"><?php echo $lang_saved; ?></div>
            <div class="warning displayNone" id="error_box"></div>

            <form method="post" enctype="multipart/form-data" id="form">
                <table class="form">
                    <input type="hidden" name="itemId" value="" id="itemId" />
                    <tr>
                        <td><?php echo $lang_tbl_title; ?></td>
                        <td><input type="text" name="title" value="" id="title" size="85" /></td>
                    </tr>
                    <tr class="stdMatrix">
                        <input type="hidden" name="qty_local" value="0" id="qty_local" />
                        <input type="hidden" name="qty_ebay" value="0" id="qty_ebay" />
                        <input type="hidden" name="variant" value="0" />
                        <td><?php echo $lang_tbl_price; ?></td>
                        <td><input type="text" name="price" value="" id="price" size="10" /></td>
                    </tr>

                    <tr class="stdMatrix">
                        <td><?php echo $lang_tbl_qty_instock; ?></td>
                        <td id="qty_instock"></td>
                    </tr>

                    <tr class="stdMatrix">
                        <td><?php echo $lang_tbl_qty_listed; ?></td>
                        <td id="qty_listed"></td>
                    </tr>

                    <tr class="stdMatrix">
                        <td><?php echo $lang_tbl_qty_reserve; ?></td>
                        <td><input type="text" name="qty_reserve" value="0" id="qty_reserve" class="50" onkeyup="updateReserveMessage();" /></td>
                    </tr>

                    <tr id="variantMatrix">
                        <td><?php echo $lang_stock_matrix_active; ?></td>
                        <td>
                            <table class="list m0">
                                <thead>
                                    <tr>
                                        <td class="center"><?php echo $lang_stock_col_code; ?></td>
                                        <td class="center"><?php echo $lang_stock_col_qty_total; ?></td>
                                        <td class="center"><?php echo $lang_stock_col_listed; ?></td>
                                        <td class="center"><?php echo $lang_stock_col_limit; ?></td>
                                        <td class="left"><?php echo $lang_stock_col_comb; ?></td>
                                        <td class="center"><?php echo $lang_stock_col_price; ?></td>
                                        <td class="center"><?php echo $lang_stock_col_active; ?></td>
                                    </tr>
                                </thead>
                                <tbody id="stdMatrixTbl">

                                    <input type="hidden" name="variant" value="1" />
                                    <input type="hidden" name="optGroupArray" value="" id="optGroupArray" />
                                    <input type="hidden" name="optGroupRelArray" value="" id="optGroupRelArray" />

                                </tbody>
                            </table>
                        </td>
                    </tr>

                    <tr id="variantMatrixInactive" class="displayNone">
                        <td><?php echo $lang_stock_matrix_inactive; ?></td>
                        <td>
                            <table class="list m0">
                                <thead>
                                <tr>
                                    <td class="center"><?php echo $lang_stock_col_code; ?></td>
                                    <td class="center"><?php echo $lang_stock_col_qty_total; ?></td>
                                    <td class="center"><?php echo $lang_stock_col_limit; ?></td>
                                    <td class="left"><?php echo $lang_stock_col_comb; ?></td>
                                    <td class="center"><?php echo $lang_stock_col_price; ?></td>
                                    <td class="center"><?php echo $lang_stock_col_add; ?></td>
                                </tr>
                                </thead>
                                <tbody id="stdMatrixInactiveTbl"></tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" colspan="2">
                            <a onclick="save();" class="button" id="reviewButton"><span><?php echo $lang_save; ?></span></a>
                            <img src="view/image/loading.gif" id="reviewButtonLoading" class="displayNone" alt="Loading" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript"><!--

    function updateReserveMessage(){
        var reserve = parseInt($('#qty_reserve').val());
        var local   = parseInt($('#qty_local').val());

        if(reserve > local){
            alert('<?php echo $lang_error_reserve_size; ?>');
            $('#qty_reserve').val(local);
        }
    }

    function load(){
        $.ajax({
            url: 'index.php?route=openbay/openbay/editLoad&token=<?php echo $token; ?>&product_id=<?php echo $product_id; ?>',
            type: 'GET',
            dataType: 'json',
            beforeSend: function(){
                $('#loadingForm').fadeIn('slow');
                $('#mainForm').hide();
                $('#errorForm').hide();
            },
            success: function(data) {
                if(data.error == false){
                    if(data.data.listing.status == 0){
                        $('#form').hide();
                        $('#btn_end_item').hide();
                        $('#error_box').html('<p><?php echo $lang_error_ended; ?></p>').fadeIn('slow');
                    }else{
                        $('#title').val(data.data.listing.title);
                        $('#itemId').val(data.data.listing.itemId);

                        if(data.data.variant.variant == 1){
                            $('.stdMatrix').remove();
                            $('#optGroupArray').val(data.data.variant.data.grp_info.optGroupArray);
                            $('#optGroupRelArray').val(data.data.variant.data.grp_info.optGroupRelArray);

                            var i = 0;
                            var html = '';

                            $.each(data.data.variant.data.options, function( k, v ) {
                                html = '';

                                $('#stdMatrixTbl').append('<input type="hidden" name="opt['+i+'][sku]" value="'+v.ebay.SKU+'" />');

                                html +='<tr>';
                                html +='<input type="hidden" name="varPriceExCount" class="varPriceExCount" value="'+i+'" />';
                                html +='<td class="center width50">'+v.local.var+'</td>';
                                html +='<td class="center width50">'+v.local.stock+'</td>';
                                html +='<td class="center width50 textCenter">'+v.ebay.Quantity+'</td>';
                                html +='<td class="center width75"><input type="text" name="opt['+i+'][reserve]" value="'+v.local.reserve+'" class="width50 textCenter"/></td>';
                                html +='<td class="left">'+v.local.combi+'</td>';
                                html +='<td class="left width100"><input type="text" name="opt['+i+'][price]" value="'+v.ebay.StartPrice+'" value="0" class="width75 textCenter" /></td>';
                                html +='<td class="left width100 center"><input type="hidden" name="opt['+i+'][active]" value="0" /><input type="checkbox" name="opt['+i+'][active]" value="1" checked="checked" /></td>';
                                html +='</tr>';

                                $('#stdMatrixTbl').append(html);

                                i++;
                            });

                            if(data.data.variant.data.optionsinactive != false){
                                $('#variantMatrixInactive').show();

                                $.each(data.data.variant.data.optionsinactive, function( k, v ) {
                                    $('#stdMatrixTbl').append('<input type="hidden" name="opt['+i+'][sku]" value="'+v.local.var+'" />');
                                    html = '';

                                    html +='<tr>';
                                    html +='<input type="hidden" name="varPriceExCount" class="varPriceExCount" value="'+i+'" />';
                                    html +='<td class="center width50">'+ v.local.var+ '</td>';
                                    html +='<td class="center width50">'+ v.local.stock+ '</td>';
                                    html +='<td class="center width75"><input type="text" name="opt['+i+'][reserve]" value="'+v.local.reserve+'" class="width50 textCenter"/></td>';
                                    html +='<td class="left">'+v.local.combi+'</td>';
                                    html +='<td class="left width100"><input type="text" name="opt['+i+'][price]" value="'+ v.local.price+'" value="0" class="width75 textCenter" /></td>';
                                    html +='<td class="left width100 center"><input type="hidden" name="opt['+i+'][active]" value="0" /><input type="checkbox" name="opt['+i+'][active]" value="1" /></td>';
                                    html +='</tr>';

                                    $('#stdMatrixInactiveTbl').append(html);

                                    i++;
                                });
                            }

                        }else{
                            $('#variantMatrix').remove();

                            $('#price').val(data.data.listing.price);
                            $('#qty_instock').text(data.data.stock.quantity);
                            $('#qty_local').val(data.data.stock.quantity);
                            $('#qty_listed').text(data.data.listing.qty);
                            $('#qty_ebay').val(data.data.listing.qty);
                            $('#qty_reserve').val(data.data.reserve);
                        }
                    }
                }

                $('#loadingForm').hide();
                $('#mainForm').fadeIn('slow');
            },
            failure: function(){
                $('#loadingForm').hide();
                $('#errorForm').fadeIn('slow');
            },
            error: function(){
                $('#loadingForm').hide();
                $('#errorForm').fadeIn('slow');
            }
        });
    }

    function save(){
        $.ajax({
            type: 'POST',
            url: 'index.php?route=openbay/openbay/editSave&token=<?php echo $token; ?>',
            dataType: 'json',
            data: $("#form").serialize(),
            beforeSend: function(){
                $('#reviewButtonLoading').show();
                $('#reviewButton').hide();
                $('#error_box').empty().hide();
                $('#successForm').hide();
            },
            success: function(data) {
                $('#reviewButtonLoading').hide();
                $('#reviewButton').show();

                if(data.Errors){
                    if(data.Errors.ShortMessage){
                        $('#error_box').append('<p class="m3">'+data.Errors.LongMessage+'</p>');
                    }else{
                        $.each(data.Errors, function(key,val){
                            $('#error_box').append('<p class="m3">'+val.LongMessage+'</p>');
                        });
                    }
                    $('#error_box').fadeIn('slow');
                }

                if(data.Ack !== 'Failure'){
                    $('#successForm').fadeIn('slow');
                }

                $('#form').hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          }
        });
    }

    function removeLink() {
        var pass = confirm("<?php echo $lang_confirm; ?>");

        if (pass == true) {
            var id = $('#itemId').val();

            if(id !== ''){
                $.ajax({
                    type: 'GET',
                    url: 'index.php?route=openbay/openbay/removeItemLink&token=<?php echo $token; ?>&product_id=<?php echo $product_id; ?>',
                    dataType: 'json',
                    success: function() {
                        alert('<?php echo $lang_alert_removed; ?>');
                        window.location = 'index.php?route=extension/openbay/itemList&token=<?php echo $token; ?>';
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                  }
                });
            }
        }
    }

    function endItem() {
        var pass = confirm("<?php echo $lang_confirm; ?>");

        if (pass == true) {
            var id = $('#itemId').val();

            if(id !== ''){
                $.ajax({
                    type: 'GET',
                    url: 'index.php?route=openbay/openbay/endItem&token=<?php echo $token; ?>&id='+id,
                    dataType: 'json',
                    success: function(data) {
                        if(data.error == true){
                            alert(data.msg);
                        }else{
                            alert('<?php echo $lang_alert_ended; ?>');
                            window.location = 'index.php?route=extension/openbay/itemList&token=<?php echo $token; ?>';
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
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