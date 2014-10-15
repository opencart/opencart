<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"> <a href="<?php echo $link_overview; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a> </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($bulk_listing_status) { ?>
    <div class="well">
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <label class="control-label" for="filter-marketplace"><?php echo $text_marketplace; ?></label>
            <select id="filter-marketplace" name="filter_marketplace" class="form-control">
              <?php foreach ($marketplaces as $marketplace) { ?>
              <?php if ($filter_marketplace == $marketplace['code']) { ?>
              <option selected="selected" value="<?php echo $marketplace['code'] ?>"><?php echo $marketplace['name'] ?></option>
              <?php } else { ?>
              <option value="<?php echo $marketplace['code'] ?>"><?php echo $marketplace['name'] ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12 text-right"> <a class="btn btn-primary" id="button-filter"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></a> <a class="btn btn-primary" id="button-search"><i class="fa fa-search"></i> <?php echo $button_search; ?></a> </div>
      </div>
    </div>
    <form id="bulk-list-form" class="form-horizontal">
      <table class="table">
        <thead>
          <tr>
            <th class="text-center"><input type="checkbox"/></th>
            <th class="text-center"><?php echo $column_image; ?></th>
            <th class="text-left"><?php echo $column_name; ?></th>
            <th class="text-right"><?php echo $column_model; ?></th>
            <th class="text-right"><?php echo $column_status; ?></th>
            <th class="text-right"><?php echo $column_matches; ?></th>
            <th class="text-left"><?php echo $column_result; ?></th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($products)) { ?>
          <?php foreach ($products as $product) { ?>
          <tr>
            <td class="text-center"><input class="amazon-listing" type="checkbox" name="product_ids[]" value="<?php echo $product['product_id'] ?>"/></td>
            <td class="text-center"><img src="<?php echo $product['image'] ?>"/></td>
            <td class="text-left"><a href="<?php echo $product['href'] ?>" target="_blank"><?php echo $product['name'] ?></a></td>
            <td class="text-right"><?php echo $product['model'] ?></td>
            <td class="text-right"><?php echo $product['search_status'] ?></td>
            <td class="text-right"><?php if ($product['matches'] !== null) { ?>
              <?php echo $product['matches'] ?>
              <?php } else { ?>
              -
              <?php } ?></td>
            <td class="text-left" id="result-<?php echo $product['product_id'] ?>"><?php if ($product['matches'] !== null) { ?>
              <?php $checked = false; ?>
              <?php if ($product['matches'] > 0) { ?>
              <input class="amazon-listing" type="radio" name="products[<?php echo $product['product_id'] ?>]" value=""/>
              <?php echo $text_dont_list ?><br/>
              <?php foreach ($product['search_results'] as $search_result) { ?>
              <?php if (!$checked) { ?>
              <input class="amazon-listing" checked="checked" type="radio" name="products[<?php echo $product['product_id'] ?>]" value="<?php echo $search_result['asin'] ?>"/>
              <?php $checked = true; ?>
              <?php } else { ?>
              <input class="amazon-listing" type="radio" name="products[<?php echo $product['product_id'] ?>]" value="<?php echo $search_result['asin'] ?>"/>
              <?php } ?>
              <a target="_blank" href="<?php echo $search_result['href'] ?>"><?php echo $search_result['title'] ?></a><br/>
              <?php } ?>
              <?php } else { ?>
              <input class="amazon-listing" checked="checked" type="radio" name="products[<?php echo $product['product_id'] ?>]" value=""/>
              <?php echo $text_dont_list ?><br/>
              <?php } ?>
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td colspan="7" class="text-center"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <div class="well">
        <h4><?php echo $text_listing_values ?></h4>
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="input-condition" class="control-label"><?php echo $entry_condition; ?></label>
              <select name="condition" class="form-control" id="input-condition">
                <option value=""></option>
                <?php foreach ($conditions as $value => $name) { ?>
                <?php if ($value == $default_condition) { ?>
                <option selected="selected" value="<?php echo $value ?>"><?php echo $name ?></option>
                <?php } else { ?>
                <option value="<?php echo $value ?>"><?php echo $name ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="input-condition-note" class="control-label"><?php echo $entry_condition_note; ?></label>
              <input type="text" name="condition_note" class="form-control" id="input-condition-note" />
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="input-start" class="control-label"><?php echo $entry_start_selling; ?></label>
              <div class="input-group date">
                <input type="text" class="form-control" id="input-start" data-format="YYYY-MM-DD" placeholder="<?php echo $entry_start_selling; ?>" name="start_selling">
                <span class="input-group-btn">
                <button type="button" class="btn btn-primary"><i class="fa fa-calendar"></i></button>
                </span> </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="pull-right"> <a class="btn btn-primary" id="button-list"><i class="fa fa-plus-circle"></i> <?php echo $button_list; ?></a> </div>
          </div>
        </div>
      </div>
    </form>
    <div class="row">
      <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
      <div class="col-sm-6 text-right"><?php echo $results; ?></div>
    </div>
    <?php } else { ?>
    <div class="warning"><?php echo $error_bulk_listing_permission ?></div>
    <?php } ?>
  </div>
  <script type="text/javascript"><!--
  $(document).ready(function () {
    $('#product-form table thead input[type="checkbox"]').change(function () {
      var checkboxes = $('input[name="product_ids[]"]');
      if ($(this).is(':checked')) {
        checkboxes.attr('checked', 'checked');
      } else {
        checkboxes.removeAttr('checked');
      }
    });

    $('input[name="product_ids[]"]').change(function () {
      if (!$(this).is(':checked')) {
        $('#product-form table thead input[type="checkbox"]').removeAttr('checked');
      }
    });
  });

  $('#button-filter').bind('click', function() {
    url = 'index.php?route=openbay/amazon/bulkListProducts&token=<?php echo $token ?>';
    url += '&filter_marketplace=' + $("select[name='filter_marketplace']").val();
    location = url;
  });

  $('#button-list').bind('click', function(e) {
    e.preventDefault();

    var request_data = $('input.amazon-listing:checked').serialize();

    if (request_data) {
      request_data += '&marketplace=<?php echo $filter_marketplace ?>';
    }

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
      url: 'index.php?route=openbay/amazon/doBulkList&token=<?php echo $token ?>',
      data: request_data,
      dataType: 'json',
      type: 'POST',
      success: function (json) {
        $('.warning, .success').remove();

        var html = '';

        if (json.status) {
          html = '<div class="success">' + json.message + '</div>';
          $('input.amazon-listing:checked[value!=""]').parent().parent().fadeOut(450);
        } else {
          html = '<div class="warning">' + json.message + '</div>';
        }

        $('.box').prepend(html);
      },
      error: function(xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  });

  $('#button-search').bind('click', function(e) {
    e.preventDefault();

    var request_data = $('input[name="product_ids[]"]:checked').serialize();

    if (request_data != '') {
      request_data += '&marketplace=<?php echo $filter_marketplace; ?>';

      $.ajax({
        url: 'index.php?route=openbay/amazon/doBulkSearch&token=<?php echo $token ?>',
        data: request_data,
        dataType: 'json',
        type: 'POST',
        beforeSend: function() {
          $('.alert').remove();
        },
        success: function (json) {
          $.each(json, function (key, value) {
            var element = $('#result-' + key);
            if (value.error) {
              element.html('<div class="alert alert-danger">' + value.error + '</span>');
            } else if (value.success) {
              element.html('<div class="alert alert-success">' + value.success + '</span>');
            }

            $('input[name="product_ids[]"]').removeAttr('checked');
          });
        },
        error: function(xhr, ajaxOptions, thrownError) {
          if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
        }
      });
    } else {
      $('#bulk-list-form').prepend('<div class="alert alert-danger"><?php echo $error_select_items; ?></div>');
    }
  });
//--></script></div>
<?php echo $footer; ?>