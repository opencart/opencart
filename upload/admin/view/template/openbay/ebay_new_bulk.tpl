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
                <a class="btn btn-primary" onclick="previewAll()" id="previewBtn"><?php echo $text_preview_all; ?></a>
                <a class="btn btn-primary" style="display:none;" onclick="editAll();" id="previewEditBtn"><?php echo $text_edit; ?></a>
                <a class="btn btn-primary" style="display:none;" onclick="submitAll();" id="submitBtn"><?php echo $text_submit; ?></a>
              </div>
            </div>
          </div>
          <?php if ($products) { ?>
            <?php $i = 0; ?>
            <?php foreach ($products as $product) { ?>
              <div class="well listingBox" id="p_row_<?php echo $i; ?>">
                <input type="hidden" class="pId openbayData_<?php echo $i; ?>" name="pId" value="<?php echo $i; ?>" />
                <input type="hidden" class="openbayData_<?php echo $i; ?>" name="product_id" value="<?php echo $product['product_id']; ?>" />
                <input type="hidden" name="price_original" id="price_original_<?php echo $i; ?>" value="<?php echo number_format($product['price']*(($default['defaults']['tax']/100) + 1), 2, '.', ''); ?>" />
                <input type="hidden" class="openbayData_<?php echo $i; ?>" name="catalog_epid" id="catalog_epid_<?php echo $i; ?>" value="0" />
                <div class="row">
                  <div class="col-sm-8">
                    <div id="p_row_title_<?php echo $i; ?>" style="display:none;"></div>
                  </div>
                  <div class="col-sm-4 form-group" id="p_row_buttons_<?php echo $i; ?>">
                    <a class="btn btn-danger btn-sm pull-right" onclick="removeBox('<?php echo $i; ?>')"><i class="fa fa-minus-circle"></i> <?php echo $text_remove; ?></a>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="row" id="p_row_msg_<?php echo $i; ?>" style="display:none;">
                      <div class="col-sm-12" id="p_msg_<?php echo $i; ?>">
                        <i class="fa fa-cog fa-lg fa-spin"></i>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-2 text-center">
                        <div class="row">
                          <div class="col-sm-12 form-group">
                            <img class="img-thumbnail" src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" />
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-12 form-group">
                            <a class="btn btn-primary" style="display:none;" onclick="showFeatures('<?php echo $i; ?>');" id="editFeature_<?php echo $i; ?>"><?php echo $text_features; ?></a>
                            <a class="btn btn-primary" style="display:none;" onclick="showCatalog('<?php echo $i; ?>');" id="editCatalog_<?php echo $i; ?>" ><?php echo $text_catalog; ?></a>
                          </div>

                          <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="overlay-feature-<?php echo $i; ?>" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                <div class="modal-body" id="feature-data-<?php echo $i; ?>"></div>
                              </div>
                            </div>
                          </div>

                          <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="catalogPage_<?php echo $i; ?>" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                <div class="modal-body">
                                  <div class="panel panel-default">
                                    <div class="panel-heading">
                                      <div class="text-right"><a onclick="overlayHide();" class="btn pull-right"><i class="fa fa-times"></i></a></div>
                                      <h1 class="panel-title"><?php echo $text_catalog_search; ?></h1>
                                    </div>
                                    <div class="panel-body">
                                      <div class="well">
                                        <div class="row">
                                          <div class="form-group">
                                            <label class="col-sm-2 control-label"><?php echo $text_search_term; ?></label>
                                            <div class="col-sm-10">
                                              <input type="text" name="catalog_search" id="catalog_search_<?php echo $i; ?>" value="" class="form-control"/>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="row">
                                          <div class="col-sm-12 text-right">
                                            <a onclick="searchEbayCatalog('<?php echo $i; ?>');" class="btn btn-primary" id="catalog_search_btn_<?php echo $i; ?>"><?php echo $text_search; ?></a>
                                          </div>
                                        </div>
                                      </div>
                                      <div id="catalogDiv_<?php echo $i; ?>" style="display:none;"></div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-5" class="p_row_content_<?php echo $i; ?>">
                        <div class="form-group">
                          <label class="col-sm-2 control-label"><?php echo $text_title; ?></label>
                          <div class="col-sm-10">
                            <input type="text" name="title" class="openbayData_<?php echo $i; ?> form-control" value="<?php echo $product['name']; ?>" id="title_<?php echo $i; ?>" />
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label"><?php echo $text_price; ?></label>
                          <div class="col-sm-10">
                            <input id="price_<?php echo $i; ?>" type="text" name="price" class="openbayData_<?php echo $i; ?> form-control" value="<?php echo number_format($product['price']*(($default['defaults']['tax']/100) + 1), 2, '.', ''); ?>" />
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label"><?php echo $text_stock; ?></label>
                          <div class="col-sm-10">
                            <span class="label label-success"><?php echo $product['quantity']; ?></span>
                            <input type="hidden" name="qty" value="<?php echo $product['quantity']; ?>" class="openbayData_<?php echo $i; ?>" />
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label"><?php echo $text_profile_theme; ?></label>
                          <div class="col-sm-10">
                            <select name="theme_profile" class="openbayData_<?php echo $i; ?> form-control">
                              <?php foreach($default['profiles_theme'] as $s) { echo '<option value="'.$s['ebay_profile_id'].'"'.($default['profiles_theme_def'] == $s['ebay_profile_id'] ? ' selected' : '').'>'.$s['name'].'</option>'; } ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label"><?php echo $text_profile_shipping; ?></label>
                          <div class="col-sm-10">
                            <select name="shipping_profile" class="openbayData_<?php echo $i; ?> form-control">
                              <?php foreach($default['profiles_shipping'] as $s) { echo '<option value="'.$s['ebay_profile_id'].'"'.($default['profiles_shipping_def'] == $s['ebay_profile_id'] ? ' selected' : '').'>'.$s['name'].'</option>'; } ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label"><?php echo $text_profile_generic; ?></label>
                          <div class="col-sm-10">
                            <select name="generic_profile" id="generic_profile_<?php echo $i; ?>" class="openbayData_<?php echo $i; ?> form-control" onchange="genericProfileChange(<?php echo $i; ?>);">
                              <?php foreach($default['profiles_generic'] as $s) { echo '<option value="'.$s['ebay_profile_id'].'"'.($default['profiles_generic_def'] == $s['ebay_profile_id'] ? ' selected' : '').'>'.$s['name'].'</option>'; } ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label"><?php echo $text_profile_returns; ?></label>
                          <div class="col-sm-10">
                            <select name="return_profile" class="openbayData_<?php echo $i; ?> form-control">
                              <?php foreach($default['profiles_returns'] as $s) { echo '<option value="'.$s['ebay_profile_id'].'"'.($default['profiles_returns_def'] == $s['ebay_profile_id'] ? ' selected' : '').'>'.$s['name'].'</option>'; } ?>
                            </select>
                          </div>
                        </div>
                        <div class="alert alert-info" id="conditionLoading_<?php echo $i; ?>"><i class="fa fa-cog fa-lg fa-spin"></i> <?php echo $text_loading_condition; ?></div>
                        <div class="form-group" id="conditionContainer_<?php echo $i; ?>" style="display:none;">
                          <label class="col-sm-2 control-label"><?php echo $text_condition; ?></label>
                          <div class="col-sm-10">
                            <select name="condition" class="openbayData_<?php echo $i; ?> form-control" id="conditionRow_<?php echo $i; ?>">
                              <?php foreach($default['profiles_returns'] as $s) { echo '<option value="'.$s['ebay_profile_id'].'"'.($default['profiles_returns_def'] == $s['ebay_profile_id'] ? ' selected' : '').'>'.$s['name'].'</option>'; } ?>
                            </select>
                          </div>
                        </div>
                        <div class="alert alert-info" id="durationLoading_<?php echo $i; ?>"><i class="fa fa-cog fa-lg fa-spin"></i> <?php echo $text_loading_duration; ?></div>
                        <div class="form-group" id="durationContainer_<?php echo $i; ?>" style="display:none;">
                          <label class="col-sm-2 control-label"><?php echo $text_duration; ?></label>
                          <div class="col-sm-10">
                            <select name="duration" class="openbayData_<?php echo $i; ?> form-control" id="durationRow_<?php echo $i; ?>">
                              <?php foreach($default['profiles_returns'] as $s) { echo '<option value="'.$s['ebay_profile_id'].'"'.($default['profiles_returns_def'] == $s['ebay_profile_id'] ? ' selected' : '').'>'.$s['name'].'</option>'; } ?>
                            </select>
                          </div>
                        </div>

                      </div>
                      <div class="col-sm-5" class="p_row_content_<?php echo $i; ?>">
                        <div class="row">
                          <div class="col-sm-12 form-group">
                            <h4><?php echo $text_category; ?></h4>
                            <div class="alert alert-info" id="loadingSuggestedCat_<?php echo $i; ?>"><i class="fa fa-cog fa-lg fa-spin"></i> <?php echo $text_category; ?></div>
                            <div class="text-left" id="suggestedCat_<?php echo $i; ?>"></div>
                            <input type="hidden" name="finalCat" id="finalCat_<?php echo $i; ?>" class="openbayData_<?php echo $i; ?>" />
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-12 form-group">
                            <div id="cSelections_<?php echo $i; ?>" style="display:none;">
                              <div class="alert alert-info" id="imageLoading_<?php echo $i; ?>"><i class="fa fa-cog fa-lg fa-spin"></i> <?php echo $text_loading_categories; ?></div>
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
        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="overlay-verify" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-body">
                <p class="bold"><i class="fa fa-cog fa-lg fa-spin"></i> <?php echo $text_verifying; ?></p>
                <p><?php echo $text_processing; ?></p>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="overlay-loading" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-body">
                <div class="progress progress-striped active">
                  <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%" id="loading-bar"></div>
                </div>

                <p><?php echo $text_preparing0; ?> <span id="ajaxCountDoneDisplay">0</span> <?php echo $text_preparing1; ?> <span id="ajaxCountTotalDisplay">0</span> <?php echo $text_preparing2; ?> </p>
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

    <?php $j = 0; while($j < $i) { ?>
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

  function modifyPrices(id) {
      var price_original  = parseFloat($('#price_original_'+id).val());
      var price_modified = '';
      var modify_percent = '';

      $.ajax({
          url: 'index.php?route=openbay/ebay_profile/profileGet&token=<?php echo $token; ?>&ebay_profile_id='+$('#generic_profile_'+id).val(),
          type: 'GET',
          async: true,
          dataType: 'json',
          beforeSend: function() { addCount(); },
          success: function(data) {

              if(data.data.price_modify !== false && typeof data.data.price_modify !== 'undefined') {
                  modify_percent = 100 + parseFloat(data.data.price_modify);
                  modify_percent = parseFloat(modify_percent / 100);
                  price_modified = price_original * modify_percent;

                  $('#price_'+id).val(parseFloat(price_modified).toFixed(2));
              }

              removeCount();
          },
          failure: function() {
              removeCount();
          },
          error: function() {
              removeCount();
          }
      });
  }

  function addCount() {
    var count = parseInt($('#ajaxCount').val()) + 1;
    $('#ajaxCount').val(count);
    var count1 = parseInt($('#ajaxCountTotal').val())+1;
    $('#ajaxCountTotal').val(count1);
    $('#ajaxCountTotalDisplay').text(count1);
  }

  function removeCount() {
      var count = parseInt($('#ajaxCount').val())-1;
      $('#ajaxCount').val(count);
      var count1 = parseInt($('#ajaxCountDone').val())+1;
      $('#ajaxCountDone').val(count1);
      $('#ajaxCountDoneDisplay').text(count1);

      var modifier = 0;
      var current = 0;
      var total = $('#ajaxCountTotal').val();

      modifier = 100 / total;
      current = parseFloat(modifier * count1);

      $('#loading-bar').css('width', current+'%');

      if(count == 0) {
          overlayHide();
      }
  }

  function removeBox(id) {
      $('#p_row_'+id).fadeOut('medium');

      setTimeout(function() {
          $('#p_row_'+id).remove();
      }, 1000);

      $('#totalItems').val($('#totalItems').val()-1);

      if ($('.listingBox').length == 1) {
          window.location = "index.php?route=extension/openbay/itemList&token=<?php echo $token; ?>";
      } else {
          $('#activeItems').text($('#totalItems').val());
      }
  }

  function useManualCategory(id) {
      loadCategories(1, true, id);
      $('#cSelections_'+id).show();
  }

  function getSuggestedCategories(id) {
      var qry = $('#title_'+id).val();

      $.ajax({
          url: 'index.php?route=openbay/ebay/getSuggestedCategories&token=<?php echo $token; ?>&qry='+qry,
          type: 'GET',
          async: true,
          dataType: 'json',
          beforeSend: function() { $('#loadingSuggestedCat_'+id).show(); addCount(); },
          success: function(data) {
              $('#suggestedCat_'+id).empty();

              var htmlInj = '';

              if(data.error == false && data.data) {
                  var i = 1;

                      $.each(data.data, function(key,val) {
                          if(val.percent != 0) {
                              htmlInj += '<p style="margin:0px; padding:0 0 0 10px;"><input type="radio" id="suggested_category_'+id+'" name="suggested_'+id+'" value="'+val.id+'" onchange="categorySuggestedChange('+val.id+','+id+')"';
                              if(i == 1) {
                                  htmlInj += ' checked="checked"';
                                  categorySuggestedChange(val.id, id);
                              }
                              htmlInj += '/> ('+val.percent+'% match) '+val.name+'</p>';
                          }
                          i++;
                      });

                      htmlInj += '<p style="margin:0px; padding:0 0 0 10px;"><input type="radio" id="manual_use_category_'+id+'" name="suggested_'+id+'" value="" onchange="useManualCategory('+id+')" /> Choose category</p>';
                      $('#suggestedCat_'+id).html(htmlInj);
              } else {
                  htmlInj += '<p style="margin:0px; padding:0 0 0 10px;"><input type="radio" id="manual_use_category_'+id+'" name="suggested_'+id+'" value="" onchange="useManualCategory('+id+')" checked="checked" /> Choose category</p>';
                  $('#suggestedCat_'+id).html(htmlInj);
                  useManualCategory(id);
              }

              $('#loadingSuggestedCat_'+id).hide();
              removeCount();
          },
          failure: function() {
              $('#loadingSuggestedCat_'+id).hide();
              removeCount();
          },
          error: function() {
              $('#loadingSuggestedCat_'+id).hide();
              removeCount();
          }
      });
  }

  function loadCategories(level, skip, id) {
      var parent = '';

      if(level == 1) {
          parent = ''
      } else {
          var prevLevel = level - 1;
          parent = $('#catsSelect'+prevLevel+'_'+id).val();
      }

      var countI = level;

      while(countI <= 6) {
          $('#catsSelect'+countI+'_'+id).hide().empty();
          countI++;
      }

      $.ajax({
          url: 'index.php?route=openbay/ebay/getCategories&token=<?php echo $token; ?>&parent='+parent,
          type: 'GET',
          dataType: 'json',
          beforeSend: function() {
              $('#imageLoading_'+id).show();
              addCount();
          },
          success: function(data) {
              if(data.items != null) {
                  $('#catsSelect'+level+'_'+id).empty();
                  $('#catsSelect'+level+'_'+id).append('<option value="">-- SELECT --</option>');
                  $.each(data.cats, function(key, val) {
                      if(val.CategoryID != parent) {
                          $('#catsSelect'+level+'_'+id).append('<option value="'+val.CategoryID+'">'+val.CategoryName+'</option>');
                      }
                  });

                  if(skip != true) {
                      $('#finalCat_'+id).val('');
                  }

                  $('#catsSelect'+level+'_'+id).show();
              } else {
                  if(data.error) {

                  } else {
                      $('#finalCat_'+id).val($('#catsSelect'+prevLevel+'_'+id).val());
                      getCategoryFeatures($('#catsSelect'+prevLevel+'_'+id).val(), id);
                  }
              }
              $('#imageLoading_'+id).hide();
              removeCount();
          },
          failure: function() {
              removeCount();
          },
          error: function() {
              removeCount();
          }
      });
  }

  function getCategoryFeatures(cat, id) {
      itemFeatures(cat, id);
      $('#editCatalog_'+id).show();

      $('#durationLoading_'+id).show();
      $('#durationContainer_'+id).hide();

      $('#conditionLoading_'+id).show();
      $('#conditionContainer_'+id).hide();

      $.ajax({
          url: 'index.php?route=openbay/ebay/getCategoryFeatures&token=<?php echo $token; ?>&category='+cat,
          type: 'GET',
          dataType: 'json',
          beforeSend: function() { addCount(); },
          success: function(data) {
              if(data.error == false) {
                  var htmlInj = '';

                  listingDuration(data.data.durations, id);

                  if(data.data.conditions) {
                      $.each(data.data.conditions, function(key, val) {
                          htmlInj += '<option value='+val.id+'>'+val.name+'</option>';
                      });

                      if(htmlInj == '') {
                          $('#conditionRow_'+id).empty();
                          $('#conditionContainer_'+id).hide();
                          $('#conditionLoading_'+id).hide();
                      } else {
                          $('#conditionRow_'+id).empty().html(htmlInj);
                          $('#conditionContainer_'+id).show();
                          $('#conditionLoading_'+id).hide();
                      }
                  }
              } else {
                  alert(data.msg);
              }
              removeCount();
          },
          failure: function() {
              removeCount();
          },
          error: function() {
              removeCount();
          }
      });
  }

  function itemFeatures(cat, id) {
      $('#editFeature_'+id).hide();

      $.ajax({
          url: 'index.php?route=openbay/ebay/getEbayCategorySpecifics&token=<?php echo $token; ?>&category='+cat,
          type: 'GET',
          dataType: 'json',
          beforeSend: function() { addCount(); },
          success: function(data) {
              if(data.error == false) {
                  $('#feature-data-'+id).empty();

                  var htmlInj = '';
                  var htmlInj2 = '';
                  var specificCount = 0;

                  if(data.data.Recommendations.NameRecommendation) {
                    htmlInj = '';
                    htmlInj += '<div class="panel panel-default">';
                      htmlInj += '<div class="panel-heading">';
                        htmlInj += '<div class=""><a onclick="overlayHide();" class="btn pull-right"><i class="fa fa-times"></i></a></div>';
                        htmlInj += '<h1 class="panel-title">Features</h1>';
                      htmlInj += '</div>';
                      htmlInj += '<div class="panel-body">';
                        htmlInj += '<div class="well">';
                          htmlInj += '<div class="row">';
                            data.data.Recommendations.NameRecommendation = $.makeArray(data.data.Recommendations.NameRecommendation);
                            $.each(data.data.Recommendations.NameRecommendation, function(key, val) {
                          htmlInj2 = '';
                        htmlInj += '<div class="row form-group">';

                          if(("ValueRecommendation" in val) && (val.ValidationRules.MaxValues == 1)) {
                            htmlInj2 += '<option value="">-- <?php echo $text_select; ?> --</option>';

                            //force an array in case of single element
                            val.ValueRecommendation = $.makeArray(val.ValueRecommendation);

                            $.each(val.ValueRecommendation, function(key2, option) {
                                htmlInj2 += '<option value="'+option.Value+'">'+option.Value+'</option>';
                            });

                            if(val.ValidationRules.SelectionMode == 'FreeText') {
                                htmlInj2 += '<option value="Other"><?php echo $text_other; ?></option>';
                            }

                            htmlInj += '<label class="col-sm-2 control-label">'+val.Name+'</label>';
                            htmlInj += '<div class="col-sm-10">';
                              htmlInj += '<div class="row">';
                                htmlInj += '<div class="col-sm-6">';
                                  htmlInj += '<select name="feat['+val.Name+']" class="openbayData_'+id+' form-control" id="spec_sel_'+specificCount+'" onchange="toggleSpecOther('+specificCount+');">'+htmlInj2+'</select>';
                                htmlInj += '</div>';
                                htmlInj += '<div class="col-sm-6" id="spec_'+specificCount+'_other">';
                                  htmlInj += '<input type="text" name="featother['+val.Name+']" class="ebaySpecificOther openbayData_'+id+' form-control" style="display:none;" />';
                                htmlInj += '</div>';
                              htmlInj += '</div>';
                            htmlInj += '</div>';

                          }else if(("ValueRecommendation" in val) && (val.ValidationRules.MaxValues > 1)) {
                            htmlInj += '<label class="col-sm-2 control-label">'+val.Name+'</label>';
                            htmlInj += '<div class="col-sm-10">';
                              htmlInj += '<div class="row">';
                                  val.ValueRecommendation = $.makeArray(val.ValueRecommendation);
                                  $.each(val.ValueRecommendation, function(key2, option) {
                                    htmlInj += '<div class="col-sm-4">';
                                      htmlInj += '<label class="checkbox-inline"><input type="checkbox" name="feat['+val.Name+'][]" value="'+option.Value+'" class="openbayData_'+id+'"/> '+option.Value+'</label>';
                                    htmlInj += '</div>';
                                  });
                              htmlInj += '</div>';
                            htmlInj += '</div>';
                          } else {
                            htmlInj += '<label class="col-sm-2 control-label">'+val.Name+'</label>';
                            htmlInj += '<div class="col-sm-10">';
                              htmlInj += '<input type="text" name="feat['+val.Name+']" id="taxInc" value="" class="openbayData_'+id+' form-control col-sm-6" />';
                            htmlInj += '</div>';
                          }

                          specificCount++;
                        htmlInj += '</div>';
                      });
                          htmlInj += '</div>';
                        htmlInj += '</div>';
                      htmlInj += '</div>';
                    htmlInj += '</div>';
                    $('#feature-data-'+id).append(htmlInj);
                  } else {
                      $('#feature-data-'+id).text('None');
                  }
              } else {
                  alert(data.msg);
              }

              $('#editFeature_'+id).show();

              removeCount();
          },
          failure: function() {
              removeCount();
          },
          error: function() {
              removeCount();
          }
      });
  }

  function toggleSpecOther(id) {
      var selectVal = $('#spec_sel_'+id).val();
      if(selectVal == 'Other') {
          $('#spec_'+id+'_other').show();
      } else {
          $('#spec_'+id+'_other').hide();
      }
  }

  function searchEbayCatalog(id) {
    var qry = $('#catalog_search_'+id).val();
    var cat = $('#finalCat_'+id).val();
    var html = '';
    $('#catalogDiv_'+id).empty().hide();

      if(qry == '') {
        $('#catalog_search_'+id).before('<div class="alert alert-danger" id="catalog_search_'+id+'_error"><?php echo $text_search_text; ?></div>');
      } else {
        $.ajax({
            url: 'index.php?route=openbay/ebay/searchEbayCatalog&token=<?php echo $token; ?>',
            type: 'POST',
            dataType: 'json',
            data: { categoryId: cat, page: 1, search: qry },
            beforeSend: function() {
              $('#catalog_search_'+id+'_error').remove();
              $('#catalog_search_btn_'+id).empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
            },
            success: function(data) {
                if(data.error == false) {
                    if (data.data.ack == 'Failure') {

                    }
                    if(data.data.productSearchResult.paginationOutput.totalEntries == 0) {
                        $('#catalogDiv_'+id).append('<div class="alert alert-warning"><?php echo $text_catalog_no_products; ?></div>').show();
                    } else {
                        data.data.productSearchResult.products = $.makeArray(data.data.productSearchResult.products);

                        $.each(data.data.productSearchResult.products, function(key, val) {
                          html = '';
                          html += '<div class="well">';
                            html += '<div class="row">';
                              html += '<div class="col-sm-1">';
                                  html += '<input type="radio" class="openbayData_'+id+'" name="catalog_epid_'+id+'" value="'+val.productIdentifier.ePID+'" />';
                                html += '</div>';
                              html += '<div class="col-sm-2 text-center">';
                              if (typeof(val.stockPhotoURL) != "undefined" && val.stockPhotoURL !== null) {
                                html += '<img class="img-thumbnail" src="'+val.stockPhotoURL.thumbnail.value+'"/>';
                              } else {
                                html += '<span class="img-thumbnail"><i class="fa fa-camera fa-5x"></i></span>';
                              }
                              html += '</div>';
                              html += '<div class="col-sm-9">';
                                html += '<p>'+val.productDetails.value.text.value+'</p>';
                              html += '</div>';
                            html += '</div>';
                          html += '</div>';

                          $('#catalogDiv_'+id).append(html).show();
                        });
                    }
                }
            },
            complete: function() {
              $('#catalog_search_btn_'+id).empty().html('<i class="fa fa-lg fa-search"></i> <?php echo $text_search; ?>').removeAttr('disabled');
            },
            failure: function() {
                $('#catalogDiv_'+id).append('<?php echo $text_search_failed; ?>');
            },
            error: function() {
                $('#catalogDiv_'+id).append('<?php echo $text_search_failed; ?>');
            }
        });
      }
  }

  function listingDuration(data, id) {
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
    $.each(data, function(key, val) {
        htmlInj += '<option value="'+val+'"';
        if(val == listingDefault) { htmlInj += ' selected="selected"';}
        htmlInj += '>'+lang[val]+'</option>';
    });

    $('#durationRow_'+id).empty().html(htmlInj);
    $('#durationLoading_'+id).hide();
    $('#durationContainer_'+id).show();
  }

  function categorySuggestedChange(val, id) {
      $('#cSelections_'+id).hide();
      loadCategories(1, true, id);
      $('input[name=finalCat]').attr('value', val);
      getCategoryFeatures(val, id);
  }

  function editAll() {
      var id = '';
      var name = '';

      $('#previewBtn').show();
      $('#previewEditBtn').hide();
      $('#submitBtn').hide();
      $('.p_row_buttons_prev').remove();
      $('.p_row_buttons_view').remove();

      $.each($('.pId'), function(i) {
          id = $(this).val();
          name = $('#title_'+$(this).val()).val();
          $('#p_row_msg_'+$(this).val()).hide();
          $('.p_row_content_'+$(this).val()).show();
          $('#p_row_title_'+$(this).val()).text(name).hide();
          $('#p_msg_'+i).empty();
      });
  }

  function previewAll() {
      var id = '';
      var name = '';
      var processedData = '';

      overlay('overlay-loading');

      $('.warning').hide();
      $('#previewBtn').hide();
      $('#previewEditBtn').show();
      $('#submitBtn').show();

      $.each($('.pId'), function(i) {
          id = $(this).val();
          name = $('#title_'+$(this).val()).val();

          $('#p_row_msg_'+$(this).val()).show();
          $('.p_row_content_'+$(this).val()).hide();
          $('#p_row_title_'+$(this).val()).text(name).show();

          $('#catalog_epid_'+id).val($("input[type='radio'][name='catalog_epid_"+id+"']:checked").val());

          processedData = $(".openbayData_"+id).serialize();

          $.ajax({
              url: 'index.php?route=openbay/ebay/verifyBulk&token=<?php echo $token; ?>&i='+id,
              type: 'POST',
              dataType: 'json',
              data: processedData,
              beforeSend: function() { addCount(); },
              success: function(data) {
                  if(data.ack != 'Failure') {
                      var msgHtml = '';
                      var feeTot = '';
                      var currencyCode = '';

                      $('#p_row_buttons_'+data.i).prepend('<a class="btn btn-primary p_row_buttons_prev" target="_BLANK" href="'+data.preview+'"><?php echo $text_preview; ?></a>');


                      if(data.errors) {
                          $.each(data.errors, function(k,v) {
                              msgHtml += '<div class="attention" style="margin:5px;">'+v+'</div>';
                          });
                      }

                      $.each(data.fees, function(key, val) {
                          if(val.Fee != 0.0 && val.Name != 'ListingFee') {
                              feeTot = feeTot + parseFloat(val.Fee);
                          }
                          currencyCode = val.Cur;
                      });

                      msgHtml += '<div class="success" style="margin:5px;">Total fees: '+currencyCode+' '+feeTot+'</div>';

                      $('#p_msg_'+data.i).html(msgHtml);
                  } else {
                      var errorHtml = '';

                      $.each(data.errors, function(k,v) {
                          errorHtml += '<div class="warning" style="margin:5px;">'+v+'</div>';
                      });

                      $('#p_msg_'+data.i).html(errorHtml);
                  }
                  removeCount();
              },
              failure: function() {
                  removeCount();
                  alert('<?php echo $text_error_reverify; ?>');
              },
              error: function() {
                  removeCount();
                  alert('<?php echo $text_error_reverify; ?>');
              }
          });
      });
  }

  function submitAll() {
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

          $.each($('.pId'), function(i) {
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
                  beforeSend: function() { addCount(); },
                  success: function(data) {
                      if(data.ack != 'Failure') {
                          var msgHtml = '';
                          var feeTot = '';
                          var currencyCode = '';

                          if(data.errors) {
                              $.each(data.errors, function(k,v) {
                                  msgHtml += '<div class="attention" style="margin:5px;">'+v+'</div>';
                              });
                          }

                          $.each(data.fees, function(key, val) {
                              if(val.Fee != 0.0 && val.Name != 'ListingFee') {
                                  feeTot = feeTot + parseFloat(val.Fee);
                              }
                              currencyCode = val.Cur;
                          });

                          $('#p_row_buttons_'+data.i).prepend('<a class="btn btn-primary p_row_buttons_view" href="<?php echo $listing_link; ?>'+data.itemid+'" target="_BLANK"><?php echo $text_view; ?></a>');

                          msgHtml += '<div class="success" style="margin:5px;"><?php echo $text_listed; ?>'+data.itemid+'</div>';

                          $('#p_msg_'+data.i).html(msgHtml);
                      } else {
                          var errorHtml = '';

                          $.each(data.errors, function(k,v) {
                              errorHtml += '<div class="warning" style="margin:5px;">'+v+'</div>';
                          });

                          $('#p_msg_'+data.i).html(errorHtml);
                      }
                      removeCount();
                  },
                  failure: function() {
                      removeCount();
                  },
                  error: function() {
                      removeCount();
                  }
              });
          });

      }
  }

  function showFeatures(id) {
      overlay('overlay-feature-'+id);
  }

  function showCatalog(id) {
      overlay('catalogPage_'+id);
  }

  function genericProfileChange(id) {
      modifyPrices(id);
  }
</script>
<?php echo $footer; ?>