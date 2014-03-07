<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php foreach($error_warning as $warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $warning; ?></div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <a onclick="confirmAction('<?php echo $cancel; ?>');" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn"><i class="fa fa-reply"></i></a>
      </div>
      <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $text_page_title; ?></h1>
    </div>
    <div class="panel-body" id="page-listing">
      <?php if (!isset($error_fail)) { ?>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
          <div class="well">
            <div class="row">
              <div class="col-sm-12 text-right">
                <a class="btn btn-primary" onclick="previewAll()" id="previewBtn"><span><?php echo $text_preview_all; ?></span></a>
                <a class="btn btn-primary" style="display:none;" onclick="editAll();" id="previewEditBtn"><span><?php echo $text_edit; ?></span></a>
                <a class="btn btn-primary" style="display:none;" onclick="submitAll();" id="submitBtn"><span><?php echo $text_submit; ?></span></a>
              </div>
            </div>
          </div>
          <?php if ($products) { ?>
            <?php $i = 0; ?>
            <?php foreach ($products as $product) { ?>
              <div class="well listingBox" id="p_row_<?php echo $i; ?>">
                <input type="hidden" class="pId openbayData_<?php echo $i; ?>" name="pId" value="<?php echo $i; ?>" />
                <input type="hidden" class="openbayData_<?php echo $i; ?>" name="product_id" value="<?php echo $product['product_id']; ?>" />
                <div class="row">
                  <div class="col-sm-8">
                    <div id="p_row_title_<?php echo $i; ?>" style="display:none;"></div>
                  </div>
                  <div id="p_row_buttons_<?php echo $i; ?>" class="col-sm-4 text-right">
                    <a class="btn btn-danger" onclick="removeBox('<?php echo $i; ?>')"><i class="fa fa-minus-circle"></i> <?php echo $text_remove; ?></a>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="row" id="p_row_msg_<?php echo $i; ?>" style="display:none;">
                      <div class="col-sm-12" id="p_msg_<?php echo $i; ?>">
                        <i class="fa fa-cog fa-lg fa-spin"></i>
                      </div>
                    </div>
                    <div class="row" class="p_row_content_<?php echo $i; ?>">
                      <div class="col-sm-2">
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" />
                      </div>
                      <div class="col-sm-5">
                        <p><label style="display:inline-block;" class="width100 mRight10 bold"><?php echo $text_title; ?>:</label><input type="text" name="title" class="openbayData_<?php echo $i; ?> form-control" value="<?php echo $product['name']; ?>" id="title_<?php echo $i; ?>" /></p>
                        <input type="hidden" name="price_original" id="price_original_<?php echo $i; ?>" value="<?php echo number_format($product['price']*(($default['defaults']['tax']/100) + 1), 2, '.', ''); ?>" />
                        <p><label style="display:inline-block;" class="width100 mRight10 bold"><?php echo $text_price; ?>:</label><input id="price_<?php echo $i; ?>" type="text" name="price" class="openbayData_<?php echo $i; ?> form-control" value="<?php echo number_format($product['price']*(($default['defaults']['tax']/100) + 1), 2, '.', ''); ?>" /></p>
                        <p><label style="display:inline-block;" class="width100 mRight10 bold"><?php echo $text_stock; ?>:</label><?php echo $product['quantity']; ?></p>
                        <input type="hidden" name="qty" value="<?php echo $product['quantity']; ?>" class="openbayData_<?php echo $i; ?>" />

                        <div class="buttons right">
                          <a class="btn btn-primary" style="display:none;" onclick="showFeatures('<?php echo $i; ?>');" id="editFeature_<?php echo $i; ?>"><?php echo $text_features; ?></a>
                          <a class="btn btn-primary" style="display:none;" onclick="showCatalog('<?php echo $i; ?>');" id="editCatalog_<?php echo $i; ?>" ><?php echo $text_catalog; ?></a>
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
                                <td><?php echo $text_catalog_search; ?>:</td>
                                <td>
                                  <div class="buttons">
                                    <input type="text" name="catalog_search" id="catalog_search_<?php echo $i; ?>" value="" class="form-control"/>
                                    <a onclick="searchEbayCatalog('<?php echo $i; ?>');" class="btn btn-primary" id="catalog_search_btn_<?php echo $i; ?>"><span><?php echo $text_search; ?></span></a>
                                    <img src="view/image/loading.gif" id="catalog_search_img_<?php echo $i; ?>" class="displayNone" alt="Loading" />
                                  </div>
                                </td>
                              </tr>
                            </table>

                            <!-- container for the product catalog information -->
                            <div id="catalogDiv_<?php echo $i; ?>"></div>

                            <input type="hidden" class="openbayData_<?php echo $i; ?>" name="catalog_epid" id="catalog_epid_<?php echo $i; ?>" value="0" />

                          </div>
                        </div>
                      </div>
                      <div class="col-sm-5">
                        <p>
                          <label style="display:inline-block;" class="mRight10 bold width100"><?php echo $text_profile_theme; ?></label>
                          <select name="theme_profile" class="width250 openbayData_<?php echo $i; ?>">
                            <?php foreach($default['profiles_theme'] as $s){ echo '<option value="'.$s['ebay_profile_id'].'"'.($default['profiles_theme_def'] == $s['ebay_profile_id'] ? ' selected' : '').'>'.$s['name'].'</option>'; } ?>
                          </select>
                        </p>
                        <p>
                          <label style="display:inline-block;" class="mRight10 bold width100"><?php echo $text_profile_shipping; ?></label>
                          <select name="shipping_profile" class="width250 openbayData_<?php echo $i; ?>">
                            <?php foreach($default['profiles_shipping'] as $s){ echo '<option value="'.$s['ebay_profile_id'].'"'.($default['profiles_shipping_def'] == $s['ebay_profile_id'] ? ' selected' : '').'>'.$s['name'].'</option>'; } ?>
                          </select>
                        </p>
                        <p>
                          <label style="display:inline-block;" class="mRight10 bold width100"><?php echo $text_profile_generic; ?></label>
                          <select name="generic_profile" id="generic_profile_<?php echo $i; ?>" class="width250 openbayData_<?php echo $i; ?>" onchange="genericProfileChange(<?php echo $i; ?>);">
                            <?php foreach($default['profiles_generic'] as $s){ echo '<option value="'.$s['ebay_profile_id'].'"'.($default['profiles_generic_def'] == $s['ebay_profile_id'] ? ' selected' : '').'>'.$s['name'].'</option>'; } ?>
                          </select>
                        </p>
                        <p>
                          <label style="display:inline-block;" class="mRight10 bold width100"><?php echo $text_profile_returns; ?></label>
                          <select name="return_profile" class="width250 openbayData_<?php echo $i; ?>">
                            <?php foreach($default['profiles_returns'] as $s){ echo '<option value="'.$s['ebay_profile_id'].'"'.($default['profiles_returns_def'] == $s['ebay_profile_id'] ? ' selected' : '').'>'.$s['name'].'</option>'; } ?>
                          </select>
                        </p>
                        <p id="conditionContainer_<?php echo $i; ?>" class="displayNone">
                          <label style="display:inline-block; width:100px;" class="mRight10 bold"><?php echo $text_condition; ?> </label>
                          <select name="condition" id="conditionRow_<?php echo $i; ?>" class="displayNone width250 openbayData_<?php echo $i; ?>"></select>
                          <img id="conditionLoading_<?php echo $i; ?>" src="view/image/loading.gif" alt="Loading" />
                        </p>
                        <p id="durationContainer_<?php echo $i; ?>" class="displayNone">
                          <label style="display:inline-block; width:100px;" class="mRight10 bold"><?php echo $text_duration; ?> </label>
                          <select name="duration" id="durationRow_<?php echo $i; ?>" class="displayNone width250 openbayData_<?php echo $i; ?>"></select>
                          <img id="durationLoading_<?php echo $i; ?>" src="view/image/loading.gif" alt="Loading" />
                        </p>
                      </div>
                    </div>
                    <div class="row" class="p_row_content_<?php echo $i; ?>">
                      <div class="col-sm-7">
                        <div class="alert alert-info" id="loadingSuggestedCat_<?php echo $i; ?>"><i class="fa fa-cog fa-lg fa-spin"></i> <?php echo $text_category; ?></div>
                        <div class="text-left" id="suggestedCat_<?php echo $i; ?>"></div>
                        <input type="hidden" name="finalCat" id="finalCat_<?php echo $i; ?>" class="openbayData_<?php echo $i; ?>" />
                      </div>
                      <div class="col-sm-5">
                        <div id="cSelections_<?php echo $i; ?>" class="form-control" style="display:none;">
                          <div class="row form-group">
                            <div class="col-sm-12">
                              <select id="catsSelect1_<?php echo $i; ?>" class="form-control" onchange="loadCategories(2, false, <?php echo $i; ?>);"></select>
                            </div>
                          </div>
                          <div class="row form-group">
                            <div class="col-sm-12">
                              <select id="catsSelect2_<?php echo $i; ?>" class="form-control" onchange="loadCategories(3, false, <?php echo $i; ?>);" style="display:none;"></select>
                            </div>
                          </div>
                          <div class="row form-group">
                            <div class="col-sm-12">
                              <select id="catsSelect3_<?php echo $i; ?>" class="form-control" onchange="loadCategories(4, false, <?php echo $i; ?>);" style="display:none;"></select>
                            </div>
                          </div>
                          <div class="row form-group">
                            <div class="col-sm-12">
                              <select id="catsSelect4_<?php echo $i; ?>" class="form-control" onchange="loadCategories(5, false, <?php echo $i; ?>, false, <?php echo $i; ?>);" style="display:none;"></select>
                            </div>
                          </div>
                          <div class="row form-group">
                            <div class="col-sm-12">
                              <select id="catsSelect5_<?php echo $i; ?>" class="form-control" onchange="loadCategories(6, false, <?php echo $i; ?>);" style="display:none;"></select>
                            </div>
                          </div>
                          <div class="row form-group">
                            <div class="col-sm-12">
                              <select id="catsSelect6_<?php echo $i; ?>" class="form-control" onchange="loadCategories(7, false, <?php echo $i; ?>);" style="display:none;"></select>
                            </div>
                          </div>
                          <i class="fa fa-cog fa-lg fa-spin" id="imageLoading_<?php echo $i; ?>"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>


                  </div>
                </div>
              </div>
              <?php $i++;?>
            <?php } ?>
          <?php } else { ?>
            <div class="alert alert-danger"><?php echo $text_no_results; ?></div>
          <?php } ?>
        </form>
      <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="overlay-verify">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-body">
              <p class="bold"><i class="fa fa-cog fa-lg fa-spin"></i> <?php echo $text_verifying; ?></p>
              <p><?php echo $text_processing; ?></p>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="overlay-loading">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-body">
              <p class="bold"><i class="fa fa-cog fa-lg fa-spin"></i> <?php echo $text_loading; ?></p>
              <p><?php echo $text_preparing0; ?> <span id="ajaxCountDoneDisplay">0</span> <?php echo $text_preparing1; ?> <span id="ajaxCountTotalDisplay">0</span> <?php echo $text_preparing2; ?> </p>
              <div class="buttons">
                <a class="btn btn-primary" href="index.php?route=extension/openbay/itemList&token=<?php echo $token; ?>"><span><?php echo $text_cancel; ?></span></a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php } else { ?>
        <?php foreach($error_fail as $fail) { ?>
          <div class="alert alert-danger"><?php echo $fail; ?></div>
        <?php } ?>
      <?php } ?>
    </div>
  </div>
</div>

<input type="hidden" id="totalItems" value="<?php echo $count; ?>" name="totalItems" />
<input type="hidden" id="ajaxCount" value="0" />
<input type="hidden" class="ajaxCountTotal" id="ajaxCountTotal" value="0" />
<input type="hidden" class="ajaxCountDone" id="ajaxCountDone" value="0" />

<script type="text/javascript">
    $(document).ready(function() {
      overlay('overlay-loading');

        <?php $j = 0; while($j < $i){ ?>
            getSuggestedCategories('<?php echo (int)$j; ?>');
            modifyPrices('<?php echo (int)$j; ?>');
        <?php $j++; } ?>

        $('#activeItems').text($('#totalItems').val());
    });

    function overlay(screen) {
      $('#'+screen).modal('toggle');
    }

    function overlayHide() {
      $('.modal').modal('hide')
    }

    $(document).keyup(function(e) {
        if (e.keyCode == 27) {
            alert('<?php echo $text_esc_key; ?>');
            overlayHide();
        }
    });

    function modifyPrices(id){
        var price_original  = parseFloat($('#price_original_'+id).val());
        var price_modified = '';
        var modify_percent = '';

        $.ajax({
            url: 'index.php?route=openbay/ebay_profile/profileGet&token=<?php echo $token; ?>&ebay_profile_id='+$('#generic_profile_'+id).val(),
            type: 'GET',
            async: true,
            dataType: 'json',
            beforeSend: function(){ addCount(); },
            success: function(data) {

                if(data.data.price_modify !== false && typeof data.data.price_modify !== 'undefined'){
                    modify_percent = 100 + parseFloat(data.data.price_modify);
                    modify_percent = parseFloat(modify_percent / 100);
                    price_modified = price_original * modify_percent;

                    $('#price_'+id).val(parseFloat(price_modified).toFixed(2));
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
            overlayHide();
        }
    }

    function removeBox(id){
        $('#p_row_'+id).fadeOut('medium');

        setTimeout(function(){
            $('#p_row_'+id).remove();
        }, 1000);

        $('#totalItems').val($('#totalItems').val()-1);

        if ($('.listingBox').length == 1){
            window.location = "index.php?route=extension/openbay/itemList&token=<?php echo $token; ?>";
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
            url: 'index.php?route=openbay/ebay/getSuggestedCategories&token=<?php echo $token; ?>&qry='+qry,
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
            url: 'index.php?route=openbay/ebay/getCategories&token=<?php echo $token; ?>&parent='+parent,
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
            url: 'index.php?route=openbay/ebay/getCategoryFeatures&token=<?php echo $token; ?>&category='+cat,
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
            url: 'index.php?route=openbay/ebay/getEbayCategorySpecifics&token=<?php echo $token; ?>&category='+cat,
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
                                htmlInj2 += '<option value="">-- <?php echo $text_select; ?> --</option>';

                                //force an array in case of single element
                                val.ValueRecommendation = $.makeArray(val.ValueRecommendation);

                                $.each(val.ValueRecommendation, function(key2, option){
                                    htmlInj2 += '<option value="'+option.Value+'">'+option.Value+'</option>';
                                });

                                if(val.ValidationRules.SelectionMode == 'FreeText'){
                                    htmlInj2 += '<option value="Other"><?php echo $text_other; ?></option>';
                                }
                                htmlInj += '<tr><td class="ebaySpecificTitle left">'+val.Name+'</td><td><select name="feat['+val.Name+']" class="ebaySpecificSelect openbayData_'+id+' left" id="spec_sel_'+specificCount+'" onchange="toggleSpecOther('+specificCount+');">'+htmlInj2+'</select><br /><span id="spec_'+specificCount+'_other" class="ebaySpecificSpan"><p><?php echo $text_other; ?>:&nbsp;<input type="text" name="featother['+val.Name+']" class="ebaySpecificOther openbayData_'+id+' form-control" /></p></span></td></tr>';

                            }else if(("ValueRecommendation" in val) && (val.ValidationRules.MaxValues > 1)){
                                htmlInj += '<tr><td class="ebaySpecificTitle left">'+val.Name+'</td><td class="left">';

                                //force an array in case of single element
                                val.ValueRecommendation = $.makeArray(val.ValueRecommendation);

                                $.each(val.ValueRecommendation, function(key2, option){
                                    htmlInj += '<p><input type="checkbox" name="feat['+val.Name+'][]" value="'+option.Value+'" class="openbayData_'+id+'"/>'+option.Value+'</p>';
                                });

                                htmlInj += '</td></tr>';
                            }else{
                                htmlInj += '<tr><td class="ebaySpecificTitle left">'+val.Name+'</td><td><input type="text" name="feat['+val.Name+']" class="ebaySpecificInput openbayData_'+id+' form-control" /></td></tr>';
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
            alert('<?php echo $text_search_text; ?>');
        }

        $.ajax({
            url: 'index.php?route=openbay/ebay/searchEbayCatalog&token=<?php echo $token; ?>',
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
                        $('#catalogDiv_'+id).append('<?php echo $text_catalog_no_products; ?>');
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
                $('#catalogDiv_'+id).append('<?php echo $text_search_failed; ?>');
            },
            error: function(){
                $('#catalog_search_btn_'+id).show();
                $('#catalog_search_img_'+id).hide();
                $('#catalogDiv_'+id).append('<?php echo $text_search_failed; ?>');
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

        overlay('overlay-loading');

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
                url: 'index.php?route=openbay/ebay/verifyBulk&token=<?php echo $token; ?>&i='+id,
                type: 'POST',
                dataType: 'json',
                data: processedData,
                beforeSend: function(){ addCount(); },
                success: function(data) {
                    if(data.ack != 'Failure'){

                        var msgHtml = '';
                        var feeTot = '';
                        var currencyCode = '';

                        $('#p_row_buttons_'+data.i).prepend('<a class="btn btn-primary p_row_buttons_prev" target="_BLANK" href="'+data.preview+'"><?php echo $text_preview; ?></a>');


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
                    alert('<?php echo $text_error_reverify; ?>');
                },
                error: function(){
                    removeCount();
                    alert('<?php echo $text_error_reverify; ?>');
                }
            });
        });
    }

    function submitAll(){
        var confirm_box = confirm('<?php echo $text_ajax_confirm_listing; ?>');
        if (confirm_box) {
            var id = '';
            var name = '';
            var processedData = '';

            overlay('overlay-loading');

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
                    url: 'index.php?route=openbay/ebay/listItemBulk&token=<?php echo $token; ?>&i='+id,
                    type: 'POST',
                    dataType: 'json',
                    data: $(".openbayData_"+id).serialize(),
                    beforeSend: function(){ addCount(); },
                    success: function(data) {
                        if(data.ack != 'Failure'){
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

                            $('#p_row_buttons_'+data.i).prepend('<a class="btn btn-primary p_row_buttons_view" href="<?php echo $listing_link; ?>'+data.itemid+'" target="_BLANK"><?php echo $text_view; ?></a>');

                            msgHtml += '<div class="success" style="margin:5px;"><?php echo $text_listed; ?>'+data.itemid+'</div>';

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

    function showFeatures(id){
        overlay('featurePage_'+id);
    }

    function showCatalog(id){
        overlay('catalogPage_'+id);
    }

    function genericProfileChange(id){
        modifyPrices(id);
    }
</script>
<?php echo $footer; ?>