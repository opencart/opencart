<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"> <a href="<?php echo $url_return; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a> </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
    <?php } ?>
    <?php if ($listing_errors) { ?>
    <div class="alert alert-danger">
      <ul>
        <?php foreach ($listing_errors as $listing_error) { ?>
        <li><i class="fa fa-exclamation-circle"></i> <?php echo $listing_error ?></li>
        <?php } ?>
      </ul>
    </div>
    <?php } ?>
    <div class="panel-body" id="search-container">
      <div class="alert alert-danger" id="search-error" style="display:none;"></div>
      <div class="well">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <input type="text" name="search_string" placeholder="<?php echo $text_placeholder_search; ?>" id="search-string" class="form-control" />
            </div>
          </div>
          <div class="col-md-1">
            <div class="form-group"> <a id="search-submit" class="btn btn-primary" data-toggle="tooltip" title="<?php echo $button_search; ?>"><i class="fa fa-search"></i></a> </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <?php foreach ($marketplaces as $id => $name) {?>
              <label class="radio-inline">
                <?php if ($default_marketplace == $id) { ?>
                <input type="radio" name="marketplace" id="marketplace_<?php echo $id ?>" value="<?php echo $id ?>" checked="checked" />
                <?php } else { ?>
                <input type="radio" name="marketplace" id="marketplace_<?php echo $id ?>" value="<?php echo $id ?>" />
                <?php } ?>
                <?php echo $name ?> </label>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
      <div class="well"> <?php echo $text_not_in_catalog; ?><a href="<?php echo $url_advanced; ?>" id="create_new" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_new; ?></a> </div>
    </div>
    <div class="panel-body" id="search-result-container" style="display:none;">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center"><?php echo $column_image ?></th>
            <th class="text-center"><?php echo $column_asin ?></th>
            <th class="text-left"><?php echo $column_name ?></th>
            <th class="text-center"><?php echo $column_price ?></th>
            <th class="text-center"><?php echo $column_action ?></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
    <div class="panel-body" id="chosen-product" style="display:none;">
      <div id="chosen-product-preview" class="well" style="display:none;"></div>
      <div class="panel">
        <div class="panel-body">
          <form method="POST" action="<?php echo $form_action ?>" class="form-horizontal">
            <input type="hidden" name="asin" value="" />
            <input type="hidden" name="marketplace" value="<?php echo $default_marketplace ?>" />
            <input type="hidden" name="product_id" value="<?php echo $product_id ?>" />
            <input type="hidden" name="quantity" value="<?php echo $quantity; ?>" id="quantity" />
            <ul class="nav nav-tabs">
              <li class="active"><a href="#required-info" data-toggle="tab"><?php echo $tab_required; ?></a></li>
              <li><a href="#additional-info" data-toggle="tab"><?php echo $tab_additional; ?></a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="required-info">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="quantity-display"><?php echo $entry_quantity; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="quantity_display" id="quantity-display" value="<?php echo $quantity; ?>" class="form-control" disabled/>
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="sku"><?php echo $entry_sku; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="sku" value="<?php echo $sku; ?>" placeholder="<?php echo $entry_sku; ?>" id="sku" class="form-control" />
                    <span class="help-block"><?php echo $help_sku; ?></span> </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="condition"><?php echo $entry_condition; ?></label>
                  <div class="col-sm-10">
                    <select name="condition" id="condition" class="form-control">
                      <?php foreach ($conditions as $value => $title) { ?>
                      <?php if ($value == $default_condition) { ?>
                      <option selected="selected" value="<?php echo $value; ?>"><?php echo $title; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $value; ?>"><?php echo $title; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group required">
                  <div class="col-sm-2">
                    <div class="row">
                      <div class="col-sm-12 text-right form-group">
                        <label class="control-label" for="price"><?php echo $entry_price; ?></label>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 text-right form-group"> <a id="button-amazon-price" class="btn btn-primary"><?php echo $button_amazon_price; ?></a> </div>
                    </div>
                  </div>
                  <div class="col-sm-10">
                    <input type="text" name="price" value="<?php echo $price; ?>" placeholder="<?php echo $entry_price; ?>" id="price" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="additional-info">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="condition_note"><?php echo $entry_condition_note; ?></label>
                  <div class="col-sm-10">
                    <textarea name="condition_note" class="form-control" rows="3" id="condition_note" placeholder="<?php echo $text_placeholder_condition; ?>"></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="sale_price"><?php echo $entry_sale_price; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="sale_price" placeholder="<?php echo $entry_sale_price; ?>" id="sale_price" class="form-control" />
                    <span class="help-block"><?php echo $help_sale_price; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="sale_price"><?php echo $entry_sale_date; ?></label>
                  <div class="col-sm-3">
                    <div class="input-group date">
                      <input type="text" class="form-control" id="sale_from" data-date-format="YYYY-MM-DD" placeholder="<?php echo $entry_from; ?>" name="sale_from">
                      <span class="input-group-btn">
                      <button type="button" class="btn btn-primary"><i class="fa fa-calendar"></i></button>
                      </span> </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="input-group date">
                      <input type="text" class="form-control" id="sale_to" data-date-format="YYYY-MM-DD" placeholder="<?php echo $entry_to; ?>" name="sale_to">
                      <span class="input-group-btn">
                      <button type="button" class="btn btn-primary"><i class="fa fa-calendar"></i></button>
                      </span> </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="start_selling"><?php echo $entry_start_selling; ?></label>
                  <div class="col-sm-3">
                    <div class="input-group date">
                      <input type="text" class="form-control" id="start_selling" data-date-format="YYYY-MM-DD" placeholder="<?php echo $entry_start_selling; ?>" name="start_selling">
                      <span class="input-group-btn">
                      <button type="button" class="btn btn-primary"><i class="fa fa-calendar"></i></button>
                      </span> </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-date-restock"><?php echo $entry_restock_date; ?></label>
                  <div class="col-sm-3">
                    <div class="input-group date">
                      <input type="text" class="form-control" id="input-date-restock" data-date-format="YYYY-MM-DD" placeholder="<?php echo $entry_restock_date; ?>" name="restock_date">
                      <span class="input-group-btn">
                      <button type="button" class="btn btn-primary"><i class="fa fa-calendar"></i></button>
                      </span> </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="well">
        <div class="row">
          <div class="col-md-12 text-right"> <a class="btn btn-primary" id="button-list" onclick="validateQuickListing();"><?php echo $button_list ?></a> </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $('#search-submit').bind('click', function(e) {
    e.preventDefault();

    $('#search-string').val($.trim($('#search-string').val()));

    $.ajax({
      url: 'index.php?route=openbay/amazon_listing/search&token=<?php echo $token; ?>',
      type: 'POST',
      dataType: 'json',
      data: {search_string : encodeURIComponent($('#search-string').val()), marketplace: $('input[name="marketplace"]:checked').val()},
      beforeSend: function(){
          $('#search-submit').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
          $('#search-error').hide();
          $('#search-result-container').hide();
          $('#chosen-product').hide();
      },
      complete: function() {
        $('#search-submit').empty().html('<i class="fa fa-search"></i> <?php echo $button_search; ?>').removeAttr('disabled').show();
      },
      success: function(data) {
          if (data.error){
              $('#search-error').empty().html('<i class="fa fa-exclamation-circle"></i>' + data.error).show();
          } else {
              var html = '';
              var count = 0;
              var funcString = '';

              $.each(data['data'], function(index, value) {
                  functString = "listProduct('" + value.asin + "')";

                  html += '<tr>';
                  html += '  <td class="text-center"><img src="' + value.image + '" /></td>';
                  html += '  <td class="text-center">' + value.asin + '</td>';
                  html += '  <td class="text-left">' + value.name + '</td>';
                  html += '  <td class="text-center">' + value.price + '</td>';
                  html += '  <td class="text-center">';
                  html += '    <a target="_blank" href="' + value.link + '" class="btn btn-primary" data-toggle="tooltip" title="<?php echo $button_view_on_amazon ?>"><i class="fa fa-eye"></i></a>';
                  html += '    <a onclick="' + functString + '" class="btn btn-primary" data-toggle="tooltip" title="<?php echo $text_list ?>"><i class="fa fa-check-square"></i></a>';
                  html += '  </td>';
                  html += '</tr>';

                  count++;
              });

              if (count != 0){
                  $('#search-result-container tbody').html(html);
                  $('#search-result-container').css('opacity', 0).slideDown('slow').animate({ opacity: 1 },{ queue: false, duration: 'slow' });
              } else {
                  $('#search-error').empty().text('<i class="fa fa-exclamation-circle"></i><?php echo $text_no_results; ?>').show();
              }
          }

          $('#search-submit').show();
      },
      error: function(){
        alert('error');
      },
      failure: function(){
        alert('failure');
      }
    });
  });

  $('#button-amazon-price').bind('click', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'index.php?route=openbay/amazon_listing/bestPrice&token=<?php echo $token; ?>',
        type: 'POST',
        dataType: 'json',
        data: $('form input[name="asin"], form select[name="condition"], form input[name="marketplace"]'),
        beforeSend: function(){
          $('#button-amazon-price').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
          $('#best-price-info').remove();
        },
        complete: function() {
          $('#button-amazon-price').empty().html('<?php echo $button_amazon_price; ?>').removeAttr('disabled').show();
        },
        success: function(data) {
          if (data['error']) {
            alert(data.error);
          } else {
            $('form input[name="price"]').val(data.data.amount);

            $('#price').before('<div class="alert alert-info" id="best-price-info">'+data.data.amount+' '+data.data.currency+' plus shipping '+data.data.shipping+' '+data.data.currency+'</div>');
          }
        },
        error: function(){
            alert('error');
        },
        failure: function(){
            alert('failure');
        }
    });
});

  $('#button-list').bind('click', function() {
    var error = false;

    if ($('#quantity').val() < 1){
        alert('<?php echo $error_stock; ?>');
        error = true;
    }

    if ($('#price').val() == '' || $('#price').val() == 0){
        alert('<?php echo $error_price; ?>');
        error = true;
    }

    if ($('#sku').val() == '' || $('#sku').val() == 0){
        alert('<?php echo $error_sku; ?>');
        error = true;
    }

    if (error == false){
        $('#chosen-product form').submit();
    }
});

  function listProduct(asin) {
    getProduct(asin);
    $('form input[name="asin"]').val(asin);
    $('#chosen-product').css('opacity', 0).slideDown('slow').animate({ opacity: 1 },{ queue: false, duration: 'slow' });
    $('#search-result-container').css('opacity', 1).slideUp('medium').animate({ opacity: 0 },{ queue: false, duration: 'medium' });
    $('html, body').animate({ scrollTop: 0 }, 'slow');
  }

  function getProduct(asin){
    $.ajax({
      url: 'index.php?route=openbay/amazon_listing/getProductByAsin&token=<?php echo $token; ?>',
      type: 'POST',
      dataType: 'json',
      data: {asin : asin, market : $('form input[name="marketplace"]').val() },
      beforeSend: function(){
        $('#chosen-product-preview').empty();
      },
      success: function(data) {
        var html = '';
        html += '<div class="row">';
          if (data.img != '') {
            html += '<div class="col-md-1 text-center">';
              html += '<img src="'+data.img+'" />';
            html += '</div>';
          }
          html += '<div class="col-md-11 text-left">';
            html += '<h2>'+data.title+'<br /><small>ASIN: '+asin+'</small></h2>';
          html += '</div>';
        html += '</div>';

        $('#chosen-product-preview').html(html).css('opacity', 0).slideDown('slow').animate({ opacity: 1 },{ queue: false, duration: 'slow' });
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  }

  $(document).ready(function() {
  $('.search-container input[name="marketplace"]').bind('change', function(){
        $('form input[name="marketplace"]').val($(this).val());
    });
});
</script>
<script type="text/javascript"><!--
$('.date').datetimepicker({
  pickTime: false
});

$('.datetime').datetimepicker({
  pickDate: true,
  pickTime: true
});

$('.time').datetimepicker({
  pickDate: false
});
//--></script>
<?php echo $footer; ?>