<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>

    <div class="box">
        <div class="heading">
            <h1><?php echo $heading_title; ?></h1>
            <div class="buttons">
                <a class="button" onclick="bulkList()"><?php echo $button_list; ?></a>
                <a class="button" onclick="searchListings()"><?php echo $button_search; ?></a>
                <a class="button" onclick="location = '<?php echo $link_overview; ?>';" ><?php echo $button_return; ?></a>
            </div>
        </div>
            
        <div class="content">
            <?php if ($bulk_listing_status) { ?>
                <form id="product-form" action="" method="POST">
                    <table class="list">
                        <thead>
                            <tr>
                                <td colspan="4" class="left"><?php echo $text_listing_values ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="left"><?php echo $text_condition ?></td>
                                <td class="left">
                                    <select name="condition">
                                        <option value=""></option>
                                        <?php foreach ($conditions as $value => $name) { ?>
                                            <?php if ($value == $default_condition) { ?>
                                                <option selected="selected" value="<?php echo $value ?>"><?php echo $name ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $value ?>"><?php echo $name ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td class="left"><?php echo $text_condition_note ?></td>
                                <td class="left">
                                    <input type="text" name="condition_note" style="width: 98%" />
                                </td>
                            </tr>
                            <tr>
                                <td class="left"><?php echo $text_start_selling ?></td>
                                <td class="left">
                                    <input type="text" name="start_selling" class="date" />
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="list">
                        <thead>
                            <tr>
                                <td width="5" class="center"><input type="checkbox" /></td>
                                <td class="center"><?php echo $column_image; ?></td>
                                <td class="left"><?php echo $column_name; ?></td>
                                <td class="right"><?php echo $column_model; ?></td>
                                <td class="right"><?php echo $column_status; ?></td>
                                <td class="right"><?php echo $column_matches; ?></td>
                                <td class="left"><?php echo $column_result; ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product) { ?>
                                <tr>
                                    <td class="center"><input type="checkbox" name="product_ids[]" value="<?php echo $product['product_id'] ?>" /></td>
                                    <td class="center"><img src="<?php echo $product['image'] ?>" /></td>
                                    <td class="left"><a href="<?php echo $product['href'] ?>" target="_blank"><?php echo $product['name'] ?></a></td>
                                    <td class="right"><?php echo $product['model'] ?></td>
                                    <td class="right"><?php echo $product['search_status'] ?></td>
                                    <td class="right">
                                        <?php if ($product['matches'] !== null) { ?>
                                            <?php echo $product['matches'] ?>
                                        <?php } else { ?>
                                            -
                                        <?php } ?>
                                    </td>
                                    <td class="left" id="result-<?php echo $product['product_id'] ?>">
                                        <?php if ($product['matches'] !== null) { ?>
                                            <?php $checked = false; ?>
                                            <?php if ($product['matches'] > 0) { ?>
                                                <input class="amazon-listing" type="radio" name="products[<?php echo $product['product_id'] ?>]" value="" /> <?php echo $text_dont_list ?> <br />
                                                <?php foreach ($product['search_results'] as $search_result) { ?>
                                                    <?php if (!$checked) { ?>
                                                        <input class="amazon-listing" checked="checked" type="radio" name="products[<?php echo $product['product_id'] ?>]" value="<?php echo $search_result['asin'] ?>" />
                                                        <?php $checked = true; ?>
                                                    <?php } else { ?>
                                                        <input class="amazon-listing" type="radio" name="products[<?php echo $product['product_id'] ?>]" value="<?php echo $search_result['asin'] ?>" />
                                                    <?php } ?>
                                                    <a target="_blank" href="<?php echo $search_result['href'] ?>"><?php echo $search_result['title'] ?></a><br />
                                                <?php } ?>
                                            <?php } else { ?>
                                                <input class="amazon-listing" checked="checked" type="radio" name="products[<?php echo $product['product_id'] ?>]" value="" /> <?php echo $text_dont_list ?> <br />
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </form>
                <div class="pagination"><?php echo $pagination; ?></div>
            <?php } else { ?>
                <div class="warning"><?php echo $error_bulk_listing_not_allowed ?></div>
            <?php } ?>
        </div>
    </div>
</div>

<script type="text/javascript"><!--
    
    $(document).ready(function() {
        $('.date').datepicker({dateFormat: 'yy-mm-dd'});
        
        $('#product-form table thead input[type="checkbox"]').change(function(){
            var checkboxes = $('input[name="product_ids[]"]');
            if ($(this).is(':checked')) {
                checkboxes.attr('checked', 'checked');
            } else {
                checkboxes.removeAttr('checked');
            }
        });
        
        $('input[name="product_ids[]"]').change(function(){
            if (!$(this).is(':checked')) {
                $('#product-form table thead input[type="checkbox"]').removeAttr('checked');
            }
        });
    });
    
    function bulkList() {
        var request_data = $('input.amazon-listing:checked').serialize();
        
        var condition = $('select[name="condition"]').val();
        var condition_note = $('input[name="condition_note"]').val();
        
        if (condition && condition_note) {
            request_data += '&condition=' + encodeURIComponent(condition) + '&condition_note=' + encodeURIComponent(condition_note);
        }
        
        var start_selling = $('input[name="start_selling"]').val();
        
        if (start_selling) {
            request_data += '&start_selling=' + encodeURIComponent(start_selling);
        }
        
        $.ajax({
            url: 'index.php?route=openbay/amazonus/doBulkList&token=<?php echo $token ?>',
            data: request_data,
            dataType: 'json',
            type: 'POST',
            
            success: function(json) {
                $('.warning, .success').remove();
                
                var html = '';
                
                if (json.status) {
                    html = '<div class="success">' + json.message + '</div>';
                    $('input.amazon-listing:checked[value!=""]').parent().parent().fadeOut(450);
                } else {                    
                    html = '<div class="warning">' + json.message + '</div>';
                }
                
                $('.box').prepend(html);
            }
        });
    }
    
    function searchListings() {
        var request_data = $('input[name="product_ids[]"]:checked').serialize();

        $.ajax({
            url: 'index.php?route=openbay/amazonus/doBulkSearch&token=<?php echo $token ?>',
            data:  request_data,
            dataType: 'json',
            type: 'POST',
            
            success: function(json) {
                $.each(json, function(key, value){
                    var element = $('#result-' + key);
                    if (value.error) {
                        element.html('<div class="warning">' + value.error + '</span>');
                    } else if (value.success) {
                        element.html('<div class="success">' + value.success + '</span>');
                    }
                    
                    $('input[name="product_ids[]"]').removeAttr('checked');
                });
            }
        });
    }
    //--></script> 