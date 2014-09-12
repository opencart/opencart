<?php echo $header; ?><?php echo $column_left; ?>
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
        <a onclick="confirmAction('<?php echo $cancel; ?>');" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $text_page_title; ?></h1>
    </div>
    <div class="panel-body" id="page-listing">
      <?php if (!isset($error_fail)) { ?>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
          <div class="well">
            <div class="row">
              <div class="col-sm-12 text-right">
                <a class="btn btn-primary" id="button-verify"><i class="fa fa-check"></i> <?php echo $text_preview_all; ?></a>
                <a class="btn btn-primary" id="button-edit" style="display:none;"><i class="fa fa-pencil"></i> <?php echo $button_edit; ?></a>
                <a class="btn btn-primary" id="button-submit" style="display:none;"><i class="fa fa-plus-circle"></i> <?php echo $button_submit; ?></a>
              </div>
            </div>
          </div>
          <?php if ($products) { ?>
            <?php $i = 0; ?>
            <?php foreach ($products as $product) { ?>
              <div class="well listingBox" id="p_row_<?php echo $i; ?>">
                <input type="hidden" class="product_id openbay_data_<?php echo $i; ?>" name="product_id" value="<?php echo $i; ?>" />
                <input type="hidden" class="openbay_data_<?php echo $i; ?>" name="product_id" value="<?php echo $product['product_id']; ?>" />
                <input type="hidden" name="price_original" id="price_original_<?php echo $i; ?>" value="<?php echo number_format($product['price']*(($default['defaults']['tax']/100) + 1), 2, '.', ''); ?>" />
                <input type="hidden" class="openbay_data_<?php echo $i; ?>" name="catalog_epid" id="catalog_epid_<?php echo $i; ?>" value="0" />
                <div class="row">
                  <div class="col-sm-7">
                    <h4 id="product_title_<?php echo $i; ?>" style="display:none;"></h4>
                  </div>
                  <div class="col-sm-5 form-group text-right" id="p_row_buttons_<?php echo $i; ?>">
                    <a class="btn btn-primary" onclick="showCategory('<?php echo $i; ?>');" id="editCategory_<?php echo $i; ?>" ><i class="fa fa-pencil"></i> <?php echo $text_category; ?></a>
                    <a class="btn btn-primary" onclick="showProfiles('<?php echo $i; ?>');" id="editProfiles_<?php echo $i; ?>" ><i class="fa fa-pencil"></i> <?php echo $text_profile; ?></a>
                    <a class="btn btn-primary" style="display:none;" onclick="showCatalog('<?php echo $i; ?>');" id="editCatalog_<?php echo $i; ?>" ><i class="fa fa-pencil"></i> <?php echo $text_catalog; ?></a>
                    <a class="btn btn-primary" style="display:none;" onclick="showFeatures('<?php echo $i; ?>');" id="editFeature_<?php echo $i; ?>"><i class="fa fa-pencil"></i> <?php echo $text_features; ?></a>
                    <a class="btn btn-danger" onclick="removeBox('<?php echo $i; ?>')"><i class="fa fa-minus-circle"></i> <?php echo $button_remove; ?></a>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="row" id="product_messages_<?php echo $i; ?>" style="display:none;"></div>
                    <div class="row product_content_<?php echo $i; ?>">
                      <div class="col-sm-2">
                        <div class="row">
                          <div class="col-sm-12 form-group text-center">
                            <img class="img-thumbnail" src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" />
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-12 form-group">
                            <h4 class="text-center"><span class="label label-success"><?php echo $text_stock; ?>: <?php echo $product['quantity']; ?></span></h4>
                            <input type="hidden" name="qty" value="<?php echo $product['quantity']; ?>" class="openbay_data_<?php echo $i; ?>" />
                          </div>
                        </div>
                        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="overlay-feature-<?php echo $i; ?>" data-backdrop="static" data-keyboard="false">
                          <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                              <div class="modal-body" id="feature-data-<?php echo $i; ?>"></div>
                            </div>
                          </div>
                        </div>
                        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="overlay-category-<?php echo $i; ?>" data-backdrop="static" data-keyboard="false">
                          <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                              <div class="modal-body">
                                <div class="panel panel-default">
                                  <div class="panel-heading">
                                    <div class="text-right"><a onclick="overlayHide();" class="btn pull-right" data-toggle="tooltip" title="<?php echo $text_close; ?>"><i class="fa fa-times"></i></a></div>
                                    <h1 class="panel-title"><?php echo $text_category; ?></h1>
                                  </div>
                                  <div class="panel-body">
                                    <div class="well">
                                      <div class="row">
                                        <div class="form-group">
                                          <label class="col-sm-2 control-label"><?php echo $text_suggested; ?></label>
                                          <div class="col-sm-10">
                                            <div class="alert alert-info" id="loadingSuggestedCat_<?php echo $i; ?>"><i class="fa fa-cog fa-lg fa-spin"></i> <?php echo $text_category; ?></div>
                                            <div id="suggestedCat_<?php echo $i; ?>"></div>
                                            <input type="hidden" name="finalCat" id="finalCat_<?php echo $i; ?>" class="openbay_data_<?php echo $i; ?>" />
                                          </div>
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="form-group" id="cSelections_<?php echo $i; ?>" style="display:none;">
                                          <label class="col-sm-2 control-label"><?php echo $text_category_choose; ?></label>
                                          <div class="col-sm-10">
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
                          </div>
                        </div>

                        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="overlay-catalog-<?php echo $i; ?>" data-backdrop="static" data-keyboard="false">
                          <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                              <div class="modal-body">
                                <div class="panel panel-default">
                                  <div class="panel-heading">
                                    <div class="text-right"><a onclick="overlayHide();" class="btn pull-right" data-toggle="tooltip" title="<?php echo $text_close; ?>"><i class="fa fa-times"></i></a></div>
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
                                          <a onclick="searchEbayCatalog('<?php echo $i; ?>');" class="btn btn-primary" id="button-catalog-search-<?php echo $i; ?>"><?php echo $text_search; ?></a>
                                        </div>
                                      </div>
                                    </div>
                                    <div id="catalog-results-<?php echo $i; ?>" style="display:none;"></div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="overlay-profile-<?php echo $i; ?>" data-backdrop="static" data-keyboard="false">
                          <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                              <div class="modal-body">
                                <div class="panel panel-default">
                                  <div class="panel-heading">
                                    <div class="text-right"><a onclick="overlayHide();" class="btn pull-right" data-toggle="tooltip" title="<?php echo $text_close; ?>"><i class="fa fa-times"></i></a></div>
                                    <h1 class="panel-title"><?php echo $text_profile; ?></h1>
                                  </div>
                                  <div class="panel-body">
                                    <div class="well">
                                      <div class="form-group">
                                        <label class="col-sm-2 control-label"><?php echo $text_profile_theme; ?></label>
                                        <div class="col-sm-10">
                                          <select name="theme_profile" class="openbay_data_<?php echo $i; ?> form-control">
                                            <?php foreach($default['profiles_theme'] as $s) { echo '<option value="'.$s['ebay_profile_id'].'"'.($default['profiles_theme_def'] == $s['ebay_profile_id'] ? ' selected' : '').'>'.$s['name'].'</option>'; } ?>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <label class="col-sm-2 control-label"><?php echo $text_profile_shipping; ?></label>
                                        <div class="col-sm-10">
                                          <select name="shipping_profile" class="openbay_data_<?php echo $i; ?> form-control">
                                            <?php foreach($default['profiles_shipping'] as $s) { echo '<option value="'.$s['ebay_profile_id'].'"'.($default['profiles_shipping_def'] == $s['ebay_profile_id'] ? ' selected' : '').'>'.$s['name'].'</option>'; } ?>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <label class="col-sm-2 control-label"><?php echo $text_profile_generic; ?></label>
                                        <div class="col-sm-10">
                                          <select name="generic_profile" id="generic_profile_<?php echo $i; ?>" class="openbay_data_<?php echo $i; ?> form-control" onchange="genericProfileChange(<?php echo $i; ?>);">
                                            <?php foreach($default['profiles_generic'] as $s) { echo '<option value="'.$s['ebay_profile_id'].'"'.($default['profiles_generic_def'] == $s['ebay_profile_id'] ? ' selected' : '').'>'.$s['name'].'</option>'; } ?>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <label class="col-sm-2 control-label"><?php echo $text_profile_returns; ?></label>
                                        <div class="col-sm-10">
                                          <select name="return_profile" class="openbay_data_<?php echo $i; ?> form-control">
                                            <?php foreach($default['profiles_returns'] as $s) { echo '<option value="'.$s['ebay_profile_id'].'"'.($default['profiles_returns_def'] == $s['ebay_profile_id'] ? ' selected' : '').'>'.$s['name'].'</option>'; } ?>
                                          </select>
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
                      <div class="col-sm-10">
                        <div class="form-group">
                          <label class="col-sm-2 control-label"><?php echo $text_title; ?></label>
                          <div class="col-sm-10">
                            <input type="text" name="title" class="openbay_data_<?php echo $i; ?> form-control" value="<?php echo $product['name']; ?>" id="title_<?php echo $i; ?>" />
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label"><?php echo $text_price; ?></label>
                          <div class="col-sm-10">
                            <input id="price_<?php echo $i; ?>" type="text" name="price" class="openbay_data_<?php echo $i; ?> form-control" value="<?php echo number_format($product['price']*(($default['defaults']['tax']/100) + 1), 2, '.', ''); ?>" />
                          </div>
                        </div>
                        <div class="alert alert-info" id="conditionLoading_<?php echo $i; ?>"><i class="fa fa-cog fa-lg fa-spin"></i> <?php echo $text_loading_condition; ?></div>
                        <div class="form-group" id="conditionContainer_<?php echo $i; ?>" style="display:none;">
                          <label class="col-sm-2 control-label"><?php echo $entry_condition; ?></label>
                          <div class="col-sm-10">
                            <select name="condition" class="openbay_data_<?php echo $i; ?> form-control" id="conditionRow_<?php echo $i; ?>">
                              <?php foreach($default['profiles_returns'] as $s) { echo '<option value="'.$s['ebay_profile_id'].'"'.($default['profiles_returns_def'] == $s['ebay_profile_id'] ? ' selected' : '').'>'.$s['name'].'</option>'; } ?>
                            </select>
                          </div>
                        </div>
                        <div class="alert alert-info" id="durationLoading_<?php echo $i; ?>"><i class="fa fa-cog fa-lg fa-spin"></i> <?php echo $text_loading_duration; ?></div>
                        <div class="form-group" id="durationContainer_<?php echo $i; ?>" style="display:none;">
                          <label class="col-sm-2 control-label"><?php echo $text_duration; ?></label>
                          <div class="col-sm-10">
                            <select name="duration" class="openbay_data_<?php echo $i; ?> form-control" id="durationRow_<?php echo $i; ?>">
                              <?php foreach($default['profiles_returns'] as $s) { echo '<option value="'.$s['ebay_profile_id'].'"'.($default['profiles_returns_def'] == $s['ebay_profile_id'] ? ' selected' : '').'>'.$s['name'].'</option>'; } ?>
                            </select>
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
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $text_no_results; ?></div>
          <?php } ?>
        </form>
        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="overlay-loading" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-body">
                <div class="progress progress-striped active">
                  <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%" id="loading-bar"></div>
                </div>
                <p class="text-center"><?php echo $text_preparing0; ?> <span id="ajax-count-complete-display">0</span> <?php echo $text_preparing1; ?> <span id="ajax-count-total-display">0</span> <?php echo $text_preparing2; ?> </p>
              </div>
            </div>
          </div>
        </div>
      <?php } else { ?>
        <?php foreach($error_fail as $fail) { ?>
          <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $fail; ?></div>
        <?php } ?>
      <?php } ?>
    </div>
  </div>
</div>

<input type="hidden" id="total-items" value="<?php echo $count; ?>" name="total-items" />
<input type="hidden" id="ajax-count" value="0" />
<input type="hidden" id="ajax-count-total" value="0" />
<input type="hidden" id="ajax-count-complete" value="0" />
<script type="text/javascript">
  $(document).ready(function() {
    overlay('overlay-loading');

    <?php $j = 0; while($j < $i) { ?>
        getSuggestedCategories('<?php echo (int)$j; ?>');
        modifyPrices('<?php echo (int)$j; ?>');
    <?php $j++; } ?>

    $('#activeItems').text($('#total-items').val());
  });

  function overlay(screen) {
    $('#ajax-count-complete').val(0);
    $('#ajax-count-complete-display').text(0);
    $('#ajax-count-total').val(0);
    $('#ajax-count-total-display').text(0);
    $('#loading-bar').css('width', '0%');
    $('#'+screen).modal('toggle');
  }

  function overlayHide() {
    $('.modal').modal('hide');
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

              if (data.data.price_modify !== false && typeof data.data.price_modify !== 'undefined') {
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
    var count = parseInt($('#ajax-count').val()) + 1;
    $('#ajax-count').val(count);
    var count1 = parseInt($('#ajax-count-total').val())+1;
    $('#ajax-count-total').val(count1);
    $('#ajax-count-total-display').text(count1);
  }

  function removeCount() {
      var count = parseInt($('#ajax-count').val())-1;
      $('#ajax-count').val(count);
      var count1 = parseInt($('#ajax-count-complete').val())+1;
      $('#ajax-count-complete').val(count1);
      $('#ajax-count-complete-display').text(count1);

      var modifier = 0;
      var current = 0;
      var total = $('#ajax-count-total').val();

      modifier = 100 / total;
      current = parseFloat(modifier * count1);

      $('#loading-bar').css('width', current+'%');

      if (count == 0) {
          overlayHide();
      }
  }

  function removeBox(id) {
      $('#p_row_'+id).fadeOut('medium');

      setTimeout(function() {
          $('#p_row_'+id).remove();
      }, 1000);

      $('#total-items').val($('#total-items').val()-1);

      if ($('.listingBox').length == 1) {
          window.location = "index.php?route=extension/openbay/itemList&token=<?php echo $token; ?>";
      } else {
          $('#activeItems').text($('#total-items').val());
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
              var htmlInj = '';

              if (data.error == false && data.data) {
                var i = 1;

                $.each(data.data, function(key,val) {
                    if (val.percent != 0) {
                      htmlInj += '<div class="row form-group">';
                        htmlInj += '<div class="col-sm-1 text-right">';
                          htmlInj += '<input type="radio" id="suggested_category_'+id+'" name="suggested_'+id+'" value="'+val.id+'" onchange="categorySuggestedChange('+val.id+','+id+')"';
                          if (i == 1) {
                              htmlInj += ' checked="checked"';
                              categorySuggestedChange(val.id, id);
                          }
                          htmlInj += '/>';
                        htmlInj += '</div>';
                        htmlInj += '<div class="col-sm-11">';
                          htmlInj += '('+val.percent+'% match) '+val.name;
                        htmlInj += '</div>';
                      htmlInj += '</div>';
                    }
                    i++;
                });

                htmlInj += '<div class="row form-group">';
                  htmlInj += '<div class="col-sm-1 text-right"><input type="radio" id="manual_use_category_'+id+'" name="suggested_'+id+'" value="" onchange="useManualCategory('+id+')" /></div>';
                  htmlInj += '<div class="col-sm-11"><?php echo $text_category_choose; ?></div>';
                htmlInj += '</div>';
              } else {
                htmlInj += '<div class="row form-group">';
                  htmlInj += '<div class="col-sm-1 text-right"><input type="radio" id="manual_use_category_'+id+'" name="suggested_'+id+'" value="" onchange="useManualCategory('+id+')" /></div>';
                  htmlInj += '<div class="col-sm-11"><?php echo $text_category_choose; ?></div>';
                htmlInj += '</div>';
                useManualCategory(id);
              }
              $('#suggestedCat_'+id).empty().html(htmlInj);
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

      if (level == 1) {
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
          },
          success: function(data) {
              if (data.items != null) {
                  $('#catsSelect'+level+'_'+id).empty();
                  $('#catsSelect'+level+'_'+id).append('<option value="">-- SELECT --</option>');
                  $.each(data.cats, function(key, val) {
                      if (val.CategoryID != parent) {
                          $('#catsSelect'+level+'_'+id).append('<option value="'+val.CategoryID+'">'+val.CategoryName+'</option>');
                      }
                  });

                  if (skip != true) {
                      $('#finalCat_'+id).val('');
                  }

                  $('#catsSelect'+level+'_'+id).show();
              } else {
                  if (data.error) {

                  } else {
                      $('#finalCat_'+id).val($('#catsSelect'+prevLevel+'_'+id).val());
                      getCategoryFeatures($('#catsSelect'+prevLevel+'_'+id).val(), id);
                  }
              }
              $('#imageLoading_'+id).hide();
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
              if (data.error == false) {
                  var htmlInj = '';

                  listingDuration(data.data.durations, id);

                  if (data.data.conditions) {
                      $.each(data.data.conditions, function(key, val) {
                          htmlInj += '<option value='+val.id+'>'+val.name+'</option>';
                      });

                      if (htmlInj == '') {
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
              if (data.error == false) {
                  $('#feature-data-'+id).empty();

                  var htmlInj = '';
                  var htmlInj2 = '';
                  var specificCount = 0;

                  if (data.data.Recommendations.NameRecommendation) {
                    htmlInj = '';
                    htmlInj += '<div class="panel panel-default">';
                      htmlInj += '<div class="panel-heading">';
                        htmlInj += '<div class="pull-right"><a onclick="overlayHide();" class="btn btn-default" data-toggle="tooltip" title="<?php echo $text_close; ?>"><i class="fa fa-times"></i></a></div>';
                        htmlInj += '<h1 class="panel-title"><?php echo $text_features; ?></h1>';
                      htmlInj += '</div>';
                      htmlInj += '<div class="panel-body">';
                        htmlInj += '<div class="well">';
                          htmlInj += '<div class="row">';
                            data.data.Recommendations.NameRecommendation = $.makeArray(data.data.Recommendations.NameRecommendation);
                            $.each(data.data.Recommendations.NameRecommendation, function(key, val) {
                              htmlInj2 = '';
                              htmlInj += '<div class="row form-group">';

                              if (("ValueRecommendation" in val) && (val.ValidationRules.MaxValues == 1)) {
                                htmlInj2 += '<option value=""><?php echo $text_select; ?></option>';

                                //force an array in case of single element
                                val.ValueRecommendation = $.makeArray(val.ValueRecommendation);

                                $.each(val.ValueRecommendation, function(key2, option) {
                                    htmlInj2 += '<option value="'+option.Value+'">'+option.Value+'</option>';
                                });

                                if (val.ValidationRules.SelectionMode == 'FreeText') {
                                    htmlInj2 += '<option value="Other"><?php echo $text_other; ?></option>';
                                }

                                htmlInj += '<label class="col-sm-2 control-label">'+val.Name+'</label>';
                                htmlInj += '<div class="col-sm-10">';
                                  htmlInj += '<div class="row">';
                                    htmlInj += '<div class="col-sm-6">';
                                      htmlInj += '<select name="feat['+val.Name+']" class="openbay_data_'+id+' form-control" id="spec_sel_'+specificCount+'" onchange="toggleSpecOther('+specificCount+');">'+htmlInj2+'</select>';
                                    htmlInj += '</div>';
                                    htmlInj += '<div class="col-sm-6" id="spec_'+specificCount+'_other">';
                                      htmlInj += '<input type="text" name="featother['+val.Name+']" class="ebaySpecificOther openbay_data_'+id+' form-control" style="display:none;" />';
                                    htmlInj += '</div>';
                                  htmlInj += '</div>';
                                htmlInj += '</div>';
                              }else if (("ValueRecommendation" in val) && (val.ValidationRules.MaxValues > 1)) {
                                htmlInj += '<label class="col-sm-2 control-label">'+val.Name+'</label>';
                                htmlInj += '<div class="col-sm-10">';
                                  htmlInj += '<div class="row">';
                                      val.ValueRecommendation = $.makeArray(val.ValueRecommendation);
                                      $.each(val.ValueRecommendation, function(key2, option) {
                                        htmlInj += '<div class="col-sm-4">';
                                          htmlInj += '<label class="checkbox-inline"><input type="checkbox" name="feat['+val.Name+'][]" value="'+option.Value+'" class="openbay_data_'+id+'"/> '+option.Value+'</label>';
                                        htmlInj += '</div>';
                                      });
                                  htmlInj += '</div>';
                                htmlInj += '</div>';
                              } else {
                                htmlInj += '<label class="col-sm-2 control-label">'+val.Name+'</label>';
                                htmlInj += '<div class="col-sm-10">';
                                  htmlInj += '<input type="text" name="feat['+val.Name+']" id="taxInc" value="" class="openbay_data_'+id+' form-control col-sm-6" />';
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
    if (selectVal == 'Other') {
      $('#spec_'+id+'_other').show();
    } else {
      $('#spec_'+id+'_other').hide();
    }
  }

  function searchEbayCatalog(id) {
    var qry = $('#catalog_search_'+id).val();
    var cat = $('#finalCat_'+id).val();
    var html = '';
    $('#catalog-results-'+id).empty().hide();

      if (qry == '') {
        $('#catalog_search_'+id).before('<div class="alert alert-danger" id="catalog_search_'+id+'_error"><i class="fa fa-exclamation-circle"></i> <?php echo $text_search_text; ?></div>');
      } else {
        $.ajax({
            url: 'index.php?route=openbay/ebay/searchEbayCatalog&token=<?php echo $token; ?>',
            type: 'POST',
            dataType: 'json',
            data: { category_id: cat, page: 1, search: qry },
            beforeSend: function() {
              $('#catalog_search_'+id+'_error').remove();
              $('#button-catalog-search-'+id).empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
            },
            success: function(data) {
                    if (data.error == false) {
                      if (data.results > 0) {
                        data.products = $.makeArray(data.products);

                        $.each(data.products, function(key, val) {
                          html = '';
                          html += '<div class="well">';
                          html += '<div class="row">';
                          html += '<div class="col-sm-1">';
                          html += '<input type="radio" class="openbay_data_'+id+'" name="catalog_epid_'+id+'" value="'+val.productIdentifier.ePID+'" />';
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

                          $('#catalog-results-'+id).append(html).show();
                        });
                      } else {
                        $('#catalog-results-'+id).append('<div class="alert alert-warning"><i class="fa fa-warning"></i> <?php echo $text_catalog_no_products; ?></div>').show();
                      }
                    } else {
                      $('#catalog-results-'+id).append('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> '+data.error_message+'</div>').show();
                    }
            },
            complete: function() {
              $('#button-catalog-search-'+id).empty().html('<i class="fa fa-lg fa-search"></i> <?php echo $text_search; ?>').removeAttr('disabled');
            },
            failure: function() {
                $('#catalog-results-'+id).append('<?php echo $text_search_failed; ?>');
            },
            error: function() {
                $('#catalog-results-'+id).append('<?php echo $text_search_failed; ?>');
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
        if (val == listingDefault) { htmlInj += ' selected="selected"';}
        htmlInj += '>'+lang[val]+'</option>';
    });

    $('#durationRow_'+id).empty().html(htmlInj);
    $('#durationLoading_'+id).hide();
    $('#durationContainer_'+id).show();
  }

  function categorySuggestedChange(val, id) {
      $('#cSelections_'+id).hide();
      loadCategories(1, true, id);
      $('#finalCat_'+id).val(val);
      getCategoryFeatures(val, id);
  }

  $('#button-verify').bind('click', function() {
      var id = '';
      var name = '';
      var processedData = '';

      overlay('overlay-loading');

      $('#button-verify').hide();
      $('#button-edit').show();
      $('#button-submit').show();

      $.each($('.product_id'), function(i) {
          id = $(this).val();
          name = $('#title_'+$(this).val()).val();

          $('#product_messages_'+id).html('<div class="alert alert-info"><i class="fa fa-cog fa-lg fa-spin"></i> <?php echo $text_loading; ?></div>').show();
          $('.product_content_'+id).hide();
          $('#product_title_'+id).text(name).show();

          $('#catalog_epid_'+id).val($("input[type='radio'][name='catalog_epid_"+id+"']:checked").val());

          processedData = $(".openbay_data_"+id).serialize();

          $.ajax({
              url: 'index.php?route=openbay/ebay/verifyBulk&token=<?php echo $token; ?>&i='+id,
              type: 'POST',
              dataType: 'json',
              data: processedData,
              beforeSend: function() { addCount(); },
              success: function(data) {
                var html = '';
                if (data.ack != 'Failure') {
                  var fee_total = '';
                  var currency = '';

                  $('#p_row_buttons_'+data.i).prepend('<a class="btn btn-primary button-preview" target="_BLANK" href="'+data.preview+'"><?php echo $text_preview; ?></a>');

                  if (data.errors) {
                    $.each(data.errors, function(k,v) {
                      html += '<div class="alert alert-warning"><i class="fa fa-warning"></i> '+v+'</div>';
                    });
                  }

                  $.each(data.fees, function(key, val) {
                    if (val.Fee != 0.0 && val.Name != 'ListingFee') {
                      fee_total = fee_total + parseFloat(val.Fee);
                    }
                    currency = val.Cur;
                  });

                  html += '<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_total_fee; ?> '+currency+' '+parseFloat(fee_total).toFixed(2)+'</div>';
                } else {
                    $.each(data.errors, function(k,v) {
                        html += '<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> '+v+'</div>';
                    });
                }
                $('#product_messages_'+data.i).html(html);
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
  });

  $('#button-edit').bind('click', function() {
    var id = '';
    var name = '';

    $('#button-verify').show();
    $('#button-edit').hide();
    $('#button-submit').hide();
    $('.button-preview').remove();
    $('.button-listing-view').remove();

    $.each($('.product_id'), function(i) {
      id = $(this).val();
      name = $('#title_'+$(this).val()).val();
      $('#product_messages_'+$(this).val()).empty().hide();
      $('.product_content_'+$(this).val()).show();
      $('#product_title_'+$(this).val()).text(name).hide();
    });
  });

  $('#button-submit').bind('click', function() {
      var confirm_box = confirm('<?php echo $text_ajax_confirm_listing; ?>');
      if (confirm_box) {
          var id = '';
          var name = '';
          var processedData = '';

          overlay('overlay-loading');

          $('#button-verify').hide();
          $('#button-edit').hide();
          $('#button-submit').hide();
          $('.button-preview').remove();

          $.each($('.product_id'), function(i) {
              id = $(this).val();
              name = $('#title_'+$(this).val()).val();

              $('.product_content_'+$(this).val()).hide();
              $('#product_title_'+$(this).val()).text(name).show();

              $.ajax({
                url: 'index.php?route=openbay/ebay/listItemBulk&token=<?php echo $token; ?>&i='+id,
                type: 'POST',
                dataType: 'json',
                data: $(".openbay_data_"+id).serialize(),
                beforeSend: function() { addCount(); },
                success: function(data) {
                  var html = '';
                  if (data.ack != 'Failure') {
                    if (data.errors) {
                      $.each(data.errors, function(k,v) {
                        html += '<div class="alert alert-warning"><i class="fa fa-warning"></i> '+v+'</div>';
                      });
                    }

                    $('#p_row_buttons_'+data.i).prepend('<a class="btn btn-primary button-listing-view" href="<?php echo $listing_link; ?>'+data.itemid+'" target="_BLANK"><?php echo $button_view; ?></a>');

                    html += '<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $text_listed; ?>'+data.itemid+'</div>';
                  } else {
                    $.each(data.errors, function(k,v) {
                      html += '<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> '+v+'</div>';
                    });
                  }
                  $('#product_messages_'+data.i).html(html).show();
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
  });

  function showFeatures(id) {
    overlay('overlay-feature-'+id);
  }

  function showCatalog(id) {
    overlay('overlay-catalog-'+id);
  }

  function showProfiles(id) {
    overlay('overlay-profile-'+id);
  }

  function showCategory(id) {
    overlay('overlay-category-'+id);
  }

  function genericProfileChange(id) {
      modifyPrices(id);
  }
</script>
<?php echo $footer; ?>