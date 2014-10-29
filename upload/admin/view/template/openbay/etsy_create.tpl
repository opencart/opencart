<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default" id="btn-cancel"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_create; ?></h3>
      </div>
      <div class="panel-body">
        <div id="page-listing">
    <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
      <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>"/>
      <input type="hidden" name="quantity" value="<?php echo $product['quantity']; ?>"/>
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-listing-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
        <li><a href="#tab-listing-additional" data-toggle="tab"><?php echo $tab_additional; ?></a></li>
        <li><a href="#tab-images" data-toggle="tab"><?php echo $tab_image; ?></a></li>
      </ul>
      <div class="tab-content">
        <div id="tab-listing-general" class="tab-pane active">
          <div class="well">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-category"><?php echo $entry_category; ?> <span id="category-loading" style="display: none;"><i class="fa fa-cog fa-lg fa-spin"></i></span></label>
                  <div class="col-sm-10">
                    <div class="alert alert-success" id="category-selected" style="display:none;"><i class="fa fa-check fa-lg" style="color:green"></i> <?php echo $text_category_selected; ?></div>
                    <select name="top_category" id="input-category" class="form-control">
                      <option id="category-default"><?php echo $text_option; ?></option>
                      <?php foreach ($setting['top_categories'] as $value) { ?>
                        <option value="<?php echo $value['category_name']; ?>"><?php echo $value['long_name']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group" style="display:none;" id="container-sub-category">
                  <label class="col-sm-2 control-label" for="input-sub-category"><span id="sub-category-loading" style="display: none;"><i class="fa fa-cog fa-lg fa-spin"></i></span></label>
                  <div class="col-sm-10">
                    <select name="top_category" id="input-sub-category" class="form-control">
                    </select>
                  </div>
                </div>
                <div class="form-group" style="display:none;" id="container-sub-sub-category">
                  <label class="col-sm-2 control-label" for="input-sub-sub-category"><span id="sub-sub-category-loading" style="display: none;"><i class="fa fa-cog fa-lg fa-spin"></i></span></label>
                  <div class="col-sm-10">
                    <select name="top_category" id="input-sub-sub-category" class="form-control">
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-title"><?php echo $entry_title; ?></label>
            <div class="col-sm-10">
              <input type="text" name="title" value="<?php echo $product['name']; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title" class="form-control" />
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-description"><span data-toggle="tooltip" data-container="#tab-listing-general" title="<?php echo $help_description; ?>"><?php echo $entry_description; ?></span></label>
            <div class="col-sm-10">
              <textarea name="description" placeholder="<?php echo $entry_description; ?>" id="input-description" class="form-control"><?php echo $product['description_raw']; ?></textarea>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-price"><?php echo $entry_price; ?></label>
            <div class="col-sm-10">
              <input type="text" name="price" value="<?php echo $product['price']; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="form-control" />
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-is-supply"><?php echo $entry_is_supply; ?></label>
            <div class="col-sm-10">
              <select name="is_supply" id="input-is-supply" class="form-control">
                <option value="false" selected="selected"><?php echo $text_no; ?></option>
                <option value="true"><?php echo $text_yes; ?></option>
              </select>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-who-made"><?php echo $entry_who_made; ?></label>
            <div class="col-sm-10">
              <select name="who_made" id="input-who-made" class="form-control">
                <?php foreach ($setting['who_made'] as $value) { ?>
                <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-when-made"><?php echo $entry_when_made; ?></label>
            <div class="col-sm-10">
              <select name="when_made" id="input-when-made" class="form-control">
                <?php foreach ($setting['when_made'] as $value) { ?>
                <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-state"><?php echo $entry_state; ?></label>
            <div class="col-sm-10">
              <select name="state" id="input-state" class="form-control">
                <?php foreach ($setting['state'] as $value) { ?>
                <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-shipping"><?php echo $entry_shipping; ?> <span id="shipping-loading" style="display: none;"><i class="fa fa-cog fa-lg fa-spin"></i></span></label>
            <div class="col-sm-10">
              <select name="shipping_template_id" id="input-shipping" class="form-control" disabled="disabled">
              </select>
            </div>
          </div>
        </div>
        <div id="tab-listing-additional" class="tab-pane">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-shop-section"><?php echo $entry_shop; ?> <span id="shop-section-loading" style="display: none;"><i class="fa fa-cog fa-lg fa-spin"></i></span></label>
            <div class="col-sm-10">
              <select name="shop_section_id" id="input-shop-section" class="form-control" disabled="disabled">
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-style-1"><?php echo $entry_style; ?></label>
            <div class="col-sm-10">
              <input type="text" name="style_1" value="" placeholder="<?php echo $entry_style; ?>" id="input-style-1" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-style-2"><?php echo $entry_style_2; ?></label>
            <div class="col-sm-10">
              <input type="text" name="style_2" value="" placeholder="<?php echo $entry_style_2; ?>" id="input-style-2" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-non-taxable"><?php echo $entry_non_taxable; ?></label>
            <div class="col-sm-10">
              <select name="non_taxable" id="input-non-taxable" class="form-control">
                <option value="0" selected="selected"><?php echo $text_no; ?></option>
                <option value="1"><?php echo $text_yes; ?></option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-recipient"><?php echo $entry_recipient; ?></label>
            <div class="col-sm-10">
              <select name="recipient" id="input-recipient" class="form-control">
                <?php foreach ($setting['recipient'] as $value) { ?>
                <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-occasion"><?php echo $entry_occasion; ?></label>
            <div class="col-sm-10">
              <select name="occasion" id="input-occasion" class="form-control">
                <?php foreach ($setting['occasion'] as $value) { ?>
                <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-processing-min"><?php echo $entry_processing_min; ?></label>
            <div class="col-sm-10">
              <input type="text" name="processing_min" value="" placeholder="<?php echo $entry_processing_min; ?>" id="input-processing-min" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-processing-max"><?php echo $entry_processing_max; ?></label>
            <div class="col-sm-10">
              <input type="text" name="processing_max" value="" placeholder="<?php echo $entry_processing_max; ?>" id="input-processing-max" class="form-control" />
            </div>
          </div>
          <input type="hidden" name="category_id" value="" id="category-id" />
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-material"><?php echo $entry_materials; ?></label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-sm-4">
                  <div class="row">
                    <div class="col-xs-8">
                      <input type="text" name="material_input" value="" placeholder="<?php echo $entry_materials; ?>" id="input-material" class="form-control" />
                    </div>
                    <div class="col-xs-2">
                      <button class="btn btn-primary" title="" onclick="addMaterial();" data-toggle="tooltip" type="button" data-original-title="<?php echo $text_material_add; ?>"><i class="fa fa-plus-circle"></i></button>
                    </div>
                  </div>
                </div>
                <div class="col-sm-8">
                  <ul class="list-group" id="material-container">
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-tag"><?php echo $entry_tags; ?></label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-sm-4">
                  <div class="row">
                    <div class="col-xs-8">
                      <input type="text" name="tag_input" value="" placeholder="<?php echo $entry_tags; ?>" id="input-tag" class="form-control" />
                    </div>
                    <div class="col-xs-2">
                      <button class="btn btn-primary" title="" onclick="addTag();" data-toggle="tooltip" type="button" data-original-title="<?php echo $text_tag_add; ?>"><i class="fa fa-plus-circle"></i></button>
                    </div>
                  </div>
                </div>
                <div class="col-sm-8">
                  <ul class="list-group" id="tag-container">
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="tab-images" class="tab-pane">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
            <div class="col-sm-10"><a id="thumb-image" class="img-thumbnail"><img src="<?php echo $product['thumb']; ?>" alt="" title="" /></a>
              <input type="hidden" name="image" value="<?php echo $product['image_url']; ?>" id="input-image" />
            </div>
          </div>
          <?php if (!empty($product['product_images'])) { ?>
          <div class="table-responsive">
            <table id="images" class="table table-striped table-bordered table-hover">
              <thead>
              <tr>
                <td class="text-right" style="width: 1px;"></td>
                <td class="text-left"><?php echo $entry_image_other; ?></td>
              </tr>
              </thead>
              <tbody>
              <?php $image_row = 0; ?>
              <?php foreach ($product['product_images'] as $product_image) { ?>
              <tr>
                <td class="text-left"><input type="checkbox" name="product_image[<?php echo $image_row; ?>][image_url]" value="<?php echo $product_image['image_url']; ?>" class="product-image" checked="checked" /></td>
                <td class="text-left"><a href="" class="img-thumbnail"><img src="<?php echo $product_image['thumb']; ?>" alt="" title="" /></a></td>
              </tr>
              <?php $image_row++; ?>
              <?php } ?>
              </tbody>
            </table>
          </div>
          <?php } ?>
        </div>
        <div class="well">
          <div class="row">
            <div class="col-sm-12"> <a class="btn btn-primary pull-right" id="button-submit" onclick="submitForm();"><span><?php echo $button_submit; ?></span></a> </div>
          </div>
        </div>
      </div>
    </form>
  </div>
        <div id="page-listing-success" style="display:none;">
          <div class="well">
            <div class="row">
              <div class="col-sm-12">
                <h3><?php echo $text_created; ?></h3>
                <p><?php echo $text_listing_id; ?>: <span id="listing-id"></span></p>
                <ul class="list-group" id="listing-image-status">
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
  var image_count = 1;

  function submitForm() {
    $.ajax({
      url: 'index.php?route=openbay/etsy_product/createsubmit&token=<?php echo $token; ?>',
      beforeSend: function(){
        $('#button-submit').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
      },
      type: 'post',
      data: $("#form").serialize(),
      dataType: 'json',
      success: function(json) {
        if (json.error) {
          if (json.code) {
            alert(json.error);
          } else {
            $.each(json.error, function( k, v ) {
              alert(v);
            });
          }
          $('#button-submit').empty().html('<span><?php echo $button_submit; ?></span>').removeAttr('disabled');
        } else {
          if (json.listing_id) {
            // upload the primary image
            var image_primary = $('#input-image').val();

            if (image_primary != '') {
              uploadImage(json.listing_id, $('#input-image').val(), image_count);
              image_count = image_count + 1;
            }

            // get the extra images and upload
            $('.product-image:checkbox:checked').each(function() {
              uploadImage(json.listing_id, $(this).val(), image_count);
              image_count = image_count + 1;
            });

            $('#listing-id').text(json.listing_id);
            $('#page-listing').hide();
            $('#page-listing-success').fadeIn();
            $('#button-submit').empty().html('<span><?php echo $button_submit; ?></span>').removeAttr('disabled');
          } else {
            alert('Error creating listing?');
          }
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
        $('#button-submit').empty().html('<span><?php echo $button_submit; ?></span>').removeAttr('disabled');
      }
    });
  }

  function uploadImage(listing_id, url, id) {
    $.ajax({
      url: 'index.php?route=openbay/etsy_product/addimage&token=<?php echo $token; ?>',
      beforeSend: function(){
        $('#listing-image-status').append('<li class="list-group-item list-group-item-info" id="image-upload-status-'+id+'"><i class="fa fa-cog fa-lg fa-spin"></i> <?php echo $text_img_upload; ?> '+id+'</li>');
      },
      type: 'post',
      data: {'listing_id':listing_id,'image':url},
      dataType: 'json',
      success: function(json) {
        $('#image-upload-status-'+id).removeClass('list-group-item-info').addClass('list-group-item-success').empty().html('<i class="fa fa-check fa-lg" style="color:green;"></i> <?php echo $text_img_upload_done; ?> '+id+'');
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  }

  var material_count = 0;

  function addMaterial() {
    var material = $('#input-material').val();

    if (material != '') {
      $('#material-container').append('<li class="list-group-item" id="material-id-'+material_count+'"><div class="row"><div class="col-xs-1"><button class="btn btn-danger btn-xs" title="" type="button" data-toggle="tooltip" data-original-title="<?php echo $text_material_remove; ?>" onclick="$(\'#material-id-'+material_count+'\').remove();"><i class="fa fa-times"></i></button></div><div class="col-xs-11">'+material+'</div></div><input type="hidden" name="materials[]" value="'+material+'" /></li>');

      material_count = material_count + 1;

      $('#input-material').val('');
    }
  }

  var tag_count = 0;

  function addTag() {
    var tag = $('#input-tag').val();

    if (tag != '') {
      $('#tag-container').append('<li class="list-group-item" id="tag-id-'+tag_count+'"><div class="row"><div class="col-xs-1"><button class="btn btn-danger btn-xs" title="" type="button" data-toggle="tooltip" data-original-title="<?php echo $text_tag_remove; ?>" onclick="$(\'#tag-id-'+tag_count+'\').remove();"><i class="fa fa-times"></i></button></div><div class="col-xs-11">'+tag+'</div></div><input type="hidden" name="tags[]" value="'+tag+'" /></li>');

      tag_count = tag_count + 1;

      $('#input-tag').val('');
    }
  }

  $('#input-category').on('change', function() {
    $.ajax({
      url: 'index.php?route=openbay/etsy_product/getsubcategory&token=<?php echo $token; ?>',
      beforeSend: function(){
        $('#input-category').attr('disabled','disabled');
        $('#input-sub-category').empty();
        $('#input-sub-sub-category').empty();
        $('#container-sub-category').hide();
        $('#container-sub-sub-category').hide();
        $('#category-id').val('');
        $('#category-loading').show();
        $('#category-selected').hide();
        $('#category-sub-default').remove();
      },
      type: 'post',
      data: {'tag' : $(this).val()},
      dataType: 'json',
      success: function(json) {
        $('#input-sub-category').append('<option id="category-sub-default" selected="selected"><?php echo $text_option; ?></option>');
        $.each(json.data, function( k, v ) {
          $('#input-sub-category').append('<option value="'+v.category_name+'">'+ v.long_name+'</option>');
        });

        $('#input-category').removeAttr('disabled');
        $('#container-sub-category').show();
        $('#category-loading').hide();
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
        $('#input-category').removeAttr('disabled');
      }
    });
  });

  $('#input-sub-category').on('change', function() {
    var sub_tag = $(this).val();

    $.ajax({
      url: 'index.php?route=openbay/etsy_product/getsubsubcategory&token=<?php echo $token; ?>',
      beforeSend: function(){
        $('#input-category').attr('disabled','disabled');
        $('#input-sub-category').attr('disabled','disabled');
        $('#input-sub-sub-category').empty();
        $('#container-sub-sub-category').hide();
        $('#category-id').val('');
        $('#sub-category-loading').show();
        $('#category-selected').hide();
        $('#category-sub-default').remove();
      },
      type: 'post',
      data: {'sub_tag' : sub_tag},
      dataType: 'json',
      success: function(json) {
        if ($.isEmptyObject(json.data)) {
          var category;

          category = getCategory(sub_tag);
        } else {
          $('#input-sub-sub-category').append('<option id="category-sub-sub-default" selected="selected"><?php echo $text_option; ?></option>');
          $.each(json.data, function( k, v ) {
            $('#input-sub-sub-category').append('<option value="'+v.category_name+'">'+ v.long_name+'</option>');
          });

          $('#container-sub-sub-category').show();
        }

        $('#input-category').removeAttr('disabled');
        $('#input-sub-category').removeAttr('disabled');
        $('#sub-category-loading').hide();
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
        $('#input-category').removeAttr('disabled');
        $('#input-sub-category').removeAttr('disabled');
      }
    });
  });

  $('#input-sub-sub-category').on('change', function() {
    $('#category-sub-sub-default').remove();
    getCategory($(this).val());
  });

  function getCategory(tag) {
    $.ajax({
      url: 'index.php?route=openbay/etsy_product/getcategory&token=<?php echo $token; ?>',
      beforeSend: function(){ },
      type: 'post',
      data: {'tag' : tag},
      dataType: 'json',
      success: function(json) {
        setCategory(json.data.category_id);
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  }

  function setCategory(id) {
    $('#category-id').val(id);
    $('#category-selected').show();
  }

  function getShippingProfiles() {
    $.ajax({
      url: 'index.php?route=openbay/etsy_shipping/getall&token=<?php echo $token; ?>',
      beforeSend: function(){
        $('#shipping-loading').show();
      },
      type: 'get',
      dataType: 'json',
      success: function(json) {
        if ($.isEmptyObject(json.data.results)) {
          alert('<?php echo $error_no_shipping; ?>');
        } else {
          $.each(json.data.results, function( k, v ) {
            $('#input-shipping').append('<option value="'+v.shipping_template_id+'">'+ v.title+'</option>');
          });
        }

        $('#input-shipping').removeAttr('disabled');
        $('#shipping-loading').hide();
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  }

  function getShopSection() {
    $.ajax({
      url: 'index.php?route=openbay/etsy_shop/getsections&token=<?php echo $token; ?>',
      beforeSend: function(){
        $('#shop-section-loading').show();
      },
      type: 'get',
      dataType: 'json',
      success: function(json) {
        if ($.isEmptyObject(json.data.results)) {
          alert('<?php echo $error_no_shop_secton; ?>');
        } else {
          $.each(json.data.results, function( k, v ) {
            $('#input-shop-section').append('<option value="'+v.shop_section_id+'">'+v.title+'</option>');
          });
        }

        $('#input-shop-section').removeAttr('disabled');
        $('#shop-section-loading').hide();
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  }

  $(document).ready(function() {
    getShippingProfiles();
    getShopSection();
  });

//--></script>
<?php echo $footer; ?>