<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator'] ?><a href="<?php echo $breadcrumb['href'] ?>"><?php echo $breadcrumb['text'] ?></a>
        <?php } ?>
    </div>
    
    <?php if (isset($error_warning)) { ?>
    
    <div class="warning">
        <ul>
            <li><?php echo $error_warning ?></li>
        </ul>
    </div>
    
    <?php } ?>
        
    <?php if ($listing_errors) { ?>
    
    <div class="warning">
        <ul>
            <?php foreach ($listing_errors as $listing_error) { ?>
            
            <li><?php echo $listing_error ?></li>
            
            <?php } ?>
        </ul>
    </div>
    
    <?php } ?>

    <div class="box mBottom130">
        <div class="heading">
            <h1><?php echo $lang_title; ?></h1>
            <div class="buttons">
                <a onclick="location = '<?php echo $url_return; ?>';" class="button"><span><?php echo $button_return; ?></span></a>
            </div>
        </div>
        <div class="content">
            <div class="search_container">
                <div class="warning m10 displayNone" id="search_error"></div>

                <div class="border p10">
                    <p>
                        <input type="text" name="search_string" placeholder="<?php echo $lang_placeholder_search; ?>" id="search_string" class="width250" />
                        <a onclick="doSearch();" id="search_submit" class="button"><?php echo $button_search; ?></a>
                        <img src="<?php echo HTTPS_SERVER; ?>view/image/loading.gif" id="search_submit_loading" class="displayNone" />
                    </p>
                    <?php foreach ($marketplaces as $id => $name) {?>

                    <?php if ($default_marketplace == $id) { ?>
                    <input type="radio" name="marketplace" id="marketplace_<?php echo $id ?>" value="<?php echo $id ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="radio" name="marketplace" id="marketplace_<?php echo $id ?>" value="<?php echo $id ?>" />
                    <?php } ?>

                    <label for="marketplace_<?php echo $id ?>"><?php echo $name ?></label>

                    <?php } ?>
                </div>

                <p class="border p10 mtop5">
                    <span><?php echo $lang_not_in_catalog; ?></span><a href="<?php echo $url_advanced; ?>" id="create_new" class="button"><?php echo $button_new; ?></a>
                </p>
            </div>
            <div class="search_result displayNone" id="search_result_container">
                <table class="list">
                    <thead>
                    <tr>
                        <td class="center"><?php echo $column_image ?></td>
                        <td class="center"><?php echo $column_asin ?></td>
                        <td class="center"><?php echo $column_name ?></td>
                        <td class="center"><?php echo $column_price ?></td>
                        <td class="center"><?php echo $column_action ?></td>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div id="chosen_product" class="displayNone">

                <div id="chosen_product_preview" class="border p10 displayNone mBottom10"></div>

                <div id="tabs" class="htabs">
                    <a href="#required-info" id="tab-settings"><?php echo $tab_required_info; ?></a>
                    <a href="#additional-info" id="tab-product"><?php echo $tab_additional_info; ?></a>
                </div>

                <form method="POST" action="<?php echo $form_action ?>">
                    <input type="hidden" name="asin" value="" />
                    <input type="hidden" name="marketplace" value="<?php echo $default_marketplace ?>" />
                    <input type="hidden" name="product_id" value="<?php echo $product_id ?>" />

                    <div id="required-info">
                        <table class="form">
                            <tr>
                                <td><label for="quantity"><?php echo $entry_quantity; ?></label></td>
                                <td>
                                    <?php echo $quantity ?>
                                    <input type="hidden" name="quantity" id="quantity" value="<?php echo $quantity ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td><span class="required">*</span> <label for="sku"><?php echo $entry_sku; ?></label><br /><span class="help"><?php echo $help_sku ?></span></td>
                                <td><input type="text" name="sku" id="sku" value="<?php echo $sku ?>" class="width200" /></td>
                            </tr>
                            <tr>
                                <td><span class="required">*</span> <label for="condition"><?php echo $entry_condition; ?></label></td>
                                <td>
                                    <select name="condition" id="condition" class="width200">
                                        <?php foreach ($conditions as $value => $title): ?>
                                            <?php if($value == $default_condition): ?>
                                                <option selected="selected" value="<?php echo $value; ?>"><?php echo $title; ?></option>
                                            <? else: ?>
                                                <option value="<?php echo $value; ?>"><?php echo $title; ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="required">*</span> <label for="price"><?php echo $entry_price; ?></label></td>
                                <td>
                                    <div id="best_price_info" class="displayNone border mBottom10 p10 width200"></div>
                                    <input type="text" name="price" id="price" value="<?php echo $price ?>" /> <a id="button-amazon-price" onclick="getBestPrice()" class="button"><?php echo $button_amazon_price ?></a><img class="displayNone" id="loading-amazon-price" src="<?php echo HTTPS_SERVER; ?>view/image/loading.gif" />
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="additional-info">
                        <table class="form">
                            <tr>
                                <td><label for="condition_note"><?php echo $entry_condition_note; ?></label></td>
                                <td>
                                    <textarea cols="70" rows="15" name="condition_note" id="condition_note" placeholder="<?php echo $lang_placeholder_condition; ?>"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_sale_price ?><br /><span class="help"><?php echo $help_sale_price ?></span></td>
                                <td>
                                    <p>
                                        <label for="sale_price"><?php echo $entry_sale_price ?></label>
                                        <input id="sale_price" name="sale_price" value="" />
                                    </p>
                                    <p>
                                        <input id="sale_from" name="sale_from" class="date" value="" placeholder="<?php echo $entry_from ?>" />
                                        <input id="sale_to" name="sale_to" class="date" value="" placeholder="<?php echo $entry_to ?>" />
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="start_selling"><?php echo $entry_start_selling; ?></label><br /><span class="help">dd/mm/yy</span></td>
                                <td><input type="text" name="start_selling" id="start_selling" class="date" /></td>
                            </tr>
                            <tr>
                                <td><label for="restock_date"><?php echo $entry_restock_date; ?></label><br /><span class="help"><?php echo $help_restock_date ?></span></td>
                                <td><input type="text" name="restock_date" id="restock_date" class="date" /></td>
                            </tr>
                        </table>
                    </div>
                </form>
                <a class="button" id="button-list" onclick="validateQuickListing();" style="float:right;"><?php echo $button_list ?></a>
            </div>
        </div>
    </div>
