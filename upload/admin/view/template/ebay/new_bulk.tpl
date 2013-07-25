<?php 
echo $header; 
$i = 0; 
?>
<div id="content">
    <?php if(!isset($error_fail)){ ?>

    <?php foreach($error_warning as $warning) { ?>
        <div class="warning mBottom10"><?php echo $warning; ?></div>
    <?php } ?>

    <div class="box">
        <div class="heading">
            <h1><?php echo $lang_page_title; ?></h1>
            <div class="buttons">
                <a class="button" onclick="previewAll()" id="previewBtn"><span><?php echo $lang_preview_all; ?></span></a>
                <a class="button" style="display:none" onclick="editAll();" id="previewEditBtn"><span>Edit</span></a>
                <a class="button" style="display:none" onclick="submitAll();" id="submitBtn"><span>Submit</span></a>
            </div>
        </div>
        <form id="form">
            <table class="list">
                <tbody>
                    <tr>
                        <td>
                            <?php if ($products) { ?>
                            <?php foreach ($products as $product) { ?>

                            <div class="box mTop15 listingBox" id="p_row_<?php echo $i; ?>">
                                <input type="hidden" class="pId openbayData_<?php echo $i; ?>" name="pId" value="<?php echo $i; ?>" />
                                <input type="hidden" class="openbayData_<?php echo $i; ?>" name="product_id" value="<?php echo $product['product_id']; ?>" />
                                <div class="heading">
                                    <div id="p_row_title_<?php echo $i; ?>" style="float:left;" class="displayNone bold m0 p10"></div>
                                    <div id="p_row_buttons_<?php echo $i; ?>" class="buttons right">
                                        <a class="button" onclick="removeBox('<?php echo $i; ?>')"><span><?php echo $lang_remove; ?></span></a>
                                    </div>
                                </div>
                                <table class="m0 border borderNoBottom" style="width:100%;" cellpadding="0" cellspacing="0">
                                    <tr id="p_row_msg_<?php echo $i; ?>" class="displayNone">
                                        <td colspan="3" id="p_msg_<?php echo $i; ?>">
                                            <img src="<?php echo HTTPS_SERVER; ?>view/image/loading.gif" style="margin:10px;" />
                                        </td>
                                    </tr>

                                    <tr class="p_row_content_<?php echo $i; ?>">
                                        <td class="center width100"><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" /></td>
                                        <td class="left width390" valign="top">
                                            <p><label style="display:inline-block;" class="width100 mRight10 bold">Title:</label><input type="text" name="title" class="openbayData_<?php echo $i; ?> width250" value="<?php echo $product['name']; ?>" id="title_<?php echo $i; ?>" /></p>
                                            <p><label style="display:inline-block;" class="width100 mRight10 bold">Price:</label><input type="text" name="price" class="openbayData_<?php echo $i; ?> width50" value="<?php echo number_format($product['price']*(($default['defaults']['tax']/100) + 1), 2); ?>"/></p>
                                            <p><label style="display:inline-block;" class="width100 mRight10 bold">Stock:</label><?php echo $product['quantity']; ?></p>
                                            <input type="hidden" name="qty" value="<?php echo $product['quantity']; ?>" class="openbayData_<?php echo $i; ?>" />
                                            
                                            <div class="buttons right">
                                                <a class="button" onclick="showFeatures('<?php echo $i; ?>');" id="editFeature_<?php echo $i; ?>" style="display:none;"><span>Edit features</span></a>
                                                <a class="button" onclick="showCatalog('<?php echo $i; ?>');" id="editCatalog_<?php echo $i; ?>" style="display:none;"><span>Select catalog</span></a>
                                            </div>
                                            
                                            <div id="featurePage_<?php echo $i; ?>" class="greyScreenBox featurePage">
                                                <div class="bold border p5 previewClose">X</div>
                                                <div class="previewContentScroll">
                                                    <table class="form" id="featureRow_<?php echo $i; ?>"></table>
                                                </div>
                                            </div>

                                            <!-- main product catalog popup box -->
                                            <div id="catalogPage_<?php echo $i; ?>" class="greyScreenBox featurePage">
                                                <div class="bold border p5 previewClose">X</div>
                                                <div class="previewContentScroll">

                                                    <!-- catalog search area -->
                                                    <table class="form">
                                                        <tr>
                                                            <td>Search catalog:</td>
                                                            <td>
                                                                <div class="buttons">
                                                                    <input type="text" name="catalog_search" id="catalog_search_<?php echo $i; ?>" value="" />
                                                                    <a onclick="searchEbayCatalog('<?php echo $i; ?>');" class="button" id="catalog_search_btn_<?php echo $i; ?>"><span>Search</span></a>
                                                                    <img src="<?php echo HTTPS_SERVER; ?>view/image/loading.gif" id="catalog_search_img_<?php echo $i; ?>" class="displayNone" />
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <!-- container for the product catalog information -->
                                                    <div id="catalogDiv_<?php echo $i; ?>"></div>

                                                    <input type="hidden" class="openbayData_<?php echo $i; ?>" name="catalog_epid" id="catalog_epid_<?php echo $i; ?>" value="0" />

                                                </div>
                                            </div>
                                        </td>
                                        <td class="p10">
                                            <p>
                                                <label style="display:inline-block;" class="mRight10 bold width100"><?php echo $lang_profile_theme; ?></label>
                                                <select name="theme_profile" class="width250 openbayData_<?php echo $i; ?>">
                                                    <?php foreach($default['profiles_theme'] as $s){ echo '<option value="'.$s['ebay_profile_id'].'"'.($default['profiles_theme_def'] == $s['ebay_profile_id'] ? ' selected' : '').'>'.$s['name'].'</option>'; } ?>
                                                </select>
                                            </p>
                                            <p>
                                                <label style="display:inline-block;" class="mRight10 bold width100"><?php echo $lang_profile_shipping; ?></label>
                                                <select name="shipping_profile" class="width250 openbayData_<?php echo $i; ?>">
                                                    <?php foreach($default['profiles_shipping'] as $s){ echo '<option value="'.$s['ebay_profile_id'].'"'.($default['profiles_shipping_def'] == $s['ebay_profile_id'] ? ' selected' : '').'>'.$s['name'].'</option>'; } ?>
                                                </select>
                                            </p>
                                            <p>
                                                <label style="display:inline-block;" class="mRight10 bold width100"><?php echo $lang_profile_generic; ?></label>
                                                <select name="generic_profile" class="width250 openbayData_<?php echo $i; ?>">
                                                    <?php foreach($default['profiles_generic'] as $s){ echo '<option value="'.$s['ebay_profile_id'].'"'.($default['profiles_generic_def'] == $s['ebay_profile_id'] ? ' selected' : '').'>'.$s['name'].'</option>'; } ?>
                                                </select>
                                            </p>
                                            <p>
                                                <label style="display:inline-block;" class="mRight10 bold width100"><?php echo $lang_profile_returns; ?></label>
                                                <select name="return_profile" class="width250 openbayData_<?php echo $i; ?>">
                                                    <?php foreach($default['profiles_returns'] as $s){ echo '<option value="'.$s['ebay_profile_id'].'"'.($default['profiles_returns_def'] == $s['ebay_profile_id'] ? ' selected' : '').'>'.$s['name'].'</option>'; } ?>
                                                </select>
                                            </p>
                                            <p id="conditionContainer_<?php echo $i; ?>" class="displayNone">
                                                <label style="display:inline-block; width:100px;" class="mRight10 bold"><?php echo $lang_condition; ?> </label>
                                                <select name="condition" id="conditionRow_<?php echo $i; ?>" class="displayNone width250 openbayData_<?php echo $i; ?>"></select>
                                                <img id="conditionLoading_<?php echo $i; ?>" src="<?php echo HTTPS_SERVER; ?>view/image/loading.gif" />
                                            </p>
                                            <p id="durationContainer_<?php echo $i; ?>" class="displayNone">
                                                <label style="display:inline-block; width:100px;" class="mRight10 bold"><?php echo $lang_duration; ?> </label>
                                                <select name="duration" id="durationRow_<?php echo $i; ?>" class="displayNone width250 openbayData_<?php echo $i; ?>"></select>
                                                <img id="durationLoading_<?php echo $i; ?>" src="<?php echo HTTPS_SERVER; ?>view/image/loading.gif" />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="p_row_content_<?php echo $i; ?>">
                                        <td colspan="3" style="padding:5px;">
                                            <p class="bold m0"><?php echo $lang_category; ?> <img src="<?php echo HTTPS_SERVER; ?>view/image/loading.gif" id="loadingSuggestedCat_<?php echo $i; ?>" /></p>

                                            <div class="left pLeft10" id="suggestedCat_<?php echo $i; ?>"></div>

                                            <div style="clear:both;"></div>

                                            <div id="cSelections_<?php echo $i; ?>" class="displayNone left mTop10 pLeft30">
                                                <select id="catsSelect1_<?php echo $i; ?>" class="mLeft30" onchange="loadCategories(2, false, <?php echo $i; ?>);"></select>
                                                <select id="catsSelect2_<?php echo $i; ?>" class="displayNone mLeft20" onchange="loadCategories(3, false, <?php echo $i; ?> );"></select>
                                                <select id="catsSelect3_<?php echo $i; ?>" class="displayNone mLeft20" onchange="loadCategories(4, false, <?php echo $i; ?> );"></select>
                                                <select id="catsSelect4_<?php echo $i; ?>" class="displayNone mLeft20" onchange="loadCategories(5, false, <?php echo $i; ?> );"></select>
                                                <select id="catsSelect5_<?php echo $i; ?>" class="displayNone mLeft20" onchange="loadCategories(6, false, <?php echo $i; ?> );"></select>
                                                <select id="catsSelect6_<?php echo $i; ?>" class="displayNone mLeft20" onchange="loadCategories(7, false, <?php echo $i; ?> );"></select>
                                                <img src="<?php echo HTTPS_SERVER; ?>view/image/loading.gif" id="imageLoading_<?php echo $i; ?>" class="displayNone" />
                                            </div>

                                            <input type="hidden" name="finalCat" id="finalCat_<?php echo $i; ?>" class="openbayData_<?php echo $i; ?>" />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <?php $i++; } ?>
                            <?php } else { ?>
                    <tr>
                        <td class="center" colspan="3"><?php echo $text_no_results; ?></td>
                    </tr>
            </table>
            <?php } ?>
            </td></tr>
            </tbody>
            </table>
        </form>
    </div>
    <div id="greyScreen"></div>
    <div id="loadingPage" class="greyScreenBox">
        <p class="bold"><img src="<?php echo HTTPS_SERVER; ?>view/image/loading.gif" /> Loading details</p>
        <p>Preparing <span id="ajaxCountDoneDisplay">0</span> of <span id="ajaxCountTotalDisplay">0</span> elements for <?php echo count($products); ?> items </p>
        <div class="buttons">
            <a class="button" href="index.php?route=extension/openbay/itemList&token=<?php echo $this->request->get['token']; ?>"><span>Cancel</span></a>
        </div>
    </div>
    <div id="loadingVerify" class="greyScreenBox">
        <p class="bold"><img src="<?php echo HTTPS_SERVER; ?>view/image/loading.gif" /> <?php echo $lang_verifying; ?></p>
        <p><?php echo $lang_processing; ?></p>
    </div>
    <div id="previewPage" class="greyScreenBox">
        <div class="bold border p5 previewClose">X</div>
        <div class="previewContent">
            <iframe id="previewContentIframe" frameborder="0" height="100%" width="100%" style="margin-left:auto; margin-right:auto;" scrolling="auto"></iframe>
        </div>
    </div>
    <?php }else{ ?>
        <?php foreach($error_fail as $fail) { ?>
        <div class="warning mBottom10"><?php echo $fail; ?></div>
    <?php } ?>
    <?php } ?>
