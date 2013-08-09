<?php echo $header; ?>
<div id="content">
<div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
</div>

<div class="warning displayNone" id="errorBox"></div>
<div class="attention displayNone" id="warningBox"></div>
<div class="success displayNone" id="successBox"></div>

<div class="box mBottom130">

    <div class="heading">
        <h1><?php echo $lang_heading; ?></h1>
        <div class="buttons">
            <a onclick="loadListingsForLink();" class="button" id="loadListingsForLink"><span><?php echo $lang_btn_load; ?></span></a>
            <a onclick="location = '<?php echo $return; ?>';" class="button"><span><?php echo $lang_btn_return; ?></span></a>
        </div>
    </div>
    <div class="content">
        <?php if($validation == true) { ?>
        <p><?php echo $lang_link_desc1; ?></p>
        <p><?php echo $lang_link_desc2; ?></p>
        <p><?php echo $lang_desc_local; ?></p>
        <p><?php echo $lang_desc_allocated; ?></p>

        <div id="tabs" class="htabs">
            <a href="#tab-linkup"><?php echo $lang_unlinked_items; ?> <img src="<?php echo HTTPS_SERVER; ?>view/image/loading.gif" id="imageLoadListingsForLink" class="displayNone"/></a>
            <a href="#tab-linked"><?php echo $lang_linked_items; ?></a>
        </div>
        <div id="tab-linkup" class="displayNone">
            <table class="list" cellpadding="2">
                <thead>
                <tr>
                    <td class="left" width="20%"><?php echo $lang_linkup_itemId; ?></td>
                    <td class="left"><?php echo $lang_linkup_header_text1; ?></td>
                    <td class="left"><?php echo $lang_linkup_header_text2; ?></span></td>
                    <td class="center width100"><?php echo $lang_linkup_header_text3; ?></td>
                    <td class="center width100"><?php echo $lang_linkup_header_text8; ?></td>
                    <td class="center width100"><?php echo $lang_linkup_header_text4; ?></td>
                    <td class="center width100"><?php echo $lang_linkup_header_text7; ?></td>
                    <td class="center width100"><?php echo $lang_linkup_header_text5; ?></td>
                </tr>
                </thead>
                <tbody id="eBayListings">
                <tr class="filter">
                    <td class="left" colspan="8" id="fetchingEbayItems"><?php echo $lang_linkup_header_text6; ?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="tab-linked">
            <table class="list" cellpadding="2">
                <thead>
                <tr>
                    <td class="left"><?php echo $lang_tbl_headertext1; ?></td>
                    <td class="center"><?php echo $lang_tbl_headertext2; ?></td>
                    <td class="center"><?php echo $lang_tbl_headertext3; ?></td>
                    <td class="center"><?php echo $lang_tbl_headertext4; ?></td>
                    <td class="center"><?php echo $lang_tbl_headertext5; ?></td>
                    <td class="center"><?php echo $lang_tbl_headertext6; ?></td>
                    <td class="center"><?php echo $lang_tbl_headertext7; ?></td>
                    <td class="center"><?php echo $lang_tbl_headertext8; ?></td>
                </tr>
                </thead>
                <tbody>
                <?php foreach($items as $id => $item) { ?>
                <input type="hidden" class="refreshClear" name="ebay_qty_<?php echo $id; ?>" value="" id="ebay_qty_<?php echo $id; ?>" />
                <input type="hidden" name="store_qty_<?php echo $id; ?>" value="<?php echo $item['qty']; ?>" id="store_qty_<?php echo $id; ?>" />
                <input type="hidden" name="item_id" id="item_id_<?php echo $id; ?>" value="<?php echo $id; ?>" class="item_id"  />
                <input type="hidden" name="product_id" id="product_id_<?php echo $id; ?>" value="<?php echo $item['product_id']; ?>" />
                <input type="hidden" name="options" id="options_<?php echo $id; ?>" value="<?php echo (int)$item['options']; ?>" />

                <tr id="row_<?php echo $id; ?>" class="refreshRow">
                    <td class="left"><a href="<?php echo HTTPS_SERVER . 'index.php?route=catalog/product/update&token=' . $this->session->data['token'] .'&product_id='.$item['product_id']; ?>" target="_BLANK"><?php echo $item['name']; ?></a></td>
                    <td class="center"><a href="<?php echo $this->config->get('openbaypro_ebay_itm_link').$id; ?>" target="_BLANK"><?php echo $id; ?></a></td>
                        <?php if($item['options'] == 0){ ?>
                            <td class="center"><?php echo $item['allocated']; ?></td>
                            <td class="center"><?php echo $item['qty']; ?></td>
                            <td id="text_qty_<?php echo $id; ?>" class="center refreshClear"></td>
                            <td class="center" align="center"><img title="" alt="" src="view/image/delete.png" style="margin-top:3px;"></td>
                        <?php }else{ ?>
                            <td class="center">-</td>
                            <td class="center"><?php foreach($item['options'] as $option){ echo $option['stock'] .' x ' . $option['combi'] . '<br />'; } ?></td>
                            <td id="text_qty_<?php echo $id; ?>" class="center refreshClear"></td>
                            <td class="center" align="center"><img title="" alt="" src="view/image/success.png" style="margin-top:3px;"></td>
                        <?php } ?>
                    <td class="center refreshClear" id="text_status_<?php echo $id; ?>"></td>
                    <td class="center buttons refreshClear" id="text_buttons_<?php echo $id; ?>"></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <?php }else{ ?>
        <div class="warning"><?php echo $lang_error_validation; ?></div>
        <?php } ?>

    </div>
</div>

<script type="text/javascript"><!--

function loadListingsForLink(){
    $('.listing').remove();
    $('#fetchingEbayItems').show();
    $('#loadListingsForLink').hide();
    $('#imageLoadListingsForLink').show();
    $('#eBayListings').empty();
    $('#errorBox').hide();
    $('#warningBox').hide();
    $('#successBox').hide();
    $('.refreshClear').empty();
    $('.refreshRow > td').css('background-color', '#FFFFFF');
    $('.item_id').addClass('unprocessed');

    $.ajax({
        url: 'index.php?route=openbay/openbay/loadItemLinks&token=<?php echo $token; ?>',
        type: 'get',
        dataType: 'json',
        success: function(json) {
            $('#fetchingEbayItems').hide();

            if(json.data.unlinked === null){
                $('#eBayListings').append('<tr><td colspan="7"><p><?php echo $lang_ajax_error_listings; ?></p></td></tr>');
            }else{
                var htmlInj;

                $.each(json.data.unlinked, function(key, val){
                    htmlInj = '';
                    htmlInj += '<tr class="listing" id="row'+key+'">';
                    htmlInj += '<td class="left">'+key+'<input type="hidden" id="l_'+key+'_val" val="'+key+'" /></td>';
                    htmlInj += '<td class="left">'+val.name+'</td>';
                    htmlInj += '<td class="left"><input type="text" class="localName" value="" id="l_'+key+'" /><input type="hidden" id="l_'+key+'_pid" /></td>';

                    if(val.variants == 0){
                        htmlInj += '<td class="center"><span id="l_'+key+'_qty"></span><input type="hidden" id="l_'+key+'_qtyinput" /></td>';
                        htmlInj += '<td class="center"><span id="l_'+key+'_allocated"></span><input type="hidden" id="l_'+key+'_allocatedinput" /><input type="hidden" id="l_'+key+'_subtractinput" /></td>';
                        htmlInj += '<td class="center"><span id="l_'+key+'_qtyebay">'+val.qty+'</span><input type="hidden" id="l_'+key+'_qtyebayinput" value="'+val.qty+'" /></td>';
                        htmlInj += '<input type="hidden" name="variants" id="l_'+key+'_variants" value="0" />';
                        htmlInj += '<td class="center"><img title="" alt="" src="view/image/delete.png" style="margin-top:3px;"></td>';
                    }else{
                        htmlInj += '<td class="center"><span id="l_'+key+'_qty"></span></td>';
                        htmlInj += '<td class="center">-</td>';
                        htmlInj += '<td class="center">';
                        $.each(val.variants, function(key1, val1){
                            htmlInj += val1.qty+' x ';
                            $.each(val1.nv.NameValueList, function(key2, val2){
                                htmlInj += val2.Value+' > ';
                            });
                            htmlInj += '<br />';
                        });
                        htmlInj += '</td>';
                        htmlInj += '<input type="hidden" name="variants" id="l_'+key+'_variants" value="1" />';
                        htmlInj += '<td class="center"><img title="" alt="" src="<?php echo HTTPS_SERVER; ?>view/image/success.png" style="margin-top:3px;"></td>';
                    }
                    htmlInj += '<td class="center"><a class="button displayNone" onclick="saveListingLink('+key+'); return false;" id="l_'+key+'_saveBtn"><span><?php echo $lang_btn_save; ?></span></a> <img src="<?php echo HTTPS_SERVER; ?>/view/image/loading.gif" class="displayNone" id="l_'+key+'_saveLoading" /></td>';
                    htmlInj += '</tr>';

                    $('#eBayListings').append(htmlInj);
                });
            }

            $.each(json.data.linked, function(key, val){
                var product_id      = $('#product_id_'+key).val();
                var options_bool    = $('#options_'+key).val();
                var storeQty        = $('#store_qty_'+key).val();

                if(val.variants == 0){
                    $('#text_qty_'+key).text(val.qty);
                    $('#ebay_qty_'+key).val(val.qty);
                    $('#item_id_'+key).removeClass('unprocessed');

                    if($('#ebay_qty_'+key).val() == $('#store_qty_'+key).val()){
                        $('#text_status_'+key).text('OK');
                        $('#row_'+key+' > td').css('background-color', '#E3FFC8');
                    }else{
                        $('#text_status_'+key).text('Stock error');
                        $('#row_'+key+' > td').css('background-color', '#FFD4D4');
                        $('#text_buttons_'+key).html('<a onclick="updateLink('+key+','+val.qty+','+product_id+', '+storeQty+');" class="button"><span><?php echo $lang_resync; ?></span></a>');
                    }
                }else{
                    var htmlInj = '';

                    $.each(val.variants, function(key1, val1){
                        htmlInj += val1.qty+' x ';
                        $.each(val1.nv.NameValueList, function(key2, val2){
                            htmlInj += val2.Value+' > ';
                        });
                        htmlInj += '<br />';
                    });

                    $('#text_qty_'+key).html(htmlInj);
                    $('#item_id_'+key).removeClass('unprocessed');
                }
            });

            $.each($('.unprocessed'), function(key, val){
                $('#text_status_'+$(this).val()).text('Listing ended');
                $('#row_'+$(this).val()+' > td').css('background-color', '#FFD4D4');
                $('#text_buttons_'+$(this).val()).html('<a onclick="removeLink('+$(this).val()+');" class="button"><span>Remove link</span></a>');
            });

            $('#successBox').text('<?php echo $lang_ajax_loaded_ok; ?>').fadeIn();
            $('#loadListingsForLink').show();
            $('#imageLoadListingsForLink').hide();
        },
        failure: function(){
            $('#imageLoadListingsForLink').hide();
            $('#loadListingsForLink').show();
            $('#errorBox').text('<?php echo $lang_ajax_load_error; ?>').fadeIn();
        },
        error: function(){
            $('#imageLoadListingsForLink').hide();
            $('#loadListingsForLink').show();
            $('#errorBox').text('<?php echo $lang_ajax_load_error; ?>').fadeIn();
        }
    });
}

function removeLink(id) {
    $.ajax({
        type: 'GET',
        url: 'index.php?route=openbay/openbay/removeItemLink&token=<?php echo $token; ?>&ebay_id='+id,
        dataType: 'json',
        success: function(json) {
            $('#row_'+id).fadeOut('slow');
        }
    });
}

function updateLink(itemid, qty, product_id, storeQty){
    var r = confirm("<?php echo $lang_alert_stock_local; ?>");
    varBtnOld = $('#text_buttons_'+itemid).html();
    
    $('#text_buttons_'+itemid).html('<p class="center"><img src="<?php echo HTTPS_SERVER; ?>view/image/loading.gif" /></p>');

    if(r == true){
        $.ajax({
            type: 'GET',
            url: 'index.php?route=openbay/openbay/setProductStock&token=<?php echo $token; ?>&product_id='+product_id,
            dataType: 'json',
            success: function(json) {
                if(json.error === false){
                    $('#text_status_'+itemid).text('OK');
                    $('#row_'+itemid+' > td').css('background-color', '#E3FFC8');
                    $('#l_'+itemid+'_qtyinput').val(qty);
                    $('#l_'+itemid+'_qty').val(qty);
                    $('#text_qty_'+itemid).text(storeQty);
                    $('#text_buttons_'+itemid).empty();
                }else{
                    $('#text_buttons_'+itemid).html(varBtnOld);
                    alert('<?php echo $lang_ajax_load_error; ?>');
                }
            },
            failure: function(){
                $('#text_buttons_'+itemid).html(varBtnOld);
                alert('<?php echo $lang_ajax_load_error; ?>');
            },
            error: function(){
                $('#text_buttons_'+itemid).html(varBtnOld);
                alert('<?php echo $lang_ajax_load_error; ?>');
            }
        });
    }
}

function saveListingLink(id){
    var product_id      = $('#l_'+id+'_pid').val();
    var qty             = $('#l_'+id+'_qtyinput').val();
    var ebayqty         = $('#l_'+id+'_qtyebayinput').val();
    var variants        = $('#l_'+id+'_variants').val();

    if(product_id === ''){
        alert('<?php echo $lang_ajax_error_link; ?>');
        return false;
    }

    if(qty < 1){
        alert('<?php echo $lang_ajax_error_link_no_sk; ?>');
        return false;
    }

    $.ajax({
        url: 'index.php?route=openbay/openbay/saveItemLink&token=<?php echo $token; ?>&pid='+product_id+'&itemId='+id+'&qty='+qty+'&ebayqty='+ebayqty+'&variants='+variants,
        type: 'post',
        dataType: 'json',
        beforeSend: function(){
            $('#l_'+id+'_saveBtn').hide();
            $('#l_'+id+'_saveLoading').show();
        },
        success: function(json) {
            $('#row'+id).fadeOut('slow');
            $('#l_'+id+'_saveLoading').hide();
        }
    });
}

function getProductStock(id, elementId){
    $.ajax({
        type:'GET',
        dataType: 'json',
        url: 'index.php?route=openbay/openbay/getProductStock&token=<?php echo $token; ?>&pid='+id,
        success: function(data){
            if(data.variant == 0){
                $('#'+elementId+'_qty').text(data.qty);
                $('#'+elementId+'_qtyinput').val(data.qty);
                $('#'+elementId+'_allocated').text(data.allocated);
                $('#'+elementId+'_allocatedinput').val(data.allocated);
                $('#'+elementId+'_subtractinput').val(data.subtract);
                $('#'+elementId+'_saveBtn').show();
            }else{
                var injHtml = '';
                $.each(data.variant, function(key, val){
                    injHtml += val.stock+' x '+val.combi+'<br />';
                });
                $('#'+elementId+'_qty').html(injHtml);
                $('#'+elementId+'_saveBtn').show();
            }
        }
    });
}

$(".localName:not(.ui-autocomplete-input)").live("focus", function (event) {
    $(this).autocomplete({
        delay: 0,
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
});

$(document).ready(function() {
    $('#tabs a').tabs();
});
//--></script>
<?php echo $footer; ?>