</div>

<?php echo $footer; ?>
<script type="text/javascript">
function doSearch(){
    $('#search_string').val($.trim($('#search_string').val()));

    $.ajax({
        url: 'index.php?route=amazon/listing/search&token=<?php echo $token; ?>',
        type: 'POST',
        dataType: 'json',
        data: {search_string : encodeURIComponent($('#search_string').val()), marketplace: $('input[name="marketplace"]:checked').val()},
        beforeSend: function(){
            $('#search_submit').hide();
            $('#search_submit_loading').show();
            $('#search_error').hide();
            $('#search_result_container').hide();
            $('#chosen_product').hide();
        },
        success: function(data) {
            if(data.error){
                $('#search_error').empty().html(data.error).show();
            } else {
                var html = '';
                var count = 0;
                var funcString = '';

                $.each(data['data'], function(index, value) {

                    functString = "listProduct('" + value.asin + "')";

                    html += '<tr>';
                    html += '  <td class="center"><img src="' + value.image + '" /></td>';
                    html += '  <td class="center">' + value.asin + '</td>';
                    html += '  <td class="left">' + value.name + '</td>';
                    html += '  <td class="center">' + value.price + '</td>';
                    html += '  <td class="center">';
                    html += '    [<a target="_blank" href="' + value.link + '"><?php echo $text_view_on_amazon ?></a>]&nbsp;&nbsp;';
                    html += '    [<a onclick="' + functString + '"><?php echo $text_list ?></a>]';
                    html += '  </td>';
                    html += '</tr>';

                    count++;
                });

                if(count != 0){
                    $('#search_result_container tbody').html(html);
                    $('#search_result_container').css('opacity', 0).slideDown('slow').animate({ opacity: 1 },{ queue: false, duration: 'slow' });
                } else {
                    $('#search_error').empty().text('<?php echo $lang_no_results; ?>').show();
                }
            }

            $('#search_submit').show();
            $('#search_submit_loading').hide();
        },
        error: function(){
            alert('error');

            $('#search_submit').show();
            $('#search_submit_loading').hide();
            $('#search_error').hide();
        },
        failure: function(){
            alert('failure');

            $('#search_submit').show();
            $('#search_submit_loading').hide();
            $('#search_error').hide();
        }
    });
}

