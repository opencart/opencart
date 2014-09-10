<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="container-fluid">
    <form id="product-form">
      <div class="alert alert-info">
        <p><?php echo $text_desc1; ?></p>
        <p><?php echo $text_desc2; ?></p>
        <p><?php echo $text_desc3; ?></p>
      </div>
      <div class="well">
        <div class="row">
          <div class="col-sm-12 text-right">
            <a class="btn btn-primary" id="button-load"><?php echo $button_load; ?></a>
          </div>
        </div>
      </div>
      <table class="table">
        <thead id="table-head-1">
          <tr>
            <th class="text-center" colspan="3"><h4><?php echo $text_new_link; ?></h4></th>
          </tr>
        </thead>
        <thead id="table-head-2">
          <tr>
            <th class="text-right"><?php echo $text_autocomplete_product; ?></th>
            <th class="text-left"><?php echo $text_amazon_sku; ?></th>
            <th class="text-center"><?php echo $text_action; ?></th>
          </tr>
        </thead>
        <tbody id="unlinked-items">
          <tr>
            <td class="text-right">
              <input type="hidden" id="new-product-id">
              <input id="new-product" type="text" class="form-control" autocomplete="off">
            </td>
            <td>
              <input id="new-amazon-sku" type="text" class="form-control" autocomplete="off">
            </td>
            <td class="text-center">
              <a class="btn btn-primary" id="add-new-button" onclick="addNewLinkAutocomplete()" data-toggle="tooltip" data-original-title="<?php echo $button_insert; ?>"><i class="fa fa-plus-circle"></i></a>
            </td>
          </tr>
        </tbody>
      </table>
      <table class="table">
        <thead>
          <tr>
            <th class="text-center" colspan="6"><h4><?php echo $text_linked_items; ?></h4></th>
          </tr>
        </thead>
        <thead>
          <tr>
            <th><?php echo $text_name; ?></th>
            <th><?php echo $text_model; ?></th>
            <th><?php echo $text_combination; ?></th>
            <th><?php echo $text_sku; ?></th>
            <th><?php echo $text_amazon_sku; ?></th>
            <th class="text-center"><?php echo $text_action; ?></th>
          </tr>
        </thead>
        <tbody id="linked-items"></tbody>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
  $(document).ready(function () {
    loadLinks();
  });

  function loadLinks() {
    $.ajax({
      url: '<?php echo html_entity_decode($get_item_links_ajax); ?>',
      type: 'get',
      dataType: 'json',
      data: 'product_id=' + encodeURIComponent($('#new-product-id').val()) + '&amazon_sku=' + encodeURIComponent($('#new-amazon-sku').val()),
      success: function (json) {
        var rows = '';
        for (i in json) {
          rows += '<tr>';
          rows += '<td class="text-left">' + json[i]['product_name'] + '</td>';
          rows += '<td class="text-left">' + json[i]['model'] + '</td>';
          rows += '<td class="text-left">' + json[i]['combi'] + '</td>';
          rows += '<td class="text-left">' + json[i]['sku'] + '</td>';
          rows += '<td class="text-left">' + json[i]['amazon_sku'] + '</td>';
          rows += '<td class="text-center"><a data-toggle="tooltip" data-original-title="<?php echo $button_remove; ?>" class="btn btn-danger" onclick="removeLink(this, \'' + json[i]['amazon_sku'] + '\');"><i class="fa fa-times-circle"></i></a></td>';
          rows += '</tr>';
        }
        $('#linked-items').html(rows);
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  }

  $('#button-load').bind('click', function(e) {
    e.preventDefault();

    $.ajax({
      url: '<?php echo html_entity_decode($get_unlinked_items_ajax); ?>',
      type: 'get',
      dataType: 'json',
      beforeSend: function () {
        $('#button-load').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
      },
      complete: function () {
        $('#button-load').empty().html('<?php echo $button_load; ?>').removeAttr('disabled');
      },
      success: function (json) {
        var thread1 = '';
        thread1 += '<tr>';
          thread1 += '<td class="text-center" colspan="6"><?php echo $text_unlinked_items; ?></td>';
        thread1 += '</tr>';
        $('#table-head-1').html(thread1);

        var thread2 = '';
        thread2 += '<tr>';
          thread2 += '<td><?php echo $text_name; ?></td>';
          thread2 += '<td><?php echo $text_model; ?></td>';
          thread2 += '<td><?php echo $text_combination; ?></td>';
          thread2 += '<td><?php echo $text_sku; ?></td>';
          thread2 += '<td><?php echo $text_amazon_sku; ?></td>';
          thread2 += '<td class="text-center"><?php echo $text_action; ?></td>';
        thread2 += '</tr>';
        $('#table-head-2').html(thread2);

        var rows = '';
        for (i in json) {
          rows += '<tr id="product_row_' + json[i]['product_id'] + '_' + json[i]['var'] + '">';
            rows += '<td class="text-left">' + json[i]['product_name'] + '</td>';
            rows += '<td class="text-left">' + json[i]['model'] + '</td>';
            rows += '<td class="text-left">' + json[i]['combi'] + '</td>';
            rows += '<td class="text-left">' + json[i]['sku'] + '</td>';
            rows += '<td class="text-left">';
              rows += '<div class="amazon_sku_div_' + json[i]['product_id'] + '_' + json[i]['var'] + '">';
                rows += '<div class="row">';
                  rows += '<div class="col-sm-8 form-group">';
                    rows += '<input class="form-control amazon_sku_' + json[i]['product_id'] + '_' + json[i]['var'] + '"  type="text">';
                  rows += '</div>';
                  rows += '<div class="col-sm-4 form-group">';
                    rows += '<a class="btn btn-primary" onclick="addNewSkuField(' + json[i]['product_id'] + ', \'' + json[i]['var'] + '\')" data-toggle="tooltip" data-original-title="<?php echo $button_insert; ?>"><i class="fa fa-plus-circle"></i></a>';
                  rows += '</div>';
                rows += '</div>';
              rows += '</div>';
            rows += '</td>';
            rows += '<td class="text-center"><a class="btn btn-primary" onclick="addNewLink(this, \'' + json[i]['product_id'] + '\', \'' + json[i]['var'] + '\')" data-toggle="tooltip" data-original-title="<?php echo $button_insert; ?>"><i class="fa fa-plus-circle"></i></a></td>';
          rows += '</tr>';
        }

        $('#unlinked-items').html(rows);
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  });

  function addLink(button, product_id, amazon_sku, variation) {
    $.ajax({
      url: '<?php echo html_entity_decode($add_item_link_ajax); ?>',
      type: 'get',
      dataType: 'json',
      async: 'false',
      data: 'product_id=' + encodeURIComponent(product_id) + '&amazon_sku=' + encodeURIComponent(amazon_sku) + '&var=' + encodeURIComponent(variation),
      beforeSend: function () {
        $(button).empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
      },
      complete: function () {
        $(button).empty().html('<i class="fa fa-plus-circle"></i>').removeAttr('disabled');
      },
      success: function (json) {
        loadLinks();
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  }

  function removeLink(button, amazon_sku) {
    $.ajax({
      url: '<?php echo html_entity_decode($remove_item_link_ajax); ?>',
      type: 'get',
      dataType: 'json',
      data: 'amazon_sku=' + encodeURIComponent(amazon_sku),
      beforeSend: function () {
        $(button).empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
      },
      success: function (json) {
        //alert(json);
        loadLinks();
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  }

  function addNewSkuField(product_id, variation) {
    var html = '';
    html += '<div class="amazon_sku_div_' + product_id + '_' + variation + '">';
      html += '<div class="row">';
        html += '<div class="col-sm-8 form-group">';
          html += '<input class="form-control amazon_sku_' + product_id + '_' + variation + '"  type="text">';
        html += '</div>';
        html += '<div class="col-sm-4 form-group">';
          html += '<a class="btn btn-danger remove_sku_icon_' + product_id + '_' + variation + '" onclick="removeSkuField(this, \'' + product_id + '\', \'' + variation + '\')"><i class="fa fa-minus-circle"></i></a>';
        html += '</div>';
      html += '</div>';
    html += '</div>';

    $(".amazon_sku_div_" + product_id + "_" + variation.replace(":", "\\:")).last().after(html);
  }

  function removeSkuField(icon, product_id, variation) {
    var removeIndex = $('.remove_sku_icon_' + product_id + '_' + variation.replace(':', '\\:')).index($(icon)) + 1;
    $(".amazon_sku_div_" + product_id + "_" + variation.replace(':', '\\:') + ":eq(" + removeIndex + ")").remove();
  }

  function addNewLink(button, product_id, variation) {
    var errors = 0;

    console.log(".amazon_sku_" + product_id + "_" + variation.replace(':', '\\:'));
    $(".amazon_sku_" + product_id + "_" + variation.replace(':', '\\:')).each(function (index) {
      if ($(this).val() == '') {
        errors++;
      }
    });
    if (errors > 0) {
      alert('<?php echo $error_empty_sku; ?>');
      return;
    }

    $(".amazon_sku_" + product_id + "_" + variation.replace(':', '\\:')).each(function (index) {
      addLink(button, product_id, $(this).val(), variation);
    });

    $("#product_row_" + product_id + "_" + variation.replace(':', '\\:')).remove();
  }

  function addNewLinkAutocomplete() {
    if ($('#new-product').val() == "") {
      alert('<?php echo $error_empty_name; ?>');
      return;
    }

    if ($('#new-product-id').attr('label') != $('#new-product').val()) {
      alert('<?php echo $error_no_product_exists; ?>');
      return;
    }

    if ($('#new-amazon-sku').val() == "") {
      alert('<?php echo $error_empty_sku; ?>');
      return;
    }

    var product_id = $('#new-product-id').val();
    var amazon_sku = $('#new-amazon-sku').val();
    var variation = '';
    if ($('#openstock-option-selector').length != 0) {
      variation = $('#openstock-option-selector').val();
    }

    addLink('#add-new-button', product_id, amazon_sku, variation);

    $('#new-product').val('');
    $('#new-amazon-sku').val('');
    $('#new-product-id').val('');
    $('#new-product-id').attr('label', '');
    $('#openstock-option-selector').remove();
  }

  $('#new-product').autocomplete({
    'source': function(request, response) {
      $.ajax({
        url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
        dataType: 'json',
        success: function (json) {
          response($.map(json, function (item) {
            return {
              label: item['name'],
              value: item['product_id']
            }
          }));
        }
      });
    },
    'select': function (item) {
      $('#new-product-id').val(item['value']);
      $('#new-product').val(item['label']);
      $('#new-product-id').attr('label',item['label']);
      openstockCheck(item['value']);
    }
  });

  function openstockCheck(product_id) {
    $.ajax({
      url: '<?php echo html_entity_decode($get_openstock_options_ajax); ?>',
      dataType: 'json',
      type: 'get',
      data: 'product_id=' + product_id,
      success: function (data) {
        if (!data) {
          $("#openstock-option-selector").remove();
          return;
        }

        var optionHtml = '<select id="openstock-option-selector"><option value=""/>';
        for (var i in data) {
          optionHtml += '<option value="' + data[i]['var'] + '">' + data[i]['combi'] + '</option>';
        }
        optionHtml += '</select>';
        $('#new-product').after(optionHtml);
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  }
//--></script>