</div>

<input type="hidden" id="totalItems" value="<?php echo $count; ?>" name="totalItems" />
<input type="hidden" id="ajaxCount" value="0" />
<input type="hidden" class="ajaxCountTotal" id="ajaxCountTotal" value="0" />
<input type="hidden" class="ajaxCountDone" id="ajaxCountDone" value="0" />

<script type="text/javascript">
    $(document).ready(function() {
        showGreyScreen('loadingPage');

        <?php $j = 0; while($j < $i){ ?>
            getSuggestedCategories('<?php echo (int)$j; ?>');
        <?php $j++; } ?>

        $('#activeItems').text($('#totalItems').val());
    });

    $(document).keyup(function(e) {
        if (e.keyCode == 27) {
            alert('<?php echo $lang_esc_key; ?>');
            hideGreyScreen();
        }
    });

    function addCount(){
        var count = parseInt($('#ajaxCount').val()) + 1;
        $('#ajaxCount').val(count);
        var count1 = parseInt($('#ajaxCountTotal').val())+1;
        $('#ajaxCountTotal').val(count1);
        $('#ajaxCountTotalDisplay').text(count1);
    }

    function removeCount(){
        var count = parseInt($('#ajaxCount').val())-1;
        $('#ajaxCount').val(count);
        var count1 = parseInt($('#ajaxCountDone').val())+1;
        $('#ajaxCountDone').val(count1);
        $('#ajaxCountDoneDisplay').text(count1);

        if(count == 0){
            hideGreyScreen();
        }
    }

    function removeBox(id){
        $('#p_row_'+id).fadeOut('medium');
    
        setTimeout(function(){
            $('#p_row_'+id).remove();
        }, 1000);

        $('#totalItems').val($('#totalItems').val()-1);

        if ($('.listingBox').length == 1){
            window.location = "index.php?route=extension/openbay/itemList&token=<?php echo $this->request->get['token']; ?>";
        }else{
            $('#activeItems').text($('#totalItems').val());
        }
    }

    function useManualCategory(id){
        loadCategories(1, true, id);
        $('#cSelections_'+id).show();
    }

    function getSuggestedCategories(id){
        var qry = $('#title_'+id).val();

        $.ajax({
            url: 'index.php?route=openbay/openbay/getSuggestedCategories&token=<?php echo $token; ?>&qry='+qry,
            type: 'GET',
            async: true,
            dataType: 'json',
            beforeSend: function(){ $('#loadingSuggestedCat_'+id).show(); addCount(); },
            success: function(data) {
                $('#suggestedCat_'+id).empty();

                var htmlInj = '';

                if(data.error == false && data.data){
                    var i = 1;

                        $.each(data.data, function(key,val){
                            if(val.percent != 0) {
                                htmlInj += '<p style="margin:0px; padding:0 0 0 10px;"><input type="radio" id="suggested_category_'+id+'" name="suggested_'+id+'" value="'+val.id+'" onchange="categorySuggestedChange('+val.id+','+id+')"';
                                if(i == 1){ 
                                    htmlInj += ' checked="checked"';
                                    categorySuggestedChange(val.id, id);
                                }
                                htmlInj += '/> ('+val.percent+'% match) '+val.name+'</p>';
                            }
                            i++;
                        });

                        htmlInj += '<p style="margin:0px; padding:0 0 0 10px;"><input type="radio" id="manual_use_category_'+id+'" name="suggested_'+id+'" value="" onchange="useManualCategory('+id+')" /> Choose category</p>';
                        $('#suggestedCat_'+id).html(htmlInj);
                }else{
                    htmlInj += '<p style="margin:0px; padding:0 0 0 10px;"><input type="radio" id="manual_use_category_'+id+'" name="suggested_'+id+'" value="" onchange="useManualCategory('+id+')" checked="checked" /> Choose category</p>';
                    $('#suggestedCat_'+id).html(htmlInj);
                    useManualCategory(id);
                }

                $('#loadingSuggestedCat_'+id).hide();
                removeCount();
            },
            failure: function(){
                $('#loadingSuggestedCat_'+id).hide();
                removeCount();
            },
            error: function(){
                $('#loadingSuggestedCat_'+id).hide();
                removeCount();
            }
        });
    }

    function loadCategories(level, skip, id){
        var parent = '';
        
        if(level == 1){
            parent = ''
        }else{
            var prevLevel = level - 1;
            parent = $('#catsSelect'+prevLevel+'_'+id).val();
        }

        var countI = level;

        while(countI <= 6){
            $('#catsSelect'+countI+'_'+id).hide().empty();
            countI++;
        }

        $.ajax({
            url: 'index.php?route=openbay/openbay/getCategories&token=<?php echo $token; ?>&parent='+parent,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(){
                $('#cSelections_'+id).removeClass('success').addClass('attention');
                $('#imageLoading_'+id).show();
                addCount();
            },
            success: function(data) {
                if(data.items != null){
                    $('#catsSelect'+level+'_'+id).empty();
                    $('#catsSelect'+level+'_'+id).append('<option value="">-- SELECT --</option>');
                    $.each(data.cats, function(key, val) {
                        if(val.CategoryID != parent){
                            $('#catsSelect'+level+'_'+id).append('<option value="'+val.CategoryID+'">'+val.CategoryName+'</option>');
                        }
                    });

                    if(skip != true){
                        $('#finalCat_'+id).val('');
                    }

                    $('#catsSelect'+level+'_'+id).show();
                }else{
                    if(data.error){

                    }else{
                        $('#finalCat_'+id).val($('#catsSelect'+prevLevel+'_'+id).val());
                        $('#cSelections_'+id).removeClass('attention').addClass('success');
                        getCategoryFeatures($('#catsSelect'+prevLevel+'_'+id).val(), id);
                    }
                }
                $('#imageLoading_'+id).hide();
                removeCount();
            },
            failure: function(){
                removeCount();
            },
            error: function(){
                removeCount();
            }
        });
    }

    function getCategoryFeatures(cat, id){
        itemFeatures(cat, id);
        $('#editCatalog_'+id).show();

        $('#durationRow_'+id).hide();
        $('#durationLoading_'+id).show();
        $('#durationContainer_'+id).show();

        $('#conditionRow_'+id).hide();
        $('#conditionLoading_'+id).show();
        $('#conditionContainer_'+id).show();

        $.ajax({
            url: 'index.php?route=openbay/openbay/getCategoryFeatures&token=<?php echo $token; ?>&category='+cat,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(){ addCount(); },
            success: function(data) {
                if(data.error == false){
                    var htmlInj = '';

                    listingDuration(data.data.durations, id);

                    if(data.data.conditions){
                        $.each(data.data.conditions, function(key, val){
                            htmlInj += '<option value='+val.id+'>'+val.name+'</option>';
                        });

                        if(htmlInj == ''){
                            $('#conditionRow_'+id).empty();
                            $('#conditionContainer_'+id).hide();
                            $('#conditionRow_'+id).hide();
                            $('#conditionLoading_'+id).hide();
                        }else{
                            $('#conditionRow_'+id).empty().html(htmlInj);
                            $('#conditionRow_'+id).show();
                            $('#conditionLoading_'+id).hide();
                        }
                    }
                }else{
                    alert(data.msg);
                }
                removeCount();
            },
            failure: function(){
                removeCount();
            },
            error: function(){
                removeCount();
            }
        });
    }
    
    function itemFeatures(cat, id){
        $('#editFeature_'+id).hide();
        
        $.ajax({
            url: 'index.php?route=openbay/openbay/getEbayCategorySpecifics&token=<?php echo $token; ?>&category='+cat,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(){ addCount(); },
            success: function(data) {
                if(data.error == false){
                    $('#featureRow_'+id).empty();

                    var htmlInj = '';
                    var htmlInj2 = '';
                    var specificCount = 0;

                    if(data.data.Recommendations.NameRecommendation){

                        data.data.Recommendations.NameRecommendation = $.makeArray(data.data.Recommendations.NameRecommendation);

                        $.each(data.data.Recommendations.NameRecommendation, function(key, val){
                            htmlInj2 = '';

                            if(("ValueRecommendation" in val) && (val.ValidationRules.MaxValues == 1)){
                                htmlInj2 += '<option value="">-- <?php echo $lang_select; ?> --</option>';

                                //force an array in case of single element
                                val.ValueRecommendation = $.makeArray(val.ValueRecommendation);

                                $.each(val.ValueRecommendation, function(key2, option){
                                    htmlInj2 += '<option value="'+option.Value+'">'+option.Value+'</option>';
                                });

                                if(val.ValidationRules.SelectionMode == 'FreeText'){
                                    htmlInj2 += '<option value="Other"><?php echo $lang_other; ?></option>';
                                }
                                htmlInj += '<tr><td class="ebaySpecificTitle left">'+val.Name+'</td><td><select name="feat['+val.Name+']" class="ebaySpecificSelect openbayData_'+id+' left" id="spec_sel_'+specificCount+'" onchange="toggleSpecOther('+specificCount+');">'+htmlInj2+'</select><br /><span id="spec_'+specificCount+'_other" class="ebaySpecificSpan"><p><?php echo $lang_other; ?>:&nbsp;<input type="text" name="featother['+val.Name+']" class="ebaySpecificOther openbayData_'+id+'" /></p></span></td></tr>';

                            }else if(("ValueRecommendation" in val) && (val.ValidationRules.MaxValues > 1)){
                                htmlInj += '<tr><td class="ebaySpecificTitle left">'+val.Name+'</td><td class="left">';

                                //force an array in case of single element
                                val.ValueRecommendation = $.makeArray(val.ValueRecommendation);

                                $.each(val.ValueRecommendation, function(key2, option){
                                    htmlInj += '<p><input type="checkbox" name="feat['+val.Name+'][]" value="'+option.Value+'" class="openbayData_'+id+'"/>'+option.Value+'</p>';
                                });

                                htmlInj += '</td></tr>';
                            }else{
                                htmlInj += '<tr><td class="ebaySpecificTitle left">'+val.Name+'</td><td><input type="text" name="feat['+val.Name+']" class="ebaySpecificInput openbayData_'+id+' left" /></td></tr>';
                            }

                            specificCount++;
                        });


                        $('#featureRow_'+id).append(htmlInj);
                    }else{
                        $('#featureRow_'+id).text('None');
                    }
                }else{
                    alert(data.msg);
                }

                $('#editFeature_'+id).show();
                
                removeCount();
            },
            failure: function(){
                removeCount();
            },
            error: function(){
                removeCount();
            }
        });
    }

    function toggleSpecOther(id){
        var selectVal = $('#spec_sel_'+id).val();

        if(selectVal == 'Other'){
            $('#spec_'+id+'_other').show();
        }else{
            $('#spec_'+id+'_other').hide();
        }
    }

    function searchEbayCatalog(id){
        var qry = $('#catalog_search_'+id).val();
        var cat = $('#finalCat_'+id).val();

        var html = '';

        if(qry == ''){
            alert('<?php echo $lang_search_text; ?>');
        }
        
        $.ajax({
            url: 'index.php?route=openbay/openbay/searchEbayCatalog&token=<?php echo $token; ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                categoryId: cat,
                page: 1,
                search: qry
            },
            beforeSend: function(){
                $('#catalog_search_btn_'+id).hide();
                $('#catalog_search_img_'+id).show();
                $('#catalogDiv_'+id).empty();
            },
            success: function(data) {
                $('#catalog_search_btn_'+id).show();
                $('#catalog_search_img_'+id).hide();
                if(data.error == false){
                    if(data.data.productSearchResult.paginationOutput.totalEntries == 0 || data.data.ack == 'Failure'){
                        $('#catalogDiv_'+id).append('<?php echo $lang_catalog_no_products; ?>');
                    }else{
                        data.data.productSearchResult.products = $.makeArray(data.data.productSearchResult.products);

                        $.each(data.data.productSearchResult.products, function(key, val){
                            processCatalogItem(val, id);
                        });
                    }
                }
            },
            failure: function(){
                $('#catalog_search_btn_'+id).show();
                $('#catalog_search_img_'+id).hide();
                $('#catalogDiv_'+id).append('<?php echo $lang_search_failed; ?>');
            },
            error: function(){
                $('#catalog_search_btn_'+id).show();
                $('#catalog_search_img_'+id).hide();
                $('#catalogDiv_'+id).append('<?php echo $lang_search_failed; ?>');
            }
        });
    }
    
    function processCatalogItem(val, id){
        html = '';
        html += '<div style="float:left; display:inline; width:450px; height:100px; padding:5px; margin-right:10px; margin-bottom:10px;" class="border">';
            html += '<div style="vertical-align:middle; float:left; display:inline; width:20px; height:100px; vertical-align:middle;">';
                html += '<input type="radio" class="openbayData_'+id+'" name="catalog_epid_'+id+'" value="'+val.productIdentifier.ePID+'" />';
            html += '</div>';
            html += '<div style="float:left; display:inline; width:100px; height:100px; overflow:hidden; text-align: center;">';
                html += '<img src="'+val.stockPhotoURL.thumbnail.value+'" />';
            html += '</div>';
            html += '<div style="float:left; display:inline; width:300px;">';
                html += '<p style="line-height:24px;">'+val.productDetails.value.text.value+'</p>';
            html += '</div>';
        html += '</div>';

        $('#catalogDiv_'+id).append(html);
    }

    function listingDuration(data, id){
        var lang            = new Array();
        var listingDefault  = '<?php echo (isset($default['defaults']['listing_duration']) ? $default['defaults']['listing_duration'] : ''); ?>';

        lang["Days_1"]      = '1 Day';
        lang["Days_3"]      = '3 Days';
        lang["Days_5"]      = '5 Days';
        lang["Days_7"]      = '7 Days';
        lang["Days_10"]     = '10 Days';
        lang["Days_30"]     = '30 Days';
        lang["GTC"]         = 'GTC';

        htmlInj        = '';
        $.each(data, function(key, val){
            htmlInj += '<option value="'+val+'"';
            if(val == listingDefault){ htmlInj += ' selected="selected"';}
            htmlInj += '>'+lang[val]+'</option>';
        });

        $('#durationRow_'+id).empty().html(htmlInj);
        $('#durationRow_'+id).show();
        $('#durationLoading_'+id).hide();
    }

    function categorySuggestedChange(val, id){
        $('#cSelections_'+id).hide();
        loadCategories(1, true, id);
        $('input[name=finalCat]').attr('value', val);
        getCategoryFeatures(val, id);
    }

    function editAll(){
        var id = '';
        var name = '';

        $('#previewBtn').show();
        $('#previewEditBtn').hide();
        $('#submitBtn').hide();
        $('.p_row_buttons_prev').remove();
        $('.p_row_buttons_view').remove();

        $.each($('.pId'), function(i){
            id = $(this).val();
            name = $('#title_'+$(this).val()).val();
            $('#p_row_msg_'+$(this).val()).hide();
            $('.p_row_content_'+$(this).val()).show();
            $('#p_row_title_'+$(this).val()).text(name).hide();
            $('#p_msg_'+i).empty();
        });
    }

    function previewAll(){
        var id = '';
        var name = '';
        var processedData = '';

        showGreyScreen('loadingVerify');

        $('.warning').hide();
        $('#previewBtn').hide();
        $('#previewEditBtn').show();
        $('#submitBtn').show();

        $.each($('.pId'), function(i){
            id = $(this).val();
            name = $('#title_'+$(this).val()).val();
        
            $('#p_row_msg_'+$(this).val()).show();
            $('.p_row_content_'+$(this).val()).hide();
            $('#p_row_title_'+$(this).val()).text(name).show();

            //set the catalog id if chosen
            $('#catalog_epid_'+id).val($("input[type='radio'][name='catalog_epid_"+id+"']:checked").val());

            processedData = $(".openbayData_"+id).serialize();

            $.ajax({
                url: 'index.php?route=openbay/openbay/verifyBulk&token=<?php echo $token; ?>&i='+id,
                type: 'POST',
                dataType: 'json',
                data: processedData,
                beforeSend: function(){ addCount(); },
                success: function(data) {
                    if(data.ack != 'Failure'){
                        var prevHtml = "previewListing('"+data.preview+"')";
                        var msgHtml = '';
                        var feeTot = '';
                        var currencyCode = '';

                        $('#p_row_buttons_'+data.i).prepend('<a class="button p_row_buttons_prev" onclick="'+prevHtml+'"><?php echo $lang_preview; ?></a>');

                        if(data.errors){
                            $.each(data.errors, function(k,v){
                                msgHtml += '<div class="attention" style="margin:5px;">'+v+'</div>';
                            });
                        }

                        $.each(data.fees, function(key, val){
                            if(val.Fee != 0.0 && val.Name != 'ListingFee'){
                                feeTot = feeTot + parseFloat(val.Fee);
                            }
                            currencyCode = val.Cur;
                        });

                        msgHtml += '<div class="success" style="margin:5px;">Total fees: '+currencyCode+' '+feeTot+'</div>';

                        $('#p_msg_'+data.i).html(msgHtml);
                    }else{
                        var errorHtml = '';

                        $.each(data.errors, function(k,v){
                            errorHtml += '<div class="warning" style="margin:5px;">'+v+'</div>';
                        });

                        $('#p_msg_'+data.i).html(errorHtml);
                    }
                    removeCount();
                },
                failure: function(){
                    removeCount();
                    alert('There was an error, you should edit and re-verify the items');
                },
                error: function(){
                    removeCount();
                    alert('There was an error, you should edit and re-verify the items');
                }
            });
        });
    }

    function submitAll(){
        var confirm_box = confirm('<?php echo $lang_ajax_confirm_listing; ?>');
        if (confirm_box) {
            var id = '';
            var name = '';
            var processedData = '';
            
            showGreyScreen('loadingVerify');

            $('.warning').hide();
            $('.attention').hide();
            $('#previewBtn').hide();
            $('#previewEditBtn').hide();
            $('#submitBtn').hide();
            $('.p_row_buttons_prev').remove();

            $.each($('.pId'), function(i){
                id = $(this).val();
                name = $('#title_'+$(this).val()).val();

                $('#p_row_msg_'+$(this).val()).show();
                $('.p_row_content_'+$(this).val()).hide();
                $('#p_row_title_'+$(this).val()).text(name).show();

                $.ajax({
                    url: 'index.php?route=openbay/openbay/listItemBulk&token=<?php echo $token; ?>&i='+id,
                    type: 'POST',
                    dataType: 'json',
                    data: $(".openbayData_"+id).serialize(),
                    beforeSend: function(){ addCount(); },
                    success: function(data) {
                        if(data.ack != 'Failure'){
                            var prevHtml = "previewListing('"+data.preview+"')";
                            var msgHtml = '';
                            var feeTot = '';
                            var currencyCode = '';

                            if(data.errors){
                                $.each(data.errors, function(k,v){
                                    msgHtml += '<div class="attention" style="margin:5px;">'+v+'</div>';
                                });
                            }

                            $.each(data.fees, function(key, val){
                                if(val.Fee != 0.0 && val.Name != 'ListingFee'){
                                    feeTot = feeTot + parseFloat(val.Fee);
                                }
                                currencyCode = val.Cur;
                            });

                            $('#p_row_buttons_'+data.i).prepend('<a class="button p_row_buttons_view" href="<?php echo $listing_link; ?>'+data.itemid+'" target="_BLANK"><?php echo $lang_view; ?></a>');

                            msgHtml += '<div class="success" style="margin:5px;"><?php echo $lang_listed; ?>'+data.itemid+'</div>';

                            $('#p_msg_'+data.i).html(msgHtml);
                        }else{
                            var errorHtml = '';

                            $.each(data.errors, function(k,v){
                                errorHtml += '<div class="warning" style="margin:5px;">'+v+'</div>';
                            });

                            $('#p_msg_'+data.i).html(errorHtml);
                        }
                        removeCount();
                    },
                    failure: function(){
                        removeCount();
                    },
                    error: function(){
                        removeCount();
                    }
                });
            });

        }
    }

    function previewListing(url){
        showGreyScreen('previewPage');
        $('#previewContentIframe').attr('src', url);
    }
    
    function showFeatures(id){
        showGreyScreen('featurePage_'+id);
    }
    
    function showCatalog(id){
        showGreyScreen('catalogPage_'+id);
    }
</script>
<?php echo $footer; ?>