function getProduct(asin){
    $.ajax({
        url: 'index.php?route=amazon/listing/getProductByAsin&token=<?php echo $token; ?>',
        type: 'POST',
        dataType: 'json',
        data: {asin : asin, market : $('form input[name="marketplace"]').val() },
        beforeSend: function(){
            $('#chosen_product_preview').empty();
        },
        success: function(data) {

            var html = '';

            if(data.img != ''){
                html += '<img style="float:left;" src="'+data.img+'" />';
            }

            html += '<div style="float:left; margin-left:10px;"><h2 style="margin:0; padding:0;">'+data.title+'</h2>';
            html += '<h4 style="margin:0; padding:0; margin-top:10px;">ASIN: '+asin+'</h4></div>';
            html += '<div style="clear:both;"></div>';

            $('#chosen_product_preview').html(html).css('opacity', 0).slideDown('slow').animate({ opacity: 1 },{ queue: false, duration: 'slow' });
        },
        error: function(){
            alert('error');
        },
        failure: function(){
            alert('failure');
        }
    });
}

function getBestPrice() {
    $.ajax({
        url: 'index.php?route=amazon/listing/bestPrice&token=<?php echo $token; ?>',
        type: 'POST',
        dataType: 'json',
        data: $('form input[name="asin"], form select[name="condition"], form input[name="marketplace"]'),
        beforeSend: function(){
            $('.loading').remove();
            $('#button-amazon-price').hide();
            $('#loading-amazon-price').show();
            $('#best_price_info').hide();
        },
        success: function(data) {
            if (data['error']) {
                alert(data.error);
            } else {
                $('form input[name="price"]').val(data.data.amount);

                var priceHtml = '<strong>Price on Amazon:</strong> '+data.data.amount+' '+data.data.currency+'<br /><strong>+ Shipping:</strong> '+data.data.shipping+' '+data.data.currency;

                $('#best_price_info').html(priceHtml).css('opacity', 0).slideDown('slow').animate({ opacity: 1 },{ queue: false, duration: 'slow' });
            }

            $('.loading').remove();

            $('#button-amazon-price').show();
            $('#loading-amazon-price').hide();
        },
        error: function(){
            alert('error');
            $('.loading').remove();

            $('#button-amazon-price').show();
            $('#loading-amazon-price').hide();
        },
        failure: function(){
            alert('failure');
            $('.loading').remove();

            $('#button-amazon-price').show();
            $('#loading-amazon-price').hide();
        }
    });
}

function listProduct(asin) {
    getProduct(asin);
    $('form input[name="asin"]').val(asin);
    $('#chosen_product').css('opacity', 0).slideDown('slow').animate({ opacity: 1 },{ queue: false, duration: 'slow' });
    $('#search_result_container').css('opacity', 1).slideUp('medium').animate({ opacity: 0 },{ queue: false, duration: 'medium' });
    $('html, body').animate({ scrollTop: 0 }, 'slow');
}

function validateQuickListing(){
    var error = false;

    if($('#quantity').val() < 1){
        alert('<?php echo $error_stock; ?>');
        error = true;
    }

    if($('#price').val() == '' || $('#price').val() == 0){
        alert('<?php echo $error_price; ?>');
        error = true;
    }

    if($('#sku').val() == '' || $('#sku').val() == 0){
        alert('<?php echo $error_sku; ?>');
        error = true;
    }

    if(error == false){
        $('#chosen_product form').submit();
    }
}

$(document).ready(function() {
    $('.date').datepicker({dateFormat: 'yy-mm-dd'});

    $('.search_container input[name="marketplace"]').change(function(){
        $('form input[name="marketplace"]').val($(this).val());
    });

    $('#tabs a').tabs();
});
</script>
