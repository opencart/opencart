<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($errors) { ?>
  <div class="alert alert-danger">
    <ul>
      <?php foreach ($errors as $listing_error) { ?>
        <li><i class="fa fa-exclamation-circle"></i> <?php echo $listing_error ?></li>
      <?php } ?>
    </ul>
  </div>
  <?php } ?>
  <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <a href="<?php echo $cancel_url; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn"><i class="fa fa-reply"></i></a>
      </div>
      <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $text_title_advanced; ?></h1>
    </div>
    <div class="panel-body">

      <ul class="nav nav-tabs" id="tabs">
        <li class="active"><a href="#page-main" data-toggle="tab"><?php echo $text_tab_main; ?></a></li>
      </ul>

      <form method="POST" id="product_form_advanced" class="form-horizontal">
        <input type="hidden" name="upload_after" value="false">

        <div class="tab-content" id="tab-content">
          <div class="tab-pane active" id="page-main">
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $listing_row_text; ?></label>
              <div class="col-sm-10">
                <a href="<?php echo $listing_url; ?>"><?php echo $listing_name; ?><?php if(!empty($options)) { echo " : "; } ?></a>
                <?php if(!empty($options)) { ?>
                  <select id="openstock_selector" name="optionVar" class="form-control">
                    <option></option>
                    <?php foreach($options as $option) { ?>
                      <option <?php if ($variation === $option['var']) { echo "selected='selected'";} ?> value="<?php echo  $option['var']?>"><?php echo $option['combi']?></option>
                    <?php } ?>
                  </select>
                <?php }?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $marketplaces_field_text; ?></label>
              <div class="col-sm-10" id="marketplaces">
                <?php foreach ($marketplaces as $mp) { ?>
                  <label class="radio-inline">
                    <?php if($saved_marketplaces === false) { ?>
                    <input class="marketplace_ids" id="adv_marketplace_<?php echo $mp['code'] ?>" <?php if (in_array($mp['id'], $default_marketplaces)) { ?> checked="checked" <?php } ?> type="radio" name="marketplace_ids[]" value="<?php echo $mp['id']; ?>">
                    <?php } else { ?>
                    <input class="marketplace_ids" id="adv_marketplace_<?php echo $mp['code'] ?>" <?php if (in_array($mp['id'], $saved_marketplaces)) { ?> checked="checked" <?php } ?> type="radio" name="marketplace_ids[]" value="<?php echo $mp['id']; ?>">
                    <?php } ?>
                    <?php echo $mp['name'] ?>
                  </label>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="category_selector" id="category_selector_label"><?php echo $category_selector_field_text; ?></label>
              <div class="col-sm-4">
                <select name="category_selector" id="category_selector" class="form-control">
                  <option value=""></option>
                  <?php foreach($amazon_categories as $category) {  ?>
                  <option <?php if ($edit_product_category == $category["name"]) echo 'selected="selected"'; ?> value="<?php echo $category['template'] ?>"><?php echo $category['friendly_name'] ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <table class="table">
            <tbody class="fields_advanced"></tbody>
            </table>
          </div>
        </div>
<!--
    <div id="greyScreen"></div>
    <div id="browseNodeForm" class="greyScreenBox nodePage">
      <div class="bold border p5 previewClose">X</div>
      <div id="browseNodeFormContent"></div>
    </div>
-->
      </form>
    </div>
  </div>
  <div class="well">
    <div class="row">
      <div class="col-md-12 text-right">
        <a class="btn btn-primary" onclick="validate_and_save('advanced')"><i class="fa fa-save"></i> <?php echo $save_button_text ?></a>
        <a class="btn btn-primary" onclick="save_and_upload()"><i class="fa fa-cloud-upload"></i> <?php echo $save_upload_button_text ?></a>
        <a class="btn btn-primary" href="<?php echo $saved_listings_url; ?>"><i class="fa fa-copy"></i> <?php echo $saved_listings_button_text ?></a>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript"><!--
$(document).ready(function(){
    $('#openstock_selector').change(function() {
        redirectOption($('#openstock_selector').val(), 'advanced');
    });

    <?php if(empty($amazon_categories)) { ?>
        $("#advanced_table").html("");
        $(".content").prepend('<div id="warning" class="alert alert-danger"><?php echo $text_error_connecting; ?></div>');
        return;
    <?php } ?>

    $(".fields_advanced :input").bind('change', function() {
        update_form(this, 'advanced');
    });

    $('#category_selector').change(function(){
        var xml = $('#category_selector').val();
        if(xml == '') {
            $('.fields_advanced').empty();
            $('.dynamic-tab').remove();
            $('.dynamic-page').remove();
            return;
        }
        show_form(xml, 'advanced');
    });
    //Update needed if editing
    $('#category_selector').change();
    $('#product_form_advanced input[name=upload_after]').val(false);

});

function redirectOption(varOption, tabOption) {
    var searchLoc = insertParamToUrl(document.location.search, 'var', varOption);
    searchLoc = insertParamToUrl(searchLoc, 'tab', tabOption);
    searchLoc = searchLoc.substr(1);
    if(document.location.search === searchLoc) {
        return false;
    } else {
        document.location.search = searchLoc;
        return true;
    }
}

function insertParamToUrl(searchLoc, key, value) {
    var kvp = searchLoc.split('&');

    if (kvp == '') {
        searchLoc = '?' + key + '=' + value;
        return searchLoc;
    }
    else {
        var i = kvp.length; var x; while (i--) {
            x = kvp[i].split('=');
            if (x[0] == key) {
                if(x[1] == value) {
                    return searchLoc;
                }
                x[1] = value;
                kvp[i] = x.join('=');
                break;
            }
        }
        if (i < 0) { kvp[kvp.length] = [key, value].join('='); }
        return kvp.join('&');
    }
}

var fieldsArray = new Array();

function show_form(xml, formType) {
    $('.fields_' + formType).empty();
    $('.dynamic-tab').remove();
    $('.dynamic-page').remove();

    var parserURL = '<?php echo html_entity_decode($template_parser_url) ?>';
    var reqUrl = parserURL + '&xml=' + xml;

    if($('#openstock_selector').val() !== undefined) {
        reqUrl = reqUrl + '&var=' + $('#openstock_selector').val();
    }

    $.ajax({
        url: reqUrl,
        data: {},
        dataType: 'json',
        beforeSend: function() {
            $('#category_selector').attr('disabled', 'disabled');
            $('#category_selector_label').after('<a class="btn btn-primary wait" disabled="disabled"><i class="fa fa-refresh fa-spin"></i> </a>');
        },
        complete: function() {
            $('#category_selector').removeAttr('disabled');
            $('.wait').remove();
        },
        success: function(data) {
            if(data['status'] === 'error') {
                if('info' in data) {
                    alert(data['info']);
                } else {
                    alert('Unexpected error.');
                }
                return;
            }
            for(tab in data['tabs']) {
                $('#tabs').append('<li class="dynamic-tab"><a href="#page-' + data['tabs'][tab]['id'] + '" data-toggle="tab">' + data['tabs'][tab]['name'] + '</a></li>');

                var pageHtml = '';
                pageHtml += '<div id="page-' + data['tabs'][tab]['id'] + '" class="tab-pane dynamic-page">';
                  pageHtml += '<div class="fields_advanced"></div>';
                pageHtml += '</div>'

                $('#tab-content').append(pageHtml);
            }

            var categoryName = data['category'];
            fieldsArray[formType] = data['fields'];

            $('.fields_' + formType).append('<input type="hidden" name="category" value="' + categoryName + '">');

            for (i in fieldsArray[formType]) {
              var row  = '<div class="form-group';


              if(fieldsArray[formType][i]['type'] == 'required') {
                row += ' required';
              }

              if (fieldsArray[formType][i]['child']){
                  row += ' child_row" display="no" field_index="' + i + '" style="display: none">';
              } else {
                  row += '">';
              }

              row += '<label class="col-sm-2 control-label">'+fieldsArray[formType][i]['title']+'</label>';
              row += '<div class="col-sm-10">';

              row += '<div class="alert alert-danger" id="error_' + fieldsArray[formType][i]['name'] + '" style="display:none;"></div>'

              if(fieldsArray[formType][i]['name'] == "Quantity") {
                  row += getQuantityField(fieldsArray[formType][i]);
              } else if(fieldsArray[formType][i]['accepted']['type'] == "integer") {
                if(fieldsArray[formType][i]['name'] == 'RecommendedBrowseNode' || fieldsArray[formType][i]['name'] == 'RecommendedBrowseNode2'){
                  row += getBrowseNodeField(fieldsArray[formType][i]);
                } else {
                  row += getIntegerField(fieldsArray[formType][i]);
                }
              }
              else if(fieldsArray[formType][i]['accepted']['type'] == "text_area") {
                  row += getTextAreaField(fieldsArray[formType][i]);
              }
              else if(fieldsArray[formType][i]['accepted']['type'] == "select") {
                  row += getSelectField(fieldsArray[formType][i]);
              }
              else if(fieldsArray[formType][i]['accepted']['type'] == "image") {
                  row += getImageField(fieldsArray[formType][i]);
              }
              else {
                  row += getStringField(fieldsArray[formType][i]);
              }

              if (fieldsArray[formType][i]['definition']) {
                row += '<span class="help-block">' + fieldsArray[formType][i]['definition'] + '</span>';
              }

              row += '</div>';
              row += '</div>';

              $('#page-' + fieldsArray[formType][i]['tab'] + ' .fields_' + formType).append(row);
            }

            //Emulate changes to populate child fields
            $('.fields_' + formType + ' :input').each(function (i) {
                $(this).change();
            });
        },
        error: function (xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
}

//Called when chenge to form was made. Shows child rows bassed on input if needed.
function update_form(element, formType) {
    var changedFieldName = $(element).attr('field_name');
    var changedFieldValue = $(element).val();

    $('.fields_' + formType + ' .child_row').each(function (i) {
        var index = $(this).attr('field_index');
        if(fieldsArray[formType][index]['parent']['name'] == changedFieldName) {
            var showChild = false;

            //values is array?
            if(fieldsArray[formType][index]['parent']['value'] instanceof Array) {
                for(i in fieldsArray[formType][index]['parent']['value']) {
                    if(fieldsArray[formType][index]['parent']['value'][i] == changedFieldValue) {
                        showChild = true;
                    }
                }
            } else if(fieldsArray[formType][index]['parent']['value'] == changedFieldValue) {
                showChild = true;
            } else if(fieldsArray[formType][index]['parent']['value'] == '*' && changedFieldValue != '') {
                showChild = true;
            }

            if(showChild) {
                $(this).attr('display', 'yes');
                $(this).removeAttr('style');
            } else {
                $('.fields_' + formType + ' [field_name="' + fieldsArray[formType][index]['name']  + '"]').attr('value', '');
                $(this).attr('display', 'no');
                $(this).attr('style', 'display: none');
            }
        }
    });
}

function getImageField(fieldData) {
    var output = "";

    output += '<input ';
    output += 'type="hidden" ';
    output += 'accepted="' + fieldData['accepted']['type'] + '" ';
    output += 'field_name="' + fieldData['name'] + '" ';
    output += 'field_type="' + fieldData['type'] + '" ';
    output += 'id="imagefield_' + fieldData['name'] + '" ';
    output += 'name="fields[' + fieldData['name'] + ']" ';
    output += 'value="' + fieldData['value'] + '">';

    output += '<div class="image">';
    output += '<img height="100" alt="" id="thumb_' + fieldData['name'] + '" ';
    if(fieldData['value'] === "") {
        output += 'src="<?php echo $no_image; ?>"';
    } else if(fieldData['thumb'] !== "") {
        output += 'src="' + fieldData['thumb'] + '"';
    } else {
        output += 'src="' + fieldData['value'] + '"';
    }
    output += "/>";
    output += '<br />';

    output += '<a onclick="image_upload(\'imagefield_' + fieldData['name'] + '\', \'thumb_' + fieldData['name'] + '\')"><?php echo $browse_image_text; ?></a>';
    output += '  |  ';
    output += '<a onclick="cleaImageField(\'' + fieldData['name'] + '\')"><?php echo $clear_image_text; ?></a>';
    output += "</div>";

    return output;
}

function cleaImageField(fieldName) {
    $('#imagefield_' + fieldName).attr('value', '');
    $('#thumb_' + fieldName).attr('src', '<?php echo $no_image; ?>');
}

function getQuantityField(fieldData) {
    var output = "";

    output += fieldData['value'];
    output += '<input ';
    output += 'type="hidden" ';
    output += 'min="0" ';
    output += 'accepted="' + fieldData['accepted']['type'] + '" ';
    output += 'field_name="' + fieldData['name'] + '" ';
    output += 'field_type="' + fieldData['type'] + '" ';
    output += 'name="fields[' + fieldData['name'] + ']" ';
    output += 'value="' + fieldData['value'] + '" class="form-control">';

    return output;
}

function getIntegerField(fieldData) {
  var output = "";

  output += '<input ';
  output += 'type="number" ';
  output += 'min="0" ';
  output += 'accepted="' + fieldData['accepted']['type'] + '" ';
  output += 'field_name="' + fieldData['name'] + '" ';
  output += 'field_type="' + fieldData['type'] + '" ';
  output += 'name="fields[' + fieldData['name'] + ']" ';
  output += 'class="form-control" ';
  output += 'value="' + fieldData['value'] + '">';

  return output;
}

function getBrowseNodeField(fieldData) {
  var output = "";

  output += '<div class="input-group col-md-3">';
  output += '<input ';
  output += 'type="number" ';
  output += 'min="0" ';
  output += 'accepted="' + fieldData['accepted']['type'] + '" ';
  output += 'field_name="' + fieldData['name'] + '" ';
  output += 'field_type="' + fieldData['type'] + '" ';
  output += 'name="fields[' + fieldData['name'] + ']" ';
  output += 'class="browseNode form-control" ';
  output += 'value="' + fieldData['value'] + '">';
  output += '<span class="input-group-btn">';
  output += '<button class="btn btn-primary" type="button"><i class="fa fa-sitemap fa-lg"></i></button>';
  output += '</span>';
  output += '</div>';

  return output;
}

function getTextAreaField(fieldData) {
    var output = "";

    output += '<textarea ';
    if('min_length' in fieldData['accepted']) {
        output += 'min_length="'+ fieldData['accepted']['min_length'] + '" ';
    }
    if('max_length' in fieldData['accepted']) {
        output += 'max_length="'+ fieldData['accepted']['max_length'] + '" ';
    }
    output += 'field_name="' + fieldData['name'] + '" ';
    output += 'field_type="' +  fieldData['type'] + '" ';
    output += 'name="fields[' + fieldData['name'] + ']" class="form-control" rows="3">';
    output += fieldData['value'];
    output += '</textarea>';

    return output;
}

function getStringField(fieldData) {
    var output = "";

    output += '<input type="text"';
    output += 'accepted="' + fieldData['accepted']['type'] + '" ';
    if('min_length' in fieldData['accepted']) {
        output += 'min_length="'+ fieldData['accepted']['min_length'] + '" ';
    }
    if('max_length' in fieldData['accepted']) {
        output += 'max_length="'+ fieldData['accepted']['max_length'] + '" ';
    }
    output += 'field_name="' + fieldData['name'] + '" ';
    output += 'field_type="' + fieldData['type'] + '" ';
    output += 'name="fields[' + fieldData['name'] + ']" ';
    output += 'value="' + fieldData['value'] + '" class="form-control">';

    return output;
}

function getSelectField(fieldData) {
    var output = "";

    output += '<select ';
    output += 'field_name="' + fieldData['name'] + '" ';
    output += 'field_type="' + fieldData['type'] + '" ';
    output += 'name="fields[' + fieldData['name'] + ']" class="form-control">';

    output += '<option></option>';

    if(fieldData['accepted']['option'].length != undefined) {
        for(j in fieldData['accepted']['option']) {
            output += '<option ';

            if(fieldData['value'].toLowerCase() == fieldData['accepted']['option'][j]['value'].toLowerCase()) {
                output += 'selected="selected" ';
            }
            output += 'value="' + fieldData['accepted']['option'][j]['value'] + '">';
            output += fieldData['accepted']['option'][j]['name'];
            output += '</option>';
        }
    }
    else {
        output += '<option ';

        if(fieldData['value'].toLowerCase() == fieldData['accepted']['option']['value'].toLowerCase()) {
            output += 'selected="selected" ';
        }
        output += 'value="' + fieldData['accepted']['option']['value'] + '">';
        output += fieldData['accepted']['option']['name'];
        output += '</option>';
    }
    output += '</select>';
    return output;
}

function image_upload(field, thumb) {
    $('#dialog').remove();

    $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

    $('#dialog').dialog({
        title: '',
        close: function (event, ui) {
            if ($('#' + field).val()) {
                $.ajax({
                    url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
                    dataType: 'text',
                    success: function(data) {
                        if(data != "") {
                            $('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
                            var imageUrl = $('#' + field).val();
                            $('#' + field).attr('value', '<?php echo HTTPS_CATALOG; ?>image/' + imageUrl);
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                  }
                });
            }
        },
        bgiframe: false,
        width: 800,
        height: 400,
        resizable: false,
        modal: false
    });
}

function validate(formType) {
    if($('#category_selector').val() == '') {
        return false;
    }

    var warnings = 0;
    var mChecked = 0;

    $('#marketplaces_' + formType + ' :input').each(function (i) {
        if($(this).attr('checked') == 'checked') {
            mChecked++;
        }
    });

    if(mChecked == 0) {
        $('#required_marketplaces').prepend('<div class="alert alert-danger" id="marketplace-alert"><?php echo $field_required_text ?></div>');
        warnings ++;
    } else {
        $('#marketplace-alert').remove();
    }

    var productIdType;
    var productId;
    var productIdRequired;

    $('.fields_' + formType + ' :input').each(function (i) {
        if($(this).parent().parent().attr('display') === "no") {
            return;
        }

        var field_value = $(this).val();
        var field_name = $(this).attr('field_name');
        var field_type = $(this).attr('field_type');
        var min_length = $(this).attr('min_length');
        var max_length = $(this).attr('max_length');

        if(field_name === 'Type') {
            productIdType = field_value;
        } else if(field_name === 'Value') {
            productId = field_value;
            if(field_type === 'required') {
                productIdRequired = true;
            } else {
                productIdRequired = false;
            }
        }

        if(field_type == 'required' || field_value !== '') {
            if(field_value === '') {
                $('.fields_' + formType + ' #error_' + field_name).text('<?php echo $field_required_text ?>').show();
                warnings ++;
            }
            else if (min_length != undefined && field_value.length < min_length) {
                $('.fields_' + formType + ' #error_' + field_name).text('<?php echo $minimum_length_text; ?> ' + min_length + ' <?php echo $characters_text; ?>').show();
                warnings ++;
            }
            else if (max_length != undefined && field_value.length > max_length) {
                $('.fields_' + formType + ' #error_' + field_name).text((field_value.length - max_length) + ' <?php echo $chars_over_limit_text; ?>').show();
                warnings ++;
            }
            else {
                $('.fields_' + formType + ' #error_' + field_name).text('').hide();
            }
        }
    });

    if(productIdRequired && productIdType !== 'ASIN' && !isValidProductId(productId)) {
        $('.fields_' + formType + ' :input').each(function (i) {
            var field_name = $(this).attr('field_name');
            if(field_name === 'Value') {
                $('.fields_' + formType + ' #error_' + field_name).text('Not valid product ID!');
                warnings ++;
                return;
            }
        });
    }

    if($('.fields_' + formType + ' [name="category"]').val() == undefined) {
        warnings ++;
    }

    if(warnings > 0) {
        return false;
    } else {
        return true;
    }
}

function validate_and_save(formType) {
    if(validate(formType)) {
        if (formType == 'advanced') {
            $("#product_form_advanced").submit();
        } else if (formType == 'quick') {
            $("#product_form_quick").submit();
        }
    } else {
        alert('<?php echo $not_saved_text; ?>');
    }
}

function save_and_upload() {
    $('#product_form_advanced input[name=upload_after]').val(true);

    if(validate('advanced')) {
        $("#product_form_advanced").submit();
    } else {
        alert('<?php echo $not_saved_text; ?>');
    }
}

function isValidProductId(value) {
    var barcode = value.substring(0, value.length - 1);
    var checksum = parseInt(value.substring(value.length - 1), 10);
    var calcSum = 0;
    var calcChecksum = 0;
    barcode.split('').map(function(number, index ) {
        number = parseInt(number, 10);
        if(value.length === 13) {
            if (index % 2 === 0) {
                calcSum += number;
            }
            else {
                calcSum += number * 3;
            }
        } else {
            if (index % 2 === 0) {
                calcSum += number * 3;
            }
            else {
                calcSum += number;
            }
        }
    });
    calcSum %= 10;
    calcChecksum = (calcSum === 0) ? 0 : (10 - calcSum);
    if (calcChecksum !== checksum) {
        return false;
    }
    return true;
}

var nodeBox = '';
var nodeString = '';
var nodeStringSimple = '';

$('.browseNode').bind('click', function(){
    var html = '';
    var market = $('.marketplace_ids:checked').val();

    nodeBox = $(this).attr("field_name");
    $('#'+nodeBox+'_text').remove();
    $(this).val('');

    nodeString = '';
    nodeStringSimple = '';

    $.ajax({
        url: 'index.php?route=openbay/amazon_listing/getBrowseNodes&token=<?php echo $token; ?>',
        type: 'POST',
        data: { marketplaceId: market},
        dataType: 'json',
        beforeSend: function(){
            $('#browseNodeFormContent').empty();
            showGreyScreen('browseNodeForm');
        },
        success: function(data) {
            if(data.node.error != true){
                html += '<select class="nodeSelect mTop20 width250">';
                html += '<option value=""><?php echo $option_default; ?></option>';

                $.each(data.children, function(k,v){
                    html += '<option value="'+ v.node_id+'">'+ v.name+'</option>';
                });

                html += '</select><br />';

                $('#browseNodeFormContent').html(html);
            }else{
                alert(data.node.error);
                hideGreyScreen('browseNodeForm');
            }
        },
        failure: function(){
            alert('<?php echo $text_error_load_nodes; ?>');
            hideGreyScreen('browseNodeForm');
        },
        error: function(){
            alert('<?php echo $text_error_load_nodes; ?>');
            hideGreyScreen('browseNodeForm');
        }
    });
});

$('.nodeSelect').bind('change', function(){
    //called when the root node id is chosen
    var html = '';
    var market = $('.marketplace_ids:checked').val();
    var node = $(this).val();
    var parentNodeName = $(this).find(":selected").text();
    nodeString += '<h3>'+parentNodeName+' ></h3>';
    nodeStringSimple += parentNodeName+' > ';

    $.ajax({
        url: 'index.php?route=openbay/amazon_listing/getBrowseNodes&token=<?php echo $token; ?>',
        type: 'POST',
        data: { marketplaceId: market, node: node},
        dataType: 'json',
        beforeSend: function(){
            $('#browseNodeFormContent select').remove();
            $('#browseNodeFormContent').append('<img src="view/image/loading.gif" alt="" />');
        },
        success: function(data) {
            if(data.node.error != true){
                if(data.node.final == 0){
                    html += '<select class="nodeSelect form-control">';
                    html += '<option value=""><?php echo $option_default; ?></option>';

                    $.each(data.children, function(k,v){
                        html += '<option value="'+ v.node_id+'">'+ v.name+'</option>';
                    });

                    html += '</select>';
                }else{
                    html += '<a onclick="saveNode('+data.node.id+')" class="btn btn-primary"><?php echo $save_button_text; ?></a>';
                }

                $('#browseNodeFormContent').html(nodeString+html);
            }else{
                alert(data.node.error);
                hideGreyScreen('browseNodeForm');
            }
        },
        failure: function(){
            alert('<?php echo $text_error_load_nodes; ?>');
            hideGreyScreen('browseNodeForm');
        },
        error: function(){
            alert('<?php echo $text_error_load_nodes; ?>');
            hideGreyScreen('browseNodeForm');
        }
    });
});

function saveNode(id){
    $('input[field_name='+nodeBox+']').val(id);
    $('input[field_name='+nodeBox+']').after('<span id="'+nodeBox+'_text" style="margin-left:15px;">'+nodeStringSimple+'</span>');
    hideGreyScreen('browseNodeForm');
}
//--></script>
<?php echo $footer; ?>